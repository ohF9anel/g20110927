<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "donBoscoFeest.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id']) ){
        header("Location:donboscoFeest.php");
        exit(0);
    }

    $idDBF=$_REQUEST['id'];
    $dbfInformatie = $biz->getDBFInfoById($idDBF);

    $frm = new SpoonForm('donBoscoFeest', 'donBoscoFeestAc.php?id='.$idDBF);
    $frm->addDropdown('RichtingId',$biz->getDBFRichtingen(), $dbfInformatie['dbf_richting_id']);
    $frm->addDropdown('SprekerId', $biz->getDBFSprekers(), $dbfInformatie['dbf_spreker_id']);
    $frm->addText('DbFilm3', null, 50);// add textfield
    $frm->addText('DbFilm4', null, 50);// add textfield
    $frm->addText('DbFilm5', null, 50);// add textfield
    $frm->addButton('submit', 'Don Bosco Feest', 'submit');
    if($frm->isSubmitted()){
        if($frm->getField('DbFilm3')->isFilled()){
            $frm->getField('DbFilm3')->isInteger('Gelieve enkel getallen te gebruiken');
        }
        if($frm->getField('DbFilm4')->isFilled()){
            $frm->getField('DbFilm4')->isInteger('Gelieve enkel getallen te gebruiken');
        }
        if($frm->getField('DbFilm5')->isFilled()){
            $frm->getField('DbFilm5')->isInteger('Gelieve enkel getallen te gebruiken');
        }
        if($frm->isCorrect()){
            $array = array();
            $array['dbf_richting_id'] = $_REQUEST['RichtingId'];
            $array['dbf_spreker_id'] = $_REQUEST['SprekerId'];
            $array['dbfilm3'] = $_REQUEST['DbFilm3'];
            $array['dbfilm4'] = $_REQUEST['DbFilm4'];
            $array['dbfilm5'] = $_REQUEST['DbFilm5'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            $databank = $biz->getDataConnect();
            $databank->update('tbl_dbf', $array, 'dbf_id=?', $idDBF);
            header("Location: donBoscoFeest.php");
            exit(0);
        }
    }


    $tpl = new SpoonTemplate();
    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $tpl->assign('naamLeerling',$dbfInformatie['aanspreek']);
    $frm->parse($tpl);
    $tpl->display('admintemplates/donBoscoFeestAc.tpl');


