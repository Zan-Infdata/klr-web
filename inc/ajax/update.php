<?php

use klr\AjaxResponse;
use klr\AjaxManager;
use klr\APIUpdate;
use klr\SessionMgmt;

$req = AjaxResponse::request();
$element = $req['ELEM'] ?? null;
$md = $req['MD'];
if ($md === AjaxManager::MD_SERVICE_UPDATE) {
    list($element, $types) = AjaxResponse::serviceLocationRequest($req);
    AjaxResponse::mutation(APIUpdate::updateServiceLocation($element, $types), 0);
}
if ($md === AjaxManager::MD_SERVICE_DROP_OFF || $md === AjaxManager::MD_SERVICE_COLLECT) {
    $from = $req['PRNT_FROM'] ?? null;
    $to = $req['PRNT_TO'] ?? null;
    $items = is_string($req['ITEMS'] ?? null) ? json_decode($req['ITEMS']) : null;
    if (!AjaxResponse::uuid($from) || !AjaxResponse::uuid($to) || !is_array($items) || !$items) {
        AjaxResponse::error(400);
    }
    AjaxResponse::serviceBatch(APIUpdate::transferServiceEquipment($from, $to, $items,
        $md === AjaxManager::MD_SERVICE_COLLECT), count($items));
}
if ($md === AjaxManager::MD_EQ_TELEPORT && !SessionMgmt::isManagerAcl(SessionMgmt::getSyAcl())) {
    AjaxResponse::error(403);
}
if ($md === AjaxManager::MD_EQ_UPDATE || $md === AjaxManager::MD_LOC_UPDATE) {
    if (!AjaxResponse::record($element, $md === AjaxManager::MD_LOC_UPDATE)) { AjaxResponse::error(400); }
} elseif (!AjaxResponse::uuid($element)) { AjaxResponse::error(400); }
if (in_array($md, array(AjaxManager::MD_LOC_TRANS_MOVE, AjaxManager::MD_LOC_EQ_TAKE, AjaxManager::MD_LOC_EQ_LEAVE, AjaxManager::MD_EQ_TELEPORT), true)
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
    case AjaxManager::MD_EQ_TELEPORT:
        AjaxResponse::mutation(APIUpdate::teleportEquipment($element, $req['PRNT_FROM'], $req['PRNT_TO']), 0);
        break;
    case AjaxManager::MD_EQ_UPDATE:
        if (!AjaxResponse::positiveId($req['TYPE'] ?? null)
            || !AjaxResponse::positiveId($req['TYPE_SECONDARY'] ?? null)) { AjaxResponse::error(400); }
        $result = APIUpdate::updateEquipment($element, $req['TYPE'], $req['TYPE_SECONDARY']); break;
    case AjaxManager::MD_LOC_UPDATE:
        $result = APIUpdate::updateLocation($element); break;
    default:
        AjaxResponse::error(400);
}
AjaxResponse::mutation($result);
