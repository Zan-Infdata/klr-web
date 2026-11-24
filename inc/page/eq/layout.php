<?php

use klr\Environment;
use klr\AjaxManager;
use klr\Lang;
use klr\IDConstants;
use klr\Output;
use klr\SessionMgmt;

$canTeleport = SessionMgmt::isManagerAcl(SessionMgmt::getSyAcl());
$teleportLabels = array();
if ($canTeleport) {
    foreach (array('changeLocation', 'warehouse', 'site', 'destination', 'choose', 'submit', 'cancel',
        'noDestinations', 'success', 'refreshError', 'stale', 'reload', 'pending') as $key) {
        $teleportLabels[$key] = Lang::getRsrc('equipmentTeleport', $key);
    }
}

$layoutLabels = array();
foreach (array('overview', 'location', 'transport', 'transports', 'equipment', 'atLocation', 'onTransport', 'viewEquipment', 'taken', 'parked', 'takenBy', 'unknownUser', 'unknownLocation', 'unknownStatus', 'unknownType', 'noTransports', 'noLocations', 'overviewCount', 'nameSearch', 'type', 'allTypes', 'clear', 'resultCount', 'noEquipment', 'noMatches', 'loading', 'loadError', 'retry', 'close') as $key) {
    $layoutLabels[$key] = Lang::getRsrc('equipmentLayout', $key);
}

