<?php
use klr\Environment;
use klr\AjaxManager;

echo "    <div class='row'> ";
echo "        <div class='col-md-6 col-sm-12'> ";
echo "          <div class='card my-4'> ";

echo "            <div class='card-header p-0 position-relative mt-n4 mx-3 z-index-2'> ";
echo "              <div class='bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3'> ";
echo "                <h6 class='text-white text-capitalize ps-3'>Your transports</h6> ";
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
echo "                      <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Employed</th> ";
echo "                      <th class='text-secondary opacity-7'></th> ";
echo "                    </tr> ";
echo "                  </thead> ";
echo "                  <tbody id='usr-tbl'> ";

echo "                  </tbody> ";
echo "                </table> ";
echo "              </div> ";
echo "            </div> ";

echo "          </div> ";
echo "        </div> ";
echo "    </div> ";


echo " <script> ";

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
echo "                 const transData = data.DATA; ";
echo "                 if(code == 200){ ";
echo "                     generateUserRows(transData); ";
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
echo "         getUsersData();";
echo "      }) ; ";


echo " function generateUserRows(data){";
echo "    const parent = $('#usr-tbl'); ";
echo "    for (i in data){";
echo "        if(data[i].selected) continue; ";
echo "        const userCard = getUserRow(data[i], chooseHandler);";
echo "        parent.append(userCard.card); ";
echo "    }";
echo " }";


echo " function chooseHandler(data){ ";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::LOGIN_AS_USER."';    ";
echo "   req.ELEM = data.id;    ";

echo "   $.ajax({ ";
echo "       url: '".$mdUserURL."', ";
echo "       method: 'POST', ";
echo "       data: { req: req }, ";
echo "       dataType: 'json', ";
echo "       success: function(data) { ";
echo "           if(data.CODE == 200){ ";
echo "               window.location.replace('/'); ";
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





echo " </script> ";


?>