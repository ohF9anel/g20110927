<?php
/* dit is waarschijnlijk een boilerplate script van David, om de bestaande gegevens naar de nieuwe structuur over te zetten
 * 
 */
    require_once "classes/spoon.php"; //<==
    require_once "classes/spoon/database/database.php";//<==
    require_once '/home/godfried/godsdienst.inc';

    print_r("Databank:");
    # TODO: dit moet hier weg
    $oude_db = 'accessl_david';
    $db = new SpoonDatabase('mysql', DBZ_DBSERVER, DBZ_DBUSER, DBZ_DBPASS, oude_db);
    $dbTest = new SpoonDatabase('mysql', DBZ_DBSERVER, DBZ_DBUSER, DBZ_DBPASS, DBZ_DBDB);

/*//SCHOOLJAAR VERANDEREN
	$schooljaarAANTAL = $db->getRecords("SELECT COUNT( * ) , SCHOOLJAAR FROM leerlingen GROUP BY SCHOOLJAAR");
	$schooljaar = $db->getRecords("SELECT SCHOOLJAAR,ID_lln FROM leerlingen");
	
	$null = 0;
	$negenAcht = 0;
	$negenNegen =0;
	$twee =0;
	$tweeEen =0;
	$tweeTwee =0;
	$tweeDrie = 0;
	$tweeVier =0;
	$tweeVijf =0;
	$tweeZes =0;
	$tweeZeven =0;
	$tweeAcht =0;
	$tweeNegen =0;
	$tweeTien =0;
	foreach($schooljaar as $s){
		$array = array();
		$sub = substr($s['SCHOOLJAAR'],0,4);
		if($sub == "1998"){
			$array['SCHOOLJAAR']="1998";
			$negenAcht++;
		}else if($sub == "1999"){
			$array['SCHOOLJAAR']="1999";
			$negenNegen++;
		}else if($sub == "2000"){
			$array['SCHOOLJAAR']="2000";
			$twee++;
		}else if($sub == "2001"){
			$array['SCHOOLJAAR']="2001";
			$tweeEen++;
		}else if($sub == "2002"){
			$array['SCHOOLJAAR']="2002";
			$tweeTwee++;
		}else if($sub == "2003"){
			$array['SCHOOLJAAR']="2003";
			$tweeDrie++;
		}else if($sub == "2004"){
			$array['SCHOOLJAAR']="2004";
			$tweeVier++;
		}else if($sub == "2005"){
			$array['SCHOOLJAAR']="2005";
			$tweeVijf++;
		}else if($sub == "2006"){
			$array['SCHOOLJAAR']="2006";
			$tweeZes++;
		}else if($sub == "2007"){
			$array['SCHOOLJAAR']="2007";
			$tweeZeven++;
		}else if($sub == "2008"){
			$array['SCHOOLJAAR']="2008";
			$tweeAcht++;
		}else if($sub == "2009"){
			$array['SCHOOLJAAR']="2009";
			$tweeNegen++;
		}else if($sub == "2010"){
			$array['SCHOOLJAAR']="2010";
			$tweeTien++;
		}else{
			$array['SCHOOLJAAR']="";
			$null++;
		}
		$db->update('leerlingen',$array,'ID_lln= ?',$s['ID_lln']);
	}
	
	var_dump($null);
	var_dump($negenAcht);
	var_dump($negenNegen);
	var_dump($twee);
	var_dump($tweeEen);
	var_dump($tweeTwee);
	var_dump($tweeDrie);
	var_dump($tweeVier);
	var_dump($tweeVijf);
	var_dump($tweeZes);
	var_dump($tweeZeven);
	var_dump($tweeAcht);
	var_dump($tweeNegen);
	var_dump($tweeTien);*/
	
/*//Voor en achternaam toevoegen
//1)klik op rangschikken aanspreek EERST verwijder persoon met alle gegevens NULL
//2)geef eerste godfried een aanspreek
//3)verwijder persoon met kies familienaam kies aanspreek
//==>-2 omdat je dan spaties filterd die helemaal op het einde staan van de string en waarop niets meer volgt bv "bosmans jan ";

$queryLLNaanpassen = "SELECT FAMNAAM,EERSTEVOOR,AANSPREEK from leerlingen where FAMNAAM is NULL or FAMNAAM='' or EERSTEVOOR is NULL or EERSTEVOOR =''";
$resultLLNaanpassen = $db->getRecords($queryLLNaanpassen);
$aantal =0;
$aantalE =0;
$aantalF =0;
if(count($resultLLNaanpassen) >0){
	foreach($resultLLNaanpassen as $r){
		$laatsteSpatie = strrpos($r['AANSPREEK'],' ',-2);
		if(($r['FAMNAAM']==null && $r['EERSTEVOOR']==null) || ($r['FAMNAAM']=="" && $r['EERSTEVOOR']=="") ){
			$aantal++;
			$array =array();
			$array['FAMNAAM'] = substr($r['AANSPREEK'],$laatsteSpatie+1);
			$array['EERSTEVOOR'] = substr($r['AANSPREEK'], 0, $laatsteSpatie);
			$db->update("leerlingen",$array,"AANSPREEK=?",$r['AANSPREEK']);
		}else if($r['EERSTEVOOR']==null || $r['EERSTEVOOR']==""){
			$aantalE++;
			$array =array();
			$array['EERSTEVOOR'] = substr($r['AANSPREEK'], 0, $laatsteSpatie);
			$db->update("leerlingen",$array,"AANSPREEK=?",$r['AANSPREEK']);
		}else if($r['FAMNAAM']==null || $r['FAMNAAM']==""){
			$aantalF++;
			$array =array();
			$array['FAMNAAM'] = substr($r['AANSPREEK'],$laatsteSpatie+1);
			$db->update("leerlingen",$array,"AANSPREEK=?",$r['AANSPREEK']);
		}
	}
}
var_dump("TOTAAL:".count($resultLLNaanpassen)." BEIDE:".$aantal." FAMNAAM:".$aantalF." EERSTEVOOR:".$aantalE);	*/

