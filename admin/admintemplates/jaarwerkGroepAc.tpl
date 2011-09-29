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
{form:jaarwerkGroep}
    <p>Jaarwerk groep: {$jaarwerkGroep}</p>
    <p>Leerling(en): {$naamLeerling}</p>
    <label for="Bedenk">Bedenkingen:</label>
    <p>
        {$txtBedenk}
        {$txtBedenkError}
    </p>
    <label for="InvoerDat">Invoerdatum (yyyy-mm-dd):</label>
    <p>
        {$txtInvoerDat}
        {$txtInvoerDatError}
    </p>
    <label for="EvalInhoud">Evaluatie inhoud:</label>
    <p>
        {$txtEvalInhoud}
        {$txtEvalInhoudError}
    </p>
    <label for="EvalVorm">Evaluatie vorm:</label>
    <p>
        {$txtEvalVorm}
        {$txtEvalVormError}
    </p>
    <label for="EvalInterview">Evaluatie interview:</label>
    <p>
        {$txtEvalInterview}
        {$txtEvalInterviewError}
    </p>
    <label for="EvalAVier">Evaluatie A4:</label>
    <p>
        {$txtEvalAVier}
        {$txtEvalAVierError}
    </p>
    <label for="Evalu">Evaluatie:</label>
    <p>
        {$txtEvalu}
        {$txtEvaluError}
    </p>

    <p>{$btnSubmit}</p>
    <a href="jaarwerkGroep.php"><input type="button" value="Annuleer"/></a>
{/form:jaarwerkGroep}
    </body>
    </html>