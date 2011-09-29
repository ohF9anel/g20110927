<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "oudleerlingen.php");

    $oudleerlingentpl= new Template("admintemplates/");
    $oudleerlingentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $oudleerlingentpl->set_file("tabellen_tp", "tabellen.tpl");
    $oudleerlingentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $oudleerlingentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $oudleerlingentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $oudleerlingentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $oudleerlingentpl->set_var("TABLE", $bizAdm->getOudleerlingenTable(30));

    $oudleerlingentpl->parse("HEAD", "HEADBLOCK");
    $oudleerlingentpl->parse("CONTENT", "CONTENTBLOCK");
    $oudleerlingentpl->pparse("htmlcode", "pageAdm_tp");


