<?php

use klr\Environment;
use klr\AjaxManager;
use klr\APIManager;
use klr\IDConstants;
use klr\SessionMgmt;

echo " <div id='card-container' class='row'> ";

echo " </div> ";


echo " <script> ";

echo "      let usersData = [{'name': 'test my guy', 'id' : 2}]; ";

echo "      function getTransportData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_LOC_TRANS_AVAILIBLE_GET."';    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";
echo "                 const code = data.CODE; ";
echo "                 const transData = data.DATA; ";
echo "                 if(code === 200){ ";
echo "                     generateTransportCards(transData); ";
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



echo "      function getUsersData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::SY_USER_LIST_GET."';    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";
echo "                 const code = data.CODE; ";
echo "                 if(code === 200){ ";
echo "                     usersData = data.DATA; ";
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
echo "         getTransportData();";
echo "      }) ; ";


echo " function generateTransportCards(data){";
echo "    const parent = $('#card-container'); ";
echo "    for (i in data){";
echo "        const transCard = getTransportCard(data[i], takeHandler);";
echo "        parent.append(transCard.card); ";
echo "    }";
echo " }";

echo " function takeHandler(data){ ";
    
echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_LOC_TRANS_TAKE."';    ";
echo "   req.ELEM = data.uuid;    ";

echo "   return submitMutation('".$mdUpdateURL."', req, function() { window.location.reload(); }); ";
echo " }";


echo " </script> ";

?>