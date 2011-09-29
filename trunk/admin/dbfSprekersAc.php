<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "dbfSprekers.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    $tpl = new SpoonTemplate();

    $idDBFSpreker="";
    $DBFSprekerInfo = "";
    $frm =new SpoonForm('dbfSprekers', 'dbfSprekersAc.php');
    if(isset($_REQUEST['id'])){
        $idDBFSpreker = $_REQUEST['id'];
        $DBFSprekerInfo = $biz->getDBFSprekerById($idDBFSpreker);
        $frm = new SpoonForm('dbfSprekers', 'dbfSprekersAc.php?id='.$idDBFSpreker);
        $status[] = array('label' => 'Nee', 'value' => 'nee');
        $status[] = array('label' => 'Ja', 'value' => 'ja');
        $status[] = array('label' => 'Misschien', 'value' => 'misschien');
        $frm->addDropdown('OudleerlingId', $biz->getOudleerlingenNaam(), $DBFSprekerInfo['oudleerling_id']);
        $frm->addText('SprekerJaar', $DBFSprekerInfo['jaar'], 45);
        $frm->addRadiobutton('SprekerActief', $status, $DBFSprekerInfo['actief']);
        $frm->addTextarea('SprekerDesiderata', $DBFSprekerInfo['desiderata']);
    }else{
        $status[] = array('label' => 'Nee', 'value' => 'nee');
        $status[] = array('label' => 'Ja', 'value' => 'ja');
        $status[] = array('label' => 'Misschien', 'value' => 'misschien');
        $frm->addDropdown('OudleerlingId', $biz->getOudleerlingenNaam());
        $frm->addText('SprekerJaar', null, 45);
        $frm->addRadiobutton('SprekerActief', $status);
        $frm->addTextarea('SprekerDesiderata');
    }
    $frm->addButton('submit', 'DBF sprekers', 'submit');// add submit button

    if($frm->isSubmitted()){
        $frm->getField('OudleerlingId')->isFilled("Kies een oudleerling");
        if($frm->getField('SprekerJaar')->isFilled("Gelieve een jaartal in te geven")){
            $schooljaar = staticFunctions::createSchooljaar();
            if($frm->getField('SprekerJaar')->isInteger("Geef een correct jaartal in van ".($schooljaar-20)." tot ".($schooljaar+20))){
                $frm->getField('SprekerJaar')->isBetween($schooljaar-20,$schooljaar+20,"Getallen moeten van ".($schooljaar-20)." tot ".($schooljaar+20)." zijn");
            }
        }
        $frm->getField('SprekerActief')->isFilled("Gelieve een keuze te maken");
        if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $array = array();
            $array['oudleerling_id'] = $_REQUEST['OudleerlingId'];
            $array['jaar'] = $_REQUEST['SprekerJaar'];
            $array['actief'] = $_REQUEST['SprekerActief'];
            $array['desiderata'] = $_REQUEST['SprekerDesiderata'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            if(!empty($idDBFSpreker)){
                $databank->update('tbl_dbf_spreker',$array, "dbf_spreker_id=?", $idDBFSpreker);
            }else{
                $databank->insert('tbl_dbf_spreker',$array);
            }
            header("Location: dbfSprekers.php");
            exit(0);
        }
    }

    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/dbfSprekersAc.tpl');
