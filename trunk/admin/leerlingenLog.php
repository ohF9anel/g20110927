<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "leerlingenLog.php");

    $leerlingenLogtpl= new Template("admintemplates/");
    $leerlingenLogtpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $leerlingenLogtpl->set_file("tabellen_tp", "tabellen.tpl");
    $leerlingenLogtpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $leerlingenLogtpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $leerlingenLogtpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $leerlingenLogtpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $formAanspreek = '<form action="" method="post">
                          <label for="filter">Leerling aanspreek:</label>
                          <input type="text" id="filter" name="filter" value="{VALUEAANSPREEK}" maxlength="250" class="inputText" />
                          <input type="submit" value="Filter" id="submit" name="submit" class="inputButton" />
                      </form>';
    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        if(!empty($_REQUEST['filter'])){
            if($bizAdm->aanspreekInLeerlingLogExist($_REQUEST['filter'])){
                $_SESSION['filterAanspreek'] = $_REQUEST['filter'];
            }
        }else{
            $_SESSION['filterAanspreek'] ="";
        }
    }
    $filterAanspreek = $_SESSION['filterAanspreek'];
    $leerlingenLogtpl->set_var("TABLE", $formAanspreek . $bizAdm->getLeerlingenLogTable(30, $filterAanspreek));
    $leerlingenLogtpl->set_var("VALUEAANSPREEK", $filterAanspreek);
    

    $leerlingenLogtpl->parse("HEAD", "HEADBLOCK");
    $leerlingenLogtpl->parse("CONTENT", "CONTENTBLOCK");
    $leerlingenLogtpl->pparse("htmlcode", "pageAdm_tp");
