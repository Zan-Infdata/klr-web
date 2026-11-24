<?php
use klr\Environment;
use klr\AjaxManager;
use klr\UUID;
use klr\Output;
use klr\Lang;

echo " <div class='col-xl-4 col-lg-5 col-md-7 d-flex flex-column'> ";
echo "     <div class='card card-plain'> ";
echo "         <div class='card-header'> ";
echo "             <h4 class='font-weight-bolder'>New Equipment</h4> ";
echo "             <p class='mb-0'>Create equipment</p> ";
echo "         </div> ";
echo "         <div class='card-body'> ";
echo "             <form role='form'> ";

echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>UUID</label> ";
echo "                    <input id='uuid-inp' type='text' class='form-control' value='".UUID::v4()."'> ";
echo "                    <div class='invalid-feedback'>Invalid uuid </div> ";
echo "                </div> ";

echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>CODE</label> ";
echo "                    <input id='code-inp' type='text' class='form-control'> ";
echo "                    <div class='invalid-feedback'>Invalid code </div> ";
echo "                </div> ";

echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>NAME</label> ";
echo "                    <input id='name-inp' type='text' class='form-control'> ";
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
echo "                <p id='equipment-type-status' class='text-sm text-secondary mb-1' role='status'></p> ";
echo "                <button id='equipment-type-retry' type='button' class='btn btn-outline-secondary btn-sm d-none'>".Output::html($typeLabels['retry'])."</button> ";
echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>WAREHOUSE</label> ";
echo "                    <select id='wrh-inp' class='form-select form-control' disabled></select> ";
echo "                </div> ";



echo "                <label class='form-label' for='original-warranty-inp'>".Output::html(Lang::getRsrc('equipmentService', 'originalWarranty'))."</label>";
echo "                <input class='form-control border px-2 mb-3' type='date' id='original-warranty-inp' min='1000-01-01' max='9999-12-31'>";
echo "                <div class='form-check form-check-info text-start ps-0'> ";
echo "                    <input id='isActive-inp' class='form-check-input' type='checkbox' value='' checked> ";
echo "                    <label class='form-check-label' for='flexCheckDefault'> ";
echo "                    Is active</a> ";
echo "                    </label> ";
echo "                </div> ";

echo "                <div class='text-center'> ";
echo "                    <button id='createBtn' type='button' disabled class='btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0'>Create</button> ";
echo "                </div> ";

echo "             </form> ";
echo "         </div> ";
echo "         <div class='card-footer text-center pt-0 px-lg-2 px-1'> ";
echo "         </div> ";
echo "     </div> ";
echo " </div> ";



echo " <script> ";

echo " const equipmentFormReadUrl = ".Output::json($mdReadURL)."; ";
echo " const equipmentTypeActions = ".Output::json(array('primary' => AjaxManager::MD_EQ_TYPE_LIST_GET, 'secondary' => AjaxManager::MD_EQ_SECONDARY_TYPE_LIST_GET))."; ";
echo " const equipmentTypeLabels = ".Output::json($typeLabels)."; ";
echo " function loadEquipmentForm() { ";
echo "     return readEquipmentFormData(equipmentFormReadUrl, {MD: '".AjaxManager::MD_LOC_WAREHOUSE_LIST_GET."'}).then(function(warehouses) { ";
echo "         if (!Array.isArray(warehouses) || !warehouses.length) { throw new Error('No warehouse options'); } ";
echo "         generateSelectOptions(warehouses, 'wrh-inp'); ";
echo "         $('#wrh-inp').prop('disabled', false).closest('.input-group-outline').addClass('is-filled'); ";
echo "         return null; ";
echo "     }); ";
echo " } ";
echo " const submitEquipmentForm = setupEquipmentTypeFields(equipmentFormReadUrl, equipmentTypeActions, 'createBtn', equipmentTypeLabels, loadEquipmentForm); ";

echo " function checkValues(data, wrhId){";
echo "    let check = true;";

echo "    if (data.uuid == '' || data.uuid == undefined){";
echo "      check = false;";
echo "      $('#uuid-inp').addClass('is-invalid');";
echo "    }";

echo "    if (data.code == '' || data.code == undefined){";
echo "      check = false;";
echo "      $('#code-inp').addClass('is-invalid');";
echo "    }";

echo "    if (data.name == '' || data.name == undefined){";
echo "      check = false;";
echo "      $('#name-inp').addClass('is-invalid');";
echo "    }";

echo "    if (!wrhId || wrhId < 1){";
echo "      check = false;";
echo "      $('#wrh-inp').addClass('is-invalid');";
echo "    }";

echo "    return check;";

echo " }";


echo " $('#createBtn').on('click', function() { ";
echo "   if ($(this).prop('disabled')) { return; } ";

echo "   $('.form-control').removeClass('is-invalid');";
echo "   let elemData = {};";

echo "   elemData.uuid = $('#uuid-inp').val().trim(); ";
echo "   elemData.code = $('#code-inp').val().trim(); ";
echo "   elemData.name = $('#name-inp').val().trim(); ";
echo "   elemData.warranty_date = $('#original-warranty-inp').val(); ";
echo "   elemData.isActive = $('#isActive-inp').is(':checked') ? 1 : 0; ";

echo "   const wrhId = $('#wrh-inp').val(); ";
echo "   const typeId = $('#type-inp').val(); ";
echo "   const secondaryTypeId = $('#secondary-type-inp').val(); ";

echo "   if(!checkValues(elemData, wrhId) || !$('#original-warranty-inp')[0].reportValidity()){";
echo "      return; ";
echo "   }";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_EQ_CREATE."';    ";
echo "   req.ELEM = elemData;    ";
echo "   req.PRNT = wrhId;    ";
echo "   req.TYPE = typeId;    ";
echo "   req.TYPE_SECONDARY = secondaryTypeId; ";

echo "   return submitEquipmentForm(function() { return submitMutation('".$mdCreateURL."', req, function() { window.location.reload(); }); }); ";
echo " });";


echo " </script> ";


?>