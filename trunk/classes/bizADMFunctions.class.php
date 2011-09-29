<?php
    require_once "spoon.php"; //<== classes/spoon.php
    require_once "spoon/database/database.php";//<== classes/spoon/database/database.php
    require_once "staticFunctions.class.php";
    require 'eyesis/class.eyemysqladap.inc.php';
    require 'eyesis/class.eyedatagrid.inc.php';
	require_once '/home/godfried/godsdienst.inc';

/*
 * In deze klasse vindt u de functies die zorgen voor de tabellen op de beheerderpagina's
 *
 * @author David Van Den Dooren
 * @version 27/05/2011
 */
class bizADMFunctions {
    protected  $db;
    private $filterSchooljaar = '<form action="" method="post">
                                      <label for="schooljaarFilter">Schooljaar:</label>
                                      <input type="text" id="schooljaarFilter" name="schooljaarFilter" value="{VALUESCHOOLJAAR}" maxlength="10" class="inputText" />
                                      <input type="submit" value="Filter" id="submit" name="submit" class="inputButton" />
                                 </form>';
    private $filterSchooljaarAndKlas = '<form action="" method="post">
                                            <label for="schooljaarFilter">Schooljaar:</label>
                                            <input type="text" id="schooljaarFilter" name="schooljaarFilter" value="{VALUESCHOOLJAAR}" maxlength="10" class="inputText" />
                                            <br/><label for="klasNaamFilter">Klasnaam:</label>
                                            <input type="text" id="klasNaamFilter" name="klasNaamFilter" value="{VALUEKLASNAAM}" maxlength="10" class="inputText" />
                                            <input type="submit" value="Filter" id="submit" name="submit" class="inputButton" />
                                         </form>';
    
    /*
     * De constructor voor de bizADMFunctions klasse
     */
    function  __construct() {
        # TODO: dit moet hier weg
		$this->db = new SpoonDatabase('mysql', DBZ_DBSERVER, DBZ_DBUSER, DBZ_DBPASS, DBZ_DBDB);
    }

