<?php

use klr\AjaxManager;
use klr\IDConstants;
use klr\Lang;
use klr\Output;
use klr\SessionMgmt;

if (!SessionMgmt::isManagerAcl(SessionMgmt::getSyAcl())) {
    http_response_code(403);
    echo Output::html(Lang::getRsrc('ajax', 'alrt1'));
    return;
}

$labels = array();
foreach (array('setup', 'location', 'newLocation', 'code', 'name', 'description', 'active', 'inactive', 'open', 'closed',
    'supportedTypes', 'save', 'loading', 'retry', 'saved', 'loadError', 'unknown', 'noTypes') as $key) {
    $labels[$key] = Lang::getRsrc('equipmentService', $key);
}
$label = static function ($key) use ($labels) { return Output::html($labels[$key]); };
?>
<div class="col-12 col-xl-9">
    <div class="card">
        <div class="card-header"><h4 class="mb-0"><?= $label('setup') ?></h4></div>
        <div class="card-body pt-0">
            <label class="form-label" for="service-location"><?= $label('location') ?></label>
            <select id="service-location" class="form-select border px-2 mb-3" disabled></select>
            <form id="service-setup">
                <fieldset id="service-fields" disabled>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <label class="form-label" for="service-code"><?= $label('code') ?></label>
                            <input id="service-code" class="form-control border px-2" maxlength="45" required>
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label" for="service-name"><?= $label('name') ?></label>
                            <input id="service-name" class="form-control border px-2" maxlength="45" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="service-desc"><?= $label('description') ?></label>
                            <textarea id="service-desc" class="form-control border px-2" maxlength="250" rows="2"></textarea>
                        </div>
                        <div class="col-12 d-flex flex-wrap align-items-center gap-3">
                            <div class="form-check mb-0 ps-0">
                                <input id="service-active" class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label" for="service-active"><?= $label('active') ?></label>
                            </div>
                            <select id="service-status" class="form-select border px-2 w-auto" aria-label="<?= $label('open') ?> / <?= $label('closed') ?>">
                                <option value="<?= IDConstants::LOC_SERVICE_OPEN ?>"><?= $label('open') ?></option>
                                <option value="<?= IDConstants::LOC_SERVICE_CLOSED ?>"><?= $label('closed') ?></option>
                            </select>
                        </div>
                    </div>
                    <h6><?= $label('supportedTypes') ?></h6>
                    <div id="service-types" class="row g-3 mb-3"></div>
                    <button type="submit" class="btn bg-gradient-primary"><?= $label('save') ?></button>
                </fieldset>
            </form>
            <p id="service-feedback" class="mb-2" role="status"></p>
            <button id="service-retry" type="button" class="btn btn-sm btn-outline-secondary d-none"><?= $label('retry') ?></button>
        </div>
    </div>
