<?php

use klr\AjaxManager;
use klr\Environment;
use klr\Lang;
use klr\ReqURI;
use klr\SessionMgmt;
use klr\Output;
use klr\Csrf;

$logoutUrl = Environment::ROOT_URL."/".ReqURI::URLMAP_AUTH_KY ;

$mdReadURL = Environment::ROOT_URL."/ajax/read/";
$mdUserURL = Environment::ROOT_URL."/ajax/user/";
$mdUpdateURL = Environment::ROOT_URL."/ajax/update/";
$mdCreateURL = Environment::ROOT_URL."/ajax/create/";
$mdDeleteURL = Environment::ROOT_URL."/ajax/delete/";

// read user and ACL data
$syUser = SessionMgmt::getSyUser() ;
$syAcl = SessionMgmt::getSyAcl() ;
$asUser = SessionMgmt::getAsUser() ;
$canSignInAsUser = SessionMgmt::isManagerAcl($syAcl);
if (!$canSignInAsUser && (ReqURI::$reqArray[\klr\URIinx::ENTITY] ?? null) === 'user'
    && (ReqURI::$reqArray[\klr\URIinx::NOUN] ?? null) === 'change') {
    http_response_code(403);
    echo Output::html(Lang::getRsrc('ajax', 'alrt1'));
    return;
}

//*<!--
//*=========================================================
//* Material Dashboard 2 - v3.0.0
//*=========================================================
//*
//* Product Page: https://www.creative-tim.com/product/material-dashboard
//* Copyright 2021 Creative Tim (https://www.creative-tim.com)
//* Licensed under MIT (https://www.creative-tim.com/license)
//* Coded by Creative Tim
//*
//*=========================================================
//*
//* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
//*-->


echo "  <!DOCTYPE html>";
echo "  <html lang='en'>";

echo "    <head>";

echo "      <meta charset='utf-8'>";
echo "      <meta name='csrf-token' content='".Output::html(Csrf::token())."'>";
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







echo "    </head>";


echo "    <body class='sb-nav-fixed g-sidenav-show'>";



echo Environment::$JQRY_JS_SRC ;
echo Environment::$BOOT_JS_SRC ;
echo Environment::$POOPER_JS_SRC ;
echo Environment::$BOOT_TOGGLE_JS_SRC ;
// datePicker JS
echo Environment::$DTPC_JS_SRC ;
// Dependencies and shared helpers must precede page-specific scripts.
echo Environment::$P_SCROLL_JS_SRC ;
echo Environment::$S_SCROLL_JS_SRC ;
echo Environment::$CHART_JS_SRC ;
echo Environment::$MY_JS_SRC ;
echo "<script>window.klrAjaxMessages=".Output::json(array('unavailable' => Lang::getRsrc('ajax','unavailable'), 'unknownOutcome' => Lang::getRsrc('ajax','unknownOutcome'))).";</script>";








