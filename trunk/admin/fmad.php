<?php
//header('Content-type: text/html; charset=utf-8'); 
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "fmad.php");

    $fmadtpl= new Template("admintemplates/");
    $fmadtpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $fmadtpl->set_file("tabellen_tp", "tabellen.tpl");
    $fmadtpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $fmadtpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $fmadtpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    // $fmadtpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek'])); // gb 21:56 zondag 11 september 2011
	$fmadtpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $fmadtpl->set_var("TABLE", $bizAdm->getFmadTable(30));

    $fmadtpl->parse("HEAD", "HEADBLOCK");
    $fmadtpl->parse("CONTENT", "CONTENTBLOCK");
    $fmadtpl->pparse("htmlcode", "pageAdm_tp");