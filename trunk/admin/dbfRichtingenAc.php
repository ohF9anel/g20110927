<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "dbfRichtingen.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    $tpl = new SpoonTemplate();

    $idDBFRichting="";
    $DBFRichtingInfo = "";
    $frm =new SpoonForm('dbfRichtingen', 'dbfRichtingenAc.php');
    if(isset($_REQUEST['id'])){
        $idDBFRichting = $_REQUEST['id'];
        $DBFRichtingInfo = $biz->getDBFRichtingById($idDBFRichting);

        $frm = new SpoonForm('dbfRichtingen', 'dbfRichtingenAc.php?id='.$idDBFRichting);
        $frm->addText('Studierichting',$DBFRichtingInfo['studierichting'],250);
        $frm->addDropdown('AssisLeerkracht', $biz->getLeerkrachten(), $DBFRichtingInfo['assistentie_leerkracht']);
        $frm->addDropdown('LokaalId', $biz->getLokalen(), $DBFRichtingInfo['lokaal_id']);
    }else{
        $frm->addText('Studierichting',null,250);
        $frm->addDropdown('AssisLeerkracht', $biz->getLeerkrachten());
        $frm->addDropdown('LokaalId', $biz->getLokalen());
    }
    $frm->addButton('submit', 'DBF richtingen', 'submit');// add submit button

    if($frm->isSubmitted()){
        $frm->getField('Studierichting')->isFilled("Gelieve een studierichting in te geven");
        if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $array = array();
            $array['studierichting'] = $_REQUEST['Studierichting'];
            $array['assistentie_leerkracht'] = $_REQUEST['AssisLeerkracht'];
            $array['lokaal_id'] = $_REQUEST['LokaalId'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            if(!empty($idDBFRichting)){
                $databank->update('tbl_dbf_richtingen',$array, "dbf_richting_id=?", $idDBFRichting);
            }else{
                $databank->insert('tbl_dbf_richtingen',$array);
            }
            header("Location: dbfRichtingen.php");
            exit(0);
         }
     }

    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/dbfRichtingenAc.tpl');
