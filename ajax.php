<?php
	
    use klr\ReqURI;
    use klr\SessionMgmt;
    use klr\AjaxResponse;


    if (SessionMgmt::checkAuth(false)){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Allow: POST'); AjaxResponse::error(405); }
        if (!\klr\Csrf::valid()) { \klr\Csrf::reject(); }

        if (ReqURI::isAjaxFileName()) {

            $pageFile = ReqURI::getAjaxFileName();
            $pgCheck = file_exists($pageFile);

            if($pgCheck){
                AjaxResponse::request();
                include_once($pageFile);
            }
            else{
                AjaxResponse::error(404);
            } // else
        
        } // if
        else {
            AjaxResponse::error(404);
        } // else

    }
    else {
		AjaxResponse::error(401);
    } // else
	
    

?>