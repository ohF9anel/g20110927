<?php
/**
 * LeerlingenToevoegen.php
 *
 * This source file is part of the New Gabin project. More information,
 * documentation can be found @ http://dbz.be/newgabin/
 *
 * @package		newgabin
 *
 * @author David Van Den Dooren
 * @author Godfried Borremans <godfried.borremans@dbz.be>
 */

    require "../_lib/_classes/template.class.php";
    require "../_lib/_includes/functions.inc.php";
    require_once "../classes/staticFunctions.class.php";
    require_once "../classes/bizADMQueryFunctions.class.php";

    session_start();
    staticFunctions::adminControl(isset($_SESSION['admin']), isset($_SESSION['user']), "indexAdm.php");

    $biz = new bizADMQueryFunctions();
    $admin_id = $_SESSION['admin'];
    $adminInfo = $biz->getAdminInfo($admin_id);
    staticFunctions::isAdmin($adminInfo['admin'], "indexAdm.php");
    $bericht = $biz->toevoegenLeerlingenNieuwSchooljaar($adminInfo['rangschik']);

    echo "<pre>".$bericht."</pre>" ;
    echo "<a href=\"leerlingen.php\">naar leerlingen overzicht</a>";
    // header("Location:Leerlingen.php");
    // exit(0);