<?php
	require_once "spoon.php"; //<== classes/spoon.php
	require_once "spoon/database/database.php";//<== classes/spoon/database/database.php
	require_once "staticFunctions.class.php";
	require_once '/home/godfried/godsdienst.inc';

/*
 * In deze klasse vindt u de functies die databankbewerkingen uitvoeren voor de leerlingen
 *
 * @author David Van Den Dooren
 * @version 27/05/2011
 */
class bizLLNFunctions {
    protected  $db;

    /*
     * De constructor voor de bizFunction klasse
     */
    function __construct(){
        # TODO: dit moet hier weg
		$this->db = new SpoonDatabase('mysql', DBZ_DBSERVER, DBZ_DBUSER, DBZ_DBPASS, DBZ_DBDB);
    }

    /*
     * Het opvragen van een random quote
     *
     * @return string 
     */
    public function getQuote(){
        $quotes = $this->db->getRecords("SELECT * from tbl_quotes");

        return str_replace("-  -","",$quotes[rand(0,count($quotes)-1)]['quote']);
    }

    /*
     * Het verkrijgen van de databank object connectie
     *
     * @return object
     */
    public function getDatabankConnection(){
        return $this->db;
    }

    /*
     * Het weergeven van de informatie over de ingelogde gebruiker
     *
     * @return array
     * @param int $leerling_id  De id van de ingelogde gebruiker
     */
    public function getUserInfo($leerling_id){
        $leerling = $this->db->getRecord("SELECT * FROM tbl_leerlingen WHERE leerling_id=?",$leerling_id);

        return $leerling;
    }

