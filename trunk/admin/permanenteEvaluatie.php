<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "permanenteEvaluatie.php");

    $permanenteEvaluatietpl= new Template("admintemplates/");
    $permanenteEvaluatietpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $permanenteEvaluatietpl->set_file("tabellen_tp", "tabellen.tpl");
    $permanenteEvaluatietpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $permanenteEvaluatietpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $permanenteEvaluatietpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $permanenteEvaluatietpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::KlasAndSchooljaarFilterCorrect($_REQUEST['schooljaarFilter'], $_REQUEST['klasNaamFilter']);
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterKlasnaam =$_SESSION['klasNaamFilter'];
    $permanenteEvaluatietpl->set_var("TABLE", $bizAdm->getPermanenteEvaluatieTable(30,$filterSchooljaar,$filterKlasnaam));
    $permanenteEvaluatietpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);
    $permanenteEvaluatietpl->set_var("VALUEKLASNAAM", $filterKlasnaam);

    $permanenteEvaluatietpl->parse("HEAD", "HEADBLOCK");
    $permanenteEvaluatietpl->parse("CONTENT", "CONTENTBLOCK");
    $permanenteEvaluatietpl->pparse("htmlcode", "pageAdm_tp");
