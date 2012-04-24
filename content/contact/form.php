---
extends: base-cform.j2
default_block: main
title: Contact Form
description: Want to talk to me?  Go for it.  Here’s the right form.
---
<style>label {display: inline;}</style>
<form action="/contact/send.php" method="post" id="form">
    <article>
        <header><label for="name">From <label for="mail">&lt;mail&gt;</label>: <input id="name" name="name" required class="required" style="width: 40%;"> &lt;<input id="mail" name="mail" type="email" required class="email required" style="width: 40%;">&gt;<br>
            <input name="subject" id="subject" required class="required" style="font-weight: bold; font-family: 'Play',sans-serif; font-size: 2em;">
        </header>
        <div class="text clearfix" style="padding: 0;"><textarea name="message" id="message" rows="24" required class="required" style="border: none; width: 100%; height: 100%;"></textarea></div>
        <footer style="height: auto;">
            <label for="vfbpq"><code style="border: none;"><?php echo $_SESSION['let']; ?></code> is the</label> <input id="vfbpq" name="vfbpq" required class="required" style="width: 75px;"> letter of the alphabet.*<br>
            <input type="hidden" name="vfbpjstoken" id="vfbpjstoken" value="<?php echo $_SESSION['vfbpjstoken'] ?>">
            <input type="submit" value="Submit" style="float: right;" id="submit">
            Your IP address will be saved.  All fields are required.<br>* please insert an ordinal number, eg. "first".<br>Anti-spam protection: field above or JS.  JS auth status:<br>
            <div id="vfbpjsresults">No JavaScript or page not loaded yet.</div>
        </footer>
    </article>
</form>