<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "jaarwerkLeerling.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id']) ){
        header("Location:jaarwerkLeerling.php");
        exit(0);
    }

    $idJaarwerkLLN=$_REQUEST['id'];
    $jwLLNInfo = $biz->getJaarwerkLeerlingInfoById($idJaarwerkLLN);

    $frm = new SpoonForm('jaarwerkLeerling', 'jaarwerkLeerlingAc.php?id='.$idJaarwerkLLN);
    $frm->addText('JwPersoonlijkeInzet', $jwLLNInfo['jaarwerk_persoonlijke_inzet'], 255);
    $frm->addButton('submit', 'Jaarwerk leerling', 'submit');
    if($frm->isSubmitted()){
        $array = array();
        $array['jaarwerk_persoonlijke_inzet'] = $_REQUEST['JwPersoonlijkeInzet'];
        $array['gewijzigd_door'] = $adminInfo['rangschik'];

        $databank = $biz->getDataConnect();
        $databank->update('tbl_jaarwerk_leerling', $array, 'jaarwerk_leerling_id=?', $idJaarwerkLLN);
        header("Location: jaarwerkLeerling.php");
        exit(0);
    }

    $tpl = new SpoonTemplate();
    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $tpl->assign('naamLeerling',$jwLLNInfo['aanspreek']);
    $frm->parse($tpl);
    $tpl->display('admintemplates/jaarwerkLeerlingAc.tpl');