echo "<div class='equipment-layout'>";
echo "    <div class='d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4'>";
echo "        <div>";
echo "            <h4 class='mb-1'>".htmlspecialchars($layoutLabels['overview'], ENT_QUOTES, 'UTF-8')."</h4>";
echo "            <p id='layout-state' class='text-sm text-secondary mb-0' role='status' aria-live='polite'>".htmlspecialchars($layoutLabels['loading'], ENT_QUOTES, 'UTF-8')."</p>";
echo "        </div>";
echo "        <button id='layout-retry' type='button' class='btn btn-outline-secondary btn-sm mb-0 d-none'>".htmlspecialchars($layoutLabels['retry'], ENT_QUOTES, 'UTF-8')."</button>";
echo "    </div>";
echo "    <div id='layout-locations' class='row g-4'></div>";
echo "</div>";
echo "<div class='modal fade equipment-layout-modal' id='layout-equipment-modal' tabindex='-1' aria-labelledby='layout-modal-title' aria-describedby='layout-modal-context' aria-hidden='true'>";
echo "    <div class='modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable'>";
echo "        <div class='modal-content'>";
echo "            <div class='modal-header align-items-start'>";
echo "                <div class='min-width-0'>";
echo "                    <h5 id='layout-modal-title' class='modal-title'>".htmlspecialchars($layoutLabels['equipment'], ENT_QUOTES, 'UTF-8')."</h5>";
echo "                    <p id='layout-modal-context' class='text-sm text-secondary mb-0'></p>";
echo "                </div>";
echo "                <button type='button' class='btn-close flex-shrink-0' data-bs-dismiss='modal' aria-label='".htmlspecialchars($layoutLabels['close'], ENT_QUOTES, 'UTF-8')."'></button>";
echo "            </div>";
echo "            <div class='modal-body p-3'>";
if ($canTeleport) {
    echo "<p id='layout-teleport-notice' class='text-sm mb-2 d-none' role='status' tabindex='-1'></p>";
    echo "<form id='layout-teleport-editor' class='border rounded p-3 mb-3 d-none' aria-label='".Output::html($teleportLabels['changeLocation'])."'>";
    echo "    <p id='layout-teleport-context' class='text-sm text-dark mb-2' tabindex='-1'></p>";
    echo "    <label class='form-label mb-1' for='layout-teleport-destination'>".Output::html($teleportLabels['destination'])."</label>";
    echo "    <select id='layout-teleport-destination' class='form-select px-2 mb-2' aria-describedby='layout-teleport-state' disabled></select>";
    echo "    <p id='layout-teleport-state' class='text-sm mb-2' role='status'></p>";
    echo "    <div class='d-flex flex-wrap gap-2'>";
    echo "        <button id='layout-teleport-submit' type='submit' class='btn btn-primary btn-sm mb-0' disabled>".Output::html($teleportLabels['submit'])."</button>";
    echo "        <button id='layout-teleport-cancel' type='button' class='btn btn-outline-secondary btn-sm mb-0'>".Output::html($teleportLabels['cancel'])."</button>";
    echo "        <button id='layout-teleport-retry' type='button' class='btn btn-outline-secondary btn-sm mb-0 d-none'>".Output::html($layoutLabels['retry'])."</button>";
    echo "        <button id='layout-teleport-reload' type='button' class='btn btn-outline-secondary btn-sm mb-0 d-none'>".Output::html($teleportLabels['reload'])."</button>";
    echo "    </div>";
    echo "</form>";
}
echo "                <fieldset id='layout-modal-filters' class='border-0 p-0 mb-3' disabled>";
echo "                    <div class='row g-2 align-items-end'>";
echo "                        <div class='col-12 col-md-6'>";
echo "                            <label class='form-label mb-1' for='layout-equipment-name'>".htmlspecialchars($layoutLabels['nameSearch'], ENT_QUOTES, 'UTF-8')."</label>";
echo "                            <input id='layout-equipment-name' type='search' class='form-control px-2' autocomplete='off'>";
echo "                        </div>";
echo "                        <div class='col-12 col-md-4'>";
echo "                            <label class='form-label mb-1' for='layout-equipment-type'>".htmlspecialchars($layoutLabels['type'], ENT_QUOTES, 'UTF-8')."</label>";
echo "                            <select id='layout-equipment-type' class='form-select px-2'><option value=''>".htmlspecialchars($layoutLabels['allTypes'], ENT_QUOTES, 'UTF-8')."</option></select>";
echo "                        </div>";
echo "                        <div class='col-12 col-md-2'>";
echo "                            <button id='layout-modal-clear' type='button' class='btn btn-outline-secondary btn-sm w-100 mb-0'>".htmlspecialchars($layoutLabels['clear'], ENT_QUOTES, 'UTF-8')."</button>";
echo "                        </div>";
echo "                    </div>";
echo "                </fieldset>";
echo "                <p id='layout-modal-count' class='text-sm text-secondary mb-2' role='status' aria-live='polite'></p>";
echo "                <p id='layout-modal-state' class='text-sm text-secondary py-4 text-center' role='status'>".htmlspecialchars($layoutLabels['loading'], ENT_QUOTES, 'UTF-8')."</p>";
echo "                <button id='layout-modal-retry' type='button' class='btn btn-outline-secondary btn-sm d-none'>".htmlspecialchars($layoutLabels['retry'], ENT_QUOTES, 'UTF-8')."</button>";
echo "                <div id='layout-modal-table' class='table-responsive d-none'>";
echo "                    <table class='table align-items-center mb-0 mobile-list-table'>";
echo "                        <thead><tr><th class='text-xs px-3'>".htmlspecialchars($layoutLabels['equipment'], ENT_QUOTES, 'UTF-8')."</th><th class='text-xs px-3'>UUID</th><th class='text-xs px-3'>".htmlspecialchars($layoutLabels['type'], ENT_QUOTES, 'UTF-8')."</th>";
if ($canTeleport) {
    echo "<th scope='col'><span class='visually-hidden'>".Output::html($teleportLabels['changeLocation'])."</span></th>";
}
echo "</tr></thead>";
echo "                        <tbody id='layout-equipment-rows'></tbody>";
echo "                    </table>";
echo "                </div>";
echo "            </div>";
echo "            <div class='modal-footer'><button type='button' class='btn btn-outline-secondary btn-sm mb-0' data-bs-dismiss='modal'>".htmlspecialchars($layoutLabels['close'], ENT_QUOTES, 'UTF-8')."</button></div>";
echo "        </div>";
echo "    </div>";
echo "</div>";

