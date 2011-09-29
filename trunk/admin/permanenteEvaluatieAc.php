<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "permanenteEvaluatie.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id'])){
        header("Location:permanenteEvaluatie.php");
        exit(0);
    }

    $tpl = new SpoonTemplate();

    $idPE = $_REQUEST['id'];
    $frm = new SpoonForm('permanenteEval', 'permanenteEvaluatieAc.php?id='.$idPE);
    $PEInfo = $biz->getPermanenteEvaluatieById($idPE);
    $tpl->assign('Leerling', $PEInfo['aanspreek']);
    $frm->addText('Pe01', $PEInfo['pe01'], 50);
    $frm->addText('Pe02', $PEInfo['pe02'], 50);
    $frm->addText('Pe03', $PEInfo['pe03'], 50);
    $frm->addText('Pe04', $PEInfo['pe04'], 50);
    $frm->addText('Pe05', $PEInfo['pe05'], 50);
    $frm->addText('Pe06', $PEInfo['pe06'], 50);
    $frm->addText('Pe07', $PEInfo['pe07'], 50);
    $frm->addText('Pe08', $PEInfo['pe08'], 50);
    $frm->addText('Pe09', $PEInfo['pe09'], 50);
    $frm->addText('Pe10', $PEInfo['pe10'], 50);
    $frm->addButton('submit', 'Permanente evaluatie', 'submit');

    if($frm->isSubmitted()){
        if($frm->getField('Pe01')->isFilled()){
            if($frm->getField('Pe01')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe01')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->getField('Pe02')->isFilled()){
            if($frm->getField('Pe02')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe02')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->getField('Pe03')->isFilled()){
            if($frm->getField('Pe03')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe03')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->getField('Pe04')->isFilled()){
            if($frm->getField('Pe04')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe04')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->getField('Pe05')->isFilled()){
            if($frm->getField('Pe05')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe05')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->getField('Pe06')->isFilled()){
            if($frm->getField('Pe06')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe06')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->getField('Pe07')->isFilled()){
            if($frm->getField('Pe07')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe07')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->getField('Pe08')->isFilled()){
            if($frm->getField('Pe08')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe08')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->getField('Pe09')->isFilled()){
            if($frm->getField('Pe09')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe09')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->getField('Pe10')->isFilled()){
            if($frm->getField('Pe10')->isInteger("Enkel getallen van 0 tot 10 zijn toegelaten")){
                $frm->getField('Pe10')->isBetween(0,10,"Getallen moeten van 0 tot 10 zijn");
            }
        }
        if($frm->isCorrect() && $ok=true){
            $array = array();
            $array['pe01'] = $_REQUEST['Pe01'];
            $array['pe02'] = $_REQUEST['Pe02'];
            $array['pe03'] = $_REQUEST['Pe03'];
            $array['pe04'] = $_REQUEST['Pe04'];
            $array['pe05'] = $_REQUEST['Pe05'];
            $array['pe06'] = $_REQUEST['Pe06'];
            $array['pe07'] = $_REQUEST['Pe07'];
            $array['pe08'] = $_REQUEST['Pe08'];
            $array['pe09'] = $_REQUEST['Pe09'];
            $array['pe10'] = $_REQUEST['Pe10'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            $databank = $biz->getDataConnect();
            $databank->update('tbl_permanente_evaluatie',$array, "permanente_evaluatie_id=?", $idPE);

            header("Location: permanenteEvaluatie.php");
            exit(0);
        }
    }

    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/permanenteEvaluatieAc.tpl');


