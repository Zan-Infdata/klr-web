<?php
use klr\Environment;
use klr\AjaxManager;
use klr\UUID;
use klr\ReqURI;
use klr\Output;

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
echo "                    <input id='code-inp' type='text' class='form-control'> ";
echo "                    <div class='invalid-feedback'>Invalid code</div> ";
echo "                </div> ";

echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>NAME</label> ";
echo "                    <input id='name-inp' type='text' class='form-control'> ";
echo "                    <div class='invalid-feedback'>Invalid name </div> ";
echo "                </div> ";

echo "                <div class='input-group input-group-outline mb-3'> ";
echo "                    <label class='form-label'>DESC</label> ";
echo "                    <input id='desc-inp' type='text' class='form-control'> ";
echo "                    <div class='invalid-feedback'>Invalid desc </div> ";
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
echo "                 if(code === 200){ ";
echo "                     generateSelectOptions(selectData, 'wrh-inp'); ";
echo "                     $('#wrh-inp').each(function() { $(this).closest('.input-group-outline').toggleClass('is-filled', this.value !== ''); }); ";
echo "                 } ";
echo "                 else if(code == 401){ ";
echo "                     return redirectToLogin(); ";
echo "                 } ";
echo "              }, ";
echo "              error: function(xhr,opts,err){  ";

    echo "                  ajaxFailure(xhr); ";


echo "              } ";
echo "         } ); ";	
echo "      } ";

echo "      function getTransportData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_LOC_GET."';    ";
echo "        req.ELEM = ".Output::json(ReqURI::getID()).";    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";
echo "                 const code = data.CODE; ";
echo "                 const trnsData = data.DATA; ";

echo "                 if(code === 200){ ";
echo "                     fillData(trnsData); ";
echo "                 } ";
echo "                 else if(code == 401){ ";
echo "                     return redirectToLogin(); ";
echo "                 } ";
echo "              }, ";
echo "              error: function(xhr,opts,err){  ";

    echo "                  ajaxFailure(xhr); ";


echo "              } ";
echo "         } ); ";	
echo "      } ";

// page ready funciton
echo "      $(document).ready( function() { ";
echo "         getSelectWarehouseData();";
echo "         getTransportData();";
echo "      }) ; ";


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

echo "    if (data.desc == '' || data.desc == undefined){";
echo "      check = false;";
echo "      $('#desc-inp').addClass('is-invalid');";
echo "    }";


echo "    return check;";

echo " }";

echo " function fillData(data){";


echo "   $('#code-inp').val(data.code); ";
echo "   $('#name-inp').val(data.name); ";
echo "   $('#desc-inp').val(data.desc); ";
echo "   $('#isActive-inp').prop('checked', data.isActive == 1); ";

echo "   $('#wrh-inp').val(data.parentId); ";

echo "   $('#code-inp, #name-inp, #desc-inp, #wrh-inp').each(function() { $(this).closest('.input-group-outline').toggleClass('is-filled', this.value !== ''); }); ";

echo " }";


echo " $('#editBtn').on('click', function() { ";
echo "   const \$actionButton = $(this); ";

echo "   $('.form-control').removeClass('is-invalid');";
echo "   let elemData = {};";

echo "   elemData.uuid = ".Output::json(ReqURI::getID())."; ";
echo "   elemData.code = $('#code-inp').val().trim(); ";
echo "   elemData.name = $('#name-inp').val().trim(); ";
echo "   elemData.desc = $('#desc-inp').val().trim(); ";
echo "   elemData.isActive = $('#isActive-inp').is(':checked') ? 1 : 0; ";

echo "   if(!checkValues(elemData)){";
echo "      return; ";
echo "   }";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_LOC_UPDATE."';    ";
echo "   req.ELEM = elemData;    ";

echo "   return runButtonAction(\$actionButton, function() { return submitMutation('".$mdUpdateURL."', req, function() { window.location.reload(); }); }); ";
echo " });";


echo " </script> ";


?>