---
layout: page
title: Kw&#8217;s Mail Hide Trick
---

## An example
<style type="text/css">.mail:before { content: "It'sa me, Mario!" }</style>
<span class="mail"></span>

### I can&#8217;t see anything above!
Internet Explorer 7 or older? I know. It is too stupid. A **good** browser will show &#8220;It'sa me, Mario!&#8221;.

## What the heck?
This is a response to [Aza Raskin&#8217;s phishing/mail/copy-paste protection][raskin]. His method will give you the legitimate thing that you wanted to copy and random characters. My method is different. The text is in CSS. In most browsers, the text is **uncopiable**. That&#8217;s right, folks! No copy-n-paste. The only way to get the data is to look into the CSS.

## The cheat?
Very simple: `.mail:before`. Yes. It&#8127;s **that** easy. See the source code:

    <style type="text/css">.mail:before { content: "It'sa me, Mario!" }</style>
    <span class="mail"></span>

That&#8217;s why older IEs won&#8217;t render it.

## Supported by

 * WebKit-based browsers
 * Firefox
 * Opera
 * Internet Explorer 8 and higher

Older Internet Explorers are too stupid to get it working, sorry.

Copyright &copy; Kwpolska 2011.

 [raskin]: http://www.azarask.in/blog/post/protecting-email-with-css/