echo " <script> ";
echo " const layoutLabels = ".json_encode($layoutLabels, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)."; ";
echo " const layoutReadURL = ".json_encode($mdReadURL, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)."; ";
echo " const layoutEditPrefix = ".json_encode(Environment::ROOT_URL . '/page/eq/edit/', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)."; ";
echo " const layoutActions = ".json_encode(array(
    'locations' => AjaxManager::MD_LOC_SITES_LIST_GET,
    'transports' => AjaxManager::MD_LOC_TRANS_LIST_GET,
    'equipment' => AjaxManager::MD_LOC_EQ_ON_LOC_GET,
    'types' => AjaxManager::MD_EQ_TYPE_LIST_GET,
))."; ";
echo " const layoutTeleportConfig = ".Output::json($canTeleport ? array(
    'labels' => $teleportLabels,
    'types' => array(IDConstants::LOC_WAREHOUSE_TYPE => $teleportLabels['warehouse'],
        IDConstants::LOC_CONSTRUCTION_SITE_TYPE => $teleportLabels['site'], IDConstants::LOC_TRANSPORT_TYPE => $layoutLabels['transports']),
    'destinations' => AjaxManager::MD_LOC_TELEPORT_DESTINATIONS_GET,
    'detail' => AjaxManager::MD_EQ_GET,
    'action' => AjaxManager::MD_EQ_TELEPORT,
    'url' => $mdUpdateURL,
) : null)."; ";
echo " let layoutModels = new Map(); ";
echo " let layoutGeneration = 0; ";
echo " let layoutModalView = 0; ";
echo " let layoutTeleport = null; ";
echo " let layoutTypeNames = new Map(); ";
echo " let layoutModal = null; ";
echo " let layoutModalLocation = null; ";
echo " let layoutModalOpener = null; ";
echo "  ";
echo " function readLayoutData(md, fields = {}, list = true) { ";
echo "     return $.ajax({ ";
echo "         url: layoutReadURL, ";
echo "         method: 'POST', ";
echo "         data: {req: Object.assign({MD: md}, fields)}, ";
echo "         dataType: 'json' ";
echo "     }).then(function(response) { ";
echo "         if (response?.CODE === 401) { redirectToLogin(); } ";
echo "         if (response?.CODE !== 200 || (list ? !Array.isArray(response.DATA) : !response.DATA || Array.isArray(response.DATA) || typeof response.DATA !== 'object')) { ";
echo "             return $.Deferred().reject().promise(); ";
echo "         } ";
echo "         return response.DATA; ";
echo "     }, function(xhr) { ";
echo "         if (xhr.status === 401) { redirectToLogin(); } ";
echo "         return $.Deferred().reject().promise(); ";
echo "     }); ";
echo " } ";
echo "  ";
echo " function loadLayoutEquipment(model) { ";
echo "     if (model.items !== null) { return $.Deferred().resolve(model.items).promise(); } ";
echo "     if (model.request) { return model.request; } ";
echo "     model.handles.count.text('…').removeAttr('title'); ";
echo "     const request = readLayoutData(layoutActions.equipment, {ID: model.data.id}); ";
echo "     model.request = request; ";
echo "     const current = () => layoutModels.get(String(model.data.id)) === model && model.request === request; ";
echo "     request.done(function(items) { ";
echo "         if (!current()) { return; } ";
echo "         model.items = items; ";
echo "         model.handles.count.text(items.length); ";
echo "     }).fail(function() { ";
echo "         if (current()) { model.handles.count.text('—').attr('title', layoutLabels.loadError); } ";
echo "     }).always(function() { if (current()) { model.request = null; } }); ";
echo "     return request; ";
echo " } ";
echo "  ";
echo " function registerLayoutModel(data, handles, kind) { ";
echo "     const model = {data: data, handles: handles, kind: kind, items: null, request: null}; ";
echo "     layoutModels.set(String(data.id), model); ";
echo "     handles.button.attr('aria-label', layoutLabels.viewEquipment + ' — ' + (data.name || data.code || '')); ";
echo "     loadLayoutEquipment(model); ";
echo "     return model; ";
echo " } ";
echo "  ";
echo " function renderLayout(locations, transports) { ";
echo "     const container = $('#layout-locations').empty(); ";
echo "     const groups = new Map(); ";
echo "     function addLocation(data, hasLocation = true) { ";
echo "         const handles = getLayoutLocationCard(data, layoutLabels, function() { openLayoutEquipment(data.id); }); ";
echo "         container.append(handles.card); ";
echo "         const group = {data: data, handles: handles, count: 0}; ";
echo "         groups.set(String(data.id), group); ";
echo "         if (hasLocation) { registerLayoutModel(data, handles, 'location'); } ";
echo "         else { handles.summary.remove(); } ";
echo "         return group; ";
echo "     } ";
echo "     locations.forEach(function(location) { addLocation(location); }); ";
echo "     transports.forEach(function(transport) { ";
echo "         const parentId = transport.parentId ? String(transport.parentId) : 'unassigned'; ";
echo "         let group = groups.get(parentId); ";
echo "         if (!group) { ";
echo "             group = addLocation({id: parentId, name: transport.parentName || layoutLabels.unknownLocation}, parentId !== 'unassigned'); ";
echo "         } ";
echo "         const handles = getLayoutTransportCard(transport, layoutLabels, transport.parentName || group.data.name, ";
echo "             function() { openLayoutEquipment(transport.id); }); ";
echo "         group.handles.transports.append(handles.card); ";
echo "         group.count += 1; ";
echo "         group.handles.transportCount.text(group.count); ";
echo "         group.handles.emptyTransports.addClass('d-none'); ";
echo "         registerLayoutModel(transport, handles, 'transport'); ";
echo "     }); ";
echo "     $('#layout-state').text(layoutLabels.overviewCount.replace('{locations}', groups.size).replace('{transports}', transports.length)); ";
echo "     if (!groups.size) { $('#layout-state').text(layoutLabels.noLocations); } ";
echo " } ";
echo "  ";
echo " function loadLayout() { ";
echo "     const generation = ++layoutGeneration; ";
echo "     layoutModels.clear(); ";
echo "     $('#layout-locations').empty(); ";
echo "     $('#layout-retry').addClass('d-none'); ";
echo "     $('#layout-state').text(layoutLabels.loading); ";
echo "     return $.when(readLayoutData(layoutActions.locations), readLayoutData(layoutActions.transports)) ";
echo "         .done(function(locations, transports) { ";
echo "             if (generation === layoutGeneration) { renderLayout(locations, transports); } ";
echo "         }).fail(function() { ";
echo "             if (generation !== layoutGeneration) { return; } ";
echo "             $('#layout-state').text(layoutLabels.loadError); ";
echo "             $('#layout-retry').removeClass('d-none'); ";
echo "         }); ";
echo " } ";
echo "  ";
echo " function layoutTypeName(typeId) { ";
echo "     return layoutTypeNames.get(String(typeId)) || (typeId == null ? layoutLabels.unknownType : layoutLabels.type + ' ' + typeId); ";
echo " } ";
echo "  ";
echo " function populateLayoutTypes(items) { ";
echo "     const select = $('#layout-equipment-type'); ";
echo "     const selected = select.val(); ";
echo "     select.empty().append($('<option>').val('').text(layoutLabels.allTypes)); ";
echo "     const types = new Set(items.map(function(item) { return item.typeId == null ? '' : String(item.typeId); }).filter(Boolean)); ";
echo "     Array.from(types).sort(function(a, b) { return layoutTypeName(a).localeCompare(layoutTypeName(b)); }).forEach(function(id) { ";
echo "         select.append($('<option>').val(id).text(layoutTypeName(id))); ";
echo "     }); ";
echo "     select.val(types.has(selected) ? selected : ''); ";
echo " } ";
echo "  ";
echo " function filterLayoutEquipment() { ";
echo "     const model = layoutModels.get(layoutModalLocation); ";
echo "     if (!model || model.items === null) { return; } ";
echo "     const name = $('#layout-equipment-name').val().trim().toLowerCase(); ";
echo "     const type = $('#layout-equipment-type').val(); ";
echo "     const items = model.items.filter(function(item) { ";
echo "         return String(item.name || '').toLowerCase().includes(name) && (!type || String(item.typeId) === type); ";
echo "     }); ";
echo "     const body = $('#layout-equipment-rows').empty(); ";
echo "     items.forEach(function(item) { ";
echo "         const teleport = layoutTeleportConfig && Number(model.data.id) > 1 && getEquipmentEditUrl(item.uuid, layoutEditPrefix) !== null ";
echo "             && Number(item.serviceStatusId) !== ".\klr\IDConstants::EQ_STATUS_IN_SERVICE." ";
echo "             ? {label: layoutTeleportConfig.labels.changeLocation, open: button => openLayoutTeleport(model, item, button)} : null; ";
echo "         body.append(getLayoutEquipmentRow(item, layoutTypeName(item.typeId), layoutEditPrefix, layoutLabels.type, teleport)); ";
echo "     }); ";
echo "     if (layoutTeleport && layoutTeleport.pending) { body.find('.layout-teleport-edit').prop('disabled', true); } ";
echo "     $('#layout-modal-count').text(layoutLabels.resultCount.replace('{shown}', items.length).replace('{total}', model.items.length)); ";
echo "     $('#layout-modal-table').toggleClass('d-none', items.length === 0); ";
echo "     $('#layout-modal-state').text(model.items.length ? layoutLabels.noMatches : layoutLabels.noEquipment).toggleClass('d-none', items.length > 0); ";
echo " } ";
echo "  ";
echo " function openLayoutEquipment(id) { ";
echo "     const model = layoutModels.get(String(id)); ";
echo "     if (!model) { return; } ";
echo "     closeLayoutTeleport(); ";
echo "     setTeleportNotice(''); ";
echo "     layoutModalLocation = String(id); ";
echo "     $('#layout-equipment-name').val(''); ";
echo "     $('#layout-equipment-type').empty().append($('<option>').val('').text(layoutLabels.allTypes)); ";
echo "     layoutModal.show(); ";
echo "     showLayoutModalEquipment(model); ";
echo " } ";
echo " function showLayoutModalEquipment(model) { ";
echo "     const view = ++layoutModalView; ";
echo "     const id = String(model.data.id); ";
echo "     const current = () => view === layoutModalView && layoutModalLocation === id && layoutModels.get(id) === model; ";
echo "     layoutModalOpener = model.handles.button[0]; ";
echo "     $('#layout-modal-title').text(layoutLabels.equipment + ' — ' + (model.data.name || model.data.code || layoutLabels.unknownLocation)); ";
echo "     $('#layout-modal-context').text(model.kind === 'transport' ? layoutLabels.location + ': ' + (model.data.parentName || layoutLabels.unknownLocation) : layoutLabels.atLocation); ";
echo "     $('#layout-modal-filters').prop('disabled', true); ";
echo "     $('#layout-modal-table, #layout-modal-retry').addClass('d-none'); ";
echo "     $('#layout-equipment-rows').empty(); ";
echo "     $('#layout-modal-count').text(''); ";
echo "     $('#layout-modal-state').text(layoutLabels.loading).removeClass('d-none'); ";
echo "     return loadLayoutEquipment(model).done(function(items) { ";
echo "         if (!current()) { return; } ";
echo "         populateLayoutTypes(items); ";
echo "         $('#layout-modal-filters').prop('disabled', false); ";
echo "         filterLayoutEquipment(); ";
echo "     }).fail(function() { ";
echo "         if (!current()) { return; } ";
echo "         $('#layout-modal-state').text(layoutLabels.loadError); ";
echo "         $('#layout-modal-retry').removeClass('d-none'); ";
echo "     }); ";
echo " } ";
echo "  ";
echo <<<'JS'

