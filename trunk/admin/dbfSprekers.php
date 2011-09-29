<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require "../classes/bizADMFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "dbfSprekers.php");

    $dbfSprekerstpl= new Template("admintemplates/");
    $dbfSprekerstpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $dbfSprekerstpl->set_file("tabellen_tp", "tabellen.tpl");
    $dbfSprekerstpl->set_block("tabellen_tp", "HEADBLOCK", "headblock");
    $dbfSprekerstpl->set_block("tabellen_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $bizAdm = new bizADMFunctions();
    $dbfSprekerstpl->set_var("LEUZE", $bizAdm->getQuote());
    $admin_id = $_SESSION['admin'];
    $adminInfo = $bizAdm->getAdminInfo($admin_id);
    $dbfSprekerstpl->set_var("ACCOUNT", iconv('Windows-1252', 'UTF-8', $adminInfo['aanspreek']));
    staticFunctions::isAdminOfBeheerder($adminInfo['admin']);

    $formDBFSpreker = '<form action="" method="post">
                              <label for="schooljaarFilter">Schooljaar:</label>
                              <input type="text" id="schooljaarFilter" name="schooljaarFilter" value="{VALUESCHOOLJAAR}" maxlength="10" class="inputText" />
                              <br/><label for="filterActief">Actief:</label>
                              <input type="radio" name="filterActief" value="nee" /> Nee
                              <input type="radio" name="filterActief" value="ja" /> Ja
                              <input type="radio" name="filterActief" value="misschien" /> Misschien
                              <input type="radio" name="filterActief" value="alles" /> Alles weergeven
                              <input type="submit" value="Filter" id="submit" name="submit" class="inputButton" />
                      </form>';
    //De filters die toegepast moeten worden op de tabellen
    if(isset($_REQUEST['submit'])){
        staticFunctions::SchooljaarFilterCorrect($_REQUEST['schooljaarFilter']);
        if(!empty($_REQUEST['filterActief'])){
            switch ($_REQUEST['filterActief']){
                case "ja": $_SESSION['filterActief'] = "ja";
                    break;
                case "nee": $_SESSION['filterActief'] = "nee";
                    break;
                case "misschien": $_SESSION['filterActief'] = "misschien";
                    break;
                case "alles": $_SESSION['filterActief'] = "";
            }
        }else{
            $_SESSION['filterActief'] = "";
        }
    }
    $filterSchooljaar = $_SESSION['schooljaarFilter'];
    $filterActief = $_SESSION['filterActief'];
    $dbfSprekerstpl->set_var("TABLE", $formDBFSpreker . $bizAdm->getdbfSprekersTable(30, $filterSchooljaar,$filterActief ));
    $dbfSprekerstpl->set_var("VALUESCHOOLJAAR", $filterSchooljaar);

    $dbfSprekerstpl->parse("HEAD", "HEADBLOCK");
    $dbfSprekerstpl->parse("CONTENT", "CONTENTBLOCK");
    $dbfSprekerstpl->pparse("htmlcode", "pageAdm_tp");


