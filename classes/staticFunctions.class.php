<?php
require_once "bizLLNFunctions.class.php";
require_once "bizADMQueryFunctions.class.php";

/*
 * In deze klasse vindt u de statische functies: formaat aanpassingen, mails, schooljaar ,...
 *
 * @author David Van Den Dooren
 * @version 27/05/2011
 */
class staticFunctions{

    /*
     * Controle van de leerling pagina's
     *       1) Kijken of het geen admin is die toegang wil tot de leerling pagina's
     *       2) Kijken of er een user session bestaat
     * Er wordt teruggelinkt naar de index.php pagina om ervoor te zorgen dat er een $_SESSION['user'] aangemaakt wordt
     *
     * @param bool $user            Bestaat er een user session
     * @param bool $admin           Bestaat er een admin session
     * @param string $redirect      De pagina waarnaar teruggekeerd moet worden
     */
    public static function userControl($user, $admin, $redirect){
        if($admin){ //1
            header("Location:admin/indexAdm.php");
            exit(0);
        }
        if(!$user){ //2
            $_SESSION['redirectPage']=$redirect;
            header("Location:index.php");
            exit(0);
        }
    }

    /*
     * Controle van de admin/beheerder pagina's
     *       1) Kijken of het geen leerling is die toegang wil tot de admin/beheer pagina's
     *       2) Kijken of er een admin session bestaat
     * Er wordt teruggelinkt naar de index.php pagina om ervoor te zorgen dat er een $_SESSION['admin'] aangemaakt wordt
     *
     * @param bool $admin   Bestaat er een admin session
     * @param bool $user    Bestaat er een user session
     * @param string $redirect      De pagina waarnaar teruggekeerd moet worden
     */
    public static function adminControl($admin, $user, $redirect){
        if($user){ //1
            header("Location:../index.php");
            exit(0);
        }
        if(!$admin){ //2
            $_SESSION['redirectPage']="admin/".$redirect;
            header("Location:../index.php");
			// header('Content-type: text/html; charset=utf-8; Location:../index.php'); 
            exit(0);
        }
    }

    /*
     * Het juiste datum formaat verkrijgen bv: Vrijdag 1 April 2011
     *
     * @return string
     * @param string $date  Een datum string
     */
    public static function createDateFormat($date){
        $dagen = array("Maandag","Dinsdag","Woensdag","Donderdag","Vrijdag","Zaterdag","Zondag");
        $maanden = array("Januari","Februari","Maart","April","Mei","Juni","Juli","Augustus","September","Oktober","November","December");
        $dag = date("N",strtotime($date));
        $maand = date("n",strtotime($date));
        $dagInWoorden = $dagen[$dag-1];
        $maandInWoorden = $maanden[$maand-1];

        $datum = $dagInWoorden.date(" j ",strtotime($date)).$maandInWoorden.date(" Y",strtotime($date));
        
        return $datum;
    }

    /*
     * Het weergeven het schooljaar
     *
     * @return int
     */
    public static function createSchooljaar(){
        //enkel de eerste 6 maand van het jaar(jan/feb/maart/apr/mei/juni) ben je begonnen vanaf het jaar -1
        //juli&augustus horen bij het nieuwe jaar
        //vb. 2011-05-15 ==>schoojaar=2010                    2010-09-15 ==> schoojaar=2010
        $schooljaar = 0;
        if(date("n")>=1 && date("n")<=6){
            $schooljaar = date("Y")-1;
        }else{
            $schooljaar = date("Y");
        }
        return $schooljaar;
    }

    /*
     * Het aanmaken van een paging systeem met het totaal aantal pagina's en een vorige/volgende/eerste en laatste knop
     *
     * @return string
     * @param array $arrayLength            De lengte van de totale array zodat je weet hoeveel pagina's er mogelijk zijn
     * @param int $numberToShow             Aantal records dat getoond moet worden
     * @param int $categorie[optional]      De gekozen categorie
     */
    public static function paging($arrayLength, $numberToShow, $categorie=0){
        $pagingHTML ="<p>";

        $maxAantal = ceil($arrayLength['count(*)'] / $numberToShow);
        $pagina = $_REQUEST['page'];
        //Eerste pagina
        //Volgende enkel mogelijk als men niet op de eerste weergave zit
        if($pagina > 0){
            if($categorie!=0){
                $pagingHTML .= "<a href='b2dProjecten.php'?categorie=".$categorie."&amp;page=0'><img src='images/firstPage.png' alt='photo'/></a>";
                $pagingHTML .= "<a href='b2dProjecten.php?categorie=".$categorie."&amp;page=".($pagina-1)."'><img src='images/previousPage.png' alt='photo'/> </a>";
            }else{
                $pagingHTML .= "<a href='b2dProjecten.php?page=0'><img src='images/firstPage.png' alt='photo'/></a>";
                $pagingHTML .= "<a href='b2dProjecten.php?page=".($pagina-1)."'><img src='images/previousPage.png' alt='photo'/> </a>";
            }
        }
        //De verschillende navigatiepagina's
        for($i =0; $i<$maxAantal ;$i++){
            if($categorie!=0){
                $pagingHTML .="<a href='b2dProjecten.php?categorie=".$categorie."&amp;page=".$i."'>".($i+1)." </a>";
            }else{
                // current page vetjes: feedback naar de gebruiker, waar zit ik
                if ($i == $pagina) {
                    $pagingHTML .="<a href='b2dProjecten.php?page=".$i."'>&nbsp;<strong>".($i+1)."</strong>&nbsp;</a>";
                }
                else {
                    $pagingHTML .="<a href='b2dProjecten.php?page=".$i."'>&nbsp;".($i+1)."&nbsp;</a>";
                }
            }
        }
        //Volgende enkel mogelijk als men niet op de laatste weergave zit
        //Laatste pagina
        if($pagina < $maxAantal-1){
            if($categorie!=0){
                $pagingHTML .= "<a href='b2dProjecten.php?categorie=".$categorie."&amp;page=".($pagina+1)."'><img src='images/nextPage.png' alt='photo'/></a>";
                $pagingHTML .= "<a href='b2dProjecten.php?categorie=".$categorie."&amp;page=".($maxAantal-1)."'><img src='images/lastPage.png' alt='photo'/></a>";
            }else{
                $pagingHTML .= "<a href='b2dProjecten.php?page=".($pagina+1)."'><img src='images/nextPage.png' alt='photo'/></a>";
                $pagingHTML .= "<a href='b2dProjecten.php?page=".($maxAantal-1)."'><img src='images/lastPage.png' alt='photo'/></a>";
            }
        }
        $pagingHTML .="</p>";

        return $pagingHTML;
    }