    /*
     * Het verkrijgen van de evaluaties voor het gekozen project
     *
     * @return array
     * @param int $project_id   De id van het gekozen project
     */
    public function getEvaluatiesProject($project_id){
        $evaluaties = $this->db->getRecords("SELECT tbl_b2d_algemeen.evaluatie_tekst1,tbl_leerlingen.aanspreek,tbl_leerlingen.leerling_id FROM tbl_b2d_algemeen
                                   INNER JOIN tbl_leerlingen ON tbl_leerlingen.leerling_id = tbl_b2d_algemeen.leerling_id
                                   WHERE tbl_b2d_algemeen.toegewezen_project = ?",$_REQUEST['project']);

        return $evaluaties;
    }

    /*
     * Het opstellen van de evaluatie(s) HTML string
     *
     * @return string
     * @param array $toegewezen    De evaluatieinfo van de gebruiker die het gekozen project toegewezen heeft gekregen
     */
    public function getEvaluatieHTMLString($toegewezen){
        $evaluaties ="";
        $evaluaties .= "<p>".$toegewezen['evaluatie_tekst1']."</p>";
        $klas = $this->db->getRecord("SELECT klasnaam,schooljaar FROM tbl_klas WHERE leerling_id =? order by schooljaar DESC LIMIT 1",$toegewezen['leerling_id']);
        $evaluaties .= "<p>(".$toegewezen['aanspreek']." - ".$klas['klasnaam']." - ".$klas['schooljaar'].")</p><br/>";

        return $evaluaties;
    }

    /*
     * Het verkrijgen van de jwinformatie van de ingelogde leerling
     *
     * @return array
     * @param int $leerling_id      De id van de ingelogde leerling
     */
    public function getJWInfoLeerling($leerling_id){
        $jwInfoLeerling = $this->db->getRecord("SELECT tbl_jaarwerk_groep.* , tbl_jaarwerk_leerling.*
                                                FROM tbl_jaarwerk_groep
                                                INNER JOIN tbl_jaarwerk_leerling ON tbl_jaarwerk_groep.jaarwerk_groep_id = tbl_jaarwerk_leerling.jaarwerk_groep_id
                                                WHERE tbl_jaarwerk_leerling.leerling_id =?
                                                ORDER BY schooljaar DESC
                                                LIMIT 1 ",$leerling_id);
        
        return $jwInfoLeerling;
    }

    /*
     * Het verkrijgen van de klas waar de leerling inzit
     *
     * @return array
     * @param int $leerling_id      Het id van de leerling
     */
    public function getKlasLLN($leerling_id){
        $query = $this->db->getRecord("SELECT tbl_klas.klasnaam,tbl_klas.schooljaar,tbl_klas_klasgroep.klasgroep_id
                                FROM tbl_klas_klasgroep
                                INNER JOIN tbl_klas ON tbl_klas.klas_id = tbl_klas_klasgroep.klas_id
                                WHERE tbl_klas.leerling_id=?
                                ORDER by tbl_klas.schooljaar DESC LIMIT 1",$leerling_id);
        return $query;
    }

    /*
     * Het verkrijgen van de leerlingen die in dezelfde klasgroep zitten als de ingelogde leerling
     * Vervolgens de leerlingen in een HTML droplist string steken
     *
     * @return string
     * @param int $leerling_id  De id van de ingelogde leerling
     */
    public function getKlasgroepLlnHTMLDroplist($leerling_id){
        $groepsledenhtml ="";
        //opvragen van de klas en klasgroep_id van de leerling
        $query = $this->getKlasLLN($leerling_id);
        if(!empty($query)){
            //Iedere leerling opvragen die in dezelfde klasgroep zit als de ingelogde gebruiker
            $leerlingenInKlasgroep = $this->db->getRecords("SELECT tbl_leerlingen.leerling_id,tbl_leerlingen.aanspreek
                                                    FROM tbl_leerlingen
                                                    INNER JOIN tbl_klas ON tbl_klas.leerling_id = tbl_leerlingen.leerling_id
                                                    WHERE tbl_klas.klas_id IN(
                                                    SELECT klas_id
                                                    FROM tbl_klas_klasgroep
                                                    WHERE klasgroep_id=?)ORDER BY tbl_leerlingen.aanspreek",$query['klasgroep_id']);
            //De 2 droplisten vullen met de namen van alle leerlingen die in dezelfde klasgroep zitten
            foreach($leerlingenInKlasgroep as $lln){
                if($lln['leerling_id']!=$_SESSION['user']){
                    $groepsledenhtml .= "<option value='".$lln['leerling_id']."'>".$lln['aanspreek']."</option>";
                }
            }
        }
        $groepsledenhtml .="</select>";

        return $groepsledenhtml;
    }

    /*
     * Het verkrijgen van de gekende project categorieen
     *
     * @return string
     */
    public function getB2DProjectCategorieen(){
        $categorieen = "<ul><li><a href='b2dProjecten.php?page=0'>Alle projecten</a></li>";
        $alleCategories = $this->db->getRecords("SELECT project_soort_id,categorie_soort FROM tbl_b2d_project_soort ORDER BY categorie_soort");
        foreach($alleCategories as $c){
                $categorieen .= "<li><a href='b2dProjecten.php?categorie=".$c['project_soort_id']."&amp;page=0'>".iconv('Windows-1252', 'UTF-8', $c['categorie_soort'])."</a></li>\n";
        }
        $categorieen .="</ul>";

        return $categorieen;
    }

    /*
     * Het verkrijgen van de project informatie
     *
     * @return array
     * @param int $project_id   De id van het gekozen project
     */
    public function getProjectInfo($project_id){
        $project = $this->db->getRecord("SELECT tbl_b2d_projecten.*, tbl_b2d_project_soort.categorie_soort FROM tbl_b2d_projecten
                                            INNER JOIN tbl_b2d_project_soort
                                            ON tbl_b2d_project_soort.project_soort_id = tbl_b2d_projecten.project_soort_id
                                            WHERE project_id=? LIMIT 1",$project_id);

        return $project;
    }

    /*
     * Het verkrijgen van de gekende Bezinnings2Daagse projecten of enkel de projecten van een bepaalde categorie
     *
     * @return string
     * @param int $page                         De navigatiepagina
     * @param int $numberToShow                 Aantal records dat getoond moet worden
     * @param int $categorie_id [optional]      De id van de geselecteerde categorie
     */
    public function getAllB2DProjecten($page, $numberToShow, $categorie_id =0){
        //Ofwel moeten de projecten van een bepaalde categorie weergegeven worden
        //Ofwel moeten alle projecten weergegeven worden omdat er nog geen categorie gekozen is
        $alleProjecten ="";
        $pagingHTMLstring="";
        $beginLimit = $page*$numberToShow;
        if($categorie_id != 0){
            $totaalAantalProjecten = $this->db->getRecord("SELECT count(*) FROM tbl_b2d_projecten WHERE project_soort_id=?",$categorie_id);
            $alleProjecten = $this->db->getRecords("SELECT project_id, naam, aantal_deelnemers, gemeente FROM tbl_b2d_projecten
                                                    WHERE project_soort_id=? ORDER by naam LIMIT ?, ?",array($categorie_id, $beginLimit , $numberToShow));
            $pagingHTMLstring= staticFunctions::paging($totaalAantalProjecten, $numberToShow, $categorie_id);
        }else{
            $totaalAantalProjecten = $this->db->getRecord("SELECT count(*) FROM tbl_b2d_projecten");
            /* gb 20110927
            $alleProjecten = $this->db->getRecords("SELECT project_id, naam, aantal_deelnemers, gemeente FROM tbl_b2d_projecten
                                                    ORDER by naam LIMIT ?, ?", array($beginLimit,$numberToShow));
             
             */
            $alleProjecten = $this->db->getRecords("SELECT count( toegewezen_project ) AS c, project_id, naam, aantal_deelnemers, gemeente, toegewezen_project
FROM tbl_b2d_projecten P, tbl_b2d_algemeen A
WHERE P.project_id = A.toegewezen_project
AND A.schooljaar =2011
GROUP BY project_id
ORDER BY naam
LIMIT ?, ?", array($beginLimit,$numberToShow));
            $pagingHTMLstring= staticFunctions::paging($totaalAantalProjecten, $numberToShow);
        }
        
        $projecten = $pagingHTMLstring;
        $projecten .="<table border='1'>\n
        <tr>\n
            <th>Project naam:</th>\n
            <th>Project gemeente:</th>\n
            <th>Max aantal:</th>\n
            <th>Aantal toegewezen:</th>\n
        </tr>\n";

        foreach($alleProjecten as $p){
            if($p['project_id']!=258 && $p['project_id']!=304){ 
                //project met id 258 (niet toegewezen) is een dummy 
                // project met id 304 (afwezig omwille van) moet niet weergegeven worden
                $projecten .="<tr>
                                <td><a href='b2dProjectenDetails.php?project=".$p['project_id']."'>".iconv('Windows-1252', 'UTF-8', $p['naam'])."</a></td>
                                <td>".$p['gemeente']."</td>
                                <td>".$p['aantal_deelnemers']."</td>
                                <td>".$p['c']."</td>    
                             </tr>";
            }
        }
        $projecten .="</table>";

        $projecten .= $pagingHTMLstring;
        
        return $projecten;
    }

    /*
     * Het verkrijgen van het toegewezen project met de bijhorende project informatie voor een welbepaalde leerling
     *
     * @return array
     * @param int $leerling_id      De id van de leerling
     */
    public function getToegewezenProject($leerling_id){
        $toegewezenProject = $this->db->getRecord("SELECT tbl_b2d_project_soort.categorie_soort, tbl_b2d_algemeen.schooljaar, tbl_b2d_projecten.*
                                                    FROM tbl_b2d_project_soort
                                                    INNER JOIN tbl_b2d_projecten
                                                    ON tbl_b2d_projecten.project_soort_id = tbl_b2d_project_soort.project_soort_id
                                                    INNER JOIN tbl_b2d_algemeen
                                                    ON tbl_b2d_algemeen.toegewezen_project = tbl_b2d_projecten.project_id
                                                    WHERE tbl_b2d_algemeen.leerling_id =?
                                                    ORDER BY tbl_b2d_algemeen.schooljaar DESC
                                                    LIMIT 1 ", $leerling_id);
        return $toegewezenProject;
    }

    /*
     * Het weergeven van de leerlingen met dezelfde project keuze in een bepaald schooljaar
     * Vervolgens het aanmaken van de HTML string met daarin de leerling/klas die hetzelfde
     * project toegewezen heeft gekregen als de ingelogde gebruiker
     *
     * @return array
     * @param int $project_id       De id van het toegewezen project
     * @param int $schooljaar       Het schooljaar van de leerling
     * @param int $leerling_id      De id van de leerling
     */
    public function getZelfdeKeuzeProjectHTMLString($project_id, $schooljaar, $leerling_id){
        $leerlingMetZelfdeProject = $this->db->getRecords("SELECT tbl_leerlingen.*, tbl_b2d_algemeen.toegewezen_project
                                                            FROM tbl_leerlingen
                                                            INNER JOIN tbl_b2d_algemeen
                                                            ON tbl_leerlingen.leerling_id = tbl_b2d_algemeen.leerling_id
                                                            WHERE tbl_b2d_algemeen.schooljaar = ?
                                                            AND tbl_b2d_algemeen.toegewezen_project = ?
                                                            AND tbl_b2d_algemeen.leerling_id != ?", array($schooljaar, $project_id, $leerling_id));
        $htmlString ="<ul>";
        foreach($leerlingMetZelfdeProject as $z){
            //Het opvragen van de klas waar de leerling inzit
            $klas = $this->getKlasLLN($z['leerling_id']);
            $htmlString .= "<li>".$z['aanspreek']." - ".$klas['klasnaam']." </li>";
        }
        $htmlString .= "</ul>";

        return $htmlString;
    }

    /*
     * Het verkrijgen van de b2d algemene informatie voor een bepaalde leerling
     *
     * @return array
     * @param int $leerling_id      De id van de leerling
     */
    public function getB2DAlgemeenInfoLeerling($leerling_id){
        $b2dAlgemeen = $this->db->getRecord("SELECT * FROM tbl_b2d_algemeen
                                            WHERE tbl_b2d_algemeen.leerling_id = ?
                                            ORDER BY tbl_b2d_algemeen.schooljaar DESC
                                            LIMIT 1 ", $leerling_id);
        return $b2dAlgemeen;
    }
    
    /*
     * Kijken of de leerling al een jaarwerk heeft
     *
     * @return boolean
     * @param int $leerling_id      De id van de leerling
     * @param int $schooljaar       Het schooljaar wanneer de actie gebeurt
     */
    public function heeftLLNEenJaarwerk($leerling_id, $schooljaar){
        $heeftJW = $this->db->getRecord("SELECT * FROM tbl_jaarwerk_leerling
                                        WHERE leerling_id = ? 
                                        AND schooljaar = ?", array($leerling_id, $schooljaar));
        if(count($heeftJW)>0){
            return true;
        }else{
            return false;
        }
    }

    /*
     * Het verkrijgen van de b2d project soort naam voor een bepaalde project_soort_id
     *
     * @return string
     * @param int $project_soort_id     De id van de b2d_project_soort
     */
    public function getCategorieNaam($project_soort_id){
        $categorie = $this->db->getRecord("SELECT categorie_soort FROM tbl_b2d_project_soort WHERE project_soort_id=?", $project_soort_id);
       
        return $categorie['categorie_soort'];
    }

    /*
     * Het verkrijgen van de overgebleven projecten als een HTML droplist string
     *
     * @return string
     * @param string $keuze     De keuzedropbox
     * @param int $gekozenProject [optional]    Het gekozen project_id
     */
    public function getB2DOvergeblevenProjectenHTMLDropList($keuze, $gekozenProject_id = 0){
        /* gb 20110912
        als aantal deelnemers  = 0 dan is het een project dat niet meer geselecteerd mag worden
        
        */
    
        $projecten = $this->db->getRecords("SELECT * FROM tbl_b2d_projecten
                                            WHERE project_id != 258
                                            AND project_id != 304
                                            AND aantal_deelnemers > 0
                                            ORDER by naam");
        $toegewezenProjecten = $this->db->getRecords("SELECT toegewezen_project FROM tbl_b2d_algemeen
                                                        WHERE schooljaar= ?", staticFunctions::createSchooljaar());
        
        $overgeblevenProjectenHTMLstring =$keuze;
        foreach($projecten as $p){
            $aantalVoorkomen = 0;
            foreach($toegewezenProjecten as $t){
                if($t['toegewezen_project'] == $p['project_id']){
                    $aantalVoorkomen++;
                }
            }
            if($p['aantal_deelnemers']==0 || $p['aantal_deelnemers']> $aantalVoorkomen){
                if($p['project_id'] == $gekozenProject_id){
                    $overgeblevenProjectenHTMLstring .= "<option value='".$p['project_id']."' selected='selected'>".$p['naam']."</option>";
                }else{
                    $overgeblevenProjectenHTMLstring .= "<option value='".$p['project_id']."'>".$p['naam']."</option>";
                }
            }
        }
        $overgeblevenProjectenHTMLstring .="</select>";

        return $overgeblevenProjectenHTMLstring;
    }

    /*
     * De informatie verkrijgen van de dbf spreker wie/wat/wnr
     *
     * @return array
     */
    public function getDBFSprekersInfo (){
        $sprekers = $this->db->getRecords("SELECT tbl_leerlingen.aanspreek, tbl_dbf_spreker.dbf_spreker_id, tbl_oudleerlingen.*, tbl_dbf_richtingen.studierichting
                                            FROM tbl_leerlingen
                                            INNER JOIN tbl_oudleerlingen
                                            ON tbl_leerlingen.leerling_id = tbl_oudleerlingen.leerling_id
                                            INNER JOIN tbl_dbf_spreker
                                            ON tbl_oudleerlingen.oudleerling_id = tbl_dbf_spreker.oudleerling_id
                                            INNER JOIN tbl_dbf_richtingen
                                            ON tbl_dbf_richtingen.dbf_richting_id = tbl_oudleerlingen.dbf_richting_id
                                            WHERE jaar = ?
                                            AND actief = 'ja'
                                            ORDER by tbl_leerlingen.aanspreek", staticFunctions::createSchooljaar());
        return $sprekers;
    }

    /*
     * Het aanmaken van de dbf sprekers droplist
     *
     * @return string
     * @param int $dbf_spreker_id [optional]   De id van de dbf_spreker
     */
    public function getDBFSprekersHTMLDroplist($dbf_spreker_id = 0){
        $sprekers = $this->getDBFSprekersInfo();
        $sprekerHTMLstring ="";
        foreach ($sprekers as $s){
            if($dbf_spreker_id == $s['dbf_spreker_id']){
                $sprekerHTMLstring .="<option value='".$s['dbf_spreker_id']."' selected='selected'>".$s['aanspreek']."</option>";
            }else{
                $sprekerHTMLstring .="<option value='".$s['dbf_spreker_id']."'>".$s['aanspreek']."</option>";
            }
        }
        $sprekerHTMLstring .= "</select>";

        return $sprekerHTMLstring;
    }

    /*
     * Het aanmaken van de studierichting droplist
     *
     * @return string
     * @param int $dbf_richting_id [optional]   De id van de dbf_richting
     */
    public function getDBFStudierichtingenHTMLDroplist($dbf_richting_id = 0){
        $studierichtingen = $this->getDBFSprekersInfo();
        
        $studieHTMLstring ="";
        $studieGroepenId = array();

        if(count($studierichtingen) > 0 && is_array($studierichtingen)){
            for ($i = 0; $i<count($studierichtingen); $i++){
                $studieGroepenId[$i] = $studierichtingen[$i]['dbf_richting_id'];
            }
            $studieGroepenId = array_unique($studieGroepenId);

            foreach($studieGroepenId as $sg){
                $richtingNaam = $this->db->getRecord("SELECT studierichting FROM tbl_dbf_richtingen WHERE dbf_richting_id= ?", $sg);
                if($dbf_richting_id == $sg){
                    $studieHTMLstring .= "<option value='".$sg."' selected='selected'>".$richtingNaam['studierichting']."</option>";
                }else{
                    $studieHTMLstring .= "<option value='".$sg."'>".$richtingNaam['studierichting']."</option>";
                }
            }
        }

        $studieHTMLstring .="</select>";
        return $studieHTMLstring;
    }

    /*
     * Het aanmaken van de dbf spreker-richting-studierichting tabel
     *
     * @return array
     */
    public function getDBFSprekerStudierichtingHTMLTabel(){
        $sprekerInfo = $this->getDBFSprekersInfo();

        $sprekerInfoHTMLstring ="<table border='1'>
                                    <tr>
                                        <th>Spreker:</th>
                                        <th>Richting:</th>
                                        <th>Studierichting:</th>
                                    </tr>";
        foreach ($sprekerInfo as $s){
            $sprekerInfoHTMLstring .="<tr>
                                        <td>".$s['aanspreek']."</td>
                                        <td>".$s['richting_details']." (".$s['school'].")"."</td>
                                        <td>".$s['studierichting']."</td>
                                     </tr>";
        }
        $sprekerInfoHTMLstring .= "</table>";

        return $sprekerInfoHTMLstring;
    }

    /*
     * Het opvragen van de gegevens voor een welbepaalde leerling uit de tbl_dbf
     *
     * @return array
     * @param int $leerling_id  De ingelogde leerling
     */
    public function getDBFKeuzeLeerling($leerling_id){
        return $this->db->getRecord("SELECT tbl_dbf.dbf_richting_id,tbl_dbf_richtingen.studierichting, tbl_dbf.dbf_spreker_id, tbl_leerlingen.aanspreek
                                        FROM tbl_dbf
                                        INNER JOIN tbl_dbf_spreker
                                        ON tbl_dbf.dbf_spreker_id = tbl_dbf_spreker.dbf_spreker_id
                                        INNER JOIN tbl_oudleerlingen
                                        ON tbl_oudleerlingen.oudleerling_id = tbl_dbf_spreker.oudleerling_id
                                        INNER JOIN tbl_leerlingen
                                        ON tbl_leerlingen.leerling_id = tbl_oudleerlingen.leerling_id
                                        INNER JOIN tbl_dbf_richtingen
                                        ON tbl_dbf.dbf_richting_id = tbl_dbf_richtingen.dbf_richting_id
                                        WHERE tbl_dbf.leerling_id =?
                                        AND tbl_dbf.schooljaar = ?
                                        LIMIT 1", array($leerling_id, staticFunctions::createSchooljaar()));
    }

    /*
     * Het tijdstip waarop een bepaalde leerling zijn mondeling examen moet afleggen
     *
     * @return string
     * @param int $leerling_id  De id van de leerling waarvan de MondEx info moet opgehaald worden
     */
    public function getMondelingExamenByLeerlingId($leerling_id){
        $mondEx = $this->db->getRecord("SELECT * FROM tbl_leerling_mondex
                                        WHERE leerling_id = ?
                                        AND schooljaar =?", array($leerling_id, staticFunctions::createSchooljaar()));
        
        if(empty($mondEx['mondeling_examen'])){
            return "er is nog geen datum vastgelegd!";
        }else{
            return $mondEx['mondeling_examen'];
        }
    }

    /*
     * Het verkrijgen van een tabel html string met de permanente evaluatie gegevens van een bepaalde leerling
     *
     * @return string
     * @param int $leerling_id  De id van de leerlinge waarvan de PE info opgehaald moet worden
     */
    public function getPermanenteEvalHTMLString($leerling_id){
        $PEInfo = $this->db->getRecord("SELECT * FROM tbl_permanente_evaluatie
                                        WHERE leerling_id=?
                                        AND schooljaar =?", array($leerling_id, staticFunctions::createSchooljaar()));

        $PEInfoHTMLstring ="<table border='1'>
                                <tr>
                                    <th>Permanente evaluatie:</th>
                                    <th>Resultaat:</th>
                                </tr>";
        for($i = 1; $i<10; $i++){
            $PEInfoHTMLstring .= "<tr>
                                    <td>pe".$i."</td>
                                    <td>".($PEInfo['pe0'.$i] == "" || $PEInfo['pe0'.$i] == 0? "..." : $PEInfo['pe0'.$i] )."/10</td>
                                 </tr>";
        }
        $PEInfoHTMLstring .= "<tr>
                                    <td>pe10</td>
                                    <td>".($PEInfo['pe10'] == "" || $PEInfo['pe10'] == 0? "..." : $PEInfo['pe10'] )."/10</td>
                              </tr>
                              </table>";

        return $PEInfoHTMLstring;
    }
}