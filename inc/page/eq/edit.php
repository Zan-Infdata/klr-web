<?php
use klr\Environment;
use klr\AjaxManager;
use klr\IDConstants;
use klr\UUID;
use klr\ReqURI;
use klr\Output;
use klr\Lang;

$serviceLabels = array();
foreach (array('originalWarranty', 'noWarranty', 'history', 'noHistory', 'loadError', 'ready', 'needsService',
    'inService', 'fixed', 'broken', 'warranty', 'locked', 'currentLocation') as $key) {
    $serviceLabels[$key] = Lang::getRsrc('equipmentService', $key);
}

echo "<div class='row'> ";
echo " <div class='col-xl-4 col-lg-5 col-md-7 d-flex flex-column'> ";
echo "     <div class='card card-plain'> ";
echo "         <div class='card-header'> ";
echo "             <h4 class='font-weight-bolder'>Edit Equipment</h4> ";
echo "             <p class='mb-0'>".Output::html(ReqURI::getID())."</p> ";
echo "         </div> ";
echo "         <div class='card-body'> ";
echo "             <form role='form'> ";

echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>CODE</label> ";
echo "                    <input id='code-inp' disabled type='text' class='form-control'> ";
echo "                    <div class='invalid-feedback'>Invalid code </div> ";
echo "                </div> ";

echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>NAME</label> ";
echo "                    <input id='name-inp' disabled type='text' class='form-control'> ";
echo "                    <div class='invalid-feedback'>Invalid name </div> ";
echo "                </div> ";



$typeLabels = array();
foreach (array('primary', 'secondary', 'loading', 'loadError', 'noPrimary', 'noSecondary', 'savedUnavailable', 'choose', 'retry') as $key) {
    $typeLabels[$key] = Lang::getRsrc('equipmentTypes', $key);
}
echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label' for='type-inp'>".Output::html($typeLabels['primary'])."</label> ";
echo "                    <select id='type-inp' class='form-select form-control' disabled></select> ";
echo "                </div> ";
echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label' for='secondary-type-inp'>".Output::html($typeLabels['secondary'])."</label> ";
echo "                    <select id='secondary-type-inp' class='form-select form-control' disabled></select> ";
echo "                </div> ";
echo "                <p id='equipment-service-lock' class='text-sm d-none'>".Output::html($serviceLabels['locked'])."</p>";
echo "                <p id='equipment-type-status' class='text-sm text-secondary mb-1' role='status'></p> ";
echo "                <button id='equipment-type-retry' type='button' class='btn btn-outline-secondary btn-sm d-none'>".Output::html($typeLabels['retry'])."</button> ";
echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label' >WAREHOUSE</label> ";
echo "                    <select id='wrh-inp' class='form-select form-control' disabled></select> ";
echo "                </div> ";



echo "                <div class='form-check form-check-info text-start ps-0'> ";
echo "                    <input id='isActive-inp' disabled class='form-check-input' type='checkbox' value='' checked> ";
echo "                    <label class='form-check-label' for='flexCheckDefault'> ";
echo "                    Is active</a> ";
echo "                    </label> ";
echo "                </div> ";

echo "                <dl class='text-sm mt-3'>";
echo "                  <dt>".Output::html($serviceLabels['currentLocation'])."</dt><dd id='current-location'></dd>";
echo "                  <dt>".Output::html(Lang::getRsrc('equipmentService', 'title'))."</dt><dd id='current-service-status'></dd>";
echo "                  <dt>".Output::html($serviceLabels['originalWarranty'])."</dt><dd id='original-warranty-value'></dd>";
echo "                </dl>";
echo "                <div class='text-center'> ";
echo "                    <button id='editBtn' type='button' disabled class='btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0'>Edit</button> ";
echo "                </div> ";

echo "             </form> ";
echo "         </div> ";
echo "         <div class='card-footer text-center pt-0 px-lg-2 px-1'> ";
echo "         </div> ";
echo "     </div> ";
echo " </div> ";


