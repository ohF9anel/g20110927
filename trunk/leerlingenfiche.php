<?php
    require_once "classes/spoon.php"; //<==
    require_once "classes/spoon/database/database.php";//<==
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
    require_once "classes/staticFunctions.class.php";

    session_start();
    staticfunctions::userControl(isset($_SESSION['user']), isset($_SESSION['admin']), "leerlingenfiche.php");

    $leerlingenfichetpl= new Template("usertemplates/");
    $leerlingenfichetpl->set_file("page_tp", "page.tpl");
    $leerlingenfichetpl->set_file("leerlingenfiche_tp", "leerlingenfiche.tpl");
    $leerlingenfichetpl->set_block("leerlingenfiche_tp", "HEADBLOCK", "headblock");
    $leerlingenfichetpl->set_block("leerlingenfiche_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();

    $leerlingenfichetpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $leerlingenfichetpl->set_var("ACCOUNT", $leerling['aanspreek']);

    //Databank verbinding
    $db = $biz->getDatabankConnection();

    //Het invullen van de form velden
    $leerlingenfichetpl->set_var("NAAM",$leerling['aanspreek']);
    $leerlingenfichetpl->set_var("STRAAT",$leerling['straat']);
    $leerlingenfichetpl->set_var("HN",$leerling['huisnummer']);
    $leerlingenfichetpl->set_var("BUS",$leerling['bus']);
    $leerlingenfichetpl->set_var("POSTCODE",$leerling['postcodewp']);
    $leerlingenfichetpl->set_var("GEMEENTE",$leerling['gemeente']);
    $leerlingenfichetpl->set_var("TELEFOON",$leerling['tel_verantw']);
    $leerlingenfichetpl->set_var("GSM",$leerling['gsm']);
    $leerlingenfichetpl->set_var("EMAIL",$leerling['email']);
    $today = getdate();
    $today['year'] = $today['year']-17;
    if(empty($leerling['geboortedatum'])){
        $leerlingenfichetpl->set_var("GEBOORTEDATUM",$today['year'].'-01-01');
    }else{
        $leerlingenfichetpl->set_var("GEBOORTEDATUM",date("Y-m-d",strtotime($leerling['geboortedatum'])));
    }

    //Het controleren van de nieuw opgegeven informatie
    $msgStraat = "";
    $msgHn="";
    $msgBus="";
    $msgPostcode="";
    $msgGemeente="";
    $msgTelefoon="";
    $msgGsm ="";
    $msgEmail="";
    $msgGeboortedatum="";

    if(isset($_REQUEST['btnSendLeerlingenfiche'])){
        $allok = true;
        $straat = $_REQUEST['straat'];
        $hn = $_REQUEST['hn'];
        $bus = $_REQUEST['bus'];
        $postcode = $_REQUEST['postcode'];
        $gemeente= $_REQUEST['gemeente'];
        $telefoon= $_REQUEST['telefoon'];
        $gsm= $_REQUEST['gsm'];
        $email= $_REQUEST['email'];
        $geboortedatum = $_REQUEST['geboortedatum'];

        $rexTelefoon = "/^[0-9\ \/]+$/";
        $rexCombinatie = "/^[a-zA-Z0-9 ]*$/";
        $rexEmail = "/^[\w+\.\+-]+@(([\w\+-])+\.)+[a-z]{2,4}$/i";
        $rexDate ="/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/";

        if(empty($straat)){
            $msgStraat = "Gelieve een adres in te vullen!";
            $allok=false;
        }
        if(empty($postcode)){
            $msgPostcode = "Gelieve een postcode in te vullen!";
            $allok=false;
        }
        if(empty($gemeente)){
            $msgGemeente = "Gelieve een gemeente in te vullen!";
            $allok=false;
        }
        if(!empty($email) && !preg_match($rexEmail,$email)){
            $msgEmail = "Gelieve een bestaand e-mailadres in te vullen!";
            $allok=false;
        }
        if(!empty($telefoon) && !preg_match($rexTelefoon,$telefoon)){
            $msgTelefoon = "Gelieve een bestaand telefoon nummer in te vullen (055/12 34 56)!";
            $allok=false;
        }
        if(!empty($gsm) && !preg_match($rexTelefoon,$gsm)){
            $msgGsm = "Gelieve een bestaand GSM nummer in te vullen(0487/789 789)!";
            $allok=false;
        }
        if(empty($hn)){
            $msgHn = "Gelieve een huisnummer in te vullen!";
            $allok=false;
        }else if(!preg_match($rexCombinatie,$hn)){
            $msgHn = "Gelieve enkel getallen, letters en spaties te gebruiker!";
            $allok=false;
        }
        if(!empty($bus) && !preg_match($rexCombinatie,$bus)){
            $msgHn = "Gelieve enkel getallen, letters en spaties te gebruiker!";
            $allok=false;
        }
        if(!empty($geboortedatum) && !preg_match($rexDate, $geboortedatum)){
            $msgGeboortedatum = "Gelieve een juiste geboortedatum in te geven (YYYY-mm-dd)!";
            $allok=false;
        }
        if($allok){
            $updateArray = array();
            $updateArray['straat'] = $straat;
            $updateArray['huisnummer'] = $hn;
            $updateArray['bus'] = $bus;
            $updateArray['postcodewp'] = $postcode;
            $updateArray['gemeente']= $gemeente;
            $updateArray['tel_verantw']= $telefoon;
            $updateArray['gsm']=$gsm;
            $updateArray['email']=$email;
            $updateArray['geboortedatum']=date("y-m-d", strtotime($geboortedatum));
            $updateArray['gewijzigd_door'] = $leerling['rangschik'];

            $db->insert("tbl_leerlingen_log", $leerling); //$leerling bevat de oude gegevens
            $db->update("tbl_leerlingen", $updateArray ,"leerling_id=?",$leerling_id);

            header("Location:leerlingenfiche.php");
            exit(0);
        }
    }

    $leerlingenfichetpl->set_var("MSGSTRAAT",$msgStraat);
    $leerlingenfichetpl->set_var("MSGHUISNUMMER",$msgHn);
    $leerlingenfichetpl->set_var("MSGBUS",$msgBus);
    $leerlingenfichetpl->set_var("MSGPOSTCODE",$msgPostcode);
    $leerlingenfichetpl->set_var("MSGGEMEENTE",$msgGemeente);
    $leerlingenfichetpl->set_var("MSGTELEFOON",$msgTelefoon);
    $leerlingenfichetpl->set_var("MSGGSM",$msgGsm);
    $leerlingenfichetpl->set_var("MSGEMAIL",$msgEmail);
    $leerlingenfichetpl->set_var("MSGGEBOORTEDATUM",$msgGeboortedatum);

    $leerlingenfichetpl->parse("HEAD", "HEADBLOCK");
    $leerlingenfichetpl->parse("CONTENT", "CONTENTBLOCK");
    $leerlingenfichetpl->pparse("htmlcode", "page_tp");