/*//RANGSCHIK GEVEN
$rangschik = $db->getRecords("SELECT * FROM `leerlingen` WHERE RANGSCHIK is NULL");
foreach($rangschik as $r){
	$array = array();
	$array['RANGSCHIK'] = str_replace(" ","",$r['AANSPREEK']);
	$db->update("leerlingen", $array,"ID_lln=?", $r['ID_lln']);
}*/

/*//Quotes aanpassen <br> vervangen door <br/> en lege <i></i> verwijderen en dan de entities aanpassen en 
//daarna de br(&lt;) and i(&gt;) terug omzetten zodat <br> <i> als code gelezen wordt
$dbTest = new SpoonDatabase( etc ...
$quote = $dbTest->getRecords("SELECT * FROM `tbl_quotes`");
foreach($quote as $q){
	$array = array();
	$br = str_replace("<br>", "<br/>", $q['quote']);
	$quote = str_replace("<i></i>", "", $br);
	$quote2 = htmlentities($quote);
	$quoteReplace2 = str_replace("&lt;br/&gt;", "<br/>", $quote2);
	$quoteReplace3 = str_replace("&lt;i&gt;", "<i>", $quoteReplace2);
	$array['quote'] = str_replace("&lt;/i&gt;", "</i>", $quoteReplace3);
	
	$dbTest->update("tbl_quotes",$array,"quote_id= ?",$q['quote_id']);
}*/

