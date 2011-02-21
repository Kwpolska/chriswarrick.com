<form action="/contact/send.php" id="myform" onsubmit="return false">
<fieldset>
<legend>Contact</legend>
<div><input type="submit" value="Submit" style="float: left;" onclick="Modalbox.show('http://kwpolska.co.cc/contact/send.php', {title: 'Sending...', params: Form.serialize('myform'), width: 600 }); return false;"><input type="button" value="Back" onclick="Modalbox.show('http://kwpolska.co.cc/contact/', {title: 'Contact', width: 600}); return false;"></div>
<div><label for="mail">Mail addr.:</label> <input id="mail" name="mail" type="email"></div>
<div><label for="name">Name:</label> <input id="name" name="name"></div>
<div><label for="subject">Subject:</label> <input name="subject" id="subject"></div>
<div style="text-align: center;"><textarea name="message" id="message" rows="24" cols="70"></textarea></div>
<div><label for="captchainput">CAPTCHA:</label> <input name="captcha" id="captchainput"></div>
<div id="captchaimg"><label>CAPTCHA image:</label> <img src="http://kwpolska.co.cc/captcha.php?action=img"> (<a href="http://kwpolska.co.cc/captcha.php?action=text">No image support? -- open in a new tab</a>)</div>
<div><em>Your IP address will be saved.</em></div>
<div><input type="submit" value="Submit" style="float: left;" onclick="Modalbox.show('http://kwpolska.co.cc/contact/send.php', {title: 'Sending...', params: Form.serialize('myform'), width: 600 }); return false;"><input type="button" value="Back" onclick="Modalbox.show('http://kwpolska.co.cc/contact/', {title: 'Contact', width: 600}); return false;"</div>
</fieldset>
</form>
