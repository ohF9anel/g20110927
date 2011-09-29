<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "lubumbashi2008.php");

    $lubumashitpl= new Template("usertemplates/");
    $lubumashitpl->set_file("page_tp", "page.tpl");
    $lubumashitpl->set_file("lubumbashi_tp", "lubumbashi2008.tpl");
    $lubumashitpl->set_block("lubumbashi_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $lubumashitpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $lubumashitpl->set_var("ACCOUNT", $leerling['aanspreek']);

    $lubumashitpl->parse("CONTENT", "CONTENTBLOCK");
    $lubumashitpl->pparse("htmlcode", "page_tp");
