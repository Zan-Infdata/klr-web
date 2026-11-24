<?php

use klr\AjaxManager;
use klr\Lang;
use klr\Output;
use klr\SessionMgmt;

if (!SessionMgmt::isManagerAcl(SessionMgmt::getSyAcl())) {
    http_response_code(403);
    echo Output::html(Lang::getRsrc('ajax', 'alrt1'));
    return;
}

$labels = array();
foreach (array('new', 'warehouse', 'assignee', 'primary', 'secondary', 'equipment', 'quantity', 'choose',
    'anySecondary', 'anyEquipment', 'add', 'remove', 'save', 'saving', 'retry', 'loading', 'listError',
    'stockError', 'emptyWarehouses', 'emptyAssignees', 'emptyStock', 'maximum', 'unavailable', 'review',
    'stockConflict', 'warehouseConflict', 'assigneeConflict', 'saved', 'unknown') as $key) {
    $labels[$key] = Lang::getRsrc('equipmentChecklist', $key);
}
$label = static function ($key) use ($labels) { return Output::html($labels[$key]); };

echo " <div class='col-xl-10 col-12'>";
echo "   <div class='card'>";
echo "     <div class='card-header'><h4 class='mb-0'>".$label('new')."</h4></div>";
echo "     <div class='card-body pt-0'>";
echo "       <form id='checklist-create' novalidate>";
echo "         <div class='row g-3 mb-3'>";
echo "           <div class='col-sm-6'>";
echo "             <label class='form-label' for='checklist-warehouse'>".$label('warehouse')."</label>";
echo "             <select class='form-select border px-2' id='checklist-warehouse' disabled></select>";
echo "           </div>";
echo "           <div class='col-sm-6'>";
echo "             <label class='form-label' for='checklist-assignee'>".$label('assignee')."</label>";
echo "             <select class='form-select border px-2' id='checklist-assignee' disabled></select>";
echo "           </div>";
echo "         </div>";
echo "         <p id='checklist-stock-status' class='text-sm mb-2' role='status'></p>";
echo "         <button id='checklist-retry' class='btn btn-outline-secondary btn-sm d-none' type='button'>".$label('retry')."</button>";
echo "         <div id='checklist-lines'></div>";
echo "         <div class='d-flex flex-wrap gap-2 mt-3'>";
echo "           <button id='checklist-add' class='btn btn-outline-secondary' type='button' disabled>".$label('add')."</button>";
echo "           <button id='checklist-save' class='btn bg-gradient-primary' type='submit' disabled>".$label('save')."</button>";
echo "         </div>";
echo "         <p id='checklist-feedback' class='mb-0' role='status'></p>";
echo "       </form>";
echo "     </div>";
echo "   </div>";
echo " </div>";

echo " <script>$(function() {";
echo " const config = ".Output::json(array('labels' => $labels, 'readUrl' => $mdReadURL, 'createUrl' => $mdCreateURL,
    'warehouses' => AjaxManager::MD_LOC_WAREHOUSE_LIST_GET, 'assignees' => AjaxManager::SY_USER_LIST_GET,
    'stock' => AjaxManager::MD_CHECKLIST_OPTIONS_GET, 'create' => AjaxManager::MD_CHECKLIST_CREATE)).";";
