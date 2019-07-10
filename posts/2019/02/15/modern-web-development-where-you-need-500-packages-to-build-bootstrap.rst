.. title: Modern Web Development: where you need 500 packages to build Bootstrap
.. slug: modern-web-development-where-you-need-500-packages-to-build-bootstrap
.. date: 2019-02-15 19:00:00+01:00
.. tags: webmastering, JavaScript, web development, rant
.. category: Internet
.. description: A rant about the modern JS ecosystem.
.. type: text

This humble blog is written by an old-school developer who sometimes does web stuff. An attempt to customize the Bootstrap CSS theme requires 50 MB of node_modules, over 500 packages, and comes with a bit of frustration at stupid tools and terrible documentation.

.. TEASER_END

You might notice that this website is based on Bootstrap. You might also notice it’s been heavily customized, especially if you’re browsing in the (currently default) Dark Mode. Back in Bootstrap v3 days, the task was accomplished by `a simple online tool <https://getbootstrap.com/docs/3.4/customize/>`_ that required no local installs. Bootstrap 4 changed the landscape: now you need to manually compile Sass. Moreover, Autoprefixer is required to make the CSS usable by web browsers.

Now, when it comes to web development, I believe the old ways were better. Back when nobody thought to make a client-side-JS-based blog or pastebin, and only apps that needed interactivity were JS-first. Gmail is a good example of that, although they *still* offer a `basic HTML view <https://support.google.com/mail/answer/15049?hl=en>`_ and it works good — in fact, I suppose it might be less buggy than the JS-ladden version. (A lot of single-page apps like to randomly glitch out in my experience.)

I still remember the days when all that one had to do is ``java -jar yuicompressor.jar style.css > style.min.css``. Then Less and Sass became more popular — and that’s good. The ability to use variables and functions makes it possible to produce well-organized stylesheets. The idea of Autoprefixer is also fine, humans should not waste their time with browser-specific prefixes for experimental features, that can be neatly automated.

But to use all these fancy tools, glue code is necessary. Autoprefixer is (mainly server-side) JS-only, Sass is currently Node or Dart, minifier tools are available in many languages.

Attempt 0: no JS stuff, no node_modules
=======================================

I installed a Sass compiler. There are web services like cssminifier.com that can be easily used with curl in a Bash script. Autoprefixer has a webpage that lets you use the service without installing it as well. The catch is, the code runs locally in your web browser. Automating a web browser requires some effort. I decided to leave this part un-automated. Here is the Bash script I hacked together (with some messages removed):

.. code:: sh

    sass bootstrap-kw.scss > bootstrap.noprefix.css
    sass bootstrap-kw-dark.scss > bootstrap-dark.noprefix.css

    echo "Go to https://autoprefixer.github.io/."

    pbcopy < bootstrap.noprefix.css
    echo -n "(light) Paste the clipboard contents and copy the output, then press Enter."
    read temp
    pbpaste > assets/css/bootstrap.css

    sleep 1

    pbcopy < bootstrap-dark.noprefix.css
    echo -n "( dark) Paste the clipboard contents and copy the output, then press Enter."
    read temp
    pbpaste > assets/css/bootstrap-dark.css

    curl -X POST -s --data-urlencode 'input@assets/css/bootstrap.css' https://cssminifier.com/raw > assets/css/bootstrap.min.css
    curl -X POST -s --data-urlencode 'input@assets/css/bootstrap-dark.css' https://cssminifier.com/raw > assets/css/bootstrap-dark.min.css


The “manual copy” solution was inconvenient, but it worked.

Well, most of the time Some lags/glitches with the clipboard meant that sometimes, files had the incorrect content. So, I wanted to fix it, and build it in a more modern, JS-y way. The way Bootstrap does it is a lot of shell commands (that run various Node tools). I don’t feel like building this pipeline with Bash, it would feel fragile. Let’s do it the JS way.

Attempt 1: webpack
==================

I’ve used webpack for `another project of mine <https://github.com/Kwpolska/django-expenses/blob/master/ts/webpack.config.js>`_. It was okay, and it did the job (namely, compiling TypeScript into browser-usable JS).

I wanted to give it a try for this one. I googled “webpack sass”. The first result was `sass-loader <https://github.com/webpack-contrib/sass-loader>`_. The pipeline for it was:

.. code:: javascript

    "style-loader", // creates style nodes from JS strings
    "css-loader", // translates CSS into CommonJS
    "sass-loader" // compiles Sass to CSS, using Node Sass by default

