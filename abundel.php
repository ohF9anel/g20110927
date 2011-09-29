<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "abundel.php");

    $abundeltpl= new Template("usertemplates/");
    $abundeltpl->set_file("page_tp", "page.tpl");
    $abundeltpl->set_file("abundel_tp", "abundel.tpl");
    $abundeltpl->set_block("abundel_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $abundeltpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $abundeltpl->set_var("ACCOUNT", $leerling['aanspreek']);

    $abundeltpl->parse("CONTENT", "CONTENTBLOCK");
    $abundeltpl->pparse("htmlcode", "page_tp");