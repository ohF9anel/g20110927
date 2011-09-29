<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "b2dDoelstelling.php");

    $b2ddoelstellingtpl= new Template("usertemplates/");
    $b2ddoelstellingtpl->set_file("page_tp", "page.tpl");
    $b2ddoelstellingtpl->set_file("b2dDoelstelling_tp", "b2dDoelstelling.tpl");
    $b2ddoelstellingtpl->set_block("b2dDoelstelling_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $b2ddoelstellingtpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $b2ddoelstellingtpl->set_var("ACCOUNT", $leerling['aanspreek']);

    $b2ddoelstellingtpl->parse("CONTENT", "CONTENTBLOCK");
    $b2ddoelstellingtpl->pparse("htmlcode", "page_tp");
	