// Install immediately on app pages and after jQuery arrives on the login page.
function installCsrfAjax() {
    const jq = window.jQuery;
    if (!jq || !jq.ajaxPrefilter || jq.klrCsrfInstalled) { return; }
    jq.klrCsrfInstalled = true;
    jq.ajaxPrefilter(function(options, original, xhr) {
        if (String(options.type || options.method || 'GET').toUpperCase() !== 'POST') { return; }
        let url;
        try { url = new URL(options.url || window.location.href, window.location.href); }
        catch (error) { return; }
        if (!/^https?:$/.test(url.protocol) || url.origin !== window.location.origin || url.username || url.password
            || !(url.pathname === '/ajax' || url.pathname.startsWith('/ajax/'))) { return; }
        const meta = document.querySelector('meta[name="csrf-token"]');
        const token = meta ? meta.content : '';
        if (/^[a-f0-9]{64}$/.test(token)) { xhr.setRequestHeader('X-CSRF-Token', token); }
    });
}
installCsrfAjax();
document.addEventListener('DOMContentLoaded', installCsrfAjax);

function redirectToLogin(){
  window.location.replace('/login');
}



function showEditRecordQr(image, uuid) {
    if (typeof uuid !== 'string' || uuid.trim() === '') { return; }
    const url = new URL(image.dataset.qrUrl, window.location.href);
    url.searchParams.set('value', uuid);
    if (!image.hidden && image.src === url.href) { return; }
    image.hidden = true;
    image.onload = function() { image.hidden = false; };
    image.src = url.href;
}

// Mutations use the legacy API adapter's explicit result, never HTTP success alone.
function ajaxFailure(response, mutation=false) {
    const code = response && (response.CODE || response.status);
    if (code === 401) { redirectToLogin(); return; }
    const messages = window.klrAjaxMessages || {};
    const body = response && response.responseJSON ? response.responseJSON : response;
    let message = body && typeof body.DATA === 'string' ? body.DATA : null;
    if (!message) {
        message = mutation && (!code || code === 502 || code === 504)
            ? (messages.unknownOutcome || 'The request outcome is unknown. Check the current data before submitting again.')
            : (messages.unavailable || 'Failed to process request. Please try again.');
    }
    alert(message);
}

function submitMutation(url, req, onSuccess, onFailure = response => ajaxFailure(response, true)) {
    return $.ajax({url: url, method: 'POST', data: {req: req}, dataType: 'json',
        success: function(response) {
            if (response && response.CODE === 200) { onSuccess(response); }
            else { onFailure(response); }
        },
        error: onFailure
    });
}

function runButtonAction($button, action, pendingText='Processing...') {
    if ($button.data('requestPending')) { return null; }
    const text = $button.text(), disabled = $button.prop('disabled');
    $button.data('requestPending', true).prop('disabled', true).text(pendingText);
    const restore = function() { $button.removeData('requestPending').prop('disabled', disabled).text(text); };
    try {
        const request = action();
        if (request && typeof request.always === 'function') { request.always(restore); }
        else { restore(); }
        return request;
    } catch (error) { restore(); throw error; }
}