function setTeleportNotice(message, error = false) {
    $('#layout-teleport-notice').text(message).toggleClass('d-none', !message).toggleClass('text-danger', error);
}

function closeLayoutTeleport(restoreFocus = false) {
    const editor = layoutTeleport;
    layoutTeleport = null;
    $('#layout-teleport-editor').addClass('d-none');
    if (restoreFocus) {
        const button = editor?.button.isConnected ? editor.button : $('.layout-teleport-edit').filter(function() {
            return $(this).data('equipmentUuid') === editor?.item.uuid;
        })[0];
        if (button) { button.focus(); }
        else { $('#layout-equipment-name').trigger('focus'); }
    }
}

function openLayoutTeleport(source, item, button) {
    if (layoutTeleport?.pending || layoutModels.get(layoutModalLocation) !== source || source.items === null) { return; }
    setTeleportNotice('');
    const editor = {source, item, button, destinations: new Map(), pending: false, reloadRequired: false};
    layoutTeleport = editor;
    $('#layout-teleport-editor').removeClass('d-none');
    $('#layout-teleport-context').text(layoutTeleportName(item) + ' · ' + layoutTeleportName(source.data)).trigger('focus');
    loadLayoutTeleportDestinations(editor);
}

function layoutTeleportName(row) {
    return [row.name, row.code].filter(Boolean).join(' — ') || row.uuid || layoutLabels.unknownLocation;
}

