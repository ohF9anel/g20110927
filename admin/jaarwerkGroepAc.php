<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "jaarwerkGroep.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id']) ){
        header("Location:jaarwerkGroep.php");
        exit(0);
    }

    $idJaarwerkGroep=$_REQUEST['id'];
    $jaarwerkGroepInfo = $biz->getJaarwerkGroepInfoById($idJaarwerkGroep);
    $leden = "";
    foreach($jaarwerkGroepInfo as $jg){
        $leden .= $jg['aanspreek'].", ";
    }
    
    $frm = new SpoonForm('jaarwerkGroep', 'jaarwerkGroepAc.php?id='.$idJaarwerkGroep);
    //dit mag de eerste zijn index=0 want alle arrays hebben dezelfde inhoud juist een andere leerling['aanspreek']
    $frm->addDate('InvoerDat', strtotime($jaarwerkGroepInfo[0]['invoerdatum']),'Y-m-d');
    $frm->addText('EvalInhoud', $jaarwerkGroepInfo[0]['evaluatie_inhoud'],10);
    $frm->addText('EvalVorm', $jaarwerkGroepInfo[0]['evaluatie_vorm'],10);
    $frm->addText('EvalInterview', $jaarwerkGroepInfo[0]['evaluatie_interview'],10);
    $frm->addText('EvalAVier', $jaarwerkGroepInfo[0]['evaluatie_a4'],10);
    $frm->addTextarea('Evalu', $jaarwerkGroepInfo[0]['evaluatie']);
    $frm->addTextarea('Bedenk', $jaarwerkGroepInfo[0]['bedenkingen']);
    $frm->addButton('submit', 'Jaarwerk groep', 'submit');

    if($frm->isSubmitted()){
        if($frm->getField('InvoerDat')->isFilled()){
            $frm->getField('InvoerDat')->isValid('Gelieve een datum in te geven als (yyyy-mm-dd)');
	}
        if($frm->getField('EvalInhoud')->isFilled()){
            if($frm->getField('EvalInhoud')->isFloat("Enkel (komma)getallen van 0 tot 20 zijn toegelaten")){
                $frm->getField('EvalInhoud')->isBetween(0,20,"Getallen moeten van 0 tot 20 zijn");
            }
        }
        if($frm->getField('EvalVorm')->isFilled()){
            if($frm->getField('EvalVorm')->isFloat("Enkel (komma)getallen van 0 tot 20 zijn toegelaten")){
                $frm->getField('EvalVorm')->isBetween(0,20,"Getallen moeten van 0 tot 20 zijn");
            }
        }
        if($frm->getField('EvalInterview')->isFilled()){
            if($frm->getField('EvalInterview')->isFloat("Enkel (komma)getallen van 0 tot 20 zijn toegelaten")){
                $frm->getField('EvalInterview')->isBetween(0,20,"Getallen moeten van 0 tot 20 zijn");
            }
        }
        if($frm->getField('EvalAVier')->isFilled()){
            if($frm->getField('EvalAVier')->isFloat("Enkel (komma)getallen van 0 tot 20 zijn toegelaten")){
                $frm->getField('EvalAVier')->isBetween(0,20,"Getallen moeten van 0 tot 20 zijn");
            }
        }
        if($frm->isCorrect()){
            $array = array();
            $array['invoerdatum'] = $_REQUEST['InvoerDat'];
            $array['evaluatie_inhoud'] = $_REQUEST['EvalInhoud'];
            $array['evaluatie_vorm'] = $_REQUEST['EvalVorm'];
            $array['evaluatie_interview'] = $_REQUEST['EvalInterview'];
            $array['evaluatie_a4'] = $_REQUEST['EvalAVier'];
            $array['evaluatie'] = $_REQUEST['Evalu'];
            $array['bedenkingen'] = $_REQUEST['Bedenk'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            $databank = $biz->getDataConnect();
            $databank->update("tbl_jaarwerk_groep", $array, "jaarwerk_groep_id=?",$idJaarwerkGroep);
            header("Location:jaarwerkGroep.php");
            exit(0);
        }
    }

    $tpl = new SpoonTemplate();
    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $tpl->assign('jaarwerkGroep',$jaarwerkGroepInfo[0]['titel']);
    $tpl->assign('naamLeerling',substr($leden,0,-2));
    $frm->parse($tpl);
    $tpl->display('admintemplates/jaarwerkGroepAc.tpl');