/*//leerlingen en leerkrachten (1 leerkracht meer VANDENDOORENDavid in tbl_leerkrachten) tbl_leerkrachten+tbl_leerlingen is 1 meer dan leerlingen
//op het einde in de tabel leerkrachten een record toevoegen met rangschiknaam GeenLeerkracht ==> schrijf geen id hiervan op komt van pas later
$resultLeerlingen = $db->getRecords("SELECT * FROM leerlingen");
$resultFMAD = $dbTest->getRecords("SELECT rangschik,ut,uuid FROM tbl_fmad");

$arrayToegevoegdePersonen = array();
$i =0;
$fmadLKR=0;
$fmadLLN =0;
foreach($resultFMAD as $f){
	if($f['ut'] == 1){
		foreach($resultLeerlingen as $personen){
			if($personen['RANGSCHIK'] == $f['rangschik']){
				$array = array();
				$array['uuid'] = $f['uuid'];
				$array['famnaam'] = $personen['FAMNAAM'];
				$array['eerstevoor'] = $personen['EERSTEVOOR'];
				$array['aanspreek'] = $personen['AANSPREEK'];
				$array['rangschik'] = $personen['RANGSCHIK'];
				$array['adres'] = $personen['ADRES'];
				$array['postcodewp'] = $personen['POSTCODEWP'];
				$array['deelgemeente'] = $personen['DEELGEMEEN'];
				$array['tel_verantw'] = $personen['TELVERANTW'];
				$array['geboortedatum'] = $personen['Geboortedatumm'];
				$array['gsm'] = $personen['GSM'];
				$array['email'] = $personen['email'];
				$array['pasfoto'] = $personen['pasfotoo'];
				$array['gewijzigd_door'] = 'VANDENDOORENDavid';
				$arrayToegevoegdePersonen[$i] = $array;
				$fmadLKR++;
				$dbTest->insert('tbl_leerkrachten',$array);
			}
		}
	}
	else{
		foreach($resultLeerlingen as $personen){
			if($personen['RANGSCHIK'] == $f['rangschik']){
				$array = array();
				$array['uuid'] = $f['uuid'];
				$array['famnaam'] = $personen['FAMNAAM'];
				$array['eerstevoor'] = $personen['EERSTEVOOR'];
				$array['aanspreek'] = $personen['AANSPREEK'];
				$array['rangschik'] = $personen['RANGSCHIK'];
				$array['adres'] = $personen['ADRES'];
				$array['postcodewp'] = $personen['POSTCODEWP'];
				$array['deelgemeente'] = $personen['DEELGEMEEN'];
				$array['tel_verantw'] = $personen['TELVERANTW'];
				$array['geboortedatum'] = $personen['Geboortedatumm'];
				$array['gsm'] = $personen['GSM'];
				$array['email'] = $personen['email'];
				$array['pasfoto'] = $personen['pasfotoo'];
				$array['fotohanden'] = $personen['fotohanden'];
				$array['gewijzigd_door'] = 'VANDENDOORENDavid';
				$arrayToegevoegdePersonen[$i] = $array;
				$fmadLLN++;
				$dbTest->insert('tbl_leerlingen',$array);
			}
		}
	}
	$i++;
}
$telLKR = 0;
$telLLN =0;
foreach($resultLeerlingen as $personen){
	$komtvoor = false;
	foreach($arrayToegevoegdePersonen as $a){
		if(($a['rangschik'] == $personen['RANGSCHIK'])){
			$komtvoor = true;
		}
	}
	if(!$komtvoor){
		if($personen['acces_level']=='leraar'){
			$gebruikersTeDoen = array();
			$gebruikersTeDoen['uuid'] = 0;
			$gebruikersTeDoen['famnaam'] = $personen['FAMNAAM'];
			$gebruikersTeDoen['eerstevoor'] = $personen['EERSTEVOOR'];
			$gebruikersTeDoen['aanspreek'] = $personen['AANSPREEK'];
			$gebruikersTeDoen['rangschik'] = $personen['RANGSCHIK'];
			$gebruikersTeDoen['adres'] = $personen['ADRES'];
			$gebruikersTeDoen['postcodewp'] = $personen['POSTCODEWP'];
			$gebruikersTeDoen['deelgemeente'] = $personen['DEELGEMEEN'];
			$gebruikersTeDoen['tel_verantw'] = $personen['TELVERANTW'];
			$gebruikersTeDoen['geboortedatum'] = $personen['Geboortedatumm'];
			$gebruikersTeDoen['gsm'] = $personen['GSM'];
			$gebruikersTeDoen['email'] = $personen['email'];
			$gebruikersTeDoen['pasfoto'] = $personen['pasfotoo'];
			$gebruikersTeDoen['gewijzigd_door'] = 'VANDENDOORENDavid';
			$telLKR++;
			$dbTest->insert('tbl_leerkrachten',$gebruikersTeDoen);			
		}
		else{
			$gebruikersTeDoen = array();
			$gebruikersTeDoen['uuid'] = 0;
			$gebruikersTeDoen['famnaam'] = $personen['FAMNAAM'];
			$gebruikersTeDoen['eerstevoor'] = $personen['EERSTEVOOR'];
			$gebruikersTeDoen['aanspreek'] = $personen['AANSPREEK'];
			$gebruikersTeDoen['rangschik'] = $personen['RANGSCHIK'];
			$gebruikersTeDoen['adres'] = $personen['ADRES'];
			$gebruikersTeDoen['postcodewp'] = $personen['POSTCODEWP'];
			$gebruikersTeDoen['deelgemeente'] = $personen['DEELGEMEEN'];
			$gebruikersTeDoen['tel_verantw'] = $personen['TELVERANTW'];
			$gebruikersTeDoen['geboortedatum'] = $personen['Geboortedatumm'];
			$gebruikersTeDoen['gsm'] = $personen['GSM'];
			$gebruikersTeDoen['email'] = $personen['email'];
			$gebruikersTeDoen['pasfoto'] = $personen['pasfotoo'];
			$gebruikersTeDoen['fotohanden'] = $personen['fotohanden'];
			$gebruikersTeDoen['gewijzigd_door'] = 'VANDENDOORENDavid';
			$telLLN++;
			$dbTest->insert('tbl_leerlingen',$gebruikersTeDoen);
		}
	}
}
print_r("leerkrachten fmad:".$fmadLKR);
print_r("<br/>leerlingen fmad:".$fmadLLN);
print_r("<br/>lkr:".$telLKR);
print_r("<br/>lln:".$telLLN);*/

//selecteren van alle leerlingen(uit tbl_leerlingen) met de alle gegevens uit leerlingen(accessl_david)
$resultLLNOud = $db->getRecords("SELECT * from leerlingen");
$resultLLNNieuw = $dbTest->getRecords("SELECT * from tbl_leerlingen");
$leerlingen = array();
$i =0;
foreach($resultLLNOud as $oud){
	foreach($resultLLNNieuw as $nieuw){
		if($oud['RANGSCHIK'] == $nieuw['rangschik']){
			$leerlingen[$i] = $oud;
			$leerlingen[$i]['leerling_id'] = $nieuw['leerling_id'];
			$i++;
			break;
		}
	}
}

/*//PE
foreach($leerlingen as $l){
	$array = array();
	$array['leerling_id'] = $l['leerling_id'];
	$array['schooljaar'] = $l['SCHOOLJAAR'];
	$array['pe01']=$l['PE01'];
	$array['pe02']=$l['PE02'];
	$array['pe03']=$l['PE03'];
	$array['pe04']=$l['PE04'];
	$array['pe05']=$l['PE05'];
	$array['pe06']=$l['PE06'];
	$array['pe07']=$l['PE07'];
	$array['pe08']=$l['PE08'];
	$array['pe09']=$l['PE09'];
	$array['pe10']=$l['PE10'];
	$array['gewijzigd_door'] = "VANDENDOORENDavid"; 
	
	$dbTest->insert("tbl_permanente_evaluatie",$array);
}*/

/*//100dagen
foreach($leerlingen as $l){
	$array = array();
	$array['leerling_id'] = $l['leerling_id'];
	$array['schooljaar'] = $l['SCHOOLJAAR'];
	$array['100dagen']=$l['100dagen'];
	$array['100dagen_commentaar']=$l['100dagencoment'];
	$array['amnesty_international_niet']=$l['AIniet'];
	$array['hd01']=$l['HD01'];
	$array['hd02']=$l['HD02'];
	$array['hd03']=$l['HD03'];
	$array['hd04']=$l['HD04'];
	$array['hd05']=$l['HD05'];
	$array['hd06']=$l['HD06'];
	$array['hd07']=$l['HD07'];
	$array['hd08']=$l['HD08'];
	$array['hd09']=$l['HD09'];
	$array['hd10']=$l['HD10'];
	$array['gewijzigd_door'] ="VANDENDOORENDavid";
	
	$dbTest->insert("tbl_100dagen",$array);
}*/

