
<?php

    use klr\ReqURI;

    $qrtext = "---";

    if(ReqURI::getID() != null){
        $qrtext = ReqURI::getID();
    }


    // clean output buffer
    ob_end_clean();

    // stream image    
    QRcode::png($qrtext);


?>