Let’s recap. Someone thought that the right way to do CSS is to use JS imports.

Yes. ``import "./style.css";`` in a JS file. So that your fancy build tool knows about CSS.

Webpack wasn’t the right tool for my project, but even if I had JS code there, **WHY WOULD I MENTION STYLESHEETS IN MY JS CODE?!** Webpack’s website also lists .jpg and .png assets, are they meant to be imported in JS as well? This is absurd.

Going back to googling “webpack sass”… The next two results were Medium posts. The stupidity of Medium as a blog platform notwithstanding, one of the posts was from 2017, referring to webpack 2. The next post was a year older, a completely unreadable mess, and it was for webpack 4. That’s not helpful in any way.

Attempt 2: Gulp
===============

Let’s try something else from the JS world: Gulp. Now, the tool is not terrible, but it still requires a lot of dependencies.

The pipeline that was required for this task sounds very simple:

 bootstrap-kw{,-dark}.sass → Sass compiler → Autoprefixer → bootstrap{,-dark}.css → minify → bootstrap{,-dark}.min.css

The Gulp version is fairly simple: (I based it on examples on Gulp’s website, and pages of all my dependencies).

.. code:: javascript

    const { src, dest } = require('gulp');
    const minifyCSS = require('gulp-csso');
    const sass = require('gulp-sass');
    const postcss = require('gulp-postcss');
    const autoprefixer = require('autoprefixer');
    const rename = require("gulp-rename");
    sass.compiler = require('node-sass');


    function css() {
        return src('*.scss')
            .pipe(sass.sync().on('error', sass.logError))
            .pipe(postcss([autoprefixer()]))
            .pipe(rename(function (path) {
                path.basename = path.basename.replace("-kw", "")
            }))
            .pipe(dest('assets/css'))
            .pipe(minifyCSS())
            .pipe(rename(function (path) {
                path.basename += ".min"
            }))
            .pipe(dest('assets/css'));
    }

    exports.default = css;

A node_modules extravaganza
===========================

Can you see all the ``require`` lines at the top? Every one of them is a dependency of my build script. With the exception of ``gulp-rename``, which IMO should be a built-in part of Gulp (it’s 45 lines of code and no external dependencies), the list is sensible.

Well, I already mentioned the size of ``node_modules``: 51 MiB according to ``du`` (size-on-disk measurement). How many packages are there?

 545. Five hundred and forty-five packages.

Whoa, when did that happen? Most of it comes from gulp/gulp-cli (384 packages), with node-sass taking the second place (177 packages). Some of those are shared between libraries, and a few more belong to the other requirements. And many of these dependencies are a disgrace to programming.

After a full install of my ``package.json``, npm says ``added 545 packages from 331 contributors and audited 10500 packages in 22.458s``.  I’ve implicitly agreed to licenses imposed by 331 random people. All to build some simple CSS files out of SASS.

Let’s go on a tour of ``node_modules`` and see what we ended up with.

Polyfills, reimplementations, oh my!
------------------------------------

Everything I’ve installed is meant to be used on top of Node.js. Node runs on top of the V8 engine, coming from Chrome. They’ve had almost-full ES2015 (ES6) support since April 2016. And yet, my node_modules is full of small polyfills.

Let’s pick a random one and work back from it: ``number-is-nan``.

.. code:: javascript

    // Copyright © Sindre Sorhus, MIT license
    module.exports = Number.isNaN || function (x) {
        return x !== x;
    };

That’s a one-liner that re-implements ``Number.isNaN`` if it’s not available, which is, according to MDN, `a more robust version of the original, global
isNaN() <https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Number/isNaN>`_. The original function coerced everything to Number before testing, which apparently wasn’t enough. That’s what you get for using a language designed in a week. The function was added to all sane browsers and Node around 2013, the polyfill was created in 2015.

It was pulled in by ``is-fullwidth-code-point`` and ``is-finite``, both by the same author. The latter one is especially interesting: it’s at version 1.0.2. Version 1.0.1improved the codebase from ``if (x) { return false; } return true`` (via pull request), and version 1.0.2 replaced a manual ``val !== val`` comparison with ``number-is-nan``. ``number-is-nan`` has 7.5 million weekly downloads, ``is-finite`` has 6.7M. The build of ``number-is-nan`` `is currently failing. <https://travis-ci.org/sindresorhus/number-is-nan/builds/363709421>`_

Fifty shades of terminal
------------------------

Every Node-based CLI tool wants to be cool. And for that, they need colors.

