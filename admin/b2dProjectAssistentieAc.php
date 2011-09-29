<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "b2dProjectAssistentie.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    $tpl = new SpoonTemplate();

    $idB2DProjectAssistentie="";
    $B2DProjectAssistentieInfo = "";
    $frm =new SpoonForm('b2dAssistentie', 'b2dProjectAssistentieAc.php');
    if(isset($_REQUEST['id'])){
        $idB2DProjectAssistentie = $_REQUEST['id'];
        $B2DProjectAssistentieInfo = $biz->getB2DAssistentiebyId($idB2DProjectAssistentie);
        $frm = new SpoonForm('b2dAssistentie', 'b2dProjectAssistentieAc.php?id='.$idB2DProjectAssistentie);
        $frm->addDropdown('LeerkrachtId', $biz->getLeerkrachten(),$B2DProjectAssistentieInfo['leerkracht_id']);
        $dropListB2DAssist[$B2DProjectAssistentieInfo['project_id']] = $B2DProjectAssistentieInfo['naam'];
        $frm->addDropdown('ProjectId', $dropListB2DAssist);
        $frm->addText('AssistJaar',$B2DProjectAssistentieInfo['assistentie_jaar'] ,50);
    }else{
        $frm->addDropdown('LeerkrachtId', $biz->getLeerkrachten());
        $frm->addDropdown('ProjectId', $biz->getB2DProjecten());
        $frm->addText('AssistJaar', null,50);
    }
    $frm->addButton('submit', 'B2D assistentie', 'submit');// add submit button

    if($frm->isSubmitted()){
        if($_REQUEST['LeerkrachtId'] == 162){ // Mag niet 162=geen leerkracht zijn
            $frm->getField('LeerkrachtId')->setError("Gelieve een leerkracht te kiezen");
        }
        if($_REQUEST['ProjectId'] == 258){ // Mag niet 258=geen project zijn
            $frm->getField('ProjectId')->setError("Gelieve een project te kiezen");
        }
        if($frm->getField('AssistJaar')->isFilled("Gelieve een jaartal in te geven")){
            $schooljaar = staticFunctions::createSchooljaar();
            if($frm->getField('AssistJaar')->isInteger("Geef een correct jaartal in van ".($schooljaar-20)." tot ".($schooljaar+20))){
                $frm->getField('AssistJaar')->isBetween($schooljaar-20,$schooljaar+20,"Getallen moeten van ".($schooljaar-20)." tot ".($schooljaar+20)." zijn");
            }
        }
        if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $array = array();
            $array['leerkracht_id'] = $_REQUEST['LeerkrachtId'];
            $array['project_id'] = $_REQUEST['ProjectId'];
            $array['assistentie_jaar'] = $_REQUEST['AssistJaar'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            if(!empty($idB2DProjectAssistentie)){
                $databank->update('tbl_b2d_project_assistentie',$array, "assistentie_id=?", $idB2DProjectAssistentie);
            }else{
                $databank->insert('tbl_b2d_project_assistentie',$array);
            }
            header("Location:b2dProjectAssistentie.php");
            exit(0);
        }
    }


    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/b2dProjectAssistentieAc.tpl');