//SIDE NAVIGATION
echo " <aside class='sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark' id='sidenav-main'> ";
echo "   <div class='sidenav-header'> ";
echo "     <button type='button' class='btn btn-link text-white p-2 mb-0 position-absolute end-0 top-0 d-xl-none' id='iconSidenav' aria-label='".Lang::getRsrc('page','closeNavigation')."'><span aria-hidden='true'>&times;</span></button> ";
echo "     <a class='navbar-brand m-0' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."' > ";
echo "       <img src='".Environment::STATIC_URL."img/logo-ct.png' class='navbar-brand-img h-100' alt='main_logo'> ";
echo "       <span class='ms-1 font-weight-bold text-white'>".Lang::getRsrc('page','ttl')."</span> ";
echo "     </a> ";
echo "   </div> ";
echo "   <hr class='horizontal light mt-0 mb-2'> ";
echo "   <div class='collapse navbar-collapse  w-auto  max-height-vh-100' id='sidenav-collapse-main'> ";
echo "     <ul class='navbar-nav'> ";
echo "       <li class='nav-item'> ";
echo "         <a class='nav-link text-white active bg-gradient-primary' href='../pages/dashboard.html'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-speedometer2 fs-6'></i> ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>Dashboard</span> ";
echo "         </a> ";
echo "       </li> ";
echo "       <li class='nav-item'> ";
echo "         <a class='nav-link text-white ' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/trans/take'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-table fs-6'></i> ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>Take transport</span> ";
echo "         </a> ";
echo "       </li> ";
echo "       <li class='nav-item'> ";
echo "         <a class='nav-link text-white ' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/trans/return'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-table fs-6'></i> ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>Return transport</span> ";
echo "         </a> ";
echo "       </li> ";
echo "       <li class='nav-item'> ";
echo "         <a class='nav-link text-white ' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/trans/move'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-receipt fs-6'></i> ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>Move transport</span> ";
echo "         </a> ";
echo "       </li> ";
echo "       <li class='nav-item'> ";
echo "         <a class='nav-link text-white ' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/eq/list'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-headset-vr fs-6'></i> ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>Equipment list</span> ";
echo "         </a> ";
echo "       </li> ";
echo "       <li class='nav-item'> ";
echo "         <a class='nav-link text-white ' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/eq/take'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-blockquote-right fs-6'></i> ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>Take equipment</span> ";
echo "         </a> ";
echo "       </li> ";
echo "       <li class='nav-item'> ";
echo "         <a class='nav-link text-white ' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/eq/leave'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-bell-fill fs-6'></i>  ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>Leave equipment</span> ";
echo "         </a> ";
echo "       </li> ";
echo "<li class='nav-item'><a class='nav-link text-white' href='".Environment::ROOT_URL."/page/service/transfer'>";
echo "<span class='text-white text-center me-2'><i class='bi bi-tools fs-6' aria-hidden='true'></i></span>";
echo "<span class='nav-link-text ms-1'>".Output::html(Lang::getRsrc('equipmentService', 'title'))."</span></a></li>";
if ($canSignInAsUser) {
    echo "<li class='nav-item'><a class='nav-link text-white' href='".Environment::ROOT_URL."/page/service/setup'>";
    echo "<span class='text-white text-center me-2'><i class='bi bi-gear fs-6' aria-hidden='true'></i></span>";
    echo "<span class='nav-link-text ms-1'>".Output::html(Lang::getRsrc('equipmentService', 'setup'))."</span></a></li>";
    echo "   <li class='nav-item'>";
    echo "     <a class='nav-link text-white' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/checklist/create'>";
    echo "       <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'>";
    echo "         <i class='bi bi-card-checklist fs-6' aria-hidden='true'></i>";
    echo "       </div>";
    echo "       <span class='nav-link-text ms-1'>".Output::html(Lang::getRsrc('equipmentChecklist', 'new'))."</span>";
    echo "     </a>";
    echo "   </li>";
}
echo "       <li class='nav-item mt-3'> ";
echo "         <h6 class='ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8'>Account pages</h6> ";
echo "       </li> ";
echo "       <li class='nav-item'> ";
echo "         <a class='nav-link text-white ' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/eq/layout'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-person-fill fs-6'></i> ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>Layout</span> ";
echo "         </a> ";
echo "       </li> ";
echo "       <li class='nav-item' role='presentation'> ";
echo "         <hr class='horizontal light mx-3 my-2'> ";
echo "       </li> ";
if ($canSignInAsUser) {
echo "       <li class='nav-item'> ";
echo "         <a class='nav-link text-white ' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/user/change'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-box-arrow-in-left fs-6'></i>  ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>Sign In as User</span> ";
echo "         </a> ";
echo "       </li> ";
}
echo "       <li class='nav-item'> ";
echo "         <form class='nav-link p-0' method='post' action='".Output::html($logoutUrl)."' id='logout-form'> ";
echo "           <input type='hidden' name='action' value='logout'>";
echo "           <input type='hidden' name='csrf_token' value='".Output::html(Csrf::token())."'>";
echo "           <button type='submit' class='nav-link text-white border-0 bg-transparent m-0 w-100 text-start'> ";
echo "           <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'> ";
echo "             <i class='bi bi-box-arrow-right fs-6' aria-hidden='true'></i> ";
echo "           </div> ";
echo "           <span class='nav-link-text ms-1'>".Lang::getRsrc('page','topMenu92')."</span> ";
echo "           </button> ";
echo "         </form> ";
echo "       </li> ";
echo "     </ul> ";
echo "   </div> ";
echo " </aside> ";





