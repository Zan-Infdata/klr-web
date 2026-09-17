<?php

    use klr\ReqURI;

    require_once "lib/phpqrcode/qrlib.php";



    $pageFile = ReqURI::getIncFileName();
    $pgCheck = file_exists($pageFile);


    if($pgCheck){

        include_once($pageFile);
    }
    else{

        $defimg = "static/img/no_image.png";
        //$fp = fopen($defimg, 'rb');

        // clean outoput buffer
        ob_end_clean();

        // stream image
        header("Content-Type: image/png");
        header("Content-Length: " . filesize($defimg));

        readfile($defimg);

        //rewind($fp);
        //fpassthru($fp);
        exit;


    } // else
    



?>