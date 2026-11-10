<?php

use klr\AjaxResponse;
use klr\AjaxManager;
use klr\APIUpdate;

$req = AjaxResponse::request();
$element = $req['ELEM'] ?? null;
$md = $req['MD'];
if ($md === AjaxManager::MD_EQ_UPDATE || $md === AjaxManager::MD_LOC_UPDATE) {
    if (!AjaxResponse::record($element, $md === AjaxManager::MD_LOC_UPDATE)) { AjaxResponse::error(400); }
} elseif (!AjaxResponse::uuid($element)) { AjaxResponse::error(400); }
if (in_array($md, array(AjaxManager::MD_LOC_TRANS_MOVE, AjaxManager::MD_LOC_EQ_TAKE, AjaxManager::MD_LOC_EQ_LEAVE), true)
    && (!AjaxResponse::uuid($req['PRNT_FROM'] ?? null) || !AjaxResponse::uuid($req['PRNT_TO'] ?? null))) { AjaxResponse::error(400); }

switch ($md) {
    case AjaxManager::MD_LOC_TRANS_MOVE:
        $result = APIUpdate::moveTransport($element, $req['PRNT_FROM'], $req['PRNT_TO']); break;
    case AjaxManager::MD_LOC_TRANS_RETURN:
        $result = APIUpdate::returnTransport($element); break;
    case AjaxManager::MD_LOC_TRANS_TAKE:
        $result = APIUpdate::takeTransport($element); break;
    case AjaxManager::MD_LOC_EQ_TAKE:
        $result = APIUpdate::takeEquipment($element, $req['PRNT_FROM'], $req['PRNT_TO']); break;
    case AjaxManager::MD_LOC_EQ_LEAVE:
        $result = APIUpdate::leaveEquipment($element, $req['PRNT_FROM'], $req['PRNT_TO']); break;
    case AjaxManager::MD_EQ_UPDATE:
        if (!AjaxResponse::positiveId($req['TYPE'] ?? null)) { AjaxResponse::error(400); }
        $result = APIUpdate::updateEquipment($element, $req['TYPE']); break;
    case AjaxManager::MD_LOC_UPDATE:
        $result = APIUpdate::updateLocation($element); break;
    default:
        AjaxResponse::error(400);
}
AjaxResponse::mutation($result);