</div>
<script>
$(function () {
    const config = <?= Output::json(array('labels' => $labels, 'read' => $mdReadURL, 'create' => $mdCreateURL,
        'update' => $mdUpdateURL, 'list' => AjaxManager::MD_SERVICE_LOCATIONS_GET, 'types' => AjaxManager::MD_SERVICE_TYPES_GET,
        'createAction' => AjaxManager::MD_SERVICE_CREATE, 'updateAction' => AjaxManager::MD_SERVICE_UPDATE,
        'open' => IDConstants::LOC_SERVICE_OPEN)) ?>;
    const locations = $('#service-location');
    const fields = $('#service-fields');
    const feedback = $('#service-feedback');
    const retry = $('#service-retry');
    const types = $('#service-types');
    const rows = new Map();
    let readVersion = 0;
    let pending = false;
    let ready = false;
    let draftUuid;

    function setReady(value) {
        ready = value;
        fields.prop('disabled', !ready || pending);
    }

    function failed(response, runAgain) {
        if (response.status === 401) { redirectToLogin(); return; }
        feedback.text(config.labels.loadError);
        retry.removeClass('d-none').off('click').on('click', runAgain);
    }

    function loadTypes(record, message) {
        const version = ++readVersion;
        setReady(false);
        types.empty();
        retry.addClass('d-none');
        feedback.text(config.labels.loading);
        readEquipmentFormData(config.read, record ? {MD: config.types, ID: record.id} : {MD: config.types})
            .done(function (data) {
                if (version !== readVersion) { return; }
                if (!Array.isArray(data)) { failed({}, () => loadTypes(record, message)); return; }
                const groups = new Map();
                for (const type of data) {
                    if (!groups.has(type.primaryId)) {
                        const group = $('<div>').addClass('col-sm-6');
                        group.append($('<h6>').addClass('text-sm').text(type.primaryName));
                        groups.set(type.primaryId, group);
                        types.append(group);
                    }
                    const input = $('<input>', {type: 'checkbox', value: type.id}).addClass('form-check-input')
                        .prop('checked', type.selected === true);
                    groups.get(type.primaryId).append($('<label>').addClass('d-flex align-items-center gap-2 mb-2')
                        .append(input, $('<span>').text(type.name)));
                }
                if (!data.length) { types.text(config.labels.noTypes); }
                setReady(true);
                feedback.text(message || '');
            }).fail(function (response) {
                if (version === readVersion) { failed(response, () => loadTypes(record, message)); }
            });
    }

    function newLocationUuid() {
        const bytes = crypto.getRandomValues(new Uint8Array(16));
        bytes[6] = (bytes[6] & 15) | 64;
        bytes[8] = (bytes[8] & 63) | 128;
        const hex = Array.from(bytes, byte => byte.toString(16).padStart(2, '0')).join('');
        return `${hex.slice(0, 8)}-${hex.slice(8, 12)}-${hex.slice(12, 16)}-${hex.slice(16, 20)}-${hex.slice(20)}`;
    }

    function choose(message) {
        const record = rows.get(locations.val());
        draftUuid = record ? record.uuid : newLocationUuid();
        $('#service-code').val(record ? record.code : '');
        $('#service-name').val(record ? record.name : '');
        $('#service-desc').val(record ? record.desc : '');
        $('#service-active').prop('checked', record ? record.isActive === 1 : true);
        $('#service-status').val(record ? record.statusId : config.open);
        loadTypes(record, message);
    }

    function loadLocations(uuid, message) {
        const version = ++readVersion;
        locations.prop('disabled', true);
        setReady(false);
        retry.addClass('d-none');
        feedback.text(config.labels.loading);
        readEquipmentFormData(config.read, {MD: config.list}).done(function (data) {
            if (version !== readVersion) { return; }
            if (!Array.isArray(data)) { failed({}, () => loadLocations(uuid, message)); return; }
            rows.clear();
            locations.empty().append($('<option>', {value: ''}).text(config.labels.newLocation));
            for (const record of data) {
                rows.set(record.uuid, record);
                const status = record.isActive ? (record.statusId === config.open ? config.labels.open : config.labels.closed)
                    : config.labels.inactive;
                locations.append($('<option>', {value: record.uuid}).text(`${record.name} · ${status}`));
            }
            locations.val(rows.has(uuid) ? uuid : '').prop('disabled', false);
            choose(message);
        }).fail(function (response) {
            if (version === readVersion) { failed(response, () => loadLocations(uuid, message)); }
        });
    }

    locations.on('change', () => choose());
    $('#service-setup').on('submit', function (event) {
        event.preventDefault();
        if (!ready || pending) { return; }
        const record = rows.get(locations.val());
        const data = {uuid: draftUuid, code: $('#service-code').val().trim(), name: $('#service-name').val().trim(),
            desc: $('#service-desc').val().trim(), isActive: $('#service-active').is(':checked') ? 1 : 0,
            statusId: Number($('#service-status').val())};
        if (!data.code || !data.name) { feedback.text(config.labels.loadError); return; }
        const selected = types.find('input:checked').map(function () { return Number(this.value); }).get();
        pending = true;
        fields.prop('disabled', true);
        locations.prop('disabled', true);
        feedback.text(config.labels.loading);
        submitMutation(record ? config.update : config.create,
            {MD: record ? config.updateAction : config.createAction, ELEM: data, TYPES: JSON.stringify(selected)},
            function () { loadLocations(data.uuid, config.labels.saved); },
            function (response) {
                const status = response.status || response.CODE;
                if (status === 401) { redirectToLogin(); return; }
                if (!status || status >= 500) {
                    setReady(false);
                    feedback.text(config.labels.unknown);
                    retry.removeClass('d-none').off('click').on('click', () => loadLocations(data.uuid));
                    return;
                }
                feedback.text(response.responseJSON?.DATA || config.labels.loadError);
                locations.prop('disabled', false);
            }).always(function () {
                pending = false;
                fields.prop('disabled', !ready);
            });
    });
    loadLocations();
});
</script>
