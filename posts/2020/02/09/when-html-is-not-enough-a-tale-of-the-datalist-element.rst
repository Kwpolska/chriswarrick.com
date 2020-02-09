.. title: When HTML is not enough: a tale of the <datalist> element
.. slug: when-html-is-not-enough-a-tale-of-the-datalist-element
.. date: 2020-02-09 16:30:00+01:00
.. tags: JavaScript, HTML, HTML5, web development, TypeScript
.. category: Internet
.. description: The <datalist> element sounds like a good idea, but browser support issues make the experience worse than a custom autocomplete widget.
.. type: text

HTML 5.0 was finalized in 2014 (and its drafts were published even earlier), and with it came the ``<datalist>`` element.  It’s
2020, and even though it might look like a good replacement for custom
autocomplete widgets, browser issues made me get rid of it.

.. TEASER_END

I’ve built a web app to help me track my expenses. The app is written in Django,
and `it’s open source <https://github.com/Kwpolska/django-expenses>`_. One of
the goals was to have a simple codebase with limited external JS dependencies,
as well as basic usability with JS disabled. This is partially to facilitate
learning of standard DOM manipulation routines and TypeScript.

The JS/TS bits are called the *Scripting Enhancements* to reflect their nature.
The biggest items are an interactive bill editor (a table with add/edit/remove
operations, that submits its data as a regular HTML POST ``<form>``) and an
autocomplete framework (used by the bill editor in an advanced way, and by
other screens in the app with a more basic featureset). The autocomplete
framework is exactly what you’d expect: point it at an input field and a URL,
and keypresses lead to the URL being queried for previous values for this
field, which are displayed as possible values to the user to save typing.

Autocomplete with HTML 5: the <datalist> tag
============================================

But how to display the options to the user? Most people would display a
``position: absolute`` box with links/buttons, throw in some more logic around
the focus and blur events, and call it a day. There are tons of ready-made
solutions that do all that for you, although most of them are terrible. But!
HTML 5 introduced a ``<datalist>`` tag. And it looks like everything you could
need. You link a ``<datalist>`` tag to an ``<input>`` and it shows matching
options in an autocomplete-style box.  In fact, here’s a simple demo, in case
your browser supports it:

.. raw:: html

    <div class="card mb-3 text-center">
        <div class="card-body">
            <label for="dldemo" class="ml-1 mr-1">Favorite programming language:</label>
            <input class="form-control d-inline-block ml-1 mr-1" style="width: auto" placeholder="Start typing…" list="dldemolist" id="dldemo">
            <datalist id="dldemolist">
                <option value="Swift">
                <option value="Rust">
                <option value="Ruby">
                <option value="Python">
                <option value="PHP">
                <option value="Kotlin">
                <option value="JavaScript">
                <option value="Java">
                <option value="Go">
                <option value="C++">
                <option value="C#">C Sharp</option>
                <option value="C">
            </datalist>
        </div>
        <div class="card-footer"><a href="/listings/datalist/datalist-demo.html.html">View demo source</a></div>
    </div>

Now, here are a few takeaways from that demo:

.. role:: raw-html(raw)
   :format: html

1. Options are displayed in the same order as in the ``<datalist>`` tag in the
   source, this list was sorted reverse-alphabetically in the source, and
   that’s how it appears in the source.
2. The list is filtered case-insensitively based on user-input substrings. In
   Chrome, Firefox and Safari, the substring can appear at any point in the
   string.  But in Edge (old Microsoft engine), it looks only at the beginning
   of the string.
3. Some browsers show an arrow on the field to show the entries, sometimes
   double-clicking opens the list.
4. The entry for C# is as follows: ``<option value="C#">C Sharp</option>``.
   Chrome displays it on as :raw-html:`“<strong>C#</strong> <small>C
   Sharp</small>”` (on two lines), Safari shows only “C#”, Firefox and Edge
   show “C Sharp”. Selecting the option always inputs C#.
5. Mobile Safari does not expand the list by default, but displays some of the
   options above the keyboard (as typing predictions). You can click on the
   arrow to display all the options in a `scrolling picker`_.
6. Chrome on Android displays it the same way as on desktop (drop-down list).

This demo uses static, hardcoded data. Doing that for the Expenses app would
be terrible for performance — that would waste bandwidth, force the browser to
parse a fairly long list, and it could easily overload the browser when it
tries to expand the list. But wiring it up to a ``fetch()`` call to a REST API
should not be hard, and browsers work correctly when the datalist changes.

An emoji hack
=============

One of the features I needed was to make the auto-complete fill out more than
one field at once. Well, ``<datalist>`` has no specific support for that. It only
supports showing a list and putting the value in the input box it’s connected
to. But choosing something from the list fires the usual ``input`` event. I
opted to do this: show every entry with a sparkles emoji (✨) in front, with the
two other fields also inside this string, delimited by other emoji, and then
catch the ``input`` event.  If the field beigns with ✨, then use a regex to go
from one emoji-delimited string to three, and place the correct strings in
three input boxes (while also removing the sparkles from the first field).

