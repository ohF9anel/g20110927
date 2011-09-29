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
{form:100Dagen}
    <p>Leerling: {$Leerling}</p>
    <label for="HonderdDagen">100 dagen:</label>
    <p>
        {$txtHonderdDagen}
    </p>
    <label for="HonderdDagenComm">100 dagen commentaar:</label>
    <p>
        {$txtHonderdDagenComm}
    </p>
    <p>
        Amnesty International:<br />
        {iteration:AINiet}
                <label for="{$AINiet.id}">{$AINiet.rbtAINiet} {$AINiet.label}</label>
        {/iteration:AINiet}
    </p>
    <label for="Hd01">hd01:</label>
    <p>
        {$txtHd01}
    </p>
    <label for="Hd02">hd02:</label>
    <p>
        {$txtHd02}
    </p>
    <label for="Hd03">hd03:</label>
    <p>
        {$txtHd03}
    </p>
    <label for="Hd04">hd04:</label>
    <p>
        {$txtHd04}
    </p>
    <label for="Hd05">hd05:</label>
    <p>
        {$txtHd05}
    </p>
    <label for="Hd06">hd06:</label>
    <p>
        {$txtHd06}
    </p>
    <label for="Hd07">hd07:</label>
    <p>
        {$txtHd07}
    </p>
    <label for="Hd08">hd08:</label>
    <p>
        {$txtHd08}
    </p>
    <label for="Hd09">hd09:</label>
    <p>
        {$txtHd09}
    </p>
    <label for="Hd10">hd10:</label>
    <p>
        {$txtHd10}
    </p>
    <p>{$btnSubmit}</p>
    <a href="honderdDagen.php"><input type="button" value="Annuleer"/></a>
{/form:100Dagen}
</body>
</html>