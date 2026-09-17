<?php
use klr\Environment;
use klr\AjaxManager;
use klr\UUID;

echo " <div class='col-xl-4 col-lg-5 col-md-7 d-flex flex-column'> ";
echo "     <div class='card card-plain'> ";
echo "         <div class='card-header'> ";
echo "             <h4 class='font-weight-bolder'>New Transport</h4> ";
echo "             <p class='mb-0'>Create transport</p> ";
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

echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>DESCRIPTION</label> ";
echo "                    <input id='desc-inp' type='textarea' class='form-control'> ";
echo "                    <div class='invalid-feedback'>Invalid description </div> ";
echo "                </div> ";


echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>WAREHOUSE</label> ";
echo "                    <select id='wrh-inp' class='form-select form-control'></select> ";
echo "                </div> ";



echo "                <div class='form-check form-check-info text-start ps-0'> ";
echo "                    <input id='isActive-inp' class='form-check-input' type='checkbox' value='' checked> ";
echo "                    <label class='form-check-label' for='flexCheckDefault'> ";
echo "                    Is active</a> ";
echo "                    </label> ";
echo "                </div> ";

echo "                <div class='text-center'> ";
echo "                    <button id='createBtn' type='button' class='btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0'>Create</button> ";
echo "                </div> ";

echo "             </form> ";
echo "         </div> ";
echo "         <div class='card-footer text-center pt-0 px-lg-2 px-1'> ";
echo "         </div> ";
echo "     </div> ";
echo " </div> ";



echo " <script> ";

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

// page ready funciton
echo "      $(document).ready( function() { ";
echo "         getSelectWarehouseData();";
echo "      }) ; ";


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

echo "    if (data.desc == '' || data.desc == undefined){";
echo "      check = false;";
echo "      $('#desc-inp').addClass('is-invalid');";
echo "    }";

echo "    if (wrhId < 1 ){";
echo "      check = false;";
echo "      $('#type-inp').addClass('is-invalid');";
echo "    }";

echo "    return check;";

echo " }";


echo " $('#createBtn').on('click', function() { ";

echo "   $('.form-control').removeClass('is-invalid');";
echo "   let elemData = {};";

echo "   elemData.uuid = $('#uuid-inp').val().trim(); ";
echo "   elemData.code = $('#code-inp').val().trim(); ";
echo "   elemData.name = $('#name-inp').val().trim(); ";
echo "   elemData.desc = $('#desc-inp').val().trim(); ";
echo "   elemData.isActive = $('#isActive-inp').is(':checked') ? 1 : 0; ";

echo "   const wrhId = $('#wrh-inp').val(); ";

echo "   if(!checkValues(elemData, wrhId)){";
echo "      return; ";
echo "   }";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_TRANS_CREATE."';    ";
echo "   req.ELEM = elemData;    ";
echo "   req.PRNT = wrhId;    ";

echo "   $.ajax({ ";
echo "       url: '".$mdCreateURL."', ";
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