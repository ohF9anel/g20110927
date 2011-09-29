<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "wereldgodsdiensten.php");

    $wereldgodsdienstentpl= new Template("usertemplates/");
    $wereldgodsdienstentpl->set_file("page_tp", "page.tpl");
    $wereldgodsdienstentpl->set_file("wereldgodsdiensten_tp", "wereldgodsdiensten.tpl");
    $wereldgodsdienstentpl->set_block("wereldgodsdiensten_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $wereldgodsdienstentpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $wereldgodsdienstentpl->set_var("ACCOUNT", $leerling['aanspreek']);

    $wereldgodsdienstentpl->parse("CONTENT", "CONTENTBLOCK");
    $wereldgodsdienstentpl->pparse("htmlcode", "page_tp");