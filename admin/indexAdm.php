<?php
    require_once "../_lib/_classes/template.class.php";
    require_once "../_lib/_includes/functions.inc.php";
    require_once "../classes/bizADMFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "indexAdm.php");

    $indexAdmtpl= new Template("admintemplates/");
    $indexAdmtpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $indexAdmtpl->set_file("indexAdm_tp", "indexAdm.tpl");
    $indexAdmtpl->set_block("indexAdm_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $indexAdmtpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $indexAdmtpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $indexAdmtpl->parse("CONTENT", "CONTENTBLOCK");
    $indexAdmtpl->pparse("htmlcode", "pageAdm_tp");

