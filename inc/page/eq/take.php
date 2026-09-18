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
echo "                  <tbody id='trnsp-tbl'> ";

echo "                  </tbody> ";
echo "                </table> ";
echo "              </div> ";
echo "            </div> ";
echo "          </div> ";
echo "        </div> ";

echo "        <div class='col-md-6 col-sm-12'> ";
echo "          <div class='card my-4'> ";
echo "            <div class='card-header p-0 position-relative mt-n4 mx-3 z-index-2'> ";
echo "              <div class='bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3'> ";
echo "                <h6 class='text-white text-capitalize ps-3'>Placeholder table</h6> ";
echo "              </div> ";
echo "            </div> ";
echo "            <div class='card-body px-0 pb-2'> ";
echo "              <div class='table-responsive p-0'> ";
echo "                <table class='table align-items-center justify-content-center mb-0'> ";
echo "                  <thead> ";
echo "                    <tr> ";
echo "                      <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Project</th> ";
echo "                      <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Budget</th> ";
echo "                      <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Status</th> ";
echo "                      <th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2'>Completion</th> ";
echo "                      <th></th> ";
echo "                    </tr> ";
echo "                  </thead> ";
echo "                  <tbody> ";
echo "                    <tr> ";
echo "                      <td> ";
echo "                        <div class='d-flex px-2'> ";
echo "                          <div> ";
echo "                            <img src='".Environment::STATIC_URL."img/small-logos/logo-asana.svg' class='avatar avatar-sm rounded-circle me-2' alt='spotify'> ";
echo "                          </div> ";
echo "                          <div class='my-auto'> ";
echo "                            <h6 class='mb-0 text-sm'>Asana</h6> ";
echo "                          </div> ";
echo "                        </div> ";
echo "                      </td> ";
echo "                      <td> ";
echo "                        <p class='text-sm font-weight-bold mb-0'>$2,500</p> ";
echo "                      </td> ";
echo "                      <td> ";
echo "                        <span class='text-xs font-weight-bold'>working</span> ";
echo "                      </td> ";
echo "                      <td class='align-middle text-center'> ";
echo "                        <div class='d-flex align-items-center justify-content-center'> ";
echo "                          <span class='me-2 text-xs font-weight-bold'>60%</span> ";
echo "                          <div> ";
echo "                            <div class='progress'> ";
echo "                              <div class='progress-bar bg-gradient-info' role='progressbar' aria-valuenow='60' aria-valuemin='0' aria-valuemax='100' style='width: 60%;'></div> ";
echo "                            </div> ";
echo "                          </div> ";
echo "                        </div> ";
echo "                      </td> ";
echo "                      <td class='align-middle'> ";
echo "                        <button class='btn btn-link text-secondary mb-0'> ";
echo "                          <i class='fa fa-ellipsis-v text-xs'></i> ";
echo "                        </button> ";
echo "                      </td> ";
echo "                    </tr> ";
echo "                  </tbody> ";
echo "                </table> ";
echo "              </div> ";
echo "            </div> ";
echo "          </div> ";
echo "        </div> ";
echo "    </div> ";

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
echo "                      <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Employed</th> ";
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

echo "      function getMyTransportData() { ";
echo "        let req = {};  ";
echo "        req.MD = '".AjaxManager::MD_LOC_MY_TRANS_GET."';    ";

echo "        $.ajax( {  ";
echo "              url:  '".$mdReadURL."', "; 
echo "              type: 'POST', ";
echo "              data: { req: req }, ";
echo "              dataType: 'json', ";

echo "              success: function(data){  ";

echo "                 const code = data.CODE; ";
echo "                 const transData = data.DATA; ";
echo "                 if(code == 200){ ";
echo "                     generateTransportRows(transData); ";
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
echo "         getMyTransportData();";
echo "      }) ; ";


echo " function generateTransportRows(data){";
echo "    const parent = $('#trnsp-tbl'); ";
echo "    for (i in data){";
echo "        const transCard = getTransportRow(data[i], chooseHandler);";
echo "        parent.append(transCard.card); ";
echo "    }";
echo " }";

echo " function generateEquipmentRows(data){";
echo "    const parent = $('#eq-tbl'); ";
echo "    parent.empty(); ";
echo "    for (i in data){";
echo "        const eqCard = getEquipmentRow(data[i], takeHandler, true);";
echo "        parent.append(eqCard.card); ";
echo "    }";
echo " }";

echo " function takeHandler(data){";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_LOC_EQ_TAKE."';    ";
echo "   req.ELEM = data.uuid;    ";
echo "   req.PRNT_TO = transportData.uuid;    ";
echo "   req.PRNT_FROM = transportData.parentUuid;    ";

echo "   $.ajax({ ";
echo "       url: '".$mdUpdateURL."', ";
echo "       method: 'POST', ";
echo "       data: { req: req }, ";
echo "       dataType: 'json', ";
echo "       success: function(data) { ";
echo "           const code = data.CODE; ";
echo "           if(code == 200){ ";
echo "               chooseHandler(transportData); ";
echo "           } ";
echo "           if(code == 401){ ";
echo "               redirectToLogin(); ";
echo "           } ";
echo "       }, ";
echo "       error: function(xhr, status, error) { ";
echo "           console.error('API call failed:', error); ";
echo "           alert('Failed to process request. Please try again.'); ";
echo "       } ";
echo "   }); ";

echo " }";

echo " function chooseHandler(data){ ";

echo "   transportData = data;";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_LOC_EQ_ON_LOC_GET."';    ";
echo "   req.ID = data.parentId;    ";

echo "   $.ajax({ ";
echo "       url: '".$mdReadURL."', ";
echo "       method: 'POST', ";
echo "       data: { req: req }, ";
echo "       dataType: 'json', ";
echo "       success: function(data) { ";
echo "           console.log(data); ";
echo "           const code = data.CODE; ";
echo "           const eqData = data.DATA; ";
echo "           if(code == 200){ ";
echo "               generateEquipmentRows(eqData); ";
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