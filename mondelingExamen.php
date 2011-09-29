<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "mondelingExamen.php");

    $mondelingExamentpl= new Template("usertemplates/");
    $mondelingExamentpl->set_file("page_tp", "page.tpl");
    $mondelingExamentpl->set_file("mondelingExamen_tp", "mondelingExamen.tpl");
    $mondelingExamentpl->set_block("mondelingExamen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $mondelingExamentpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $mondelingExamentpl->set_var("ACCOUNT", $leerling['aanspreek']);

    $mondelingExamentpl->set_var("MONDEX", $biz->getMondelingExamenByLeerlingId($leerling_id));
    $mondelingExamentpl->set_var("PERMANTEEVALUATIE", $biz->getPermanenteEvalHTMLString($leerling_id));

    $mondelingExamentpl->parse("CONTENT", "CONTENTBLOCK");
    $mondelingExamentpl->pparse("htmlcode", "page_tp");

