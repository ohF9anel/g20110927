<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "mondelingExamen.php");

    $mondelingExamentpl= new Template("admintemplates/");
    $mondelingExamentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $mondelingExamentpl->set_file("tabellen_tp", "tabellen.tpl");
    $mondelingExamentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $mondelingExamentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $mondelingExamentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $mondelingExamentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam = $_SESSION['klasNaamFilter'];
    $mondelingExamentpl->set_var("TABLE", $bizAdm->getMondelingExamenTable(30,$filterSchooljaar,$filterKlasnaam));
    $mondelingExamentpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $mondelingExamentpl->set_var("VALUEKLASNAAM", $filterKlasnaam);

    $mondelingExamentpl->parse("HEAD", "HEADBLOCK");
    $mondelingExamentpl->parse("CONTENT", "CONTENTBLOCK");
    $mondelingExamentpl->pparse("htmlcode", "pageAdm_tp");


