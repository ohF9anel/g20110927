<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "b2dProjecten.php");

    //Zorgen dat als men een andere host zou ingeven  dat men geen fouten krijgt
    if(!isset($_REQUEST['page'])){
        header("Location:b2dProjecten.php?page=0");
        exit(0);
    }

    $b2dProjectentpl= new Template("usertemplates/");
    $b2dProjectentpl->set_file("page_tp", "page.tpl");
    $b2dProjectentpl->set_file("b2dProjecten_tp", "b2dProjecten.tpl");
    $b2dProjectentpl->set_block("b2dProjecten_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $b2dProjectentpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $b2dProjectentpl->set_var("ACCOUNT", $leerling['aanspreek']);

    //Categorien weergeven
    $b2dProjectentpl->set_var("CATEGORIES",$biz->getB2DProjectCategorieen());

    //Projecten weergeven: alle projecten OF projecten van een bepaalde categorie
    $numberToShow = 30; //het aantal projecten dat moet weergegeven worden
    if(isset($_REQUEST['categorie'])){
        $b2dProjectentpl->set_var("GEKOZENCATEGORIE", "- Categorie: ".$biz->getCategorieNaam($_REQUEST['categorie']));
        $b2dProjectentpl->set_var("PROJECTEN",$biz->getAllB2DProjecten($_REQUEST['page'], $numberToShow, $_REQUEST['categorie']));
    }else{
        $b2dProjectentpl->set_var("GEKOZENCATEGORIE", "- Alle projecten");
        $b2dProjectentpl->set_var("PROJECTEN",$biz->getAllB2DProjecten($_REQUEST['page'], $numberToShow));
    }

    $b2dProjectentpl->parse("CONTENT", "CONTENTBLOCK");
    $b2dProjectentpl->pparse("htmlcode", "page_tp");
	