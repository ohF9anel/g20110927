<?php error_reporting(E_ALL | E_STRICT); ini_set('display_errors', 'On'); ?>
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
<form action="<?php echo $this->forms['b2dProjecten']->getAction(); ?>" method="<?php echo $this->forms['b2dProjecten']->getMethod(); ?>"<?php echo $this->forms['b2dProjecten']->getParametersHTML(); ?>>
<div>
<input type="hidden" value="b2dProjecten" id="form" name="form" />
    <label for="NaamProject">Naam project*:</label>
    <p>
        <?php echo $this->variables['txtNaamProject']; ?>
        <?php echo $this->variables['txtNaamProjectError']; ?>
    </p>
    <label for="ProjectSoort">Project Soort*:</label>
    <p>
        <?php echo $this->variables['ddmProjectSoort']; ?>
        <?php echo $this->variables['ddmProjectSoortError']; ?>
    </p>
    <label for="AantalDeeln">Aantal deelnemers:</label>
    <p>
        <?php echo $this->variables['txtAantalDeeln']; ?>
        <?php echo $this->variables['txtAantalDeelnError']; ?>
    </p>
    <label for="Str">Straat:</label>
    <p>
        <?php echo $this->variables['txtStr']; ?>
        <?php echo $this->variables['txtStrError']; ?>
    </p>
    <label for="Zi">Zip:</label>
    <p>
        <?php echo $this->variables['txtZi']; ?>
        <?php echo $this->variables['txtZiError']; ?>
    </p>
    <label for="Gem">Gemeente:</label>
    <p>
        <?php echo $this->variables['txtGem']; ?>
        <?php echo $this->variables['txtGemError']; ?>
    </p>
    <label for="Tel">telefoon:</label>
    <p>
        <?php echo $this->variables['txtTel']; ?>
        <?php echo $this->variables['txtTelError']; ?>
    </p>
    <label for="Fa">Fax:</label>
    <p>
        <?php echo $this->variables['txtFa']; ?>
        <?php echo $this->variables['txtFaError']; ?>
    </p>
    <label for="Email">E-mail:</label>
    <p>
        <?php echo $this->variables['txtEmail']; ?>
        <?php echo $this->variables['txtEmailError']; ?>
    </p>
    <label for="Verantw">Verantwoordelijke:</label>
    <p>
        <?php echo $this->variables['txtVerantw']; ?>
        <?php echo $this->variables['txtVerantwError']; ?>
    </p>
    <label for="Omsch">Omschrijving:</label>
    <p>
        <?php echo $this->variables['txtOmsch']; ?>
        <?php echo $this->variables['txtOmschError']; ?>
    </p>
    <p>
        Afgevoerd:<br />
        <?php $AfgevI = 1; ?>
<?php $AfgevCount = count($this->variables['Afgev']); ?>
<?php foreach((array) $this->variables['Afgev'] as $Afgev): ?>
<?php
						if(!isset($Afgev['first']) && $AfgevI == 1) $Afgev['first'] = true;
						if(!isset($Afgev['last']) && $AfgevI == $AfgevCount) $Afgev['last'] = true;
						if(isset($Afgev['formElements']) && is_array($Afgev['formElements']))
						{
							foreach($Afgev['formElements'] as $name => $object)
							{
								$Afgev[$name] = $object->parse();
								$Afgev[$name .'Error'] = (method_exists($object, 'getErrors') && $object->getErrors() != '') ? '<span class="formError">'. $object->getErrors() .'</span>' : '';
							}
						}
						?>
                <label for="<?php echo $Afgev['id']; ?>"><?php echo $Afgev['rbtAfgev']; ?> <?php echo $Afgev['label']; ?></label>
        <?php $AfgevI++; ?>
<?php endforeach; ?>
    </p>
    <label for="TerAtt">Ter attentie van:</label>
    <p>
        <?php echo $this->variables['txtTerAtt']; ?>
        <?php echo $this->variables['txtTerAttError']; ?>
    </p>
    <label for="EvalVraag">Evaluatie vraag:</label>
    <p>
        <?php echo $this->variables['txtEvalVraag']; ?>
        <?php echo $this->variables['txtEvalVraagError']; ?>
    </p>

    <p><?php echo $this->variables['btnSubmit']; ?></p>
    <a href="b2dProjecten.php"><input type="button" value="Annuleer"/></a>

</div>
</form>
</body>
</html>