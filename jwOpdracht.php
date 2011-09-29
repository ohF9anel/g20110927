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
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "jwOpdracht.php");

    $jwopdrachttpl= new Template("usertemplates/");
    $jwopdrachttpl->set_file("page_tp", "page.tpl");
    $jwopdrachttpl->set_file("jwOpdracht_tp", "jwOpdracht.tpl");
    $jwopdrachttpl->set_block("jwOpdracht_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $jwopdrachttpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $jwopdrachttpl->set_var("ACCOUNT", $leerling['aanspreek']);
    
    /* TODO locatie van docs moet via databank gestuurd kunnen worden 
            nu workaround via www folder van Peter
            biesbrouckp/godsdienst/
    */
    $jwopdrachttpl->set_var("DOCROOT", "/biesbrouckp/godsdienst/");    

    $jwopdrachttpl->parse("CONTENT", "CONTENTBLOCK");
    $jwopdrachttpl->pparse("htmlcode", "page_tp");
	