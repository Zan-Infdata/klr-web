<?php

use klr\ACLevel;
use klr\Lang;
use klr\ReqURI;
use klr\SessionMgmt;
use klr\APIUpdate;
use klr\APIManager;
use klr\AjaxManager;


//get acl level bit
// $aclbit = SessionMgmt::getAclBit();


$response = array();
$id = ReqURI::getID();
$syUser=SessionMgmt::getSyUser();


$data = array();

if (isset($_POST['req'])){

    $req = $_POST['req'];

    $md = isset($req['MD']) ? $req['MD'] : null ;
    $element = isset($req['ELEM']) ? $req['ELEM'] : null ;
    $parent_from = isset($req['PRNT_FROM']) ? $req['PRNT_FROM'] : null ;
    $parent_to = isset($req['PRNT_TO']) ? $req['PRNT_TO'] : null ;
    $type = isset($req['TYPE']) ? $req['TYPE'] : null ;
    

    //check if you got an element
    if ($element){

        //if(ACLevel::isSysAdmin($aclbit)){
            switch($md){

                case AjaxManager::MD_LOC_TRANS_MOVE:
                    $res = APIUpdate::moveTransport($element, $parent_from, $parent_to);
                    break;

                case AjaxManager::MD_LOC_TRANS_RETURN:
                    $res = APIUpdate::returnTransport($element);
                    break;

                case AjaxManager::MD_LOC_TRANS_TAKE:
                    $res = APIUpdate::takeTransport($element);
                    break;

                case AjaxManager::MD_LOC_EQ_TAKE:
                    $res = APIUpdate::takeEquipment($element, $parent_from, $parent_to);
                    break;

                case AjaxManager::MD_LOC_EQ_LEAVE:
                    $res = APIUpdate::leaveEquipment($element, $parent_from, $parent_to);
                    break;

                case AjaxManager::MD_EQ_UPDATE:
                    $res = APIUpdate::updateEquipment($element, $type);
                    break;

                case AjaxManager::MD_LOC_UPDATE:
                    $res = APIUpdate::updateLocation($element);
                    break;

            } 
        // authentication failed
        //} else {
        //    http_response_code(401);   // BAD request no POST parameters
        //    $responseMsg = Lang::getRsrc('ajax','alrt1') ;
        //    $responseCode = 1;
        //}
        
        $data['CODE'] = $res[APIManager::CODE];
        if ($res[APIManager::CODE] == 200) {
            $data['DATA'] = Lang::getRsrc('ajax','alrt0') ;
        }
        else {
            http_response_code($data["CODE"]);
            $data['DATA'] = Lang::getRsrc('ajax','alrt2') ;
        } 

    }






}

echo json_encode($data);


?>