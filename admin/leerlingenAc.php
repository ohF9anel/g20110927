<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "leerlingen.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id']) ){
        header("Location:leerlingen.php");
        exit(0);
    }

    $tpl = new SpoonTemplate();
    $leerling_id = $_REQUEST['id'];
    $leerlingInfo = $biz->getLeerlingById($leerling_id);
    $tpl->assign('naamLeerling',$leerlingInfo['aanspreek']);

    $frm = new SpoonForm('leerlingen', 'leerlingenAc.php?id='.$leerling_id);
    $frm->addText('email', $leerlingInfo['email'], 250);// add textfield
    $frm->addFile('pasfoto');
    $frm->addFile('fotohanden');
    $frm->addButton('submit', 'Leerling aanpassen', 'submit');// add submit button

    if($frm->isSubmitted()){
        if($frm->getField('email')->isFilled()){
            $frm->getField('email')->isEmail('Gelieve een correct e-mailadres op te geven');
        }
        if($frm->getField('pasfoto')->isFilled()){
            $frm->getField('pasfoto')->isAllowedExtension(array('jpg', 'jpeg', 'bmp', 'png'), 'Enkel jpg,jpeg,bmp en png zijn toegestaan');
        }
        if($frm->getField('fotohanden')->isFilled()){
            $frm->getField('fotohanden')->isAllowedExtension(array('jpg', 'jpeg', 'bmp', 'png'), 'Enkel jpg,jpeg,bmp en png zijn toegestaan');
        }
        if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $databank->insert('tbl_leerlingen_log',$leerlingInfo);
            $array = array();
            $array['email'] = $_REQUEST['email'];
            if($frm->getField('pasfoto')->isFilled()){
                $array['pasfoto'] = "images/pasfotos/".$frm->getField('pasfoto')->getFileName();
            }
            if($frm->getField('fotohanden')->isFilled()){
                $array['fotohanden'] = $frm->getField('fotohanden')->getFileName();
            }
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            $databank->update('tbl_leerlingen',$array, "leerling_id=?", $leerling_id);
            header("Location: leerlingen.php");
            exit(0);
        }
    }
    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/leerlingenAc.tpl');