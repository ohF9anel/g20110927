<?php
    require_once "classes/spoon.php"; //<==
    require_once "classes/spoon/database/database.php";//<==
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    $biz = new bizLLNFunctions();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "jwKeuze.php");

    //als een leerling een jaarwerk heeft moet deze niet meer in staat zijn een keuze op te geven
    $leerling_id = $_SESSION['user'];
    if($biz->heeftLLNEenJaarwerk($leerling_id, staticFunctions::createSchooljaar())){
        header("Location:jwPersoonlijkeGegevens.php");
        exit(0);
    }
    //Databank verbinding
    $db = $biz->getDatabankConnection();

    $jwKeuzetpl= new Template("usertemplates/");
    $jwKeuzetpl->set_file("page_tp", "page.tpl");
    $jwKeuzetpl->set_file("jwKeuze_tp", "jwKeuze.tpl");
    $jwKeuzetpl->set_block("jwKeuze_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $jwKeuzetpl->set_var("LEUZE", $biz->getQuote());
    $leerling = $biz->getUserInfo($leerling_id);
    $jwKeuzetpl->set_var("ACCOUNT", $leerling['aanspreek']);

    //Opvullen van droplist met leerlingen van dezelfde klasgroep
    //De 2 droplisten vullen met de namen van alle leerlingen die in dezelfde klasgroep zitten
    //2 groepsledenHtmlDroplist's zodat er onderscheid kan gemaakt worden tussen de 2 droplisten (name/id=groepsleden2 EN name/id=groepsleden3)
    $jwKeuzetpl->set_var("GEBRUIKER",$leerling['aanspreek']);
    $groepsledenHtmlDroplist2 = "<select name='groepsleden2' id='groepsleden2'>
                                    <option value='geen leerling'>Geen leerling</option>";
    $groepsledenHtmlDroplist3 = "<select name='groepsleden3' id='groepsleden3'>
                                    <option value='geen leerling'>Geen leerling</option>";
    $klasgroepLLN = $biz->getKlasgroepLlnHTMLDroplist($leerling_id);
    $groepsledenHtmlDroplist2 .=$klasgroepLLN;
    $groepsledenHtmlDroplist3 .=$klasgroepLLN;

    $jwKeuzetpl->set_var("DROPDOWNGROEPSLID2",$groepsledenHtmlDroplist2);
    $jwKeuzetpl->set_var("DROPDOWNGROEPSLID3",$groepsledenHtmlDroplist3);


    //controle op velden als deze verzonder worden
    $msgGroepsleden="";
    $msgTitel ="";
    $msgOnderwerp="";
    $msgBeschrijving ="";
    $msgErvaringsdeskundige ="";
    $msgVoorstelling ="";

    $titel="";
    $onderwerp="";
    $beschrijving="";
    $ervaringsdeskundige="";
    $voorstelling="";
    if(isset($_REQUEST['btnSendJW'])){
        $allok = true;
        $groepslid2 = $_REQUEST['groepsleden2'];
        $groepslid3 = $_REQUEST['groepsleden3'];
        $titel = $_REQUEST['titel'];
        $onderwerp = $_REQUEST['onderwerp'];
        $beschrijving = $_REQUEST['beschrijving'];
        $ervaringsdeskundige = $_REQUEST['ervaringsdeskundige'];
        $voorstelling = $_REQUEST['voorstelling'];
        $periode = $_REQUEST['periode'];

        if($groepslid2 == $groepslid3 && ($groepslid2!='geen leerling' && $groepslid3!="geen leerling")){
            $msgGroepsleden = "Gelieve verschillende groepsleden te kiezen!";
            $allok =false;
        }
        if(empty($titel)){
            $msgTitel ="Gelieve een titel in te geven!";
            $titel="";
            $allok=false;
        }
        if(empty($onderwerp)){
            $msgOnderwerp = "Gelieve een onderwerp in te geven!";
            $onderwerp="";
            $allok =false;
        }
        if(empty($beschrijving)){
            $msgBeschrijving ="Gelieve een beschrijving op te geven!";
            $allok =false;
        }
        if(empty($ervaringsdeskundige)){
            $msgErvaringsdeskundige = "Gelieve een ervaringsdeskundige op te geven!";
            $allok =false;
        }
        if(empty($voorstelling)){
            $msgVoorstelling = "Gelieve een manier van voorstellen op te geven!";
            $allok =false;
        }
        if($allok){
            $arrayJWGroep = array();
            $arrayJWGroep['titel'] = htmlentities($titel);
            $arrayJWGroep['onderwerp']=$onderwerp;
            $arrayJWGroep['beschrijving']=$beschrijving;
            $arrayJWGroep['specialist']=$ervaringsdeskundige;
            $arrayJWGroep['voorstelling']=$voorstelling;
            $arrayJWGroep['periode']=$periode;
            $arrayJWGroep['datum'] = date("Y-m-d G:i:s");
            $arrayJWGroep['gewijzigd_door']=$leerling['rangschik'];

            //gegevens inbrengen in de tbl_jaarwerk_groep
            $jaarwerkGroepId = $db->insert("tbl_jaarwerk_groep", $arrayJWGroep);
            $arrayJWLeerling = array();
            $arrayJWLeerling['jaarwerk_groep_id'] = $jaarwerkGroepId;
            $arrayJWLeerling['jaarwerk_persoonlijke_inzet'] = "";
            $arrayJWLeerling['schooljaar'] = staticFunctions::createSchooljaar();
            $arrayJWLeerling['gewijzigd_door']= $leerling['rangschik'];

            $gekozenLLN = array($leerling_id, $groepslid2, $groepslid3);
            foreach ($gekozenLLN as $g){
                if($g != 'geen leerling'){
                    $arrayJWLeerling['leerling_id'] = $g;
                    $db->insert("tbl_jaarwerk_leerling",$arrayJWLeerling);
                }
            }
            header("Location:jwPersoonlijkeGegevens.php");
            exit(0);
        }
    }
    //invullen van de velden met de ingegeven waarde als er een foute ingave is gebeurt
    $jwKeuzetpl->set_var("VALTITEL",$titel);
    $jwKeuzetpl->set_var("VALONDERWERP",$onderwerp);
    $jwKeuzetpl->set_var("VALBESCH",$beschrijving);
    $jwKeuzetpl->set_var("VALERVARING",$ervaringsdeskundige);
    $jwKeuzetpl->set_var("VALVOORSTELLING",$voorstelling);

    //Invullen van de foutmeldingen
    $jwKeuzetpl->set_var("MSGGROEPSLEDEN",$msgGroepsleden);
    $jwKeuzetpl->set_var("MSGTITEL",$msgTitel);
    $jwKeuzetpl->set_var("MSGONDERWERP",$msgOnderwerp);
    $jwKeuzetpl->set_var("MSGBESCHRIJVING",$msgBeschrijving);
    $jwKeuzetpl->set_var("MSGERVARINGSDESKUNDIGE",$msgErvaringsdeskundige);
    $jwKeuzetpl->set_var("MSGVOORSTELLING",$msgVoorstelling);

    $jwKeuzetpl->parse("CONTENT", "CONTENTBLOCK");
    $jwKeuzetpl->pparse("htmlcode", "page_tp");
	