.. title: KwBot
.. slug: kwbot
.. date: 2010-01-12 00:00:00
.. description: My IRC bot.

KwBot is my IRC bot.  It was originally registered on 2010-01-12 on Freenode,
but it has left the network following the events in May 2021, and is now
exclusively on Libera.Chat.

.. TEASER_END

It is currently in its third iteration.  The current iteration is a very basic bot,
written in Twisted.

It lives on Libera.Chat.  You can ``/invite`` it to your channel temporarily, or
`contact me </contact/>`_ to get it permanently.  Demo in ``#kwbot`` on Libera.

KwBot can act as a GitHub bot, currently working in co-operation with the
official GitHub IRC bot (KwBot handles issues — missing from the GitHub bot and
very useful)

The channels are logged; see logs from ``#nikola`` (Libera, previously Freenode) `generated by KwBot <https://irclogs.getnikola.com/>`_.

Commands must be prepended with ``!``, ``KwBot:`` or ``KwBot,`` — as demonstrated below.

.. code-block:: irc

   <ChrisWarrick> !ping
   <KwBot> ChrisWarrick: pong
   <ChrisWarrick> KwBot: ping
   <KwBot> ChrisWarrick: pong
   <ChrisWarrick> KwBot, ping
   <KwBot> ChrisWarrick: pong

* ``ping`` — respond with ``pong``
* ``hi`` — respond with ``Hi!``
* ``hello`` — respond with ``Hello!``
* ``help`` — respond with a message linking to this page
* ``logs`` — respond with the URL to KwBot’s logs for this channel on the Internet (if available)
* ``tell target message`` — add the ``message`` to a queue and show it when ``target`` joins the channel next time
* ``factoids`` — list factoids for this channel (messages that can be used for
  eg. common links or information)
