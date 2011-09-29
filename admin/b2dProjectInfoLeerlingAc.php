<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "b2dProjectInfoLeerlingen.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id']) ){
        header("Location:b2dProjectInfoLeerlingen.php");
        exit(0);
    }

    $idB2D=$_REQUEST['id'];
    $b2dInfo = $biz->getB2DInfoById($idB2D);

    $frm = new SpoonForm('b2dProjectInfoLLN', 'b2dProjectInfoLeerlingAc.php?id='.$idB2D);
    $frm->addDropdown('ToegewezenProject',$biz->getB2DProjecten(), $b2dInfo['toegewezen_project']);
    $status[] = array('label' => 'Nee', 'value' => '0');
    $status[] = array('label' => 'Ja', 'value' => '1');
    $frm->addRadiobutton('AanvraagOk', $status, ($b2dInfo['aanvraag_ok'] == 1 ? '1' : '0'));
    $frm->addRadiobutton('HandTekeningOuders', $status, ($b2dInfo['handtekening_ouders'] == 1 ? '1' : '0'));
    $frm->addRadiobutton('Aanwezigheid', $status, ($b2dInfo['bevestiging_aanwezigheid'] == 1 ? '1' : '0'));
    $frm->addTextarea('EvalTekst1', $b2dInfo['evaluatie_tekst1']);
    $frm->addTextarea('EvalTekst2', $b2dInfo['evaluatie_tekst2']);
    $frm->addText('EvalPunten', $b2dInfo['evaluatie_punten'], 100);
    $frm->addRadiobutton('EvalAfgegeven', $status, ($b2dInfo['evaluatie_afgegeven'] == 1 ? '1' : '0'));
    $frm->addButton('submit', 'Bezinnings2daagse', 'submit');
    if($frm->isSubmitted()){
        if($frm->getField('EvalPunten')->isFilled()){
            if($frm->getField('EvalPunten')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('EvalPunten')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->isCorrect()){
            $array = array();
            $array['toegewezen_project'] = $_REQUEST['ToegewezenProject'];
            $array['aanvraag_ok'] = $_REQUEST['AanvraagOk'];
            $array['handtekening_ouders']=$_REQUEST['HandTekeningOuders'];
            $array['bevestiging_aanwezigheid'] = $_REQUEST['Aanwezigheid'];
            $array['evaluatie_tekst1'] =$_REQUEST['EvalTekst1'];
            $array['evaluatie_tekst2'] =$_REQUEST['EvalTekst2'];
            $array['evaluatie_punten'] =$_REQUEST['EvalPunten'];
            $array['evaluatie_afgegeven'] =$_REQUEST['EvalAfgegeven'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            $databank = $biz->getDataConnect();
            $databank->update("tbl_b2d_algemeen", $array, "b2d_algemeen_id=?",$idB2D);
            header("Location:b2dProjectInfoLeerlingen.php");
            exit(0);
        }
    }


    $tpl = new SpoonTemplate();
    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $tpl->assign('naamLeerling',$b2dInfo['aanspreek']);
    $frm->parse($tpl);
    $tpl->display('admintemplates/b2dProjectInfoLeerlingAc.tpl');


