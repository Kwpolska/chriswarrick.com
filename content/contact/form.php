---
extends: base-cform.j2
default_block: main
title: Contact Form
description: Want to talk to me?  Go for it.  Here’s the right form.
---
<form action="/contact/send.php" method="post" id="form">
    <article>
        <header><label for="name">Name</label><input id="name" name="name" required class="required"><br><label for="mail">Mail address</label><input id="mail" name="mail" type="email" required class="email required"><br>
            <input name="subject" id="subject" required class="required" style="font-weight: bold; font-family: 'Play',sans-serif; font-size: 2em;">
        </header>
        <div class="text clearfix"><textarea name="message" id="message" rows="24" required class="required" style="width:100%;"></textarea></div>
        <footer>
            <label for="abq"><code style="border: none;"><?php echo $_SESSION['let']; ?></code> is the</label> <input id="abq" name="abq" required class="required" style="width: 25px;"> letter of the alphabet.*<br>
            <input type="submit" value="Submit" style="float: right;" id="submit">
            <em>Your IP address will be saved.  All fields are required.<br>* please insert an ordinal number, eg. "first".</em>
        </footer>
    </article>
</form>

