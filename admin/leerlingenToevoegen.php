<?php

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
    $biz->toevoegenLeerlingenNieuwSchooljaar($adminInfo['rangschik']);

    header("Location:Leerlingen.php");
    exit(0);