/*//Kleiwerk
foreach($leerlingen as $l){
	$array = array();
	$array['leerling_id'] = $l['leerling_id'];
	$array['kleiwerk']=$l['kleiwerk'];
	$array['kleiwerk_commentaar']=$l['kleiwerk_com'];
	$array['kleiwerk_evaluator']=$l['kleiwerk_evaluator'];
	$array['schooljaar'] = $l['SCHOOLJAAR'];
	$array['gewijzigd_door'] = "VANDENDOORENDavid";

	$dbTest->insert("tbl_kleiwerk",$array);
}*/

/*//mondex
foreach($leerlingen as $l){
	$array = array();
	$array['leerling_id'] = $l['leerling_id'];
	$array['mondeling_examen']=$l['MondEx'];
	$array['schooljaar']=$l['SCHOOLJAAR'];
	$array['gewijzigd_door']="VANDENDOORENDavid";
	
	$dbTest->insert("tbl_leerling_mondex",$array);
}*/

/*//tbl_jaarwerk_groep
$jaarwerk = $db->getRecords("SELECT * from jaarwerken");
$i=0;
foreach($jaarwerk as $j){
	$array = array();
	$array['jaarwerk_groep_id'] = $j['ID_jw'];
	$array['titel'] = $j['Titel'];
	$array['onderwerp'] = $j['Onderwerp'];
	$array['beschrijving'] = $j['Beschrijving'];
	$array['specialist'] = $j['Specialist'];
	$array['voorstelling'] = $j['Voorstelling'];
	$array['periode'] = $j['Periode'];
	$array['datum'] = $j['datum'];
	$array['bedenkingen'] = $j['Bedenkingen'];
	$array['invoerdatum'] = $j['invoerdatum'];
	$array['evaluatie_inhoud'] = $j['ev_inhoud'];
	$array['evaluatie_vorm'] = $j['ev_vorm'];
	$array['evaluatie_interview'] = $j['ev_interview'];
	$array['evaluatie_a4'] = $j['ev_A4'];
	$array['evaluatie'] = $j['evaluatie'];
	$array['gewijzigd_door'] = "VANDENDOORENDavid";
	
	$dbTest->insert("tbl_jaarwerk_groep", $array);
}*/

/*//tbl_jaarwerk_leerling
$aantal=0;
$jaarwerken = $dbTest->getRecords("SELECT jaarwerk_groep_id from tbl_jaarwerk_groep");
foreach($leerlingen as $l){
	$array = array();
	$array['leerling_id'] = $l['leerling_id'];
	if($l['JW']==0 || $l['JW']==null || $l['JW']==""){
		$array['jaarwerk_groep_id'] = 1;
	}else{
		$array['jaarwerk_groep_id'] = $l['JW'];
	}
	$array['jaarwerk_persoonlijke_inzet'] = $l['JW_persoonlijke_inzet'];
	$array['schooljaar'] = $l['SCHOOLJAAR'];
	$array['gewijzigd_door'] = "VANDENDOORENDavid";
	
	$jwBestaat = false;
	foreach($jaarwerken as $j){
		if($array['jaarwerk_groep_id']==$j['jaarwerk_groep_id']){
			$jwBestaat = true;
			break;
		}
	}
	if($jwBestaat == false){
		$array['jaarwerk_groep_id']=1;
	}
	$aantal++;
	$dbTest->insert("tbl_jaarwerk_leerling", $array);
}*/

/*//tbl_b2d_projecten
$aantal =0;
$projecten = $db->getRecords("SELECT * from projecten_b2d");
$soort = $dbTest->getRecords("SELECT * from tbl_b2d_project_soort");
foreach($projecten as $p){
	foreach($soort as $s){
		if($p['soort_project'] == $s['categorie_soort']){
			$array = array();
			$array['project_id'] = $p['ID_projecten'];
			$array['project_soort_id'] = $s['project_soort_id'];
			$array['naam'] = $p['naam_project'];
			$array['aantal_deelnemers'] = $p['aantal_deelnemers'];
			$array['straat'] = $p['straat_project'];
			$array['zip'] = $p['zip_project'];
			$array['gemeente'] = $p['gemeente_project'];
			$array['telefoon'] = $p['telefoon_project'];
			$array['fax'] = $p['fax_project'];
			$array['email'] = $p['emailproj'];
			$array['verantwoordelijke'] = $p['verantwoordelijke_project'];
			$array['omschrijving'] = $p['omschrijving_project'];
			$array['invoerdatum'] = $p['invoerdatum_proj'];
			$array['afgevoerd'] = $p['afgevoerd'];
			$array['ter_attentie_van'] = $p['ter_att_van'];
			$array['evaluatie_vraag'] = $p['EvVraag'];
			$array['gewijzigd_door'] = "VANDENDOORENDavid";
			
			$aantal++;
			$dbTest->insert("tbl_b2d_projecten", $array);
			break;
		}
	}
}
print_r($aantal);*/

