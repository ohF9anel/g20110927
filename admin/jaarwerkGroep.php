<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";
    require_once "../classes/bizADMQueryFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "jaarwerkGroep.php");

    $jaarwerkGroeptpl= new Template("admintemplates/");
    $jaarwerkGroeptpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $jaarwerkGroeptpl->set_file("tabellen_tp", "tabellen.tpl");
    $jaarwerkGroeptpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $jaarwerkGroeptpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $jaarwerkGroeptpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $jaarwerkGroeptpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    if(isset($_REQUEST['value'])){
        $bizQ = new bizADMQueryFunctions();
        $jaarwerk_groep = $bizQ->getJaarwerkGroepInfoById($_REQUEST['value']);
        $_SESSION['filterTitel'] = $jaarwerk_groep[0]['titel'];
    }

    $formTitel = '<form action="" method="post">
                      <label for="filter">Titel:</label>
                      <input type="text" id="filter" name="filter" value="{VALUETITEL}" maxlength="250" class="inputText" />
                      <input type="submit" value="Filter" id="submit" name="submit" class="inputButton" />
                 </form>';
    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        if(!empty($_REQUEST['filter'])){
            if($bizAdm->titelJWGroepExists($_REQUEST['filter'])){
                $_SESSION['filterTitel'] = $_REQUEST['filter'];
            }
        }else{
            $_SESSION['filterTitel'] ="";
        }
    }
    $filterTitel = $_SESSION['filterTitel'];
    $jaarwerkGroeptpl->set_var("TABLE", $formTitel . $bizAdm->getJaarwerkGroepTable(30, $filterTitel));
    $jaarwerkGroeptpl->set_var("VALUETITEL", $filterTitel);

    $jaarwerkGroeptpl->parse("HEAD", "HEADBLOCK");
    $jaarwerkGroeptpl->parse("CONTENT", "CONTENTBLOCK");
    $jaarwerkGroeptpl->pparse("htmlcode", "pageAdm_tp");