function loadLayoutTeleportDestinations(editor) {
    const labels = layoutTeleportConfig.labels;
    const select = $('#layout-teleport-destination');
    editor.destinations.clear();
    select.empty().append($('<option>').val('').text(labels.choose)).prop('disabled', true);
    $('#layout-teleport-submit').prop('disabled', true);
    $('#layout-teleport-state').text(layoutLabels.loading);
    $('#layout-teleport-retry, #layout-teleport-reload').addClass('d-none');

    const sourceData = editor.source.data;
    const source = sourceData.uuid
        ? $.Deferred().resolve({parentId: sourceData.id, parentUuid: sourceData.uuid}).promise()
        : readLayoutData(layoutTeleportConfig.detail, {ELEM: editor.item.uuid}, false);
    $.when(source, readLayoutData(layoutTeleportConfig.destinations)).done(function(detail, items) {
        if (layoutTeleport !== editor) { return; }
        if (String(detail.parentId) !== String(sourceData.id) || !detail.parentUuid) {
            requireLayoutTeleportReload(editor, labels.stale);
            return;
        }

        editor.sourceUuid = detail.parentUuid;
        const groups = new Map();
        const names = new Map();
        items.forEach(function(item) {
            if (String(item.id) === String(sourceData.id) || item.uuid === editor.sourceUuid) { return; }
            const name = layoutTeleportName(item);
            names.set(name, (names.get(name) || 0) + 1);
            editor.destinations.set(item.uuid, item);
            if (!groups.has(item.typeId)) {
                groups.set(item.typeId, $('<optgroup>').attr('label', layoutTeleportConfig.types[item.typeId]));
            }
        });
        for (const item of editor.destinations.values()) {
            const name = layoutTeleportName(item);
            $('<option>').val(item.uuid).text(names.get(name) > 1 ? name + ' · ' + item.uuid : name)
                .appendTo(groups.get(item.typeId));
        }
        for (const group of groups.values()) { select.append(group); }
        const hasDestinations = editor.destinations.size > 0;
        select.prop('disabled', !hasDestinations);
        $('#layout-teleport-state').text(hasDestinations ? '' : labels.noDestinations);
    }).fail(function() {
        if (layoutTeleport !== editor) { return; }
        $('#layout-teleport-state').text(layoutLabels.loadError);
        $('#layout-teleport-retry').removeClass('d-none');
    });
}

