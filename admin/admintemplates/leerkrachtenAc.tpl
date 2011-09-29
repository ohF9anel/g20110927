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
{form:leerkrachten}
    <p>Leerkracht: {$naamLeerkracht}</p>
    <label for="Str">Straat:</label>
    <p>
        {$txtStr}
    </p>
    <label for="Hn">Huisnummer:</label>
    <p>
        {$txtHn}
    </p>
    <label for="Po">Postcode:</label>
    <p>
        {$txtPo}
    </p>
    <label for="Bu">Bus:</label>
    <p>
        {$txtBu}
    </p>
    <label for="Gem">Gemeente:</label>
    <p>
        {$txtGem}
    </p>
    <label for="Tel">Telefoon:</label>
    <p>
        {$txtTel}
        {$txtTelError}
    </p>
    <label for="Gs">GSM:</label>
    <p>
        {$txtGs}
        {$txtGsError}
    </p>
    <label for="Geb">Geboortedatum (yyyy-mm-dd):</label>
    <p>
        {$txtGeb}
        {$txtGebError}
    </p>
    <label for="email">Email:</label>
    <p>
        {$txtEmail}
        {$txtEmailError}
    </p>
    <label for="functie">Functie:</label>
    <p>
        {$ddmFunctie}
    </p>
    <label for="pasfoto">pasfoto:</label>
    <p>
        {$filePasfoto}
        {$filePasfotoError}
    </p>

    <p>{$btnSubmit}</p>
    <a href="leerkrachten.php"><input type="button" value="Annuleer"/></a>
{/form:leerkrachten}
</body>
</html>