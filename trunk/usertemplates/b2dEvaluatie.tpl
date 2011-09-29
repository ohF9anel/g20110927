<!-- BEGIN CONTENTBLOCK -->

    <div id="breadcrumb"><p>U bent hier: <span id="breadcrumbLocatie">Bezinnings2daagse - Evaluatie</span></p></div>
    <div id="inhoudBody">
        <p>Jouw project: {PROJECT}</p>
        <p>Wat je hier als evaluatie schrijft kunnen de leerlingen (de volgende jaren) lezen. Hiermee help je ze op een
           zinvolle manier te kiezen voor het project en geef je meer informatie over het project zelf.
           Je evaluatie wordt in een brief naar het project gestuurd. Verzorg dus je taalgebruik en controleer op spellingsfouten!
        </p>
        <form id="formulier" action="" method="post" enctype="multipart/form-data">
            <label for="evaluatie">Evaluatie: {MSGEVALUATIE}</label><br/>
            <textarea id="evaluatie" name="evaluatie" cols="140" rows="7" title="Evaluatie">{EVALUATIE}</textarea>

            <input type ="submit" name="btnSendEvalB2D" value="Evaluatie indienen"/>
        </form>

        <p>Hieronder kan je andere/relevante informatie ingeven over je ervaringen. Deze informatie zal niet doorgestuurd worden naar het project.</p>
        <p><span class="requireFields">Vul zeker een e-mailadres in!</span></p>
        <form id="formulier2" action="" method="post" enctype="multipart/form-data">
            <label for="email">Email-adres*:</label>
            <input type="text" id="email" name="email" value="{VALEMAIL}"/>{MSGEMAIL}<br/>

            <label for="info">Andere informatie*: {MSGANDEREINFO}</label><br/>
            <textarea id="info" name="info" cols="140" rows="7" title="Andere informatie"></textarea>

            <input type ="submit" name="btnSendInfoMail" value="Mail versturen"/>
        </form>
    </div>
<!-- END CONTENTBLOCK -->