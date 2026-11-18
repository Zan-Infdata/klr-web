<?php

use klr\AjaxResponse;
use klr\AjaxManager;
use klr\APIManager;
use klr\APIRead;
use klr\SessionMgmt;

$req = AjaxResponse::request();
$context = SessionMgmt::capture();
if ($context === null) { AjaxResponse::error(401); }
if ($req['MD'] === AjaxManager::LOGIN_AS_USER) {
    if (!SessionMgmt::isManagerAcl(SessionMgmt::getSyAcl())) { AjaxResponse::error(403); }
    if (!AjaxResponse::positiveId($req['ELEM'] ?? null)) { AjaxResponse::error(400); }
    $result = AjaxResponse::validateRead(APIRead::getUserById($req['ELEM']), false);
    $user = $result[APIManager::DATA][APIManager::DATA];
    if (!isset($user['id']) || (string) $user['id'] !== (string) $req['ELEM']) { AjaxResponse::error(502); }
} elseif ($req['MD'] === AjaxManager::LOGOUT_AS_USER) {
    $user = null;
} else { AjaxResponse::error(400); }
if (!SessionMgmt::changeAsUser($context, $user)) { AjaxResponse::error(409); }
AjaxResponse::send(200, array('success' => true));