Yes, it’s a hack. But it’s pretty okay appearance-wise, and it does work. It
wouldn’t have worked so well in Edge, but I didn’t even know about this
behavior before writing this blog post, and the initial sparkles emoji could be
dropped and I could still make it work.

Works on mobile? Yes, except…
=============================

I went on and deployed the ``<datalist>``-based autocomplete to my site. It
looked good, worked fine. To use the thing on mobile, I’ve got a special
launcher app. Its main reason for existence? I want a home screen icon, but
Chrome only allows progressive web apps to do that (and that’s busywork I don’t
feel like doing), and back then, Firefox (which has no such restrictions) did
not support ``<datalist>`` on Android.  The app is fairly simple, with a
standard WebView widget and a slide-out navigation drawer, and a few other nice
things, and it’s 120 SLOC of Kotlin.

But then, I bought a new phone, and with it, upgraded from Android 7 to 9. And
I hit a bug in Chrome, which is still not fixed. The bug?
`HTML datalist doesn’t work on Android 8 or higher in WebView
<https://bugs.chromium.org/p/chromium/issues/detail?id=949555>`_.

Oh. We’ve got a bit of a problem. Firefox still didn’t seem to support
``<datalist>``. But there’s one more way to make an app show a webpage: Custom
Tabs. This is a feature you’ve probably seen around Android, and it’s somewhere
in between. The app gets minimum control over the appearance of the toolbar,
but the “real” web browser is responsible for rendering the page. Chrome in a
Custom Tab supports ``<datalist>``. So I built a small app to do what I wanted.

There was just one minor thing to fix. My default browser on mobile is `Firefox
Focus <https://support.mozilla.org/en-US/kb/focus>`_. The main features of
Focus are tracking protection, content blocking, and storing zero
history and cookies (permanent incognito mode with one-click clearing). This is
perfect for clicking random links, especially since I hate Chrome’s insistence
on showing webpages you visited 5 years ago once when autocompleting URLs.
(Chrome is my secondary browser on mobile; on desktop, I almost always have an
incognito window open.)

Why is Focus relevant to this story? One, it (still) does not support the tag.
Two, the default browser is also the provider of the Custom Tabs. Which is
great for my web-browsing habits, but won’t solve the problem.  Fortunately,
it’s just a one-line change to send the intent directly to Chrome. The entire
thing is less than 30 lines long. You can see the full `CustomTabsActivity.java
</listings/android-chrome-custom-tabs/CustomTabsActivity.java.html>`_ file, but the relevant bits are below.

.. code:: java

    CustomTabsIntent.Builder builder = new CustomTabsIntent.Builder();
    // Optionally, configure appearance and buttons on toolbar.
    CustomTabsIntent intent = builder.build();
    // Force browser to Chrome instead of system default.
    intent.intent.setPackage("com.android.chrome");
    intent.launchUrl(this, Uri.parse("https://chriswarrick.com/"));

It seems to work well, the list is displayed, and it can be used to input
stuff, the emoji hack works too.

There was one more bug with Chrome on Android. Typing a character sometimes led to
it appearing twice: I typed *A*, the hints appeared, then the text box started
showing *AA*, and my hints disappeared. I can’t reproduce it right now, but
that also made the entire flow just annoying.

Aftermath
=========

With all the browser bugs, support issues, and various glitches, I decided to
build an autocomplete widget of my own. I took the CSS from Bootstrap 4, and
used Popper.js to do the positioning. It looks and works better, has keyboard
support, and is definitely less hacky (the emoji is still there, because they
look good, but my entries know the original object they were made from and can
just tell the handler to use that instead of using regex). And it beats many of
the autocomplete widgets out there, because they often fail when you hold the
mouse a bit longer; also, it can reposition itself to the top if there’s more
space. All that in just 198 SLOC of TypeScript. (I also discovered a bug in my
code that made it work a bit worse, fixing it for the old implementation would
still not fix the other issues.)

What’s the moral of the story? Even though HTML 5 has been a standard for many
years, browser support for the new tags still seems to be an issue. And
sometimes, it’s better to just put in the extra work and build a good UI on
your own, instead of trusting the browser to do it right.

The same applies to other “new” HTML 5 form elements.  ``<input type="date">``
is not supported in desktop Safari, and is fairly ugly in desktop Firefox and
Chrome.  It displays the standard OS picker on mobile, which gets you a
calendar on Android, but a `scrolling picker`_ on iOS.
``datetime-local`` is currently Chrome-only.  ``month`` lets you click on a day
and end up with an entire month selected in Chrome.  A custom component with
JavaScript would be far more consistent and often easier to use.

.. _scrolling picker: https://developer.apple.com/design/human-interface-guidelines/ios/controls/pickers/
