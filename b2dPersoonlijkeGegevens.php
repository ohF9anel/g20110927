<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "b2dPersoonlijkeGegevens.php");

    $b2dpersoonlijkegegevenstpl= new Template("usertemplates/");
    $b2dpersoonlijkegegevenstpl->set_file("page_tp", "page.tpl");
    $b2dpersoonlijkegegevenstpl->set_file("b2dPersoonlijkeGegevens_tp", "b2dPersoonlijkeGegevens.tpl");
    $b2dpersoonlijkegegevenstpl->set_block("b2dPersoonlijkeGegevens_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $b2dpersoonlijkegegevenstpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $b2dpersoonlijkegegevenstpl->set_var("ACCOUNT", $leerling['aanspreek']);

    $toegewezenProject = $biz->getToegewezenProject($leerling_id);
    if($toegewezenProject != "" && $toegewezenProject['project_id'] != 258){
        $b2dpersoonlijkegegevenstpl->set_var("TOEGEWEZEN", "Het toegewezen project is: ".$toegewezenProject['naam']."");
        $b2dpersoonlijkegegevenstpl->set_var("CATEGORIE", $toegewezenProject['categorie_soort']);
        $b2dpersoonlijkegegevenstpl->set_var("OMSCHRIJVING",$toegewezenProject['omschrijving']);
        $b2dpersoonlijkegegevenstpl->set_var("ADRES", $toegewezenProject['straat'].", ".$toegewezenProject['zip']." ".$toegewezenProject['gemeente']);
        $b2dpersoonlijkegegevenstpl->set_var("TELEFOON",$toegewezenProject['telefoon']);
        $b2dpersoonlijkegegevenstpl->set_var("FAX",$toegewezenProject['fax']);
        $b2dpersoonlijkegegevenstpl->set_var("EMAIL",$toegewezenProject['email']);
        $b2dpersoonlijkegegevenstpl->set_var("VERANTWOORDELIJKE",$toegewezenProject['verantwoordelijke']);

        //kijken wie hetzelfde project gekozen heeft
        $b2dpersoonlijkegegevenstpl->set_var("ZELFDEKEUZE", $biz->getZelfdeKeuzeProjectHTMLString($toegewezenProject['project_id'], $toegewezenProject['schooljaar'], $leerling_id));
    }else{
        $b2dpersoonlijkegegevenstpl->set_var("TOEGEWEZEN", "Er is nog <b>geen</b> toegewezen project");
    }

    $b2dpersoonlijkegegevenstpl->parse("CONTENT", "CONTENTBLOCK");
    $b2dpersoonlijkegegevenstpl->pparse("htmlcode", "page_tp");
	