function invalidateLayoutEquipment(model) {
    if (!model) { return; }
    model.items = null;
    model.request = null;
    model.handles.count.text('…').removeAttr('title');
}

function requireLayoutTeleportReload(editor, message) {
    editor.reloadRequired = true;
    invalidateLayoutEquipment(editor.source);
    invalidateLayoutEquipment(layoutModels.get(String(editor.destinationId)));
    ++layoutModalView;
    $('#layout-modal-filters').prop('disabled', true);
    $('#layout-modal-count, #layout-equipment-rows').empty();
    $('#layout-modal-table, #layout-modal-retry').addClass('d-none');
    $('#layout-teleport-state').text(message);
    $('#layout-teleport-reload').removeClass('d-none');
}

function refreshLayoutTeleport(sourceId, destinationId, success = '') {
    const destinationKey = destinationId == null ? null : String(destinationId);
    const source = layoutModels.get(sourceId);
    const destination = layoutModels.get(destinationKey);
    invalidateLayoutEquipment(source);
    invalidateLayoutEquipment(destination);
    const view = ++layoutModalView;
    $('#layout-modal-filters').prop('disabled', true);
    $('#layout-modal-table, #layout-modal-retry').addClass('d-none');
    $('#layout-equipment-rows, #layout-modal-count').empty();
    $('#layout-modal-state').text(layoutLabels.loading).removeClass('d-none');
    setTeleportNotice(success);
    if (success) { $('#layout-teleport-notice').trigger('focus'); }
    const refreshFailed = () => {
        setTeleportNotice([success, layoutTeleportConfig.labels.refreshError].filter(Boolean).join(' '), true);
        $('#layout-modal-retry').removeClass('d-none').data('teleportRefresh', {sourceId, destinationId, success});
    };
    const load = () => {
        if (layoutModalLocation !== sourceId || layoutModalView !== view) { return; }
        const current = layoutModels.get(sourceId);
        if (!current) {
            $('#layout-modal-state').text(layoutLabels.loadError);
            refreshFailed();
            return;
        }
        const sourceRequest = showLayoutModalEquipment(current);
        const currentView = layoutModalView;
        const target = layoutModels.get(destinationKey);
        const destinationRequest = target ? loadLayoutEquipment(target) : $.Deferred().resolve().promise();
        $.when(sourceRequest, destinationRequest).fail(function() {
            if (layoutModalLocation === sourceId && layoutModalView === currentView) { refreshFailed(); }
        });
    };
    if (!source || (destinationKey !== null && !destination)) {
        loadLayout().done(load).fail(function() {
            if (layoutModalLocation === sourceId && layoutModalView === view) {
                $('#layout-modal-state').text(layoutLabels.loadError);
                refreshFailed();
            }
        });
    } else {
        load();
    }
}

