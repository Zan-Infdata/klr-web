function redirectToLogin(){
  console.log(window.location);
  window.location.replace('/auth');
}



function getLocationCard(cardData, onClickHandler){

    const cardHtml = `
        <div class='col-lg-3 col-6'>
            <div class='card'>
                <div class='card-header m-auto p-3 text-center'>
                    <div class='icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg'>
                        <i class='bi bi-truck'></i>
                    </div>
                </div>
                <div class='card-body pt-0 p-3 text-center'>
                    <h6 class='text-center mb-0'>${cardData.name}</h6>
                    <span class='text-xs'>${cardData.uuid}</span>
                    <br/>
                    <span class='text-xs'>${cardData.name}</span>
                    <hr class='horizontal dark my-3'>
                    <button class='btn btn-primary'>Move to location</button>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    const $button = $card.find('button.btn-primary');

    $button.on('click', function() {
        $button.prop('disabled', true).text('Processing...');
        onClickHandler(cardData);
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}

function getEqListItemLayout(itemData){
    const itemHtml = `
        <li>${itemData.name}</li>
        `;

    const $item = $(itemHtml);

    out = {"item" : $item}

    return out
    
}

function getTransportCardLayout(cardData){

  const takenBadge = cardData.statusId == 3 ? "<span class='badge bg-info'>taken</span>" : "";

    const cardHtml = `
        <div class='col-lg-12 col-6'>
            <div class='card'>
                <div class='card-header m-auto p-3 text-center'>
                    <div class='icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg'>
                        <i class='bi bi-truck'></i>
                    </div>
                </div>
                <div class='card-body pt-0 p-3 text-center'>
                    <h6 class='text-center mb-0'>${cardData.name}</h6>
                    ${takenBadge}
                    <ul id='trans-${cardData.id}'>
                    </ul>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    const $list = $card.find('ul');

    out = {"card" : $card, "list" : $list}

    return out
    
}

function getTransportCard(cardData, onClickHandler, btnText="Take"){
    const cardHtml = `
        <div class='col-lg-3 col-6'>
            <div class='card'>
                <div class='card-header m-auto p-3 text-center'>
                    <div class='icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg'>
                        <i class='bi bi-truck'></i>
                    </div>
                </div>
                <div id="div-${cardData.code}" class='card-body pt-0 p-3 text-center'>
                    <h6 class='text-center mb-0'>${cardData.name}</h6>
                    <span class='text-xs'>${cardData.code}</span>
                    <br>
                    <span class='text-xs'>${cardData.parentName}</span>
                    <hr class='horizontal dark my-3'>
                    <button class='btn btn-primary'>${btnText}</button>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    const $button = $card.find('button.btn-primary');

    $button.on('click', function() {
        $button.prop('disabled', true).text('Processing...');
        onClickHandler(cardData);
        $button.prop('disabled', false).text(btnText);
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}


function getSiteCard(cardData){
    const cardHtml = `
        <div class='col-lg-3 col-6'>
            <div class='card'>
                <div class='card-header m-auto p-3 text-center'>
                    ${cardData.name}
                </div>
                <div id='site-${cardData.id}' class='card-body pt-0 p-3 text-center'>
                    <ul id='site-list-${cardData.id}'></ul>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    const $list = $card.find('ul');

    out = { "card" : $card, "list" : $list }

    return out
    
}

function getTransportReturnCard(cardData, onClickHandler, btnText="Return"){
    const cardHtml = `
        <div class='col-lg-3 col-6'>
            <div class='card'>
                <div class='card-header m-auto p-3 text-center'>
                    <div class='icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg'>
                        <i class='bi bi-truck'></i>
                    </div>
                </div>
                <div class='card-body pt-0 p-3 text-center'>
                    <h6 class='text-center mb-0'>${cardData.code}</h6>
                    <span class='text-xs'>${cardData.uuid}</span>
                    <span class='text-xs'>${cardData.parentName}</span>
                    <br/>
                    <span class='text-s'>Items in transport: ${cardData.childCount}</span>
                    <hr class='horizontal dark my-3'>
                    <button class='btn btn-primary'>${btnText}</button>
                </div>
            </div>
        </div>
        `;

    const $card = $(cardHtml);
    const $button = $card.find('button.btn-primary');

    $button.on('click', function() {
      $button.prop('disabled', true).text('Processing...');
      onClickHandler(cardData);
      $button.prop('disabled', false).text(btnText);
    });
      
    out = {"card" : $card, "btn": $button}

    return out
    
}


