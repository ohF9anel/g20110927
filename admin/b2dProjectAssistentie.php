<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "b2dProjectAssistentie.php");

    $b2dProjectAssistentietpl= new Template("admintemplates/");
    $b2dProjectAssistentietpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $b2dProjectAssistentietpl->set_file("tabellen_tp", "tabellen.tpl");
    $b2dProjectAssistentietpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $b2dProjectAssistentietpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $b2dProjectAssistentietpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $b2dProjectAssistentietpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::SchooljaarFilterCorrect($_REQUEST['schooljaarFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $b2dProjectAssistentietpl->set_var("TABLE", $bizAdm->getProjectAssistentieTable(30,$filterSchooljaar));
    $b2dProjectAssistentietpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);

    $b2dProjectAssistentietpl->parse("HEAD", "HEADBLOCK");
    $b2dProjectAssistentietpl->parse("CONTENT", "CONTENTBLOCK");
    $b2dProjectAssistentietpl->pparse("htmlcode", "pageAdm_tp");