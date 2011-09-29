<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "kleiwerk.php");

    $kleiwerktpl= new Template("admintemplates/");
    $kleiwerktpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $kleiwerktpl->set_file("tabellen_tp", "tabellen.tpl");
    $kleiwerktpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $kleiwerktpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $kleiwerktpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $kleiwerktpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam = $_SESSION['klasNaamFilter'];
    $kleiwerktpl->set_var("TABLE", $bizAdm->getKleiwerkTable(30,$filterSchooljaar,$filterKlasnaam));
    $kleiwerktpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $kleiwerktpl->set_var("VALUEKLASNAAM", $filterKlasnaam);;

    $kleiwerktpl->parse("HEAD", "HEADBLOCK");
    $kleiwerktpl->parse("CONTENT", "CONTENTBLOCK");
    $kleiwerktpl->pparse("htmlcode", "pageAdm_tp");


