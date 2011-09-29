<?php
    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require "../classes/bizADMQueryFunctions.class.php";
    require_once "../classes/staticFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "quotes.php");
    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);

    $quotestpl= new Template("admintemplates/");
    $quotestpl->set_file("pageAdm_tp", "pageAdm.tpl");
    $quotestpl->set_file("quotesAc_tp", "quotesAc.tpl");
    $quotestpl->set_block("quotesAc_tp", "CONTENTBLOCK", "contentblock");

    $idQuote="";
    if(isset($_REQUEST['id'])){
        $idQuote = $_REQUEST['id'];
        $quoteTekst = $biz->getQuoteTekstById($idQuote);
        // gb $quotestpl->set_var("QUOTETEKST", iconv('Windows-1252', 'UTF-8', $quoteTekst['quote']));
        $quotestpl->set_var("QUOTETEKST", $quoteTekst['quote']);
    }

    $databank = $biz->getDataConnect();
    $msgQuote="";
    if(isset($_REQUEST['del'])){ //het verwijderen van een quote
        $databank->delete("tbl_quotes","quote_id=?",$_REQUEST['del']);
        $quotestpl->set_var("JAVASCRIPTDEL",'<script type="text/javascript">
                                                alert("De quote is verwijderd");
                                                window.location="quotes.php";
                                            </script>');        
    }
    else if(isset($_REQUEST['btnQuote'])){
        if(empty($idQuote)){//het maken van een nieuwe quote
            $ok = true;
            if(empty($_REQUEST['quote'])){
                $msgQuote = "Gelieve een quote in te vullen.";
                $ok = false;
            }
            if($ok){
                $array = array();
                // if ($value) $value = iconv('Windows-1252', 'UTF-8', $value);
                $array['quote'] = $_REQUEST['quote'];
                // gb $array['quote'] = iconv('Windows-1252', 'UTF-8', $_REQUEST['quote']);
                $databank->insert("tbl_quotes", $array);
                header("Location:quotes.php");
                exit(0);
            }
        }else{ // het aanpassen van een quote
            $ok = true;
            if(empty($_REQUEST['quote'])){
                $msgQuote = "Gelieve een quote in te vullen.";
                $ok = false;
            }
            if($ok){
                $array = array();
                $array['quote'] = $_REQUEST['quote'];
                $databank->update("tbl_quotes", $array,"quote_id=?",$idQuote);
                header("Location:quotes.php");
                exit(0);
            }
        }
    }

    $quotestpl->set_var("MSGQUOTE", $msgQuote);

    $quotestpl->parse("HEAD", "HEADBLOCK");
    $quotestpl->parse("CONTENT", "CONTENTBLOCK");
    $quotestpl->pparse("htmlcode", "pageAdm_tp");




