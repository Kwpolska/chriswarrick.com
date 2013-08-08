.. title: Password Changing 101
.. slug: password-changing-101
.. date: 2013/02/09 13:26:04
.. tags: Internet, password, rant
.. link: 
.. description: Why is password changing so difficult?

Yesterday, I finally created a new password for myself.  Now, changing
passwords all over the world is what I should do.  But it isn’t easy.

.. TEASER_END

One important thing: the new password is `XKCD 936`_ compatible.  It makes no
sense at all and is **32** characters (29 letters) long.  I kept myself within
ASCII, specifically 0x20 + 0x61…0x7A.  The previous password was **9**
characters long and a few (4 or 5) years old.

The Great List of Password Changes
==================================

After updating the most important passwords (Linux, Google, Twitter, Facebook,
GitHub, Reddit, Remember the Milk, Trello), I took an analog piece of paper and
wrote down all the places where I should change my password.  I wrote down
**26** different places.  We will go through them (including the above ones,
save for Linux.)

Password Update Wall of Fame
============================

In alphabetical order,

 * Arch User Repository
 * Codecademy
 * Dropbox*
 * Facebook*
 * GitHub
 * Google*
 * Heroku
 * IFTTT
 * Khan Academy
 * MediaWiki (Wikipedia etc.)
 * Reddit
 * Remember the Milk
 * Twitter*

All of those make it easy to change your password.  Just go to the profile
page, find the appropriate fields, fill them (sometimes your previous password
is needed, sometimes it isn’t, that’s generally fine) and kaboom, your password
is changed.

Services marked with an asterisk sent me an e-mail confirmation, which is even
better.

Password Update Wall of Amnesia
===============================

I had to simulate amnesia for those services.  I couldn’t find a Password reset
functionality or it was broken.

DISQUS
    DISQUS has a nice password update link.  One problem, it doesn’t work.
    After simulating amnesia, all is well.
TweetDeck
    Couldn’t find a valid option for what I needed, so I simulated amnesia and
    it worked just fine.
Mailman
    Some passwords were my previous password, others were my unsafe passwords,
    others weren’t my password at all.  All were changed to the unsafe
    passwords, sometimes with a need to simulate amnesia.

Password Update Wall of BULLSHIT
================================

Those services are SHIT in terms of password reset!  I have absolutely no idea
why I have an account with them!

AOL/AIM
    It wanted a CAPTCHA, link click and other magic.  Moreover, passwords are
    limited to 16 characters.  Enough to type half of my password, at least,
    but this is still bad!  The proper upper limit is NONE.
Gadu-Gadu
    A Polish IM.  It required amnesia, a difficult CAPTCHA, and when I typed my
    password, it rejected it without any meaningful error messages (an
    exclamation mark isn’t considered a valid error message.)
Microsoft
    Sixteen characters, no spaces, requires fancy characters.  I did not change
    my password, because it won’t let me.  Oh, and aren’t those passwords used
    for Windows 8, making it even less secure, considering that `passwords are
    saved using a reversible encryption method if you use the fancy new
    authentication methods (PIN/Picture Password) <http://arstechnica.com/security/2012/10/experts-windows-8-features-make-account-passwords-easier-to-steal/>`!
PayPal
    I trust them with my money, but they believe that my password is weak (1/3)
    and have a 20 character limit.  I took the half and added a ``!`` so they
    would let me use it.
Steam
    You need to do it from within the desktop app.  Which crashes for me under
    Linux due to a recent update.  And I need to do important work under Linux.
    So the password will get updated when I will get (a) to Windows; (b) Steam
    for Linux working.

How to do a proper password update process (for web devs)
=========================================================

User logged in
    Profile/settings page should contain a field for the current password (for
    added security) and two fields for the new password and its confirmation.
User forgot their password
    Ask for an e-mail or the username.  Then, do an e-mail confirmation and let
    the user set his password.  Ask twice for the new password.
Limits, requirements etc.
    Lower limit of 8 characters should be good enough for most people, and
    upper limit should not exist.  A password meter like `zxcvbn by Dropbox`_
    would also be a great idea.


.. _XKCD 936: http://xkcd.com/936/
.. _zxcvbn by Dropbox: https://tech.dropbox.com/2012/04/zxcvbn-realistic-password-strength-estimation/