/*//tbl_b2d_algemeen - 258 als id gebruiken als er geen bestaand project_id is opgegeven
//de foreach lussen foreach($bestaandeProjectIds as $be) zijn nodig om te zien of de keuze/toegewezen bestaan in de tbl_b2d_projecten
$aantalk1=0;
$aantalk1b=0;
$aantalk2=0;
$aantalk2b=0;
$aantalk3=0;
$aantalk3b=0;
$aantalt =0;
$aantalt2 =0;
$bestaandeProjectIds = $dbTest->getRecords("SELECT project_id from tbl_b2d_projecten");
foreach($leerlingen as $l){
	$array = array();
	$array['leerling_id']=$l['leerling_id'];
	//Keuze 1
	$bestaatK1=false;
	foreach($bestaandeProjectIds as $be){
		if($be['project_id']==$l['B2D_K1']){
			$bestaatK1 = true;
			break;
		}
	}
	if($l['B2D_K1'] == "" || $l['B2D_K1']==null || $l['B2D_K1']==0 || $bestaatK1 == false || $l['B2D_K1']==258){
		$array['project_keuze1']=258;
		$aantalk1++;
	}else{
		$array['project_keuze1']=$l['B2D_K1'];
		$aantalk1b++;
	}
	//keuze 2
	$bestaatK2=false;
	foreach($bestaandeProjectIds as $be){
		if($be['project_id']==$l['B2D_K2']){
			$bestaatK2 = true;
			break;
		}
	}
	if($l['B2D_K2'] == "" || $l['B2D_K2']==null || $l['B2D_K2']==0 || $bestaatK2==false || $l['B2D_K2']==258){
		$array['project_keuze2']=258;
		$aantalk2++;
	}else{
		$array['project_keuze2']=$l['B2D_K2'];
		$aantalk2b++;
	}
	//keuze 3
	$bestaatK3=false;
	foreach($bestaandeProjectIds as $be){
		if($be['project_id']==$l['B2D_K3']){
			$bestaatK3 = true;
			break;
		}
	}
	if($l['B2D_K3'] == "" || $l['B2D_K3']==null || $l['B2D_K3']==0 || $bestaatK3==false || $l['B2D_K3']==258){
		$array['project_keuze3']=258;
		$aantalk3++;
	}else{
		$array['project_keuze3']=$l['B2D_K3'];
		$aantalk3b++;
	}
	//toegewezen
	$bestaatT=false;
	foreach($bestaandeProjectIds as $be){
		if($be['project_id']==$l['B2D_Gekozen_project']){
			$bestaatT = true;
			break;
		}
	}
	if($l['B2D_Gekozen_project'] == "" || $l['B2D_Gekozen_project']==null || $l['B2D_Gekozen_project']==0 || $bestaatT == false || $l['B2D_Gekozen_project']==258){
		$array['toegewezen_project']=258;
		$aantalt++;
	}else{
		$array['toegewezen_project'] = $l['B2D_Gekozen_project'];
		$aantalt2++;
	}
	$array['aanvraag_ok']=$l['B2D_aanvraag_okee'];
	$array['handtekening_ouders']=$l['B2D_handtekening_ouders'];
	$array['bevestiging_aanwezigheid']=$l['B2D_Bevestiging_van_aanwezigheid'];
	$array['evaluatie_tekst1']=$l['B2D_Evaluatie'];
	$array['evaluatie_tekst2']=$l['Gebed'];
	$array['evaluatie_punten']=$l['B2D_Punten_evaluatie'];
	$array['evaluatie_afgegeven']=$l['B2D_evaluatie_afg'];
	$array['schooljaar']=$l['SCHOOLJAAR'];
	$array['gewijzigd_door']="VANDENDOORENDavid";
        
	$dbTest->insert("tbl_b2d_algemeen", $array);
}

print_r($aantalk1."<br/>");
print_r($aantalk1b."<br/>");
print_r($aantalk2."<br/>");
print_r($aantalk2b."<br/>");
print_r($aantalk3."<br/>");
print_r($aantalk3b."<br/>");
print_r($aantalt."<br/>");
print_r($aantalt2."<br/>");*/

