<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "b2dProjectSoorten.php");

    $b2dProjectSoortentpl= new Template("admintemplates/");
    $b2dProjectSoortentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $b2dProjectSoortentpl->set_file("tabellen_tp", "tabellen.tpl");
    $b2dProjectSoortentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $b2dProjectSoortentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $b2dProjectSoortentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $b2dProjectSoortentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $b2dProjectSoortentpl->set_var("TABLE", $bizAdm->getProjectSoortTable(30));

    $b2dProjectSoortentpl->parse("HEAD", "HEADBLOCK");
    $b2dProjectSoortentpl->parse("CONTENT", "CONTENTBLOCK");
    $b2dProjectSoortentpl->pparse("htmlcode", "pageAdm_tp");