//echo "                           <li><a class='dropdown-item' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/user/show'>".Lang::getRsrc('page','topMenu91')."</a></li>";
////TODO: Remove in production
//echo "                           <li><a class='dropdown-item' href='".Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/test/show'>TEST ODSTRANI!!!</a></li>";
//echo "                           <li><a class='dropdown-item' href='".$logoutUrl."'>".Lang::getRsrc('page','topMenu92')."</a></li>";


//echo  "                       <div class='sb-sidenav-menu-heading'>".Lang::getRsrc('page','sideGroup1')."</div>";
//echo  "                       <a class='nav-link' href='".Environment::ROOT_URL."/'>";
//echo                            Lang::getRsrc('page','sideMenu10') ;
//echo  "                       </a>";
//echo  "                         <nav class='sb-sidenav-menu-nested nav'>";
//echo  "                            <a class='nav-link' href='" .Environment::ROOT_URL."/".ReqURI::URLMAP_PAGE_KY."/mdarticle/list'>".Lang::getRsrc('page','sideMenu21sub01')."</a>";
//echo  "                         </nav>";
//echo  "                       </div>";
//echo  "                  <div class='sb-sidenav-footer'>";
//echo "                        <div class='small'>".Lang::getRsrc('page','logInAs')."</div> TODO: USER-NAME " ;
//echo  "                  </div>";   // sb-sidenav-footer


