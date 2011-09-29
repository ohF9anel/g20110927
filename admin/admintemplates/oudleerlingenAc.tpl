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
{form:oudleerlingen}
    <label for="LeerlingId">Leerling*:</label>
    <p>
        {$ddmLeerlingId}
        {$ddmLeerlingIdError}
    </p>
    <label for="DbfRichting">Dbf richtingen:</label>
    <p>
        {$ddmDbfRichting}
        {$ddmDbfRichtingError}
    </p>
    <label for="RichtingDetails">Richting details*:</label>
    <p>
        {$txtRichtingDetails}
        {$txtRichtingDetailsError}
    </p>
    <label for="Scho">School*:</label>
    <p>
        {$txtScho}
        {$txtSchoError}
    </p>
    <p>{$btnSubmit}</p>
    <a href="oudleerlingen.php"><input type="button" value="Annuleer"/></a>
{/form:oudleerlingen}
</body>
    </html>