function getTransportRow(cardData, onClickHandler){
    const cardHtml = `
                    <tr> 
                      <td> 
                        <div class='d-flex px-2 py-1'> 
                          <div> 
                            <img src='/klrweb/static/img/team-2.jpg' class='avatar avatar-sm me-3 border-radius-lg' alt='user1'> 
                          </div> 
                          <div class='d-flex flex-column justify-content-center'> 
                            <h6 class='mb-0 text-sm'>${cardData.code}</h6> 
                            <p class='text-xs text-secondary mb-0'>${cardData.uuid}</p> 
                          </div> 
                        </div> 
                      </td> 
                     <td> 
                        <p class='text-xs font-weight-bold mb-0'>Located at:</p> 
                        <p class='text-xs text-secondary mb-0'>${cardData.parentName}</p> 
                      </td> 
                      <td class='align-middle text-center text-sm'> 
                        <span class='badge badge-sm bg-gradient-success'>Online</span> 
                      </td> 
                      <td class='align-middle text-center'> 
                        <span class='text-secondary text-xs font-weight-bold'>23/04/18</span> 
                      </td> 
                      <td class='align-middle'> 
                        <button class='btn btn-primary btn-transport'>Chose</button>
                      </td> 
                    </tr>
    `;
    const $card = $(cardHtml);
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
                      <td> 
                        <div class='d-flex px-2 py-1'> 
                          <div> 
                            <img src='/klrweb/static/img/team-2.jpg' class='avatar avatar-sm me-3 border-radius-lg' alt='user1'> 
                          </div> 
                          <div class='d-flex flex-column justify-content-center'> 
                            <h6 class='mb-0 text-sm'>${cardData.name}</h6> 
                            <p class='text-xs text-secondary mb-0'>${cardData.surname}</p> 
                          </div> 
                        </div> 
                      </td> 
                     <td> 
                        <p class='text-xs font-weight-bold mb-0'>email:</p> 
                        <p class='text-xs text-secondary mb-0'>${cardData.email}</p> 
                      </td> 
                      <td class='align-middle text-center text-sm'> 
                        <span class='badge badge-sm bg-gradient-success'>Online</span> 
                      </td> 
                      <td class='align-middle text-center'> 
                        <span class='text-secondary text-xs font-weight-bold'>23/04/18</span> 
                      </td> 
                      <td class='align-middle'> 
                        <button class='btn btn-primary btn-transport'>Log in as user</button>
                      </td> 
                    </tr>
    `;
    const $card = $(cardHtml);
    const $button = $card.find('button.btn-primary');

    $button.on('click', function() {
        $(".btn-transport").prop('disabled', false).text('Chose');
        $button.prop('disabled', true).text('Active...');
        onClickHandler(cardData);
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}

function getEquipmentRow(cardData, onClickHandler, isTake){

    btnText = isTake ? "Take" : "Leave";
    btnTextActive = isTake ? "Taking..." : "Leaving...";

    const cardHtml = `
            <tr>
              <td>
                <div class='d-flex px-2 py-1'>
                  <div>
                    <img src='/klrweb/static/img/team-2.jpg' class='avatar avatar-sm me-3 border-radius-lg' alt='user1'>
                  </div>
                  <div class='d-flex flex-column justify-content-center'>
                    <h6 class='mb-0 text-sm'>${cardData.name}</h6>
                    <p class='text-xs text-secondary mb-0'>${cardData.code}</p>
                  </div>
                </div>
              </td>
              <td>
                <p class='text-xs font-weight-bold mb-0'>${cardData.uuid}</p>
                <p class='text-xs text-secondary mb-0'>Equipment</p>
              </td>
              <td class='align-middle text-center text-sm'>
                <span class='badge badge-sm bg-gradient-success'>Online</span>
              </td>
              <td class='align-middle text-center'>
                <span class='text-secondary text-xs font-weight-bold'>TODO</span>
              </td>
              <td class='align-middle'>
                <button class='btn btn-outline-primary'>${btnText}</button>
              </td>
            </tr>
    `;
    const $card = $(cardHtml);
    const $button = $card.find('button.btn-outline-primary');

    $button.on('click', function() {
        $button.prop('disabled', true).text(btnTextActive);
        onClickHandler(cardData);
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}

function getEquipmentListRow(cardData, urlpfx=""){

    const href = urlpfx+'edit/'+cardData.uuid

    const badge_class = cardData.parent.isWarehouse ? "bg-gradient-success" : "bg-gradient-warning";
    const status_txt = cardData.parent.isWarehouse ? "Stored" : "Taken";

    const cardHtml = `
            <tr>
              <td>
                <div class='d-flex px-2 py-1'>
                  <div>
                    <img src='/klrweb/static/img/team-2.jpg' class='avatar avatar-sm me-3 border-radius-lg' alt='user1'>
                  </div>
                  <div class='d-flex flex-column justify-content-center'>
                    <h6 class='mb-0 text-sm'>${cardData.name}</h6>
                    <p class='text-xs text-secondary mb-0'>${cardData.code}</p>
                  </div>
                </div>
              </td>
              <td>
                <p class='text-xs font-weight-bold mb-0'>${cardData.uuid}</p>
                <p class='text-xs text-secondary mb-0'>Equipment</p>
              </td>
              <td class='align-middle text-center text-sm'>
                <span class='badge badge-sm ${badge_class}'>${status_txt}</span>
              </td>
              <td class='align-middle text-center'>
                <span class='text-secondary text-xs font-weight-bold'>${cardData.parent.name}</span>
              </td>
              <td class='align-middle'>
                <a class='link-primary' href='${href}' >Edit</a>
              </td>
            </tr>
    `;
    const $card = $(cardHtml);
    const $button = $card.find('button.btn-outline-primary');

    $button.on('click', function() {
        $button.prop('disabled', true).text(btnTextActive);
        onClickHandler(cardData);
    });

    out = {"card" : $card, "btn": $button}

    return out
    
}

function getEqHistory(data, isOut){

    const color = isOut ? "text-success" : "text-danger" ;
    const icon = isOut ? "bi-box-arrow-in-up-right" : "bi-box-arrow-in-up-left" ;

    const html = `
          <div class='timeline-block mb-3'>
            <span class='timeline-step'>
              <i class='bi ${icon} ${color} text-gradient'></i>
            </span>
            <div class='timeline-content'>
              <h6 class='text-dark text-sm font-weight-bold mb-0'>${data.parentToName}</h6>
              <p class='text-secondary font-weight-bold text-xs mt-1 mb-0'>${data.time}</p>
            </div>
          </div>
    `;
    const $timeline = $(html);

    out = {"html" : $timeline}

    return out
    
}

function getEqTransHistory(data){

    const html = `
          <div class='timeline-block mb-3'>
            <span class='timeline-step'>
              <i class='bi bi-truck text-info text-gradient'></i>
            </span>
            <div class='timeline-content'>
              <h6 class='text-dark text-sm font-weight-bold mb-0'>${data.parentToName}</h6>
              <p class='text-secondary font-weight-bold text-xs mt-1 mb-0'>${data.time}</p>
            </div>
          </div>
    `;
    const $timeline = $(html);

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

function generateSelectOptions(selectData, selectorId){

    let out = []
    const $selector = $("#"+selectorId);

    for(i in selectData){
      currData = selectData[i]
      const optionHtml = `
              <option value='${currData.id}'>
                ${currData.name}
              </option>
      `;
      const $option = $(optionHtml)
      $selector.append($option);
      out.push($option);
    }

    return out
    
}


function calUserAjax(url, swtch, element) {
  let req = {};  
  req.MD = swtch;    
  req.ELEM = element;    

  $.ajax({ 
      url: url, 
      method: 'POST', 
      data: { req: req }, 
      dataType: 'json', 
      success: function(data) { 
          if(data.CODE == 200){ 
              window.location.replace('/'); 
          } 
          else if(data.CODE == 401){ 
              redirectToLogin(); 
          } 
      }, 
      error: function(xhr, status, error) { 
          console.error('API call failed:', error); 
          alert('Failed to process request. Please try again.'); 
      } 
  }); 

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

function getLayoutEquipmentRow(data, typeName, editPrefix) {
    const $row = $(`
        <tr>
            <td class='px-3 py-2'><a class='layout-equipment-name text-sm font-weight-bold'></a><p class='layout-equipment-code text-xs text-secondary mb-0'></p></td>
            <td class='layout-equipment-uuid text-xs px-3 py-2'></td>
            <td class='layout-equipment-type text-sm px-3 py-2'></td>
        </tr>
    `);
    $row.find('.layout-equipment-name').text(data.name || data.code || data.uuid || '')
        .attr('href', editPrefix + encodeURIComponent(data.uuid || ''));
    $row.find('.layout-equipment-code').text(data.code || '');
    $row.find('.layout-equipment-uuid').text(data.uuid || '');
    $row.find('.layout-equipment-type').text(typeName);
    return $row;
}
