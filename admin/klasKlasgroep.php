<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "klasKlasgroep.php");

    $klasKlasgroeptpl= new Template("admintemplates/");
    $klasKlasgroeptpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $klasKlasgroeptpl->set_file("tabellen_tp", "tabellen.tpl");
    $klasKlasgroeptpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $klasKlasgroeptpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $klasKlasgroeptpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $klasKlasgroeptpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam = $_SESSION['klasNaamFilter'];
    $klasKlasgroeptpl->set_var("TABLE", $bizAdm->getKlasKlasgroepConnectionTable(30,$filterSchooljaar,$filterKlasnaam));
    $klasKlasgroeptpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $klasKlasgroeptpl->set_var("VALUEKLASNAAM", $filterKlasnaam);

    $klasKlasgroeptpl->parse("HEAD", "HEADBLOCK");
    $klasKlasgroeptpl->parse("CONTENT", "CONTENTBLOCK");
    $klasKlasgroeptpl->pparse("htmlcode", "pageAdm_tp");

