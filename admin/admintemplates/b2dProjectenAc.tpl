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
{form:b2dProjecten}
    <label for="NaamProject">Naam project*:</label>
    <p>
        {$txtNaamProject}
        {$txtNaamProjectError}
    </p>
    <label for="ProjectSoort">Project Soort*:</label>
    <p>
        {$ddmProjectSoort}
        {$ddmProjectSoortError}
    </p>
    <label for="AantalDeeln">Aantal deelnemers:</label>
    <p>
        {$txtAantalDeeln}
        {$txtAantalDeelnError}
    </p>
    <label for="Str">Straat:</label>
    <p>
        {$txtStr}
        {$txtStrError}
    </p>
    <label for="Zi">Zip:</label>
    <p>
        {$txtZi}
        {$txtZiError}
    </p>
    <label for="Gem">Gemeente:</label>
    <p>
        {$txtGem}
        {$txtGemError}
    </p>
    <label for="Tel">telefoon:</label>
    <p>
        {$txtTel}
        {$txtTelError}
    </p>
    <label for="Fa">Fax:</label>
    <p>
        {$txtFa}
        {$txtFaError}
    </p>
    <label for="Email">E-mail:</label>
    <p>
        {$txtEmail}
        {$txtEmailError}
    </p>
    <label for="Verantw">Verantwoordelijke:</label>
    <p>
        {$txtVerantw}
        {$txtVerantwError}
    </p>
    <label for="Omsch">Omschrijving:</label>
    <p>
        {$txtOmsch}
        {$txtOmschError}
    </p>
    <p>
        Afgevoerd:<br />
        {iteration:Afgev}
                <label for="{$Afgev.id}">{$Afgev.rbtAfgev} {$Afgev.label}</label>
        {/iteration:Afgev}
    </p>
    <label for="TerAtt">Ter attentie van:</label>
    <p>
        {$txtTerAtt}
        {$txtTerAttError}
    </p>
    <label for="EvalVraag">Evaluatie vraag:</label>
    <p>
        {$txtEvalVraag}
        {$txtEvalVraagError}
    </p>

    <p>{$btnSubmit}</p>
    <a href="b2dProjecten.php"><input type="button" value="Annuleer"/></a>
{/form:b2dProjecten}
</body>
</html>