function setLayoutTeleportPending(editor, pending) {
    editor.pending = pending;
    $('#layout-equipment-modal [data-bs-dismiss="modal"], .layout-teleport-edit, #layout-teleport-cancel').prop('disabled', pending);
    if (pending) { $('#layout-modal-filters').prop('disabled', true); }
    else if (!editor.reloadRequired && editor.source.items !== null) { $('#layout-modal-filters').prop('disabled', false); }
    const select = $('#layout-teleport-destination');
    select.prop('disabled', pending || editor.reloadRequired || !editor.destinations.size);
    $('#layout-teleport-submit').prop('disabled', pending || editor.reloadRequired || !editor.destinations.has(select.val()));
}

function submitLayoutTeleport(event) {
    event.preventDefault();
    const editor = layoutTeleport;
    if (!editor || editor.pending || editor.reloadRequired) { return; }
    const destination = editor.destinations.get($('#layout-teleport-destination').val());
    if (!destination) { return; }
    const labels = layoutTeleportConfig.labels;
    const sourceId = String(editor.source.data.id);
    editor.destinationId = destination.id;
    setLayoutTeleportPending(editor, true);
    $('#layout-teleport-state').text(labels.pending);

    const req = {MD: layoutTeleportConfig.action, ELEM: editor.item.uuid, PRNT_FROM: editor.sourceUuid, PRNT_TO: destination.uuid};
    submitMutation(layoutTeleportConfig.url, req, function() {
        setLayoutTeleportPending(editor, false);
        closeLayoutTeleport();
        refreshLayoutTeleport(sourceId, destination.id, labels.success);
    }, function(failure) {
        const code = failure?.status || failure?.CODE;
        if (code === 401) { redirectToLogin(); return; }
        const messages = window.klrAjaxMessages || {};
        if (code === 409 || code === 404) {
            requireLayoutTeleportReload(editor, labels.stale);
        } else if (code === 400 || code === 403 || code === 405) {
            $('#layout-teleport-state').text(failure.responseJSON?.DATA || messages.unavailable || layoutLabels.loadError);
        } else {
            requireLayoutTeleportReload(editor, messages.unknownOutcome || labels.stale);
        }
    }).always(function() {
        if (layoutTeleport === editor) {
            setLayoutTeleportPending(editor, false);
            if (editor.reloadRequired) { $('#layout-teleport-reload').trigger('focus'); }
        }
    });
}

