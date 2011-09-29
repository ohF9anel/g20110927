<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "b2dProjectInfoLeerlingen.php");

    $b2dProjectInfoLeerlingentpl= new Template("admintemplates/");
    $b2dProjectInfoLeerlingentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $b2dProjectInfoLeerlingentpl->set_file("tabellen_tp", "tabellen.tpl");
    $b2dProjectInfoLeerlingentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $b2dProjectInfoLeerlingentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $b2dProjectInfoLeerlingentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $b2dProjectInfoLeerlingentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam = $_SESSION['klasNaamFilter'];
    $b2dProjectInfoLeerlingentpl->set_var("TABLE", $bizAdm->getProjectInfoLeerlingTable(30,$filterSchooljaar,$filterKlasnaam));
    $b2dProjectInfoLeerlingentpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $b2dProjectInfoLeerlingentpl->set_var("VALUEKLASNAAM", $filterKlasnaam);

    $b2dProjectInfoLeerlingentpl->parse("HEAD", "HEADBLOCK");
    $b2dProjectInfoLeerlingentpl->parse("CONTENT", "CONTENTBLOCK");
    $b2dProjectInfoLeerlingentpl->pparse("htmlcode", "pageAdm_tp");

