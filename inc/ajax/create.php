<?php

use klr\AjaxResponse;
use klr\AjaxManager;
use klr\APICreate;
use klr\APIManager;
use klr\SessionMgmt;

$req = AjaxResponse::request();
if ($req['MD'] === AjaxManager::MD_SERVICE_CREATE) {
    list($element, $types) = AjaxResponse::serviceLocationRequest($req);
    AjaxResponse::mutation(APICreate::createServiceLocation($element, $types), 0);
}
if ($req['MD'] === AjaxManager::MD_CHECKLIST_CREATE) {
    if (!SessionMgmt::isManagerAcl(SessionMgmt::getSyAcl())) { AjaxResponse::error(403); }
    $warehouse = $req['WAREHOUSE'] ?? null;
    $assignee = $req['ASSIGNEE'] ?? null;
    $lines = is_string($req['LINES'] ?? null) ? json_decode($req['LINES']) : null;
    if (!AjaxResponse::positiveId($warehouse) || !AjaxResponse::positiveId($assignee)
        || !is_array($lines) || !$lines) { AjaxResponse::error(400); }

    $result = APICreate::createChecklist((int) $warehouse, (int) $assignee, $lines);
    $code = $result[APIManager::CODE] ?? 502;
    $body = $result[APIManager::DATA] ?? null;
    $data = $body[APIManager::DATA] ?? null;
    if ($code === 200) {
        if (($body[APIManager::ERR] ?? null) !== 0 || !is_int($data['id'] ?? null) || $data['id'] < 1) {
            AjaxResponse::error(502, true);
        }
        AjaxResponse::send(200, array('id' => $data['id']));
    }
    if ($code === 409 && ($body[APIManager::ERR] ?? null) === 1
        && in_array($data['reason'] ?? null, array('stock', 'warehouse', 'assignee'), true)
        && is_array($data['lineIndexes'] ?? null) && array_is_list($data['lineIndexes'])) {
        foreach ($data['lineIndexes'] as $index) {
            if (!is_int($index) || $index < 0 || $index >= count($lines)) { AjaxResponse::error(409); }
        }
        AjaxResponse::send(409, array('reason' => $data['reason'], 'lineIndexes' => $data['lineIndexes']));
    }
    AjaxResponse::error($code, $code >= 500 || ($result['outcome_unknown'] ?? false) === true);
}

$element = $req['ELEM'] ?? null;
$parent = $req['PRNT'] ?? null;
if (!AjaxResponse::positiveId($parent)) { AjaxResponse::error(400); }

switch ($req['MD']) {
    case AjaxManager::MD_EQ_CREATE:
        if (!AjaxResponse::record($element) || !AjaxResponse::positiveId($req['TYPE'] ?? null)
            || !AjaxResponse::positiveId($req['TYPE_SECONDARY'] ?? null)) { AjaxResponse::error(400); }
        if (($element['warranty_date'] ?? '') === '') { unset($element['warranty_date']); }
        $result = APICreate::createMdEq($element, $parent, $req['TYPE'], $req['TYPE_SECONDARY']);
        break;
    case AjaxManager::MD_TRANS_CREATE:
        if (!AjaxResponse::record($element, true)) { AjaxResponse::error(400); }
        $result = APICreate::createMdTrans($element, $parent);
        break;
    default:
        AjaxResponse::error(400);
}
AjaxResponse::mutation($result);
