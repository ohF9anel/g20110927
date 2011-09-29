<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Godsdienst - Zesdes - beheerders</title>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
    <meta name="description" content="Site Description Here" />
    <meta name="keywords" content="keywords, here" />
    <meta name="robots" content="index, follow, noarchive" />
    <meta name="googlebot" content="noarchive" />

    <link rel="stylesheet" href="styles/styleFFAdm.css" type="text/css" media="screen" />
</head>
<body>
{form:b2dProjectInfoLLN}
    <p>Leerling: {$naamLeerling}</p>
    <label for="ToegewezenProject">Toegewezen project:</label>
    <p>
        {$ddmToegewezenProject}
        {$ddmToegewezenProjectError}
    </p>
    <p>
        Aanvraag ok:<br />
        {iteration:AanvraagOk}
                <label for="{$AanvraagOk.id}">{$AanvraagOk.rbtAanvraagOk} {$AanvraagOk.label}</label>
        {/iteration:AanvraagOk}
    </p>
    <p>
        Handtekening ouders:<br />
        {iteration:HandTekeningOuders}
                <label for="{$HandTekeningOuders.id}">{$HandTekeningOuders.rbtHandTekeningOuders} {$HandTekeningOuders.label}</label>
        {/iteration:HandTekeningOuders}
    </p>
    <p>
        Aanwezigheid:<br />
        {iteration:Aanwezigheid}
                <label for="{$Aanwezigheid.id}">{$Aanwezigheid.rbtAanwezigheid} {$Aanwezigheid.label}</label>
        {/iteration:Aanwezigheid}
    </p>
    <label for="EvalTekst1">Evaluatie tekst 1:</label>
    <p>
        {$txtEvalTekst1}
    </p>
    <label for="EvalTekst2">Evaluatie tekst 2:</label>
    <p>
        {$txtEvalTekst2}
    </p>
    <label for="EvalPunten">Evaluatie punten:</label>
    <p>
        {$txtEvalPunten}
        {$txtEvalPuntenError}
    </p>
    <p>
        Evaluatie afgegeven:<br />
        {iteration:EvalAfgegeven}
                <label for="{$EvalAfgegeven.id}">{$EvalAfgegeven.rbtEvalAfgegeven} {$EvalAfgegeven.label}</label>
        {/iteration:EvalAfgegeven}
    </p>

    <p>{$btnSubmit}</p>
    <a href="b2dProjectInfoLeerlingen.php"><input type="button" value="Annuleer"/></a>
{/form:b2dProjectInfoLLN}
    </body>
</html>