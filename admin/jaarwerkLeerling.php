<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "jaarwerkLeerling.php");

    $jaarwerkLeerlingtpl= new Template("admintemplates/");
    $jaarwerkLeerlingtpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $jaarwerkLeerlingtpl->set_file("tabellen_tp", "tabellen.tpl");
    $jaarwerkLeerlingtpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $jaarwerkLeerlingtpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $jaarwerkLeerlingtpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $jaarwerkLeerlingtpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam = $_SESSION['klasNaamFilter'];
    $jaarwerkLeerlingtpl->set_var("TABLE", $bizAdm->getJaarwerkLeerlingTable(30,$filterSchooljaar,$filterKlasnaam));
    $jaarwerkLeerlingtpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $jaarwerkLeerlingtpl->set_var("VALUEKLASNAAM", $filterKlasnaam);

    $jaarwerkLeerlingtpl->parse("HEAD", "HEADBLOCK");
    $jaarwerkLeerlingtpl->parse("CONTENT", "CONTENTBLOCK");
    $jaarwerkLeerlingtpl->pparse("htmlcode", "pageAdm_tp");

