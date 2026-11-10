<?php 

    require_once(__DIR__ . '/lib/errors.php');
    \klr\AppErrors::start();
    \klr\AppErrors::phase('configuration');
    require_once(__DIR__ . '/lib/klrlib.php');
    \klr\AppErrors::configure(\klr\Diagnostics::isDebug());
    \klr\AppErrors::phase('session');

    // Apply one explicit policy before starting the PHP session.
    \klr\ReqURI::readURI($_SERVER['REQUEST_URI']);
    \klr\SessionMgmt::start(!\klr\ReqURI::isRootAuth() && !\klr\ReqURI::isRootAjax());

    // Requests queued at a rotated ID must not establish competing sessions or overwrite newer cookies.
    if (\klr\SessionMgmt::isRotated()) {
        \klr\SessionMgmt::close();
        \klr\Lang::setLangCode(\klr\LangCode::SLO);
        if (str_starts_with(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/ajax/')) { \klr\AjaxResponse::error(401); }
        http_response_code(409);
        include_once('login.php');
        exit();
    }


    use klr\APIManager;
    use klr\CookieManager;
    use klr\LangCode;
    use klr\Lang;
    use klr\NumberHandler;

    use klr\ReqURI;
    use klr\SessionMgmt;

    \klr\AppErrors::phase('localization');
	// localisation
	Lang::setLangCode(LangCode::SLO);
	NumberHandler::setFmtDec( Lang::getRsrc('format','dec') ) ;
	NumberHandler::setFmtSep( Lang::getRsrc('format','sep') ) ;
	NumberHandler::setFmtCurr( Lang::getRsrc('format','curr') ) ;
	
    \klr\AppErrors::phase('dispatch');
	// READ REQUEST URI call
    // Route was parsed before deciding whether the session may issue a cookie.

    \klr\Diagnostics::event('request', array('endpoint' => ReqURI::$reqArray[\klr\URIinx::ROOT] ?? ''));

    // Auth POSTs are validated before idle renewal or remembered-login restoration.
    if (ReqURI::isRootAuth()) { include_once(ReqURI::$rootMap[ReqURI::URLMAP_AUTH_KY]); exit(); }
    if ((ReqURI::$reqArray[\klr\URIinx::ROOT] ?? null) === ReqURI::URLMAP_LOGN_KY) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') { header('Allow: GET'); \klr\AjaxResponse::error(405); }
        if (SessionMgmt::checkAuth(false)) { header('Location: /page', true, 302); exit(); }
        include_once(ReqURI::$rootMap[ReqURI::URLMAP_LOGN_KY]);
        exit();
    }

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

                    $validated = APIManager::validateAuthResponse($token, CookieManager::getTokenCookie());
                    if ($validated !== null && SessionMgmt::init($validated[APIManager::USER_KY], $validated[APIManager::USER_KY][APIManager::ACL_KY], $validated[APIManager::TOKEN])) {
                        // Reissue legacy cookies with the new flags and the same signed expiry.
                        if (CookieManager::setTokenCookie($validated[APIManager::RF_TOKEN])) {
                            include_once(ReqURI::getRootFile(true));
                        } else {
                            SessionMgmt::destroy();
                            include_once(ReqURI::$rootMap[ReqURI::URLMAP_LOGN_KY]);
                        }
                    }
                    else {
                        \klr\Diagnostics::event('refresh_failed', array('status' => is_array($token) ? ($token[APIManager::CODE] ?? 502) : 502));
                        if (is_array($token) && in_array($token[APIManager::CODE] ?? null, array(401, 403), true)) { SessionMgmt::destroy(); }
                        $status = is_array($token) ? ($token[APIManager::CODE] ?? 502) : 502;
                        if (!in_array($status, array(401, 403), true)) {
                            http_response_code($status === 504 ? 504 : 502);
                            SessionMgmt::setRegister(SessionMgmt::AUTHERR_KY, Lang::getRsrc('ajax', $status === 504 ? 'timeout' : 'unavailable'));
                        }
                        include_once(ReqURI::$rootMap[ReqURI::URLMAP_LOGN_KY]);
                    }
                    
                }
                else {
                    if (isset($_COOKIE[CookieManager::COOKIE_NAME])) { CookieManager::deleteTokenCookie(); }
                    include_once( ReqURI::$rootMap[ReqURI::URLMAP_LOGN_KY] );
                }


            } // else

        } // else

    } // else

	
?>