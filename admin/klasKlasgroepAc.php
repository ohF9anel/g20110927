<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "klasKlasgroep.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id'])){
        header("Location:klassen.php");
        exit(0);
    }

    $tpl = new SpoonTemplate();
    $idKlasKlasgroep = $_REQUEST['id'];
    $KlasKlasgroepInfo = $biz->getKlasKlasgroepById($idKlasKlasgroep);
    $frm = new SpoonForm('klasKlasgroep', 'klasKlasgroepAc.php?id='.$idKlasKlasgroep);
    $tpl->assign("Leerling", $KlasKlasgroepInfo['aanspreek']);
    $tpl->assign("Klasnaam", $KlasKlasgroepInfo['klasnaam']);
    $tpl->assign("Schooljaar", $KlasKlasgroepInfo['schooljaar']);
    $frm->addDropdown("Klasgroep", $biz->getKlasgroepenBySchooljaar($KlasKlasgroepInfo['schooljaar']), $KlasKlasgroepInfo['klasgroep_naam']);
    $frm->addButton('submit', 'Klas - Klasgroep', 'submit');

    if($frm->isSubmitted()){
        $databank = $biz->getDataConnect();
        $array = array();
        $array['klasgroep_id'] = $_REQUEST['Klasgroep'];
        $array['gewijzigd_door'] = $adminInfo['rangschik'];

        $databank->update("tbl_klas_klasgroep", $array,"klas_klasgroep_id=?", $idKlasKlasgroep);
        header("Location:klasKlasgroep.php");
        exit(0);
    }

    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/klasKlasgroepAc.tpl');


