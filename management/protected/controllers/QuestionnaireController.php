<?php
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class QuestionnaireController extends CController
{

    public $layout = '_main';

    private $_model;

    public function actionIndex()
    {
        // Authen Login
        if (! UserLoginUtils::isLogin()) {
            $this->redirect(Yii::app()->createUrl('Site/login'));
        }
        if (! UserLoginUtils::authorizePage($_SERVER['REQUEST_URI'])) {
            $this->redirect(Yii::app()->createUrl('DashBoard/Permission'));
        }
        $model = new Questionnaire();
        $this->render('//questionnaire/main', array(
            'data' => $model
        ));
    }

    public function actionCreate()
    {
        // Authen Login
        if (! UserLoginUtils::isLogin()) {
            $this->redirect(Yii::app()->createUrl('Site/login'));
        }
        if (! UserLoginUtils::authorizePage($_SERVER['REQUEST_URI'])) {
            $this->redirect(Yii::app()->createUrl('DashBoard/Permission'));
        }

        if (isset($_POST['Questionnaire'])) {

            $transaction = Yii::app()->db->beginTransaction();
            // Add Request
            $model = new Questionnaire();
            $model->attributes = $_POST['Questionnaire'];

            $model->save();
            // echo "SAVE";
            $transaction->commit();

            // $transaction->rollback ();
            $this->redirect(Yii::app()->createUrl('Questionnaire'));
        } else {
            // Render
            $this->render('//questionnaire/create');
        }
    }

    public function actionResult()
    {
        // Authen Login
        if (! UserLoginUtils::isLogin()) {
            $this->redirect(Yii::app()->createUrl('Site/login'));
        }
        if (! UserLoginUtils::authorizePage($_SERVER['REQUEST_URI'])) {
            $this->redirect(Yii::app()->createUrl('DashBoard/Permission'));
        }

        // /
        $person_phone_num = addslashes($_GET['person_phone_num']);

        $criteria = new CDbCriteria();
        $criteria->condition = "person_phone_num='" . $person_phone_num . "'";

        $result = QuestionnaireResult::model()->findAll($criteria);

        $this->render('//questionnaire/result', array(
            'data' => $result
        ));
    }

    public function actionPrint()
    {
        // Authen Login
        if (! UserLoginUtils::isLogin()) {
            $this->redirect(Yii::app()->createUrl('Site/login'));
        }
        if (! UserLoginUtils::authorizePage($_SERVER['REQUEST_URI'])) {
            $this->redirect(Yii::app()->createUrl('DashBoard/Permission'));
        }

        $image_url = $_GET['charts'];
        $phone_num = $_GET['phone_num'];

        if (isset($image_url)) {

            $imgURL = ConfigUtil::getHightChartExportURL() . $image_url;

            $path = getcwd() . "/uploads/" . date('Y/m/d');
            if (! file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $saveImgpath = $path . "/" . $phone_num . ".png";
            if (! file_exists($saveImgpath)) {
                // Image path
                $ch = curl_init($imgURL);
                $fp = fopen($saveImgpath, 'wb');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);
            }
            if (file_exists($saveImgpath)) {

                $quest = Questionnaire::model()->findByAttributes(array(
                    'person_phone_num' => $phone_num
                ));

                $sql = "
SELECT
(CASE
    WHEN type = 'D' THEN 1
    WHEN type = 'I' THEN 2
    WHEN type = 'S' THEN 3
    WHEN type = 'C' THEN 4
    END) AS SEQ,
    type,
(CASE
        WHEN type = 'D' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'M' AND D <> '' AND D <= M)
        WHEN type = 'I' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'M' AND I <> '' AND I <= M)
        WHEN type = 'S' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'M' AND S <> '' AND S <= M)
        WHEN type = 'C' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'M' AND C <> '' AND C <= M)
        END) AS M,
(CASE
            WHEN type = 'D' THEN 28 - (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'L' AND D <> '' AND D <= L)
            WHEN type = 'I' THEN 28 - (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'L' AND I <> '' AND I <= L)
            WHEN type = 'S' THEN 28 - (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'L' AND S <> '' AND S <= L)
            WHEN type = 'C' THEN 28 - (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'L' AND C <> '' AND C <= L)
            END) AS L,
