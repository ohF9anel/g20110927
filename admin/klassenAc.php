<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "klassen.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id'])){
        header("Location:klassen.php");
        exit(0);
    }

    $tpl = new SpoonTemplate();
    $idKlas = $_REQUEST['id'];
    $KlasInfo = $biz->getKlasInfoById($idKlas);
    $frm = new SpoonForm('klassen', 'klassenAc.php?id='.$idKlas);
    $tpl->assign("Leerling", $KlasInfo['aanspreek']);
    $frm->addDropdown('Klasnaam',$biz->getKlassenCurrentSchooljaar(),$KlasInfo['klasnaam']);
    $frm->addButton('submit', 'Klas', 'submit');

    if($frm->isSubmitted()){
        $databank = $biz->getDataConnect();
        $array = array();
        $array['klasnaam'] = $_REQUEST['Klasnaam'];
        $array['gewijzigd_door'] = $adminInfo['rangschik'];

        $databank->update("tbl_klas",$array,"klas_id=?",$idKlas);
        header("Location:klassen.php");
        exit(0);
    }


    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/klassenAc.tpl');


