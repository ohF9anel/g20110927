<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "oudleerlingen.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    $tpl = new SpoonTemplate();

    $idOudleerling="";
    $oudleerlingInfo = "";
    $frm =new SpoonForm('oudleerlingen', 'oudleerlingenAc.php');
    if(isset($_REQUEST['id'])){
        $idOudleerling = $_REQUEST['id'];
        $oudleerlingInfo = $biz->getOudleerlingenById($idOudleerling);
        $frm = new SpoonForm('oudleerlingen', 'oudleerlingenAc.php?id='.$idOudleerling);
        $dropList[$oudleerlingInfo['leerling_id']] = $oudleerlingInfo['aanspreek'];
        $frm->addDropdown('LeerlingId', $dropList);
        $frm->addDropdown('DbfRichting',$biz->getDBFRichtingen(), $oudleerlingInfo['dbf_richting_id']);
        $frm->addText('RichtingDetails',$oudleerlingInfo['richting_details'],250);
        $frm->addText('Scho',$oudleerlingInfo['school'], 250);
    }else{
        $frm->addDropdown('LeerlingId', $biz->getLeerlingenThatAreNotOudleerling());
        $frm->addDropdown('DbfRichting',$biz->getDBFRichtingen());
        $frm->addText('RichtingDetails',null,250);
        $frm->addText('Scho',null, 250);
    }
    $frm->addButton('submit', 'Oudleerlingen', 'submit');// add submit button

    if($frm->isSubmitted()){
        $frm->getField('LeerlingId')->isFilled("Gelieve een leerling te kiezen");
        $frm->getField('RichtingDetails')->isFilled("Geef de details van de richting op");
        $frm->getField('Scho')->isFilled("Gelieve een school in te geven");
        if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $array = array();
            $array['leerling_id'] = $_REQUEST['LeerlingId'];
            $array['dbf_richting_id'] = $_REQUEST['DbfRichting'];
            $array['richting_details'] = $_REQUEST['RichtingDetails'];
            $array['school'] = $_REQUEST['Scho'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            if(!empty($idOudleerling)){
                $databank->update('tbl_oudleerlingen',$array, "oudleerling_id=?", $idOudleerling);
            }else{
                $databank->insert('tbl_oudleerlingen',$array);
            }
            header("Location:oudleerlingen.php");
            exit(0);
        }
    }

    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/oudleerlingenAc.tpl');
