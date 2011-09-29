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
    {form:klasKlasgroep}
        <p>Leerling: {$Leerling}</p>
        <p>Klas naam: {$Klasnaam} - {$Schooljaar}</p>
        <p>
        <label for="Klasgroep">Klasgroep naam:</label>
            {$ddmKlasgroep}
            {$ddmKlasgroepError}
        </p>
        <p>{$btnSubmit}</p>
        <a href="klasKlasgroep.php"><input type="button" value="Annuleer"/></a>
    {/form:klasKlasgroep}
</body>
</html>