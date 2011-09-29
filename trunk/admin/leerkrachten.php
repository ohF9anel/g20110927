<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "leerkrachten.php");

    $leerkrachtentpl= new Template("admintemplates/");
    $leerkrachtentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $leerkrachtentpl->set_file("tabellen_tp", "tabellen.tpl");
    $leerkrachtentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $leerkrachtentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $leerkrachtentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $leerkrachtentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $leerkrachtentpl->set_var("TABLE", $bizAdm->getLeerkrachtenTable(30));

    $leerkrachtentpl->parse("HEAD", "HEADBLOCK");
    $leerkrachtentpl->parse("CONTENT", "CONTENTBLOCK");
    $leerkrachtentpl->pparse("htmlcode", "pageAdm_tp");
