<?php
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "kleiwerk.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    if(!isset($_REQUEST['id'])){
        header("Location:kleiwerk.php");
        exit(0);
    }

    $tpl = new SpoonTemplate();
    $idKleiwerk = $_REQUEST['id'];
    $kleiwerkInfo = $biz->getKleiwerkById($idKleiwerk);
    $tpl->assign("Leerling", $kleiwerkInfo['aanspreek']);
    $frm = new SpoonForm('klei', 'kleiwerkAc.php?id='.$idKleiwerk);
    $frm->addText('Kleiwerk', $kleiwerkInfo['kleiwerk'], 45);
    $frm->addTextarea('KleiwerkComm', $kleiwerkInfo['kleiwerk_commentaar']);
    $frm->addText('KleiwerkEval', $kleiwerkInfo['kleiwerk_evaluator'], 45);
    $frm->addButton('submit', 'Kleiwerk', 'submit');
    if($frm->isSubmitted()){
        $databank = $biz->getDataConnect();
        $array = array();
        $array['kleiwerk']=$_REQUEST['Kleiwerk'];
        $array['kleiwerk_commentaar']=$_REQUEST['KleiwerkComm'];
        $array['kleiwerk_evaluator']=$_REQUEST['KleiwerkEval'];
        $array['gewijzigd_door'] = $adminInfo['rangschik'];

        $databank->update("tbl_kleiwerk", $array, "kleiwerk_id=?", $idKleiwerk);
        header("Location:kleiwerk.php");
        exit(0);
    }

    $tpl->setForceCompile(true);
    $tpl->setCompileDirectory('created_templates');
    $frm->parse($tpl);
    $tpl->display('admintemplates/kleiwerkAc.tpl');



