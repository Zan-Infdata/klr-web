<?php
use klr\Environment;
use klr\AjaxManager;


echo "    <div class='row'> ";
echo "        <div class='col-12'> ";
echo "          <div class='card my-4'> ";
echo "            <div class='card-header p-0 position-relative mt-n4 mx-3 z-index-2'> ";
echo "              <div class='bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3'> ";
echo "                <h6 class='text-white text-capitalize ps-3'>Equipment table</h6> ";
echo "              </div> ";
echo "            </div> ";
echo "            <div class='card-body px-0 pb-2'> ";
echo "              <div class='table-responsive p-0'> ";
echo "                <table class='table align-items-center mb-0'> ";
echo "                  <thead> ";
echo "                    <tr> ";
echo "                      <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Author</th> ";
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

echo " var transportData = null; ";

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
echo "                 if(code == 200){ ";
echo "                     generateEquipmentRows(eqData); ";
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
echo "         getEquipmenttData();";
echo "      }) ; ";



echo " function generateEquipmentRows(data){";
echo "    const parent = $('#eq-tbl'); ";
echo "    parent.empty(); ";
echo "    for (i in data){";
echo "        const eqCard = getEquipmentListRow(data[i]);";
echo "        parent.append(eqCard.card); ";
echo "    }";
echo " }";




echo " </script> ";


?>