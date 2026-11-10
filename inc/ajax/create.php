<?php

use klr\AjaxResponse;
use klr\AjaxManager;
use klr\APICreate;

$req = AjaxResponse::request();
$element = $req['ELEM'] ?? null;
$parent = $req['PRNT'] ?? null;
if (!AjaxResponse::positiveId($parent)) { AjaxResponse::error(400); }

switch ($req['MD']) {
    case AjaxManager::MD_EQ_CREATE:
        if (!AjaxResponse::record($element) || !AjaxResponse::positiveId($req['TYPE'] ?? null)) { AjaxResponse::error(400); }
        $result = APICreate::createMdEq($element, $parent, $req['TYPE']);
        break;
    case AjaxManager::MD_TRANS_CREATE:
        if (!AjaxResponse::record($element, true)) { AjaxResponse::error(400); }
        $result = APICreate::createMdTrans($element, $parent);
        break;
    default:
        AjaxResponse::error(400);
}
AjaxResponse::mutation($result);