echo "    <div class='col-lg-4 col-md-6'> ";
echo "        <div class='card h-100'> ";
echo "            <div class='card-header pb-0'> ";
echo "                <h6>History</h6> ";
echo "                <p class='text-sm'> ";
echo "                <i class='fa fa-arrow-up text-success' aria-hidden='true'></i> ";
echo "                <span class='font-weight-bold'>24%</span> this month ";
echo "                </p> ";
echo "            </div> ";
echo "            <div class='card-body p-3 overflow-auto' style='max-height: 700px;'> ";
echo "                <div class='timeline timeline-one-side' id='timeline-card'> ";
//HERE COMES THE HISTORY
echo "                </div> ";
echo "            </div> ";
echo "        </div> ";
echo "    </div> ";
echo " <div class='col-lg-4 col-md-6 mt-3 mt-lg-0'><div class='card'>";
echo "   <div class='card-header pb-0'><h6>".Output::html($serviceLabels['history'])."</h6></div>";
echo "   <div class='card-body p-3'><ul class='list-unstyled mb-0' id='equipment-service-history'></ul></div>";
echo " </div></div>";
echo "</div> ";




echo " <script> ";

echo " const serviceLabels = ".Output::json($serviceLabels)."; ";
echo " const equipmentFormReadUrl = ".Output::json($mdReadURL)."; ";
echo " const equipmentTypeActions = ".Output::json(array('primary' => AjaxManager::MD_EQ_TYPE_LIST_GET, 'secondary' => AjaxManager::MD_EQ_SECONDARY_TYPE_LIST_GET))."; ";
echo " const equipmentTypeLabels = ".Output::json($typeLabels)."; ";
echo " function getEquipmentData() { ";
echo "     return readEquipmentFormData(equipmentFormReadUrl, {MD: '".AjaxManager::MD_EQ_GET."', ELEM: ".Output::json(ReqURI::getID())."}); ";
echo " } ";
echo " function loadEquipmentForm() { ";
echo "     const warehouses = readEquipmentFormData(equipmentFormReadUrl, {MD: '".AjaxManager::MD_LOC_WAREHOUSE_LIST_GET."'}); ";
echo "     return $.when(getEquipmentData(), warehouses).then(function(data, warehouseOptions) { ";
echo "         if (!data || !/^[1-9][0-9]*$/.test(String(data.typeId)) || !Number.isInteger(data.secondaryTypeId) ";
echo "             || data.secondaryTypeId < 1 || !Array.isArray(warehouseOptions)) { throw new Error('Invalid equipment form data'); } ";
echo "         generateSelectOptions(warehouseOptions, 'wrh-inp'); ";
echo "         fillData(data); ";
echo "         getHistory(data.uuid); ";
echo "         loadServiceHistory(data.uuid); ";
echo "         data.typeLocked = data.serviceStatusId === ".IDConstants::EQ_STATUS_IN_SERVICE."; ";
echo "         return data; ";
echo "     }); ";
echo " } ";
echo " const submitEquipmentForm = setupEquipmentTypeFields(equipmentFormReadUrl, equipmentTypeActions, 'editBtn', equipmentTypeLabels, loadEquipmentForm); ";

echo " function checkValues(data){";
echo "    let check = true;";

echo "    if (data.code == '' || data.code == undefined){";
echo "      check = false;";
echo "      $('#code-inp').addClass('is-invalid');";
echo "    }";

echo "    if (data.name == '' || data.name == undefined){";
echo "      check = false;";
echo "      $('#name-inp').addClass('is-invalid');";
echo "    }";

echo "    return check;";

echo " }";

echo "   function getHistory(uuid){";

echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_EQ_HISTORY_GET."';    ";
echo "        req.ELEM = uuid;    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";
echo "                 const code = data.CODE; ";
echo "                 const history = data.DATA; ";
echo "                 if(code === 200){ ";
echo "                     displayHistory(history); ";
echo "                 } ";
echo "                 else if(code == 401){ ";
echo "                     return redirectToLogin(); ";
echo "                 } ";
echo "              }, ";
echo "              error: function(xhr,opts,err){  ";

    echo "                  ajaxFailure(xhr); ";


echo "              } ";
echo "         } ); ";	

echo "   }";


echo " function displayHistory(history){";
echo "     const timelineCard = $('#timeline-card'); ";
echo "     const teleportLabel = ".Output::json(Lang::getRsrc('equipmentTeleport', 'submit'))."; ";

