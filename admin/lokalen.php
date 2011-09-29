<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "lokalen.php");

    $lokalentpl= new Template("admintemplates/");
    $lokalentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $lokalentpl->set_file("tabellen_tp", "tabellen.tpl");
    $lokalentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $lokalentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $lokalentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $lokalentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $lokalentpl->set_var("TABLE", $bizAdm->getLokalenTable(30));

    $lokalentpl->parse("HEAD", "HEADBLOCK");
    $lokalentpl->parse("CONTENT", "CONTENTBLOCK");
    $lokalentpl->pparse("htmlcode", "pageAdm_tp");