<?php

    use klr\APIManager;
    use klr\CookieManager;
    use klr\Environment;
    use klr\JWTManager;
    use klr\Lang;
    use klr\ReqURI;
    use klr\SessionMgmt;


    $username = null;
    $password = null;


    $syUser = null;


    $loginTest = null;  // 1-no DB, 2-no user, 3-wrong pwd, 4-no parms, 5-reset account pwd
    $loginType = null; // 2-u&p, 3-remail


    $redirectURL = Environment::ROOT_URL . "/" . ReqURI::URLMAP_LOGN_KY ;


    $uInput = 'uinput';
    $pInput = 'pwdinput';



    if ($_SERVER['REQUEST_METHOD'] != "POST") {
        //LOGOUT
        SessionMgmt::destroy(); 
    }
    else {

        // bad parameters
        if (!isset($_POST[$uInput]) || !isset($_POST[$pInput])) {
            SessionMgmt::setRegister( SessionMgmt::AUTHERR_KY , Lang::getRsrc('login','errMsg4') );   // no parameters
        }
        else {
            
            $username = $_POST[$uInput];
            $password = $_POST[$pInput];

            $res = APIManager::callLogin($username, $password);

            // api call failed
            if ($res == null) {
                SessionMgmt::setRegister( SessionMgmt::AUTHERR_KY, Lang::getRsrc('login','errMsg1') ); // system error
            }
            else {

                $code = $res[APIManager::CODE];
                $data = $res[APIManager::DATA];

                // successful login
                if ($code == 200){
                    $token = $data[APIManager::TOKEN];
                    $rfToken = $data[APIManager::RF_TOKEN];
                    $verified = JWTManager::verifyJWT($rfToken);

                    // failed token read
                    if(!isset($verified)){
                        SessionMgmt::setRegister( SessionMgmt::AUTHERR_KY, Lang::getRsrc('login','errMsg1') ); // system error
                    }
                    else{
                        
                        CookieManager::setTokenCookie($rfToken);

                        $acl = SessionMgmt::getSyAclFromData($verified);
                        SessionMgmt::init($verified, $acl, $token);
                        
                        $redirectURL = Environment::ROOT_URL . "/" . ReqURI::URLMAP_PAGE_KY ;
                    }


                }
                // failed login
                else {
                    SessionMgmt::setRegister( SessionMgmt::AUTHERR_KY , $data[APIManager::MESSAGE] );
                    $loginTest = 2;
                }
                    
            }
        }
    }


    header("location:".$redirectURL);


?>