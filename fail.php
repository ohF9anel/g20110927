<?php
    require "_lib/_classes/template.class.php";
    require "_lib/_includes/functions.inc.php";
    
    $failtpl= new Template("usertemplates/");
    $failtpl->set_file("fail_tp", "fail.tpl");
    $failtpl->pparse("htmlcode", "fail_tp");
