<?php
    require_once "spoon.php"; //<== classes/spoon.php
    require_once "spoon/database/database.php";//<== classes/spoon/database/database.php
    require_once "staticFunctions.class.php";
	require_once '/home/godfried/godsdienst.inc';

/*
 * In deze klasse vindt u de functies die databankbewerkingen uitvoeren voor de beheerders
 *
 * @author David Van Den Dooren
 * @version 27/05/2011
 */
class bizADMQueryFunctions {
    protected  $db;

    /*
     * De constructor voor de bizADMQueryFunctions klasse
     */
    function  __construct() {
        # TODO: dit moet hier weg
		$this->db = new SpoonDatabase('mysql', DBZ_DBSERVER, DBZ_DBUSER, DBZ_DBPASS, DBZ_DBDB);
    }

    /*
     * Het verkrijgen van de databank object connectie
     *
     * @return object
     */
    function getDataConnect (){
        return $this->db;
    }

    /*
     * Het opvragen van een random quote voor admin's
     *
     * @return string
     */
    public function getQuote(){
        $quotes = $this->db->getRecords("SELECT * FROM tbl_quotes");

        return str_replace("-  -","",$quotes[rand(0,count($quotes)-1)]['quote']);
    }

    /*
     * Het verkrijgen van de quote die bij de gevraagde quote_id past
     *
     * @return array
     * @param int $quote_id     De quote waarvan je de info wil
     */
    public function getQuoteTekstById($quote_id){
        return $this->db->getRecord("SELECT * FROM tbl_quotes WHERE quote_id=?", $quote_id);
    }

    /*
     * Het weergeven van de informatie over de ingelogde gebruiker
     *
     * @return array
     * @param int $admin_id  De id van de ingelogde gebruiker
     */
    public function getAdminInfo($admin_id){
        $admin = $this->db->getRecord("SELECT * FROM tbl_leerkrachten WHERE leerkracht_id=?",$admin_id);

        return $admin;
    }

