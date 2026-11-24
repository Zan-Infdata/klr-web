<?php

use klr\AjaxManager;
use klr\Lang;
use klr\Output;

$labels = array();
foreach (array('title', 'action', 'transport', 'chooseTransport', 'noTransports', 'pickup', 'dropOff', 'pickupOnly', 'finished',
    'equipment', 'outcome', 'chooseOutcome', 'fixed', 'broken', 'needsService', 'inService', 'warranty', 'optional',
    'loading', 'loadError', 'retry', 'noEquipment', 'submit', 'ready', 'unknown', 'moved', 'rejected') as $key) {
    $labels[$key] = Lang::getRsrc('equipmentService', $key);
}
$label = static function ($key) use ($labels) { return Output::html($labels[$key]); };
?>
<div class="col-12 col-xl-10">
    <div class="card">
        <div class="card-header"><h4 class="mb-0"><?= $label('title') ?></h4></div>
        <div class="card-body pt-0">
            <form id="service-transfer">
                <fieldset id="service-transfer-fields">
                    <div class="row g-3 mb-3">
                        <div class="col-sm-5">
                            <label class="form-label" for="service-action"><?= $label('action') ?></label>
                            <select id="service-action" class="form-select border px-2">
                                <option value="drop_off"><?= $label('dropOff') ?></option>
                                <option value="pickup"><?= $label('pickup') ?></option>
                            </select>
                        </div>
                        <div class="col-sm-7">
                            <label class="form-label" for="service-transport"><?= $label('transport') ?></label>
                            <select id="service-transport" class="form-select border px-2" disabled></select>
                        </div>
                    </div>
                    <h6 id="service-parent" class="mb-2"></h6>
                    <div id="service-items"></div>
                    <button type="submit" id="service-confirm" class="btn bg-gradient-primary mt-3" disabled><?= $label('submit') ?></button>
                </fieldset>
            </form>
            <p id="service-transfer-feedback" class="mb-2" role="status"></p>
            <ul id="service-transfer-results" class="list-unstyled mb-2"></ul>
            <button id="service-transfer-retry" type="button" class="btn btn-sm btn-outline-secondary d-none"><?= $label('retry') ?></button>
        </div>
    </div>
