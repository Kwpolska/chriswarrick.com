---
extends: base.j2
default_block: main
title: KwBot
description: Some info about my bot, KwBot.
---
<p><?php include '/home/Kwpolska/www/bottxt.php' ?></p>
<p>KwBot is an IRC bot. It&#8217;s a supybot, written in Python.</p>

<h1 id='main_commands'>Main commands:</h1>
<p><strong>Each command must be preceded by a backtick (<code>`</code>), apostrophe (<code>'</code>) or <code>KwB.</code></strong></p>
<ul>
<li><strong><code>calc</code></strong> &mdash; calculates using Google&#8217;s calculator.</li>
<li><strong><code>commands</code></strong> &mdash; shows bots commands.</li>
<li><strong><code>more</code></strong> &mdash; shows rest of the message which ends with <em>(X more messages)</em></li>
<li><strong><code>g</code></strong> &mdash; searches Google.</li>
<li><strong><code>tiny</code>, <code>bitly</code></strong> &mdash; shortens an URL with <a href="http://kwpl.tk">kwpl.tk</a>.</li>
<li><strong><code>title</code></strong> &mdash; shows the link&#8217;s title.</li>
<li><strong><code>tr</code></strong> &mdash; translates with Google Translate. (<a href="http://kwpolska.tk/kwbot/langs/">Supported Languages</a>)</li>
<li><strong><code>seen</code></strong> &mdash; returns lastest user message on channel and date of sending.</li>
</ul>