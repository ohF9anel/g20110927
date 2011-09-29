<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "klasgroepen.php");

    $klasgroepentpl= new Template("admintemplates/");
    $klasgroepentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $klasgroepentpl->set_file("tabellen_tp", "tabellen.tpl");
    $klasgroepentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $klasgroepentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $klasgroepentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $klasgroepentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::SchooljaarFilterCorrect($_REQUEST['schooljaarFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $klasgroepentpl->set_var("TABLE", $bizAdm->getKlasgroepenTable(30,$filterSchooljaar));
    $klasgroepentpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);

    $klasgroepentpl->parse("HEAD", "HEADBLOCK");
    $klasgroepentpl->parse("CONTENT", "CONTENTBLOCK");
    $klasgroepentpl->pparse("htmlcode", "pageAdm_tp");

