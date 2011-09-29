<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "dbfRichtingen.php");

    $richtingentpl= new Template("admintemplates/");
    $richtingentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $richtingentpl->set_file("tabellen_tp", "tabellen.tpl");
    $richtingentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $richtingentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $richtingentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $richtingentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $richtingentpl->set_var("TABLE", $bizAdm->getRichtingenTable(30));

    $richtingentpl->parse("HEAD", "HEADBLOCK");
    $richtingentpl->parse("CONTENT", "CONTENTBLOCK");
    $richtingentpl->pparse("htmlcode", "pageAdm_tp");