<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "leerkrachten.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);
    staticFunctions::isAdmin($adminInfo['admin'], "leerkrachten.php");

    if(!isset($_REQUEST['id']) ){
        header("Location:leerkrachten.php");
        exit(0);
    }

    $tpl = new SpoonTemplate();
    $leerkracht_id = $_REQUEST['id'];
    $leerkrachtInfo = $biz->getLeerkrachtById($leerkracht_id);
    $tpl->assign('naamLeerkracht',$leerkrachtInfo['aanspreek']);

    $frm = new SpoonForm('leerkrachten', 'leerkrachtenAc.php?id='.$leerkracht_id);
    $frm->addText('Str', $leerkrachtInfo['straat'], 250);
    $frm->addText('Hn', $leerkrachtInfo['huisnummer'], 250);
    $frm->addText('Po', $leerkrachtInfo['postcodewp'], 250);
    $frm->addText('Bu', $leerkrachtInfo['bus'], 250);
    $frm->addText('Gem', $leerkrachtInfo['gemeente'], 250);
    $frm->addText('Tel', $leerkrachtInfo['tel_verantw'], 250);
    $frm->addText('Gs', $leerkrachtInfo['gsm'], 250);
    $frm->addDate('Geb', strtotime($leerkrachtInfo['geboortedatum']),'Y-m-d');
    $frm->addText('email', $leerkrachtInfo['email'], 250);// add textfield
    $frm->addFile('pasfoto');

    $functions['0'] = 'Leerkracht';
    $functions['1'] = 'Beheerder';
    $functions['2'] = 'Admin';
    $frm->addDropdown('functie', $functions, $leerkrachtInfo['admin']);
    $frm->addButton('submit', 'Leerkracht aanpassen', 'submit');// add submit button
    if($frm->isSubmitted()){
        $rexTelefoon = "/^[0-9\.\ \/]+$/";

        if($frm->getField('Tel')->isFilled()){
            $frm->getField('Tel')->isValidAgainstRegexp($rexTelefoon, "Gelieve een correct telefoon nummer in te geven");
        }
        if($frm->getField('Gs')->isFilled()){
            $frm->getField('Gs')->isValidAgainstRegexp($rexTelefoon, "Gelieve een correct telefoon nummer in te geven");
        }
        if($frm->getField('Geb')->isFilled()){
            $frm->getField('Geb')->isValid('Gelieve een datum in te geven als (yyyy-mm-dd)');
	}
        if($frm->getField('email')->isFilled()){
            $frm->getField('email')->isEmail('Gelieve een correct e-mailadres op te geven');
        }
        if($frm->getField('pasfoto')->isFilled()){
            $frm->getField('pasfoto')->isAllowedExtension(array('jpg', 'jpeg', 'bmp', 'png'), 'Enkel jpg,jpeg,bmp en png zijn toegestaan');
        }
        if($frm->isCorrect()){
            $databank = $biz->getDataConnect();
            $array = array();
            $array['straat'] = $_REQUEST['Str'];
            $array['huisnummer'] = $_REQUEST['Hn'];
            $array['postcodewp'] = $_REQUEST['Po'];
            $array['bus'] = $_REQUEST['Bu'];
            $array['gemeente'] = $_REQUEST['Gem'];
            $array['tel_verantw'] = $_REQUEST['Tel'];
            $array['gsm'] = $_REQUEST['Gs'];
            $array['geboortedatum'] = $_REQUEST['Geb'];
            $array['email'] = $_REQUEST['email'];
            $array['admin'] = $_REQUEST['functie'];
            if($frm->getField('pasfoto')->isFilled()){
                $array['pasfoto'] = "images/pasfotos/".$frm->getField('pasfoto')->getFileName();
            }
            $array['gewijzigd_door'] = $adminInfo['rangschik'];

            $databank->update('tbl_leerkrachten',$array, "leerkracht_id=?", $leerkracht_id);
            header("Location: leerkrachten.php");
            exit(0);
        }
    }

    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/leerkrachtenAc.tpl');