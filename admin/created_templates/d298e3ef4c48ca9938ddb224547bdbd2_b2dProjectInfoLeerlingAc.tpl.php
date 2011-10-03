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
<form action="<?php echo $this->forms['b2dProjectInfoLLN']->getAction(); ?>" method="<?php echo $this->forms['b2dProjectInfoLLN']->getMethod(); ?>"<?php echo $this->forms['b2dProjectInfoLLN']->getParametersHTML(); ?>>
<div>
<input type="hidden" value="b2dProjectInfoLLN" id="form" name="form" />
    <p>Leerling: <?php echo $this->variables['naamLeerling']; ?></p>
    <label for="ToegewezenProject">Toegewezen project:</label>
    <p>
        <?php echo $this->variables['ddmToegewezenProject']; ?>
        <?php echo $this->variables['ddmToegewezenProjectError']; ?>
    </p>
    <p>
        Aanvraag ok:<br />
        <?php $AanvraagOkI = 1; ?>
<?php $AanvraagOkCount = count($this->variables['AanvraagOk']); ?>
<?php foreach((array) $this->variables['AanvraagOk'] as $AanvraagOk): ?>
<?php
						if(!isset($AanvraagOk['first']) && $AanvraagOkI == 1) $AanvraagOk['first'] = true;
						if(!isset($AanvraagOk['last']) && $AanvraagOkI == $AanvraagOkCount) $AanvraagOk['last'] = true;
						if(isset($AanvraagOk['formElements']) && is_array($AanvraagOk['formElements']))
						{
							foreach($AanvraagOk['formElements'] as $name => $object)
							{
								$AanvraagOk[$name] = $object->parse();
								$AanvraagOk[$name .'Error'] = (method_exists($object, 'getErrors') && $object->getErrors() != '') ? '<span class="formError">'. $object->getErrors() .'</span>' : '';
							}
						}
						?>
                <label for="<?php echo $AanvraagOk['id']; ?>"><?php echo $AanvraagOk['rbtAanvraagOk']; ?> <?php echo $AanvraagOk['label']; ?></label>
        <?php $AanvraagOkI++; ?>
<?php endforeach; ?>
    </p>
    <p>
        Handtekening ouders:<br />
        <?php $HandTekeningOudersI = 1; ?>
<?php $HandTekeningOudersCount = count($this->variables['HandTekeningOuders']); ?>
<?php foreach((array) $this->variables['HandTekeningOuders'] as $HandTekeningOuders): ?>
<?php
						if(!isset($HandTekeningOuders['first']) && $HandTekeningOudersI == 1) $HandTekeningOuders['first'] = true;
						if(!isset($HandTekeningOuders['last']) && $HandTekeningOudersI == $HandTekeningOudersCount) $HandTekeningOuders['last'] = true;
						if(isset($HandTekeningOuders['formElements']) && is_array($HandTekeningOuders['formElements']))
						{
							foreach($HandTekeningOuders['formElements'] as $name => $object)
							{
								$HandTekeningOuders[$name] = $object->parse();
								$HandTekeningOuders[$name .'Error'] = (method_exists($object, 'getErrors') && $object->getErrors() != '') ? '<span class="formError">'. $object->getErrors() .'</span>' : '';
							}
						}
						?>
                <label for="<?php echo $HandTekeningOuders['id']; ?>"><?php echo $HandTekeningOuders['rbtHandTekeningOuders']; ?> <?php echo $HandTekeningOuders['label']; ?></label>
        <?php $HandTekeningOudersI++; ?>
<?php endforeach; ?>
    </p>
    <p>
        Aanwezigheid:<br />
        <?php $AanwezigheidI = 1; ?>
<?php $AanwezigheidCount = count($this->variables['Aanwezigheid']); ?>
<?php foreach((array) $this->variables['Aanwezigheid'] as $Aanwezigheid): ?>
<?php
						if(!isset($Aanwezigheid['first']) && $AanwezigheidI == 1) $Aanwezigheid['first'] = true;
						if(!isset($Aanwezigheid['last']) && $AanwezigheidI == $AanwezigheidCount) $Aanwezigheid['last'] = true;
						if(isset($Aanwezigheid['formElements']) && is_array($Aanwezigheid['formElements']))
						{
							foreach($Aanwezigheid['formElements'] as $name => $object)
							{
								$Aanwezigheid[$name] = $object->parse();
								$Aanwezigheid[$name .'Error'] = (method_exists($object, 'getErrors') && $object->getErrors() != '') ? '<span class="formError">'. $object->getErrors() .'</span>' : '';
							}
						}
						?>
                <label for="<?php echo $Aanwezigheid['id']; ?>"><?php echo $Aanwezigheid['rbtAanwezigheid']; ?> <?php echo $Aanwezigheid['label']; ?></label>
        <?php $AanwezigheidI++; ?>
<?php endforeach; ?>
    </p>
    <label for="EvalTekst1">Evaluatie tekst 1:</label>
    <p>
        <?php echo $this->variables['txtEvalTekst1']; ?>
    </p>
    <label for="EvalTekst2">Evaluatie tekst 2:</label>
    <p>
        <?php echo $this->variables['txtEvalTekst2']; ?>
    </p>
    <label for="EvalPunten">Evaluatie punten:</label>
    <p>
        <?php echo $this->variables['txtEvalPunten']; ?>
        <?php echo $this->variables['txtEvalPuntenError']; ?>
    </p>
    <p>
        Evaluatie afgegeven:<br />
        <?php $EvalAfgegevenI = 1; ?>
<?php $EvalAfgegevenCount = count($this->variables['EvalAfgegeven']); ?>
<?php foreach((array) $this->variables['EvalAfgegeven'] as $EvalAfgegeven): ?>
<?php
						if(!isset($EvalAfgegeven['first']) && $EvalAfgegevenI == 1) $EvalAfgegeven['first'] = true;
						if(!isset($EvalAfgegeven['last']) && $EvalAfgegevenI == $EvalAfgegevenCount) $EvalAfgegeven['last'] = true;
						if(isset($EvalAfgegeven['formElements']) && is_array($EvalAfgegeven['formElements']))
						{
							foreach($EvalAfgegeven['formElements'] as $name => $object)
							{
								$EvalAfgegeven[$name] = $object->parse();
								$EvalAfgegeven[$name .'Error'] = (method_exists($object, 'getErrors') && $object->getErrors() != '') ? '<span class="formError">'. $object->getErrors() .'</span>' : '';
							}
						}
						?>
                <label for="<?php echo $EvalAfgegeven['id']; ?>"><?php echo $EvalAfgegeven['rbtEvalAfgegeven']; ?> <?php echo $EvalAfgegeven['label']; ?></label>
        <?php $EvalAfgegevenI++; ?>
<?php endforeach; ?>
    </p>

    <p><?php echo $this->variables['btnSubmit']; ?></p>
    <a href="b2dProjectInfoLeerlingen.php"><input type="button" value="Annuleer"/></a>

</div>
</form>
    </body>
</html>