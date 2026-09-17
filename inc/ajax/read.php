<?php

use klr\DateHandler;
use klr\SessionMgmt;

use klr\Paging;
use klr\AjaxManager;
use klr\APIRead;
use klr\APIManager;
use klr\IDConstants;

	$defRPP = Paging::RPP_25 ;

	$data = array();

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

        	case AjaxManager::SY_USER_LIST_GET :
				$res = APIRead::getUsersList();

				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_EQ_GET :
				$res = APIRead::getEqByUUID($element);

				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_GET :
				$res = APIRead::getLocByUUID($element);

				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_AVAILIBLE_GET :
				$res = APIRead::getAvailibleRealEstate($sort, $fltr);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_TRANS_AVAILIBLE_GET :
				$res = APIRead::getAvailibleTrans($sort, $fltr);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_MY_TRANS_GET :
				$res = APIRead::getMyTrans();
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

        	case AjaxManager::MD_LOC_MY_TRANS_RETURN_GET :
				$res = APIRead::getMyTransReturn();
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_LOC_EQ_ON_LOC_GET :
				$res = APIRead::getEqOnLoc($id);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_EQ_TYPE_LIST_GET :
				$res = APIRead::getEqTypeList();
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_LOC_WAREHOUSE_LIST_GET :
				$res = APIRead::getActiveWarehouseList();
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_LOC_SITES_LIST_GET :
				$res = APIRead::getActiveSitesList();
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_LOC_TRANS_LIST_GET :
				$res = APIRead::getActiveTrans($sort, $fltr);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_EQ_ACTIVE_LIST_GET :
				$res = APIRead::getActiveEqList();
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_EQ_LIST_GET :
				$res = APIRead::getAllEqList();
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					$data['DATA'] = $res[APIManager::DATA][APIManager::DATA];
				}
				else {
					$data['DATA'] = array();
				} 
				break;

			case AjaxManager::MD_EQ_HISTORY_GET :
				$res = APIRead::getEqTransitions($element, null, null);
				
				$data['CODE'] = $res[APIManager::CODE];
				if ($res[APIManager::CODE] == 200) {
					
					$transitions = $res[APIManager::DATA][APIManager::DATA];


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

						if ($curr["parentToType"] == IDConstants::LOC_TRANSPORT_TYPE){

							$from = $curr["time"];
							$to = $transitions[$i+1]["time"];

							$currRes = APIRead::getTransTransitions($curr["parentTo"], $from, $to);
							
							$data['CODE'] = $currRes[APIManager::CODE];
							if ($currRes[APIManager::CODE] == 200) {
								$currTransData = $currRes[APIManager::DATA][APIManager::DATA];

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
				$res = APIRead::getTransTransitions($element, $from, $to);
				
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


    echo json_encode($data);	

?>	