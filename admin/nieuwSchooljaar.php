<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "nieuwSchooljaar.php");

    $dbfSprekerstpl= new Template("admintemplates/");
    $dbfSprekerstpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $dbfSprekerstpl->set_file("nieuwSchooljaar_tp", "nieuwSchooljaar.tpl");
    $dbfSprekerstpl->set_block("nieuwSchooljaar_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $dbfSprekerstpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $dbfSprekerstpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdmin($adminInfo['admin'], "indexAdm.php");

    $dbfSprekerstpl->parse("CONTENT", "CONTENTBLOCK");
    $dbfSprekerstpl->pparse("htmlcode", "pageAdm_tp");
