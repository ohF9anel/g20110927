<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";
    require_once "classes/spoon.php"; //<==
    require_once "classes/spoon/database/database.php";//<==
	
    session_start();
    $biz = new bizLLNFunctions();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "b2dKeuze.php");

    //Als de keuzes al gemaakt zijn moet men niet meer toegang hebben tot deze pagina
    $leerling_id = $_SESSION['user'];
    $llnHeeftB2D = $biz->getB2DAlgemeenInfoLeerling($leerling_id);
    if($llnHeeftB2D['toegewezen_project'] != 258 && $llnHeeftB2D!=""){
        header("Location:b2dPersoonlijkeGegevens.php");
        exit(0);
    }

    $b2dkeuzetpl= new Template("usertemplates/");
    $b2dkeuzetpl->set_file("page_tp", "page.tpl");
    $b2dkeuzetpl->set_file("b2dKeuze_tp", "b2dKeuze.tpl");
    $b2dkeuzetpl->set_block("b2dKeuze_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $b2dkeuzetpl->set_var("LEUZE", $biz->getQuote());
    $leerling = $biz->getUserInfo($leerling_id);
    $b2dkeuzetpl->set_var("ACCOUNT", $leerling['aanspreek']);

    //Databank verbinding
    $db = $biz->getDatabankConnection();

    //droplists vullen
    $keuze1="<select name='keuze1' id='keuze1'>
                <option value='Nog geen project'>Nog geen project</option>";
    $keuze2="<select name='keuze2' id='keuze2'>
                <option value='Nog geen project'>Nog geen project</option>";
    $keuze3="<select name='keuze3' id='keuze3'>
                <option value='Nog geen project'>Nog geen project</option>";
    if(empty($llnHeeftB2D)){
        $b2dkeuzetpl->set_var("PROJECTKEUZE1",$biz->getB2DOvergeblevenProjectenHTMLDropList($keuze1));
        $b2dkeuzetpl->set_var("PROJECTKEUZE2",$biz->getB2DOvergeblevenProjectenHTMLDropList($keuze2));
        $b2dkeuzetpl->set_var("PROJECTKEUZE3",$biz->getB2DOvergeblevenProjectenHTMLDropList($keuze3));
    }else{
        $b2dkeuzetpl->set_var("PROJECTKEUZE1",$biz->getB2DOvergeblevenProjectenHTMLDropList($keuze1,$llnHeeftB2D['project_keuze1']));
        $b2dkeuzetpl->set_var("PROJECTKEUZE2",$biz->getB2DOvergeblevenProjectenHTMLDropList($keuze2,$llnHeeftB2D['project_keuze2']));
        $b2dkeuzetpl->set_var("PROJECTKEUZE3",$biz->getB2DOvergeblevenProjectenHTMLDropList($keuze3,$llnHeeftB2D['project_keuze3']));
    }

    $msgKeuze1 ="";
    $msgKeuze2 ="";
    $msgKeuze3 ="";
    if(isset($_REQUEST['btnSendKeuzeB2D'])){
        $allok = true;
        $keuze1= $_REQUEST['keuze1'];
        $keuze2= $_REQUEST['keuze2'];
        $keuze3= $_REQUEST['keuze3'];

        if($keuze1 == $keuze2 || $keuze1 == $keuze3 || $keuze3 == $keuze2){
            $msgKeuze3 ="Gelieve verschillende keuzes op te te geven";
            $allok=false;
        }
        if($keuze1 == "Nog geen project"){
            $msgKeuze1 = "Gelieve een 1e keuze op te geven!";
            $allok = false;
        }
        if($keuze2 == "Nog geen project"){
            $msgKeuze2 = "Gelieve een 2e keuze op te geven!";
            $allok = false;
        }
        if($keuze3 == "Nog geen project"){
            $msgKeuze3 = "Gelieve een 3e keuze op te geven!";
            $allok = false;
        }
        if($allok){
            $array = array();
            $array['leerling_id'] = $leerling_id;
            $array['project_keuze1'] = $keuze1;
            $array['project_keuze2'] = $keuze2;
            $array['project_keuze3'] = $keuze3;
            $array['toegewezen_project']= 258; //Nog geen toegewezen project
            $array['schooljaar'] = staticFunctions::createSchooljaar();
            $array['gewijzigd_door'] = $leerling['rangschik'];

            if(empty($llnHeeftB2D)){
                $db->insert("tbl_b2d_algemeen", $array);
            }else{
                $db->update("tbl_b2d_algemeen", $array, "schooljaar =? AND leerling_id =?", array(staticFunctions::createSchooljaar(),$leerling_id));
            }
            header("Location:b2dKeuze.php");
            exit(0);
        }
    }
    $b2dkeuzetpl->set_var("MSGKEUZE1", $msgKeuze1);
    $b2dkeuzetpl->set_var("MSGKEUZE2", $msgKeuze2);
    $b2dkeuzetpl->set_var("MSGKEUZE3", $msgKeuze3);
    $b2dkeuzetpl->set_var("BREAD", $_SERVER['PHP_SELF']);
    $b2dkeuzetpl->parse("CONTENT", "CONTENTBLOCK");
    $b2dkeuzetpl->pparse("htmlcode", "page_tp");
	