<?php

    use klr\APIManager;
    use klr\CookieManager;
    use klr\Csrf;
    use klr\Diagnostics;
    use klr\Environment;
    use klr\Lang;
    use klr\Output;
    use klr\ReqURI;
    use klr\SessionMgmt;

    $loginURL = Environment::ROOT_URL . '/' . ReqURI::URLMAP_LOGN_KY;
    $pageURL = Environment::ROOT_URL . '/' . ReqURI::URLMAP_PAGE_KY;
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'GET') { header('Location: ' . $loginURL, true, 302); exit(); }
    if ($method !== 'POST') {
        header('Allow: GET, POST');
        \klr\AjaxResponse::error(405);
    }
    $action = $_POST['action'] ?? null;
    if (!is_string($action) || !in_array($action, array('login', 'logout'), true)) { \klr\AjaxResponse::error(400); }
    if (!Csrf::valid()) {
        http_response_code(403);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><html lang="sl"><head><meta charset="utf-8"><title>Napaka</title></head><body><p>'
            . Output::html(Lang::getRsrc('ajax', 'csrfFailed')) . '</p></body></html>';
        exit();
    }
    if ($action === 'logout') {
        SessionMgmt::destroy();
        header('Location: ' . $loginURL, true, 303);
        exit();
    }
    // Changing authenticated identity requires an explicit logout first.
    if (SessionMgmt::checkAuth(false)) { \klr\AjaxResponse::error(409); }
    if (!isset($_POST['uinput'], $_POST['pwdinput']) || !is_string($_POST['uinput']) || !is_string($_POST['pwdinput'])) {
        SessionMgmt::setRegister(SessionMgmt::AUTHERR_KY, Lang::getRsrc('login', 'errMsg4'));
    } else {
        $result = APIManager::callLogin($_POST['uinput'], $_POST['pwdinput']);
        $validated = APIManager::validateAuthResponse($result);
        if ($validated !== null && SessionMgmt::init($validated[APIManager::USER_KY], $validated[APIManager::USER_KY][APIManager::ACL_KY], $validated[APIManager::TOKEN])) {
            if (CookieManager::setTokenCookie($validated[APIManager::RF_TOKEN])) {
                Diagnostics::event('login_succeeded', array('status' => 200));
                header('Location: ' . $pageURL, true, 303);
                exit();
            }
            SessionMgmt::destroy();
        } else {
            $status = $result[APIManager::CODE] ?? 502;
            Diagnostics::event('login_failed', array('status' => $status));
            SessionMgmt::setRegister(SessionMgmt::AUTHERR_KY, Lang::getRsrc('login', in_array($status, array(401, 404), true) ? 'errMsg2' : 'errMsg1'));
        }
    }
    header('Location: ' . $loginURL, true, 303);
    exit();
