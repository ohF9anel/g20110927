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
{form:lokalen}
    <label for="LokaalNaam">Lokaal naam*:</label>
    <p>
        {$txtLokaalNaam}
        {$txtLokaalNaamError}
    </p>
    <label for="LokaalType">Lokaal type:</label>
    <p>
        {$txtLokaalType}
        {$txtLokaalTypeError}
    </p>
    <label for="LokaalPlaatsen">Lokaal aantal plaatsen:</label>
    <p>
        {$txtLokaalPlaatsen}
        {$txtLokaalPlaatsenError}
    </p>
    <p>
        Computer aanwezig:<br />
        {iteration:CompAanwezig}
                <label for="{$CompAanwezig.id}">{$CompAanwezig.rbtCompAanwezig} {$CompAanwezig.label}</label>
        {/iteration:CompAanwezig}
    </p>
    <p>
        Beamer aanwezig:<br />
        {iteration:BeamAanwezig}
                <label for="{$BeamAanwezig.id}">{$BeamAanwezig.rbtBeamAanwezig} {$BeamAanwezig.label}</label>
        {/iteration:BeamAanwezig}
    </p>
    <p>{$btnSubmit}</p>
    <a href="lokalen.php"><input type="button" value="Annuleer"/></a>
{/form:lokalen}
    </body>
    </html>