How does this work in Bash? You could use ``tput setaf XX``, but many people would just manually ``echo '\033[XXm'``, the codes are available `in Wikipedia <https://en.wikipedia.org/wiki/ANSI_escape_code#3/4_bit>`_ or elsewhere.

How does this work in Python? There are a few libraries for this (and you can always do it manually), but the most popular one is `colorama <https://pypi.org/project/colorama/>`_. That library can even handle Windows.

What is available in Node?

* ``color-support`` and ``supports-color`` are both part of my ``node_modules``.
* There seems to be a fairly advanced ``chalk`` library, by the aforementioned Sindre Sorhus.
* ``ansi-colors`` seems to be another, smaller option for it, it claims to be 10-20x faster than ``chalk``.
* There’s a package called ``has-ansi`` which checks if a string has ANSI escapes in it. It depends on ``ansi-regex``.
* Also, ``strip-ansi`` also uses ``ansi-regex``. All three packages are basically one liners. One exports a regex, the other two do replacement/search with it.
* There’s ``wrap-ansi`` and ``ansi-wrap``. ``wrap-ansi`` intelligently wraps a string with ANSI escapes in it.  ``ansi-wrap`` takes three strings and  returns ``'\u001b['+ a + 'm' + msg + '\u001b[' + b + 'm'`` (Copyright © Jon Schlinkert, MIT license)
* There’s also ``ansi-gray``, which calls ``ansi-wrap`` with a = 90, b = 39, and a user-specified message. (Copyright © Jon Schlinkert, MIT license)
*  ``ansi-red`` and ``ansi-cyan`` are very similar libraries to ``ansi-gray``. Is this a joke?!

There are definitely other ``ansi-$color`` libraries, although they are not in my ``node_modules``. And probably other libraries for color support, but either they are not installed, or I haven’t managed to spot them in my ``npm list`` output.

Copyrighted one-liners
----------------------

Another famous library by Jon Schlinkert is called ``is-even``. Here is the complete code, verbatim:

.. code:: javascript

    /*!
     * is-even <https://github.com/jonschlinkert/is-even>
     *
     * Copyright (c) 2015, 2017, Jon Schlinkert.
     * Released under the MIT License.
     */

    'use strict';

    var isOdd = require('is-odd');

    module.exports = function isEven(i) {
      return !isOdd(i);
    };

``is-odd`` is slightly longer:

.. code:: javascript

    /*!
     * is-odd <https://github.com/jonschlinkert/is-odd>
     *
     * Copyright (c) 2015-2017, Jon Schlinkert.
     * Released under the MIT License.
     */

    'use strict';

    const isNumber = require('is-number');

    module.exports = function isOdd(value) {
      const n = Math.abs(value);
      if (!isNumber(n)) {
        throw new TypeError('expected a number');
      }
      if (!Number.isInteger(n)) {
        throw new Error('expected an integer');
      }
      if (!Number.isSafeInteger(n)) {
        throw new Error('value exceeds maximum safe integer');
      }
      return (n % 2) === 1;
    };

``is-number`` is another fun library; it says ``true`` for strings of numbers, and ``false`` for NaN (``typeof NaN === 'number'``). ``is-even`` is used by, for example, ``even``, which calls ``Array.filter`` with ``is-even`` as the argument. There’s also ``odd``, and for some reason, the two packages are separate.

The checks found in ``is-odd`` make some more sense if you’re working with a dynamically-typed language where every number is a float (like JS). But you could release ``check-odd``, which is 100x faster than ``is-odd`` (it assumes its input is correct), and exports ``function checkOdd(value) { return (value % 2) !== 0; }`` |copr_strike|

This product includes software developed by…
--------------------------------------------

Hold on a second, 4-clause BSD? That license contains the following clause:

.. code:: text

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
    3. All advertising materials mentioning features or use of this software
       must display the following acknowledgement:
         This product includes software developed by the University of
         California, Berkeley and its contributors.

This clause was removed by UC Berkeley in 1999, but there is still old code that has clauses (with other names), and someone could create something with the old license. I actually found one such clause in my ``node_modules`` (from ``bcrypt_pbkdf``). `NetBSD had 75 different clauses <https://www.gnu.org/licenses/bsd.html>`_ in 1997. It would be fun to see figures for the Node ecosystem… or more packages with equally problematic clauses.

Most people aren’t aware of the licenses of their node dependencies. Going back to Colorama, I can quickly verify that Colorama has no dependencies, and itself uses the 3-clause BSD license. (That version of the license lacks the advertising clause and is considered GPL-compatible.) There is a helpful ``license-checker`` package that can tell you what licenses you have (based on the details provided in ``package.json``)