JS;
echo " $(document).ready(function() { ";
echo "     const modalElement = document.getElementById('layout-equipment-modal'); ";
echo "     layoutModal = new bootstrap.Modal(modalElement); ";
echo "     modalElement.addEventListener('hide.bs.modal', function(event) { ";
echo "         if (layoutTeleport?.pending) { event.preventDefault(); return; } ";
echo "         ++layoutModalView; ";
echo "         closeLayoutTeleport(); ";
echo "     }); ";
echo "     modalElement.addEventListener('hidden.bs.modal', function() { ";
echo "         $('#layout-modal-retry').removeData('teleportRefresh'); ";
echo "         layoutModalLocation = null; ";
echo "         if (layoutModalOpener && layoutModalOpener.isConnected) { layoutModalOpener.focus(); } ";
echo "     }); ";
echo "     modalElement.addEventListener('shown.bs.modal', function() { if (!layoutTeleport) { $('#layout-equipment-name').trigger('focus'); } }); ";
echo "     $('#layout-equipment-name').on('input', filterLayoutEquipment); ";
echo "     $('#layout-equipment-type').on('change', filterLayoutEquipment); ";
echo "     $('#layout-modal-clear').on('click', function() { ";
echo "         $('#layout-equipment-name, #layout-equipment-type').val(''); ";
echo "         filterLayoutEquipment(); ";
echo "     }); ";
echo "     $('#layout-modal-retry').on('click', function() { ";
echo "         const refresh = $(this).removeClass('d-none').data('teleportRefresh'); ";
echo "         $(this).removeData('teleportRefresh'); ";
echo "         if (refresh) { refreshLayoutTeleport(refresh.sourceId, refresh.destinationId, refresh.success); } ";
echo "         else if (layoutModalLocation) { openLayoutEquipment(layoutModalLocation); } ";
echo "     }); ";
echo "     if (layoutTeleportConfig) { ";
echo "         $('#layout-teleport-editor').on('submit', submitLayoutTeleport); ";
echo "         $('#layout-teleport-destination').on('change', function() { ";
echo "             const editor = layoutTeleport; ";
echo "             $('#layout-teleport-submit').prop('disabled', !editor || editor.pending || editor.reloadRequired || !editor.destinations.has(this.value)); ";
echo "         }); ";
echo "         $('#layout-teleport-cancel').on('click', function() { ";
echo "             if (layoutTeleport?.reloadRequired) { $('#layout-teleport-reload').trigger('click'); } ";
echo "             else { closeLayoutTeleport(true); } ";
echo "         }); ";
echo "         $('#layout-teleport-retry').on('click', function() { if (layoutTeleport) { loadLayoutTeleportDestinations(layoutTeleport); } }); ";
echo "         $('#layout-teleport-reload').on('click', function() { ";
echo "             const editor = layoutTeleport; ";
echo "             if (!editor || editor.pending) { return; } ";
echo "             closeLayoutTeleport(); ";
echo "             refreshLayoutTeleport(String(editor.source.data.id), editor.destinationId); ";
echo "         }); ";
echo "     } ";
echo "     $('#layout-retry').on('click', loadLayout); ";
echo "     readLayoutData(layoutActions.types).done(function(types) { ";
echo "         types.forEach(function(type) { layoutTypeNames.set(String(type.id), type.name || type.code); }); ";
echo "         const model = layoutModels.get(layoutModalLocation); ";
echo "         if (model && model.items !== null) { populateLayoutTypes(model.items); filterLayoutEquipment(); } ";
echo "     }); ";
echo "     loadLayout(); ";
echo " }); ";
echo " </script> ";

?>
