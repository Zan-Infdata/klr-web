<?php

use klr\ACLevel;
use klr\Lang;
use klr\ReqURI;
use klr\SessionMgmt;
use klr\APIUpdate;
use klr\APIManager;
use klr\AjaxManager;
use klr\APICreate;

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
    $parent = isset($req['PRNT']) ? $req['PRNT'] : null ;
    $type = isset($req['TYPE']) ? $req['TYPE'] : null ;

    //check if you got an element
    if ($element){
        //if(ACLevel::isSysAdmin($aclbit)){
            switch($md){

                case AjaxManager::MD_EQ_CREATE:
                    
                        $res = APICreate::createMdEq($element, $parent, $type);
                    
                        $data['CODE'] = $res[APIManager::CODE];
                        if ($res[APIManager::CODE] == 200) {
                            $data['DATA'] = Lang::getRsrc('ajax','alrt0') ;
                        }
                        else {
                            $data['DATA'] = Lang::getRsrc('ajax','alrt2') ;
                        } 
                        
                        
                    break;

                case AjaxManager::MD_TRANS_CREATE:
                    
                        $res = APICreate::createMdTrans($element, $parent);
                    
                        $data['CODE'] = $res[APIManager::CODE];
                        if ($res[APIManager::CODE] == 200) {
                            $data['DATA'] = Lang::getRsrc('ajax','alrt0') ;
                        }
                        else {
                            $data['DATA'] = Lang::getRsrc('ajax','alrt2') ;
                        } 
                        
                        
                    break;
                        
            }
        //}
        // authentication failed
        //else {
        //    http_response_code(401);   // BAD request no POST parameters
        //    $responseMsg = Lang::getRsrc('ajax','alrt1') ;
        //    $responseCode = 1;
        //}

    }






}

echo json_encode($data);


?>