function getLocationCard(cardData, onClickHandler){

    const cardHtml = `
        <div class='col-12 col-sm-6 col-lg-3 mb-3 inventory-card'>
            <div class='card h-100'>
                <div class='card-header m-auto p-3 text-center'>
                    <div class='icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg'>
                        <i class='bi bi-truck'></i>
                    </div>
                </div>
                <div class='card-body pt-0 p-3 text-center'>
                    <h6 class='text-center mb-0'></h6>
                    <span class='location-uuid text-xs'></span>
                    <br/>
                    <span class='location-name text-xs'></span>
                    <hr class='horizontal dark my-3'>
                    <button class='btn btn-primary'>Move to location</button>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    $card.find('h6, .location-name').text(cardData.name ?? '');
    $card.find('.location-uuid').text(cardData.uuid ?? '');
    const $button = $card.find('button.btn-primary');

    $button.on('click', function() {
        return runButtonAction($button, function() { return onClickHandler(cardData); });
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}

function getEqListItemLayout(itemData){
    const itemHtml = `
        <li></li>
        `;

    const $item = $(itemHtml).text(itemData.name ?? '');

    out = {"item" : $item}

    return out
    
}

function getTransportCardLayout(cardData){

  const takenBadge = cardData.statusId == 3 ? "<span class='badge bg-info'>taken</span>" : "";

    const cardHtml = `
        <div class='col-12 inventory-card mb-3'>
            <div class='card h-100'>
                <div class='card-header m-auto p-3 text-center'>
                    <div class='icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg'>
                        <i class='bi bi-truck'></i>
                    </div>
                </div>
                <div class='card-body pt-0 p-3 text-center'>
                    <h6 class='text-center mb-0'></h6>
                    ${takenBadge}
                    <ul>
                    </ul>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    const $list = $card.find('ul');
    $card.find('h6').text(cardData.name ?? '');
    if (/^[1-9]\d*$/.test(String(cardData.id))) { $list.attr('id', 'trans-' + cardData.id); }

    out = {"card" : $card, "list" : $list}

    return out
    
}

function getTransportCard(cardData, onClickHandler, btnText="Take"){
    const cardHtml = `
        <div class='col-12 col-sm-6 col-lg-3 mb-3 inventory-card'>
            <div class='card h-100'>
                <div class='card-header m-auto p-3 text-center'>
                    <div class='icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg'>
                        <i class='bi bi-truck'></i>
                    </div>
                </div>
                <div class='card-body pt-0 p-3 text-center'>
                    <h6 class='text-center mb-0'></h6>
                    <span class='transport-code text-xs'></span>
                    <br>
                    <span class='transport-parent-name text-xs'></span>
                    <hr class='horizontal dark my-3'>
                    <button class='btn btn-primary'></button>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    $card.find('h6').text(cardData.name ?? '');
    $card.find('.transport-code').text(cardData.code ?? '');
    $card.find('.transport-parent-name').text(cardData.parentName ?? '');
    const $button = $card.find('button.btn-primary').text(btnText);

    $button.on('click', function() {
        return runButtonAction($button, function() { return onClickHandler(cardData); });
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}


function getSiteCard(cardData){
    const cardHtml = `
        <div class='col-12 col-sm-6 col-lg-3 mb-3 inventory-card'>
            <div class='card h-100'>
                <div class='card-header m-auto p-3 text-center'>

                </div>
                <div class='card-body pt-0 p-3 text-center'>
                    <ul></ul>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    const $list = $card.find('ul');
    $card.find('.card-header').text(cardData.name ?? '');
    if (/^[1-9]\d*$/.test(String(cardData.id))) {
        $card.find('.card-body').attr('id', 'site-' + cardData.id);
        $list.attr('id', 'site-list-' + cardData.id);
    }

    out = { "card" : $card, "list" : $list }

    return out
    
}

