<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "b2dProjecten.php");

    $b2dProjectentpl= new Template("admintemplates/");
    $b2dProjectentpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $b2dProjectentpl->set_file("tabellen_tp", "tabellen.tpl");
    $b2dProjectentpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $b2dProjectentpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $b2dProjectentpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $b2dProjectentpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    if(isset($_REQUEST['value'])){
        $_SESSION['filterNaam'] = $_REQUEST['value'];
    }

    $formNaam = '<form action="" method="post">
                      <label for="filter">Naam:</label>
                      <input type="text" id="filter" name="filter" value="{VALUENAAM}" maxlength="250" class="inputText" />
                      <input type="submit" value="Filter" id="submit" name="submit" class="inputButton" />
                 </form>';
    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        if(!empty($_REQUEST['filter'])){
            if($bizAdm->naamProjectExists($_REQUEST['filter'])){
                $_SESSION['filterNaam'] = $_REQUEST['filter'];
            }
        }else{
            $_SESSION['filterNaam'] ="";
        }
    }
    $filterNaam = str_replace('"','&quot;',$_SESSION['filterNaam']);
    $b2dProjectentpl->set_var("TABLE", $formNaam . $bizAdm->getProjectenTable(30, $filterNaam));
    $b2dProjectentpl->set_var("VALUENAAM", $filterNaam);

    $b2dProjectentpl->parse("HEAD", "HEADBLOCK");
    $b2dProjectentpl->parse("CONTENT", "CONTENTBLOCK");
    $b2dProjectentpl->pparse("htmlcode", "pageAdm_tp");