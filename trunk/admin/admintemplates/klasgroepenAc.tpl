<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>
      Godsdienst - Zesdes - beheerders
    </title>
    <meta http-equiv="content-type" content=
    "application/xhtml+xml; charset=utf-8" />
    <meta name="description" content="Site Description Here" />
    <meta name="keywords" content="keywords, here" />
    <meta name="robots" content="index, follow, noarchive" />
    <meta name="googlebot" content="noarchive" />

    <link rel="stylesheet" href="styles/styleF0FAdm.css" type=
    "text/css" media="screen" />
  </head>
  <body>
    {form:klasgroepen}
    <p>
      Het schooljaar wordt automatisch toegekend
    </p>
    <p>
      <label for="klasgroep_naam">Klasgroep naam*:</label>

      {$txtKlasgroepNaam} {$txtKlasgroepNaamError}
    </p><label for="leerkracht">Leerkracht*:</label>
    <p>
      {$ddmLeerkracht} {$ddmLeerkrachtError}
    </p>
    <p>
      {$btnSubmit}
    </p><a href="klasgroepen.php"><input type="button" value=
    "Annuleer" /></a> {/form:klasgroepen}
  </body>
</html>