echo  " <main class='main-content position-relative max-height-vh-100 h-100 border-radius-lg '>";


 
    echo " <nav class='navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl' id='navbarBlur' navbar-scroll='true'> ";
    echo "   <div class='container-fluid py-1 px-3'> ";
    echo "       <nav aria-label='breadcrumb'> ";
    echo "         <ol class='breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5'> ";
    echo "           <li class='breadcrumb-item text-sm'><a class='opacity-5 text-dark' href='javascript:;'>Pages</a></li> ";
    echo "           <li class='breadcrumb-item text-sm text-dark active' aria-current='page'>Dashboard</li> ";
    echo "         </ol> ";
    echo "         <h6 class='font-weight-bolder mb-0'>Dashboard</h6> ";
    echo "       </nav> ";

    echo "     <button type='button' class='btn btn-link text-body p-2 mb-0 d-xl-none ms-auto' id='iconNavbarSidenav' aria-controls='sidenav-main' aria-expanded='false' aria-label='".Lang::getRsrc('page','openNavigation')."'> ";
    echo "       <span class='sidenav-toggler-inner' aria-hidden='true'> ";
    echo "         <i class='sidenav-toggler-line'></i> ";
    echo "         <i class='sidenav-toggler-line'></i> ";
    echo "         <i class='sidenav-toggler-line'></i> ";
    echo "       </span> ";
    echo "     </button> ";
    echo "     <div class='collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4' id='navbar'> ";
    echo "       <div class='ms-md-auto pe-md-3 d-flex align-items-center'> ";
    echo "         <div class='input-group input-group-outline'> ";
    echo "           <label class='form-label'>Type here...</label> ";
    echo "           <input type='text' class='form-control'> ";
    echo "         </div> ";
    echo "       </div> ";
    echo "       <ul class='navbar-nav  justify-content-end'> ";
    echo "         <li class='nav-item px-3 d-flex align-items-center'> ";
    echo "           <a href='javascript:;' class='nav-link text-body p-0'> ";
    echo "             <i class='fa fa-cog fixed-plugin-button-nav cursor-pointer'></i> ";
    echo "           </a> ";
    echo "         </li> ";
    echo "         <li class='nav-item dropdown pe-2 d-flex align-items-center'> ";
    echo "           <a href='javascript:;' class='nav-link text-body p-0' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'> ";
    echo "             <i class='fa fa-bell cursor-pointer'></i> ";
    echo "           </a> ";
    echo "           <ul class='dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4' aria-labelledby='dropdownMenuButton'> ";
    echo "             <li class='mb-2'> ";
    echo "               <a class='dropdown-item border-radius-md' href='javascript:;'> ";
    echo "                 <div class='d-flex py-1'> ";
    echo "                   <div class='my-auto'> ";
    echo "                     <img src='".Environment::STATIC_URL."img/team-2.jpg' class='avatar avatar-sm  me-3 '> ";
    echo "                   </div> ";
    echo "                   <div class='d-flex flex-column justify-content-center'> ";
    echo "                     <h6 class='text-sm font-weight-normal mb-1'> ";
    echo "                       <span class='font-weight-bold'>New message</span> from Laur ";
    echo "                     </h6> ";
    echo "                     <p class='text-xs text-secondary mb-0'> ";
    echo "                       <i class='fa fa-clock me-1'></i> ";
    echo "                       13 minutes ago ";
    echo "                     </p> ";
    echo "                   </div> ";
    echo "                 </div> ";
    echo "               </a> ";
    echo "             </li> ";
    echo "             <li class='mb-2'> ";
    echo "               <a class='dropdown-item border-radius-md' href='javascript:;'> ";
    echo "                 <div class='d-flex py-1'> ";
    echo "                   <div class='my-auto'> ";
    echo "                     <img src='".Environment::STATIC_URL."img/small-logos/logo-spotify.svg' class='avatar avatar-sm bg-gradient-dark  me-3 '> ";
    echo "                   </div> ";
    echo "                   <div class='d-flex flex-column justify-content-center'> ";
    echo "                     <h6 class='text-sm font-weight-normal mb-1'> ";
    echo "                       <span class='font-weight-bold'>New album</span> by Travis Scott ";
    echo "                     </h6> ";
    echo "                     <p class='text-xs text-secondary mb-0'> ";
    echo "                       <i class='fa fa-clock me-1'></i> ";
    echo "                       1 day ";
    echo "                     </p> ";
    echo "                   </div> ";
    echo "                 </div> ";
    echo "               </a> ";
    echo "             </li> ";
    echo "             <li> ";
    echo "               <a class='dropdown-item border-radius-md' href='javascript:;'> ";
    echo "                 <div class='d-flex py-1'> ";
    echo "                   <div class='avatar avatar-sm bg-gradient-secondary  me-3  my-auto'> ";
    echo "                     <svg width='12px' height='12px' viewBox='0 0 43 36' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'> ";
    echo "                       <title>credit-card</title> ";
    echo "                       <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'> ";
    echo "                         <g transform='translate(-2169.000000, -745.000000)' fill='#FFFFFF' fill-rule='nonzero'> ";
    echo "                           <g transform='translate(1716.000000, 291.000000)'> ";
    echo "                             <g transform='translate(453.000000, 454.000000)'> ";
    echo "                               <path class='color-background' d='M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z' opacity='0.593633743'></path> ";
    echo "                               <path class='color-background' d='M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z'></path> ";
    echo "                             </g> ";
    echo "                           </g> ";
    echo "                         </g> ";
    echo "                       </g> ";
    echo "                     </svg> ";
    echo "                   </div> ";
    echo "                   <div class='d-flex flex-column justify-content-center'> ";
    echo "                     <h6 class='text-sm font-weight-normal mb-1'> ";
    echo "                       Payment successfully completed ";
    echo "                     </h6> ";
    echo "                     <p class='text-xs text-secondary mb-0'> ";
    echo "                       <i class='fa fa-clock me-1'></i> ";
    echo "                       2 days ";
    echo "                     </p> ";
    echo "                   </div> ";
    echo "                 </div> ";
    echo "               </a> ";
    echo "             </li> ";
    echo "           </ul> ";
    echo "         </li> ";
    echo "       </ul> ";
    echo "     </div> ";
    echo "   </div> ";
    echo " </nav> ";

    if(isset($asUser)){
echo "     <div class='row'> ";
echo "       <div class='d-flex w-100 alert alert-warning-striped justify-content-between mb-0 py-1' style='border-radius:0;'> ";
echo "         ";
echo "         <span>";
echo "           You are logged in as <strong>".Output::html($asUser['name'])." </strong> ";
echo "         </span>";
echo "         <button  ";
echo "           class='btn btn-link mb-0 py-0 ' ";
echo "           id='logout-as-user'> ";
echo "           Sign out ";
echo "         </button> ";
echo "       </div> ";
echo "     </div> ";
    }
    echo " <script> ";
    echo " $('#logout-as-user').on('click', function() { calUserAjax(".Output::json($mdUserURL).", ".Output::json(AjaxManager::LOGOUT_AS_USER).", null); }); ";
    echo " </script> ";
    echo " <!-- End Navbar --> ";


    echo " <div class='container-fluid py-4'> ";

