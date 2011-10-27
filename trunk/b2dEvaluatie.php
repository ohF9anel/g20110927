<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";
    require_once "classes/spoon.php"; //<==
    require_once "classes/spoon/database/database.php";//<==

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "b2dEvaluatie.php"); 

    $b2devaluatietpl= new Template("usertemplates/");
    $b2devaluatietpl->set_file("page_tp", "page.tpl");
    $b2devaluatietpl->set_file("b2dEvaluatie_tp", "b2dEvaluatie.tpl");
    $b2devaluatietpl->set_block("b2dEvaluatie_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $b2devaluatietpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $b2devaluatietpl->set_var("ACCOUNT", $leerling['aanspreek']);

    //Databank verbinding
    $db = $biz->getDatabankConnection();

    $toegewezen = $biz->getToegewezenProject($leerling_id);
    if(!empty($toegewezen['naam']) && $toegewezen['project_id']!=258){
        $b2devaluatietpl->set_var("PROJECT", $toegewezen['naam']);
    }else{
        $b2devaluatietpl->set_var("PROJECT", 'U hebt nog geen toegewezen project voor B2D');
    }

    $b2dAlgemeen = $biz->getB2DAlgemeenInfoLeerling($leerling_id);
    $b2devaluatietpl->set_var("EVALUATIE", $b2dAlgemeen['evaluatie_tekst1']);

    $b2devaluatietpl->set_var("VALEMAIL", $leerling['email']);

    //het controleren van het evaluatie formulier
    $msgEvaluatie = "";
    if(isset($_REQUEST['btnSendEvalB2D']) && !empty($toegewezen) && $toegewezen['project_id']!=258){
        $allok =true;
        $evaluatie = $_REQUEST['evaluatie'];
        
        if(empty($evaluatie)){
            $msgEvaluatie = "Gelieve een evaluatie in te vullen!";
            $allok = false;
        }
        if($allok){
            $array = array();
            $array['evaluatie_tekst1'] = $evaluatie;
            $array['gewijzigd_door'] = $leerling['rangschik'];
            
            $db->update("tbl_b2d_algemeen", $array, "b2d_algemeen_id = ?", $b2dAlgemeen['b2d_algemeen_id']);
            // gbo header("Location:b2dEvaluatie.php");
            exit(0);
        }
    }else if(isset($_REQUEST['btnSendEvalB2D']) && (empty($toegewezen) || $toegewezen['project_id']==258)){
        $msgEvaluatie = "U kan nog geen evaluatie opgeven want je hebt nog geen toegewezen project voor B2D";
    }
    $b2devaluatietpl->set_var("MSGEVALUATIE", $msgEvaluatie);

    //het controleren van het andere informatie formulier
    $msgAndereInfo = "";
    $msgEmail ="";
    if(isset($_REQUEST['btnSendInfoMail'])){
        $allok =true;
        $andereInfo = $_REQUEST['info'];
        $email = $_REQUEST['email'];
        
        $rexEmail = "/^[\w+\.\+-]+@(([\w\+-])+\.)+[a-z]{2,4}$/i";
        if(empty($email)){
            $msgEmail = "Gelieve een e-mailadres op te geven!";
            $allok = false;
        }else if(!preg_match($rexEmail, $email)){
            $msgEmail = "Gelieve een correct e-mailadres op te geven!";
            $allok = false;
        }
        if(empty($andereInfo)){
            $msgAndereInfo = "Gelieve een evaluatie in op te geven!";
            $allok = false;
        }
        if($allok){
            //als men een e-mail opgeeft dit dan wegschrijven naar de databank als deze verschilt met de vorige
            if($email != $leerling['email']){
                $array = array();
                $array['email'] = $email;
                $array['gewijzigd_door'] = $leerling['rangschik'];

                $db->update("tbl_leerlingen", $array, "leerling_id =?", $leerling_id);
            }
            $titel = "Andere info over b2d van ".$leerling['aanspreek'];
            $onderwerp = "<h3>".$titel."</h3>".$andereInfo;
            /* TODO: verwijder hard-coded e-mailadres hier */
            staticFunctions::sendMail("peter.biesbrouck@dbz.be", $titel, $onderwerp, $leerling['aanspreek']);
            $db->insert("tbl_leerlingen_log", $leerling); //$leerling bevat de oude gegevens
            header("Location:b2dEvaluatie.php");
            exit(0);
        }
    }
    $b2devaluatietpl->set_var("MSGANDEREINFO", $msgAndereInfo);
    $b2devaluatietpl->set_var("MSGEMAIL", $msgEmail);

    $b2devaluatietpl->parse("CONTENT", "CONTENTBLOCK");
    $b2devaluatietpl->pparse("htmlcode", "page_tp");
	