<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "b2dProjecten.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    $tpl = new SpoonTemplate();

    $idB2DProject="";
    $B2DProjectInfo = "";
    $frm =new SpoonForm('b2dProjecten', 'b2dProjectenAc.php');
    if(isset($_REQUEST['id'])){
        $idB2DProject = $_REQUEST['id'];
        $B2DProjectInfo = $biz->getB2DProjectById($idB2DProject);
        $frm = new SpoonForm('b2dProjecten', 'b2dProjectenAc.php?id='.$idB2DProject);
        $frm->addText('NaamProject',$B2DProjectInfo['naam'],75);
        $frm->addDropdown('ProjectSoort',$biz->getB2DProjectSoorten(),$B2DProjectInfo['project_soort_id']);
        $frm->addText('AantalDeeln',$B2DProjectInfo['aantal_deelnemers'],45);
        $frm->addText('Str',$B2DProjectInfo['straat'],75);
        $frm->addText('Zi',$B2DProjectInfo['zip'],45);
        $frm->addText('Gem',$B2DProjectInfo['gemeente'],45);
        $frm->addText('Tel',$B2DProjectInfo['telefoon'],45);
        $frm->addText('Fa',$B2DProjectInfo['fax'],45);
        $frm->addText('Email',$B2DProjectInfo['email'],245);
        $frm->addText('Verantw',$B2DProjectInfo['verantwoordelijke'],115);
        $frm->addTextarea('Omsch',$B2DProjectInfo['omschrijving']);
        $status[] = array('label' => 'Nee', 'value' => '0');
        $status[] = array('label' => 'Ja', 'value' => '1');
        $frm->addRadiobutton('Afgev',$status, ($B2DProjectInfo['afgevoerd']== 1 ? '1' : '0'));
        $frm->addText('TerAtt',$B2DProjectInfo['ter_attentie_van'],145);
        $frm->addTextArea('EvalVraag',$B2DProjectInfo['evaluatie_vraag']);
    }else{
        $frm->addText('NaamProject',null,75);
        $frm->addDropdown('ProjectSoort',$biz->getB2DProjectSoorten());
        $frm->addText('AantalDeeln',null,45);
        $frm->addText('Str',null,75);
        $frm->addText('Zi',null,45);
        $frm->addText('Gem',null,45);
        $frm->addText('Tel',null,45);
        $frm->addText('Fa',null,45);
        $frm->addText('Email',null,245);
        $frm->addText('Verantw',null,115);
        $frm->addTextarea('Omsch');
        $status[] = array('label' => 'Nee', 'value' => '0');
        $status[] = array('label' => 'Ja', 'value' => '1');
        $frm->addRadiobutton('Afgev',$status);
        $frm->addText('TerAtt',null,145);
        $frm->addTextArea('EvalVraag');
    }
    $frm->addButton('submit', 'B2D project', 'submit');// add submit button

    if($frm->isSubmitted()){
        $rexTelefoon = "/^[0-9\.\ \/]+$/";

        $frm->getField('NaamProject')->isFilled("Gelieve een naam in te geven");
        $frm->getField('ProjectSoort')->isFilled("Gelieve een project soort te kiezen");
        if($frm->getField('AantalDeeln')->isFilled()){
            if($frm->getField('AantalDeeln')->isInteger("Gelieve een getal in te geven")){
                $frm->getField('AantalDeeln')->isBetween(0,50,"Getallen moeten van 0 tot 50 zijn");
            }
        }
        if($frm->getField('Zi')->isFilled()){
            $frm->getField('Zi')->isInteger("Gelieve een getal in te geven");
        }
        if($frm->getField('Tel')->isFilled()){
            $frm->getField('Tel')->isValidAgainstRegexp($rexTelefoon, "Gelieve een correct telefoon nummer in te geven");
        }
        if($frm->getField('Fa')->isFilled()){
            $frm->getField('Fa')->isValidAgainstRegexp($rexTelefoon, "Gelieve een correct fax nummer in te geven");
        }
        if($frm->getField('Email')->isFilled()){
            $frm->getField('Email')->isEmail("Gelieve een correct email in te geven");
        }
        if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $array = array();
            $array['naam'] = htmlentities($_REQUEST['NaamProject']);
            $array['project_soort_id'] = $_REQUEST['ProjectSoort'];
            $array['aantal_deelnemers'] = $_REQUEST['AantalDeeln'];
            $array['straat'] = $_REQUEST['Str'];
            $array['zip'] = $_REQUEST['Zi'];
            $array['gemeente'] = $_REQUEST['Gem'];
            $array['telefoon'] = $_REQUEST['Tel'];
            $array['fax'] = $_REQUEST['Fa'];
            $array['email'] = $_REQUEST['Email'];
            $array['verantwoordelijke'] = $_REQUEST['Verantw'];
            $array['omschrijving'] = $_REQUEST['Omsch'];
            $array['afgevoerd'] = $_REQUEST['Afgev'];
            $array['ter_attentie_van'] = $_REQUEST['TerAtt'];
            $array['evaluatie_vraag'] = $_REQUEST['EvalVraag'];
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            if(!empty($idB2DProject)){
                $databank->update('tbl_b2d_projecten',$array, "project_id=?", $idB2DProject);
            }else{
                $array['invoerdatum'] = date("Y-m-d G:i:s");
                $databank->insert('tbl_b2d_projecten',$array);
            }
            header("Location: b2dProjecten.php");
            exit(0);
        }
    }


    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/b2dProjectenAc.tpl');
