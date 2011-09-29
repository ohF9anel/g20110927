<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "mondelingExamen.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id'])){
        header("Location:mondelingExamen.php");
        exit(0);
    }

    $tpl = new SpoonTemplate();
    $idMondEx = $_REQUEST['id'];
    $infoMondEx = $biz->getMondelingExamenById($idMondEx);
    $tpl->assign("Leerling", $infoMondEx['aanspreek']);
    $frm = new SpoonForm('mondelingExamen', 'mondelingExamenAc.php?id='.$idMondEx);
    $frm->addText("MondEx",$infoMondEx['mondeling_examen'], 50);
    $frm->addButton('submit', 'Mondeling examen', 'submit');

    
    if($frm->isSubmitted()){
        if($frm->getField('MondEx')->isFilled("Gelieve een datum in te geven")){
            $RexDataFormat = "/^[0-9\:\- ]+$/";
            if(!strtotime($_REQUEST['MondEx']) || !preg_match($RexDataFormat, $_REQUEST['MondEx'])){ //als het niet in een date formaat is
                //dit moet gedaan worden gewoon om zeker een foutmelding weer te geven
                //daarom gebruik je een filter die altijd fout zal zijn en deze wordt gelinkt met het feit dat de ingegeven datum onjuist is
                $frm->getField('MondEx')->isEmail("Gelieve een datum in te geven met als formaat yyyy-mm-dd hh:mm");
            }
	}
        if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $array = array();
            $array['mondeling_examen'] = date("Y-m-d G:i:s", strtotime($_REQUEST['MondEx']));
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            $databank->update("tbl_leerling_mondex", $array, "leerling_mondex_id=?", $idMondEx);
            header("Location:mondelingExamen.php");
            exit(0);
        }
    }

    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/mondelingExamenAc.tpl');