echo <<<'JS'
    const labels = config.labels;
    const warehouse = $('#checklist-warehouse');
    const assignee = $('#checklist-assignee');
    const lines = $('#checklist-lines');
    const stockStatus = $('#checklist-stock-status');
    const retry = $('#checklist-retry');
    const save = $('#checklist-save');
    const feedback = $('#checklist-feedback');
    let stock = [];
    let draft = [];
    let stockReady = false;
    let requestId = 0;
    let saving = false;
    let assigneesLoading = false;
    let retryRead = null;
    let serverConflicts = new Set();

    function setFeedback(message, error = false) {
        feedback.text(message).toggleClass('text-danger', error).toggleClass('text-success', !error && !!message);
    }

    function stockFeedback(message, action = null) {
        retryRead = action;
        stockStatus.text(message);
        retry.toggleClass('d-none', !action).prop('disabled', saving);
    }

    function fillSelect(select, options, emptyLabel, value = null) {
        select.empty().append($('<option>').val('').text(emptyLabel));
        for (const [id, name] of options) {
            select.append($('<option>').val(id).text(name));
        }
        if (value !== null && !options.has(value)) {
            select.append($('<option>').val(value).text(labels.unavailable + ' (' + value + ')'));
        }
        select.val(value === null ? '' : String(value));
    }

    function readOptions() {
        warehouse.prop('disabled', true);
        assignee.prop('disabled', true);
        stockFeedback(labels.loading);
        $.when(readEquipmentFormData(config.readUrl, {MD: config.warehouses}),
            readEquipmentFormData(config.readUrl, {MD: config.assignees})).done(function(warehouses, users) {
            if (!Array.isArray(warehouses) || !Array.isArray(users)) {
                stockFeedback(labels.listError, readOptions);
                return;
            }
            fillSelect(warehouse, new Map(warehouses.map(item => [Number(item.id), item.name])), labels.choose);
            fillSelect(assignee, new Map(users.map(user => [Number(user.id), [user.name, user.surname].join(' ')])), labels.choose);
            warehouse.prop('disabled', !warehouses.length);
            assignee.prop('disabled', !users.length);
            stockFeedback(!warehouses.length ? labels.emptyWarehouses : !users.length ? labels.emptyAssignees : '');
        }).fail(function(xhr) {
            if (xhr.status === 401) { redirectToLogin(); return; }
            stockFeedback(labels.listError, readOptions);
        });
    }

    function readStock() {
        const id = ++requestId;
        const warehouseId = Number(warehouse.val());
        stock = [];
        stockReady = false;
        renderLines();
        if (!warehouseId) { stockFeedback(''); return; }
        stockFeedback(labels.loading);
        readEquipmentFormData(config.readUrl, {MD: config.stock, ID: warehouseId}).done(function(items) {
            if (id !== requestId) { return; }
            if (!Array.isArray(items) || !items.every(item => item
                && ['id', 'typeId', 'secondaryTypeId'].every(key => Number.isSafeInteger(item[key]) && item[key] > 0)
                && ['name', 'code', 'typeName', 'secondaryTypeName'].every(key => typeof item[key] === 'string'))) {
                stockFeedback(labels.stockError, readStock);
                return;
            }
            stock = items;
            stockReady = true;
            if (stock.length && !draft.length) { addLine(); }
            else { renderLines(); }
            stockFeedback(stock.length ? '' : labels.emptyStock);
        }).fail(function(xhr) {
            if (id !== requestId) { return; }
            if (xhr.status === 401) { redirectToLogin(); return; }
            stockFeedback(xhr.status === 409 ? labels.warehouseConflict : labels.stockError, readStock);
        });
    }

    function primaryStock(line) {
        return stock.filter(item => item.typeId === line.primary_type_id);
    }

    function secondaryStock(line, items) {
        return items.filter(item => item.secondaryTypeId === line.secondary_type_id);
    }

    function maximum(line) {
        if (!line.primary_type_id) { return 0; }
        const others = draft.filter(other => other !== line && other.primary_type_id === line.primary_type_id);
        const total = group => group.reduce((sum, other) => sum + (other.quantity || 0), 0);
        const primary = primaryStock(line);
        let available = primary.length - total(others);
        if (line.secondary_type_id !== null) {
            const matching = secondaryStock(line, primary);
            available = Math.min(available, matching.length
                - total(others.filter(other => other.secondary_type_id === line.secondary_type_id)));
            if (line.equipment_id !== null) {
                const given = matching.some(item => item.id === line.equipment_id)
                    && !others.some(other => other.equipment_id === line.equipment_id);
                available = given ? Math.min(1, available) : 0;
            }
        }
        return Math.max(0, available);
    }

    function field(row, index, key, title, width, input) {
        const id = 'checklist-' + key + '-' + index;
        input.attr('id', id);
        row.append($('<div>').addClass(width).append($('<label class="form-label">').attr('for', id).text(title), input));
        return input;
    }

    function renderLines() {
        lines.empty();
        for (const [index, line] of draft.entries()) {
            const row = $('<div class="row g-2 border-top pt-2 pb-3">').appendTo(lines);
            const primary = primaryStock(line);
            const secondary = secondaryStock(line, primary);
            const select = () => $('<select class="form-select border px-2">');
            for (const [key, title, options, emptyLabel, disabled] of [
                ['primary_type_id', labels.primary, new Map(stock.map(item => [item.typeId, item.typeName])),
                    labels.choose, !stock.length],
                ['secondary_type_id', labels.secondary, new Map(primary.map(item => [item.secondaryTypeId, item.secondaryTypeName])),
                    labels.anySecondary, !line.primary_type_id || !primary.length],
                ['equipment_id', labels.equipment, new Map(secondary.map(item => [item.id, item.name + ' (' + item.code + ')'])),
                    labels.anyEquipment, !line.secondary_type_id || !secondary.length]
            ]) {
                const input = field(row, index, key, title, 'col-md-6 col-xl-3', select());
                fillSelect(input, options, emptyLabel, line[key]);
                input.prop('disabled', saving || !stockReady || disabled).on('change', function() {
                    line[key] = Number(this.value) || null;
                    if (key === 'primary_type_id') { line.secondary_type_id = null; }
                    if (key !== 'equipment_id') { line.equipment_id = null; }
                    if (line.equipment_id !== null) { line.quantity = 1; }
                    serverConflicts.clear();
                    setFeedback('');
                    renderLines();
                    document.getElementById(this.id)?.focus();
                });
            }
            const quantity = field(row, index, 'quantity', labels.quantity, 'col-8 col-md-3 col-xl-2',
                $('<input class="form-control border px-2" type="number" min="1" step="1">'));
            quantity.val(line.quantity).prop('disabled', saving || !stockReady || !line.primary_type_id || line.equipment_id !== null)
                .on('input', function() {
                    line.quantity = /^[1-9][0-9]*$/.test(this.value) && Number.isSafeInteger(Number(this.value))
                        ? Number(this.value) : null;
                    serverConflicts.clear();
                    setFeedback('');
                    updateLimits();
                });
            const limitId = 'checklist-max-' + index;
            quantity.attr('aria-describedby', limitId);
            quantity.parent().append($('<small class="d-block">').attr('id', limitId),
                $('<small class="checklist-review d-none text-danger d-block">').text(labels.review));
            row.append($('<div class="col-4 col-md-3 col-xl-1 align-self-center text-end">').append(
                $('<button type="button" class="btn btn-outline-secondary btn-sm px-2 mb-0">')
                    .text(labels.remove).prop('disabled', saving).on('click', function() {
                        draft.splice(index, 1);
                        serverConflicts.clear();
                        setFeedback('');
                        renderLines();
                        $('#checklist-add').trigger('focus');
                    })
            ));
        }
        updateLimits();
    }

    function updateLimits() {
        let valid = stockReady && !assigneesLoading && Boolean(assignee.val()) && draft.length > 0;
        for (const [index, line] of draft.entries()) {
            const limit = maximum(line);
            const invalid = !line.primary_type_id || !line.quantity || line.quantity > limit;
            const review = stockReady && Boolean(line.primary_type_id) && (invalid || serverConflicts.has(index));
            valid = valid && !invalid;
            $('#checklist-quantity-' + index).attr('max', limit)
                .attr('aria-invalid', review ? 'true' : 'false').toggleClass('is-invalid', review);
            $('#checklist-max-' + index).text(stockReady ? labels.maximum + ' ' + limit : '');
            lines.children().eq(index).find('.checklist-review').toggleClass('d-none', !review);
        }
        save.prop('disabled', saving || !valid).text(saving ? labels.saving : labels.save);
        $('#checklist-add').prop('disabled', saving || !stockReady || !stock.length);
    }

    function addLine() {
        draft.push({primary_type_id: null, secondary_type_id: null, equipment_id: null, quantity: 1});
        renderLines();
    }

    $('#checklist-add').on('click', function() {
        addLine();
        $('#checklist-primary_type_id-' + (draft.length - 1)).trigger('focus');
    });
    retry.on('click', function() { if (retryRead && !saving) { retryRead(); } });
    warehouse.on('change', function() {
        draft = [];
        serverConflicts.clear();
        setFeedback('');
        readStock();
    });
    assignee.on('change', function() { setFeedback(''); updateLimits(); });

    $('#checklist-create').on('submit', function(event) {
        event.preventDefault();
        if (saving || save.prop('disabled')) { return; }
        const entries = new Map();
        for (const [index, line] of draft.entries()) {
            const key = [line.primary_type_id, line.secondary_type_id, line.equipment_id].join(':');
            if (!entries.has(key)) { entries.set(key, {line: {...line, quantity: 0}, indexes: []}); }
            const entry = entries.get(key);
            entry.line.quantity += line.quantity;
            entry.indexes.push(index);
        }
        const grouped = Array.from(entries.values());
        const request = {MD: config.create, WAREHOUSE: warehouse.val(), ASSIGNEE: assignee.val(),
            LINES: JSON.stringify(grouped.map(entry => entry.line))};
        const savedMessage = labels.saved.replace('{warehouse}', () => warehouse.find(':selected').text())
            .replace('{assignee}', () => assignee.find(':selected').text());
        saving = true;
        setFeedback('');
        warehouse.add(assignee).add(retry).prop('disabled', true);
        renderLines();
        submitMutation(config.createUrl, request, function(response) {
            if (!response.DATA || !Number.isInteger(response.DATA.id) || response.DATA.id < 1) {
                setFeedback(labels.unknown, true);
                return;
            }
            serverConflicts.clear();
            draft = [];
            addLine();
            setFeedback(savedMessage);
        }, function(xhr) {
            const status = xhr.status || xhr.CODE;
            if (status === 401) { redirectToLogin(); return; }
            const body = xhr.responseJSON || xhr;
            const conflict = body.DATA;
            if (status === 409 && conflict && Array.isArray(conflict.lineIndexes)) {
                serverConflicts = new Set(conflict.lineIndexes.flatMap(index => grouped[index]?.indexes || []));
                setFeedback(conflict.reason === 'assignee' ? labels.assigneeConflict
                    : conflict.reason === 'warehouse' ? labels.warehouseConflict : labels.stockConflict, true);
                if (conflict.reason === 'assignee') {
                    refreshAssignees();
                } else {
                    readStock();
                }
                return;
            }
            setFeedback(!status || status >= 500 ? labels.unknown
                : typeof body.DATA === 'string' ? body.DATA : labels.listError, true);
        }).always(function() {
            saving = false;
            warehouse.add(retry).prop('disabled', false);
            assignee.prop('disabled', assigneesLoading);
            renderLines();
        });
    });

    function refreshAssignees() {
        assigneesLoading = true;
        assignee.prop('disabled', true).val('');
        updateLimits();
        readEquipmentFormData(config.readUrl, {MD: config.assignees}).done(function(users) {
            if (!Array.isArray(users)) { stockFeedback(labels.listError, refreshAssignees); return; }
            const choices = new Map(users.map(user => [Number(user.id), [user.name, user.surname].join(' ')]));
            fillSelect(assignee, choices, labels.choose);
            if (retryRead === refreshAssignees) { stockFeedback(''); }
            if (!users.length) { stockFeedback(labels.emptyAssignees); }
        }).fail(function(xhr) {
            if (xhr.status === 401) { redirectToLogin(); return; }
            stockFeedback(labels.listError, refreshAssignees);
        }).always(function() {
            assigneesLoading = false;
            assignee.prop('disabled', saving);
            updateLimits();
        });
    }

    readOptions();
JS;
echo " });</script>";