</div>
<script>
$(function () {
    const config = <?= Output::json(array('labels' => $labels, 'read' => $mdReadURL, 'update' => $mdUpdateURL,
        'transports' => AjaxManager::MD_LOC_MY_TRANS_GET, 'options' => AjaxManager::MD_SERVICE_OPTIONS_GET,
        'dropOff' => AjaxManager::MD_SERVICE_DROP_OFF, 'pickup' => AjaxManager::MD_SERVICE_COLLECT,
        'serviceType' => \klr\IDConstants::LOC_SERVICE_TYPE)) ?>;
    const labels = config.labels;
    const transports = $('#service-transport');
    const action = $('#service-action');
    const items = $('#service-items');
    const feedback = $('#service-transfer-feedback');
    const retry = $('#service-transfer-retry');
    const report = $('#service-transfer-results');
    const submit = $('#service-confirm');
    const answers = new Map();
    const statusNames = {1: labels.ready, 2: labels.needsService, 3: labels.inService, 4: labels.broken};
    let options;
    let readVersion = 0;
    let pending = false;
    let reloadRequired = false;

    function setSubmit() {
        const selected = [...answers.values()].filter(answer => answer.selected);
        submit.prop('disabled', pending || reloadRequired || !options || !selected.length
            || (action.val() === 'pickup' && selected.some(answer => answer.isBroken === null)));
    }

    function showRetry(message, handler) {
        feedback.text(message);
        retry.removeClass('d-none').off('click').on('click', handler);
    }

    function readFailed(response, handler) {
        if (response.status === 401) { redirectToLogin(); return; }
        const message = response.status === 409 ? labels.chooseTransport : labels.loadError;
        showRetry(message, handler);
    }

    function loadTransports() {
        transports.prop('disabled', true);
        feedback.text(labels.loading);
        retry.addClass('d-none');
        readEquipmentFormData(config.read, {MD: config.transports}).done(function (list) {
            if (!Array.isArray(list)) { readFailed({}, loadTransports); return; }
            const eligible = list.filter(record => record.parentTypeId === config.serviceType);
            transports.empty().append($('<option>', {value: ''}).text(labels.chooseTransport));
            for (const transport of eligible) {
                transports.append($('<option>', {value: transport.uuid}).text(`${transport.name} · ${transport.parentName}`));
            }
            transports.prop('disabled', !eligible.length);
            feedback.text(eligible.length ? labels.chooseTransport : labels.noTransports);
        }).fail(response => readFailed(response, loadTransports));
    }

    function renderOptions() {
        items.empty();
        const pickup = action.val() === 'pickup';
        const candidates = options.equipment;
        const eligible = new Set(candidates.map(item => item.uuid));
        for (const uuid of answers.keys()) {
            if (!eligible.has(uuid)) { answers.delete(uuid); }
        }
        for (const item of candidates) {
            const answer = answers.get(item.uuid) || {selected: false, isBroken: null, warrantyDate: ''};
            answers.set(item.uuid, answer);
            const check = $('<input>', {type: 'checkbox'}).addClass('form-check-input').prop('checked', answer.selected);
            const label = $('<label>').addClass('d-flex align-items-center gap-2 mb-1')
                .append(check, $('<strong>').text(`${item.code} · ${item.name}`));
            const row = $('<div>').addClass('border rounded p-3 mb-2').append(label,
                $('<div>').addClass('text-sm').text(statusNames[item.serviceStatusId] || ''));
            const answersRow = $('<div>').addClass('row g-2 mt-1').toggleClass('d-none', !answer.selected);
            if (pickup) {
                const outcome = $('<select>').addClass('form-select border px-2').attr('aria-label', labels.outcome)
                    .append($('<option>', {value: ''}).text(labels.chooseOutcome), $('<option>', {value: '0'}).text(labels.fixed),
                        $('<option>', {value: '1'}).text(labels.broken)).val(answer.isBroken === null ? '' : String(answer.isBroken));
                const warranty = $('<input>', {type: 'date', min: '1000-01-01', max: '9999-12-31'})
                    .addClass('form-control border px-2').val(answer.warrantyDate).prop('disabled', !answer.selected);
                warranty.on('change', function () { answer.warrantyDate = this.value; });
                outcome.on('change', function () {
                    answer.isBroken = this.value === '' ? null : Number(this.value);
                    setSubmit();
                });
                answersRow.append($('<label>').addClass('col-sm-6').append($('<span>').text(labels.outcome), outcome),
                    $('<label>').addClass('col-sm-6').append($('<span>').text(`${labels.warranty} (${labels.optional})`), warranty));
                row.append(answersRow);
                check.on('change', function () { warranty.prop('disabled', !this.checked); });
            }
            check.on('change', function () {
                answer.selected = this.checked;
                answersRow.toggleClass('d-none', !this.checked);
                setSubmit();
            });
            items.append(row);
        }
        $('#service-parent').text(options.pickupOnly ? `${options.name} · ${labels.pickupOnly}` : options.name);
        setSubmit();
    }

    function loadOptions(reset) {
        const generation = ++readVersion;
        const transport = transports.val();
        if (reset) { answers.clear(); report.empty(); }
        options = null;
        items.empty();
        $('#service-parent').empty();
        retry.addClass('d-none');
        setSubmit();
        if (!transport) { feedback.text(labels.chooseTransport); return; }
        feedback.text(labels.loading);
        readEquipmentFormData(config.read, {MD: config.options, ELEM: transport, ACTION: action.val()})
            .done(function (data) {
                if (generation !== readVersion) { return; }
                if (!data || data.transportUuid !== transport || !Array.isArray(data.equipment)) {
                    readFailed({}, () => loadOptions(false)); return;
                }
                options = data;
                reloadRequired = false;
                renderOptions();
                feedback.text(data.equipment.length ? '' : data.pickupOnly && action.val() === 'drop_off'
                    ? labels.pickupOnly : labels.noEquipment);
            }).fail(function (response) {
                if (generation === readVersion) { readFailed(response, () => loadOptions(false)); }
            });
    }

    action.add(transports).on('change', () => loadOptions(true));
    $('#service-transfer').on('submit', function (event) {
        event.preventDefault();
        if (submit.prop('disabled') || pending || !options) { return; }
        const snapshot = options.equipment.filter(item => answers.get(item.uuid).selected);
        const pickup = action.val() === 'pickup';
        const payload = snapshot.map(item => {
            const answer = answers.get(item.uuid);
            return pickup ? {uuid: item.uuid, is_broken: answer.isBroken, warranty_date: answer.warrantyDate || null} : item.uuid;
        });
        const unknown = () => {
            reloadRequired = true;
            showRetry(labels.unknown, () => loadOptions(false));
        };
        pending = true;
        setSubmit();
        report.empty();
        $('#service-transfer-fields').prop('disabled', true);
        feedback.text(labels.loading);
        submitMutation(config.update, {MD: pickup ? config.pickup : config.dropOff,
            PRNT_FROM: pickup ? options.uuid : options.transportUuid, PRNT_TO: pickup ? options.transportUuid : options.uuid,
            ITEMS: JSON.stringify(payload)}, function (response) {
            const results = response.DATA;
            if (!Array.isArray(results) || results.length !== snapshot.length || results.some(value => value !== 0 && value !== 1)) {
                unknown(); return;
            }
            const moved = new Set();
            snapshot.forEach((item, index) => {
                const success = results[index] === 1;
                report.append($('<li>').text(`${item.code} · ${item.name} — ${success ? labels.moved : labels.rejected}`));
                if (success) { moved.add(item.uuid); }
            });
            options.equipment = options.equipment.filter(item => !moved.has(item.uuid));
            renderOptions();
            feedback.text(labels.finished);
        }, function (response) {
            const status = response.status || response.CODE;
            if (status === 401) { redirectToLogin(); return; }
            if (!status || status >= 500) { unknown(); return; }
            feedback.text(response.responseJSON?.DATA || labels.loadError);
        }).always(function () {
            pending = false;
            $('#service-transfer-fields').prop('disabled', false);
            setSubmit();
        });
    });
    loadTransports();
});
</script>
