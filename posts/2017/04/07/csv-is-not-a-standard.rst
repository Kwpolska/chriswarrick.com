.. title: CSV is not a standard
.. slug: csv-is-not-a-standard
.. date: 2017-04-07 20:00:00+02:00
.. tags: CSV, Microsoft, Excel, Microsoft Office
.. category: Programming
.. description: CSV is not a standard.
.. type: text
.. shortlink: csv

CSV is not a standard. What does that really mean for anyone using that format?
The file’s recipient may be unable to read it the way you intended. Separators,
decimal marks, escaping and encodings are all problems — and Excel does them
all pretty badly.

.. TEASER_END

So first, some people might claim that `RFC 4180`__ is the CSV standard. Those
people also have not read the document they’re referring to. It states:

  This memo provides information for the Internet community.  It does
  not specify an Internet standard of any kind.

The problem with this is the fact that a ``.csv`` file does not mean much. There
are a few problems. The first question is,

  What is the field separator? Is it a comma or a semicolon?

Hey, wait a minute, doesn’t the file format/extension stand for
*comma-separated values*? Yes, it does. But that does not matter in the
slightest. You see, Microsoft Excel — which most people will use to read/write
their CSV files — makes this decision based on the user locale settings. If the
OS is set to a locale where the comma is the `decimal mark`__ (eg. most of
Europe), the list separator is set to ``;`` instead of ``,`` — and Excel uses
that.

Of course, there’s also the TSV data format — those are tab-separated values.
And some people might name their TSV files ``.csv``.

To read files saved in a different locale, or with a different separator, Excel
users need to change the file extension to ``.txt``, or go to Data → Get
External Data → From Text `(documentation)`__ and use the import wizard. You
can’t double-click on files.

On a side note, Apple Numbers guesses the format — one of the few things it
gets right. LibreOffice always asks the user to pick import settings, but by
default it uses tab AND comma AND semicolon for CSV files, which brings its own
host of problems.

Here’s a quick test:

  What does ``foo;bar,baz;quux`` mean? What about ``foo,bar;baz,quux``?

* LibreOffice assumes it’s (Chinese) UTF-16 text, but after telling it the real encoding, both
  files contain **4 columns**.
* Microsoft Excel says one of the files contains **3 columns** and the other contains **2 columns**
  (which is which depends on locale)
* Apple Numbers says the first file contains **3 columns** and the other
  contains **2 columns** if set to English, and both files contain **3
  columns** if set to Polish.

But let’s get back to gotchas:

  What is the decimal mark? Is it a dot or a comma?

That’s a direct consequence of the previous question. However, one can’t simply
assume ``comma/dot`` and ``semicolon/comma``, because users might do crazy
stuff.

  What is used to escape rows containing the field separator? Quotes?
  Backslashes?  What is used to escape the escape character?

Excel, for example, puts some things in ``"quotes"``. If a literal quote
character appears in the spreadsheet, it’s represented as ``""``, and
the entire cell is quoted as well. But there might be programs that use
backslashes for escapes, or even bad code that does not consider the need of
escaping like this, with tragic results.

There’s still one more thing to cover: encodings. You see, even though the TSV
format effectively solves the issues I named before, both CSV and TSV suffer
from one problem:

  Which encoding to use when reading this file?

I already mentioned that LibreOffice believed my sample file was UTF-16,
containing Chinese text — in reality, this file was UTF-8 (or ASCII).

What does Microsoft Excel do then? It looks like it follows *System locale for
non-Unicode programs*. While there is an encoding option hidden in the Save
dialog, it does not seem to affect the output. So what does that mean? You
can’t expect a CSV file that contains characters outside of your system locale
— or outside of ASCII if you’re working with people around the world — to look
right. Unless you’re on `Excel 2016`__ and Office 365 — if you have the October
2016 update, you can read and write UTF-8 files. But if you’re using an older
version of Excel, or you’re using a non-Office 365 license, tough luck.

So, to reiterate: CSV can mean a lot of things. And you can’t trust it to work
well most of the time, unless you’re dealing with people in one country, all
using the same locale settings and software. Which is pretty unlikely. TSV
can work around most of the problems, but encodings are still troublesome.

__ http://www.ietf.org/rfc/rfc4180.txt
__ https://en.wikipedia.org/wiki/Decimal_mark#Hindu.E2.80.93Arabic_numeral_system
__ https://support.office.com/en-us/article/Text-Import-Wizard-c5b02af6-fda1-4440-899f-f78bafe41857
__ https://answers.microsoft.com/en-us/msoffice/forum/msoffice_install-mso_win10/announcing-october-feature-update-for-office-2016/927eea90-eea3-479a-a78a-45f7612460e1
