<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "profeten.php");

    $profetentpl= new Template("usertemplates/");
    $profetentpl->set_file("page_tp", "page.tpl");
    $profetentpl->set_file("profeten_tp", "profeten.tpl");
    $profetentpl->set_block("profeten_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $profetentpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $profetentpl->set_var("ACCOUNT", $leerling['aanspreek']);

    $profetentpl->parse("CONTENT", "CONTENTBLOCK");
    $profetentpl->pparse("htmlcode", "page_tp");