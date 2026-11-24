<?php

use klr\DateHandler;
use klr\AjaxResponse;
use klr\SessionMgmt;

use klr\Paging;
use klr\AjaxManager;
use klr\APIRead;
use klr\APIManager;
use klr\IDConstants;

	$defRPP = Paging::RPP_25 ;

	$data = array();

	$_POST['req'] = AjaxResponse::request();
	if (isset($_POST['req'])){
		
		// read LIST ARRAY
		$req = $_POST['req'];
		
		// read BI VALUES
		$md = isset($req['MD']) ? $req['MD'] : null ;
		$element = isset($req['ELEM']) ? $req['ELEM'] : null ;
		$from = isset($req['FROM']) ? $req['FROM'] : null ;
		$to = isset($req['TO']) ? $req['TO'] : null ;
		$id = isset($req['ID']) ? $req['ID'] : null ;
		$page = isset($req['PG']) ? $req['PG'] : 1 ;
		$rpp = isset($req['RPP']) ? $req['RPP'] : $defRPP ;
		$sort = isset($req['SC']) ? $req['SC'] : AjaxManager::SORTCOL_ID ;
		$fltr = isset($req['FT']) ? $req['FT'] : null ;
		$qr = isset($list['QR']) ? $list['QR'] : null ;

		

        switch ($md) {
            case AjaxManager::MD_SERVICE_LOCATIONS_GET:
            case AjaxManager::MD_SERVICE_TYPES_GET:
                if (!SessionMgmt::isManagerAcl(SessionMgmt::getSyAcl())) { AjaxResponse::error(403); }
                if ($id !== null && !AjaxResponse::positiveId($id)) { AjaxResponse::error(400); }
                $result = $md === AjaxManager::MD_SERVICE_LOCATIONS_GET
                    ? APIRead::getServiceLocations() : APIRead::getServiceTypes($id);
                $result = AjaxResponse::validateRead($result, true);
                AjaxResponse::send(200, $result[APIManager::DATA][APIManager::DATA]);
                break;

            case AjaxManager::MD_SERVICE_OPTIONS_GET:
                $action = $req['ACTION'] ?? null;
                if (!AjaxResponse::uuid($element) || !in_array($action, array('drop_off', 'pickup'), true)) {
                    AjaxResponse::error(400);
                }
                $result = AjaxResponse::validateRead(APIRead::getServiceOptions($element, $action), false);
                AjaxResponse::send(200, $result[APIManager::DATA][APIManager::DATA]);
                break;

            case AjaxManager::MD_SERVICE_HISTORY_GET:
                if (!AjaxResponse::uuid($element)) { AjaxResponse::error(400); }
                $result = AjaxResponse::validateRead(APIRead::getServiceHistory($element), true);
                AjaxResponse::send(200, $result[APIManager::DATA][APIManager::DATA]);
                break;

        	case AjaxManager::SY_USER_LIST_GET :
				$res = AjaxResponse::validateRead(APIRead::getUsersList(), true);

				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_EQ_GET :
				$res = AjaxResponse::validateRead(APIRead::getEqByUUID($element), false);

				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_GET :
				$res = AjaxResponse::validateRead(APIRead::getLocByUUID($element), false);

				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_AVAILIBLE_GET :
				$res = AjaxResponse::validateRead(APIRead::getAvailibleRealEstate($sort, $fltr, ($req['SERVICE'] ?? null) === '1'), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_TRANS_AVAILIBLE_GET :
				$res = AjaxResponse::validateRead(APIRead::getAvailibleTrans($sort, $fltr), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_MY_TRANS_GET :
				$res = AjaxResponse::validateRead(APIRead::getMyTrans(), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_MY_TRANS_RETURN_GET :
				$res = AjaxResponse::validateRead(APIRead::getMyTransReturn(), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_LOC_EQ_ON_LOC_GET :
				$res = AjaxResponse::validateRead(APIRead::getEqOnLoc($id), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_EQ_TYPE_LIST_GET :
				$res = AjaxResponse::validateRead(APIRead::getEqTypeList(), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

            case AjaxManager::MD_LOC_TELEPORT_DESTINATIONS_GET :
                if (!SessionMgmt::isManagerAcl(SessionMgmt::getSyAcl())) { AjaxResponse::error(403); }
                $res = AjaxResponse::validateRead(APIRead::getTeleportDestinations(), true);
                $destinations = $res[APIManager::DATA][APIManager::DATA];
                AjaxResponse::requireFields($destinations, array('id', 'uuid', 'name', 'code', 'typeId'));
                foreach ($destinations as $destination) {
                    if (!is_int($destination['id']) || $destination['id'] <= 1 || !AjaxResponse::uuid($destination['uuid'])
                        || !is_string($destination['name']) || !is_string($destination['code'])
                        || !in_array($destination['typeId'], array(IDConstants::LOC_TRANSPORT_TYPE,
                            IDConstants::LOC_CONSTRUCTION_SITE_TYPE, IDConstants::LOC_WAREHOUSE_TYPE), true)) {
                        AjaxResponse::error(502);
                    }
                }
                AjaxResponse::send(200, $destinations);
                break;

            case AjaxManager::MD_CHECKLIST_OPTIONS_GET :
                if (!SessionMgmt::isManagerAcl(SessionMgmt::getSyAcl())) { AjaxResponse::error(403); }
                if (!AjaxResponse::positiveId($id)) { AjaxResponse::error(400); }
                $res = AjaxResponse::validateRead(APIRead::getChecklistOptions($id), true);
                AjaxResponse::send(200, $res[APIManager::DATA][APIManager::DATA]);
                break;

            case AjaxManager::MD_EQ_SECONDARY_TYPE_LIST_GET :
                if (!AjaxResponse::positiveId($id)) { AjaxResponse::error(400); }
                $res = AjaxResponse::validateRead(APIRead::getEqSecondaryTypeList($id), true);
                $data['CODE'] = $res[APIManager::CODE];
                $data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
                break;

			case AjaxManager::MD_LOC_WAREHOUSE_LIST_GET :
				$res = AjaxResponse::validateRead(APIRead::getActiveWarehouseList(), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_LOC_SITES_LIST_GET :
				$res = AjaxResponse::validateRead(APIRead::getActiveSitesList(), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_LOC_TRANS_LIST_GET :
				$res = AjaxResponse::validateRead(APIRead::getActiveTrans($sort, $fltr), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_EQ_ACTIVE_LIST_GET :
				$res = AjaxResponse::validateRead(APIRead::getActiveEqList(), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_EQ_LIST_GET :
				$res = AjaxResponse::validateRead(APIRead::getAllEqList(), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_EQ_HISTORY_GET :
				$res = AjaxResponse::validateRead(APIRead::getEqTransitions($element, null, null), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					
					$transitions = $res[APIManager::DATA][APIManager::DATA];
                    AjaxResponse::requireFields($transitions, array('parentFrom','parentFromName','parentFromType','parentTo','parentToName','parentToType','time'));
                    foreach ($transitions as &$transition) {
                        if (!array_key_exists('isTeleport', $transition)) { $transition['isTeleport'] = 0; }
                        elseif (!in_array($transition['isTeleport'], array(0, 1), true)) { AjaxResponse::error(502); }
                    }
                    unset($transition);


					$history = array();

					for ($i = 0 ; $i < count($transitions)-1; $i += 1){
						$curr = $transitions[$i];
						
						// copy all data
						$row = array();
						$row["parentFrom"] = $curr["parentFrom"];
						$row["parentFromName"] = $curr["parentFromName"];
						$row["parentFromType"] = $curr["parentFromType"];
						$row["parentTo"] = $curr["parentTo"];
						$row["parentToName"] = $curr["parentToName"];
						$row["parentToType"] = $curr["parentToType"];
						$row["time"] = $curr["time"];
						$row["isTeleport"] = $curr["isTeleport"];

						if ($curr["parentToType"] == IDConstants::LOC_TRANSPORT_TYPE){

							$from = $curr["time"];
							$to = $transitions[$i+1]["time"];

							$currRes = AjaxResponse::validateRead(APIRead::getTransTransitions($curr["parentTo"], $from, $to), true);
							
							$data['CODE'] = $currRes[APIManager::CODE];
							if ($currRes[APIManager::CODE] == 200) {
								$currTransData = $currRes[APIManager::DATA][APIManager::DATA];
                                AjaxResponse::requireFields($currTransData, array('parentFrom','parentFromName','parentTo','parentToName','time'));

								$transTransitions = array();

								for ($j = 0 ; $j < count($currTransData); $j += 1){
									$currTransTransition = $currTransData[$j];
									$transTransitions[] = array(
										"parentFrom" => $currTransTransition["parentFrom"],
										"parentFromName" => $currTransTransition["parentFromName"],
										"parentTo" => $currTransTransition["parentTo"],
										"parentToName" => $currTransTransition["parentToName"],
										"time" => $currTransTransition["time"],
									);
								}

								$row["locationHistory"] = $transTransitions;
								
							}
							else{
								$data['DATA'] = array();
								break;
							}
									
						}
						else{
							$row["locationHistory"] = array();
						}

						$history[] = $row;

					}

					if($transitions){
						//handle last row
						$last = $transitions[count($transitions)-1];
						$history[] = array(
							"parentFrom" => $last["parentFrom"],
							"parentFromName" => $last["parentFromName"],
							"parentFromType" => $last["parentFromType"],
							"parentTo" => $last["parentTo"],
							"parentToName" => $last["parentToName"],
							"parentToType" => $last["parentToType"],
							"time" => $last["time"],
							"isTeleport" => $last["isTeleport"],
							"locationHistory" => array(),
						);
					}

					$data['DATA'] = $history;
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_LOC_TRANS_HISTORY_GET :
				$res = AjaxResponse::validateRead(APIRead::getTransTransitions($element, $from, $to), true);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {


					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

//*            case ReadData::MD_ARTTYPE_FULL :
//*
//*				$cntAll = ReadData::getMdArtTypeCnt($db, $fltr) ;
//*				// calculate paging parameters
//*				$paging = new SQLPaging($rpp, $cntAll[ReadData::COLCNT]);
//*				// retrieve data with SQL
//*				$list = ReadData::getMdArtTypeList($db, $sort, $fltr, $paging->getOffset($page), $paging->getRPP() );
//*
//*				$d = array();
//*				foreach($list as $el) {
//*					$row = array();
//*					$row[ReadData::COL01] = $el[MdArtType::$id] ;
//*					$row[ReadData::COL02] = $el[MdArtType::$code] ;
//*					$row[ReadData::COL03] = $el[MdArtType::$uuid] ;
//*					$row[ReadData::COL04] = $el[MdArtType::$name] ;
//*					
//*					array_push($d, $row) ;
//*				} // foreach
//*
//*
//*				$data['CNT'] = $cntAll[ReadData::COLCNT];
//*				$data['DATA'] = $d;
//*                break ;
//*
//*
//*			case ReadData::MD_ARTTYPE_SLCT :
//*				
//*				$list = ReadData::getMdArtTypeList($db, ReadData::SORTCOL_NAME, $fltr, 0, $cntAll[ReadData::COLCNT] );
//*
//*				$d = array();
//*				// modify data for export
//*				foreach($list as $el) {
//*
//*					$row = array();
//*					$row[ReadData::COL01] = $el[MdArtType::$id] ;
//*					$row[ReadData::COL02] = $el[MdArtType::$name] ;
//*
//*					array_push($d, $row) ;
//*
//*				} // foreach
//*				
//*				$data['CNT'] = count($d);
//*				$data['DATA'] = $d;
//*
//*				break;

        } // switch

    } // if


    if (!isset($data['CODE'])) { AjaxResponse::error(400); }
    AjaxResponse::send($data['CODE'], $data['DATA']);

?>	