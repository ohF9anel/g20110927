<!-- BEGIN CONTENTBLOCK -->

    <div id="breadcrumb"><p>U bent hier: <span id="breadcrumbLocatie">Bezinnings2daagse - Keuze maken</span>
{BREAD}
</p>
</div>
    <div id="inhoudBody">
        <p>Hier kan u uw 3 keuzes van projecten ingeven. Voor meer informatie ivm de projecten: <a href="b2dProjecten.php">Projecten</a></p>
        <p>U kan de projectkeuzes veranderen zolang u nog geen toegewezen project hebt gekregen</p>
        <p><span class="requireFields">Gelieve 3 verschillende keuzes te maken!</span></p>
        <form id="formulier" action="" method="post" enctype="multipart/form-data">
            <label for="keuze1">Project keuze 1:</label>
            {PROJECTKEUZE1} {MSGKEUZE1}<br/>

            <label for="keuze2">Project keuze 2:</label>
            {PROJECTKEUZE2} {MSGKEUZE2}<br/>

            <label for="keuze3">Project keuze 3:</label>
            {PROJECTKEUZE3} {MSGKEUZE3}<br/>

            <input type ="submit" name="btnSendKeuzeB2D" value="Keuzes indienen"/>
        </form>
    </div>
<!-- END CONTENTBLOCK -->