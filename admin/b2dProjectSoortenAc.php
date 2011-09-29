<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "b2dProjectSoorten.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    $tpl = new SpoonTemplate();

    $idProjectSoort="";
    $projectSoortInfo = "";
    $frm =new SpoonForm('projectSoort', 'b2dProjectSoortenAc.php');
    if(isset($_REQUEST['id'])){
        $idProjectSoort = $_REQUEST['id'];
        $projectSoortInfo = $biz->getB2DProjectSoortById($idProjectSoort);
        $frm = new SpoonForm('projectSoort', 'b2dProjectSoortenAc.php?id='.$idProjectSoort);
        $frm->addText('CategorieSoort', $projectSoortInfo['categorie_soort'], 250);// add textfield
    }else{
        $frm->addText('CategorieSoort', null, 250);// add textfield
    }
    $frm->addButton('submit', 'Project soort', 'submit');// add submit button
  
    if($frm->isSubmitted()){
        $frm->getField('CategorieSoort')->isFilled('Gelieve een categorie soort in te vullen');
	if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $array = array();
            $array['categorie_soort'] = $_REQUEST['CategorieSoort'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];
            if(!empty($idProjectSoort)){
                $databank->update('tbl_b2d_project_soort',$array, "project_soort_id=?", $idProjectSoort);
            }else{
                $databank->insert('tbl_b2d_project_soort',$array);
            }
            header("Location: b2dProjectSoorten.php");
            exit(0);
        }
    }


    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/b2dProjectSoortenAc.tpl');
