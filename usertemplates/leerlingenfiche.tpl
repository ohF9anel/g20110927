<!-- BEGIN HEADBLOCK -->
    <!-- obsolete volgens gb <script src="scripts/jquery-1.4.2.min.js" type="text/javascript"></script> -->
    <script src="scripts/jquery-ui-1.8.6.custom.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#geboortedatum").datepicker({ dateFormat: 'yy-mm-dd' , defaultDate: -(365*18) });
        });
    </script>
    <link href="styles/jquery-ui-1.8.6.custom.css" rel="stylesheet" type="text/css" />
<!-- END HEADBLOCK -->

<!-- BEGIN CONTENTBLOCK -->

    <div id="breadcrumb"><p>U bent hier: <span id="breadcrumbLocatie">Cursus - Leerlingenfiche</span></p></div>
    <div id="inhoudBody">
        <p>Dit zijn de gegevens waarover we momenteel beschikken.</p>
        <p>Indien deze niet meer van toepassing zijn kan u deze hier aanpassen.</p>
        <p>Zorg er zeker voor dat we beschikken over jouw GSM en/of e-mailadres.</p>
        <p>Deze gegevens kunnen van pas komen om info door te sturen.(ivm de tweedaagse of jaarwerk,...).</p>
        <p>Gelieve de velden met een <span class="requireFields">*</span> in te vullen!</p>
        <p>Naam: {NAAM}</p>
        <form id="formulier" action="" method="post" enctype="multipart/form-data">
            <label for="straat">Straat:<span class="requireFields">*</span></label>
            <input type="text" id="straat" name="straat" value="{STRAAT}"/>{MSGSTRAAT}<br/>

            <label for="hn">Huisnummer:<span class="requireFields">*</span></label>
            <input type="text" id="hn" name="hn" value="{HN}"/>{MSGHUISNUMMER}<br/>

            <label for="bus">Bus:</label>
            <input type="text" id="bus" name="bus" value="{BUS}"/>{MSGBUS}<br/>

            <label for="postcode">Postcode:<span class="requireFields">*</span></label>
            <input type="text" id="postcode" name="postcode" value="{POSTCODE}"/>{MSGPOSTCODE}<br/>

            <label for="gemeente">Gemeente:<span class="requireFields">*</span></label>
            <input type="text" id="gemeente" name="gemeente" value="{GEMEENTE}"/>{MSGGEMEENTE}<br/>

            <label for="telefoon">Telefoon:</label>
            <input type="text" id="telefoon" name="telefoon" value="{TELEFOON}"/>{MSGTELEFOON}<br/>

            <label for="gsm">GSM:</label>
            <input type="text" id="gsm" name="gsm" value="{GSM}"/>{MSGGSM}<br/>

            <label for="geboortedatum">Geboortedatum:</label>
            <input type="text" id="geboortedatum" name="geboortedatum" value="{GEBOORTEDATUM}"/>{MSGGEBOORTEDATUM}<br/>

            <label for="email">E-mailadres:</label>
            <input type="text" id="email" name="email" value="{EMAIL}"/>{MSGEMAIL}<br/>

            <input type ="submit" name="btnSendLeerlingenfiche" value="Gegevens aanpassen"/>
        </form>
    </div>
<!-- END CONTENTBLOCK -->