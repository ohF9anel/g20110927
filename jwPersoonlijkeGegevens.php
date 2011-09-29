<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "jwPersoonlijkeGegevens.php");

    $jwPersoonlijkeGegevenstpl= new Template("usertemplates/");
    $jwPersoonlijkeGegevenstpl->set_file("page_tp", "page.tpl");
    $jwPersoonlijkeGegevenstpl->set_file("jwPersoonlijkeGegevens_tp", "jwPersoonlijkeGegevens.tpl");
    $jwPersoonlijkeGegevenstpl->set_block("jwPersoonlijkeGegevens_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $jwPersoonlijkeGegevenstpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $jwPersoonlijkeGegevenstpl->set_var("ACCOUNT", $leerling['aanspreek']);

    //Persoonlijke gegevens invullen
    $jwlln = $biz->getJWInfoLeerling($leerling_id);
    $datum = staticFunctions::createDateFormat($jwlln['datum']);

    //Deze gegevens moeten enkel ingevuld worden als er al een jaarwerk opgegeven is al keuze
    if($biz->heeftLLNEenJaarwerk($leerling_id, staticFunctions::createSchooljaar())){
        $jwPersoonlijkeGegevenstpl->set_var("VERDUIDELIJKING", "U hebt ".$jwlln['titel']." als jaarwerk opgegeven:");
        $jwPersoonlijkeGegevenstpl->set_var("DATUMVOORSTELLING",$datum);
        $jwPersoonlijkeGegevenstpl->set_var("JWTITEL",$jwlln['titel']);
        $jwPersoonlijkeGegevenstpl->set_var("JWBESCHR",$jwlln['beschrijving']);
        $jwPersoonlijkeGegevenstpl->set_var("JWERVARINGSDESKUNDIGE",$jwlln['specialist']);
        $jwPersoonlijkeGegevenstpl->set_var("JWVOORSTELLING",$jwlln['voorstelling']);
        $jwPersoonlijkeGegevenstpl->set_var("JWBEDENK",$jwlln['bedenkingen']);
        $jwPersoonlijkeGegevenstpl->set_var("JWPERSOONLIJKEINZET",$jwlln['jaarwerk_persoonlijke_inzet']);
    }else {
        $jwPersoonlijkeGegevenstpl->set_var("VERDUIDELIJKING", "U hebt nog <b>geen</b> jaarwerk opgegeven:");
        $jwPersoonlijkeGegevenstpl->set_var("DATUMVOORSTELLING","");
    }

    

    $jwPersoonlijkeGegevenstpl->parse("CONTENT", "CONTENTBLOCK");
    $jwPersoonlijkeGegevenstpl->pparse("htmlcode", "page_tp");
	