/*//tbl_b2d_project_assistentie
$projecten = $db->getRecords("SELECT * from projecten_b2d");
$projectenNieuw= $dbTest->getRecords("SELECT * from tbl_b2d_projecten");
$leerkrachten = $dbTest->getRecords("SELECT * from tbl_leerkrachten");
$aantal=0;
$aantal1=0;
$aantal2=0;
foreach($projecten as $p){
	$assistZes = $p['assist_2006_2007'];
	$assistZeven = $p['assist_2007_2008'];
	$assistTien = $p['assist_2010_2011'];
	$array = array();
	$array2 = array();
	$array3 = array();
	if($assistZes!=null || $assistZes!=""){
		foreach($leerkrachten as $l){
			if(strtolower($assistZes) == strtolower($l['aanspreek'])){
				$array['leerkracht_id']= $l['leerkracht_id'];
				$projectbestaat =false;
				foreach($projectenNieuw as $nieuw){
					if($nieuw['naam']==$p['naam_project']){
						$array['project_id'] = $nieuw['project_id'];
						$projectbestaat =true;
						break;
					}
				}
				if($projectbestaat==false){
					$array['project_id'] = 258;
				}
				$array['assistentie_jaar']= 2006;
				$array['gewijzigd_door']="VANDENDOORENDavid";
				$aantal++;
				$dbTest->insert("tbl_b2d_project_assistentie", $array);
				var_dump($array);
				break;
			}
		}
	}
	if($assistZeven!=null || $assistZeven!=""){
		foreach($leerkrachten as $l){
			if(strtolower($assistZeven) == strtolower($l['aanspreek'])){
				$array2['leerkracht_id']= $l['leerkracht_id'];
				$projectbestaat =false;
				foreach($projectenNieuw as $nieuw){
					if($nieuw['naam']==$p['naam_project'] && $nieuw['email']==$p['emailproj']){
						$array2['project_id'] = $nieuw['project_id'];
						$projectbestaat =true;
						break;
					}
				}
				if($projectbestaat==false){
					$array2['project_id'] = 258;
				}
				$array2['assistentie_jaar']= 2007;
				$array2['gewijzigd_door']="VANDENDOORENDavid";
				$aantal1++;
				$dbTest->insert("tbl_b2d_project_assistentie", $array2);
				var_dump($array2);
				break;
			}
		}
	}
	if($assistTien!=null || $assistTien!=""){
		foreach($leerkrachten as $l){
			if(strtolower($assistTien) == strtolower($l['aanspreek'])){
				$array3['leerkracht_id']= $l['leerkracht_id'];
				$projectbestaat =false;
				foreach($projectenNieuw as $nieuw){
					if($nieuw['naam']==$p['naam_project'] && $nieuw['email']==$p['emailproj']){
						$array3['project_id'] = $nieuw['project_id'];
						$projectbestaat =true;
						break;
					}
				}
				if($projectbestaat==false){
					$array3['project_id'] = 258;
				}
				$array3['assistentie_jaar']= 2010;
				$array3['gewijzigd_door']="VANDENDOORENDavid";
				$aantal2++;
				$dbTest->insert("tbl_b2d_project_assistentie", $array3);
				var_dump($array3);
				break;
			}
		}
	}
}
print_r($aantal."<br/>");
print_r($aantal1."<br/>");
print_r($aantal2."<br/>");*/

/*//tbl_lokalen
manueel*/

/*//tbl_dbf_richtingen
$dbfr = $db->getRecords("SELECT * from dbfrichtingen");
$lokalen = $dbTest->getRecords("SELECT * from tbl_lokalen");
$leerkrachten = $dbTest->getRecords("SELECT * from tbl_leerkrachten");

foreach($dbfr as $d){
	$array = array();
	$leerkrachtbestaat = false;
	foreach($leerkrachten as $l){
		if(strtolower($d['assistentie']) == strtolower($l['aanspreek'])){
			$array['assistentie_leerkracht'] = $l['leerkracht_id'];
			$leerkrachtbestaat = true;
		}
	}
	if($leerkrachtbestaat == false){
		$array['assistentie_leerkracht'] = 162;
	}
	$lokaalbestaat = false;
	foreach($lokalen as $l){
		if($l['naam']==$d['lokaal']){
			$array['lokaal_id']=$l['lokaal_id'];
			$lokaalbestaat =true;
		}
	}
	if($lokaalbestaat == false){
		$array['lokaal_id'] = 13;
	}
	$array['studierichting'] = $d['Studierichting'];
	$array['gewijzigd_door'] = "VANDENDOORENDavid";
	
	print_r($array);
	$dbTest->insert("tbl_dbf_richtingen",$array);
}*/

/*//tbl_klasgroepen
$aantal = 0;
$aantal2 = 0;
foreach($leerlingen as $lln){
	if($lln['KLASGROEPEN']==null && $lln['KLASGROEPEN']==""){
		$aantal2++;
		$array = array();
		$array['leerkracht_id']= 162;
		$array['klasgroep_naam'] = "Geen klasgroep";
		$array['schooljaar']= $lln['SCHOOLJAAR'];
		$array['gewijzigd_door'] = "VANDENDOORENDavid";
		$dbTest->insert("tbl_klasgroepen",$array);
	}else{
		$array = array();
		$aantal++;
		if($lln['KLASGROEPEN'] == "6 LAWI bc"){
			$array['leerkracht_id']= 160;
		}else{
			$array['leerkracht_id']= 162;
		}
		$array['klasgroep_naam'] = $lln['KLASGROEPEN'];
		$array['schooljaar']= $lln['SCHOOLJAAR'];
		$array['gewijzigd_door'] = "VANDENDOORENDavid";
		$dbTest->insert("tbl_klasgroepen",$array);
	}
}
//enkel de verschillende klasgroepen moeten erin staan dus niet voor elke leerling afzonderlijk maar enkel de verschillende klasgroepen
$klasgroepen = $dbTest->getRecords("SELECT * FROM tbl_klasgroepen GROUP BY klasgroep_naam");
$dbTest->delete("tbl_klasgroepen","klasgroep_id!=?",12000);		
foreach($klasgroepen as $klasg){
	$array = array();
	$array['leerkracht_id']= $klasg['leerkracht_id'];
	$array['klasgroep_naam'] = $klasg['klasgroep_naam'];
	$array['schooljaar']= $klasg['schooljaar'];
	$array['gewijzigd_door'] = "VANDENDOORENDavid";
	$dbTest->insert("tbl_klasgroepen",$array);
}*/

