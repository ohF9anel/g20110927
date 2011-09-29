<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "quotes.php");

    $quotestpl= new Template("admintemplates/");
    $quotestpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $quotestpl->set_file("tabellen_tp", "tabellen.tpl");
    $quotestpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $quotestpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $quotestpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $quotestpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $quotestpl->set_var("TABLE", $bizAdm->getQuotesTable(12));

    $quotestpl->parse("HEAD", "HEADBLOCK");
    $quotestpl->parse("CONTENT", "CONTENTBLOCK");
    $quotestpl->pparse("htmlcode", "pageAdm_tp");
