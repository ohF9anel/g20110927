<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "honderdDagen.php");

    $honderdDagentpl= new Template("admintemplates/");
    $honderdDagentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $honderdDagentpl->set_file("tabellen_tp", "tabellen.tpl");
    $honderdDagentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $honderdDagentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $honderdDagentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $honderdDagentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam = $_SESSION['klasNaamFilter'];
    $honderdDagentpl->set_var("TABLE", $bizAdm->get100DagenTable(30,$filterSchooljaar,$filterKlasnaam));
    $honderdDagentpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $honderdDagentpl->set_var("VALUEKLASNAAM", $filterKlasnaam);

    $honderdDagentpl->parse("HEAD", "HEADBLOCK");
    $honderdDagentpl->parse("CONTENT", "CONTENTBLOCK");
    $honderdDagentpl->pparse("htmlcode", "pageAdm_tp");

