<!-- BEGIN CONTENTBLOCK -->
    <div id="breadcrumb"><p>U bent hier: <span id="breadcrumbLocatie">Jaarwerk - Keuze maken</span></p></div>
    <div id="inhoudBody">
        <p>De keuze die u hier maakt is en blijft dezelfde keuze! Dus doe dit correct!</p>
        <p><span class="requireFields">Gelieve de volgende gegevens allemaal in te vullen! Indien u geen groepsleden hebt in de droplist neem dan contact op met de leerkracht</span></p>
        <form id="formulier" action="" method="post" enctype="multipart/form-data">
            <p>Groepslid 1: {GEBRUIKER}</p>

            <label for="groepsleden2">Groepslid 2:</label>
            {DROPDOWNGROEPSLID2}<br/>

            <label for="groepsleden3">Groepslid 3:</label>
            {DROPDOWNGROEPSLID3}{MSGGROEPSLEDEN}<br/>

            <label for="titel">Titel:</label>
            <input type="text" id="titel" name="titel" value="{VALTITEL}"/>{MSGTITEL}<br/>

            <label for="onderwerp">Onderwerp:</label>
            <input type="text" id="onderwerp" name ="onderwerp" value="{VALONDERWERP}"/>{MSGONDERWERP}<br/>

            <label for="beschrijving">Beschrijving - Leg goed uit waarover jullie het willen hebben. Welke 5 vragen willen jullie beantwoorden?:</label>{MSGBESCHRIJVING}<br/>
            <textarea id="beschrijving" name="beschrijving" cols="140" rows="8" title="Beschrijving: Leg goed uit waarover jullie het willen hebben. Welke 5 vragen willen jullie beantwoorden?">{VALBESCH}</textarea><br/>

            <label for="ervaringsdeskundige">Ervaringsdeskundige - Wat soort persoon zoeken jullie? Welke concrete voorstellen heb je al?:</label>{MSGERVARINGSDESKUNDIGE}<br/>
            <textarea id="ervaringsdeskundige" name="ervaringsdeskundige" cols="140" rows="8" title="Ervaringsdeskundige - Wat soort persoon zoeken jullie? Welke concrete voorstellen heb je al?">{VALERVARING}</textarea><br/>

            <label for="voorstelling">Manier van voorstellen - Noteer de bevindingen die je al hebt voor een creatieve voorstelling:</label>{MSGVOORSTELLING}<br/>
            <textarea id="voorstelling" name="voorstelling" cols="140" rows="8" title="Voorstelling - Noteer de bevindingen die je al hebt voor een creatieve voorstelling">{VALVOORSTELLING}</textarea><br/>

            <label for="periode">Periode:</label>
            <select name="periode" id="periode">
                <option value="geen voorkeur">Geen voorkeur</option>
                <option value="januari">Januari</option>
                <option value="februari">Februari</option>
                <option value="maart">Maart</option>
                <option value="april">April</option>
                <option value="mei">Mei</option>
            </select><br/>

            <input type ="submit" name="btnSendJW" value="Jaarwerk indienen"/>
        </form>
    </div>
<!-- END CONTENTBLOCK -->