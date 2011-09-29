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
{form:b2dAssistentie}
    <label for="LeerkrachtId">Leerkracht*:</label>
    <p>
        {$ddmLeerkrachtId}
        {$ddmLeerkrachtIdError}
    </p>
    <label for="ProjectId">Project*:</label>
    <p>
        {$ddmProjectId}
        {$ddmProjectIdError}
    </p>
    <label for="AssistJaar">Assistentie jaar*:</label>
    <p>
        {$txtAssistJaar}
        {$txtAssistJaarError}
    </p>
    <p>{$btnSubmit}</p>
    <a href="b2dProjectAssistentie.php"><input type="button" value="Annuleer"/></a>
{/form:b2dAssistentie}
</body>
</html>