<?php
    // require "../classes/bizADMQueryFunctions.class.php";
	// gb cross-platform adjustement 15:28 zondag 11 september 2011
	require ".." . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "bizADMQueryFunctions.class.php";
    require_once ".." . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "klasgroepen.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);
    
    $idKlasgroep="";
    if(isset($_REQUEST['id'])){
        $idKlasgroep = $_REQUEST['id'];
    }
    //als er een nieuwe klasgroep moet aangemaakt worden wat de inhoud van de velden moet zijn
    if(empty($idKlasgroep)){
        $frm = new SpoonForm('klasgroepen', 'klasgroepenAc.php');
        $frm->addText('klasgroep_naam', null, 50);// add textfield
        $leerkrachten = $biz->getLeerkrachten();
        $frm->addDropdown('leerkracht', $leerkrachten);//add droplist
        $frm->addButton('submit', 'Klasgroep aanmaken', 'submit');// add submit button
        if($frm->isSubmitted()){
            $frm->getField('klasgroep_naam')->isFilled('Gelieve dit veld in te vullen');
            $frm->getField('leerkracht')->isFilled('Gelieve een leerkracht te kiezen');
            if($frm->isCorrect()){
                $array = array();
                $array['klasgroep_naam'] = $_REQUEST['klasgroep_naam'];
                $array['leerkracht_id'] = $_REQUEST['leerkracht'];
                $array['schooljaar'] = staticFunctions::createSchooljaar();
                $array['gewijzigd_door'] = $adminInfo['rangschik'];

                $databank = $biz->getDataConnect();
                $databank->insert('tbl_klasgroepen',$array);
                header("Location: klasgroepen.php");
                exit(0);
            }
        }
    }else{ //als de gegevens van een klasgroep aangepast moeten worden
        $frm = new SpoonForm('klasgroepen', 'klasgroepenAc.php?id='.$idKlasgroep);
        $klasgroepInfo = $biz->getKlasgroepById($idKlasgroep);
        $frm->addText('klasgroep_naam', $klasgroepInfo['klasgroep_naam'], 50);
        $leerkrachten = $biz->getLeerkrachten();
        $frm->addDropdown('leerkracht', $leerkrachten, $klasgroepInfo['leerkracht_id']);
        $frm->addButton('submit', 'Klasgroep aanpassen', 'submit');
        if($frm->isSubmitted()){
            $frm->getField('klasgroep_naam')->isFilled('Gelieve dit veld in te vullen');
            $frm->getField('leerkracht')->isFilled('Gelieve dit veld in te vullen');
            if($frm->isCorrect()){
                $array = array();
                $array['klasgroep_naam'] = $_REQUEST['klasgroep_naam'];
                $array['leerkracht_id'] = $_REQUEST['leerkracht'];
                $array['gewijzigd_door'] = $adminInfo['rangschik'];

                $databank = $biz->getDataConnect();
                $databank->update('tbl_klasgroepen',$array, "klasgroep_id=?", $idKlasgroep);
                header("Location: klasgroepen.php");
                exit(0);
            }
        }
    }

    $tpl = new SpoonTemplate();
    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/klasgroepenAc.tpl');
    