.. code:: text

   ├─ MIT: 380
   ├─ ISC: 64
   ├─ Apache-2.0: 10
   ├─ BSD-3-Clause: 10
   ├─ BSD-2-Clause: 3
   ├─ CC-BY-3.0: 2
   ├─ BSD-3-Clause OR MIT: 1
   ├─ MIT*: 1
   ├─ (MIT OR Apache-2.0): 1
   ├─ CC-BY-4.0: 1
   ├─ AFLv2.1,BSD: 1
   ├─ MPL-2.0: 1
   ├─ (BSD-2-Clause OR MIT OR Apache-2.0): 1
   ├─ CC0-1.0: 1
   └─ Unlicense: 1

Attempt 2: back to Bash
=======================

I decided to get rid of Gulp, it’s not necessary for this pipeline. I replaced
it with Bash and ``postcss-cli``. ``node-sass`` was replaced by ``dart-sass``
(a two-file binary distribution), and ``csso`` was replaced by ``cssnano`` (it
works with postcss). Here is the resulting Bash file:

.. code:: bash

    sass bootstrap-kw.scss | npx postcss --no-map --use autoprefixer -o assets/css/bootstrap.css
    sass bootstrap-kw-dark.scss | npx postcss --no-map --use autoprefixer -o assets/css/bootstrap-dark.css
    npx postcss --no-map --use cssnano -o assets/css/bootstrap.min.css assets/css/bootstrap.css
    npx postcss --no-map --use cssnano -o assets/css/bootstrap-dark.min.css assets/css/bootstrap-dark.css

The simplified dependency list cost me 37 MiB of disk space, and I’ve got 438
packages from 232 contributors.

Attempt 3: node CLIs are unnecessary
====================================

Let’s try something else: replace ``npx postcss`` with a custom tool.

.. code:: javascript

    const fs = require('fs');
    const getStdin = require('get-stdin');

    const postcss = require('postcss');
    const autoprefixer = require('autoprefixer');
    const cssnano = require('cssnano');

    const name = process.argv[2];

    getStdin().then(css => {
        postcss([autoprefixer]).process(css, {from: undefined}).then(result1 => {
            fs.writeFileSync(`assets/css/${name}.css`, result1.css);

            postcss([cssnano]).process(result1.css, {from: undefined}).then(result2 => {
                fs.writeFileSync(`assets/css/${name}.min.css`, result2.css)
            });
        });
    });

The bash script now pipes ``sass`` output to ``node run_postcss.js bootstrap(-dark)``.

Doing this… cost me a new dependency. Its name is ``get-stdin``. We’ve already met its author, Sindre Sorhus. While the library has its deficiencies `(namely, it doesn’t support reading from TTY) <https://github.com/sindresorhus/get-stdin/issues/21>`_, it’s good enough. I could do it manually or use some other tricks, but since ``get-stdin`` does not pull in any other dependencies, I’m going to accept it. After cleaning up ``packages.json``, we end up with:

.. code:: console

    $ npm install
    added 144 packages from 119 contributors and audited 637 packages in 8.127s
    found 0 vulnerabilities
    $ du -hs node_modules
     21M    node_modules


Conclusion
==========

The task at hand was very simple. So was the JS code (Gulp and custom) I had to write to implement it. But underneath, there was a mess of unknown, unaudited code, duplicated libraries, and libraries created effectively to bump people’s npm download stats. There were already incidents, like ``left-pad`` (the removal of which broke Babel), or ``event-stream`` (which was taken over and modified to steal cryptocurrencies). The modern web development ecosystem is a huge mess of dependencies and one-line packages. Some of them are necessary due to the lackluster JS standard library — but some are just useless. And some of these micro-packages would be better off as larger libraries.

 Sure, the package count went down from the original 545 to 144. But the original point still stands: too much useless stuff.

PS. Five of the packages (in the “large” set) had a ``.DS_Store`` file left over. I’m wondering if there are any other files that shouldn’t be shipped with packages, lurking in ``node_modules`` directories all over the world…

PPS. I’ve replaced Disqus with Isso, because it had too many advertisements. If you experience any issues with the comment system (after force-refreshing), e-mail me.

.. |copr_strike| raw:: html

  <s>(Copyright © 2019, Chris Warrick. Licensed under the 4-clause BSD license.)</s> <i>(No, not really.)</i>
