<?php
use klr\Environment;
use klr\AjaxManager;
use klr\Lang;
use klr\IDConstants;

$filterLabels = array();
foreach (array('uuid', 'name', 'clear', 'count', 'empty', 'loading', 'failed', 'selectTransport') as $key) {
    $filterLabels[$key] = Lang::getRsrc('equipmentList', $key);
}

$serviceStatuses = array(
    IDConstants::EQ_STATUS_READY => array('className' => 'bg-success', 'label' => Lang::getRsrc('equipmentList', 'serviceReady')),
    IDConstants::EQ_STATUS_NEEDS_SERVICE => array('className' => 'bg-danger', 'label' => Lang::getRsrc('equipmentList', 'serviceNeedsService')),
    IDConstants::EQ_STATUS_IN_SERVICE => array('className' => 'bg-warning', 'label' => Lang::getRsrc('equipmentList', 'serviceInService')),
    IDConstants::EQ_STATUS_BROKEN => array('className' => 'bg-danger', 'label' => Lang::getRsrc('equipmentService', 'broken')),
    'unknown' => array('className' => 'bg-secondary', 'label' => Lang::getRsrc('equipmentList', 'serviceUnknown')),
);

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
echo "                <table class='table align-items-center mb-0 mobile-list-table'> ";
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
echo "              <fieldset id='eq-list-filters' class='equipment-list-filters border-0 px-3 pt-3 pb-2 m-0' disabled> ";
echo "                <div class='row g-3'> ";
echo "                  <div class='col-12 col-md-6'> ";
echo "                    <label class='form-label mb-1' for='eq-filter-uuid'>".htmlspecialchars($filterLabels['uuid'], ENT_QUOTES, 'UTF-8')."</label> ";
echo "                    <input id='eq-filter-uuid' type='search' class='form-control px-2' autocomplete='off' spellcheck='false'> ";
echo "                  </div> ";
echo "                  <div class='col-12 col-md-6'> ";
echo "                    <label class='form-label mb-1' for='eq-filter-name'>".htmlspecialchars($filterLabels['name'], ENT_QUOTES, 'UTF-8')."</label> ";
echo "                    <input id='eq-filter-name' type='search' class='form-control px-2' autocomplete='off'> ";
echo "                  </div> ";
echo "                </div> ";
echo "                <div class='d-flex justify-content-end mt-2'> ";
echo "                  <button id='eq-filter-clear' type='button' class='btn btn-outline-secondary btn-sm mb-0'>".htmlspecialchars($filterLabels['clear'], ENT_QUOTES, 'UTF-8')."</button> ";
echo "                </div> ";
echo "              </fieldset> ";
echo "              <p id='eq-filter-count' class='text-sm text-secondary px-3 mb-2' role='status' aria-live='polite'>".htmlspecialchars($filterLabels['selectTransport'], ENT_QUOTES, 'UTF-8')."</p> ";
echo "              <div class='table-responsive p-0'> ";
echo "                <table class='table align-items-center mb-0 mobile-list-table'> ";
echo "                  <thead> ";
echo "                    <tr> ";
echo "                      <th class='text-secondary opacity-7'></th> ";
echo "                      <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Name</th> ";
echo "                      <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>UUID</th> ";
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
echo " let equipmentRows = []; ";
echo " let equipmentLoadId = 0; ";
echo " const equipmentServiceStatuses = ".json_encode($serviceStatuses, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)."; ";
echo " const equipmentFilterLabels = ".json_encode($filterLabels, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)."; ";

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
echo "                 if(code === 200){ ";
echo "                     generateTransportRows(transData); ";
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
echo "         getMyTransportData();";
echo "         $('#eq-filter-uuid, #eq-filter-name').on('input', filterEquipmentRows); ";
echo "         $('#eq-filter-clear').on('click', function() { ";
echo "             $('#eq-filter-uuid, #eq-filter-name').val(''); ";
echo "             filterEquipmentRows(); ";
echo "         }); ";
echo "      }) ; ";


