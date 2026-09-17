<?php 

    ini_set('display_errors', 1);
    error_reporting(E_ALL);


    //Init the session
    session_start();

    require_once('lib/klrlib.php');

    use klr\APIManager;
    use klr\CookieManager;
    use klr\LangCode;
    use klr\Lang;
    use klr\NumberHandler;

    use klr\ReqURI;
    use klr\SessionMgmt;

	// localisation
	Lang::setLangCode(LangCode::SLO);
	NumberHandler::setFmtDec( Lang::getRsrc('format','dec') ) ;
	NumberHandler::setFmtSep( Lang::getRsrc('format','sep') ) ;
	NumberHandler::setFmtCurr( Lang::getRsrc('format','curr') ) ;
	
	// READ REQUEST URI call
    ReqURI::readURI($_SERVER['REQUEST_URI']) ;

    error_log("CALLED " . $_SERVER['REQUEST_URI'] );

    //check if is img call
    if (ReqURI::isRootImg()){
        //check authorization inside AJAX handling file
        include_once( ReqURI::$rootMap[ReqURI::URLMAP_IMG_KY] );
    } // if
    //check if is ajax
    else if (ReqURI::isRootAjax()){
        //check authorization inside AJAX handling file
        include_once( ReqURI::$rootMap[ReqURI::URLMAP_AJAX_KY] );
    } // if

    else {
        //check authorization
        if (SessionMgmt::checkAuth(true)){

            error_log("COOKIE TOKEN-- ".print_r(CookieManager::getTokenCookie(),true));
            //default PAGE file
            include_once(ReqURI::getRootFile(true));

        } // if
        else {
            //check if is AUTH
            if (ReqURI::isRootAuth()){
                include_once( ReqURI::$rootMap[ReqURI::URLMAP_AUTH_KY] );
            } // ifSessionMgmt
            else {
                // everything else to login    
                $validCookie = CookieManager::isTokenCookieValid();

                if ($validCookie !== null){
                    $token = APIManager::callRefresh();

                    if ($token[APIManager::DATA][APIManager::ERR]){
                        CookieManager::deleteTokenCookie();
                        include_once( ReqURI::$rootMap[ReqURI::URLMAP_LOGN_KY] );
                    }
                    else {
                        $ac_token = $token[APIManager::DATA][APIManager::TOKEN];
                        $acl = SessionMgmt::getSyAclFromData($validCookie);
                        SessionMgmt::init($validCookie, $acl, $ac_token);
                        include_once(ReqURI::getRootFile(true));
                    }
                    
                }
                else {
                    include_once( ReqURI::$rootMap[ReqURI::URLMAP_LOGN_KY] );
                }


            } // else

        } // else

    } // else

	
?>