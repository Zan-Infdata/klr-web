<?php
use klr\Environment;
use klr\AjaxManager;
use klr\Lang;
use klr\IDConstants;

$filterLabels = array();
foreach (array('uuid', 'name', 'location', 'status', 'allLocations', 'allStatuses', 'stored', 'taken', 'clear', 'count', 'empty', 'loading', 'failed') as $key) {
    $filterLabels[$key] = Lang::getRsrc('equipmentList', $key);
}

$locationGroups = array(
    array('typeId' => IDConstants::LOC_WAREHOUSE_TYPE, 'label' => Lang::getRsrc('equipmentList', 'locationWarehouses')),
    array('typeId' => IDConstants::LOC_CONSTRUCTION_SITE_TYPE, 'label' => Lang::getRsrc('equipmentList', 'locationSites')),
    array('typeId' => IDConstants::LOC_TRANSPORT_TYPE, 'label' => Lang::getRsrc('equipmentList', 'locationTransports')),
    array('typeId' => 0, 'label' => Lang::getRsrc('equipmentList', 'locationOther')),
);

$serviceStatuses = array(
    IDConstants::EQ_STATUS_READY => array('className' => 'bg-success', 'label' => Lang::getRsrc('equipmentList', 'serviceReady')),
    IDConstants::EQ_STATUS_NEEDS_SERVICE => array('className' => 'bg-danger', 'label' => Lang::getRsrc('equipmentList', 'serviceNeedsService')),
    IDConstants::EQ_STATUS_IN_SERVICE => array('className' => 'bg-warning', 'label' => Lang::getRsrc('equipmentList', 'serviceInService')),
    'unknown' => array('className' => 'bg-secondary', 'label' => Lang::getRsrc('equipmentList', 'serviceUnknown')),
);

echo "    <div class='row'> ";
echo "        <div class='col-12'> ";
echo "          <div class='card my-4'> ";
echo "            <div class='card-header p-0 position-relative mt-n4 mx-3 z-index-2'> ";
echo "              <div class='bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3'> ";
echo "                <h6 class='text-white text-capitalize ps-3'>Equipment table</h6> ";
echo "              </div> ";
echo "            </div> ";
echo "            <div class='card-body px-0 pb-2'> ";
echo "              <fieldset id='eq-list-filters' class='equipment-list-filters border-0 px-3 pt-3 pb-2 m-0' disabled> ";
echo "                <div class='row g-3'> ";
echo "                  <div class='col-12 col-md-6 col-xl-3'> ";
echo "                    <label class='form-label mb-1' for='eq-filter-uuid'>".htmlspecialchars($filterLabels['uuid'], ENT_QUOTES, 'UTF-8')."</label> ";
echo "                    <input id='eq-filter-uuid' type='search' class='form-control px-2' autocomplete='off' spellcheck='false'> ";
echo "                  </div> ";
echo "                  <div class='col-12 col-md-6 col-xl-3'> ";
echo "                    <label class='form-label mb-1' for='eq-filter-name'>".htmlspecialchars($filterLabels['name'], ENT_QUOTES, 'UTF-8')."</label> ";
echo "                    <input id='eq-filter-name' type='search' class='form-control px-2' autocomplete='off'> ";
echo "                  </div> ";
echo "                  <div class='col-12 col-md-6 col-xl-3'> ";
echo "                    <label class='form-label mb-1' for='eq-filter-location'>".htmlspecialchars($filterLabels['location'], ENT_QUOTES, 'UTF-8')."</label> ";
echo "                    <select id='eq-filter-location' class='form-select px-2'> ";
echo "                      <option value=''>".htmlspecialchars($filterLabels['allLocations'], ENT_QUOTES, 'UTF-8')."</option> ";
echo "                    </select> ";
echo "                  </div> ";
echo "                  <div class='col-12 col-md-6 col-xl-3'> ";
echo "                    <label class='form-label mb-1' for='eq-filter-status'>".htmlspecialchars($filterLabels['status'], ENT_QUOTES, 'UTF-8')."</label> ";
echo "                    <select id='eq-filter-status' class='form-select px-2'> ";
echo "                      <option value=''>".htmlspecialchars($filterLabels['allStatuses'], ENT_QUOTES, 'UTF-8')."</option> ";
echo "                      <option value='stored'>".htmlspecialchars($filterLabels['stored'], ENT_QUOTES, 'UTF-8')."</option> ";
echo "                      <option value='taken'>".htmlspecialchars($filterLabels['taken'], ENT_QUOTES, 'UTF-8')."</option> ";
echo "                    </select> ";
echo "                  </div> ";
echo "                </div> ";
echo "                <div class='d-flex justify-content-end mt-2'> ";
echo "                  <button id='eq-filter-clear' type='button' class='btn btn-outline-secondary btn-sm mb-0'>".htmlspecialchars($filterLabels['clear'], ENT_QUOTES, 'UTF-8')."</button> ";
echo "                </div> ";
echo "              </fieldset> ";
echo "              <p id='eq-filter-count' class='text-sm text-secondary px-3 mb-2' role='status' aria-live='polite'>".htmlspecialchars($filterLabels['loading'], ENT_QUOTES, 'UTF-8')."</p> ";
echo "              <div class='table-responsive p-0'> ";
echo "                <table class='table align-items-center mb-0 mobile-list-table'> ";
echo "                  <thead> ";
echo "                    <tr> ";
echo "                      <th class='text-secondary opacity-7'></th> ";
echo "                      <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Name</th> ";
echo "                      <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Function</th> ";
echo "                      <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Status</th> ";
echo "                      <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Location</th> ";
echo "                      <th class='text-secondary opacity-7'></th> ";
echo "                    </tr> ";
echo "                  </thead> ";
echo "                  <tbody id='eq-tbl'> ";