echo "     for (i in history){";
echo "         let curr = history[i];";
echo "         let isOut = curr.parentToType == ".IDConstants::LOC_TRANSPORT_TYPE."; ";
echo "         let historyPart = getEqHistory(curr, isOut, teleportLabel); ";
echo "         timelineCard.prepend(historyPart.html); ";
echo "         const locHistory = displayTransHistory(curr.locationHistory);";
echo "         if (locHistory){ ";
echo "              timelineCard.prepend(locHistory); ";
echo "         }";
echo "     }";
echo " }";

echo " function displayTransHistory(history){";
echo "     if (history.length == 0) return false; ";

echo "     const locationHistoryComponent = getLocationHistoryContainer(); ";
echo "     for (j in history){"; 
echo "        let currLoc = history[j];";
echo "        let transHistoryPart = getEqTransHistory(currLoc);";
echo "        locationHistoryComponent.html.prepend(transHistoryPart.html); ";
echo "     }";

echo "     return locationHistoryComponent.html; ";
echo " }";

echo " function loadServiceHistory(uuid) { ";
echo "   const list = $('#equipment-service-history').empty(); ";
echo "   readEquipmentFormData(equipmentFormReadUrl, {MD: '".AjaxManager::MD_SERVICE_HISTORY_GET."', ELEM: uuid}).done(function(rows) { ";
echo "     if (!Array.isArray(rows) || rows.length === 0) { list.text(serviceLabels.noHistory); return; } ";
echo "     for (const row of rows) { ";
echo "       const outcome = row.isBroken === 1 ? serviceLabels.broken : serviceLabels.fixed; ";
echo "       const warranty = row.warrantyDate ? serviceLabels.warranty + ' · ' + row.warrantyDate : serviceLabels.noWarranty; ";
echo "       $('<li>').addClass('border-bottom pb-2 mb-2').append($('<strong>').text(outcome), $('<div>').text(row.time), $('<div>').text(warranty)).appendTo(list); ";
echo "     } ";
echo "   }).fail(function(response) { if (response.status === 401) { redirectToLogin(); return; } list.text(serviceLabels.loadError); }); ";
echo " } ";

echo " function fillData(data){";

echo "   $('#code-inp').val(data.code); ";
echo "   $('#name-inp').val(data.name); ";
echo "   $('#isActive-inp').prop('checked', data.isActive == 1); ";

echo "   $('#wrh-inp').val(data.parentId); ";
echo "   const inService = data.serviceStatusId === ".IDConstants::EQ_STATUS_IN_SERVICE."; ";
echo "   $('#current-location').text(data.parentName || ''); ";
echo "   $('#current-service-status').text(({1: serviceLabels.ready, 2: serviceLabels.needsService, 3: serviceLabels.inService, 4: serviceLabels.broken})[data.serviceStatusId] || ''); ";
echo "   $('#original-warranty-value').text(data.originalWarrantyDate || serviceLabels.noWarranty); ";
echo "   $('#equipment-service-lock').toggleClass('d-none', !inService); ";
echo "   $('#code-inp, #name-inp').prop('disabled', false); ";
echo "   $('#isActive-inp').prop('disabled', inService); ";

echo "   $('#code-inp, #name-inp, #wrh-inp').each(function() { $(this).closest('.input-group-outline').toggleClass('is-filled', this.value !== ''); }); ";

echo " }";


echo " $('#editBtn').on('click', function() { ";
echo "   if ($(this).prop('disabled')) { return; } ";

echo "   $('.form-control').removeClass('is-invalid');";
echo "   let elemData = {};";

echo "   elemData.uuid = ".Output::json(ReqURI::getID())."; ";
echo "   elemData.code = $('#code-inp').val().trim(); ";
echo "   elemData.name = $('#name-inp').val().trim(); ";
echo "   elemData.isActive = $('#isActive-inp').is(':checked') ? 1 : 0; ";

echo "   const typeId = $('#type-inp').val(); ";
echo "   const secondaryTypeId = $('#secondary-type-inp').val(); ";

echo "   if(!checkValues(elemData)){";
echo "      return; ";
echo "   }";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_EQ_UPDATE."';    ";
echo "   req.ELEM = elemData;    ";
echo "   req.TYPE = typeId;    ";
echo "   req.TYPE_SECONDARY = secondaryTypeId; ";

echo "   return submitEquipmentForm(function() { return submitMutation('".$mdUpdateURL."', req, function() { window.location.reload(); }); }); ";
echo " });";


echo " </script> ";


?>