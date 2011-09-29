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
{form:donBoscoFeest}
    <p>Leerling: {$naamLeerling}</p>
    <label for="RichtingId">dbf_richting_id:</label>
    <p>
        {$ddmRichtingId}
    </p>
    <label for="SprekerId">dbf_spreker_id:</label>
    <p>
        {$ddmSprekerId}
    </p>
    <label for="DbFilm3">dbFilm3:</label>
    <p>
        {$txtDbFilm3}
        {$txtDbFilm3Error}
    </p>
    <label for="DbFilm4">dbFilm4:</label>
    <p>
        {$txtDbFilm4}
        {$txtDbFilm4Error}
    </p>
    <label for="DbFilm5">dbFilm5:</label>
    <p>
        {$txtDbFilm5}
        {$txtDbFilm5Error}
    </p>
    <p>{$btnSubmit}</p>
    <a href="donBoscoFeest.php"><input type="button" value="Annuleer"/></a>
{/form:donBoscoFeest}
</body>
</html>