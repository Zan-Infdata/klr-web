<?php
	
    use klr\ReqURI;
    use klr\SessionMgmt;


    if (SessionMgmt::checkAuth(false)){

        if (ReqURI::isAjaxFileName()) {

            $pageFile = ReqURI::getAjaxFileName();
            $pgCheck = file_exists($pageFile);

            if($pgCheck){
                include_once($pageFile);
            }
            else{
                http_response_code(404);
                echo "AJAX call ERR - 404";
            } // else
        
        } // if
        else {
            http_response_code(404);
            echo "AJAX call ERR - 404";
        } // else

    }
    else {
		http_response_code(401);
		echo "AJAX call is not authorized";
    } // else
	
    

?>