    /*
     * Het opvragen van de namen + id van de leerkrachten
     *
     * @return array
     */
    public function getLeerkrachten(){
        $leerkrachten = $this->db->getRecords("SELECT leerkracht_id, aanspreek
                                                FROM tbl_leerkrachten
                                                WHERE 1
                                                ORDER BY aanspreek");
        $dropbox = array();
        $dropbox['162'] = 'geen leerkracht';
        foreach($leerkrachten as $l){
            $dropbox[$l['leerkracht_id']] = $l['aanspreek'];
        }
        
        return $dropbox;
    }

    /*
     * Het verkrijgen van de klasgroepInformatie
     *
     * @return array
     */
    public function getKlasgroepById($klasgroep_id){
        return $this->db->getRecord("SELECT * FROM tbl_klasgroepen WHERE klasgroep_id =? LIMIT 1", $klasgroep_id);
    }

    /*
     * Het toevoegen van de nieuwe leerlingen en leerkrachten.
     * Bij leerlingen wordt er automatisch een record aangemaakt voor 100dagen,kleiwerk,mondex en permanente evaluatie
     * De leerlingen krijgen ook automatisch de juiste klas en klasgroep toegewezen
     *
     * @param string $beheerder    Zodat je weet wie de laatste wijziging heeft gedaan = admin['rangschik']
     */
    public function toevoegenLeerlingenNieuwSchooljaar($beheerder){
        $fmad = $this->db->getRecords("SELECT * FROM tbl_fmad");
        $leerlingen = $this->db->getRecords("SELECT uuid, leerling_id from tbl_leerlingen");
        $leerkrachten = $this->db->getRecords("SELECT uuid from tbl_leerkrachten");
        $klasgroepen = $this->db->getRecords("SELECT * FROM tbl_klasgroepen WHERE schooljaar =?", staticFunctions::createSchooljaar());

        foreach($fmad as $f){
            $leerling_id = 0;
            $bestaat = false;
            $geenKlasWelLeerling = false;
            if($f['ut']==2){ //2=leerlingen
                if(!empty($leerlingen)){
                    if($f['uuid'] == 2011032216150093){//2011032216150093= testgodsdienst
                        $bestaat = true;
                    }else{
                        foreach($leerlingen as $lln){
                            if($f['uuid']==$lln['uuid']){ 
                                $klassen = $this->db->getRecords("SELECT schooljaar FROM tbl_klas WHERE leerling_id=?", $lln['leerling_id']);
                                if(!empty($klassen)){
                                    foreach($klassen as $kl){
                                        if(staticFunctions::createSchooljaar() == $kl['schooljaar']){
                                            $bestaat = true;
                                        }
                                    }
                                }
                                else{
                                    $geenKlasWelLeerling = true;
                                    $leerling_id = $lln['leerling_id'];
                                }
                            }
                        }
                    }
                }
                if($bestaat == false){
                    if($geenKlasWelLeerling != true){
                        $arrayLLN = array();
                        $arrayLLN['uuid'] = $f['uuid'];
                        $arrayLLN['rangschik'] = $f['rangschik'];
                        $arrayLLN['famnaam'] = $f['ln'];
                        $arrayLLN['eerstevoor'] = $f['fn'];
                        $arrayLLN['aanspreek'] = $f['ln']." ".$f['fn'];
                        $arrayLLN['gewijzigd_door'] = $beheerder;
                        $leerling_id = $this->db->insert("tbl_leerlingen", $arrayLLN);
                    }

                    $arrayKlas = array();
                    $arrayKlas['leerling_id']= $leerling_id;
                    $arrayKlas['klasnaam'] = $f['klass'];
                    $arrayKlas['schooljaar'] = staticFunctions::createSchooljaar();
                    $arrayKlas['gewijzigd_door'] = $beheerder;
                    $klas_id = $this->db->insert("tbl_klas", $arrayKlas);

                    $klasgroepGevonden = false;
                    foreach($klasgroepen as $kg){
                        $arrayKlasKlasgroep = array();
                        $posKlass ="";
                        $posKlas ="";
                        if($f['klass']!="" && $f['klas']!==""){
                            //Het jaar en de spatie daarop volgende van de klas moeten weg om te zoeken naar een klasgroep => 6 ECMT a = ECMT a
                            $posKlass = strpos(strtolower($kg['klasgroep_naam']),strtolower(substr($f['klass'],2)));
                            //Het jaar van de klas moet weg om te zoeken naar een klasgroep dus 6ECMTa = ECMTa
                            $posKlas = strpos(strtolower($kg['klasgroep_naam']),strtolower(substr($f['klas'],1)));
                        }
                        if(($posKlass !== false && $posKlass !== "") || ($posKlas !== false && $posKlass !== "")){
                            $arrayKlasKlasgroep['klas_id'] = $klas_id;
                            $arrayKlasKlasgroep['klasgroep_id'] = $kg['klasgroep_id'];
                            $arrayKlasKlasgroep['gewijzigd_door'] = $beheerder;
                            $this->db->insert("tbl_klas_klasgroep", $arrayKlasKlasgroep);
                            $klasgroepGevonden = true;
                            break;
                        }
                    }
                    if($klasgroepGevonden == false){
                        $arrayKlasKlasgroep['klas_id'] = $klas_id;
                        $arrayKlasKlasgroep['klasgroep_id'] = 2665; //dummy= geen klasgroep
                        $arrayKlasKlasgroep['gewijzigd_door'] = $beheerder;
                        $this->db->insert("tbl_klas_klasgroep", $arrayKlasKlasgroep);
                    }
                    
                    $arrayHonderdPermaKleiwMondex = array();
                    $arrayHonderdPermaKleiwMondex['leerling_id'] = $leerling_id;
                    $arrayHonderdPermaKleiwMondex['schooljaar'] = staticFunctions::createSchooljaar();
                    $arrayHonderdPermaKleiwMondex['gewijzigd_door'] = $beheerder;
                    $this->db->insert("tbl_100dagen", $arrayHonderdPermaKleiwMondex);
                    $this->db->insert("tbl_kleiwerk", $arrayHonderdPermaKleiwMondex);
                    $this->db->insert("tbl_permanente_evaluatie", $arrayHonderdPermaKleiwMondex);
                    $this->db->insert("tbl_leerling_mondex", $arrayHonderdPermaKleiwMondex);
                }
            }
            else if($f['ut']==1){ //1=leerkracht
                if(!empty($leerkrachten)){
                    foreach($leerkrachten as $lkr){
                        if($f['uuid']==$lkr['uuid']){
                            $bestaat = true;
                        }
                    }
                }
                if($bestaat == false){
                    $arrayLKR = array();
                    $arrayLKR['uuid'] = $f['uuid'];
                    $arrayLKR['rangschik'] = $f['rangschik'];
                    $arrayLKR['famnaam'] = $f['ln'];
                    $arrayLKR['eerstevoor'] = $f['fn'];
                    $arrayLKR['aanspreek'] = $f['ln']." ".$f['fn'];
                    $arrayLKR['admin'] = 0;
                    $this->db->insert("tbl_leerkrachten", $arrayLKR);
                }
            }
        }
    }
    
    /*
     * Het verkrijgen van de informatie over een leerling d.m.v. de leerling_id
     * 
     * @return array
     * @param int $leerling_id  De id van de leerling waarvan je de info wil
     */
    public function getLeerlingById($leerling_id){
        $leerling = $this->db->getRecord("SELECT * FROM tbl_leerlingen WHERE leerling_id=?",$leerling_id);

        return $leerling;
    }

    /*
     * Het verkrijgen van de informatie over een leerkracht d.m.v. de leerkracht_id
     *
     * @return array
     * @param int $leerkracht_id  De id van de leerkracht waarvan je de info wil
     */
    public function getLeerkrachtById($leerkracht_id){
        $leerkracht = $this->db->getRecord("SELECT * FROM tbl_leerkrachten WHERE leerkracht_id=?",$leerkracht_id);

        return $leerkracht;
    }

    /*
     * Het verkrijgen van alle DBF richtingen en weergeven als een array die door de Spoon dropbox te gebruiken is
     *
     * @return array
     */
    public function getDBFRichtingen(){
        $richtingen = $this->db->getRecords("SELECT * FROM tbl_dbf_richtingen WHERE 1 ORDER BY studierichting");

        $dropbox = array();
        $dropbox['20'] = '...';
        foreach($richtingen as $r){
            $dropbox[$r['dbf_richting_id']] = $r['studierichting'];
        }

        return $dropbox;
    }

    /*
     * Het verkrijgen van alle dbf sprekers en weergeven als een array die door de Spoon dropbox te gebruiken is
     *
     * @return array
     */
    public function getDBFSprekers(){
        $sprekers = $this->db->getRecords("SELECT l.aanspreek, s.dbf_spreker_id
                                            FROM tbl_dbf_spreker AS s
                                            INNER JOIN tbl_oudleerlingen AS o
                                            ON o.oudleerling_id = s.oudleerling_id
                                            INNER JOIN tbl_leerlingen AS l
                                            ON l.leerling_id = o.leerling_id
                                            WHERE s.jaar = ?
                                            AND (s.actief = 'ja' OR s.actief = 'misschien')
                                            ORDER BY l.aanspreek", staticFunctions::createSchooljaar());
        $dropbox = array();
        $dropbox['453'] = '...'; 
        foreach($sprekers as $s){
            $dropbox[$s['dbf_spreker_id']] = $s['aanspreek'];
        }

        return $dropbox;
    }

    /*
     * Het verkrijgen van de D0n Bosco feesten informatie d.m.v. een dbf_id
     *
     * @return array
     * @param int $dbf_id   De Don Bosco Feest informatie die opgezocht moet worden
     */
    public function getDBFInfoById($dbf_id){
        return $this->db->getRecord("SELECT tbl_leerlingen.aanspreek, tbl_dbf.*
                                        FROM tbl_dbf
                                        INNER JOIN tbl_leerlingen
                                        ON tbl_dbf.leerling_id = tbl_leerlingen.leerling_id
                                        WHERE dbf_id = ?", $dbf_id);
    }

    /*
     * Het verkrijgen van de bezinnings2daagse informatie d.m.v. een b2d_algemeen_id
     *
     * @return array
     * @param int $b2d_algemeen_id      De id van de bezinnings2daagse algemeen
     */
    public function getB2DInfoById($b2d_algemeen_id){
        return $this->db->getRecord('SELECT tbl_b2d_algemeen.*, tbl_leerlingen.aanspreek
                                        FROM tbl_b2d_algemeen
                                        INNER JOIN tbl_leerlingen
                                        ON tbl_leerlingen.leerling_id = tbl_b2d_algemeen.leerling_id
                                        WHERE tbl_b2d_algemeen.b2d_algemeen_id = ?', $b2d_algemeen_id);
    }

    /*
     * Het verkrijgen van alle b2d projecten en weergeven als een array die door de Spoon dropbox te gebruiken is
     *
     * @return array
     */
    public function getB2DProjecten(){
        $projecten = $this->db->getRecords('SELECT p.project_id, p.naam, s.categorie_soort
                                            FROM tbl_b2d_projecten AS p
                                            INNER JOIN tbl_b2d_project_soort AS s
                                            ON s.project_soort_id = p.project_soort_id
                                            ORDER BY p.naam');
        $dropbox = array();
        foreach($projecten as $p){
            $dropbox[$p['project_id']] = $p['naam']." ==> ".$p['categorie_soort'];
        }

        return $dropbox;
    }

    /*
     * Het verkrijgen van de jaarwerk van een leerling d.m.v. een jaarwerk_leerling_id
     *
     * @return array
     * @param int $jaarwerk_leerling_id     De id van het jaarwerk(lln) dat opgevraagd moet worden
     */
    public function getJaarwerkLeerlingInfoById($jaarwerk_leerling_id){
        return $this->db->getRecord("SELECT j.*, l.aanspreek
                                        FROM tbl_jaarwerk_leerling AS j
                                        INNER JOIN tbl_leerlingen AS l
                                        ON l.leerling_id = j.leerling_id
                                        WHERE j.jaarwerk_leerling_id = ?", $jaarwerk_leerling_id);
    }

    /*
     * Het verkrijgen van de jaarwerk van een groep d.m.v. een jaarwerk_groep_id
     *
     * @return array
     * @param int $jaarwerk_groep_id     De id van het jaarwerk(groep) dat opgevraagd moet worden
     */
    public function getJaarwerkGroepInfoById($jaarwerk_groep_id){
        return $this->db->getRecords("SELECT g.*, l.aanspreek
                                        FROM tbl_jaarwerk_groep AS g
                                        INNER JOIN tbl_jaarwerk_leerling AS j
                                        ON j.jaarwerk_groep_id = g.jaarwerk_groep_id
                                        INNER JOIN tbl_leerlingen AS l
                                        ON l.leerling_id = j.leerling_id
                                        WHERE g.jaarwerk_groep_id = ?", $jaarwerk_groep_id);
    }

    /*
     * Het verkrijgen van de Project soort d.m.v. een project_soort_id
     *
     * @return array
     * @param int $project_soort_id     De id van het project soort
     */
    public function getB2DProjectSoortById($project_soort_id){
        return $this->db->getRecord("SELECT * FROM tbl_b2d_project_soort WHERE project_soort_id = ?", $project_soort_id);
    }

    /*
     * Het verkrijgen van de informatie over een lokaal a.d.h.v. een lokaal_id
     *
     * @return array
     * @param int $lokaal_id    De id van het lokaal waar je info over wil
     */
    public function getLokaalById($lokaal_id){
        return $this->db->getRecord("SELECT * FROM tbl_lokalen WHERE lokaal_id = ?", $lokaal_id);
    }

    /*
     * Het verkrijgen van de informatie over een permanente evaluatie a.d.h.v. een permanente evaluatie id
     *
     * @return array
     * @param int $pe_id     De id van de permanente evaluatie
     */
    public function getPermanenteEvaluatieById($pe_id){
        return $this->db->getRecord("SELECT p.*,l.aanspreek
                                    FROM tbl_permanente_evaluatie AS p
                                    INNER JOIN tbl_leerlingen AS l
                                    ON l.leerling_id = p.leerling_id
                                    WHERE p.permanente_evaluatie_id = ?", $pe_id);
    }

    /*
     * Het verkrijgen van de informatie over honderd dagen a.d.h.v. een 100 dagen id
     * 
     * @return array
     * @param int $honderdDagen_id    De id van de 100dagen
     */
    public function getHonderdDagenById($honderdDagen_id){
        return $this->db->getRecord("SELECT h.*, l.aanspreek
                                        FROM tbl_100dagen AS h
                                        INNER JOIN tbl_leerlingen AS l
                                        ON l.leerling_id = h.leerling_id
                                        WHERE h.100dagen_id = ?", $honderdDagen_id);
    }

    /*
     * Het verkrijgen van de informatie over het kleiwerk a.d.h.v. een kleiwerk id
     *
     * @return array
     * @param int $kleiwerk_id   De id van het kleiwerk
     */
    public function getKleiwerkById($kleiwerk_id){
        return $this->db->getRecord("SELECT k.*, l.aanspreek
                                        FROM tbl_kleiwerk AS k
                                        INNER JOIN tbl_leerlingen AS l
                                        ON l.leerling_id = k.leerling_id
                                        WHERE k.kleiwerk_id =?", $kleiwerk_id);
    }

    /*
     * Het verkrijgen van de informatie over het mondeling examen a.d.h.v. een mondeling_examen_id
     *
     * @return array
     * @param int $mondeling_examen_id   De id van het mondeling examen
     */
    public function getMondelingExamenById($mondeling_examen_id){
        return $this->db->getRecord("SELECT m.*, l.aanspreek
                                        FROM tbl_leerling_mondex AS m
                                        INNER JOIN tbl_leerlingen AS l
                                        ON l.leerling_id = m.leerling_id
                                        WHERE m.leerling_mondex_id = ?", $mondeling_examen_id);
    }

    /*
     * Het verkrijgen van de informatie over het dbf richtingen a.d.h.v. een dbf_richting_id
     *
     * @return array
     * @param int $dbf_richting_id  De id van de dbf richting
     */
    public function getDBFRichtingById($dbf_richting_id){
        return $this->db->getRecord("SELECT r.*, le.aanspreek, lo.naam
                                    FROM tbl_dbf_richtingen AS r
                                    INNER JOIN tbl_leerkrachten AS le
                                    ON le.leerkracht_id = r.assistentie_leerkracht
                                    INNER JOIN tbl_lokalen AS lo
                                    ON lo.lokaal_id = r.lokaal_id
                                    WHERE r.dbf_richting_id = ?",$dbf_richting_id);
    }

    /*
     * Het verkrijgen van alle lokalen en weergeven als een array die door de Spoon dropbox te gebruiken is
     *
     * @return array
     */
    public function getLokalen(){
        $lokalen =  $this->db->getRecords("SELECT * FROM tbl_lokalen ORDER BY naam");

        $dropbox = array();
        $dropbox['13'] = "Geen lokaal";
        foreach($lokalen as $l){
            $dropbox[$l['lokaal_id']] = $l['naam'];
        }

        return $dropbox;
    }

    /*
     * Het verkrijgen van de informatie over de dbf spreker a.d.h.v. een dbf_spreker_id
     *
     * @return array
     * @param int $dbf_spreker_id   De id van de dbf spreker
     */
    public function getDBFSprekerById($dbf_spreker_id){
        return $this->db->getRecord("SELECT s.*, l.aanspreek
                                        FROM tbl_dbf_spreker AS s
                                        INNER JOIN tbl_oudleerlingen AS o
                                        ON o.oudleerling_id = s.oudleerling_id
                                        INNER JOIN tbl_leerlingen AS l
                                        ON l.leerling_id = o.leerling_id
                                        WHERE s.dbf_spreker_id =?", $dbf_spreker_id);
    }

    /*
     * Het verkrijgen van alle oudleerlingen met hun naam en deze weergeven als
     * een array die door de Spoon dropbox te gebruiken is
     *
     * @return array
     */
    public function getOudleerlingenNaam(){
        $oudleerlingen = $this->db->getRecords("SELECT l.aanspreek, o.*
                                                FROM tbl_oudleerlingen AS o
                                                INNER JOIN tbl_leerlingen AS l
                                                ON l.leerling_id = o.leerling_id
                                                ORDER BY l.aanspreek");
        $dropbox = array();
        $dropbox[''] = '...';
        foreach($oudleerlingen as $o){
            $dropbox[$o['oudleerling_id']] = $o['aanspreek'];
        }

        return $dropbox;
    }

    /*
     * Het verkrijgen van de informatie over de oudleerlingen a.d.h.v. een oudleerling_id
     *
     * @return array
     * @param int $oudleerling_id   De id van de oudleerling
     */
    public function getOudleerlingenById($oudleerling_id){
        return $this->db->getRecord("SELECT o.*, l.aanspreek
                                    FROM tbl_oudleerlingen AS o
                                    INNER JOIN tbl_leerlingen AS l
                                    ON l.leerling_id = o.leerling_id
                                    WHERE o.oudleerling_id =?", $oudleerling_id);
    }

    /*
     * Het verkrijgen van alle leerlingen die nog geen oudleerling zijn deze weergeven als
     * een array die door de Spoon dropbox te gebruiken is
     *
     * @return array
     */
    public function getLeerlingenThatAreNotOudleerling(){
        $oudleerlingen = $this->db->getRecords("SELECT * FROM tbl_oudleerlingen");
        $leerlingen = $this->db->getRecords("SELECT * FROM tbl_leerlingen ORDER BY aanspreek");

        $arrayDroplist = array();
        foreach($leerlingen as $l){
            $bestaat = false;
            foreach($oudleerlingen as $o){
                if($l['leerling_id'] == $o['leerling_id']){
                    $bestaat = true;
                    break;
                }
            }
            if($bestaat == false){
                array_push($arrayDroplist, $l);
            }
        }
        
        $dropbox = array();
        $dropbox[''] = '...';
        foreach($arrayDroplist as $a){
            $dropbox[$a['leerling_id']] = $a['aanspreek'];
        }

        return $dropbox;
    }

    /*
     * Het verkrijgen van de assistentie informatie a.d.h.v. een assistentie_id
     *
     * @return array
     * @param int $assistentie_id   De id van de b2d_project_assistentie
     */
    public function getB2DAssistentiebyId($assitentie_id){
        return $this->db->getRecord("SELECT a.*, p.naam, l.aanspreek
                                        FROM tbl_b2d_project_assistentie AS a
                                        INNER JOIN tbl_leerkrachten AS l
                                        ON l.leerkracht_id = a.leerkracht_id
                                        INNER JOIN tbl_b2d_projecten AS p
                                        ON p.project_id = a.project_id
                                        WHERE a.assistentie_id =?", $assitentie_id);
    }

    /*
     * Het verkrijgen van de project informatie a.d.h.v. een project_id
     *
     * @return array
     * @param int $project_id   De id van het project
     */
    public function getB2DProjectById($project_id){
        return $this->db->getRecord("SELECT p.*, s.categorie_soort
                                        FROM tbl_b2d_projecten AS p
                                        INNER JOIN tbl_b2d_project_soort AS s
                                        ON s.project_soort_id =p.project_soort_id
                                        WHERE p.project_id = ?", $project_id);
    }

    /*
     * Het verkrijgen van alle B2D soorten projecten met hun naam en deze weergeven als
     * een array die door de Spoon dropbox te gebruiken is
     *
     * @return array
     */
    public function getB2DProjectSoorten(){
        $soorten = $this->db->getRecords("SELECT * FROM tbl_b2d_project_soort
                                            ORDER BY categorie_soort");

        $dropbox = array();
        $dropbox[''] = '...';
        foreach($soorten as $s){
            $dropbox[$s['project_soort_id']] = $s['categorie_soort'];
        }

        return $dropbox;
    }

    /*
     * Het verkrijgen van de klas informatie a.d.h.v. een klas_id
     *
     * @return array
     * @param int $klas_id   De id van het klas
     */
    public function getKlasInfoById($klas_id){
        return $this->db->getRecord("SELECT k.*, l.aanspreek
                                        FROM tbl_klas AS k
                                        INNER JOIN tbl_leerlingen AS l
                                        ON l.leerling_id = k.leerling_id
                                        WHERE klas_id = ?", $klas_id);
    }

    /*
     * Het verkrijgen van alle klassen van het huidige schooljaar met hun naam en deze weergeven als
     * een array die door de Spoon dropbox te gebruiken is
     *
     * @return array
     */
    public function getKlassenCurrentSchooljaar(){
        $klassen = $this->db->getRecords("SELECT * FROM tbl_klas 
                                            WHERE schooljaar = ?
                                            AND klasnaam != 'geen klas'
                                            GROUP BY klasnaam
                                            ORDER BY klasnaam", staticFunctions::createSchooljaar());

        $dropbox = array();
        foreach($klassen as $k){
            $dropbox[$k['klasnaam']] = $k['klasnaam'];
        }

        return $dropbox;
    }

    /*
     * Het verkrijgen van de klas - klasgroep informatie a.d.h.v. een klas_klasgroep_id
     *
     * @return array
     * @param int $klas_klasgroep_id   De id van het klas_klasgroep
     */
    public function getKlasKlasgroepById($klas_klasgroep_id){
        return $this->db->getRecord("SELECT tbl_klas_klasgroep.*,tbl_klas.klasnaam, tbl_klas.schooljaar,tbl_klasgroepen.klasgroep_naam,tbl_leerlingen.aanspreek
                                        FROM tbl_klas_klasgroep
                                        INNER JOIN tbl_klas
                                        ON tbl_klas.klas_id = tbl_klas_klasgroep.klas_id
                                        INNER JOIN tbl_leerlingen
                                        ON tbl_leerlingen.leerling_id = tbl_klas.leerling_id
                                        INNER JOIN tbl_klasgroepen
                                        ON tbl_klasgroepen.klasgroep_id = tbl_klas_klasgroep.klasgroep_id
                                        WHERE tbl_klas_klasgroep.klas_klasgroep_id = ?", $klas_klasgroep_id);
    }

        /*
     * Het verkrijgen van alle klasgroepen van het opgegeven schooljaar met hun naam en deze weergeven als
     * een array die door de Spoon dropbox te gebruiken is
     *
     * @return array
     * @param int $schooljaar   Het schooljaar waarvan de klasgroepen opgevraagd moeten worden
     */
    public function getKlasgroepenBySchooljaar($schooljaar){
        $klasgroepen = $this->db->getRecords("SELECT * FROM tbl_klasgroepen
                                            WHERE schooljaar = ?
                                            AND klasgroep_id != 2665
                                            AND klasgroep_naam != 'Geen klasgroep'
                                            GROUP BY klasgroep_naam
                                            ORDER BY klasgroep_naam", $schooljaar);

        $dropbox = array();
        foreach($klasgroepen as $k){
            $dropbox[$k['klasgroep_id']] = $k['klasgroep_naam'];
        }

        return $dropbox;
    }

    /*
     * De klasgroepen verkrijgen van het huidige schooljaar. Is nodig voor het toevoegen van nieuwe
     * leerlingen/leerkrachten
     *
     * @return string
     */
    public function getKlasgroepenCurrentSchoolyearList(){
        $klasgroepen = $this->db->getRecords("SELECT * FROM tbl_klasgroepen WHERE schooljaar = ?", staticFunctions::createSchooljaar());
        
        $string ="<ul>";
        if(!empty($klasgroepen)){
            foreach($klasgroepen as $k){
                $string .= "<li>".$k['klasgroep_naam']."</li>";
            }
        }else{
            $string .= "<li>Er zijn nog een klasgroepen</li>";
        }
        $string .="<ul>";
     
        return $string;
    }

    /*
     * Kijken of de opgegeven klasnaam bestaat
     *
     * @return boolean
     * @param string $klas      De opgegeven klasnaam
     */
    public function klasNaamExists($klas){
        $klassen = $this->db->getRecords("SELECT klasnaam FROM tbl_klas");

        foreach($klassen as $k){
            if($k['klasnaam'] == $klas){
                return true;
            }
        }
        return false;
    }
}
