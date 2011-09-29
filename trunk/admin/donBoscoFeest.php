<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "donBoscoFeest.php");

    $donBoscoFeesttpl= new Template("admintemplates/");
    $donBoscoFeesttpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $donBoscoFeesttpl->set_file("tabellen_tp", "tabellen.tpl");
    $donBoscoFeesttpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $donBoscoFeesttpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $donBoscoFeesttpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $donBoscoFeesttpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam = $_SESSION['klasNaamFilter'];
    $donBoscoFeesttpl->set_var("TABLE", $bizAdm->getDonBoscoFeestTable(30,$filterSchooljaar,$filterKlasnaam));
    $donBoscoFeesttpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $donBoscoFeesttpl->set_var("VALUEKLASNAAM", $filterKlasnaam);

    $donBoscoFeesttpl->parse("HEAD", "HEADBLOCK");
    $donBoscoFeesttpl->parse("CONTENT", "CONTENTBLOCK");
    $donBoscoFeesttpl->pparse("htmlcode", "pageAdm_tp");