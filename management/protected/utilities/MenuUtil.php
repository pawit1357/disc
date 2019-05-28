<?php
class MenuUtil {
	public static function getMenuByRole($currentPage) {
		if (! UserLoginUtils::isLogin ()) {
			header ( "Location: " . ConfigUtil::getSiteName () );
			die ();
		}
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $currentPage );
		$navMenu = '';
		$Menus = UserLoginUtils::getMenuInRole ();
		if (isset ( $Menus )) {
			foreach ( $Menus as $parent ) {
				if ($parent->PREVIOUS_MENU_ID == - 1) {
					
					$bActiveSelectedMenu = false;
					foreach ( $Menus as $child ) {
						if ($child->PREVIOUS_MENU_ID == $parent->MENU_ID) {
							// ---- end sub menu ----
							$isActive = false;
							if (0 === strpos ( $currentPage, $child->URL_NAVIGATE )) {
								$bActiveSelectedMenu = true;
								break;
							}
						}
					}
					/* - BEGIN ADD MAIN MENU - */
					$navMenu = $navMenu . "<li class=\"nav-item " . ($bActiveSelectedMenu == true ? "start active open" : "") . "  \">";
					$navMenu = $navMenu . "<a href=\"javascript:;\" class=\"nav-link nav-toggle\">";
					$navMenu = $navMenu . "<i class=\"" . $parent->MENU_ICON . "\"></i>";
					$navMenu = $navMenu . "<span class=\"title\">" . $parent->MENU_NAME . "</span>";
					$navMenu = $navMenu . "<span class=\"arrow\"></span>";
					$navMenu = $navMenu . "</a>";
					/* - BEGIN ADD SUBMENU - */
					$navMenu = $navMenu . "<ul class=\"sub-menu\">";
					foreach ( $Menus as $child ) {
						if ($child->PREVIOUS_MENU_ID == $parent->MENU_ID) {
							// ---- end sub menu ----
							$isActive = false;
							if (0 === strpos ( $currentPage, $child->URL_NAVIGATE )) {
								$isActive = true;
							}
							
							$navMenu = $navMenu . "<li class=\"nav-item " . ($isActive == true ? "start active open" : "") . " \">";
							$navMenu = $navMenu . "<a href=\"" . ($child->URL_NAVIGATE == "#" ? "javascript:;" : ConfigUtil::getAppName () . $child->URL_NAVIGATE) . "\" class=\"nav-link " . ($child->URL_NAVIGATE == "#" ? "nav-toggle" : "") . "\">";
							$navMenu = $navMenu . "<i class=\"" . $child->MENU_ICON . "\"></i>";
							
							$navMenu = $navMenu . "<span class=\"title\">" . $child->MENU_NAME . "</span>";
							$navMenu = $navMenu . "" . ($isActive == true ? '<span class=\" selected\"></span>' : '');
							// $navMenu = $navMenu . "" . ($isActive == true ? '<span class=\"arrow open\"></span>' : '');
							if ($child->URL_NAVIGATE == "#") {
								$navMenu = $navMenu . "<span class=\"arrow open\"></span>";
							}
							$navMenu = $navMenu . "</a>";
							
							if ($child->URL_NAVIGATE == "#") {
								$navMenu = $navMenu . "<ul class=\"sub-menu\">";
								foreach ( $Menus as $child_sub ) {
									if ($child_sub->PREVIOUS_MENU_ID == $child->MENU_ID) {
										$navMenu = $navMenu . "<li class=\"nav-item " . ($isActive == true ? "start active open" : "") . " \">";
										$navMenu = $navMenu . "<a href=\"" . ($child_sub->URL_NAVIGATE == "#" ? "javascript:;" : ConfigUtil::getAppName () . $child_sub->URL_NAVIGATE) . "\" class=\"nav-link " . ($child_sub->URL_NAVIGATE == "#" ? "nav-toggle" : "") . "\">";
										$navMenu = $navMenu . "<i class=\"" . $child_sub->MENU_ICON . "\"></i>";
										
										$navMenu = $navMenu . "<span class=\"title\">" . $child_sub->MENU_NAME . "</span>";
										$navMenu = $navMenu . "" . ($isActive == true ? '<span class=\" selected\"></span>' : '');
										if ($child_sub->URL_NAVIGATE == "#") {
											$navMenu = $navMenu . "<span class=\"arrow open\"></span>";
										}
										$navMenu = $navMenu . "</a>";
										// #########################################################//
										if ($child_sub->URL_NAVIGATE == "#") {
											$navMenu = $navMenu . "<ul class=\"sub-menu\">";
											foreach ( $Menus as $child_sub1 ) {
												if ($child_sub1->PREVIOUS_MENU_ID == $child_sub->MENU_ID) {
													$navMenu = $navMenu . "<li class=\"nav-item " . ($isActive == true ? "start active open" : "") . " \">";
													$navMenu = $navMenu . "<a href=\"" . ($child_sub1->URL_NAVIGATE == "#" ? "javascript:;" : ConfigUtil::getAppName () . $child_sub1->URL_NAVIGATE) . "\" class=\"nav-link " . ($child_sub1->URL_NAVIGATE == "#" ? "nav-toggle" : "") . "\">";
													$navMenu = $navMenu . "<i class=\"" . $child_sub1->MENU_ICON . "\"></i>";
													
													$navMenu = $navMenu . "<span class=\"title\">" . $child_sub1->MENU_NAME . "</span>";
													$navMenu = $navMenu . "" . ($isActive == true ? '<span class=\" selected\"></span>' : '');
													$navMenu = $navMenu . "</a>";
													$navMenu = $navMenu . "</li>";
												}
											}
											$navMenu = $navMenu . "</ul>";
										}
										// #########################################################//
										$navMenu = $navMenu . "</li>";
									}
								}
								$navMenu = $navMenu . "</ul>";
							}
							
							$navMenu = $navMenu . "</li>";
						}
					}
					$navMenu = $navMenu . "</ul>";
					/* - END SUBMENU - */
					$navMenu = $navMenu . "</li>";
					/* - END MAIN MENU - */
				}
			}
		}
		return $navMenu;
	}
	public static function getNavigator($currentPage) {
		if (strcmp ( $currentPage, "/" ) == 0) {
			header ( "Location: " . ConfigUtil::getSiteName () );
			die ();
		}
		
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $currentPage );
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $currentPage );
		$link = explode ( "/", $currentPage );
		$action = '';
		switch (count ( $link )) {
			// case 0 :echo "<font color='red'>:" . count ( $link ) . ":</font>";
			// break;
			// case 1 :echo "<font color='red'>:" . count ( $link ) . ":</font>";
			// break;
			// case 2 :echo "<font color='red'>:" . count ( $link ) . ":</font>";
			// break;
			case 3 :
				$currentPage = $link [2];
				break;
			case 4 :
				$currentPage = $link [2];
				$action = $link [3];
				break;
			case 6 :
				$currentPage = $link [2];
				$action = $link [3];
				break;
		}
		// echo "<font color='red'>:" . count ( $link ) . ',' . $currentPage . ',' . $action . ":</font>";
		
		$nav = "";
		$nav = $nav . "<li>";
		$nav = $nav . "<i class=\"fa fa-home\"></i>";
		$nav = $nav . "<a href=\"" . ConfigUtil::getAppName () . '/index.php/DashBoard' . "\">Home</a>";
		$nav = $nav . "</li>";
		$childs = $Menus = UserLoginUtils::getMenuInRole (); // Menu::model ()->findAll ( $criMenu );
		if (isset ( $childs )) {
			
			if ($currentPage == "Report") {
				$nav = $nav . "<li>";
				$nav = $nav . "<i class=\"fa fa-angle-right\"></i>";
				$nav = $nav . "<a href=\"#\">รายงาน</a>";
				$nav = $nav . "</li>";
				foreach ( $childs as $parent ) {
					$compare = explode ( "/", $parent->URL_NAVIGATE );
					if (strtolower ( $action ) == strtolower ( $compare [count ( $compare ) - 1] )) {
						$nav = $nav . "<li>";
						$nav = $nav . "<i class=\"fa fa-angle-right\"></i>";
						$nav = $nav . "<a href=\"#\">" . $parent->MENU_NAME . "</a>";
						$nav = $nav . "</li>";
						break;
					}
				}
			} else {
				foreach ( $childs as $child ) {
					
					$compare = explode ( "/", $child->URL_NAVIGATE );
					
					if (strtolower ( $currentPage ) == strtolower ( $compare [count ( $compare ) - 1] )) {
						
						foreach ( $childs as $parent ) {
							if ($parent->MENU_ID == $child->PREVIOUS_MENU_ID) {
								$nav = $nav . "<li>";
								$nav = $nav . "<i class=\"fa fa-angle-right\"></i>";
								$nav = $nav . "<a href=\"" . ConfigUtil::getAppName () . $child->URL_NAVIGATE . "\">" . $parent->MENU_NAME . "</a>";
								$nav = $nav . "</li>";
								break;
							}
						}
						
						$nav = $nav . "<li>";
						$nav = $nav . "<i class=\"fa fa-angle-right\"></i>";
						$nav = $nav . "<a href=\"#\">" . $child->MENU_NAME . "</a>";
						$nav = $nav . "</li>";
						break;
					}
				}
			}
		}
		
		return $nav;
	}
	public static function getMenuName($currentPage) {
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $currentPage );
		$link = explode ( "/", $currentPage );
		$action = '';
		switch (count ( $link )) {
			// case 0 :echo "<font color='red'>:" . count ( $link ) . ":</font>";
			// break;
			// case 1 :echo "<font color='red'>:" . count ( $link ) . ":</font>";
			// break;
			// case 2 :echo "<font color='red'>:" . count ( $link ) . ":</font>";
			// break;
			case 3 :
				$currentPage = $link [2];
				break;
			case 4 :
				$currentPage = $link [2];
				$action = $link [3];
				break;
			case 6 :
				$currentPage = $link [2];
				$action = $link [3];
				break;
		}
		// echo "<font color='red'>:" .count($link).','. $currentPage . ',' . $action . ":</font>";
		$menuName = "";
		
		$childs = $Menus = UserLoginUtils::getMenuInRole ();
		
		if (isset ( $childs )) {
			if ($currentPage == "Report") {
				foreach ( $childs as $child ) {
					$compare = explode ( "/", $child->URL_NAVIGATE );
					if (strtolower ( $action ) == strtolower ( $compare [count ( $compare ) - 1] )) {
						$menuName = "<i class=\"fa fa-pie-chart\"></i>&nbsp;<span class=\"caption-subject bold font-yellow-casablanca uppercase\">" . $child->MENU_NAME . "</span>";
						break;
					}
				}
			} else {
				foreach ( $childs as $child ) {
					
					$compare = explode ( "/", $child->URL_NAVIGATE );
					
					if (strtolower ( $currentPage ) == strtolower ( $compare [count ( $compare ) - 1] )) {
						
						$menuName = "<i class=\"fa fa-database\">&nbsp;</i><span class=\"caption-subject bold font-yellow-casablanca uppercase\">" . $child->MENU_NAME . "</span>";
						
						switch ($action) {
							case "Create" :
								$menuName = "<i class=\"fa fa-plus\"></i>&nbsp;<span class=\"caption-subject bold font-yellow-casablanca uppercase\">" . $child->MENU_NAME . "</span>" . " <span class=\"caption-helper\">(เพิ่มข้อมูล)</span>";
								break;
							case "Update" :
								$menuName = "<i class=\"fa fa-edit\"></i>&nbsp;<span class=\"caption-subject bold font-yellow-casablanca uppercase\">" . $child->MENU_NAME . "</span>" . " <span class=\"caption-helper\">(แก้ไขข้อมูล)</span>";
								break;
							case "View" :
								$menuName = "<i class=\"fa fa-television\"></i>&nbsp;<span class=\"caption-subject bold font-yellow-casablanca uppercase\">" . $child->MENU_NAME . "</span>" . " <span class=\"caption-helper\">(ดูข้อมูลที่บันทึก)</span>";
								break;
							// case "Report" :
							// $menuName = "<i class=\"fa fa-pie-chart\"></i> รายงาน" . $child->MENU_NAME;
							// break;
							case "Revision" :
								$menuName = "<i class=\"fa fa-clock-o\"></i>&nbsp;" . $child->MENU_NAME . "</span>" . " <span class=\"caption-helper\">(ประวัติการแก้ไขข้อมูล)</span>";
								break;
						}
						// }
					}
				}
			}
		}
		
		return $menuName;
	}
}