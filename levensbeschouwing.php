<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "levensbeschouwing.php");

    $levensbeschouwingtpl= new Template("usertemplates/");
    $levensbeschouwingtpl->set_file("page_tp", "page.tpl");
    $levensbeschouwingtpl->set_file("levensbeschouwing_tp", "levensbeschouwing.tpl");
    $levensbeschouwingtpl->set_block("levensbeschouwing_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $levensbeschouwingtpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $levensbeschouwingtpl->set_var("ACCOUNT", $leerling['aanspreek']);

    $levensbeschouwingtpl->parse("CONTENT", "CONTENTBLOCK");
    $levensbeschouwingtpl->pparse("htmlcode", "page_tp");