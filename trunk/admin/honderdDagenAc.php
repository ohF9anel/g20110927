<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "honderdDagen.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id'])){
        header("Location:honderdDagen.php");
        exit(0);
    }

    $tpl = new SpoonTemplate();
    $idHonderdDagen = $_REQUEST['id'];
    $honderdDagenInfo = $biz->getHonderdDagenById($idHonderdDagen);
    $frm = new SpoonForm('100Dagen', 'honderdDagenAc.php?id='.$idHonderdDagen);
    $tpl->assign("Leerling", $honderdDagenInfo['aanspreek']);
    $frm->addText('HonderdDagen', $honderdDagenInfo['100dagen'], 95);
    $frm->addTextarea('HonderdDagenComm', $honderdDagenInfo['100dagen_commentaar']);
    $status[] = array('label' => 'Nee', 'value' => '0');
    $status[] = array('label' => 'Ja', 'value' => '1');
    $frm->addRadiobutton('AINiet', $status, ($honderdDagenInfo['amnesty_international_niet']== 1 ? '1' : '0'));
    $frm->addText('Hd01', $honderdDagenInfo['hd01'], 45);
    $frm->addText('Hd02', $honderdDagenInfo['hd02'], 45);
    $frm->addText('Hd03', $honderdDagenInfo['hd03'], 45);
    $frm->addText('Hd04', $honderdDagenInfo['hd04'], 45);
    $frm->addText('Hd05', $honderdDagenInfo['hd05'], 45);
    $frm->addText('Hd06', $honderdDagenInfo['hd06'], 45);
    $frm->addText('Hd07', $honderdDagenInfo['hd07'], 45);
    $frm->addText('Hd08', $honderdDagenInfo['hd08'], 45);
    $frm->addText('Hd09', $honderdDagenInfo['hd09'], 45);
    $frm->addText('Hd10', $honderdDagenInfo['hd10'], 45);
    $frm->addButton('submit', '100dagen', 'submit');

    if($frm->isSubmitted()){
        $databank = $biz->getDataConnect();
        $array = array();
        $array['100dagen'] = $_REQUEST['HonderdDagen'];
        $array['100dagen_commentaar'] = $_REQUEST['HonderdDagenComm'];
        $array['amnesty_international_niet'] = $_REQUEST['AINiet'];
        $array['hd01'] = $_REQUEST['Hd01'];
        $array['hd02'] = $_REQUEST['Hd02'];
        $array['hd03'] = $_REQUEST['Hd03'];
        $array['hd04'] = $_REQUEST['Hd04'];
        $array['hd05'] = $_REQUEST['Hd05'];
        $array['hd06'] = $_REQUEST['Hd06'];
        $array['hd07'] = $_REQUEST['Hd07'];
        $array['hd08'] = $_REQUEST['Hd08'];
        $array['hd09'] = $_REQUEST['Hd09'];
        $array['hd10'] = $_REQUEST['Hd10'];
        $array['gewijzigd_door'] = $adminInfo['rangschik'];

        $databank->update("tbl_100dagen", $array, "100dagen_id=?", $idHonderdDagen);
        header("Location:honderdDagen.php");
        exit(0);
    }


    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/honderdDagenAc.tpl');


