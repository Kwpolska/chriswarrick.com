---
extends: base-cform.j2
default_block: main
title: Contact Form
description: Want to talk to me?  Go for it.  Here’s the right form.
---
<form action="/contact/send.php" method="post" id="form">
    <div style="text-align: center;"><textarea name="message" id="message" rows="24" cols="70" required class="required"></textarea></div>
    <label for="mail">Mail address</label><input id="mail" name="mail" type="email" required class="email required"><br>
    <label for="name">Name</label><input id="name" name="name" required class="required"><br>
    <label for="subject">Subject</label><input name="subject" id="subject" required class="required"><br>
    <label for="abq"><code style="border: none;"><?php echo $_SESSION['let']; ?></code> is the*</label><input id="abq" name="abq" required class="required"><br>letter of the alphabet.<br>
    <input type="submit" value="Submit" style="float: right;" id="submit">
    <em>Your IP address will be saved.  All fields are required.<br>* please insert an ordinal number, eg. "first".</em>
</form>

