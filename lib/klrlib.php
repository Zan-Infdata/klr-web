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

    $configFile = __DIR__ . '/web.conf';
    if (!is_file($configFile) || !is_readable($configFile)) { throw new \RuntimeException('Application configuration is unavailable'); }
    $config = parse_ini_file($configFile, true);
    if (!is_array($config)) { throw new \RuntimeException('Application configuration is unavailable'); }
    foreach (array('server_url', 'api_url', 'secret') as $key) {
        if (!isset($config[$key]) || !is_string($config[$key]) || $config[$key] === '') { throw new \RuntimeException('Application configuration is unavailable'); }
    }

	define(
		__NAMESPACE__ . '\\CONFIG',
		$config
	);


	class Environment {




		const SRVR_URL = CONFIG["server_url"];
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





    // Encode at the output boundary; stored/API values remain plain domain data.
    class Output {
        public static function html($value) {
            return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        public static function json($value) {
            $encoded = json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE);
            return $encoded === false ? 'null' : $encoded;
        }

        public static function url($value) {
            if (!is_string($value)) { return '#'; }
            $url = trim($value);
            if ($url === '' || preg_match('/[\x00-\x20\x7f\\\\]/', $url) || strpos($url, '//') === 0) { return '#'; }
            $scheme = parse_url($url, PHP_URL_SCHEME);
            if ($scheme === false || ($scheme !== null && !in_array(strtolower($scheme), array('http', 'https'), true))) { return '#'; }
            return $url;
        }
    }

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
			echo  "            <li class='breadcrumb-item'><a href='".Output::html(Output::url($hrefarea))."'>".Output::html($area)."</a></li>";
			echo  "            <li class='breadcrumb-item active'>".Output::html($page)."</li>";
			echo  "        </ol>";


		}


	} // Page








    // Accept metadata only: never dump requests, cookies, credentials or responses.
    class Diagnostics {
        private static $requestId;
        public static function isDebug() {
            if (!defined(__NAMESPACE__ . '\\CONFIG') || !is_array(CONFIG)) { return false; }
            $value = CONFIG['debug'] ?? false;
            return (is_bool($value) || is_string($value) || is_int($value))
                && filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true;
        }

        public static function event($event, $context=array()) {
            if (!in_array($event, array('request', 'login_succeeded', 'login_failed', 'refresh_failed', 'access_refreshed', 'impersonation', 'api_request', 'application_error', 'api_configuration_failed'), true)) { return; }
            if (self::$requestId === null) { self::$requestId = (class_exists(AppErrors::class, false) ? AppErrors::requestId() : null) ?? bin2hex(random_bytes(12)); }
            $entry = array('event' => $event, 'request_id' => self::$requestId);
            if (!is_array($context)) { $context = array(); }
            $endpoints = array('page', 'ajax', 'auth', 'login', 'img',
                '/V1/auth/login', '/V1/auth/refresh', '/V1/auth/jwt-login',
                '/V1/eq/active', '/V1/eq/list', '/V1/eq/show', '/V1/eq/types', '/V1/eq/transitions', '/V1/eq/create', '/V1/eq/update', '/V1/eq/take', '/V1/eq/leave',
                '/V1/loc/availible', '/V1/loc/availible-eq', '/V1/loc/sites', '/V1/loc/warehouses', '/V1/loc/show', '/V1/loc/update',
                '/V1/trans/availible', '/V1/trans/list', '/V1/trans/my', '/V1/trans/my-return', '/V1/trans/show', '/V1/trans/transitions', '/V1/trans/create', '/V1/trans/take', '/V1/trans/return', '/V1/trans/move', '/V1/user/list', '/V1/user/get');
            if (isset($context['endpoint']) && in_array($context['endpoint'], $endpoints, true)) { $entry['endpoint'] = $context['endpoint']; }
            foreach (array('status' => 599, 'curl_errno' => 10000, 'elapsed_ms' => 86400000) as $key => $max) {
                if (isset($context[$key]) && is_int($context[$key]) && $context[$key] >= ($key === 'status' ? 100 : 0) && $context[$key] <= $max) { $entry[$key] = $context[$key]; }
            }
            if ($event === 'application_error' && self::isDebug()) {
                foreach (array('phase' => array('bootstrap','configuration','session','localization','dispatch'),
                    'category' => array('deprecation','warning','parse_error','type_error','exception','fatal')) as $key => $allowed) {
                    if (isset($context[$key]) && in_array($context[$key], $allowed, true)) { $entry[$key] = $context[$key]; }
                }
                if (isset($context['severity']) && is_int($context['severity']) && $context['severity'] > 0 && $context['severity'] <= 32767) { $entry['severity'] = $context['severity']; }
            }
            error_log(json_encode($entry));
        }
    }

    class Csrf {
        const SESSION_KEY = 'csrfToken';

        public static function rotate() {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        public static function token() {
            if (SessionMgmt::isRotated()) { return ''; }
            if (!self::isToken($_SESSION[self::SESSION_KEY] ?? null)) { self::rotate(); }
            return $_SESSION[self::SESSION_KEY];
        }

        private static function isToken($value) {
            return is_string($value) && strlen($value) === 64 && preg_match('/^[a-f0-9]{64}$/D', $value);
        }

        private static function origin($url, $originHeader=false) {
            if (!is_string($url) || strlen($url) > 8192 || preg_match('/[\x00-\x20\x7f]/', $url)
                || filter_var($url, FILTER_VALIDATE_URL) === false) { return null; }
            $parts = parse_url($url);
            if (!is_array($parts) || !isset($parts['scheme'], $parts['host']) || isset($parts['user']) || isset($parts['pass'])) { return null; }
            $scheme = strtolower($parts['scheme']);
            if (!in_array($scheme, array('http', 'https'), true)) { return null; }
            if ($originHeader && (isset($parts['path']) && $parts['path'] !== '' || isset($parts['query']) || isset($parts['fragment']))) { return null; }
            $port = $parts['port'] ?? ($scheme === 'https' ? 443 : 80);
            if ($port < 1) { return null; }
            return $scheme . '://' . strtolower($parts['host']) . ':' . $port;
        }

        public static function valid() {
            $expected = $_SESSION[self::SESSION_KEY] ?? null;
            if (!self::isToken($expected)) { return false; }
            $headerPresent = array_key_exists('HTTP_X_CSRF_TOKEN', $_SERVER);
            $formPresent = array_key_exists('csrf_token', $_POST);
            if (!$headerPresent && !$formPresent) { return false; }
            foreach (array($headerPresent ? $_SERVER['HTTP_X_CSRF_TOKEN'] : $expected,
                $formPresent ? $_POST['csrf_token'] : $expected) as $value) {
                if (!self::isToken($value) || !hash_equals($expected, $value)) { return false; }
            }
            $origin = self::origin(CONFIG['server_url'] ?? null);
            if ($origin === null) { return false; }
            if (array_key_exists('HTTP_ORIGIN', $_SERVER)) { return self::origin($_SERVER['HTTP_ORIGIN'], true) === $origin; }
            if (array_key_exists('HTTP_REFERER', $_SERVER)) { return self::origin($_SERVER['HTTP_REFERER']) === $origin; }
            return true;
        }

        public static function reject() {
            AjaxResponse::send(403, Lang::getRsrc('ajax', 'csrfFailed'));
        }
    }

    class AjaxResponse {
        public static function send($code, $data) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code($code);
            echo json_encode(array('CODE' => $code, 'DATA' => $data), JSON_INVALID_UTF8_SUBSTITUTE);
            exit();
        }

        public static function error($code, $unknown=false) {
            $keys = array(400 => 'alrt9', 401 => 'authRequired', 403 => 'alrt1', 404 => 'alrt4', 405 => 'badMethod',
                409 => 'alrt2', 502 => 'unavailable', 504 => 'timeout');
            $key = $unknown ? 'unknownOutcome' : ($keys[$code] ?? 'alrt2');
            self::send($code, Lang::getRsrc('ajax', $key));
        }

        public static function request() {
            if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { header('Allow: POST'); self::error(405); }
            $request = $_POST['req'] ?? null;
            if (!is_array($request) || !isset($request['MD']) || !is_string($request['MD'])) { self::error(400); }
            return $request;
        }

        public static function uuid($value) {
            return is_string($value) && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/iD', $value);
        }

        public static function positiveId($value) {
            return (is_int($value) || is_string($value)) && filter_var($value, FILTER_VALIDATE_INT) !== false && (int) $value > 0;
        }

        public static function record($value, $description=false) {
            if (!is_array($value) || !self::uuid($value['uuid'] ?? null)) { return false; }
            foreach ($description ? array('code', 'name', 'desc') : array('code', 'name') as $key) {
                if (!isset($value[$key]) || !is_string($value[$key]) || trim($value[$key]) === '') { return false; }
            }
            return isset($value['isActive']) && in_array($value['isActive'], array(0, 1, '0', '1'), true);
        }

        public static function mutation($result) {
            $code = APIManager::mutationStatus($result);
            if ($code !== 200) { self::error($code, ($result['outcome_unknown'] ?? false) === true); }
            self::send(200, Lang::getRsrc('ajax', 'alrt0'));
        }

        public static function requireFields($items, $fields) {
            foreach ($items as $item) {
                if (!is_array($item)) { self::error(502); }
                foreach ($fields as $field) { if (!array_key_exists($field, $item)) { self::error(502); } }
            }
        }

        public static function validateRead($result, $list=null) {
            $code = $result[APIManager::CODE] ?? 502;
            if ($code !== 200) { self::error(is_int($code) && $code >= 400 && $code <= 599 ? $code : 502); }
            $body = $result[APIManager::DATA] ?? null;
            if (!is_array($body) || ($body[APIManager::ERR] ?? null) !== 0 || !is_array($body[APIManager::DATA] ?? null)) { self::error(502); }
            $items = $body[APIManager::DATA];
            $isList = $items === array() || array_keys($items) === range(0, count($items) - 1);
            if ($list === true && (!$isList || count(array_filter($items, 'is_array')) !== count($items))) { self::error(502); }
            if ($list === false && ($isList || !isset($items['id']))) { self::error(502); }
            return $result;
        }
    }

    class SessionMgmt {

		const TIMEOUT = 1800;   //in seconds
		const AUTH_GENERATION_KY = "authGeneration";


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


        const CONTEXT_REVISION_KY = 'contextRevision';
        const ROTATED_KY = 'sessionRotated';
        private static $requestContext;

        public static function start($issueCookie=true) {
            if (session_status() === PHP_SESSION_ACTIVE) { return true; }
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.use_cookies', '1');
            ini_set('session.use_trans_sid', '0');
            session_set_cookie_params(array('lifetime' => 0, 'path' => CookieManager::COOKIE_PATH, 'domain' => '',
                'secure' => CookieManager::isSecure(), 'httponly' => true, 'samesite' => 'Lax'));
            if (!$issueCookie) {
                // Rejected unsafe requests must not replace a browser's existing session cookie.
                ini_set('session.use_cookies', '0');
                $sid = $_COOKIE[session_name()] ?? null;
                if (!is_string($sid) || !preg_match('/^[A-Za-z0-9,-]{1,128}$/D', $sid)) { $_SESSION = array(); return false; }
                session_id($sid);
                if (!session_start()) { $_SESSION = array(); return false; }
                if (session_id() !== $sid) { session_abort(); session_id(''); $_SESSION = array(); return false; }
                return true;
            }
            return session_start();
        }

        public static function init($u, $a, $t) {
            if (self::isRotated() || !is_array($u) || !isset($u['id']) || !is_string($t) || $t === '') { return false; }
            if (session_status() === PHP_SESSION_ACTIVE) {
                // Keep only a tombstone at the old ID so queued anonymous requests cannot restore it again.
                $previous = $_SESSION;
                $_SESSION = array(self::ROTATED_KY => true);
                if (!session_regenerate_id(false)) { $_SESSION = $previous; return false; }
            }
            $_SESSION = array();
            $_SESSION[self::AUTH_KY] = true;
            $_SESSION[self::SSTART_KY] = time();
            $_SESSION[self::SYUSER_KY] = $u;
            $_SESSION[self::SYACL_KY] = $a;
            $_SESSION[self::TKN_KY] = $t;
            $_SESSION[self::AUTH_GENERATION_KY] = bin2hex(random_bytes(32));
            $_SESSION[self::CONTEXT_REVISION_KY] = 0;
            Csrf::rotate();
            if (session_status() === PHP_SESSION_ACTIVE && !ini_get('session.use_cookies') && !CookieManager::setSessionCookie(session_id())) { self::destroy(); return false; }
            self::$requestContext = null;
            return true;
        }

        public static function destroy() {
            $_SESSION = array();
            self::$requestContext = null;
            CookieManager::deleteTokenCookie();
            CookieManager::deleteSessionCookie();
            if (session_status() === PHP_SESSION_ACTIVE) { session_destroy(); }
        }

        public static function isRotated() {
            return ($_SESSION[self::ROTATED_KY] ?? false) === true;
        }

        public static function checkAuth($rebuild) {
            $valid = isset($_SESSION[self::SSTART_KY], $_SESSION[self::AUTH_KY], $_SESSION[self::TKN_KY])
                && $_SESSION[self::AUTH_KY] === true && is_string($_SESSION[self::TKN_KY]) && $_SESSION[self::TKN_KY] !== ''
                && isset($_SESSION[self::AUTH_GENERATION_KY]) && is_string($_SESSION[self::AUTH_GENERATION_KY])
                && (time() - $_SESSION[self::SSTART_KY]) < self::TIMEOUT;
            if ($valid && $rebuild) { $_SESSION[self::SSTART_KY] = time(); }
            return $valid;
        }

        public static function close() {
            if (session_status() === PHP_SESSION_ACTIVE) { session_write_close(); }
        }

        public static function capture() {
            if (self::$requestContext === null) {
                if (!self::checkAuth(false) || session_id() === '') { return null; }
                self::$requestContext = array('session_id' => session_id(), 'generation' => $_SESSION[self::AUTH_GENERATION_KY],
                    'user_id' => $_SESSION[self::SYUSER_KY]['id'], 'token' => self::getAcToken(), 'as_user' => self::getAsUser(),
                    'revision' => $_SESSION[self::CONTEXT_REVISION_KY] ?? 0, 'refresh' => CookieManager::getTokenCookie());
            }
            if (session_status() !== PHP_SESSION_ACTIVE && !self::reopen(self::$requestContext)) { return null; }
            if (!self::matches(self::$requestContext)) { return null; }
            self::$requestContext['token'] = self::getAcToken();
            return self::$requestContext;
        }

        private static function matches($context, $revision=false) {
            return self::checkAuth(false) && session_id() === $context['session_id']
                && ($_SESSION[self::AUTH_GENERATION_KY] ?? null) === $context['generation']
                && (string) ($_SESSION[self::SYUSER_KY]['id'] ?? '') === (string) $context['user_id']
                && (!$revision || ($_SESSION[self::CONTEXT_REVISION_KY] ?? 0) === $context['revision']);
        }

        public static function reopen($context, $revision=false) {
            if (!is_array($context)) { return false; }
            if (session_status() !== PHP_SESSION_ACTIVE) {
                // Internal compare/reopen must never issue a replacement browser cookie or cache headers.
                ini_set('session.use_cookies', '0');
                $cacheLimiter = session_cache_limiter('');
                session_id($context['session_id']);
                $started = session_start();
                if (!$started || session_id() !== $context['session_id']) {
                    if ($started) { session_abort(); }
                    ini_set('session.use_cookies', '1');
                    session_cache_limiter($cacheLimiter);
                    session_id('');
                    $_SESSION = array();
                    return false;
                }
            }
            if (!self::matches($context, $revision)) {
                session_abort();
                ini_set('session.use_cookies', '1');
                $_SESSION = array();
                return false;
            }
            return true;
        }

        public static function changeAsUser($context, $user) {
            if (!self::reopen($context, true)) { return false; }
            if ($user === null) { unset($_SESSION[self::AS_USER_KY]); }
            else { $_SESSION[self::AS_USER_KY] = $user; }
            $_SESSION[self::CONTEXT_REVISION_KY] = $context['revision'] + 1;
            self::close();
            return true;
        }

        public static function invalidate($context, $expectedToken=null) {
            if (!self::reopen($context)) { return false; }
            if ($expectedToken !== null && self::getAcToken() !== $expectedToken) { self::close(); return false; }
            self::destroy();
            return true;
        }

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
        const COOKIE_NAME = 'klr_ck';
        const COOKIE_PATH = '/';

        public static function isSecure() {
            // A false value is an explicit HTTP-development exception, never inferred from headers.
            return !array_key_exists('cookie_secure', CONFIG)
                || filter_var(CONFIG['cookie_secure'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== false;
        }

        private static function options($expires) {
            return array('expires' => $expires, 'path' => self::COOKIE_PATH, 'secure' => self::isSecure(),
                'httponly' => true, 'samesite' => 'Lax');
        }

        public static function setTokenCookie($value) {
            $payload = JWTManager::verifyAuthToken($value, true);
            if ($payload === null) { return false; }
            return setcookie(self::COOKIE_NAME, $value, self::options($payload['exp']));
        }

        public static function getTokenCookie() {
            $value = $_COOKIE[self::COOKIE_NAME] ?? null;
            return is_string($value) && $value !== '' ? $value : null;
        }

        public static function deleteTokenCookie() {
            setcookie(self::COOKIE_NAME, '', self::options(time() - 3600));
            unset($_COOKIE[self::COOKIE_NAME]);
        }

        public static function setSessionCookie($value) {
            return setcookie(session_name(), $value, self::options(0));
        }

        public static function deleteSessionCookie() {
            setcookie(session_name(), '', self::options(time() - 3600));
            unset($_COOKIE[session_name()]);
        }

        public static function isTokenCookieValid() {
            return JWTManager::verifyAuthToken(self::getTokenCookie(), true);
        }
    }

	class JWTManager {

		public const IS_REFRESH = "is_refresh";

		protected const JWT_CNF_FILE = "web.conf";
		protected static $secret;

        private static function getSecret() {
            if (!isset(self::$secret)) { self::$secret = CONFIG['secret'] ?? ''; }
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
            if (!is_string($token) || strlen($token) > 8192) { return null; }
            $parts = explode('.', $token);
            if (count($parts) !== 3) { return null; }
            foreach ($parts as $part) { if (!preg_match('/^[A-Za-z0-9_-]+$/D', $part)) { return null; } }
            list($header, $payload, $signature) = $parts;
            $decodedHeader = json_decode(self::base64UrlDecode($header), true);
            $decodedPayload = json_decode(self::base64UrlDecode($payload), true);
            if (!is_array($decodedHeader) || ($decodedHeader['alg'] ?? null) !== 'HS256'
                || (isset($decodedHeader['typ']) && $decodedHeader['typ'] !== 'JWT') || !is_array($decodedPayload)
                || !isset($decodedPayload['exp']) || !is_int($decodedPayload['exp']) || $decodedPayload['exp'] <= time()) { return null; }
            $secret = self::getSecret();
            if (!is_string($secret) || $secret === '') { return null; }
            $expected = self::base64UrlEncode(hash_hmac('sha256', "$header.$payload", $secret, true));
            return hash_equals($expected, $signature) ? $decodedPayload : null;
        }

        public static function verifyAuthToken($token, $isRefresh) {
            $payload = self::verifyJWT($token);
            if ($payload === null || ($payload[self::IS_REFRESH] ?? null) !== ($isRefresh ? 1 : 0)) { return null; }
            $id = $payload['id'] ?? null;
            $acl = $payload['acl'] ?? null;
            if ((!is_int($id) && !is_string($id)) || !preg_match('/^[1-9][0-9]*$/D', (string) $id)
                || (!is_int($acl) && !is_string($acl)) || !in_array((string) $acl, array('1','2','3','4'), true)) { return null; }
            return $payload;
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
		const LOC_CONSTRUCTION_SITE_TYPE = 2;
		const LOC_WAREHOUSE_TYPE = 3;

		const EQ_STATUS_READY = 1;
		const EQ_STATUS_NEEDS_SERVICE = 2;
		const EQ_STATUS_IN_SERVICE = 3;

		const USER_ADMIN = 1;
		const USER_OWNER = 2;
		const USER_MANAGER = 3;
		const USER_OPERATOR = 4;
	}


	class APIManager {


		const API_URL = CONFIG["api_url"];

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

        public static function validateAuthResponse($res, $refreshToken=null) {
            if (!is_array($res) || ($res[self::CODE] ?? null) !== 200 || !is_array($res[self::DATA] ?? null)) { return null; }
            $data = $res[self::DATA];
            if (($data[self::ERR] ?? null) !== 0) { return null; }
            $accessToken = $data[self::TOKEN] ?? null;
            if ($refreshToken === null) { $refreshToken = $data[self::RF_TOKEN] ?? null; }
            $access = JWTManager::verifyAuthToken($accessToken, false);
            $refresh = JWTManager::verifyAuthToken($refreshToken, true);
            if ($access === null || $refresh === null || (string) $access['id'] !== (string) $refresh['id']) { return null; }
            return array(self::USER_KY => $access, self::TOKEN => $accessToken, self::RF_TOKEN => $refreshToken);
        }

		public static function callLogin($username, $password) {

			$data = array(
				self::USR_KY => $username,
				self::PWD_KY => $password
			);
			$res = self::callAPI(self::LOGIN_ENDPOINT, "POST", $data);
			return $res;
		}

        const CONNECT_TIMEOUT_MS = 3000;
        const REQUEST_TIMEOUT_MS = 10000;
        const DEADLINE_MS = 20000;

        private static function limit($key, $default) {
            $configured = filter_var(CONFIG[$key] ?? $default, FILTER_VALIDATE_INT);
            return $configured !== false && $configured > 0 ? min($configured, $default) : $default;
        }

        private static function clockMs() { return hrtime(true) / 1000000; }
        private static function deadline() { return self::clockMs() + self::limit('api_deadline_ms', self::DEADLINE_MS); }

        private static function failure($code, $kind, $unknown=false) {
            return array(self::CODE => $code, self::DATA => null, 'kind' => $kind, 'outcome_unknown' => $unknown);
        }

        // HTTPS is the default; the explicit HTTP development exception never weakens TLS verification.
        private static function transportPolicy() {
            $base = self::API_URL;
            if (!is_string($base) || preg_match('/[\x00-\x20\x7f]/', $base)
                || filter_var($base, FILTER_VALIDATE_URL) === false) { return null; }
            $parts = parse_url($base);
            if (!is_array($parts) || !isset($parts['scheme'], $parts['host'])
                || isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) || isset($parts['fragment'])
                || (isset($parts['port']) && $parts['port'] < 1)) { return null; }
            $scheme = strtolower($parts['scheme']);
            $host = strtolower($parts['host']);
            $exception = CONFIG['api_allow_http_development'] ?? false;
            $allowDevelopmentHTTP = (is_bool($exception) || is_string($exception) || is_int($exception))
                && filter_var($exception, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true;
            $loopback = $scheme === 'http' && $allowDevelopmentHTTP && in_array($host, array('127.0.0.1', '[::1]', 'localhost'), true);
            if ($scheme !== 'https' && !($scheme === 'http' && $allowDevelopmentHTTP)) { return null; }
            $ca = CONFIG['api_ca_file'] ?? '';
            if (!is_string($ca) || strpos($ca, "\0") !== false) { return null; }
            if ($ca !== '') {
                if ($ca[0] !== '/') { $ca = __DIR__ . '/' . $ca; }
                if (!is_file($ca) || !is_readable($ca)) { return null; }
            }
            return array('base' => rtrim($base, '/'), 'protocol' => $scheme, 'ca_file' => $ca,
                'loopback' => $loopback, 'localhost_port' => $loopback && $host === 'localhost' ? ($parts['port'] ?? 80) : null);
        }

        // One bounded attempt. Family-specific validators interpret JSON result flags.
        private static function execute($family, $endpoint, $method, $data, $token, $deadline) {
            $remaining = (int) floor($deadline - self::clockMs());
            if ($remaining <= 0) { return self::failure(504, 'timeout'); }
            $timeout = min($remaining, self::limit('api_timeout_ms', self::REQUEST_TIMEOUT_MS));
            try { $policy = self::transportPolicy(); } catch (\Throwable $ignored) { $policy = null; }
            if ($policy === null) {
                Diagnostics::event('api_configuration_failed', array('endpoint' => $endpoint, 'status' => 502));
                return self::failure(502, 'configuration');
            }
            $url = $policy['base'] . '/' . $family . $endpoint;
            if ($method === 'GET' && count($data) !== 0) { $url .= '?' . http_build_query($data); }
            $headers = array('Accept: application/json');
            if ($token !== null) { $headers[] = 'Authorization: Bearer ' . $token; }
            $curl = curl_init();
            curl_setopt_array($curl, array(CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT_MS => min($timeout, self::limit('api_connect_timeout_ms', self::CONNECT_TIMEOUT_MS)),
                CURLOPT_TIMEOUT_MS => $timeout, CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => $method));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            if (defined('CURLOPT_PROTOCOLS_STR')) { curl_setopt($curl, constant('CURLOPT_PROTOCOLS_STR'), $policy['protocol']); }
            else { curl_setopt($curl, CURLOPT_PROTOCOLS, $policy['protocol'] === 'http' ? CURLPROTO_HTTP : CURLPROTO_HTTPS); }
            if ($policy['ca_file'] !== '') { curl_setopt($curl, CURLOPT_CAINFO, $policy['ca_file']); }
            if ($policy['loopback']) {
                // Keep loopback development URLs local, including when proxy/DNS settings change.
                curl_setopt($curl, CURLOPT_PROXY, '');
                if ($policy['localhost_port'] !== null) {
                    curl_setopt($curl, CURLOPT_RESOLVE, array('localhost:' . $policy['localhost_port'] . ':127.0.0.1,[::1]'));
                }
            }
            if ($method === 'POST') {
                $headers[] = 'Content-Type: application/json';
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $started = self::clockMs();
            $response = curl_exec($curl);
            $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $errno = curl_errno($curl);
            curl_close($curl);
            Diagnostics::event('api_request', array('endpoint' => $endpoint, 'status' => $status,
                'curl_errno' => $errno, 'elapsed_ms' => (int) (self::clockMs() - $started)));
            if ($response === false) {
                return self::failure($errno === CURLE_OPERATION_TIMEDOUT ? 504 : 502,
                    $errno === CURLE_OPERATION_TIMEDOUT ? 'timeout' : 'transport', $method === 'POST');
            }
            if ($status < 200 || ($status >= 300 && $status < 400) || $status > 599) {
                return self::failure(502, 'protocol', $method === 'POST');
            }
            $decoded = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return self::failure(502, 'protocol', $method === 'POST');
            }
            return array(self::CODE => $status, self::DATA => $decoded, 'kind' => 'http', 'outcome_unknown' => false);
        }

        public static function callRefresh($deadline=null, $refreshToken=null) {
            $token = $refreshToken ?? CookieManager::getTokenCookie();
            if (JWTManager::verifyAuthToken($token, true) === null) { return self::failure(401, 'auth'); }
            return self::execute(self::CALL_ENDPOINT, self::REFRESH_ENDPOINT, 'POST', array(), $token, $deadline ?? self::deadline());
        }

        public static function callJWTCheck() {
            $token = CookieManager::getTokenCookie();
            if (JWTManager::verifyAuthToken($token, true) === null) { return self::failure(401, 'auth'); }
            return self::execute(self::CALL_ENDPOINT, self::JWT_LOGIN_ENDPOINT, 'POST', array(), $token, self::deadline());
        }

        private static function authRejected($result) {
            return $result[self::CODE] === 401 && $result['kind'] === 'http'
                && (($result[self::DATA]['RC'] ?? null) === 401
                    || ($result[self::DATA]['message'] ?? null) === 'No token in header.');
        }

        public static function callAPIProtected($endpoint, $method, $data, $is_retry=false) {
            $deadline = self::deadline();
            $context = SessionMgmt::capture();
            if ($context === null) { return self::failure(401, 'auth'); }
            if ($context['as_user'] !== null) {
                $data[self::AS_USER_KY] = $context['as_user']['id'];
                Diagnostics::event('impersonation', array('endpoint' => $endpoint));
            }
            $token = $context['token'];
            SessionMgmt::close();
            $result = self::execute(self::PROTECTED_ENDPOINT, $endpoint, $method, $data, $token, $deadline);
            if (!self::authRejected($result) || $is_retry) { return $result; }
            if (!SessionMgmt::reopen($context)) { return self::failure(401, 'auth'); }
            if (SessionMgmt::getAcToken() === $token) {
                $refresh = self::callRefresh($deadline, $context['refresh']);
                $validated = self::validateAuthResponse($refresh, $context['refresh']);
                if ($validated === null || (string) $validated[self::USER_KY]['id'] !== (string) $context['user_id']) {
                    $code = $refresh[self::CODE];
                    Diagnostics::event('refresh_failed', array('status' => $code));
                    if (in_array($code, array(401, 403), true)) { SessionMgmt::destroy(); }
                    else { SessionMgmt::close(); }
                    return self::failure($code === 200 ? 502 : $code, $code === 504 ? 'timeout' : 'auth');
                }
                SessionMgmt::setAcToken($validated[self::TOKEN]);
                SessionMgmt::setRegister(SessionMgmt::SYUSER_KY, $validated[self::USER_KY]);
                SessionMgmt::setRegister(SessionMgmt::SYACL_KY, $validated[self::USER_KY][self::ACL_KY]);
                Diagnostics::event('access_refreshed', array('status' => 200));
            }
            $token = SessionMgmt::getAcToken();
            SessionMgmt::close();
            $retry = self::execute(self::PROTECTED_ENDPOINT, $endpoint, $method, $data, $token, $deadline);
            if (self::authRejected($retry)) { SessionMgmt::invalidate($context, $token); }
            return $retry;
        }

        public static function callAPI($endpoint, $method, $data) {
            return self::execute($endpoint === self::PING_ENDPOINT ? '' : self::CALL_ENDPOINT, $endpoint,
                $method, $data, null, self::deadline());
        }

        public static function mutationStatus($result) {
            $code = $result[self::CODE] ?? 502;
            if ($code !== 200) { return is_int($code) && $code >= 400 && $code <= 599 ? $code : 502; }
            $data = $result[self::DATA] ?? null;
            if (!is_array($data) || !isset($data[self::ERR]) || !is_int($data[self::ERR])) { return 502; }
            return $data[self::ERR] === 1 ? 200 : ($data[self::ERR] === 0 ? 409 : 502);
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