echo " function generateTransportRows(data){";
echo "    const parent = $('#trnsp-tbl'); ";
echo "    for (i in data){";
echo "        const transCard = getTransportRow(data[i], chooseHandler);";
echo "        parent.append(transCard.card); ";
echo "    }";
echo " }";

echo " function generateEquipmentRows(data) { ";
echo "     const parent = $('#eq-tbl').empty(); ";
echo "     equipmentRows = []; ";
echo "     data.forEach(function(item) { ";
echo "         const eqCard = getEquipmentRow(item, leaveHandler, false, equipmentServiceStatuses); ";
echo "         equipmentRows.push({row: eqCard.card, uuid: String(item.uuid || '').toLowerCase(), name: String(item.name || '').toLowerCase()}); ";
echo "         parent.append(eqCard.card); ";
echo "     }); ";
echo "     const emptyRow = $('<tr>').attr('id', 'eq-filter-empty'); ";
echo "     emptyRow.append($('<td>').attr('colspan', 4).addClass('text-center text-sm text-secondary py-4').text(equipmentFilterLabels.empty)); ";
echo "     parent.append(emptyRow); ";
echo "     $('#eq-list-filters').prop('disabled', false); ";
echo "     filterEquipmentRows(); ";
echo " } ";
echo "  ";
echo " function filterEquipmentRows() { ";
echo "     const uuid = $('#eq-filter-uuid').val().trim().toLowerCase(); ";
echo "     const name = $('#eq-filter-name').val().trim().toLowerCase(); ";
echo "     let shown = 0; ";
echo "     equipmentRows.forEach(function(item) { ";
echo "         const matches = item.uuid.includes(uuid) && item.name.includes(name); ";
echo "         item.row.toggleClass('d-none', !matches); ";
echo "         if (matches) { shown += 1; } ";
echo "     }); ";
echo "     $('#eq-filter-empty').toggleClass('d-none', shown !== 0); ";
echo "     $('#eq-filter-count').text(equipmentFilterLabels.count.replace('{shown}', shown).replace('{total}', equipmentRows.length)); ";
echo " } ";
echo "  ";
echo " function leaveHandler(data){";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_LOC_EQ_LEAVE."';    ";
echo "   req.ELEM = data.uuid;    ";
echo "   req.PRNT_FROM = transportData.uuid;    ";
echo "   req.PRNT_TO = transportData.parentUuid;    ";

echo "   return submitMutation('".$mdUpdateURL."', req, function() { chooseHandler(transportData); }); ";

echo " }";

echo " function chooseHandler(data){ ";

echo "   transportData = data;";
echo "   const loadId = ++equipmentLoadId; ";
echo "   equipmentRows = []; ";
echo "   $('#eq-tbl').empty(); ";
echo "   $('#eq-list-filters').prop('disabled', true); ";
echo "   $('#eq-filter-count').text(equipmentFilterLabels.loading); ";

echo "   let req = {};  ";
echo "   req.MD = '".AjaxManager::MD_LOC_EQ_ON_LOC_GET."';    ";
echo "   req.ID = data.id;    ";


echo "   $.ajax({ ";
echo "       url: '".$mdReadURL."', ";
echo "       method: 'POST', ";
echo "       data: { req: req }, ";
echo "       dataType: 'json', ";
echo "       success: function(data) { ";
echo "           if (loadId !== equipmentLoadId) { return; } ";
echo "           const code = data.CODE; ";
echo "           const eqData = data.DATA; ";
echo "           if(code === 200){ ";
echo "               generateEquipmentRows(eqData); ";
echo "           } ";
echo "           else if(code == 401){ ";
echo "               return redirectToLogin(); ";
echo "           } ";
echo "           else { $('#eq-filter-count').text(equipmentFilterLabels.failed); } ";
echo "       }, ";
echo "       error: function(xhr, status, error) { ";
echo "           if (loadId !== equipmentLoadId) { return; } ";
echo "           $('#eq-filter-count').text(equipmentFilterLabels.failed); ";
echo "            ";
echo "           ajaxFailure(xhr); ";
echo "       } ";
echo "   }); ";

echo " }";





echo " </script> ";


?>