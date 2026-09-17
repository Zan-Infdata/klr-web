<?php

   use klr\Environment;
   use klr\Lang;

   use klr\ReqURI;
   use klr\SessionMgmt;

   //check if it is a bad login BEFORE destroying SESSION
   $failCheck = SessionMgmt::getRegister(SessionMgmt::AUTHERR_KY);

   $authUrl = Environment::ROOT_URL."/".ReqURI::URLMAP_AUTH_KY ;


   $uInput = 'uinput';
   $pInput = 'pwdinput';
   

   echo "  <!DOCTYPE html>";
   echo "  <html lang='en'>";
   
   echo "    <head>";
   
   echo "      <meta charset='utf-8'>";
   echo "      <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
   echo "      <meta name='description' content='".Environment::META_DESC."'>";
   echo "      <meta name='author' content='".Environment::META_AUTH."'>";
   echo "      <meta http-equiv='X-UA-Compatible' content='ie=edge'>";
   
   echo "      <title>".Environment::PAGE_TTL."</title>";
   
   echo Environment::$PAGE_ICON_SRC ;

   echo Environment::$FONT_CSS_SRC ;
   
   echo Environment::$ICON_CSS_SRC ;
   echo Environment::$BOOT_TOGGLE_CSS_SRC ;
   
   // page css
   echo Environment::$MATERIAL_CSS_SRC ;
   echo Environment::$CSTM1_CSS_SRC ;
   
   // custom JS
   echo Environment::$CSTM1_JS_SRC ;
   echo Environment::$MY_JS_SRC ;
   
  



   echo "    </head>";
   echo "    <body class='login-page'>";




  echo "    <main class='main-content  mt-0'> ";
  echo "     <div class='page-header align-items-start min-vh-100' style='');'> ";
  echo "       <span class='mask bg-gradient-dark opacity-6'></span> ";
  echo "       <div class='container my-auto'> ";
  echo "         <div class='row'> ";
  echo "           <div class='col-lg-4 col-md-8 col-12 mx-auto'> ";
  echo "             <div class='card z-index-0 fadeIn3 fadeInBottom'> ";
  echo "               <div class='card-header p-0 position-relative mt-n4 mx-3 z-index-2'> ";
  echo "                 <div class='bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1'> ";
  echo "                   <h4 class='text-white font-weight-bolder text-center mt-2 mb-0'>".Lang::getRsrc('login','ttl')."</h4> ";
  echo "                   <h5 class='text-white font-weight-bolder text-center mt-2 mb-0'>".Lang::getRsrc('login','subttl')."</h5> ";
  echo "                   <div class='row mt-3'> ";
  echo "                     <div class='col-2 text-center ms-auto'> ";
  echo "                       <a class='btn btn-link px-3' href='javascript:;'> ";
  echo "                         <i class='fa fa-facebook text-white text-lg'></i> ";
  echo "                       </a> ";
  echo "                     </div> ";
  echo "                     <div class='col-2 text-center px-1'> ";
  echo "                       <a class='btn btn-link px-3' href='javascript:;'> ";
  echo "                         <i class='fa fa-github text-white text-lg'></i> ";
  echo "                       </a> ";
  echo "                     </div> ";
  echo "                     <div class='col-2 text-center me-auto'> ";
  echo "                       <a class='btn btn-link px-3' href='javascript:;'> ";
  echo "                         <i class='fa fa-google text-white text-lg'></i> ";
  echo "                       </a> ";
  echo "                     </div> ";
  echo "                   </div> ";
  echo "                 </div> ";
  echo "               </div> ";
  echo "               <div class='card-body'> ";
  echo "                 <form role='form' class='text-start' action= '". $authUrl ."' method='POST'> ";
  echo "                   <div class='input-group input-group-outline my-3'> ";
  echo "                     <label class='form-label'>".Lang::getRsrc('login','usrL')."</label> ";
  echo "                     <input type='text' name='".$uInput."' id='".$uInput."' class='form-control'> ";
  echo "                   </div> ";
  echo "                   <div class='input-group input-group-outline mb-3'> ";
  echo "                     <label class='form-label'>".Lang::getRsrc('login','pwdL')."</label> ";
  echo "                     <input type='password' name='".$pInput."' id='".$pInput."' class='form-control'> ";
  echo "                   </div> ";
  // echo "                   <div class='form-check form-switch d-flex align-items-center mb-3'> ";
  // echo "                     <input class='form-check-input' type='checkbox' id='rememberMe'> ";
  // echo "                     <label class='form-check-label mb-0 ms-2' for='rememberMe'>Remember me</label> ";
  // echo "                   </div> ";
  echo "                   <div class='text-center'> ";
  echo "                     <button type='şubmit' class='btn bg-gradient-primary w-100 my-4 mb-2'>".Lang::getRsrc('login','logB')."</button> ";
  echo "                   </div> ";
  if(!empty($failCheck)){
 
    echo "<p class='loginError'>".Lang::getRsrc('login','errTtl')." ". $failCheck ."</p>";

  }
  echo "                   <p class='mt-4 text-sm text-center'> ";
  echo "                     ". $failCheck ." ";
  echo "                   </p> ";
  
  
  //echo "                   <p class='mt-4 text-sm text-center'> ";
  //echo "                     Don't have an account? ";
  //echo "                     <a href='../pages/sign-up.html' class='text-primary text-gradient font-weight-bold'>Sign up</a> ";
  //echo "                   </p> ";
  echo "                 </form> ";
  echo "               </div> ";
  echo "             </div> ";
  echo "           </div> ";
  echo "         </div> ";
  echo "       </div> ";
  echo "       <footer class='footer position-absolute bottom-2 py-2 w-100'> ";
  echo "         <div class='container'> ";
  echo "           <div class='row align-items-center justify-content-lg-between'> ";
  echo "             <div class='col-12 col-md-6 my-auto'> ";
  echo "               <div class='copyright text-center text-sm text-white text-lg-start'> ";
  echo "                 © <script> ";
  echo "                   document.write(new Date().getFullYear()) ";
  echo "                 </script>, ";
  echo "                 made with <i class='fa fa-heart' aria-hidden='true'></i> by ";
  echo "                 <a href='https://www.creative-tim.com' class='font-weight-bold text-white' target='_blank'>Creative Tim</a> ";
  echo "                 for a better web. ";
  echo "               </div> ";
  echo "             </div> ";
  echo "             <div class='col-12 col-md-6'> ";
  echo "               <ul class='nav nav-footer justify-content-center justify-content-lg-end'> ";
  echo "                 <li class='nav-item'> ";
  echo "                   <a href='https://www.creative-tim.com/license' class='nav-link pe-0 text-white' target='_blank'>License</a> ";
  echo "                 </li> ";
  echo "               </ul> ";
  echo "             </div> ";
  echo "           </div> ";
  echo "          </div> ";
  echo "        </footer> ";
  echo "      </div> ";
  echo "    </main> ";


   echo Environment::$JQRY_JS_SRC ;
   echo Environment::$BOOT_JS_SRC ;
   echo Environment::$POOPER_JS_SRC ;


   echo "    </body>";
   echo "  </html>";

?>