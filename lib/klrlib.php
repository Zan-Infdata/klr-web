<?php
/* -----------------
 * PHP klr
 * @author: ZP
 * @date: 15.12.2021
 * 
 * PHP classes definition file
 * 
 */

    namespace klr;


	class Environment {


		const DEBUG = true;

		//const SRVR_URL = 'https://klrweb.infdata.com';
		//const ROOT_URL = "";
		const SRVR_URL = 'http://klrweb.test';
		const ROOT_URL = "";

		const STATIC_URL = self::SRVR_URL.self::ROOT_URL."/static/";


		const META_DESC = "Klr Sistem";
		const META_AUTH = "ZP Infdata";

		const PAGE_TTL = "KlrWEB";


		public static $PAGE_ICON_SRC = "<link rel='icon' type='image/png' href='".self::ROOT_URL."/static/img/logo_ttl.png' />";

		public static $FONT_CSS_SRC = "<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700'> " ;
		
		public static $BOOT_TOGGLE_CSS_SRC = "<link href='".self::ROOT_URL."/static/vendor/bootstrap5-toggle-5.0.6/css/bootstrap5-toggle.min.css' type='text/css' rel='stylesheet'> " ;
		public static $BOOT_CSS_SRC = "<link href='".self::ROOT_URL."/static/vendor/bootstrap-5.0.2-dist/css/bootstrap.min.css' type='text/css' rel='stylesheet'> " ;
		public static $ICON_CSS_SRC = "<link href='".self::ROOT_URL."/static/vendor/bootstrap-icons-1.9.1/bootstrap-icons.css' type='text/css' rel='stylesheet'> ";
		
		
		public static $MATERIAL_CSS_SRC = "<link href='".self::ROOT_URL."/static/css/material-dashboard.css' type='text/css' rel='stylesheet'> ";

		public static $BOOT_TOGGLE_JS_SRC = "<script src='".self::ROOT_URL."/static/vendor/bootstrap5-toggle-5.0.6/js/bootstrap5-toggle.jquery.min.js'></script> ";
		public static $BOOT_JS_SRC = "<script src='".self::ROOT_URL."/static/vendor/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js'></script> ";
		public static $POOPER_JS_SRC = "<script src='".self::ROOT_URL."/static/vendor/material-dashboard/popper.min.js'></script> ";
		public static $JQRY_JS_SRC = "<script src='".self::ROOT_URL."/static/vendor/jquery/jquery-3.6.1.min.js'></script> "; 


		// custom stylesheets
		public static $CSTM1_CSS_SRC = "<link href='".self::ROOT_URL."/static/css/my.css' rel='stylesheet'> ";

		// custom JS
		public static $CSTM1_JS_SRC = "<script src='".self::ROOT_URL."/static/vendor/material-dashboard/material-dashboard.min.js'></script> ";
		public static $CHART_JS_SRC = "<script src='".self::ROOT_URL."/static/vendor/material-dashboard/plugins/chartjs.min.js'></script> ";
		public static $P_SCROLL_JS_SRC = "<script src='".self::ROOT_URL."/static/vendor/material-dashboard/plugins/perfect-scrollbar.min.js'></script> ";
		public static $S_SCROLL_JS_SRC = "<script src='".self::ROOT_URL."/static/vendor/material-dashboard/plugins/smooth-scrollbar.min.js'></script> ";
		
		public static $MY_JS_SRC = "<script src='".self::ROOT_URL."/static/js/klr.js'></script> ";

		// DatePicker JS
		public static $DTPC_JS_SRC = "<script src='".self::ROOT_URL."/static/vendor/datepicker/js/bootstrap-datepicker.min.js'></script> ";


	} // Environment


    class LangCode {

        const DFLT = "en_EN";
        const ENG = "en_EN";
        const SLO = "sl_SI";

		protected static $dbExtMap = array(
			self::DFLT=>''
			,self::ENG=>''
			,self::SLO=>'_SI'
        );

		public static function mapCodeToDbExt($code){
			return self::$dbExtMap[$code];
		}

    } // LangCode




    class Lang {
        
        protected static $code = LangCode::DFLT;
        protected static $rFile;

        public static function setLangCode($lc){
            self::$code = $lc;
        }


		public static function getDbExt(){
			return LangCode::mapCodeToDbExt(self::$code);
		}
        

        public static function getRsrc($sec, $ky) {
                
            if ( !isset(self::$rFile) ) {
                                
                self::$rFile = parse_ini_file('./bundle/'.self::$code.'.bundle', true);
            }

			$res = isset(self::$rFile[$sec][$ky]) ? self::$rFile[$sec][$ky] : null;
            
            return $res;
            
        }

    } // Lang
 



 
	class NumberHandler {

		private static $fmtDec  = ',';
		private static $fmtSep  = '.';
		private static $fmtCurr = 'EUR';
		
		
		public static function getFmtDec () {
			return self::$fmtDec;
		}
		public static function setFmtDec ($fd) {
			self::$fmtDec = $fd;
		}
		
		public static function getFmtSep () {
			return self::$fmtSep;
		}
		public static function setFmtSep ($fs) {
			self::$fmtSep = $fs;
		}
		
		public static function getFmtCurr () {
			return self::$fmtCurr;
		}
		public static function setFmtCurr ($fc) {
			self::$fmtCurr = $fc;
		}		
		
		
		public static function formatCurr($val) {
			return self::formatCurrDec($val, 2);	
		}
		
		
		public static function formatCurrDec($val, $dec) {
			
			return number_format($val, $dec, self::$fmtDec, self::$fmtSep)." ". self::$fmtCurr;
			
		}
		
		
		public static function formatDec($val,$dec) {

			return number_format($val, $dec, self::$fmtDec, self::$fmtSep);
			
		}
		
		public static function formatPlain($val,$dec) {

			return number_format($val, $dec, self::$fmtDec, '');
			
		}		
		
		
		public static function formatPct($val,$dec) {

			return number_format( ($val*100), $dec, self::$fmtDec, '' )."%";
			
		}			

        public static function formatLeadZeroInt($val,$lead) {

            $fmt = "%0".$lead."d";
            return sprintf($fmt,$val);
        }
		
		
		
	}	// NumberHandler







	class DateHandler {

		const DB_DATE_FORMAT = "Y-m-d";

		static public function formatDat($val) {
			
			$res = '';
			if (!empty($val)) {
				$res = date ( Lang::getRsrc('format','date'), strtotime($val) ) ;	
			}

			
			return $res ;
		}	


		static public function formatDBDat($val) {
			
			$res = '';
			if (!empty($val)) {
				$res = date ( DateHandler::DB_DATE_FORMAT, strtotime($val) ) ;	
			}

			
			return $res ;
		}	


		static public function formatDatTime($val) {
			
			$res = '';
			if (!empty($val)) {
				$res = date ( Lang::getRsrc('format','datetime'), strtotime($val) ) ;	
			}

			
			return $res ;
		}	

		static public function formatTime($val) {
			
			$res = '';
			if (!empty($val)) {
				$res = date ( Lang::getRsrc('format','time'), strtotime($val) ) ;	
			}

			
			return $res ;
		}	


	}  // DateHandler	



    // 5 level Request URI breakdown	
    class URIinx {
        const DOMAIN      = 0;   // segment 0
        const ROOT        = 1;   // segment 1
        const ENTITY      = 2;   // segment 2
        const NOUN        = 3;   // segment 3
        const ID          = 4;   // segment 4
    } // URIinx


	// Request URI class
    class ReqURI {

		const URL_SUCC_KY = "succ";
		const URL_CID_KY = "cid";
		const URL_ATID_KY = "atid";
		const URL_VAT_KY = "vat";

		const URLMAP_AUTH_KY = "auth" ;
		const URLMAP_LOGN_KY = "login" ;

		const URLMAP_AJAX_KY = "ajax" ;
		const URLMAP_PAGE_KY = "page" ;
		const URLMAP_FILE_KY = "file" ;
		const URLMAP_IMG_KY = "img" ;

		const INC_ROOT = "./inc/" ;
		const INC_EXT = ".php" ;

		const PAGE_DEF = "page/home/show" ;
		const PAGE_E404 = "page/error/404" ;
		const PAGE_E401 = "page/error/401" ;

		public static $reqArray = array();

		public static $rootMap = array(
									self::URLMAP_AUTH_KY=>'auth.php'
									,self::URLMAP_LOGN_KY=>'login.php'

									,self::URLMAP_AJAX_KY=>'ajax.php'
									,self::URLMAP_PAGE_KY=>'page.php'
									,self::URLMAP_FILE_KY=>'file.php'
									,self::URLMAP_IMG_KY=>'img.php'

		);



		public static function readURI($url) {

			$prsu = parse_url($url) ;
			$urlSeg = explode('/', $prsu['path']);

			self::setSegment($urlSeg, 0, URIinx::DOMAIN) ;
			self::setSegment($urlSeg, 1, URIinx::ROOT) ;
			self::setSegment($urlSeg, 2, URIinx::ENTITY) ;
			self::setSegment($urlSeg, 3, URIinx::NOUN) ;
			self::setSegment($urlSeg, 4, URIinx::ID) ;


		} // readURI


		private static function setSegment($seg, $inx, $segInx){

			if (isset($seg[$inx])) {
				self::$reqArray[$segInx] = $seg[$inx];
			}
			else {
				self::$reqArray[$segInx] = null ;
			}

		} // setSegment




		public static function isRootAjax(){

			return self::$reqArray[URIinx::ROOT] == self::URLMAP_AJAX_KY ? true : false ;
			
		}


		public static function isRootImg(){

			return self::$reqArray[URIinx::ROOT] == self::URLMAP_IMG_KY ? true : false ;
			
		}



		public static function isRootAuth(){

			return self::$reqArray[URIinx::ROOT] == self::URLMAP_AUTH_KY ? true : false ;
			
		}		



		public static function getRootFile($redirectLogin){
			
			// default go to auth
			$file = self::$rootMap[self::URLMAP_PAGE_KY] ;

			$rootKy = self::$reqArray[URIinx::ROOT];

			//redirect to page if login call when logged in
			if ($redirectLogin && $rootKy == self::URLMAP_LOGN_KY) {
				$rootKy = self::$rootMap[self::URLMAP_PAGE_KY] ;
			}

			if (  array_key_exists( $rootKy  , self::$rootMap) ) {
				$file = self::$rootMap[$rootKy] ;
			}
			
			return $file ;

		} // getRootFile



		public static function isIncFileName(){

			$test = false;

			if (self::$reqArray[URIinx::ENTITY] != null && self::$reqArray[URIinx::NOUN] != null) {
				$test = true;
			}

			return $test;
			
		} // isIncFileName

		public static function isAjaxFileName(){

			$test = false;

			if (self::$reqArray[URIinx::ENTITY] != null) {
				$test = true;
			}

			return $test;
			
		} // isIncFileName



		public static function getID(){

			$id = null;

			if (self::$reqArray[URIinx::ID] != null && self::$reqArray[URIinx::ID] != "") {
				$id = self::$reqArray[URIinx::ID];
			}

			return $id ;
			
		} // getID		



		public static function getIncFileName(){
			
			$file = self::INC_ROOT;
			
			$file .= isset(self::$reqArray[URIinx::ROOT])   ? self::$reqArray[URIinx::ROOT]   : "" ;
			$file .='/';
			$file .= isset(self::$reqArray[URIinx::ENTITY]) ? self::$reqArray[URIinx::ENTITY] : "" ;
			$file .='/';
			$file .= isset(self::$reqArray[URIinx::NOUN])   ? self::$reqArray[URIinx::NOUN]   : "" ;

			$file .= self::INC_EXT;
			return $file;
			
		} // getFileName

		public static function getAjaxFileName(){
			
			$file = self::INC_ROOT;
			
			$file .= isset(self::$reqArray[URIinx::ROOT])   ? self::$reqArray[URIinx::ROOT]   : "" ;
			$file .='/';
			$file .= isset(self::$reqArray[URIinx::ENTITY]) ? self::$reqArray[URIinx::ENTITY] : "" ;

			$file .= self::INC_EXT;
			return $file;
			
		} // getFileName


		public static function getDEFFileName(){
			
			$file = self::INC_ROOT.self::PAGE_DEF.self::INC_EXT;

			return $file;
			
		} // getDEFFileName


		public static function getERR404FileName(){
			
			$file = self::INC_ROOT.self::PAGE_E404.self::INC_EXT;

			return $file;
			
		} // getERR404FileName		
		

		public static function getERR401FileName(){
			
			$file = self::INC_ROOT.self::PAGE_E401.self::INC_EXT;

			return $file;

		} // getERR401FileName		



    } // ReqURI





	// Internal PAGE structure
    class Page {

		const FORM_FIELD01 = 'FF01';
		const FORM_FIELD02 = 'FF02';
		const FORM_FIELD03 = 'FF03';
		const FORM_FIELD04 = 'FF04';
		const FORM_FIELD05 = 'FF05';
		const FORM_FIELD06 = 'FF06';
		const FORM_FIELD07 = 'FF07';
		const FORM_FIELD08 = 'FF08';
		const FORM_FIELD09 = 'FF09';
		const FORM_FIELD10 = 'FF10';
		const FORM_FIELD11 = 'FF11';
		const FORM_FIELD12 = 'FF12';
		const FORM_FIELD13 = 'FF13';
		const FORM_FIELD14 = 'FF14';
		const FORM_FIELD15 = 'FF15';
		const FORM_FIELD16 = 'FF16';
		const FORM_FIELD17 = 'FF17';
		const FORM_FIELD18 = 'FF18';
		const FORM_FIELD19 = 'FF19';
		const FORM_FIELD20 = 'FF20';
		const FORM_FIELD21 = 'FF21';
		const FORM_FIELD22 = 'FF22';



		public static function renrerBreadCrumb($hrefarea, $area, $page){
			
			echo  "        <ol class='breadcrumb mb-4'>";
			echo  "            <li class='breadcrumb-item'><a href='".$hrefarea."'>".$area."</a></li>";
			echo  "            <li class='breadcrumb-item active'>".$page."</li>";
			echo  "        </ol>";


		}


	} // Page








    class SessionMgmt {

		const TIMEOUT = 1800;   //in seconds


		const AUTHERR_KY = "authERR";

		const AUTH_KY = "klrAuth";
		const SSTART_KY = "SSTART";

		const SYUSER_KY = "syUSER";
		const SYACL_KY = "syACL";
		const TKN_KY = "acTOKEN";
		const AS_USER_KY = "syUSERas";

		const REFRESH_TRY = false;

		const ADD_MDART_OK = "AAOK";
		const ADD_MDARTTY_OK = "AATOK";
		const ADD_TWO_OK = "AWOOK";


		public static function init($u, $a, $t){

			$_SESSION[self::AUTH_KY] = true;
			$_SESSION[self::SSTART_KY] = time();

			$_SESSION[self::SYUSER_KY] = $u;
			$_SESSION[self::SYACL_KY] = $a;
			
			if (!is_null($t)){
				$_SESSION[self::TKN_KY] = $t;
			}

		} // init



		
		public static function destroy(){
			
			$_SESSION = array();
			CookieManager::deleteTokenCookie();
			session_destroy();
			
		} // destroy		
		
	
		
		public static function checkAuth($rebuild){
			$r=false;

			if ( isset($_SESSION[self::SSTART_KY]) && ( (time() - $_SESSION[self::SSTART_KY]) < self::TIMEOUT) ){
				if ( isset($_SESSION[self::AUTH_KY]) && $_SESSION[self::AUTH_KY] === true ){
					if ($rebuild) {
						session_regenerate_id(true);
						$_SESSION[self::SSTART_KY] = time();
					} // if

					$r=true;
				} // if
			} // if

			return $r;
		} // checkAuth


		public static function setRegister($ky, $obj) {	

			$_SESSION[$ky] = $obj;

		} // setRegister

		public static function unsetRegister($ky) {	

			$out = false;

			if (isset($_SESSION[$ky])){
				$out = true;
				unset($_SESSION[$ky]);
			}

			return $out;

		} // unsetRegister
		
		
		
		public static function getRegister($ky) {	

			return isset($_SESSION[$ky]) ? $_SESSION[$ky] : null ;

		} // getRegister


		
		public static function getSyUser() {	

			return isset($_SESSION[self::SYUSER_KY]) ? $_SESSION[self::SYUSER_KY] : null ;

		} // getSyUser

		public static function getSyAcl() {	

			return isset($_SESSION[self::SYACL_KY]) ? $_SESSION[self::SYACL_KY] : null ;

		} // getSyAcl

		public static function getAcToken() {	

			return isset($_SESSION[self::TKN_KY]) ? $_SESSION[self::TKN_KY] : null ;

		} // getAcToken

		public static function setAcToken($t) {	

			$_SESSION[self::TKN_KY] = $t;

		} // setAcToken

		public static function getRefreshTry() {	

			return isset($_SESSION[self::REFRESH_TRY]) ? $_SESSION[self::REFRESH_TRY] : null ;

		} // getRefreshTry

		public static function setRefreshTry($rt) {	

			$_SESSION[self::REFRESH_TRY] = $rt;

		} // setRefreshTry

		public static function getAsUser() {	

			return isset($_SESSION[self::AS_USER_KY]) ? $_SESSION[self::AS_USER_KY] : null ;

		} // getAsUser

		public static function setAsUser($rt) {	

			$_SESSION[self::AS_USER_KY] = $rt;

		} // setAsUser

		public static function usetAsUser() {	
			self::unsetRegister(self::AS_USER_KY);

		} // usetAsUser

		public static function getSyAclFromData($data) {	

			return isset($data[APIManager::ACL_KY]) ? $data[APIManager::ACL_KY] : null ;

		} // getSyAclFromData

		public static function isManagerAcl($aclId) {
			return $aclId <= IDConstants::USER_MANAGER;
		}


	}  // SessionMgmt	

	class ImgComtroller {
		const ASPECT_RATIO = 0.77;
		const IMG_PIN_SIZE_PERCENT = 0.03;
	}


	class UUID {

		private $bytes;

		private function __construct() {
			// NOOP
		}

		public static function v4(): string {
			$uuid = new UUID;

			$uuid->bytes    = random_bytes(16);
			$uuid->bytes[6] = chr((ord($uuid->bytes[6]) & 0b00001111) | 0b01000000);
			$uuid->bytes[8] = chr((ord($uuid->bytes[8]) & 0b00111111) | 0b10000000);

			return $uuid->toString();
		}

		public function toBinary(): string {
			if (is_string($this->bytes) === false) {
				$type = 'unknown';

				if (is_array($this->bytes)) {
					$type = 'array';
				}
				elseif ($this->bytes === true || $this->bytes === false) {
					$type = 'boolean';
				}
				elseif (is_float($this->bytes)) {
					$type = 'float';
				}
				elseif (is_int($this->bytes)) {
					$type = 'integer';
				}
				elseif ($this->bytes === null) {
					$type = 'null';
				}
				elseif (is_object($this->bytes)) {
					$type = 'object';
				}
				elseif (is_resource($this->bytes)) {
					$type = 'resource';
				}

				throw new \TypeError('Expected ' . __CLASS__ . '::$bytes value to be of type string, but found ' . $type);
			}

			if (strlen($this->bytes) !== 16) {
				throw new \Error('Expected ' . __CLASS__ . '::$bytes value to be exactly 16 bytes long, but found ' . strlen($this->bytes));
			}

			return $this->bytes;
		}

		public function toHex(): string {
			return bin2hex($this->toBinary());
		}

		public function toString(): string {
			$hex = $this->toHex();

			return substr($hex, 0, 8) . '-' .
			       substr($hex, 8, 4) . '-' .
			       substr($hex, 12, 4) . '-' .
			       substr($hex, 16, 4) . '-' .
			       substr($hex, 20);
		}

	}


	class CookieManager {

		const COOKIE_NAME = "klr_ck";
		const COOKIE_EXPIRE = 86400*30; // in days

		public static function setTokenCookie($value) {
			setcookie(self::COOKIE_NAME, $value, time() + self::COOKIE_EXPIRE);
		}

		public static function getTokenCookie() {
			return isset($_COOKIE[self::COOKIE_NAME]) ? $_COOKIE[self::COOKIE_NAME] : null;
		}

		public static function deleteTokenCookie() {
			setcookie(self::COOKIE_NAME, "", time() - 3600);
		}

		public static function isTokenCookieValid() {

			$token = self::getTokenCookie();
			if ($token === null) {
				return null;
			}

			return JWTManager::verifyJWT($token);
		}


	}



	class JWTManager {

		public const IS_REFRESH = "is_refresh";

		protected const JWT_CNF_FILE = "web.conf";
		protected static $secret;

		private static function getSecret(){
			if (!isset(self::$secret)){
				$config = parse_ini_file(self::JWT_CNF_FILE, true);
				self::$secret = $config['secret'];
			}
			return self::$secret;
		}


		public static function base64UrlEncode($data) {
			return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
		}
		
		public static function base64UrlDecode($data) {
			return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
		}
		
		public static function createJWT($payload) {
			$header = self::base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
			$payload['exp'] = time() + 900; // Access token expires in 15 minutes
			$payloadEncoded = self::base64UrlEncode(json_encode($payload));
		
			$signature = self::base64UrlEncode(hash_hmac('sha256', "$header.$payloadEncoded", self::getSecret(), true));
			return "$header.$payloadEncoded.$signature";
		}
		
		public static function verifyJWT($token) {
			$parts = explode('.', $token);
			if (count($parts) !== 3) return null;
		
			list($header, $payload, $signature) = $parts;
			$decodedPayload = json_decode(self::base64UrlDecode($payload), true);
		
			if (isset($decodedPayload['exp']) && time() > $decodedPayload['exp']) return null;
		
			$expectedSignature = self::base64UrlEncode(hash_hmac('sha256', "$header.$payload", self::getSecret(), true));
			return hash_equals($expectedSignature, $signature) ? $decodedPayload : null;
		}




	}

	class Paging{

		const RPP_6 = 6;
		const RPP_10 = 10;
		const RPP_15 = 15;
		const RPP_25 = 25;
		const RPP_50 = 50;
		const RPP_100 = 100;

		const LIMIT_6 = 6;
		const LIMIT_200 = 200;
		const LIMIT_500 = 500;
		const LIMIT_1000 = 1000;


	}  // Paging


	class AjaxManager {
		const UNKNOWN_ID = '***';
		const UNKNOWN_UUID = '***';

		// out keys
		const ID = 'id';
		const NAME = 'name';
		const CODE = 'code';
		const DESC = 'desc';
		const UUID = 'uuid';
		const ISACTIVE = 'isActive';
		const TYPEID = 'typeId';


		const SORTCOL_ID = 1;
		const SORTCOL_CODE = 2;
		const SORTCOL_NAME = 3;
		const SORTCOL_PARM1 = 4;
		const SORTCOL_PARM2 = 5;
		const SORTCOL_PARM3 = 6;
		const SORTCOL_PARM4 = 7;
		const SORTCOL_PARM5 = 8;
		const SORTCOL_PARM6 = 9;
		const SORTCOL_FKID = 10;
		const SORTCOL_FKNAME = 11;
		const SORTCOL_FKCODE = 12;


		const MD_LOC_TRANS_AVAILIBLE_GET = "MLTG" ;
		const MD_LOC_AVAILIBLE_GET = "MLAG" ;
		const MD_EQ_GET = "MEG" ;
		const MD_EQ_HISTORY_GET = "MEHG" ;
		const MD_LOC_GET = "MLG" ;
		const MD_LOC_TRANS_HISTORY_GET = "MLTHG" ;
		const MD_LOC_MY_TRANS_GET = "MLMTG" ;
		const MD_LOC_MY_TRANS_RETURN_GET = "MLMTRG" ;
		const MD_LOC_EQ_ON_LOC_GET = "MLEOLG" ;
		const MD_LOC_WAREHOUSE_LIST_GET = "MlWLG" ;
		const MD_LOC_SITES_LIST_GET = "MlSLG" ;
		const MD_LOC_TRANS_LIST_GET = "MlTLG" ;
		const SY_USER_LIST_GET = "SULG" ;
		

		const MD_EQ_TYPE_LIST_GET = "METLG" ;
		const MD_EQ_LIST_GET = "MELG" ;
		const MD_EQ_ACTIVE_LIST_GET = "MEALG" ;

		const MD_LOC_TRANS_TAKE = "MLTTK" ;
		const MD_LOC_TRANS_RETURN = "MLTRT" ;
		const MD_LOC_TRANS_MOVE = "MLTM" ;
		const MD_LOC_EQ_TAKE = "MLETK" ;
		const MD_LOC_EQ_LEAVE = "MLELE" ;
		
		const MD_EQ_CREATE = "MEC" ;
		const MD_TRANS_CREATE = "MTRANSC" ;

		const MD_EQ_UPDATE = "MEU" ;
		const MD_LOC_UPDATE = "MLU" ;

		const LOGIN_AS_USER = "LIAU" ;
		const LOGOUT_AS_USER = "LOAU" ;
	}

	class IDConstants {
		const LOC_TRANSPORT_TYPE = 1;

		const USER_ADMIN = 1;
		const USER_OWNER = 2;
		const USER_MANAGER = 3;
		const USER_OPERATOR = 4;
	}


	class APIManager {


		const API_URL = "http://klrapi.test/";
		//const API_URL = "https://klrapi.infdata.com/";

		const CALL_ENDPOINT = "call";
		const PROTECTED_ENDPOINT = "protected";

		const PING_ENDPOINT = "ping";
		const LOGIN_ENDPOINT = "/V1/auth/login";
		const REFRESH_ENDPOINT = "/V1/auth/refresh";
		const JWT_LOGIN_ENDPOINT = "/V1/auth/jwt-login";

		const DATA = "data";
		const CODE = "code";
		const MESSAGE = "message";
		const ERR = "error";
		const TOKEN = "token"; 
		const RF_TOKEN = "refresh_token";

		const FILTER_KY	 = "fltr";
		const RPP_KY	 = "rpp";
		const PAGE_KY	 = "pge";
		const SORT_KY	 = "srt";

		const PWD_KY     = "gsl";
        const USR_KY     = "ime";

		const USER_KY = 'user';
		const ELEM_KY = 'element';
		const PRNT_FROM_KY = 'parent_from';
		const PRNT_TO_KY = 'parent_to';
		const ID_KY = 'id';
		const TYPE_KY = 'type';
		const DATE_FROM_KY = 'date_from';
		const DATE_TO_KY = 'date_to';
		const AS_USER_KY = 'as_user';

		//in keys
		const NAME_KY = 'name';
		const SURNAME_KY = 'surname';
		const ACL_KY = 'acl';
		const EMAIL_KY = 'email';

		public static function callPing(){

			$data = self::callAPI(self::PING_ENDPOINT, "GET", []);
			return $data;
		}

		public static function callLogin($username, $password) {

			$data = array(
				self::USR_KY => $username,
				self::PWD_KY => $password
			);
			$res = self::callAPI(self::LOGIN_ENDPOINT, "POST", $data);
			return $res;
		}

		public static function callRefresh(){
			$token = CookieManager::getTokenCookie();
			if ($token === null) {
				return null;
			}

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => self::API_URL .self::CALL_ENDPOINT. self::REFRESH_ENDPOINT,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POST => true,
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer '.$token,
				),
			));

			//TODO: remove this in production
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		
			$response = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
		
			$response = trim($response);
			$data = json_decode($response, true);

			return [self::DATA => $data, self::CODE => $http_code];

		}

		public static function callJWTCheck(){
			$token = CookieManager::getTokenCookie();
			if ($token === null) {
				return null;
			}

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => self::API_URL .self::CALL_ENDPOINT. self::JWT_LOGIN_ENDPOINT,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POST => true,
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer '.$token,
				),
			));

			//TODO: remove this in production
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		
			$response = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
		
			$response = trim($response);
			$data = json_decode($response, true);

			return [self::DATA => $data, self::CODE => $http_code];

		}


		public static function callAPIProtected($endpoint, $method, $data, $is_retry=false) {
			$token = SessionMgmt::getAcToken();
			if ($token === null) {
				return null;
			}

			$asUser = SessionMgmt::getAsUser();
			if ($asUser !== null) {
				$data[APIManager::AS_USER_KY] = $asUser['id'];
				error_log("DOING AS USER");
			}

			$url = self::API_URL .self::PROTECTED_ENDPOINT .$endpoint;
			if ($method === 'GET' && count($data) != 0) {
				$url .= '?'.http_build_query($data);
			}

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => $method,
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer '.$token,
				),
			  ));

			//TODO: remove this in production
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


			if ($method === 'POST') {
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			}
		
			$response = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
		
			$response = trim($response);
			$res_data = json_decode($response, true);


			if(!$is_retry && $http_code == 401){
				$token = APIManager::callRefresh();
				error_log(print_r($token, true));
				if (!$token[APIManager::DATA][APIManager::ERR]){
					$ac_token = $token[APIManager::DATA][APIManager::TOKEN];
					SessionMgmt::setAcToken($ac_token);
					return self::callAPIProtected($endpoint, $method,$data,true);
				}
			}

			return [self::DATA => $res_data, self::CODE => $http_code];


		}

		public static function callAPI($endpoint, $method, $data) {
			$curl = curl_init();

			$url = self::API_URL .self::CALL_ENDPOINT .$endpoint;
			if ($method == "GET" && count($data) != 0) {
				$url .= '?'.http_build_query($data);
			}

			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => $method,
			  ));

			//TODO: remove this in production
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


			if ($method == "POST") {
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			}
		
			$response = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
		
			$response = trim($response);
			$res_data = json_decode($response, true);

			return [self::DATA => $res_data, self::CODE => $http_code];


		}

	}


	class APIRead {

		const AVAILIBLE_TRANS_ENDPOINT = "/V1/trans/availible";
		const AVAILIBLE_LOC_ENDPOINT = "/V1/loc/availible";
		const MY_TRANS_ENDPOINT = "/V1/trans/my";
		const MY_TRANS_RETURN_ENDPOINT = "/V1/trans/my-return";
		const EQ_ON_LOC = "/V1/loc/availible-eq";
		const EQ_SHOW = "/V1/eq/show";
		const EQ_TRANSITIONS = "/V1/eq/transitions";
		const TRANS_TRANSITIONS = "/V1/trans/transitions";
		const LOC_SHOW = "/V1/loc/show";
		const EQ_TYPE_LIST = "/V1/eq/types";
		const ACTIVE_WAREHOUSE_LIST = "/V1/loc/warehouses";
		const ACTIVE_SITES_LIST = "/V1/loc/sites";
		const ACTIVE_TRANS_LIST = "/V1/trans/list";
		const ACTIVE_EQ_LIST = "/V1/eq/active";
		const ALL_EQ_LIST = "/V1/eq/list";
		const USER_LIST = "/V1/user/list";

		const GET_USER = "/V1/user/get";

		static function getAvailibleRealEstate($sort, $fltr){

			$data = array(
				APIManager::RPP_KY => Paging::LIMIT_200,
				APIManager::PAGE_KY => 1,
				APIManager::SORT_KY => $sort,
				APIManager::FILTER_KY => $fltr
			);

			$res = APIManager::callAPIProtected(self::AVAILIBLE_LOC_ENDPOINT, "GET", $data);
			return $res;
		}

		static function getAvailibleTrans($sort, $fltr){

			$data = array(
				APIManager::RPP_KY => Paging::LIMIT_200,
				APIManager::PAGE_KY => 1,
				APIManager::SORT_KY => $sort,
				APIManager::FILTER_KY => $fltr
			);

			$res = APIManager::callAPIProtected(self::AVAILIBLE_TRANS_ENDPOINT, "GET", $data);
			return $res;
		}

		static function getMyTrans(){
			$data = array();
			$res = APIManager::callAPIProtected(self::MY_TRANS_ENDPOINT, "GET", $data);
			return $res;
		}

		static function getMyTransReturn(){
			$data = array();
			$res = APIManager::callAPIProtected(self::MY_TRANS_RETURN_ENDPOINT, "GET", $data);
			return $res;
		}

		static function getEqOnLoc($element){
			$data = array (
				APIManager::ID_KY => $element
			);
			$res = APIManager::callAPIProtected(self::EQ_ON_LOC, "GET", $data);
			return $res;
		}

		static function getEqTypeList(){
			$data = array();
			$res = APIManager::callAPIProtected(self::EQ_TYPE_LIST, "GET", $data);
			return $res;
		}
		
		static function getUsersList(){
			$data = array();
			$res = APIManager::callAPIProtected(self::USER_LIST, "GET", $data);
			return $res;	
		}

		static function getUserById($uid){
			$data = array(
				APIManager::ID_KY => $uid,
			);
			
			$res = APIManager::callAPIProtected(self::GET_USER, "GET", $data);
			return $res;
		}

		static function getActiveWarehouseList(){
			$data = array();
			$res = APIManager::callAPIProtected(self::ACTIVE_WAREHOUSE_LIST, "GET", $data);
			return $res;
		}

		static function getActiveSitesList(){
			$data = array();
			$res = APIManager::callAPIProtected(self::ACTIVE_SITES_LIST, "GET", $data);
			return $res;
		}

		static function getActiveTrans(){
			$data = array();
			$res = APIManager::callAPIProtected(self::ACTIVE_TRANS_LIST, "GET", $data);
			return $res;
		}

		static function getActiveEqList(){
			$data = array();
			$res = APIManager::callAPIProtected(self::ACTIVE_EQ_LIST, "GET", $data);
			return $res;
		}

		static function getAllEqList(){
			$data = array();
			$res = APIManager::callAPIProtected(self::ALL_EQ_LIST, "GET", $data);
			return $res;
		}

		static function getEqByUUID($element){
			$data = array();
			$data = array (
				APIManager::ELEM_KY => $element
			);
			$res = APIManager::callAPIProtected(self::EQ_SHOW, "GET", $data);
			return $res;
		}

		static function getLocByUUID($element){
			$data = array();
			$data = array (
				APIManager::ELEM_KY => $element
			);
			$res = APIManager::callAPIProtected(self::LOC_SHOW, "GET", $data);
			return $res;
		}

		static function getEqTransitions($element, $dateFrom, $dateTo){
			$data = array();
			$data = array (
				APIManager::ELEM_KY => $element,
				APIManager::DATE_FROM_KY => $dateFrom,
				APIManager::DATE_TO_KY => $dateTo
			);
			$res = APIManager::callAPIProtected(self::EQ_TRANSITIONS, "GET", $data);
			return $res;
		}

		static function getTransTransitions($element, $dateFrom, $dateTo){
			$data = array();
			$data = array (
				APIManager::ELEM_KY => $element,
				APIManager::DATE_FROM_KY => $dateFrom,
				APIManager::DATE_TO_KY => $dateTo
			);
			$res = APIManager::callAPIProtected(self::TRANS_TRANSITIONS, "GET", $data);
			return $res;
		}

	}

	class APIUpdate {

		const MOVE_TRANS_ENDPOINT = "/V1/trans/move";
		const TAKE_TRANS_ENDPOINT = "/V1/trans/take";
		const RETURN_TRANS_ENDPOINT = "/V1/trans/return";
		const TAKE_EQ_ENDPOINT = "/V1/eq/take";
		const LEAVE_EQ_ENDPOINT = "/V1/eq/leave";
		const UPDATE_EQ_ENDPOINT = "/V1/eq/update";
		const UPDATE_LOC_ENDPOINT = "/V1/loc/update";

		static function moveTransport($element, $parent_from, $parent_to){

			$data = array (
				APIManager::ELEM_KY => $element,
				APIManager::PRNT_FROM_KY => $parent_from,
				APIManager::PRNT_TO_KY => $parent_to
			);

			$res = APIManager::callAPIProtected(self::MOVE_TRANS_ENDPOINT, "POST", $data);
			return $res;
		}

		static function takeTransport($element){
			$data = array (
				APIManager::ELEM_KY => $element
			);

			$res = APIManager::callAPIProtected(self::TAKE_TRANS_ENDPOINT, "POST", $data);
			return $res;
		}

		static function returnTransport($element){

			$data = array (
				APIManager::ELEM_KY => $element
			);

			$res = APIManager::callAPIProtected(self::RETURN_TRANS_ENDPOINT, "POST", $data);
			return $res;
		}

		static function takeEquipment($element, $parent_from, $parent_to){

			$data = array (
				APIManager::ELEM_KY => $element,
				APIManager::PRNT_FROM_KY => $parent_from,
				APIManager::PRNT_TO_KY => $parent_to,
			);

			$res = APIManager::callAPIProtected(self::TAKE_EQ_ENDPOINT, "POST", $data);
			return $res;
		}

		static function leaveEquipment($element, $parent_from, $parent_to){

			$data = array (
				APIManager::ELEM_KY => $element,
				APIManager::PRNT_FROM_KY => $parent_from,
				APIManager::PRNT_TO_KY => $parent_to,
			);

			$res = APIManager::callAPIProtected(self::LEAVE_EQ_ENDPOINT, "POST", $data);
			return $res;
		}

		static function updateEquipment($element, $type){

			$data = array (
				APIManager::ELEM_KY => $element,
				APIManager::TYPE_KY => $type,
			);

			$res = APIManager::callAPIProtected(self::UPDATE_EQ_ENDPOINT, "POST", $data);
			return $res;
		}

		static function updateLocation($element){

			$data = array (
				APIManager::ELEM_KY => $element,
			);

			$res = APIManager::callAPIProtected(self::UPDATE_LOC_ENDPOINT, "POST", $data);
			return $res;
		}

	}


	class APICreate {

		const CREATE_EQ_ENDPOINT = "/V1/eq/create";
		const CREATE_TRANS_ENDPOINT = "/V1/trans/create";
		
		static function createMdEq($element, $parent, $type){

			$data = array (
				APIManager::ELEM_KY => $element,
				APIManager::PRNT_TO_KY => $parent,
				APIManager::TYPE_KY => $type,
			);

			$res = APIManager::callAPIProtected(self::CREATE_EQ_ENDPOINT, "POST", $data);
			return $res;
		}

		static function createMdTrans($element, $parent){

			$data = array (
				APIManager::ELEM_KY => $element,
				APIManager::PRNT_TO_KY => $parent,
			);

			$res = APIManager::callAPIProtected(self::CREATE_TRANS_ENDPOINT, "POST", $data);
			return $res;
		}

	}



?>
 