// MAIN CONTENT

if (ReqURI::isIncFileName()) {

    $pageFile = ReqURI::getIncFileName();
    $pgCheck = file_exists($pageFile);


    if($pgCheck){
      include_once($pageFile);
    }
    else{
      include_once(ReqURI::getERR404FileName());
    }

}
else {
  include_once(ReqURI::getDEFFileName());
}

echo " <footer class='footer py-4  '> ";
echo "   <div class='container-fluid'> ";
echo "     <div class='row align-items-center justify-content-lg-between'> ";
echo "       <div class='col-lg-6 mb-lg-0 mb-4'> ";
echo "         <div class='copyright text-center text-sm text-muted text-lg-start'> Copyright &copy; ".Lang::getRsrc('footer','copyright')." ";
echo "         </div> ";
echo "       </div> ";
echo "       <div class='col-lg-6'> ";
echo "         <ul class='nav nav-footer justify-content-center justify-content-lg-end'> ";
echo "           <li class='nav-item'> ";
echo "             <a href='https://www.creative-tim.com' class='nav-link text-muted' target='_blank'>Creative Tim</a> ";
echo "           </li> ";
echo "           <li class='nav-item'> ";
echo "             <a href='https://www.creative-tim.com/presentation' class='nav-link text-muted' target='_blank'>About Us</a> ";
echo "           </li> ";
echo "           <li class='nav-item'> ";
echo "             <a href='https://www.creative-tim.com/blog' class='nav-link text-muted' target='_blank'>Blog</a> ";
echo "           </li> ";
echo "           <li class='nav-item'> ";
echo "             <a href='https://www.creative-tim.com/license' class='nav-link pe-0 text-muted' target='_blank'>License</a> ";
echo "           </li> ";
echo "         </ul> ";
echo "       </div> ";
echo "     </div> ";
echo "   </div> ";
echo " </footer> ";

