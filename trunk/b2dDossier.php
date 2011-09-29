<?php
/*
* @author David Van Den Dooren
* @author Godfried Borremans
* @version 27/05/2011
 */

    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "b2dDossier.php");

    $b2ddossiertpl= new Template("usertemplates/");
    $b2ddossiertpl->set_file("page_tp", "page.tpl");
    $b2ddossiertpl->set_file("b2dDossier_tp", "b2dDossier.tpl");
    $b2ddossiertpl->set_block("b2dDossier_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $b2ddossiertpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $b2ddossiertpl->set_var("ACCOUNT", $leerling['aanspreek']);
    
    /* TODO locatie van docs moet via databank gestuurd kunnen worden 
            nu workaround via www folder van Peter
            biesbrouckp/godsdienst/
    */
    $b2ddossiertpl->set_var("DOCROOT", "/biesbrouckp/godsdienst/");    

    $b2ddossiertpl->parse("CONTENT", "CONTENTBLOCK");
    $b2ddossiertpl->pparse("htmlcode", "page_tp");
	