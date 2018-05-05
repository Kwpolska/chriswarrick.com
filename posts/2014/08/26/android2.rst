.. title: Speeker — my little Android app
.. slug: speeker
.. date: 2014-08-26 15:00:00+02:00
.. tags: android, app, devel, programming, projects
.. category: Android Adventure
.. description: My little Android app.
.. type: text

.. class:: android-adventure-logo-robot

.. image:: /blog-content/android-adventure/robot.png
   :target: /pl/blog/2014/08/01/series-android-adventure/

Now that I have an usable phone, I can dwelve into Android app development.
And so I did.  I started with an app to test and play with the text-to-speech
services of Android.  I named it Speeker.

.. TEASER_END

.. raw:: html

   <p style="text-align: center; clear: both;">
   <img src="/blog-content/android-adventure/speeker.png" alt="Speeker logo">
   </p>
   <p style="text-align: center; clear: both;">
   <a href="/galleries/speeker/" class="btn btn-secondary" style="width: 144px;">
   <i class="far fa-image"></i>
   Screenshots
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/speeker" class="btn btn-secondary" style="width: 144px;">
   <i class="fab fa-github"></i>
   GitHub
   </a>
   </p>
   <p style="text-align: center; clear: both;">
   <a href="https://github.com/Kwpolska/speeker/releases" class="btn btn-secondary" style="width: 144px;">
   <i class="fa fa-download"></i>
   Downloads
   </a>
   </p>

Speeker is a very small and easy frontend to the system TTS service.  In its
current iteration, it’s quite limited. The complete feature set is:

* speak text provided by the user
* speak text in the system default TTS language (as set in Android settings,
  and there is currently no button to even get you there)
* clear the text box
* display About screen
* display open source licenses
* open my website

That’s SIX features!  And you can’t even download it from Google Play, because
I’m too cheap to pay the $25 fee!

I might or might not continue development and add some features.  Either way,
my experience with Android development wasn’t  quite nice: there is not enough
good documentation, and I had to do a lot of guessing when it comes to things,
including imports.  Documentation could be vastly improved.

Another problem is the choice of IDE.  While you certainly can do Android work
in any environment you like, including just Vim and the terminal, the official
and recommended environment is Eclipse.  Which just so happens to be the worst
IDE ever created.  It’s unfriendly and bulky — the usual characteristics of
huge Java applications.  Once Eclipse managed to open a file in about ten
seconds.  This is very impressive, until you notice anything else will take
much less time.  No, I am not making this up.  This is the problem with big
IDEs: trying to do too much at once.

Developing for Android has not been the best experience ever — but it can be
done, and you don’t need that much experience with the platform to do it.
Android clearly has potential, but needs to be improved to be friendly for
developers.
