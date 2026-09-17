<?php

use klr\Environment;
use klr\AjaxManager;

echo " <div id='card-container' class='row'> ";

echo " </div> ";


echo " <script> ";

echo "      function getTransportData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_LOC_MY_TRANS_RETURN_GET."';    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";
echo "                 const code = data.CODE; ";
echo "                 const transData = data.DATA; ";
echo "                 if(code == 200){ ";
echo "                     generateTransportCards(transData); ";
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
echo "         getTransportData();";
echo "      }) ; ";


echo " function generateTransportCards(data){";
echo "    const parent = $('#card-container'); ";
echo "    for (i in data){";
echo "        const transCard = getTransportReturnCard(data[i], returnHandler, 'Return');";
echo "        parent.append(transCard.card); ";
echo "    }";
echo " }";

echo " function returnHandler(data){ ";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_LOC_TRANS_RETURN."';    ";
echo "   req.ELEM = data.uuid;    ";

echo "   $.ajax({ ";
echo "       url: '".$mdUpdateURL."', ";
echo "       method: 'POST', ";
echo "       data: { req: req }, ";
echo "       dataType: 'json', ";
echo "       success: function(response) { ";
echo "           const code = data.CODE; ";
echo "           if(code == 401){ ";
echo "               redirectToLogin(); ";
echo "           } ";
echo "           window.location.reload(); ";
echo "       }, ";
echo "       error: function(xhr, status, error) { ";
echo "           console.error('API call failed:', error); ";
echo "           alert('Failed to process request. Please try again.'); ";
echo "       } ";
echo "   }); ";
echo " }";


echo " </script> ";

?>