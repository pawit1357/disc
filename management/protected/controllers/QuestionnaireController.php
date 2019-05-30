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

                if (isset($quest)) {

                    // generate word
                    $templateProcessor = new TemplateProcessor(ConfigUtil::getDiscTemplaePath());

                    $templateProcessor->setValue('name', $quest->person_name);
                    $templateProcessor->setImageValue('chart', array(
                        "path" => $saveImgpath,
                        "width" => 550,
                        "height" => 480
                    ));
                    $report_file_doc = $path . "/" . $phone_num . ".docx";
                    $report_file_pdf = $path . "/" . $phone_num . ".pdf";
                                        
                    $templateProcessor->saveAs($report_file_doc);

                    
                    
                    //export to pdf
                    // Make sure you have `dompdf/dompdf` in your composer dependencies.
                    Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
                    // Any writable directory here. It will be ignored.
                    Settings::setPdfRendererPath('.');
                    
                    $phpWord = IOFactory::load($report_file_doc, 'Word2007');
                    $phpWord->save($report_file_pdf, 'PDF');
                    
                    
                    
                    
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
                $this->_model = MTitle::model()->findbyPk($id);
            }
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }
}