function getTransportReturnCard(cardData, onClickHandler, btnText="Return"){
    const cardHtml = `
        <div class='col-12 col-sm-6 col-lg-3 mb-3 inventory-card'>
            <div class='card h-100'>
                <div class='card-header m-auto p-3 text-center'>
                    <div class='icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg'>
                        <i class='bi bi-truck'></i>
                    </div>
                </div>
                <div class='card-body pt-0 p-3 text-center'>
                    <h6 class='text-center mb-0'></h6>
                    <span class='transport-uuid text-xs'></span>
                    <span class='transport-parent-name text-xs'></span>
                    <br/>
                    <span class='transport-equipment-count text-s'></span>
                    <hr class='horizontal dark my-3'>
                    <button class='btn btn-primary'></button>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    $card.find('h6').text(cardData.code ?? '');
    $card.find('.transport-uuid').text(cardData.uuid ?? '');
    $card.find('.transport-parent-name').text(cardData.parentName ?? '');
    $card.find('.transport-equipment-count').text('Items in transport: ' + cardData.childCount);
    const $button = $card.find('button.btn-primary').text(btnText);

    $button.on('click', function() {
      return runButtonAction($button, function() { return onClickHandler(cardData); });
    });
      
    out = {"card" : $card, "btn": $button}

    return out
    
}


function getTransportRow(cardData, onClickHandler){
    const cardHtml = `
                    <tr> 
                      <td class='mobile-list-title'>
                        <div class='d-flex px-2 py-1'> 
                          <div> 
                            <img src='/klrweb/static/img/team-2.jpg' class='avatar avatar-sm me-3 border-radius-lg' alt='user1'> 
                          </div> 
                          <div class='d-flex flex-column justify-content-center'> 
                            <h6 class='transport-code mb-0 text-sm'></h6>
                            <p class='transport-uuid text-xs text-secondary mb-0'></p>
                          </div> 
                        </div> 
                      </td> 
                     <td> 
                        <p class='text-xs font-weight-bold mb-0'>Located at:</p> 
                        <p class='transport-parent-name text-xs text-secondary mb-0'></p>
                      </td> 
                      <td class='align-middle text-center text-sm'> 
                        <span class='badge badge-sm bg-gradient-success'>Online</span> 
                      </td> 
                      <td class='align-middle text-center'> 
                        <span class='text-secondary text-xs font-weight-bold'>23/04/18</span> 
                      </td> 
                      <td class='align-middle mobile-list-actions'>
                        <button class='btn btn-primary btn-transport'>Chose</button>
                      </td> 
                    </tr>
    `;
    const $card = $(cardHtml);
    $card.find('.transport-code').text(cardData.code ?? '');
    $card.find('.transport-uuid').text(cardData.uuid ?? '');
    $card.find('.transport-parent-name').text(cardData.parentName ?? '');
    const $button = $card.find('button.btn-primary');

    $button.on('click', function() {
        $(".btn-transport").prop('disabled', false).text('Chose');
        $button.prop('disabled', true).text('Active...');
        onClickHandler(cardData);
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}

function getUserRow(cardData, onClickHandler){
    const cardHtml = `
                    <tr> 
                      <td class='mobile-list-title'>
                        <div class='d-flex px-2 py-1'> 
                          <div> 
                            <img src='/klrweb/static/img/team-2.jpg' class='avatar avatar-sm me-3 border-radius-lg' alt='user1'> 
                          </div> 
                          <div class='d-flex flex-column justify-content-center'> 
                            <h6 class='user-name mb-0 text-sm'></h6>
                            <p class='user-surname text-xs text-secondary mb-0'></p>
                          </div> 
                        </div> 
                      </td> 
                     <td> 
                        <p class='text-xs font-weight-bold mb-0'>email:</p> 
                        <p class='user-email text-xs text-secondary mb-0'></p>
                      </td> 
                      <td class='align-middle text-center text-sm'> 
                        <span class='badge badge-sm bg-gradient-success'>Online</span> 
                      </td> 
                      <td class='align-middle text-center'> 
                        <span class='text-secondary text-xs font-weight-bold'>23/04/18</span> 
                      </td> 
                      <td class='align-middle mobile-list-actions'>
                        <button class='btn btn-primary btn-transport'>Log in as user</button>
                      </td> 
                    </tr>
    `;
    const $card = $(cardHtml);
    $card.find('.user-name').text(cardData.name ?? '');
    $card.find('.user-surname').text(cardData.surname ?? '');
    $card.find('.user-email').text(cardData.email ?? '');
    const $button = $card.find('button.btn-primary');

    $button.on('click', function() {
        return runButtonAction($button, function() { return onClickHandler(cardData); });
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}

function getEquipmentServiceDot(serviceStatusId, serviceStatuses={}) {
    const status = serviceStatuses[Number(serviceStatusId)] || serviceStatuses.unknown
        || {className: 'bg-secondary', label: 'Unknown service status'};
    return $('<span>').addClass('equipment-service-dot ' + status.className)
        .attr({role: 'img', title: status.label, 'aria-label': status.label});
}

function getEquipmentRow(cardData, onClickHandler, isTake, serviceStatuses={}) {
    const btnText = isTake ? 'Take' : 'Leave';
    const btnTextActive = isTake ? 'Taking...' : 'Leaving...';
    const $card = $(`
        <tr>
            <td class='equipment-service-column align-middle text-center'></td>
            <td class='mobile-list-title'>
                <div class='d-flex px-2 py-1'>
                    <div class='d-flex flex-column justify-content-center'>
                        <h6 class='equipment-name mb-0 text-sm'></h6>
                        <p class='equipment-code text-xs text-secondary mb-0'></p>
                    </div>
                </div>
            </td>
            <td data-label='UUID'>
                <p class='equipment-uuid text-xs font-weight-bold mb-0'></p>
                <p class='text-xs text-secondary mb-0'>Equipment</p>
            </td>
            <td class='align-middle mobile-list-actions'>
                <button type='button' class='btn btn-outline-primary'></button>
            </td>
        </tr>
    `);
    $card.find('.equipment-service-column').append(getEquipmentServiceDot(cardData.serviceStatusId, serviceStatuses));
    $card.find('.equipment-name').text(cardData.name || '');
    $card.find('.equipment-code').text(cardData.code || '');
    $card.find('.equipment-uuid').text(cardData.uuid || '');
    const $button = $card.find('button.btn-outline-primary').text(btnText);
    $button.on('click', function() {
        return runButtonAction($button, function() { return onClickHandler(cardData); }, btnTextActive);
    });
    return {card: $card, btn: $button};
}

// Only application URLs with a complete UUID may become equipment edit links.
function getEquipmentEditUrl(uuid, prefix) {
    if (typeof uuid !== 'string' || !/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i.test(uuid)) {
        return null;
    }
    if (typeof prefix !== 'string') { return null; }
    const path = prefix + encodeURIComponent(uuid);
    try {
        const url = new URL(path, window.location.href);
        if (!/^https?:$/.test(url.protocol) || url.origin !== window.location.origin) { return null; }
        return path;
    } catch (error) {
        return null;
    }
}

function getEquipmentListRow(cardData, urlpfx="", serviceStatuses={}, mobileLabels={}){

    const href = getEquipmentEditUrl(cardData.uuid, urlpfx + 'edit/');

    const atService = cardData.parent.typeId === mobileLabels.serviceType;
    const badge_class = cardData.parent.isWarehouse ? "bg-gradient-success" : "bg-gradient-warning";
    const status_txt = cardData.parent.isWarehouse ? "Stored" : "Taken";

    const cardHtml = `
            <tr>
              <td class='equipment-service-column align-middle text-center'>
              </td>
              <td class='mobile-list-title'>
                <div class='d-flex px-2 py-1'>
                  <div class='d-flex flex-column justify-content-center'>
                    <h6 class='equipment-name mb-0 text-sm'></h6>
                    <p class='equipment-code text-xs text-secondary mb-0'></p>
                  </div>
                </div>
              </td>
              <td data-label='UUID'>
                <p class='equipment-uuid text-xs font-weight-bold mb-0'></p>
                <p class='text-xs text-secondary mb-0'>Equipment</p>
              </td>
              <td class='align-middle text-center text-sm'>
                <span class='badge badge-sm ${badge_class}'>${status_txt}</span>
              </td>
              <td class='align-middle text-center mobile-list-location'>
                <span class='text-secondary text-xs font-weight-bold'></span>
              </td>
              <td class='align-middle mobile-list-actions'>
                <a class='link-primary'>Edit</a>
              </td>
            </tr>
    `;
    const $card = $(cardHtml);
    $card.find('.equipment-name').text(cardData.name ?? '');
    $card.find('.equipment-code').text(cardData.code ?? '');
    $card.find('.equipment-uuid').text(cardData.uuid ?? '');
    $card.find('.mobile-list-location > span').text(cardData.parent.name ?? '');
    if (atService && mobileLabels.serviceLocation) { $card.find('.badge').text(mobileLabels.serviceLocation); }
    if (href !== null) { $card.find('a.link-primary').attr('href', href); }
    else { $card.find('a.link-primary').attr('aria-disabled', 'true'); }
    $card.find('.mobile-list-location').attr('data-label', mobileLabels.location || 'Location');
    $card.find('.equipment-service-column').append(getEquipmentServiceDot(cardData.serviceStatusId, serviceStatuses));
    const $button = $card.find('button.btn-outline-primary');

    $button.on('click', function() {
        return runButtonAction($button, function() { return onClickHandler(cardData); }, btnTextActive);
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}

function getEqHistory(data, isOut, teleportLabel = 'Teleport'){
    const isTeleport = data.isTeleport === 1;
    const color = isTeleport ? 'eq-history-teleport' : (isOut ? 'text-success text-gradient' : 'text-danger text-gradient');
    const icon = isTeleport ? 'bi-lightning-charge' : (isOut ? 'bi-box-arrow-in-up-right' : 'bi-box-arrow-in-up-left');

    const html = `
          <div class='timeline-block mb-3'>
            <span class='timeline-step'>
              <i class='bi ${icon} ${color}' aria-hidden='true'></i>
            </span>
            <div class='timeline-content'>
              <h6 class='text-dark text-sm font-weight-bold mb-0'></h6>
              <p class='text-secondary font-weight-bold text-xs mt-1 mb-0'></p>
            </div>
          </div>
    `;
    const $timeline = $(html);
    const destination = data.parentToName ?? '';
    const heading = $timeline.find('h6');
    if (isTeleport) {
        heading.text(teleportLabel);
        $('<p>', {class: 'text-dark text-sm mb-0'})
            .text((data.parentFromName ?? '') + ' → ' + destination).insertAfter(heading);
    } else {
        heading.text(destination);
    }
    $timeline.find('p').last().text(data.time ?? '');
    return {html: $timeline};
}

function getEqTransHistory(data){

    const html = `
          <div class='timeline-block mb-3'>
            <span class='timeline-step'>
              <i class='bi bi-truck text-info text-gradient'></i>
            </span>
            <div class='timeline-content'>
              <h6 class='text-dark text-sm font-weight-bold mb-0'></h6>
              <p class='text-secondary font-weight-bold text-xs mt-1 mb-0'></p>
            </div>
          </div>
    `;
    const $timeline = $(html);
    $timeline.find('h6').text(data.parentToName ?? '');
    $timeline.find('p').text(data.time ?? '');

    out = {"html" : $timeline}

    return out
    
}

function getLocationHistoryContainer(data, isOut){

    const html = `
          <div class='timeline timeline-one-side ms-4'>
          </div>
    `;
    const $html = $(html);

    out = {"html" : $html }

    return out
    
}

function readEquipmentFormData(url, req) {
    return $.ajax({url: url, method: 'POST', data: {req: req}, dataType: 'json'}).then(function(response) {
        if (response && response.CODE === 200) { return response.DATA; }
        return $.Deferred().reject(response).promise();
    });
}

function setupEquipmentTypeFields(readUrl, actions, buttonId, labels, loadForm) {
    const primary = $('#type-inp');
    const secondary = $('#secondary-type-inp');
    const button = $(document.getElementById(buttonId));
    const status = $('#equipment-type-status');
    const retry = $('#equipment-type-retry');
    let secondaryRequestId = 0;
    let formValues;

    function showStatus(key, retryAction) {
        status.text(labels[key] || '');
        retry.off('click').toggleClass('d-none', !retryAction);
        if (retryAction) { retry.on('click', retryAction); }
    }

    function updateField(select) {
        select.closest('.input-group-outline').toggleClass('is-filled', select.children().length > 0);
    }

    function updateSubmit() {
        const waitingForTypes = !formValues?.typeLocked && (primary.prop('disabled') || secondary.prop('disabled'));
        button.prop('disabled', Boolean(button.data('requestPending')) || waitingForTypes || !primary.val() || !secondary.val());
    }

    function showFailure(response, retryAction) {
        if (response && (response.CODE === 401 || response.status === 401)) {
            redirectToLogin();
            return;
        }
        showStatus('loadError', retryAction);
    }

    function readOptions(req) {
        return readEquipmentFormData(readUrl, req).then(function(items) {
            const ids = new Set();
            if (!Array.isArray(items) || !items.every(function(item) {
                if (!item || (typeof item.id !== 'number' && typeof item.id !== 'string') ||
                    !/^[1-9][0-9]*$/.test(String(item.id)) ||
                    !Number.isSafeInteger(Number(item.id)) || typeof item.name !== 'string' ||
                    typeof item.code !== 'string' || ids.has(String(item.id))) { return false; }
                ids.add(String(item.id));
                return true;
            })) { throw new Error('Invalid equipment type options'); }
            return items;
        });
    }

    function populate(select, items, savedId) {
        const defaultOption = items.find(item => item.code === 'n/a');
        const preferred = savedId === undefined ? defaultOption?.id : savedId;
        select.empty().append($('<option>').val('').text(labels.choose));
        generateSelectOptions(items, select.attr('id'));
        select.val(items.some(item => String(item.id) === String(preferred)) ? String(preferred) : '');
        updateField(select);
    }

    function loadSecondary(savedId) {
        const requestId = ++secondaryRequestId;
        const primaryId = primary.val();
        secondary.empty().prop('disabled', true);
        updateField(secondary);
        updateSubmit();
        if (!primaryId) { showStatus(''); return; }

        const reload = () => loadSecondary(savedId);
        showStatus('loading');
        readOptions({MD: actions.secondary, ID: primaryId}).done(function(items) {
            if (requestId !== secondaryRequestId) { return; }
            if (!items.length) { showStatus('noSecondary', reload); return; }
            populate(secondary, items, savedId);
            secondary.prop('disabled', Boolean(formValues?.typeLocked));
            showStatus(savedId !== undefined && !secondary.val() ? 'savedUnavailable' : '');
            updateSubmit();
        }).fail(function(response) {
            if (requestId === secondaryRequestId) { showFailure(response, reload); }
        });
    }

    function loadPrimary() {
        primary.empty().prop('disabled', true);
        secondary.empty().prop('disabled', true);
        updateSubmit();
        showStatus('loading');
        const ready = formValues === undefined ? loadForm().then(function(values) {
            formValues = values;
        }) : $.Deferred().resolve().promise();
        ready.then(() => readOptions({MD: actions.primary})).done(function(items) {
            if (!items.length) { showStatus('noPrimary', loadPrimary); return; }
            populate(primary, items, formValues?.typeId);
            primary.prop('disabled', Boolean(formValues?.typeLocked));
            if (formValues && !primary.val()) { showStatus('savedUnavailable'); return; }
            loadSecondary(formValues?.secondaryTypeId);
        }).fail(function(response) {
            showFailure(response, loadPrimary);
        });
    }

    primary.on('change', function() {
        if (primary.prop('disabled')) { return; }
        updateField(primary);
        loadSecondary();
    });
    secondary.on('change', function() {
        if (secondary.prop('disabled')) { return; }
        updateField(secondary);
        showStatus('');
        updateSubmit();
    });
    loadPrimary();

    return function submit(action) {
        if (button.prop('disabled')) { return null; }
        primary.add(secondary).prop('disabled', true);
        const restore = function() {
            primary.add(secondary).prop('disabled', Boolean(formValues?.typeLocked));
            updateSubmit();
        };
        try {
            return runButtonAction(button, action).always(restore);
        } catch (error) {
            restore();
            throw error;
        }
    };
}

function generateSelectOptions(selectData, selectorId){
    const out = [];
    const $selector = $(document.getElementById(selectorId));
    for (const currData of selectData) {
        const $option = $('<option>').val(currData.id).text(currData.name ?? '');
        $selector.append($option);
        out.push($option);
    }
    return out;
}


function calUserAjax(url, swtch, element) {
    return submitMutation(url, {MD: swtch, ELEM: element}, function() { window.location.replace('/'); });
}
// Equipment layout summaries share the same count-and-modal interaction.
function getLayoutLocationCard(data, labels, onOpen) {
    const $card = $(`
        <section class='col-12 col-xl-6'>
            <div class='card layout-location h-100'>
                <div class='card-header p-3 d-flex align-items-center gap-3'>
                    <span class='layout-location-icon' aria-hidden='true'><i class='bi bi-geo-alt'></i></span>
                    <div class='min-width-0'>
                        <p class='layout-eyebrow mb-1'></p>
                        <h5 class='layout-location-name mb-0'></h5>
                        <p class='layout-location-code text-sm text-secondary mb-0'></p>
                    </div>
                </div>
                <div class='card-body p-3 pt-0'>
                    <div class='layout-equipment-summary d-flex align-items-center justify-content-between gap-3'>
                        <div><strong class='layout-equipment-count'>…</strong><span class='layout-equipment-label text-sm'></span></div>
                        <button type='button' class='layout-view-equipment btn btn-outline-secondary btn-sm mb-0'></button>
                    </div>
                    <div class='d-flex align-items-center justify-content-between mt-3 mb-2'>
                        <h6 class='layout-transports-title text-sm mb-0'></h6>
                        <span class='layout-transport-count text-sm text-secondary'>0</span>
                    </div>
                    <div class='layout-transports row g-2'></div>
                    <p class='layout-no-transports text-sm text-secondary mb-0'></p>
                </div>
            </div>
        </section>
    `);
    $card.find('.layout-eyebrow').text(labels.location);
    $card.find('.layout-location-name').text(data.name || data.code || labels.unknownLocation);
    $card.find('.layout-location-code').text(data.code || '');
    $card.find('.layout-equipment-label').text(labels.atLocation);
    $card.find('.layout-transports-title').text(labels.transports);
    $card.find('.layout-no-transports').text(labels.noTransports);
    const $button = $card.find('.layout-view-equipment').text(labels.viewEquipment).on('click', onOpen);
    return {
        card: $card,
        count: $card.find('.layout-equipment-count'),
        button: $button,
        summary: $card.find('.layout-equipment-summary'),
        transports: $card.find('.layout-transports'),
        transportCount: $card.find('.layout-transport-count'),
        emptyTransports: $card.find('.layout-no-transports')
    };
}

function getLayoutTransportCard(data, labels, parentName, onOpen) {
    const taken = Number(data.statusId) === 3;
    const $card = $(`
        <div class='col-12 col-md-6'>
            <article class='layout-transport h-100 p-3 d-flex flex-column'>
                <div class='d-flex align-items-start justify-content-between gap-2 mb-2'>
                    <div class='min-width-0'>
                        <h6 class='layout-transport-name text-sm mb-1'></h6>
                        <p class='layout-transport-code text-xs text-secondary mb-0'></p>
                    </div>
                    <span class='layout-transport-status badge flex-shrink-0'></span>
                </div>
                <p class='text-xs text-secondary mb-2'><i class='bi bi-geo-alt me-1' aria-hidden='true'></i><span class='layout-parent-name'></span></p>
                <p class='layout-owner text-sm mb-3'><i class='bi bi-person me-1' aria-hidden='true'></i><span class='layout-owner-name'></span></p>
                <div class='layout-equipment-summary mt-auto pt-2'>
                    <div class='mb-2'><strong class='layout-equipment-count'>…</strong><span class='layout-equipment-label text-xs'></span></div>
                    <button type='button' class='layout-view-equipment btn btn-outline-secondary btn-sm w-100 mb-0'></button>
                </div>
            </article>
        </div>
    `);
    $card.find('.layout-transport-name').text(data.name || data.code || labels.transport);
    $card.find('.layout-transport-code').text(data.code || '');
    $card.find('.layout-parent-name').text(parentName || labels.unknownLocation);
    $card.find('.layout-transport-status').addClass(taken ? 'bg-info' : 'bg-light text-dark')
        .text(taken ? labels.taken : (Number(data.statusId) === 2 ? labels.parked : labels.unknownStatus));
    if (taken) {
        const user = data.user || {};
        const name = [user.name, user.surname].filter(Boolean).join(' ').trim();
        $card.find('.layout-owner-name').text(labels.takenBy + ': ' + (name || labels.unknownUser));
    } else {
        $card.find('.layout-owner').remove();
    }
    $card.find('.layout-equipment-label').text(labels.onTransport);
    const $button = $card.find('.layout-view-equipment').text(labels.viewEquipment).on('click', onOpen);
    return {card: $card, count: $card.find('.layout-equipment-count'), button: $button};
}

function getLayoutEquipmentRow(data, typeName, editPrefix, typeLabel='Type', teleportAction = null) {
    const $row = $(`
        <tr>
            <td class='px-3 py-2 mobile-list-title'><a class='layout-equipment-name text-sm font-weight-bold'></a><p class='layout-equipment-code text-xs text-secondary mb-0'></p></td>
            <td class='layout-equipment-uuid text-xs px-3 py-2' data-label='UUID'></td>
            <td class='layout-equipment-type text-sm px-3 py-2'></td>
        </tr>
    `);
    const href = getEquipmentEditUrl(data.uuid, editPrefix);
    $row.find('.layout-equipment-name').text(data.name || data.code || data.uuid || '');
    if (href !== null) { $row.find('.layout-equipment-name').attr('href', href); }
    else { $row.find('.layout-equipment-name').attr('aria-disabled', 'true'); }
    $row.find('.layout-equipment-code').text(data.code || '');
    $row.find('.layout-equipment-uuid').text(data.uuid || '');
    $row.find('.layout-equipment-type').attr('data-label', typeLabel).text(typeName);
    if (teleportAction) {
        const $edit = $('<button>', {type: 'button',
            class: 'btn btn-outline-secondary btn-sm mb-0 px-2 py-1 layout-teleport-edit',
            'aria-label': teleportAction.label + ' — ' + (data.name || data.code || data.uuid)})
            .data('equipmentUuid', data.uuid)
            .append($('<i>', {class: 'bi bi-pencil', 'aria-hidden': 'true'}))
            .on('click', function() { teleportAction.open(this); });
        $('<td>', {class: 'px-3 py-2 text-end layout-teleport-cell'}).append($edit).appendTo($row);
    }
    return $row;
}

// The theme binds the toggle buttons at the end of page.php, before DOM ready.
document.addEventListener('DOMContentLoaded', function() {
    const sidenav = document.getElementById('sidenav-main');
    const menuButton = document.getElementById('iconNavbarSidenav');
    const closeButton = document.getElementById('iconSidenav');
    if (!sidenav || !menuButton || !closeButton) return;

    const desktop = window.matchMedia('(min-width: 1200px)');
    function syncNavigation() {
        const open = document.body.classList.contains('g-sidenav-pinned');
        menuButton.setAttribute('aria-expanded', String(!desktop.matches && open));
        sidenav.inert = !desktop.matches && !open;
    }

    // The stock color configurator is absent from this application shell.
    if (!document.querySelector('[data-class]') && typeof navbarColorOnResize === 'function') {
        window.removeEventListener('resize', navbarColorOnResize);
    }

    menuButton.addEventListener('click', function() {
        syncNavigation();
        if (!sidenav.inert) closeButton.focus();
    });
    closeButton.addEventListener('click', function() {
        syncNavigation();
        menuButton.focus();
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !desktop.matches && document.body.classList.contains('g-sidenav-pinned')) {
            toggleSidenav();
            syncNavigation();
            menuButton.focus();
        }
    });
    desktop.addEventListener('change', function() {
        document.body.classList.remove('g-sidenav-pinned');
        syncNavigation();
    });
    syncNavigation();
});
