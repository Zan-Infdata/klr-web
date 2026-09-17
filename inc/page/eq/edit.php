<?php
use klr\Environment;
use klr\AjaxManager;
use klr\IDConstants;
use klr\UUID;
use klr\ReqURI;

echo "<div class='row'> ";
echo " <div class='col-xl-4 col-lg-5 col-md-7 d-flex flex-column'> ";
echo "     <div class='card card-plain'> ";
echo "         <div class='card-header'> ";
echo "             <h4 class='font-weight-bolder'>Edit Equipment</h4> ";
echo "             <p class='mb-0'>".ReqURI::getID()."</p> ";
echo "         </div> ";
echo "         <div class='card-body'> ";
echo "             <form role='form'> ";

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



echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>TYPE</label> ";
echo "                    <select id='type-inp' class='form-select form-control'></select> ";
echo "                </div> ";
echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label' >WAREHOUSE</label> ";
echo "                    <select id='wrh-inp' class='form-select form-control' disabled></select> ";
echo "                </div> ";



echo "                <div class='form-check form-check-info text-start ps-0'> ";
echo "                    <input id='isActive-inp' class='form-check-input' type='checkbox' value='' checked> ";
echo "                    <label class='form-check-label' for='flexCheckDefault'> ";
echo "                    Is active</a> ";
echo "                    </label> ";
echo "                </div> ";

echo "                <div class='text-center'> ";
echo "                    <button id='editBtn' type='button' class='btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0'>Edit</button> ";
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
echo "</div> ";




echo " <script> ";

echo "      function getSelectEqTypeData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_EQ_TYPE_LIST_GET."';    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";
echo "                 const code = data.CODE; ";
echo "                 const selectData = data.DATA; ";
echo "                 if(code == 200){ ";
echo "                     generateSelectOptions(selectData, 'type-inp'); ";
echo "                 } ";
echo "                 else if(code == 401){ ";
echo "                     redirectToLogin(); ";
echo "                 } ";
echo "              }, ";
echo "              error: function(xhr,opts,err){  ";

if (Environment::DEBUG){
    echo "                  alert(xhr.responseText + ':' + err); ";
    echo "                  console.log(xhr.responseText + ':' + err); ";
} // if

echo "              } ";
echo "         } ); ";	
echo "      } ";

echo "      function getSelectWarehouseData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_LOC_WAREHOUSE_LIST_GET."';    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";
echo "                 const code = data.CODE; ";
echo "                 const selectData = data.DATA; ";
echo "                 if(code == 200){ ";
echo "                     generateSelectOptions(selectData, 'wrh-inp'); ";
echo "                 } ";
echo "                 else if(code == 401){ ";
echo "                     redirectToLogin(); ";
echo "                 } ";
echo "              }, ";
echo "              error: function(xhr,opts,err){  ";

if (Environment::DEBUG){
    echo "                  alert(xhr.responseText + ':' + err); ";
    echo "                  console.log(xhr.responseText + ':' + err); ";
} // if

echo "              } ";
echo "         } ); ";	
echo "      } ";

echo "      function getEquipmentData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_EQ_GET."';    ";
echo "        req.ELEM = '".ReqURI::getID()."';    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";
echo "                 const code = data.CODE; ";
echo "                 const eqData = data.DATA; ";

echo "                 if(code == 200){ ";
echo "                     fillData(eqData); ";
echo "                     getHistory(eqData.uuid); ";
echo "                 } ";
echo "                 else if(code == 401){ ";
echo "                     redirectToLogin(); ";
echo "                 } ";
echo "              }, ";
echo "              error: function(xhr,opts,err){  ";

if (Environment::DEBUG){
    echo "                  alert(xhr.responseText + ':' + err); ";
    echo "                  console.log(xhr.responseText + ':' + err); ";
} // if

echo "              } ";
echo "         } ); ";	
echo "      } ";

// page ready funciton
echo "      $(document).ready( function() { ";
echo "         getSelectEqTypeData();";
echo "         getSelectWarehouseData();";
echo "         getEquipmentData();";
echo "      }) ; ";


echo " function checkValues(data, typeId){";
echo "    let check = true;";

echo "    if (data.code == '' || data.code == undefined){";
echo "      check = false;";
echo "      $('#code-inp').addClass('is-invalid');";
echo "    }";

echo "    if (data.name == '' || data.name == undefined){";
echo "      check = false;";
echo "      $('#name-inp').addClass('is-invalid');";
echo "    }";

echo "    if (typeId < 1 ){";
echo "      check = false;";
echo "      $('#wrh-inp').addClass('is-invalid');";
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
echo "                 console.log(history); ";
echo "                 if(code == 200){ ";
echo "                     displayHistory(history); ";
echo "                 } ";
echo "                 else if(code == 401){ ";
echo "                     redirectToLogin(); ";
echo "                 } ";
echo "              }, ";
echo "              error: function(xhr,opts,err){  ";

if (Environment::DEBUG){
    echo "                  alert(xhr.responseText + ':' + err); ";
    echo "                  console.log(xhr.responseText + ':' + err); ";
} // if

echo "              } ";
echo "         } ); ";	

echo "   }";


echo " function displayHistory(history){";
echo "     const timelineCard = $('#timeline-card'); ";

echo "     for (i in history){";
echo "         let curr = history[i];";
echo "         let isOut = curr.parentToType == ".IDConstants::LOC_TRANSPORT_TYPE."; ";
echo "         let historyPart = getEqHistory(curr, isOut); ";
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

echo " function fillData(data){";

echo "   $('#code-inp').val(data.code); ";
echo "   $('#name-inp').val(data.name); ";
echo "   $('#isActive-inp').prop('checked', data.isActive == 1); ";

echo "   $('#wrh-inp').val(data.parentId); ";
echo "   $('#type-inp').val(data.typeId); ";

echo " }";


echo " $('#editBtn').on('click', function() { ";

echo "   $('.form-control').removeClass('is-invalid');";
echo "   let elemData = {};";

echo "   elemData.uuid = '".ReqURI::getID()."'; ";
echo "   elemData.code = $('#code-inp').val().trim(); ";
echo "   elemData.name = $('#name-inp').val().trim(); ";
echo "   elemData.isActive = $('#isActive-inp').is(':checked') ? 1 : 0; ";

echo "   const typeId = $('#type-inp').val(); ";

echo "   if(!checkValues(elemData, typeId)){";
echo "      return; ";
echo "   }";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_EQ_UPDATE."';    ";
echo "   req.ELEM = elemData;    ";
echo "   req.TYPE = typeId;    ";

echo "   $.ajax({ ";
echo "       url: '".$mdUpdateURL."', ";
echo "       method: 'POST', ";
echo "       data: { req: req }, ";
echo "       dataType: 'json', ";
echo "       success: function(response) { ";
echo "            const code = response.CODE; ";
echo "            const message = response.DATA; ";
echo "            if(code == 200){ ";
echo "                window.location.reload(); ";
echo "            } ";
echo "            else if(code == 401){ ";
echo "                redirectToLogin(); ";
echo "            } ";
echo "            else { ";
echo "                alert(message); ";
echo "            } ";
echo "       }, ";
echo "       error: function(xhr, status, error) { ";
echo "           console.error('API call failed:', error); ";
echo "           alert('Failed to process request. Please try again.'); ";
echo "       } ";
echo "   }); ";
echo " });";


echo " </script> ";


?>