echo  "              </div>";
echo  "              </main>";



  // echo " <div class='fixed-plugin'> ";
  // echo "   <a class='fixed-plugin-button text-dark position-fixed px-3 py-2'> ";
  // echo "     <i class='material-icons py-2'>settings</i> ";
  // echo "   </a> ";
  // echo "   <div class='card shadow-lg'> ";
  // echo "     <div class='card-header pb-0 pt-3'> ";
  // echo "       <div class='float-start'> ";
  // echo "         <h5 class='mt-3 mb-0'>Material UI Configurator</h5> ";
  // echo "         <p>See our dashboard options.</p> ";
  // echo "       </div> ";
  // echo "       <div class='float-end mt-4'> ";
  // echo "         <button class='btn btn-link text-dark p-0 fixed-plugin-close-button'> ";
  // echo "           <i class='material-icons'>clear</i> ";
  // echo "         </button> ";
  // echo "       </div> ";
  // echo "       <!-- End Toggle Button --> ";
  // echo "     </div> ";
  // echo "     <hr class='horizontal dark my-1'> ";
  // echo "     <div class='card-body pt-sm-3 pt-0'> ";
  // echo "       <!-- Sidebar Backgrounds --> ";
  // echo "       <div> ";
  // echo "         <h6 class='mb-0'>Sidebar Colors</h6> ";
  // echo "       </div> ";
  // echo "       <a href='javascript:void(0)' class='switch-trigger background-color'> ";
  // echo "         <div class='badge-colors my-2 text-start'> ";
  // echo "           <span class='badge filter bg-gradient-primary active' data-color='primary' onclick='sidebarColor(this)'></span> ";
  // echo "           <span class='badge filter bg-gradient-dark' data-color='dark' onclick='sidebarColor(this)'></span> ";
  // echo "           <span class='badge filter bg-gradient-info' data-color='info' onclick='sidebarColor(this)'></span> ";
  // echo "           <span class='badge filter bg-gradient-success' data-color='success' onclick='sidebarColor(this)'></span> ";
  // echo "           <span class='badge filter bg-gradient-warning' data-color='warning' onclick='sidebarColor(this)'></span> ";
  // echo "           <span class='badge filter bg-gradient-danger' data-color='danger' onclick='sidebarColor(this)'></span> ";
  // echo "         </div> ";
  // echo "       </a> ";
  // echo "       <!-- Sidenav Type --> ";
  // echo "       <div class='mt-3'> ";
  // echo "         <h6 class='mb-0'>Sidenav Type</h6> ";
  // echo "         <p class='text-sm'>Choose between 2 different sidenav types.</p> ";
  // echo "       </div> ";
  // echo "       <p class='text-sm d-xl-none d-block mt-2'>You can change the sidenav type just on desktop view.</p> ";
  // echo "       <!-- Navbar Fixed --> ";
  // echo "       <div class='mt-3 d-flex'> ";
  // echo "         <h6 class='mb-0'>Navbar Fixed</h6> ";
  // echo "         <div class='form-check form-switch ps-0 ms-auto my-auto'> ";
  // echo "           <input class='form-check-input mt-1 ms-auto' type='checkbox' id='navbarFixed' onclick='navbarFixed(this)'> ";
  // echo "         </div> ";
  // echo "       </div> ";
  // echo "       <hr class='horizontal dark my-3'> ";
  // echo "       <div class='mt-2 d-flex'> ";
  // echo "         <h6 class='mb-0'>Light / Dark</h6> ";
  // echo "         <div class='form-check form-switch ps-0 ms-auto my-auto'> ";
  // echo "           <input class='form-check-input mt-1 ms-auto' type='checkbox' id='dark-version' onclick='darkMode(this)'> ";
  // echo "         </div> ";
  // echo "       </div> ";
  // echo "       <hr class='horizontal dark my-sm-4'> ";
  // echo "       <a class='btn btn-outline-dark w-100' href=''>View documentation</a> ";
  // echo "       <div class='w-100 text-center'> ";
  // echo "         <a class='github-button' href='https://github.com/creativetimofficial/material-dashboard' data-icon='octicon-star' data-size='large' data-show-count='true' aria-label='Star creativetimofficial/material-dashboard on GitHub'>Star</a> ";
  // echo "         <h6 class='mt-3'>Thank you for sharing!</h6> ";
  // echo "         <a href='https://twitter.com/intent/tweet?text=Check%20Material%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard' class='btn btn-dark mb-0 me-2' target='_blank'> ";
  // echo "           <i class='fab fa-twitter me-1' aria-hidden='true'></i> Tweet";
  // echo "         </a>";
  // echo "         <a href='https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/material-dashboard' class='btn btn-dark mb-0 me-2' target='_blank'>";
  // echo "           <i class='fab fa-facebook-square me-1' aria-hidden='true'></i> Share";
  // echo "         </a>";
  // echo "       </div>";
  // echo "     </div>";
  // echo "   </div>";
  // echo " </div>";


  // Material Dashboard binds navigation to the completed DOM and loaded plugins.
  echo Environment::$CSTM1_JS_SRC ;

  echo " <script> \n";
  echo "   var win = navigator.platform.indexOf('Win') > -1; ";
  echo "   if (win && document.querySelector('#sidenav-scrollbar')) { ";
  echo "     var options = { ";
  echo "       damping: '0.5' ";
  echo "     } \n";
  echo "     Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options); ";
  echo "   } ";
  echo " </script> ";

echo "    </body>";
echo "  </html>";

?>