/*//tbl_klas
$aantal=0;
foreach($leerlingen as $l){
	$array = array();
	$array['leerling_id'] = $l['leerling_id'];
	if($l['KLAS']=="" || $l['KLAS']==null || $l['KLAS']=="5" || $l['KLAS']=="6" || $l['KLAS']=="7" || $l['KLAS']=="(geen oud lln)" || $l['KLAS']=="vrij student"){
		$array['klasnaam']= "geen klas";
		$aantal++;
	}else{
		$array['klasnaam'] = $l['KLAS'];
		
	}	
	$array['schooljaar']=$l['SCHOOLJAAR'];
	$array['gewijzigd_door']="VANDENDOORENDavid";
	var_dump($array);
	$dbTest->insert("tbl_klas",$array);
}*/

/*//tbl_klas_klasgroep
//tbl_klasgroep: klas 6LAMT toevoegen(jaar 2009 leerkracht 162)
//tbl_klasgroep: klas 6 MTWI a toevoegen
//tbl_klasgroep: 6 WEWI d toevoegen (2003 leerkracht162)
$klas = $dbTest->getRecords("SELECT * from tbl_klas");
$klasgroepen = $dbTest->getRecords("SELECT * from tbl_klasgroepen");
$aantalk=0;
$aantalk1=0;
$aantalk2=0;
$aantalk3=0;
foreach($klas as $k){
	//if($k['schooljaar']==2010){
		foreach($leerlingen as $l){
			if($l['leerling_id']==$k['leerling_id']){
				$klasgroepGevonden = false;
				foreach($klasgroepen as $kg){
					if($kg['klasgroep_naam'] == $l['KLASGROEPEN'] && $k['schooljaar']==$kg['schooljaar']){ //&& $k['schooljaar']==$kg['schooljaar']
						$array = array();
						$array['klas_id'] = $k['klas_id'];
						$array['klasgroep_id'] = $kg['klasgroep_id'];
                                                $array['gewijzigd_door'] = "VANDENDOORENDavid";
						$dbTest->insert("tbl_klas_klasgroep",$array);
						$klasgroepGevonden=true;
						$aantalk1++;
						break;
					}
				}
				if($klasgroepGevonden==false){
					$array = array();
					$array['klas_id'] = $k['klas_id'];
					$array['klasgroep_id'] = 2665;
                                        $array['gewijzigd_door'] = "VANDENDOORENDavid";
					$dbTest->insert("tbl_klas_klasgroep",$array);
					$aantalk3++;
				}
			}
		}
	//}
	else{ //volledige else in commentaar
		$klasgevonden =false;
		foreach($klasgroepen as $kg){
			$pos = strpos(strtolower($kg['klasgroep_naam']),strtolower($k['klasnaam']));
	        if($pos !== false && $kg['schooljaar']==$k['schooljaar']) {
				$array = array();
				$array['klas_id'] = $k['klas_id'];
				$array['klasgroep_id'] = $kg['klasgroep_id'];
                                $array['gewijzigd_door'] = "VANDENDOORENDavid";
				$dbTest->insert("tbl_klas_klasgroep",$array);
				$klasgevonden=true;
				$aantalk++;
	            break;
	        }
		}
		if($klasgevonden==false){
			$array = array();
			$array['klas_id'] = $k['klas_id'];
			$array['klasgroep_id'] = 2665; //geen klasgroep
                        $array['gewijzigd_door'] = "VANDENDOORENDavid";
			$dbTest->insert("tbl_klas_klasgroep",$array);
			$aantalk2++;
		}
	}
}
print_r($aantalk1." ");
print_r($aantalk3." ");
print_r($aantalk." ");
print_r($aantalk2);*/

/*//tbl_oudleerlingen
$aantal=0;
$aantal2=0;
$totaal=0;
$dbfRichtingen = $dbTest->getRecords("SELECT dbf_richting_id from tbl_dbf_richtingen");
foreach($leerlingen as $l){
	if($l['SCHOOLJAAR']!="2010"){
		$totaal++;
		$array = array();
		$array['leerling_id'] = $l['leerling_id'];
		$richtingBestaat = false;
		foreach($dbfRichtingen as $d){
			if($d['dbf_richting_id']==$l['DB_I_richting']){
				$array['dbf_richting_id']=$l['DB_I_richting'];
				$aantal++;
				$richtingBestaat = true;
				break;
			}
		}
		if($richtingBestaat == false){
			$aantal2++;
			$array['dbf_richting_id']=20; //andere richtingen die niet in die niet onder de paraplutermen van tbl_dbf_richtingen vallen
		}
		$array['richting_details']=$l['DBF_richtingdetail'];
		$array['school']=$l['DBF_School'];
		$array['gewijzigd_door']="VANDENDOORENDavid";
		$dbTest->insert("tbl_oudleerlingen",$array);
	}
}

print_r($aantal." ".$aantal2." ".$totaal); //totaal is 2602 - 227(leerlingen 2010)= 2375*/

