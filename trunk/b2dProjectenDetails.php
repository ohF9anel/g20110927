<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "b2dProjectenDetails.php");

    $b2dprojectendetailstpl= new Template("usertemplates/");
    $b2dprojectendetailstpl->set_file("page_tp", "page.tpl");
    $b2dprojectendetailstpl->set_file("b2dProjectenDetails_tp", "b2dProjectenDetails.tpl");
    $b2dprojectendetailstpl->set_block("b2dProjectenDetails_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $b2dprojectendetailstpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $b2dprojectendetailstpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $leerling['aanspreek']));

    //Projectinformatie weergeven
    $projectInfo = $biz->getProjectInfo($_REQUEST['project']);
    $b2dprojectendetailstpl->set_var("NAAM",iconv('Windows-1252', 'UTF-8', $projectInfo['naam']));
    $b2dprojectendetailstpl->set_var("CATEGORIE",iconv('Windows-1252', 'UTF-8', $projectInfo['categorie_soort']));
    $b2dprojectendetailstpl->set_var("ADRES",iconv('Windows-1252', 'UTF-8', $projectInfo['straat'])."; ".$projectInfo['zip']." ".iconv('Windows-1252', 'UTF-8', $projectInfo['gemeente']));
    $b2dprojectendetailstpl->set_var("TELEFOON",$projectInfo['telefoon']);
    $b2dprojectendetailstpl->set_var("FAX",$projectInfo['fax']);
    $b2dprojectendetailstpl->set_var("EMAIL",$projectInfo['email']);
    $b2dprojectendetailstpl->set_var("VERANTW",iconv('Windows-1252', 'UTF-8', $projectInfo['verantwoordelijke']));
    $b2dprojectendetailstpl->set_var("OMSCH",iconv('Windows-1252', 'UTF-8', $projectInfo['omschrijving']));

    //Evaluaties van het project weergeven + rangschikken van nieuwste naar oudste
    $toegewezen = $biz->getEvaluatiesProject($_REQUEST['project']);

    $evaluaties = "";
    for($i = count($toegewezen)-1; $i>=0 ; $i--){
        if($toegewezen[$i]['evaluatie_tekst1']!=""){
            $evaluaties .= $biz->getEvaluatieHTMLString($toegewezen[$i]);
        }
    }
    $b2dprojectendetailstpl->set_var("EVALUATIES", iconv('Windows-1252', 'UTF-8', $evaluaties));

    $b2dprojectendetailstpl->parse("CONTENT", "CONTENTBLOCK");
    $b2dprojectendetailstpl->pparse("htmlcode", "page_tp");