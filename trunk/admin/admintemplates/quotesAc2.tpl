<!-- BEGIN CONTENTBLOCK -->
    <div id="inhoudBody">
        {JAVASCRIPTDEL}
        <form id="formulier" action="" method="post" enctype="multipart/form-data">
            <label for="quote">Quote*: {MSGQUOTE}</label><br/>
            <textarea id="quote" name="quote" cols="140" rows="7" title="quote tekst">{QUOTETEKST}</textarea><br/>

            <input type ="submit" name="btnQuote" value="Verstuur quote"/>
            <a href="quotes.php"><input type="button" value="Annuleer"/></a>
        </form>
    </div>
<!-- END CONTENTBLOCK -->