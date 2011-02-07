<form action="/contact/send.php" id="myform" onsubmit="return false">
<fieldset>
<legend>Kontakt</legend>
<div><input type="submit" value="Wyślij" style="float: left;" onclick="Modalbox.show('http://kwpolska.co.cc/contact/send.php', {title: 'Sending...', params: Form.serialize('myform'), width: 600 }); return false;"><input type="button" value="Wróć" onclick="Modalbox.show('http://kwpolska.co.cc/polish/kontakt/', {title: 'Kontakt', width: 600}); return false;"</div>
<div><label for="mail">Adr. pocztowy:</label> <input id="mail" name="mail"></div>
<div><label for="name">Imię:</label> <input id="name" name="name"></div>
<div><label for="subject">Temat:</label> <input name="subject" id="subject"></div>
<div style="text-align: center;"><textarea name="message" id="message" rows="24" cols="70"></textarea></div>
<div><label for="captchainput">CAPTCHA:</label> <input name="captcha" id="captchainput"></div>
<div id="captchaimg"><label>CAPTCHA image:</label> <img src="http://kwpolska.co.cc/captcha.php?action=img"></div>
<div><em>Twój adres IP zostanie zapisany.</em></div>
<div><input type="submit" value="Wyślij" style="float: left;" onclick="Modalbox.show('http://kwpolska.co.cc/contact/send.php', {title: 'Sending...', params: Form.serialize('myform'), width: 600 }); return false;"><input type="button" value="Wróć" onclick="Modalbox.show('http://kwpolska.co.cc/polish/kontakt/', {title: 'Kontakt', width: 600}); return false;"</div>
</fieldset>
</form>
