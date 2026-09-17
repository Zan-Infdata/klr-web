<?php

use klr\Environment;
use klr\AjaxManager;
use klr\APIManager;

echo " <div id='card-container' class='row'> ";

echo " </div> ";


echo " <script> ";

echo "      function getWarehouseData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_LOC_SITES_LIST_GET."';    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";
echo "                 const code = data.CODE; ";
echo "                 const transData = data.DATA; ";

echo "                 if(code == 200){ ";
echo "                     generateSiteCards(transData); ";
echo "                     getTransportData(); ";
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

echo "      function getTransportData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_LOC_TRANS_LIST_GET."';    ";

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


echo " function fillLoactionCard(data, parent){ ";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_LOC_EQ_ON_LOC_GET."';    ";
echo "   req.ID = data.id;    ";

echo "   $.ajax({ ";
echo "       url: '".$mdReadURL."', ";
echo "       method: 'POST', ";
echo "       data: { req: req }, ";
echo "       dataType: 'json', ";
echo "       success: function(data) { ";
echo "           const code = data.CODE; ";
echo "           const eqData = data.DATA; ";

echo "           if(code == 200){ ";
echo "               generateEqItems(eqData, parent); ";
echo "           } ";
echo "           else if(code == 401){ ";
echo "               redirectToLogin(); ";
echo "           } ";
echo "       }, ";
echo "       error: function(xhr, status, error) { ";
echo "           console.error('API call failed:', error); ";
echo "           alert('Failed to process request. Please try again.'); ";
echo "       } ";
echo "   }); ";

echo " }";

// page ready funciton
echo "      $(document).ready( function() { ";
echo "         getWarehouseData();";
echo "      }) ; ";


echo " function generateEqItems(data, parent){";
echo "    for (i in data){";
echo "        const eqItem = getEqListItemLayout(data[i]);";
echo "        parent.append(eqItem.item); ";
echo "    }";
echo " }";

echo " function generateSiteCards(data){";
echo "    const parent = $('#card-container'); ";
echo "    for (i in data){";
echo "        const siteCard = getSiteCard(data[i]);";
echo "        fillLoactionCard(data[i], siteCard.list);";
echo "        parent.append(siteCard.card); ";
echo "    }";
echo " }";

echo " function generateTransportCards(data){";
echo "    for (i in data){";
echo "        const parent = $('#site-'+data[i].parentId); ";
echo "        const transCard = getTransportCardLayout(data[i]);";
echo "        fillLoactionCard(data[i], transCard.list);";
echo "        parent.append(transCard.card); ";
echo "    }";
echo " }";


echo " </script> ";

?>