echo "                  </tbody> ";
echo "                </table> ";
echo "              </div> ";
echo "            </div> ";
echo "          </div> ";
echo "        </div> ";
echo "        </div> ";


echo " <script> ";

echo " let equipmentRows = []; ";
echo " const equipmentLocationGroups = ".json_encode($locationGroups, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)."; ";
echo " const equipmentServiceStatuses = ".json_encode($serviceStatuses, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)."; ";
echo " const equipmentFilterLabels = ".json_encode($filterLabels, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)."; ";

echo "      function getEquipmenttData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_EQ_LIST_GET."';    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";

echo "                 const code = data.CODE; ";
echo "                 const eqData = data.DATA; ";
echo "                 if(code === 200){ ";
echo "                     generateEquipmentRows(eqData); ";
echo "                 } ";
echo "                 else if(code == 401){ ";
echo "                     return redirectToLogin(); ";
echo "                 } ";
echo "                 else { ";
echo "                     $('#eq-filter-count').text(equipmentFilterLabels.failed); ";
echo "                 } ";
echo "              }, ";
echo "              error: function(xhr,opts,err){  ";
echo "                  if (xhr.status == 401) { return redirectToLogin(); } ";
echo "                  $('#eq-filter-count').text(equipmentFilterLabels.failed); ";

    echo "                  ajaxFailure(xhr); ";


echo "              } ";
echo "         } ); ";	
echo "      } ";

// page ready funciton
echo "      $(document).ready( function() { ";
echo "         $('#eq-filter-uuid, #eq-filter-name').on('input', filterEquipmentRows); ";
echo "         $('#eq-filter-location, #eq-filter-status').on('change', filterEquipmentRows); ";
echo "         $('#eq-filter-clear').on('click', function() { ";
echo "             $('#eq-list-filters input, #eq-list-filters select').val(''); ";
echo "             filterEquipmentRows(); ";
echo "         }); ";
echo "         getEquipmenttData();";
echo "      }) ; ";