    /*
     * Het opvragen van een random quote voor admin's
     *
     * @return string
     */
    public function getQuote(){
        $quotes = $this->db->getRecords("SELECT * from tbl_quotes");

        return str_replace("-  -","",$quotes[rand(0,count($quotes)-1)]['quote']);
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
     * De connectie maken met de Datagrid class
     *
     * @return object
     */
    public function getDatagridClassConnection(){
		// TODO gb dit moet hier weg 21:58 zondag 11 september 2011
        $db = new EyeMySQLAdap(DBZ_DBSERVER, DBZ_DBUSER, DBZ_DBPASS, DBZ_DBDB);
        
        //load the datagrid class
        $dataGrid = new EyeDataGrid($db);

        return $dataGrid;
    }

    /*
     * Het verkrijgen van tbl_fmad
     *
     * @return string
     * @param int $numberToShow     Het aantal gegevens dat per pagina moet weergegeven worden
     */
    public function getFmadTable($numberToShow){
        $dataGridFmad = $this->getDatagridClassConnection();
        $dataGridFmad->setQuery("rangschik, fn, ln, klass, un, ut", "tbl_fmad", "uuid", "1" );   
		// Set the query, select all rows from the people table with *
        $dataGridFmad->setColumnHeader('fn', 'voornaam');   //Rename column headers
        $dataGridFmad->setColumnHeader('ln', 'achternaam');
        $dataGridFmad->setColumnHeader('klass', 'klas');
        $dataGridFmad->setColumnHeader('un', 'gebruikersnaam');
        $dataGridFmad->setColumnHeader('ut', '1=LKR, 2=LLN');
        $dataGridFmad->showReset();     // Show reset grid control
        $dataGridFmad->setResultsPerPage($numberToShow);   // Change the amount of results per page

        return $dataGridFmad->printTable();
    }

    /*
     * Het verkrijgen van tbl_quotes
     *
     * @return string
     * @param int $numberToShow     Het aantal gegevens dat per pagina moet weergegeven worden
     */
    public function getQuotesTable($numberToShow){
        $dataGridQuotes = $this->getDatagridClassConnection();
        //setQuery(select, table, Primary Key, where-clause)
        $dataGridQuotes->setQuery("*", "tbl_quotes", "quote_id", "1" );     // Set the query, select all rows from the people table with *
        $dataGridQuotes->showReset();
        $dataGridQuotes->showCreateButton("window.location='quotesAc.php'", EyeDataGrid::TYPE_ONCLICK, 'Nieuwe quote');
        // Add standard control
        $dataGridQuotes->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='quotesAc.php?id=%quote_id%'");
        $dataGridQuotes->addRowSelect("window.location='quotesAc.php?id=%quote_id%'");
        $dataGridQuotes->addStandardControl(EyeDataGrid::STDCTRL_DELETE, "window.location='quotesAc.php?del=%quote_id%'");
        $dataGridQuotes->setResultsPerPage($numberToShow);

        return $dataGridQuotes->printTable();
    }

    /*
     * Het verkrijgen van tbl_100dagen
     *
     * @return string
     * @param int $numberToShow     Het aantal gegevens dat per pagina moet weergegeven worden
     * @param int $schooljaar [optional]    De filter schooljaar
     * @param string $klas [optional]       De filter klas
     */
    public function get100DagenTable($numberToShow, $schooljaar = 0, $klas= ""){
        $dataGrid100Dagen = $this->getDatagridClassConnection();
        //de setQuery functie moet nog steeds opgeroepen worden ook bij multi-table selectiequeries
        //multi-table selectiequeries = setQuery("*", "de basistabel", "id van de basistabel", "de where-clause");
        //Het is belangrijk dat je dit doet zodat de tabel het juiste aantal pagina's weergeeft dmv de setQuery functie
        $dataGrid100Dagen->setQuery("*", "tbl_100dagen", "100dagen_id", "1");
        //reset button
        $dataGrid100Dagen->showReset();
        $dataGrid100Dagen->setResultsPerPage($numberToShow);
        //edit button toevoegen
        $dataGrid100Dagen->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='honderdDagenAc.php?id=%100dagen_id%'");
        $dataGrid100Dagen->addRowSelect("window.location='honderdDagenAc.php?id=%100dagen_id%'");

        $dataGrid100Dagen->setColumnHeader('aanspreek', 'leerling');
        $queryToExecute = "SELECT h.100dagen_id, l.aanspreek,k.klasnaam, h.schooljaar,100dagen,100dagen_commentaar,amnesty_international_niet,hd01,hd02,hd03,hd04,hd05,hd06,hd07,hd08,hd09,hd10, h.gewijzigd_op, h.gewijzigd_door
                            FROM tbl_100dagen AS h
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = h.leerling_id
                            INNER JOIN tbl_klas AS k
                            ON h.leerling_id = k.leerling_id
                            WHERE "; // Na de WHERE MOET er nog een spatie komen!!
        //als deze methode uitgevoerd wordt zal de where-clause van de query aangevuld worden.
        //Zorg dat je steeds de juiste naam meegeeft voor het schooljaar en de klasnaam. Dit zijn de namen die je
        //normaal zou ingeven in de query!!!
        $queryToExecute .= $this->createFilterForQuery($schooljaar, "h.schooljaar", $klas,"k.klasnaam");

        //printTable functie kan al dan niet een parameter bevatten.
        //Een parameter is enkel nodig als er een query uitgevoerd wordt die betrekking heeft op meer dan 1 tabel
        return $this->filterSchooljaarAndKlas . $dataGrid100Dagen->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_permanente_evaluatie
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int $schooljaar [optional]    De filter schooljaar
     * @param string $klas [optional]       De filter klas
     */
    public function getPermanenteEvaluatieTable($numberToShow, $schooljaar = 0, $klas= ""){
        $dataGridPE = $this->getDatagridClassConnection();
        $dataGridPE->setQuery("*", "tbl_permanente_evaluatie", "permanente_evaluatie_id", "1");
        $dataGridPE->showReset();
        $dataGridPE->setResultsPerPage($numberToShow);
        $dataGridPE->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='permanenteEvaluatieAc.php?id=%permanente_evaluatie_id%'");
        $dataGridPE->addRowSelect("window.location='permanenteEvaluatieAc.php?id=%permanente_evaluatie_id%'");

        $dataGridPE->setColumnHeader('aanspreek', 'leerling');
        $queryToExecute = "SELECT p.permanente_evaluatie_id,l.aanspreek,k.klasnaam,p.schooljaar,pe01,pe02,pe03,pe04,pe05,pe06,pe07,pe08,pe09,pe10, p.gewijzigd_op, p.gewijzigd_door
                            FROM tbl_permanente_evaluatie AS p
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = p.leerling_id
                            INNER JOIN tbl_klas AS k
                            ON p.leerling_id = k.leerling_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar, "p.schooljaar", $klas,"k.klasnaam");
        
        return $this->filterSchooljaarAndKlas . $dataGridPE->printTable($queryToExecute);
    }

    /*
     * Het verkrijgen van de filters die uitgevoerd moeten worden op de databank query
     *
     * @return string
     * @param int $schooljaar [optional]   Het schooljaar voor de filter
     * @param string $schooljaarQueryNaam [optional]    De naam van het veld schooljaar in de query GEEN SPATIES!!!
     * @param string $klas [optional]      De klas voor de filter
     * @param string $klasQueryNaam [optional]  De naam van het veld klas in de query. GEEN SPATIES!!!
     */
    private function createFilterForQuery($schooljaar = 0, $schooljaarQueryNaam = "", $klas = "", $klasQueryNaam = ""){
        $filter = "";
        if($schooljaar == 0 && $klas == ""){
            $filter .= "1";
        }else{
            if($schooljaar!=0){
                $filter.= $schooljaarQueryNaam." = ".$schooljaar;
            }
            if($schooljaar != 0 && $klas != ""){
                $filter.=" AND ";
            }
            if($klas != ""){
                $filter.= $klasQueryNaam." = '".$klas."'";
            }
        }
        return $filter;
    }

    /*
     * Het weergeven van tbl_leerlingen
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int schooljaar [optional]     De extra filter schooljaar
     * @param string $klas [optional]       De extra filter klas
     */
    public function getLeerlingenTable($numberToShow, $schooljaar = 0, $klas = ""){
        $dataGridLeerlingen = $this->getDatagridClassConnection();
        $dataGridLeerlingen->setQuery("*", "tbl_leerlingen", "leerling_id", "1");
        $dataGridLeerlingen->showReset();
        $dataGridLeerlingen->setResultsPerPage($numberToShow);
        $dataGridLeerlingen->hideColumn('uuid');
        $dataGridLeerlingen->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='leerlingenAc.php?id=%leerling_id%'");
        $dataGridLeerlingen->addRowSelect("window.location='leerlingenAc.php?id=%leerling_id%'");
        $dataGridLeerlingen->setColumnHeader('postcodewp', 'postcode');
        $dataGridLeerlingen->setColumnHeader('famnaam', 'achternaam');
        $dataGridLeerlingen->setColumnHeader('eerstevoor', 'voornaam');

        $queryToExecute = "SELECT l.leerling_id, famnaam,eerstevoor,aanspreek,rangschik,k.klasnaam,k.schooljaar,straat,huisnummer,postcodewp,bus,gemeente,tel_verantw,geboortedatum,gsm,email,pasfoto,fotohanden,l.gewijzigd_op,l.gewijzigd_door
                            FROM tbl_leerlingen AS l
                            INNER JOIN tbl_klas AS k
                            ON k.leerling_id = l.leerling_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"k.schooljaar",$klas,"k.klasnaam");

        return $this->filterSchooljaarAndKlas . $dataGridLeerlingen->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_leerlingen_log
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param string $aanspreek [optional]  De extra filter aanspreek
     */
    public function getLeerlingenLogTable($numberToShow, $aanspreek =""){
        $dataGridLeerlingenLog = $this->getDatagridClassConnection();
        if(empty($aanspreek)){
            $dataGridLeerlingenLog->setQuery("*", "tbl_leerlingen_log", "leerling_log_id", "1");
        }else{
            $dataGridLeerlingenLog->setQuery("*", "tbl_leerlingen_log", "leerling_log_id", "aanspreek LIKE '%".$aanspreek."%'");
        }
        $dataGridLeerlingenLog->showReset();
        $dataGridLeerlingenLog->setResultsPerPage($numberToShow);
        $dataGridLeerlingenLog->hideColumn('uuid');
        $dataGridLeerlingenLog->setColumnHeader('postcodewp', 'postcode');
        $dataGridLeerlingenLog->setColumnHeader('famnaam', 'achternaam');
        $dataGridLeerlingenLog->setColumnHeader('eerstevoor', 'voornaam');

        return $dataGridLeerlingenLog->printTable();
    }

    /*
     * Het weergeven van tbl_leerkrachten
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     */
    public function getLeerkrachtenTable($numberToShow){
        $dataGridLeerkrachten = $this->getDatagridClassConnection();
        $dataGridLeerkrachten->setQuery("*", "tbl_leerkrachten", "leerkracht_id", "1");
        $dataGridLeerkrachten->showReset();
        $dataGridLeerkrachten->setResultsPerPage($numberToShow);
        $dataGridLeerkrachten->hideColumn('uuid');
        $dataGridLeerkrachten->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='leerkrachtenAc.php?id=%leerkracht_id%'");
        $dataGridLeerkrachten->addRowSelect("window.location='leerkrachtenAc.php?id=%leerkracht_id%'");
        $dataGridLeerkrachten->setColumnHeader('postcodewp', 'postcode');
        $dataGridLeerkrachten->setColumnHeader('famnaam', 'achternaam');
        $dataGridLeerkrachten->setColumnHeader('eerstevoor', 'voornaam');

        return $dataGridLeerkrachten->printTable();
    }

    /*
     * Het weergeven van tbl_leerling_mondex
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int schooljaar [optional]     De extra filter schooljaar
     * @param string $klas [optional]       De extra filter klas
     */
    public function getMondelingExamenTable($numberToShow,$schooljaar = 0, $klas = ""){
        $dataGridMondEx = $this->getDatagridClassConnection();
        $dataGridMondEx->setQuery("*", "tbl_leerling_mondex", "leerling_mondex_id", "1");
        $dataGridMondEx->showReset();
        $dataGridMondEx->setResultsPerPage($numberToShow);
        $dataGridMondEx->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='mondelingExamenAc.php?id=%leerling_mondex_id%'");
        $dataGridMondEx->addRowSelect("window.location='mondelingExamenAc.php?id=%leerling_mondex_id%'");

        $dataGridMondEx->setColumnHeader('aanspreek', 'leerling');
        $queryToExecute = "SELECT m.leerling_mondex_id, l.aanspreek, k.klasnaam, m.schooljaar, m.mondeling_examen,m.gewijzigd_op, m.gewijzigd_door
                            FROM tbl_leerling_mondex AS m
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = m.leerling_id
                            INNER JOIN tbl_klas AS k
                            ON k.leerling_id = m.leerling_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"m.schooljaar",$klas,"k.klasnaam");

        return $this->filterSchooljaarAndKlas . $dataGridMondEx->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_kleiwerk
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int $schooljaar [optional]    De filter schooljaar
     * @param string $klas [optional]       De filter klas
     */
    public function getKleiwerkTable($numberToShow,$schooljaar = 0, $klas = ""){
        $dataGridKleiwerk = $this->getDatagridClassConnection();
        $dataGridKleiwerk->setQuery("*", "tbl_kleiwerk", "kleiwerk_id", "1");
        $dataGridKleiwerk->showReset();
        $dataGridKleiwerk->setResultsPerPage($numberToShow);
        $dataGridKleiwerk->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='kleiwerkAc.php?id=%kleiwerk_id%'");
        $dataGridKleiwerk->addRowSelect("window.location='kleiwerkAc.php?id=%kleiwerk_id%'");

        $dataGridKleiwerk->setColumnHeader('aanspreek', 'leerling');
        $queryToExecute = "SELECT k.kleiwerk_id, l.aanspreek, kl.klasnaam,k.schooljaar, k.kleiwerk,k.kleiwerk_commentaar,k.kleiwerk_evaluator,k.gewijzigd_op,k.gewijzigd_door
                            FROM tbl_kleiwerk AS k
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = k.leerling_id
                            INNER JOIN tbl_klas AS kl
                            ON kl.leerling_id = k.leerling_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"k.schooljaar",$klas,"kl.klasnaam");

        return $this->filterSchooljaarAndKlas . $dataGridKleiwerk->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_oudleerlingen
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     */
    public function getOudleerlingenTable($numberToShow){
        $dataGridOudleerlingen = $this->getDatagridClassConnection();
        $dataGridOudleerlingen->setQuery("*", "tbl_oudleerlingen", "oudleerling_id", "1");
        $dataGridOudleerlingen->showReset();
        $dataGridOudleerlingen->setResultsPerPage($numberToShow);
        $dataGridOudleerlingen->showCreateButton("window.location='oudleerlingenAc.php'", EyeDataGrid::TYPE_ONCLICK, 'Nieuwe oudleerling');
        $dataGridOudleerlingen->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='oudleerlingenAc.php?id=%oudleerling_id%'");
        $dataGridOudleerlingen->addRowSelect("window.location='oudleerlingenAc.php?id=%oudleerling_id%'");

        $dataGridOudleerlingen->setColumnHeader('aanspreek', 'oudleerling');
        $queryToExecute = "SELECT o.oudleerling_id, l.aanspreek, r.studierichting , o.richting_details, o.school, o.gewijzigd_op, o.gewijzigd_door
                            FROM tbl_oudleerlingen AS o
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = o.leerling_id
                            INNER JOIN tbl_dbf_richtingen AS r
                            ON r.dbf_richting_id = o.dbf_richting_id
                            WHERE 1";

        return $dataGridOudleerlingen->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_klas
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int schooljaar [optional]     De extra filter schooljaar
     * @param string $klas [optional]       De extra filter klas
     */
    public function getKlassenTable($numberToShow,$schooljaar = 0, $klas = ""){
        $dataGridKlassen = $this->getDatagridClassConnection();
        $dataGridKlassen->setQuery("*", "tbl_klas", "klas_id", "1");
        $dataGridKlassen->showReset();
        $dataGridKlassen->setResultsPerPage($numberToShow);
        $dataGridKlassen->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='klassenAc.php?id=%klas_id%'");
        $dataGridKlassen->addRowSelect("window.location='klassenAc.php?id=%klas_id%'");

        $dataGridKlassen->setColumnHeader('aanspreek', 'leerling');
        $queryToExecute = "SELECT k.klas_id, l.aanspreek, k.klasnaam, k.schooljaar,k.gewijzigd_op, k.gewijzigd_door
                            FROM tbl_klas AS k
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = k.leerling_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"k.schooljaar",$klas,"k.klasnaam");

        return $this->filterSchooljaarAndKlas . $dataGridKlassen->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_klasgroepen
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int schooljaar [optional]     De extra filter schooljaar
     * @param string $klas [optional]       De extra filter klas
     */
    public function getKlasgroepenTable($numberToShow,$schooljaar = 0){
        $dataGridKlasgroepen = $this->getDatagridClassConnection();
        $dataGridKlasgroepen->setQuery("*", "tbl_klasgroepen", "klasgroep_id", "1");
        $dataGridKlasgroepen->showReset();
        $dataGridKlasgroepen->setResultsPerPage($numberToShow);
        $dataGridKlasgroepen->showCreateButton("window.location='klasgroepenAc.php'", EyeDataGrid::TYPE_ONCLICK, 'Nieuw klasgroep');
        $dataGridKlasgroepen->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='klasgroepenAc.php?id=%klasgroep_id%'");
        $dataGridKlasgroepen->addRowSelect("window.location='klasgroepenAc.php?id=%klasgroep_id%'");

        $dataGridKlasgroepen->setColumnHeader('aanspreek', 'leerkracht');
        $queryToExecute = "SELECT k.klasgroep_id, k.klasgroep_naam,l.aanspreek, k.schooljaar,k.gewijzigd_op, k.gewijzigd_door
                            FROM tbl_klasgroepen AS k
                            INNER JOIN tbl_leerkrachten AS l
                            ON l.leerkracht_id = k.leerkracht_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"k.schooljaar");

        return $this->filterSchooljaar . $dataGridKlasgroepen->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_b2d_project_soort
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     */
    public function getProjectSoortTable($numberToShow){
        $dataGridProjectSoort = $this->getDatagridClassConnection();
        $dataGridProjectSoort->setQuery("*", "tbl_b2d_project_soort", "project_soort_id", "1");
        $dataGridProjectSoort->showReset();
        $dataGridProjectSoort->setResultsPerPage($numberToShow);
        $dataGridProjectSoort->showCreateButton("window.location='b2dProjectSoortenAc.php'", EyeDataGrid::TYPE_ONCLICK, 'Nieuwe project soort');
        $dataGridProjectSoort->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='b2dProjectSoortenAc.php?id=%project_soort_id%'");
        $dataGridProjectSoort->addRowSelect("window.location='b2dProjectSoortenAc.php?id=%project_soort_id%'");

        return $dataGridProjectSoort->printTable();
    }

    /*
     * Het weergeven van tbl_b2d_projecten
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param string $naam [optional]   De naam waarnaar gezocht moet worden
     */
    public function getProjectenTable($numberToShow, $naam =""){
        $dataGridProjecten = $this->getDatagridClassConnection();
        $dataGridProjecten->setQuery("*", "tbl_b2d_projecten", "project_id", "1");
        $dataGridProjecten->showReset();
        $dataGridProjecten->setResultsPerPage($numberToShow);
        $dataGridProjecten->showCreateButton("window.location='b2dProjectenAc.php'", EyeDataGrid::TYPE_ONCLICK, 'Nieuw project');
        $dataGridProjecten->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='b2dProjectenAc.php?id=%project_id%'");
        $dataGridProjecten->addRowSelect("window.location='b2dProjectenAc.php?id=%project_id%'");

        $queryToExecute = 'SELECT p.project_id,p.naam, s.categorie_soort, p.aantal_deelnemers,p.straat,p.zip,p.gemeente,p.telefoon,p.fax,p.email,p.verantwoordelijke,p.omschrijving,p.invoerdatum,p.afgevoerd,p.ter_attentie_van,p.evaluatie_vraag,p.gewijzigd_op,p.gewijzigd_door
                            FROM tbl_b2d_projecten AS p
                            INNER JOIN tbl_b2d_project_soort AS s
                            ON p.project_soort_id = s.project_soort_id
                            WHERE ';
        if($naam ==""){
            $queryToExecute .= '1';
        }else{
            $queryToExecute .= 'p.naam LIKE "%'.$naam.'%"';
        }

        return $dataGridProjecten->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_b2d_project_assistentie
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int schooljaar [optional]       De extra filter schooljaar
     */
    public function getProjectAssistentieTable($numberToShow, $schooljaar = 0){
        $dataGridProjectAssistentie = $this->getDatagridClassConnection();
        $dataGridProjectAssistentie->setQuery("*", "tbl_b2d_project_assistentie", "assistentie_id", "1");
        $dataGridProjectAssistentie->showReset();
        $dataGridProjectAssistentie->setResultsPerPage($numberToShow);
        $dataGridProjectAssistentie->showCreateButton("window.location='b2dProjectAssistentieAc.php'", EyeDataGrid::TYPE_ONCLICK, 'Nieuw project assistentie');
        $dataGridProjectAssistentie->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='b2dProjectAssistentieAc.php?id=%assistentie_id%'");
        $dataGridProjectAssistentie->addRowSelect("window.location='b2dProjectAssistentieAc.php?id=%assistentie_id%'");
        $dataGridProjectAssistentie->setColumnHeader('aanspreek', 'leerkracht');
        $dataGridProjectAssistentie->setColumnHeader('naam', 'project naam');

        $dataGridProjectAssistentie->setColumnType('naam', EyeDataGrid::TYPE_HREF, 'b2dProjecten.php?value=%naam%&id=%assistentie_id%');

        $queryToExecute = "SELECT a.assistentie_id, l.aanspreek, p.naam, a.assistentie_jaar,a.gewijzigd_op,a.gewijzigd_door
                            FROM tbl_b2d_project_assistentie AS a
                            INNER JOIN tbl_b2d_projecten AS p
                            ON p.project_id = a.project_id
                            INNER JOIN tbl_leerkrachten AS l
                            ON a.leerkracht_id = l.leerkracht_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"a.assistentie_jaar");

        return $this->filterSchooljaar . $dataGridProjectAssistentie->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_b2d_algemeen
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int schooljaar [optional]     De extra filter schooljaar
     * @param string $klas [optional]       De extra filter klas
     */
    public function getProjectInfoLeerlingTable($numberToShow,$schooljaar = 0, $klas = ""){
        $dataGridProjectInfoLLN = $this->getDatagridClassConnection();
        $dataGridProjectInfoLLN->setQuery("*", "tbl_b2d_algemeen", "b2d_algemeen_id", "1");
        $dataGridProjectInfoLLN->showReset();
        $dataGridProjectInfoLLN->setResultsPerPage($numberToShow);
        $dataGridProjectInfoLLN->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='b2dProjectInfoLeerlingAc.php?id=%b2d_algemeen_id%'");
        $dataGridProjectInfoLLN->addRowSelect("window.location='b2dProjectInfoLeerlingAc.php?id=%b2d_algemeen_id%'");
        $dataGridProjectInfoLLN->setColumnHeader('aanspreek', 'leerling');

        $dataGridProjectInfoLLN->setColumnType('keuze1', EyeDataGrid::TYPE_HREF, 'b2dProjecten.php?value=%keuze1%&id=%b2d_algemeen_id%&pr=1');
        $dataGridProjectInfoLLN->setColumnType('keuze2', EyeDataGrid::TYPE_HREF, 'b2dProjecten.php?value=%keuze2%&id=%b2d_algemeen_id%&pr=2');
        $dataGridProjectInfoLLN->setColumnType('keuze3', EyeDataGrid::TYPE_HREF, 'b2dProjecten.php?value=%keuze3%&id=%b2d_algemeen_id%&pr=3');
        $dataGridProjectInfoLLN->setColumnType('toegewezen_project', EyeDataGrid::TYPE_HREF, 'b2dProjecten.php?value=%toegewezen_project%&id=%b2d_algemeen_id%&pr=4');

        $queryToExecute = "SELECT a.b2d_algemeen_id, l.aanspreek, k1.naam AS keuze1, k2.naam AS keuze2, k3.naam AS keuze3, toe.naam AS toegewezen_project, kl.klasnaam, a.schooljaar, a.aanvraag_ok, a.handtekening_ouders, a.bevestiging_aanwezigheid, a.evaluatie_tekst1, a.evaluatie_tekst2, a.evaluatie_punten, a.evaluatie_afgegeven, a.gewijzigd_op, a.gewijzigd_door
                            FROM tbl_b2d_algemeen AS a
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = a.leerling_id
                            INNER JOIN tbl_b2d_projecten AS k1
                            ON k1.project_id = a.project_keuze1
                            INNER JOIN tbl_b2d_projecten AS k2
                            ON k2.project_id = a.project_keuze2
                            INNER JOIN tbl_b2d_projecten AS k3
                            ON k3.project_id = a.project_keuze3
                            INNER JOIN tbl_b2d_projecten AS toe
                            ON toe.project_id = a.toegewezen_project
                            INNER JOIN tbl_klas AS kl
                            ON kl.leerling_id = a.leerling_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"a.schooljaar",$klas,"kl.klasnaam");

        return $this->filterSchooljaarAndKlas . $dataGridProjectInfoLLN->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_jaarwerk_leerling
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int $schooljaar [optional]    De filter schooljaar
     * @param string $klas [optional]       De filter klas
     */
    public function getJaarwerkLeerlingTable($numberToShow,$schooljaar = 0, $klas = ""){
        $dataGridJaarwerkLln = $this->getDatagridClassConnection();
        $dataGridJaarwerkLln->setQuery("*", "tbl_jaarwerk_leerling", "jaarwerk_leerling_id", "1");
        $dataGridJaarwerkLln->showReset();
        $dataGridJaarwerkLln->setResultsPerPage($numberToShow);
        $dataGridJaarwerkLln->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='jaarwerkLeerlingAc.php?id=%jaarwerk_leerling_id%'");
        $dataGridJaarwerkLln->addRowSelect("window.location='jaarwerkLeerlingAc.php?id=%jaarwerk_leerling_id%'");
        $dataGridJaarwerkLln->setColumnHeader('aanspreek', 'leerling');
        $dataGridJaarwerkLln->setColumnHeader('titel', 'jaarwerk groep');

        $dataGridJaarwerkLln->setColumnType('titel', EyeDataGrid::TYPE_HREF, 'jaarwerkGroep.php?value=%jaarwerk_groep_id%&id=%jaarwerk_leerling_id%');

        $queryToExecute = "SELECT j.jaarwerk_leerling_id, l.aanspreek,k.klasnaam,j.schooljaar,g.titel, g.jaarwerk_groep_id,j.jaarwerk_persoonlijke_inzet,j.gewijzigd_op,j.gewijzigd_door
                            FROM tbl_jaarwerk_leerling AS j
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = j.leerling_id
                            INNER JOIN tbl_jaarwerk_groep AS g
                            ON g.jaarwerk_groep_id = j.jaarwerk_groep_id
                            INNER JOIN tbl_klas AS k
                            ON k.leerling_id = j.leerling_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"j.schooljaar",$klas,"k.klasnaam");

        return $this->filterSchooljaarAndKlas . $dataGridJaarwerkLln->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_jaarwerk_groep
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param string $titel [optional]     De titel die gezocht moet worden
     */
    public function getJaarwerkGroepTable($numberToShow, $titel = ""){
        $dataGridJaarwerkGroep = $this->getDatagridClassConnection();
        if(empty($titel)){
            $dataGridJaarwerkGroep->setQuery("*", "tbl_jaarwerk_groep", "jaarwerk_groep_id", "1");
        }else{
            $dataGridJaarwerkGroep->setQuery("*", "tbl_jaarwerk_groep", "jaarwerk_groep_id", 'titel LIKE "%'.$titel.'%"');
        }
        $dataGridJaarwerkGroep->showReset();
        $dataGridJaarwerkGroep->setResultsPerPage($numberToShow);
        $dataGridJaarwerkGroep->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='jaarwerkGroepAc.php?id=%jaarwerk_groep_id%'");
        $dataGridJaarwerkGroep->addRowSelect("window.location='jaarwerkGroepAc.php?id=%jaarwerk_groep_id%'");

        return $dataGridJaarwerkGroep->printTable();
    }

    /*
     * Het weergeven van tbl_lokalen
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     */
    public function getLokalenTable($numberToShow){
        $dataGridLokalen = $this->getDatagridClassConnection();
        $dataGridLokalen->setQuery("*", "tbl_lokalen", "lokaal_id", "1");
        $dataGridLokalen->showReset();
        $dataGridLokalen->setResultsPerPage($numberToShow);
        $dataGridLokalen->hideColumn('uuid');
        $dataGridLokalen->showCreateButton("window.location='lokalenAc.php'", EyeDataGrid::TYPE_ONCLICK, 'Nieuw lokaal');
        $dataGridLokalen->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='lokalenAc.php?id=%lokaal_id%'");
        $dataGridLokalen->addRowSelect("window.location='lokalenAc.php?id=%lokaal_id%'");

        return $dataGridLokalen->printTable();
    }

    /*
     * Het weergeven van tbl_b2d_algemeen
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     */
    public function getRichtingenTable($numberToShow){
        $dataGriddbfRichtingen = $this->getDatagridClassConnection();
        $dataGriddbfRichtingen->setQuery("*", "tbl_dbf_richtingen", "dbf_richting_id", "1");
        $dataGriddbfRichtingen->showReset();
        $dataGriddbfRichtingen->setResultsPerPage($numberToShow);
        $dataGriddbfRichtingen->showCreateButton("window.location='dbfRichtingenAc.php'", EyeDataGrid::TYPE_ONCLICK, 'Nieuw Don Bosco feesten richting');
        $dataGriddbfRichtingen->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='dbfRichtingenAc.php?id=%dbf_richting_id%'");
        $dataGriddbfRichtingen->addRowSelect("window.location='dbfRichtingenAc.php?id=%dbf_richting_id%'");

        $queryToExecute = "SELECT r.dbf_richting_id, l.naam, lkr.aanspreek, r.studierichting, r.gewijzigd_op,r.gewijzigd_door
                            FROM tbl_dbf_richtingen AS r
                            INNER JOIN tbl_lokalen AS l
                            ON l.lokaal_id = r.lokaal_id
                            INNER JOIN tbl_leerkrachten as lkr
                            ON lkr.leerkracht_id = r.assistentie_leerkracht
                            WHERE 1";
        $dataGriddbfRichtingen->setColumnHeader('naam', 'lokaal');
        $dataGriddbfRichtingen->setColumnHeader('aanspreek', 'leerkracht');

        return $dataGriddbfRichtingen->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_dbf_spreker
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int $jaar [optional]    De filter jaar
     * @param int $actief [optional]       De filter actief
     */
    public function getdbfSprekersTable($numberToShow, $jaar =0, $actief = 0){
        $dataGriddbfSpreker = $this->getDatagridClassConnection();
        $dataGriddbfSpreker->setQuery("*", "tbl_dbf_spreker", "dbf_spreker_id", "1");
        $dataGriddbfSpreker->showReset();
        $dataGriddbfSpreker->setResultsPerPage($numberToShow);
        $dataGriddbfSpreker->showCreateButton("window.location='dbfSprekersAc.php'", EyeDataGrid::TYPE_ONCLICK, 'Nieuwe Don Bosco feesten spreker');
        $dataGriddbfSpreker->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='dbfSprekersAc.php?id=%dbf_spreker_id%'");
        $dataGriddbfSpreker->addRowSelect("window.location='dbfSprekersAc.php?id=%dbf_spreker_id%'");
        $dataGriddbfSpreker->setColumnHeader('aanspreek', 'oudleerling');

        $queryToExecute = "SELECT  s.dbf_spreker_id, l.aanspreek,s.jaar,s.actief,s.desiderata,s.gewijzigd_op,s.gewijzigd_door
                            FROM tbl_dbf_spreker AS s
                            INNER JOIN tbl_oudleerlingen AS o
                            ON o.oudleerling_id = s.oudleerling_id
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = o.leerling_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($jaar,"s.jaar",$actief,"s.actief");

        return $dataGriddbfSpreker->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_dbf
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int $schooljaar [optional]    De filter schooljaar
     * @param string $klas [optional]       De filter klas
     */
    public function getDonBoscoFeestTable($numberToShow,$schooljaar = 0, $klas = ""){
        $dataGridDonBoscoFeest = $this->getDatagridClassConnection();
        $dataGridDonBoscoFeest->setQuery("*", "tbl_dbf", "dbf_id", "1");
        $dataGridDonBoscoFeest->showReset();
        $dataGridDonBoscoFeest->setResultsPerPage($numberToShow);
        $dataGridDonBoscoFeest->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='donBoscoFeestAc.php?id=%dbf_id%'");
        $dataGridDonBoscoFeest->addRowSelect("window.location='donBoscoFeestAc.php?id=%dbf_id%'");
        $dataGridDonBoscoFeest->setColumnHeader('aanspreek', 'leerling');

        $queryToExecute = "SELECT d.dbf_id, l.aanspreek,k.klasnaam ,d.schooljaar,r.studierichting AS dbf_richting,le.aanspreek AS dbf_spreker,d.dbfilm3,d.dbfilm4,d.dbfilm5,d.gewijzigd_op,d.gewijzigd_door
                            FROM tbl_dbf AS d
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = d.leerling_id
                            INNER JOIN tbl_dbf_richtingen AS r
                            ON d.dbf_richting_id = r.dbf_richting_id
                            INNER JOIN tbl_dbf_spreker AS s
                            ON s.dbf_spreker_id = d.dbf_spreker_id
                            INNER JOIN tbl_oudleerlingen AS o
                            ON o.oudleerling_id = s.oudleerling_id
                            INNER JOIN tbl_leerlingen AS le
                            ON le.leerling_id = o.leerling_id
                            INNER JOIN tbl_klas AS k
                            ON k.leerling_id = d.leerling_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"d.schooljaar",$klas,"k.klasnaam");

        return $this->filterSchooljaarAndKlas . $dataGridDonBoscoFeest->printTable($queryToExecute);
    }

    /*
     * Het weergeven van tbl_klas_klasgroep
     * Voor meer informatie over de interne codelijnen zie functie get100DagenTable
     *
     * @return string
     * @param int $numberToShow     Het aantal records dat getoond moet worden
     * @param int $schooljaar [optional]    De filter schooljaar
     * @param string $klas [optional]       De filter klas
     */
    public function getKlasKlasgroepConnectionTable($numberToShow,$schooljaar = 0, $klas = ""){
        $dataGridKlasKlasgroep = $this->getDatagridClassConnection();
        $dataGridKlasKlasgroep->setQuery("*", "tbl_klas_klasgroep", "klas_klasgroep_id", "1");
        $dataGridKlasKlasgroep->showReset();
        $dataGridKlasKlasgroep->setResultsPerPage($numberToShow);
        $dataGridKlasKlasgroep->addStandardControl(EyeDataGrid::STDCTRL_EDIT, "window.location='klasKlasgroepAc.php?id=%klas_klasgroep_id%'");
        $dataGridKlasKlasgroep->addRowSelect("window.location='klasKlasgroepAc.php?id=%klas_klasgroep_id%'");
        $dataGridKlasKlasgroep->setColumnHeader('aanspreek', 'leerling');
        
        $queryToExecute = "SELECT kk.klas_klasgroep_id,l.aanspreek, ka.klasnaam, kg.klasgroep_naam, ka.schooljaar, kk.gewijzigd_op,kk.gewijzigd_door
                            FROM tbl_klas_klasgroep AS kk
                            INNER JOIN tbl_klas AS ka
                            ON ka.klas_id = kk.klas_id
                            INNER JOIN tbl_leerlingen AS l
                            ON l.leerling_id = ka.leerling_id
                            INNER JOIN tbl_klasgroepen AS kg
                            ON kg.klasgroep_id = kk.klasgroep_id
                            WHERE ";
        $queryToExecute .= $this->createFilterForQuery($schooljaar,"ka.schooljaar",$klas,"ka.klasnaam");
        
        return $this->filterSchooljaarAndKlas . $dataGridKlasKlasgroep->printTable($queryToExecute);
    }

    /*
     * Kijken of de opgegeven titel wel bestaat voor een jaarwerk groep
     *
     * @return boolean
     * @param string $titel     De titel waarnaar gezocht moet worden
     */
    public function titelJWGroepExists($titel){
        $titelRecord = $this->db->getRecords("SELECT titel FROM tbl_jaarwerk_groep WHERE titel LIKE ?", '%'.$titel.'%');
        
        if(empty($titelRecord)){
            return false;
        }else{
            return true;
        }
    }

    /*
     * Kijken of de opgegeven naam wel bestaat als project
     *
     * @return boolean
     * @param string $naam    De titel waarnaar gezocht moet worden
     */
    public function naamProjectExists($naam){
        $NaamRecords = $this->db->getRecords("SELECT naam FROM tbl_b2d_projecten WHERE naam LIKE ?", '%'.$naam.'%');
       
        if(empty($NaamRecords)){
            return false;
        }else{
            return true;
        }
    }

    /*
     * Kijken of de opgegeven aanspreeknaam bestaat
     *
     * @return boolean
     * @param string $aanspreek     De aanspreek naam waarnaar gezocht moet worden
     */
    public function aanspreekInLeerlingLogExist($aanspreek){
        $aanspreekRecords = $this->db->getRecords("SELECT aanspreek FROM tbl_leerlingen_log WHERE aanspreek LIKE ?", '%'.$aanspreek.'%');

        if(empty($aanspreekRecords)){
            return false;
        }else{
            return true;
        }
    }
}
