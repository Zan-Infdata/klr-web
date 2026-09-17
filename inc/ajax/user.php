<?php

use klr\SessionMgmt;

use klr\AjaxManager;
use klr\APIRead;
use klr\APIManager;


	$data = array();

	if (isset($_POST['req'])){
		
		// read LIST ARRAY
		$req = $_POST['req'];
		
		// read BI VALUES
		$md = isset($req['MD']) ? $req['MD'] : null ;
		$element = isset($req['ELEM']) ? $req['ELEM'] : null ;

		

		switch ($md) {

        	case AjaxManager::LOGIN_AS_USER :
				$res = APIRead::getUserById($element);

				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					SessionMgmt::setAsUser($res[APIManager::DATA][APIManager::DATA]);
					$data['DATA'] = array(
						"success" => true
					);
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::LOGOUT_AS_USER :
					SessionMgmt::usetAsUser();
					$data['DATA'] = array(
						"success" => true
					);
					$data['CODE'] = 200;
				break;


        } // switch

    } // if


    echo json_encode($data);	

?>	