/*//tbl_dbf_spreker
$aantal2=0;
$oudleerlingen = array();
$k=0;
foreach($leerlingen as $l){
	if($l['SCHOOLJAAR']!="2010"){
		$oudleerlingen[$k] = $l;
		$k++;
	}
}
foreach($oudleerlingen as $o){
	if($o['DBF_spreker']!=null && $o['DBF_spreker']!=""){
		$split = split('20',$o['DBF_spreker']);
		foreach($split as $s){
			$array = array();
			$idOudLLN =$dbTest->getRecord("SELECT oudleerling_id from tbl_oudleerlingen where leerling_id=?",$o['leerling_id']);
			$array['oudleerling_id'] = $idOudLLN['oudleerling_id'];
			if(strpos($s,"ja")!==false){
				$aantal2++;
				if(strpos($s,"06")!==false){
					$array['jaar']=2006;
				}else if(strpos($s,"07")!==false){
					$array['jaar']=2007;
				}else if(strpos($s,"08")!==false){
					$array['jaar']=2008;
				}else if(strpos($s,"09")!==false){
					$array['jaar']=2009;
				}else if(strpos($s,"10")!==false){
					$array['jaar']=2010;
				}else if(strpos($s,"11")!==false){
					$array['jaar']=2011;
				}
				$array['actief']="ja";
				$array['desiderata'] = $o['DBF_Desiderata'];
				$array['gewijzigd_door'] = "VANDENDOORENDavid";
				$dbTest->insert("tbl_dbf_spreker",$array);				
			}else if(strpos($s,"nee")!==false){
				$aantal2++;
				if(strpos($s,"06")!==false){
					$array['jaar']=2006;
				}else if(strpos($s,"07")!==false){
					$array['jaar']=2007;
				}else if(strpos($s,"08")!==false){
					$array['jaar']=2008;
				}else if(strpos($s,"09")!==false){
					$array['jaar']=2009;
				}else if(strpos($s,"10")!==false){
					$array['jaar']=2010;
				}else if(strpos($s,"11")!==false){
					$array['jaar']=2011;
				}
				$array['actief']="nee";
				$array['desiderata'] = $o['DBF_Desiderata'];
				$array['gewijzigd_door'] = "VANDENDOORENDavid";
				$dbTest->insert("tbl_dbf_spreker",$array);					
			}else if($s!=""){
				$aantal2++;
				if(strpos($s,"06")!==false){
					$array['jaar']=2006;
				}else if(strpos($s,"07")!==false){
					$array['jaar']=2007;
				}else if(strpos($s,"08")!==false){
					$array['jaar']=2008;
				}else if(strpos($s,"09")!==false){
					$array['jaar']=2009;
				}else if(strpos($s,"10")!==false){
					$array['jaar']=2010;
				}else if(strpos($s,"11")!==false){
					$array['jaar']=2011;
				}
				$array['actief']="misschien";
				$array['desiderata'] = $o['DBF_Desiderata'];
				$array['gewijzigd_door'] = "VANDENDOORENDavid";
				$dbTest->insert("tbl_dbf_spreker",$array);	
			}
		}
	}
}
print_r($aantal2);*/

/*//tbl_dbf
$oudleerlingen = array();
$k=0;
foreach($leerlingen as $l){
	if($l['SCHOOLJAAR']!="2010"){
		$oudleerlingen[$k] = $l;
		$k++;
	}
}
foreach($leerlingen as $l){
	$array = array();
	$array['leerling_id'] = $l['leerling_id'];
	if($l['DBFilm1']==null || $l['DBFilm1']>20){
		$array['dbf_richting_id'] = 20;
	}else{
		$array['dbf_richting_id'] = $l['DBFilm1'];
	}
	if($l['DBFilm2']==null){
		$array['dbf_spreker_id']=453;
	}else{
		$bestaat = false;
		foreach($oudleerlingen as $o){
			if($o['AANSPREEK']==$l['DBFilm2'] || $o['RANGSCHIK']==$l['DBFilm2']){
				$oudleerlingId = $dbTest->getRecord("SELECT oudleerling_id from tbl_oudleerlingen where leerling_id=?",$o['leerling_id']);
				$dbfRichtingId = $dbTest->getRecord("SELECT dbf_spreker_id from tbl_dbf_spreker where oudleerling_id=?",$oudleerlingId['oudleerling_id']);
				$array['dbf_spreker_id']=$dbfRichtingId['dbf_spreker_id'];
				$bestaat =true;
				break;
			}
		}
		if($bestaat == false){
			$array['dbf_spreker_id']=453;
		}
	}
	if($l['DBFilm3']==null){
		$array['dbfilm3'] = 20;
	}else{
		$array['dbfilm3'] = $l['DBFilm3'];
	}
	if($l['DBFilm4']==null){
		$array['dbfilm4'] = 20;
	}else{
		$array['dbfilm4'] = $l['DBFilm4'];
	}
	if($l['DBFilm5']==null){
		$array['dbfilm5'] = 20;
	}else{
		$array['dbfilm5'] = $l['DBFilm5'];
	}
	$array['schooljaar'] = $l['SCHOOLJAAR'];
	$array['gewijzigd_door'] = "VANDENDOORENDavid";
	$dbTest->insert("tbl_dbf",$array);
}*/

/*//tbl_dbf_spreker ==> schooljaar -1
$dbfSpreker = $dbTest->getRecords("select * from tbl_dbf_spreker");
foreach($dbfSpreker as $d){
    $array = array();
    $array['jaar'] = $d['jaar']-1;
    $dbTest->update("tbl_dbf_spreker", $array, "dbf_spreker_id =?", $d['dbf_spreker_id']);
}*/