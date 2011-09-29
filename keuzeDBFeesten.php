<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";
    require_once "classes/spoon.php"; //<==
    require_once "classes/spoon/database/database.php";//<==
	
    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "keuzeDBFeesten.php");

    $keuzedbfeestentpl= new Template("usertemplates/");
    $keuzedbfeestentpl->set_file("page_tp", "page.tpl");
    $keuzedbfeestentpl->set_file("keuzeDBFeesten_tp", "keuzeDBFeesten.tpl");
    $keuzedbfeestentpl->set_block("keuzeDBFeesten_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $keuzedbfeestentpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $keuzedbfeestentpl->set_var("ACCOUNT", $leerling['aanspreek']);

    //Databank verbinding
    $db = $biz->getDatabankConnection();
    $leerlingHasDBF = $biz->getDBFKeuzeLeerling($leerling_id);
    
    //als er al een keuze is moet er geen droplist weergegeven worden maar pure tekst met de keuzes erin
    $sprekerHTML = "<select name='spreker' id='spreker'>
                        <option value='geen spreker'>Geen spreker</option>";
    $studierichtingHTML ="<select name='studierichting' id='studierichting'>
                            <option value='geen studierichting'>Geen studierichting</option>";
    //hier is geen selected value nodig
    if(empty($leerlingHasDBF)){
        $sprekerHTML .=$biz->getDBFSprekersHTMLDroplist();
        $studierichtingHTML .= $biz->getDBFStudierichtingenHTMLDroplist();
    }else{
        $sprekerHTML .=$biz->getDBFSprekersHTMLDroplist($leerlingHasDBF['dbf_spreker_id']);
        $studierichtingHTML .= $biz->getDBFStudierichtingenHTMLDroplist($leerlingHasDBF['dbf_richting_id']);
    }

    $keuzedbfeestentpl->set_var("SPREKER",$sprekerHTML);
    $keuzedbfeestentpl->set_var("STUDIERICHTING", $studierichtingHTML);
    
    
    //Het aanmaken van de dbf spreker info tabel
    $keuzedbfeestentpl->set_var("DBFTABEL",$biz->getDBFSprekerStudierichtingHTMLTabel());

    $msgStudierichting = "";
    $msgSpreker ="";
    //er mag enkel een nieuwe keuze opgestuurd worden als er nog geen keuze gemaakt is
    //DUS als er op de button geklikt wordt moet er ook gekeken worden of er nog GEEN dbf keuze gemaakt is door de ingelogde gebruiker
    if(isset($_REQUEST['btnSendKeuzeDBF'])){
        $allok = true;
        $spreker = $_REQUEST['spreker'];
        $studierichting = $_REQUEST['studierichting'];

        if($spreker == "geen spreker"){
            $msgSpreker = "Gelieve een spreker te selecteren";
            $allok = false;
        }
        if($studierichting == "geen studierichting"){
            $msgStudierichting = "Gelieve een studierichting te selecteren";
            $allok = false;
        }
        if($allok){
            $array = array();
            $array['leerling_id'] = $leerling_id;
            $array['dbf_richting_id'] = $studierichting;
            $array['dbf_spreker_id'] = $spreker;
            $array['schooljaar'] = staticFunctions::createSchooljaar();
            $array['gewijzigd_door'] = $leerling['rangschik'];

            if(empty($leerlingHasDBF)){
                $db->insert("tbl_dbf",$array);
            }else{
                $db->update("tbl_dbf",$array, "leerling_id = ? AND schooljaar=?",array($leerling_id, staticFunctions::createSchooljaar()));
            }
            header("Location: keuzeDBFeesten.php");
            exit(0);
        }
    }
    $keuzedbfeestentpl->set_var("MSGSPREKER", $msgSpreker);
    $keuzedbfeestentpl->set_var("MSGSTUDIERICHTING", $msgStudierichting);

    $keuzedbfeestentpl->parse("CONTENT", "CONTENTBLOCK");
    $keuzedbfeestentpl->pparse("htmlcode", "page_tp");
	