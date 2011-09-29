<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "klassen.php");

    $klassentpl= new Template("admintemplates/");
    $klassentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $klassentpl->set_file("tabellen_tp", "tabellen.tpl");
    $klassentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $klassentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $klassentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $klassentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam = $_SESSION['klasNaamFilter'];
    $klassentpl->set_var("TABLE", $bizAdm->getKlassenTable(30,$filterSchooljaar,$filterKlasnaam));
    $klassentpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $klassentpl->set_var("VALUEKLASNAAM", $filterKlasnaam);

    $klassentpl->parse("HEAD", "HEADBLOCK");
    $klassentpl->parse("CONTENT", "CONTENTBLOCK");
    $klassentpl->pparse("htmlcode", "pageAdm_tp");