echo " function generateEquipmentRows(data) { ";
echo "     const parent = $('#eq-tbl'); ";
echo "     parent.empty(); ";
echo "     equipmentRows = []; ";
echo "     const locations = new Map(); ";
echo "     const locationSelect = $('#eq-filter-location'); ";
echo "     const selectedLocation = locationSelect.val(); ";
echo "     locationSelect.children().not(':first').remove(); ";
echo "     for (const item of data) { ";
echo "         const eqCard = getEquipmentListRow(item, '', equipmentServiceStatuses, equipmentFilterLabels); ";
echo "         eqCard.card.find('.badge').text(item.parent.isWarehouse ? equipmentFilterLabels.stored : equipmentFilterLabels.taken); ";
echo "         const location = String(item.parent.id ?? ''); ";
echo "         equipmentRows.push({ ";
echo "             row: eqCard.card, ";
echo "             uuid: String(item.uuid || '').toLowerCase(), ";
echo "             name: String(item.name || '').toLowerCase(), ";
echo "             location: location, ";
echo "             status: item.parent.isWarehouse ? 'stored' : 'taken' ";
echo "         }); ";
echo "         if (location) { locations.set(location, {id: location, name: String(item.parent.name || ''), typeId: Number(item.parent.typeId)}); } ";
echo "         parent.append(eqCard.card); ";
echo "     } ";
echo "     const knownTypes = new Set(equipmentLocationGroups.filter(function(group) { return group.typeId !== 0; }).map(function(group) { return group.typeId; })); ";
echo "     let hasGroups = false; ";
echo "     equipmentLocationGroups.forEach(function(group) { ";
echo "         const groupLocations = Array.from(locations.values()).filter(function(location) { ";
echo "             return group.typeId === 0 ? !knownTypes.has(location.typeId) : location.typeId === group.typeId; ";
echo "         }).sort(function(a, b) { return a.name.localeCompare(b.name); }); ";
echo "         if (groupLocations.length === 0) { return; } ";
echo "         if (hasGroups) { ";
echo "             locationSelect.append($('<option>').val('__divider-' + group.typeId).prop('disabled', true).attr('aria-hidden', 'true').text('────────────')); ";
echo "         } ";
echo "         const options = $('<optgroup>').attr('label', group.label); ";
echo "         groupLocations.forEach(function(location) { options.append($('<option>').val(location.id).text(location.name)); }); ";
echo "         locationSelect.append(options); ";
echo "         hasGroups = true; ";
echo "     }); ";
echo "     locationSelect.val(locations.has(selectedLocation) ? selectedLocation : ''); ";
echo "     const emptyRow = $('<tr>').attr('id', 'eq-filter-empty'); ";
echo "     emptyRow.append($('<td>').attr('colspan', 6).addClass('text-center text-sm text-secondary py-4').text(equipmentFilterLabels.empty)); ";
echo "     parent.append(emptyRow); ";
echo "     $('#eq-list-filters').prop('disabled', false); ";
echo "     filterEquipmentRows(); ";
echo " } ";
echo "  ";
echo " function filterEquipmentRows() { ";
echo "     const uuid = $('#eq-filter-uuid').val().trim().toLowerCase(); ";
echo "     const name = $('#eq-filter-name').val().trim().toLowerCase(); ";
echo "     const location = $('#eq-filter-location').val(); ";
echo "     const status = $('#eq-filter-status').val(); ";
echo "     let shown = 0; ";
echo "     equipmentRows.forEach(function(item) { ";
echo "         const matches = item.uuid.includes(uuid) && item.name.includes(name) ";
echo "             && (!location || item.location === location) ";
echo "             && (!status || item.status === status); ";
echo "         item.row.toggleClass('d-none', !matches); ";
echo "         if (matches) { shown += 1; } ";
echo "     }); ";
echo "     $('#eq-filter-empty').toggleClass('d-none', shown !== 0); ";
echo "     $('#eq-filter-count').text(equipmentFilterLabels.count.replace('{shown}', shown).replace('{total}', equipmentRows.length)); ";
echo " } ";

echo " </script> ";


?>