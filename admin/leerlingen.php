<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "leerlingen.php");

    $leerlingentpl= new Template("admintemplates/");
    $leerlingentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $leerlingentpl->set_file("tabellen_tp", "tabellen.tpl");
    $leerlingentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $leerlingentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $leerlingentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $leerlingentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam = $_SESSION['klasNaamFilter'];
    $leerlingentpl->set_var("TABLE", $bizAdm->getLeerlingenTable(30,$filterSchooljaar,$filterKlasnaam));
    $leerlingentpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $leerlingentpl->set_var("VALUEKLASNAAM", $filterKlasnaam);

    $leerlingentpl->parse("HEAD", "HEADBLOCK");
    $leerlingentpl->parse("CONTENT", "CONTENTBLOCK");
    $leerlingentpl->pparse("htmlcode", "pageAdm_tp");
