<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "indexAdm.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);
    staticFunctions::isAdmin($adminInfo['admin'], "indexAdm.php");
    
    $controleToetpl= new Template("admintemplates/");
    $controleToetpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $controleToetpl->set_file("controleToevoegenNieuweLlnLkr_tp", "controleToevoegenNieuweLlnLkr.tpl");
    $controleToetpl->set_block("controleToevoegenNieuweLlnLkr_tp", "CONTENTBLOCK", "contentblock");

    $controleToetpl->set_var("LEUZE", $biz->getQuote());
    $controleToetpl->set_var("KLASGROEPEN",$biz->getKlasgroepenCurrentSchoolyearList());
    $controleToetpl->set_var("SCHOOLJAAR", staticFunctions::createSchooljaar());
    $controleToetpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));

    $controleToetpl->parse("HEAD", "HEADBLOCK");
    $controleToetpl->parse("CONTENT", "CONTENTBLOCK");
    $controleToetpl->pparse("htmlcode", "pageAdm_tp");