    /*
     * Het versturen van een email
     *
     * @param string $to            Naar wie de email verstuurd moet worden
     * @param string $onderwerp     Het onderwerp van de mail
     * @param string $bericht       Het mail bericht
     * @param string $from          De naam van de persoon die de mail verzonden heeft
     */
    public static function sendMail($to, $onderwerp, $bericht, $from){
        // gb 19:52 zondag 11 september 2011 $headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: '.$from.' <noreply@dbz.be>';

        //als de mail niet verzonden wordt via skynet dan moet deze verzonden worden via telenet
        if(!mail($to,$onderwerp,$bericht,$headers)){
            //de smtp omzetten naar out.telenet.be
            ini_set("SMTP", "out.telenet.be");
            mail($to,$onderwerp,$bericht,$headers);
        }
    }

    /*
     * Met deze functie zal controleren of de gebruiker wel een beheerder of admin is
     *
     * @param int $admin    0=leerkracht    1=beheerder     2=admin
     */
    public static function isAdminOfBeheerder($access){
        if($access == 0){
            header("Location:../fail.php");
            exit(0);
        }
    }

    /*
     * De pagina's waartoe enkel admins toegang hebben
     */
    public static function isAdmin($admin, $url){
        if($admin != 2){
            echo '<script type="text/javascript">
                    alert("U bent niet bevoegd om deze pagina aan te passen");
                    window.location="'.$url.'";
                  </script>';
        }
    }

    /*
     * Controleren of de ingegeven schooljaar filter correct is en vervolgens een actie uitvoeren
     *
     * @param int $schooljaar   Het ingegeven schooljaar als filter
     */
    public static function SchooljaarFilterCorrect($schooljaar){
        $filterSchooljaar = $schooljaar;
        if(!empty($filterSchooljaar)){
            $rexgetal="/^[0-9]+$/";
            if(preg_match($rexgetal, $schooljaar)){
                if($schooljaar > 1997 && $schooljaar < staticFunctions::createSchooljaar()+1){
                    $_SESSION['schooljaarFilter'] = $filterSchooljaar;
                }
            }
        }else if(isset($_SESSION['schooljaarFilter'])){
            $_SESSION['schooljaarFilter'] = "";
        }
    }

    /*
     * Controleren of de ingegeven schooljaar en klas filter correct zijn en vervolgens een actie uitvoeren
     *
     * @param int $schooljaar   Het ingegeven schooljaar als filter
     * @param string $klas      De ingegeven klas als filter
     */
    public static function KlasAndSchooljaarFilterCorrect($schooljaar, $klas){
        $filterSchooljaar = $schooljaar;
        $filterKlasnaam = $klas;

        $biz = new bizADMQueryFunctions();

        staticFunctions::SchooljaarFilterCorrect($schooljaar);

        if(!empty($filterKlasnaam)){
            if($biz->klasNaamExists($filterKlasnaam)){
                $_SESSION['klasNaamFilter'] = $filterKlasnaam;
            }
        }else if(isset($_SESSION['klasNaamFilter'])){
            $_SESSION['klasNaamFilter'] ="";
        }
    }
  
    /*
     * Ervoor zorgen dat alle entities, uit een array, herkend worden voor op de HTML website
     *
     * @return array
     * @param array $arrayNoEntities    De array waarin de entities nog niet herkend zijn
     * @param boolean $twoDim       Zal aanduiden of het over een 2-dim array gaat of niet
     */
    public static function makeHtmlEntitiesFromArray($arrayNoEntities, $twoDim){
        $result=array();
        if(!$twoDim){ //1dim
            while ($var = current($arrayNoEntities)) {
                $result[key($arrayNoEntities)] = htmlentities($var);
                next($arrayNoEntities);
            }
        }else{ //2dim
            for ($i = 0; $i<count($arrayNoEntities); $i++){
                while ($var = current($arrayNoEntities[$i])) {
                    $result[$i][key($arrayNoEntities[$i])] = htmlentities($var);
                    next($arrayNoEntities[$i]);
                }
            }
        }
        return $result;
    }
}