(CASE
                WHEN type = 'D' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'O' AND D <> '' AND D <= A)
                WHEN type = 'I' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'O' AND I <> '' AND I <= A)
                WHEN type = 'S' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'O' AND S <> '' AND S <= A)
                WHEN type = 'C' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'O' AND C <> '' AND C <= A)
                END) AS A
FROM questionnaire_result WHERE person_phone_num = '" . $phone_num . "' AND type <> '#' ORDER BY SEQ";

                $list = Yii::app()->db->createCommand($sql)->queryAll();
                // echo ':::'.$list[0]['type'];

                if (isset($quest)) {

                    // generate word
                    $templateProcessor = new TemplateProcessor(ConfigUtil::getDiscTemplaePath());
                    $templateProcessor->setValue('name', $quest->person_name);

                    // #Actual
                    // D – Dominance (มิติของความมีอำนาจเหนือผู้อื่น)
                    list ($ActualD01, $ActualD02, $ActualD03, $ActualD04, $ActualD05) = split('#', DISCUtils::getDesc2($list[0]['type'], $list[0]['A']));
                    $templateProcessor->setValue('ActualD01_', split(',', $ActualD01)[0]);
                    $templateProcessor->setValue('ActualD02_', split(',', $ActualD02)[0]);
                    $templateProcessor->setValue('ActualD03_', split(',', $ActualD03)[0]);
                    $templateProcessor->setValue('ActualD04_', split(',', $ActualD04)[0]);
                    $templateProcessor->setValue('ActualD05_', split(',', $ActualD05)[0]);

                    $templateProcessor->setValue('ActualD01', split(',', $ActualD01)[1]);
                    $templateProcessor->setValue('ActualD02', split(',', $ActualD02)[1]);
                    $templateProcessor->setValue('ActualD03', split(',', $ActualD03)[1]);
                    $templateProcessor->setValue('ActualD04', split(',', $ActualD04)[1]);
                    $templateProcessor->setValue('ActualD05', split(',', $ActualD05)[1]);
                    // I – Influence (มิติของความสามารถในการโน้มน้าวผู้อื่น)
                    list ($ActualI01, $ActualI02, $ActualI03, $ActualI04, $ActualI05) = split('#', DISCUtils::getDesc2($list[1]['type'], $list[1]['A']));
                    $templateProcessor->setValue('ActualI01_', split(',', $ActualI01)[0]);
                    $templateProcessor->setValue('ActualI02_', split(',', $ActualI02)[0]);
                    $templateProcessor->setValue('ActualI03_', split(',', $ActualI03)[0]);
                    $templateProcessor->setValue('ActualI04_', split(',', $ActualI04)[0]);
                    $templateProcessor->setValue('ActualI05_', split(',', $ActualI05)[0]);

                    $templateProcessor->setValue('ActualI01', split(',', $ActualI01)[1]);
                    $templateProcessor->setValue('ActualI02', split(',', $ActualI02)[1]);
                    $templateProcessor->setValue('ActualI03', split(',', $ActualI03)[1]);
                    $templateProcessor->setValue('ActualI04', split(',', $ActualI04)[1]);
                    $templateProcessor->setValue('ActualI05', split(',', $ActualI05)[1]);

                    // S - Steadiness (มิติของความมั่นคงสม่ำเสมอ)
                    list ($ActualS01, $ActualS02, $ActualS03, $ActualS04, $ActualS05) = split('#', DISCUtils::getDesc2($list[2]['type'], $list[2]['A']));
                    $templateProcessor->setValue('ActualS01_', split(',', $ActualS01)[0]);
                    $templateProcessor->setValue('ActualS02_', split(',', $ActualS02)[0]);
                    $templateProcessor->setValue('ActualS03_', split(',', $ActualS03)[0]);
                    $templateProcessor->setValue('ActualS04_', split(',', $ActualS04)[0]);
                    $templateProcessor->setValue('ActualS05_', split(',', $ActualS05)[0]);

                    $templateProcessor->setValue('ActualS01', split(',', $ActualS01)[1]);
                    $templateProcessor->setValue('ActualS02', split(',', $ActualS02)[1]);
                    $templateProcessor->setValue('ActualS03', split(',', $ActualS03)[1]);
                    $templateProcessor->setValue('ActualS04', split(',', $ActualS04)[1]);
                    $templateProcessor->setValue('ActualS05', split(',', $ActualS05)[1]);

                    // C – Conscientiousness (มิติของความะละเอียดมีระเบียบแบบแผน)
                    list ($ActualC01, $ActualC02, $ActualC03, $ActualC04, $ActualC05) = split('#', DISCUtils::getDesc2($list[3]['type'], $list[3]['A']));
                    $templateProcessor->setValue('ActualC01_', split(',', $ActualC01)[0]);
                    $templateProcessor->setValue('ActualC02_', split(',', $ActualC02)[0]);
                    $templateProcessor->setValue('ActualC03_', split(',', $ActualC03)[0]);
                    $templateProcessor->setValue('ActualC04_', split(',', $ActualC04)[0]);
                    $templateProcessor->setValue('ActualC05_', split(',', $ActualC05)[0]);

                    $templateProcessor->setValue('ActualC01', split(',', $ActualC01)[1]);
                    $templateProcessor->setValue('ActualC02', split(',', $ActualC02)[1]);
                    $templateProcessor->setValue('ActualC03', split(',', $ActualC03)[1]);
                    $templateProcessor->setValue('ActualC04', split(',', $ActualC04)[1]);
                    $templateProcessor->setValue('ActualC05', split(',', $ActualC05)[1]);

                    // #Most
                    // D – Dominance (มิติของความมีอำนาจเหนือผู้อื่น)
                    list ($MostD01, $MostD02, $MostD03, $MostD04, $MostD05) = split('#', DISCUtils::getDesc2($list[0]['type'], $list[0]['M']));
                    $templateProcessor->setValue('MostD01_', split(',', $MostD01)[0]);
                    $templateProcessor->setValue('MostD02_', split(',', $MostD02)[0]);
                    $templateProcessor->setValue('MostD03_', split(',', $MostD03)[0]);
                    $templateProcessor->setValue('MostD04_', split(',', $MostD04)[0]);
                    $templateProcessor->setValue('MostD05_', split(',', $MostD05)[0]);

                    $templateProcessor->setValue('MostD01', split(',', $MostD01)[1]);
                    $templateProcessor->setValue('MostD02', split(',', $MostD02)[1]);
                    $templateProcessor->setValue('MostD03', split(',', $MostD03)[1]);
                    $templateProcessor->setValue('MostD04', split(',', $MostD04)[1]);
                    $templateProcessor->setValue('MostD05', split(',', $MostD05)[1]);
                    // I – Influence (มิติของความสามารถในการโน้มน้าวผู้อื่น)
                    list ($MostI01, $MostI02, $MostI03, $MostI04, $MostI05) = split('#', DISCUtils::getDesc2($list[1]['type'], $list[1]['M']));
                    $templateProcessor->setValue('MostI01_', split(',', $MostI01)[0]);
                    $templateProcessor->setValue('MostI02_', split(',', $MostI02)[0]);
                    $templateProcessor->setValue('MostI03_', split(',', $MostI03)[0]);
                    $templateProcessor->setValue('MostI04_', split(',', $MostI04)[0]);
                    $templateProcessor->setValue('MostI05_', split(',', $MostI05)[0]);

                    $templateProcessor->setValue('MostI01', split(',', $MostI01)[1]);
                    $templateProcessor->setValue('MostI02', split(',', $MostI02)[1]);
                    $templateProcessor->setValue('MostI03', split(',', $MostI03)[1]);
                    $templateProcessor->setValue('MostI04', split(',', $MostI04)[1]);
                    $templateProcessor->setValue('MostI05', split(',', $MostI05)[1]);

                    // S - Steadiness (มิติของความมั่นคงสม่ำเสมอ)
                    list ($MostS01, $MostS02, $MostS03, $MostS04, $MostS05) = split('#', DISCUtils::getDesc2($list[2]['type'], $list[2]['M']));
                    $templateProcessor->setValue('MostS01_', split(',', $MostS01)[0]);
                    $templateProcessor->setValue('MostS02_', split(',', $MostS02)[0]);
                    $templateProcessor->setValue('MostS03_', split(',', $MostS03)[0]);
                    $templateProcessor->setValue('MostS04_', split(',', $MostS04)[0]);
                    $templateProcessor->setValue('MostS05_', split(',', $MostS05)[0]);

                    $templateProcessor->setValue('MostS01', split(',', $MostS01)[1]);
                    $templateProcessor->setValue('MostS02', split(',', $MostS02)[1]);
                    $templateProcessor->setValue('MostS03', split(',', $MostS03)[1]);
                    $templateProcessor->setValue('MostS04', split(',', $MostS04)[1]);
                    $templateProcessor->setValue('MostS05', split(',', $MostS05)[1]);

                    // C – Conscientiousness (มิติของความะละเอียดมีระเบียบแบบแผน)
                    list ($MostC01, $MostC02, $MostC03, $MostC04, $MostC05) = split('#', DISCUtils::getDesc2($list[3]['type'], $list[3]['M']));
                    $templateProcessor->setValue('MostC01_', split(',', $MostC01)[0]);
                    $templateProcessor->setValue('MostC02_', split(',', $MostC02)[0]);
                    $templateProcessor->setValue('MostC03_', split(',', $MostC03)[0]);
                    $templateProcessor->setValue('MostC04_', split(',', $MostC04)[0]);
                    $templateProcessor->setValue('MostC05_', split(',', $MostC05)[0]);

                    $templateProcessor->setValue('MostC01', split(',', $MostC01)[1]);
                    $templateProcessor->setValue('MostC02', split(',', $MostC02)[1]);
                    $templateProcessor->setValue('MostC03', split(',', $MostC03)[1]);
                    $templateProcessor->setValue('MostC04', split(',', $MostC04)[1]);
                    $templateProcessor->setValue('MostC05', split(',', $MostC05)[1]);

                    // #Least
                    // D – Dominance (มิติของความมีอำนาจเหนือผู้อื่น)
                    list ($LeastD01, $LeastD02, $LeastD03, $LeastD04, $LeastD05) = split('#', DISCUtils::getDesc2($list[0]['type'], $list[0]['L']));
                    $templateProcessor->setValue('LeastD01_', split(',', $LeastD01)[0]);
                    $templateProcessor->setValue('LeastD02_', split(',', $LeastD02)[0]);
                    $templateProcessor->setValue('LeastD03_', split(',', $LeastD03)[0]);
                    $templateProcessor->setValue('LeastD04_', split(',', $LeastD04)[0]);
                    $templateProcessor->setValue('LeastD05_', split(',', $LeastD05)[0]);

                    $templateProcessor->setValue('LeastD01', split(',', $LeastD01)[1]);
                    $templateProcessor->setValue('LeastD02', split(',', $LeastD02)[1]);
                    $templateProcessor->setValue('LeastD03', split(',', $LeastD03)[1]);
                    $templateProcessor->setValue('LeastD04', split(',', $LeastD04)[1]);
                    $templateProcessor->setValue('LeastD05', split(',', $LeastD05)[1]);

                    // I – Influence (มิติของความสามารถในการโน้มน้าวผู้อื่น)
                    list ($LeastI01, $LeastI02, $LeastI03, $LeastI04, $LeastI05) = split('#', DISCUtils::getDesc2($list[1]['type'], $list[1]['L']));
                    $templateProcessor->setValue('LeastI01_', split(',', $LeastI01)[0]);
                    $templateProcessor->setValue('LeastI02_', split(',', $LeastI02)[0]);
                    $templateProcessor->setValue('LeastI03_', split(',', $LeastI03)[0]);
                    $templateProcessor->setValue('LeastI04_', split(',', $LeastI04)[0]);
                    $templateProcessor->setValue('LeastI05_', split(',', $LeastI05)[0]);

                    $templateProcessor->setValue('LeastI01', split(',', $LeastI01)[1]);
                    $templateProcessor->setValue('LeastI02', split(',', $LeastI02)[1]);
                    $templateProcessor->setValue('LeastI03', split(',', $LeastI03)[1]);
                    $templateProcessor->setValue('LeastI04', split(',', $LeastI04)[1]);
                    $templateProcessor->setValue('LeastI05', split(',', $LeastI05)[1]);

                    // S – Steadiness (มิติของความมั่นคงสม่ำเสมอ)
                    list ($LeastS01, $LeastS02, $LeastS03, $LeastS04, $LeastS05) = split('#', DISCUtils::getDesc2($list[2]['type'], $list[2]['L']));
                    $templateProcessor->setValue('LeastS01_', split(',', $LeastS01)[0]);
                    $templateProcessor->setValue('LeastS02_', split(',', $LeastS02)[0]);
                    $templateProcessor->setValue('LeastS03_', split(',', $LeastS03)[0]);
                    $templateProcessor->setValue('LeastS04_', split(',', $LeastS04)[0]);
                    $templateProcessor->setValue('LeastS05_', split(',', $LeastS05)[0]);

                    $templateProcessor->setValue('LeastS01', split(',', $LeastS01)[1]);
                    $templateProcessor->setValue('LeastS02', split(',', $LeastS02)[1]);
                    $templateProcessor->setValue('LeastS03', split(',', $LeastS03)[1]);
                    $templateProcessor->setValue('LeastS04', split(',', $LeastS04)[1]);
                    $templateProcessor->setValue('LeastS05', split(',', $LeastS05)[1]);

                    // C – Conscientiousness (มิติของความะละเอียดมีระเบียบแบบแผน)
                    list ($LeastC01, $LeastC02, $LeastC03, $LeastC04, $LeastC05) = split('#', DISCUtils::getDesc2($list[3]['type'], $list[3]['L']));
                    $templateProcessor->setValue('LeastC01_', split(',', $LeastC01)[0]);
                    $templateProcessor->setValue('LeastC02_', split(',', $LeastC02)[0]);
                    $templateProcessor->setValue('LeastC03_', split(',', $LeastC03)[0]);
                    $templateProcessor->setValue('LeastC04_', split(',', $LeastC04)[0]);
                    $templateProcessor->setValue('LeastC05_', split(',', $LeastC05)[0]);

                    $templateProcessor->setValue('LeastC01', split(',', $LeastC01)[1]);
                    $templateProcessor->setValue('LeastC02', split(',', $LeastC02)[1]);
                    $templateProcessor->setValue('LeastC03', split(',', $LeastC03)[1]);
                    $templateProcessor->setValue('LeastC04', split(',', $LeastC04)[1]);
                    $templateProcessor->setValue('LeastC05', split(',', $LeastC05)[1]);

                    $templateProcessor->setImageValue('chart', array(
                        "path" => $saveImgpath,
                        "width" => 550,
                        "height" => 480
                    ));
                    $report_file_doc = $path . "/" . $phone_num . ".docx";
//                     $report_file_pdf = $path . "/" . $phone_num . ".pdf";

                    $templateProcessor->saveAs($report_file_doc);

                    // The following offers file to user on client side: deletes temp version of file
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename='.$phone_num.'.docx');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($report_file_doc));
                    flush();
                    readfile($report_file_doc);
                    unlink($report_file_doc); // deletes the temporary file
                    
                    
                    // export to pdf
                    // Make sure you have `dompdf/dompdf` in your composer dependencies.
//                     Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
//                     // Any writable directory here. It will be ignored.
//                     Settings::setPdfRendererPath('.');

//                     $phpWord = IOFactory::load($report_file_doc, 'Word2007');
//                     $phpWord->save($report_file_pdf, 'PDF');

                    echo date('H:i:s'), ' Saving the result document...', EOL;
                }
            } else {
                echo date('H:i:s'), ' Can not generate file', EOL;
            }

            // $this->render ( '//questionnaire/print' );
        }
    }

    public function loadModel()
    {
        if ($this->_model === null) {
            if (isset($_GET['id'])) {
                $id = addslashes($_GET['id']);
                $this->_model = Questionnaire::model()->findbyPk($id);
            }
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }
}