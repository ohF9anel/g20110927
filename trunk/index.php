<?php
    require_once "classes/spoon.php"; //<==
    require_once "classes/spoon/database/database.php";//<==
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    require "classes/bizLLNFunctions.class.php";
	require_once '/home/godfried/godsdienst.inc';

    session_start();
    //Databank verbinding
	// gb TODO dit moet hier weg 19:58 zondag 11 september 2011
	// DBZ_DBSERVER, DBZ_DBUSER, DBZ_DBPASS
    $db = new SpoonDatabase('mysql', DBZ_DBSERVER, DBZ_DBUSER, DBZ_DBPASS, DBZ_DBDB);

    /* BELANGRIJK!!!
     * Enkel op de INDEX pagina
     * Bij het starten van de website:
     *  - Controleren: over welke gebruiker gaat het hier (admin/beheerder - leerling)
     *  - Een session maken met de juiste id van de ingelogde gebruiker
     *  - Andere sessions aanmaken die nodig zijn voor het admingedeelte
     *  - Indien een gebruiker geen toegang mag hebben tot de website wordt deze doorverwezen naar de fail.php pagina
     *  - Dit stuk code maakt page redirection mogelijk.
     *      Indien een persoon de website start vanop een bepaalde pagina, dat niet de index pagina is, dan wordt er
     *      op die bepaalde pagina een redirectPage session gemaakt en wordt er gelinkt naar de index.php pagina waar
     *      de noodzakelijke controles i.v.m. de gebruiker zullen plaatsvinden.
     */
    if(!isset($_SESSION['user']) || !isset($_SESSION['admin'])){
        $var="";
        if(isset($_SERVER['PHP_AUTH_USER'])){
            $var = $db->getRecord("SELECT uuid,ut,ln from tbl_fmad where un='".$_SERVER['PHP_AUTH_USER']."'"); //APACHE gebruikers uit fmad selecteren
        }else{
            $var = $db->getRecord("SELECT uuid,ut from tbl_fmad where un='".substr($_SERVER['AUTH_USER'],4)."'"); //IIS
        }
        if($var != null){ //fmad tabel mag niet leeg zijn
            if($var['ut']==2){ // leerlingen
                $persoonId = $db->getRecord("SELECT leerling_id FROM tbl_leerlingen WHERE uuid=?",$var['uuid']);
                if(!empty($persoonId)){ 
                    $_SESSION['user'] = $persoonId['leerling_id']; //183;//dit is een vaste id om te testen vervangen door $persoonId['leerling_id'];
                }else{
                    header("Location:fail.php");
                    exit(0);
                }
            }
            else if($var['ut']==1){ //leerkrachten
			// echo iconv('Windows-1252', 'UTF-8', $var['ln']);
                $persoonId = $db->getRecord("SELECT leerkracht_id,admin FROM tbl_leerkrachten WHERE uuid=?",$var['uuid']);
                if($persoonId['admin'] == 1 || $persoonId['admin']==2){
                    $_SESSION['admin'] = $persoonId['leerkracht_id'];
                    $_SESSION['schooljaarFilter'] ="";  //Deze sessie moet geset zijn om geen fouten te krijgen later
                    $_SESSION['klasNaamFilter'] ="";    //Deze sessie moet geset zijn om geen problemen te krijgen
                    $_SESSION['filterTitel'] ="";       //Deze sessie moet geset zijn om geen problemen te krijgen
                    $_SESSION['filterNaam'] ="";       //Deze sessie moet geset zijn om geen problemen te krijgen
                    $_SESSION['filterActief'] ="";       //Deze sessie moet geset zijn om geen problemen te krijgen
                    $_SESSION['filterAanspreek'] ="";       //Deze sessie moet geset zijn om geen problemen te krijgen
                    if(isset($_SESSION['redirectPage'])){
                        $redirect = $_SESSION['redirectPage'];
                        unset($_SESSION['redirectPage']);
                          header("Location:".$redirect);
                        exit(0);
                    }else{
                         header("Location:admin/indexAdm.php"); //linken naar Admin pagina
                        exit(0);
                    }
                }else{
                    header("Location:fail.php");
                    exit(0);
                }
            }
        }
        else{ //als de fmad leeg is wordt er gelinkt naar de fail.php pagina
            header("Location:fail.php");
            exit(0);
        }
        if(isset($_SESSION['redirectPage'])){ //de pagina waarnaar gelinkt wordt
            $redirect = $_SESSION['redirectPage'];
            unset($_SESSION['redirectPage']);
            header("Location:".$redirect);
            exit(0);
        }
    }

    //INDEX.PHP
    $indextpl= new Template("usertemplates/");
    $indextpl->set_file("page_tp", "page.tpl");
    $indextpl->set_file("index_tp", "index.tpl");
    $indextpl->set_block("index_tp", "CONTENTBLOCK", "contentblock");

    //Op alle pagina's
    $biz = new bizLLNFunctions();
    $indextpl->set_var("LEUZE", $biz->getQuote());
    $leerling_id = $_SESSION['user'];
    $leerling = $biz->getUserInfo($leerling_id);
    $indextpl->set_var("ACCOUNT", $leerling['aanspreek']);

    $indextpl->parse("CONTENT", "CONTENTBLOCK");
    $indextpl->pparse("htmlcode", "page_tp");
	