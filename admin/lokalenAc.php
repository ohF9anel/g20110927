<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "lokalen.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    $tpl = new SpoonTemplate();

    $idLokaal="";
    $lokaalInfo = "";
    $frm =new SpoonForm('lokalen', 'lokalenAc.php');
    if(isset($_REQUEST['id'])){
        $idLokaal = $_REQUEST['id'];
        $lokaalInfo = $biz->getLokaalById($idLokaal);
        $frm = new SpoonForm('lokalen', 'lokalenAc.php?id='.$idLokaal);
        $frm->addText('LokaalNaam', $lokaalInfo['naam'],45);
        $frm->addText('LokaalType', $lokaalInfo['type'],95);
        $frm->addText('LokaalPlaatsen', $lokaalInfo['aantal_plaatsen'],50);
        $status[] = array('label' => 'Nee', 'value' => '0');
        $status[] = array('label' => 'Ja', 'value' => '1');
        $frm->addRadiobutton('CompAanwezig', $status, ($lokaalInfo['computer'] == 1 ? '1' : '0'));
        $frm->addRadiobutton('BeamAanwezig', $status, ($lokaalInfo['beamer'] == 1 ? '1' : '0'));
    }else{
        $frm->addText('LokaalNaam', null,45);
        $frm->addText('LokaalType', null,95);
        $frm->addText('LokaalPlaatsen', null ,50);
        $status[] = array('label' => 'Nee', 'value' => '0');
        $status[] = array('label' => 'Ja', 'value' => '1');
        $frm->addRadiobutton('CompAanwezig', $status, '0');
        $frm->addRadiobutton('BeamAanwezig', $status, '0');
    }
    $frm->addButton('submit', 'Lokalen', 'submit');// add submit button

    if($frm->isSubmitted()){
        $frm->getField('LokaalNaam')->isFilled('Gelieve een lokaal naam in te vullen');
        if($frm->getField('LokaalPlaatsen')->isFilled()){
            if($frm->getField('LokaalPlaatsen')->isInteger("Enkel getallen (van 0 tot 100) zijn toegelaten")){
                $frm->getField('LokaalPlaatsen')->isBetween(0,100,"Getallen moeten van 0 tot 100 zijn");
            }
        }
	if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $array = array();
            $array['naam'] = $_REQUEST['LokaalNaam'];
            $array['type']= $_REQUEST['LokaalType'];
            $array['aantal_plaatsen']=$_REQUEST['LokaalPlaatsen'];
            $array['computer']=$_REQUEST['CompAanwezig'];
            $array['beamer'] = $_REQUEST['BeamAanwezig'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            if(!empty($idLokaal)){
                $databank->update('tbl_lokalen',$array, "lokaal_id=?", $idLokaal);
            }else{
                $databank->insert('tbl_lokalen',$array);
            }
            header("Location: lokalen.php");
            exit(0);
        }
        
    }

    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/lokalenAc.tpl');
