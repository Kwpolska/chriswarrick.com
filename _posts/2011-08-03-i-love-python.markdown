---
layout: post
title: I love Python!
category: programming
---
**I recently started writing [Python][] code. And I love it.**

If you’d ask me a week ago about Python, I’d say “meh.”  Python3K?  I
wouldn’t be happy about it.  Sunday evening?  I love both.

One project, rewritten
----------------------
I wrote a new project.  Or rather re-wrote a Perl “project”.

The project, once a little help for building AUR packages, now is almost
a fully-featured AUR helper (it has no update function, but I will
write one soon).  It’s the [PKGBUILDer][].

The Perl version (search in the repo, linked above) had 56 lines.  In
short, it did something like this (rewritten to bash):

    {% highlight bash %}
    function generate(package) {
        wget http://aur.archlinux.org/$package/$package.tar.gz
        tar -xzvf $package.tar.gz
        cd $package
        makepkg -si
        cd ..
    }
    for package in $@, do generate(package); done{% endhighlight %}

This code is really, REALLY bad.  But it worked for me, because the
“normal” AUR helpers were slow.  I wanted to do something about it.  I put
an entry on my TODO list about it.  A few months later I decided to do it.
The TODO list entry said “write build.py”.  I wanted to use Python because
I wanted to learn it.  In fact, I began *loving* Python.

The Perl version had 56 lines.  A shortened version of it in Bash took only
8 (I skipped a few features, the full version would be around 20 lines or
so.)  Take a guess: how long is the Python version? 30 lines?  100?  No.
300 lines.  How could this happen?  No, *not* because Python is a pain in
the ass to write.  It was because I could implement new, great features
EASILY.  The original version could only download a package and build it.
What if the package didn’t exist?  The library responsible for untarring it
would throw an error.  And even if makepkg had a problem with building the
package, the script would happily inform the user that it was successfully
bulit…  What are the new features, you may ask?  Install validation, i.e.
checking if the package is installed or not.  Package searching, sanity
checks, dependency solving…  This is great.  If I’d like to write it in
Perl, it will take me ages and I’m not sure if there is any libalpm
wrapper.

If you think that you can rewrite it in Perl, sure, go for it, if you will:

 * find a working libalpm wrapper or write one yourself
 * port python3-aur (it heps with the XML-RPC of the AUR) to perl
 * implement EVERY feature of the Py3K version
 * give me the code and tell me how long did you write it

Done?  Great then, [contact me](http://kwpolska.tk/contact/).

Documentation
-------------
Python has the friendliest web documentation ever.  PHP’s looks a bit
harsh.  Perl’s is not easy to search.

Time for a real world example:  I want to learn how to write a specific
function in Perl, PHP and Python.  This function would print the argument.
For example, in C (this code REALLY sucks, but it is just an example):

    {% highlight c %}
#include <stdio.h>
void writeStuff(text) {
    printf("Input: %s\n", text);
}
int main(void) {
    writeStuff("some stuff to print");
    return 0;
}{% endhighlight %}

Notice: by “searching” in docs I mean reading the page and looking for
a thing.

**Perl:** Let’s begin at <http://perl.org>. Documentation tab, Tutorials.
I need to define a function.  Nothing seems to help me.  I look at the
sidebar and find *Reference/Functions*.  Great, that’s what I need, so i
click it…  I can’t see anything about functions.  Langauge reference?
Nothing.  I ask Google and I learn that Perl names them *subroutines*.  I
check the Language reference:  it’s the sub function, now I can define my
function and call it.  printf?  Let’s look it up in the Functions list.
We’re done.

    {% highlight perl %}
sub writeStuff {
    my $text = shift;
    printf("Input: %s\n", $text);
}
writeStuff("some stuff to print");{% endhighlight %}

Perl’s documentation is anywhere near user-friendliness.

**PHP:** <http://php.net>.  Why is the *documentation* link so small?
Anyways, I need functions.  Language Refernce/Functions.  Here we go, one
more click and I know how to make a function.  And I guess that I’ll have
to search the Function Reference.  I find text processing, go for Strings
and I can happily see printf.  Take a look and we can write this:

    {% highlight php %}
<?php
function writeStuff($text) {
    printf("Input: %s\n", $text);
}
writeStuff("some stuff to print");
?>{% endhighlight %}

**Python:** <http://python.org/>.  Documentation element exists in the
menu.  I click it.  They offer me a nice tutorial, so I’ll check it out.
I scan through the Table of Contents and I see a chapter called Defining
Functions.  Great, it will work.  Now I go back to the ToC and, because
this is a tutorial rather than a reference, and I can see chapter *7.1:
Fancier output formatting*.  I want to have %s as in other languages, so
I skip this one and see *Old string formatting*, which uses the %s.  Now,
assuming they indented the code for purpose because there are no braces,
I can write:

    {% highlight python %}
def writeStuff(text):
    print "Input: %s" % text
writeStuff("some stuff to print"){% endhighlight %}

All of them work and output `Input: some stuff to print` followed by a
newline.  The original C example had 8 lines.  Perl made it in 5, PHP
in 6 (or 4 if you willn’t count the PHP tags), Python used only 3.

Which documentation is the most HUMAN-friendly?  Python’s.  Which is the
worst? Perl’s.

Nothing is flawless
-------------------
Everything has some flaws.  What is it in Python, then?  [Existence of two
releases.][pyver]  Most distros and projects use Py2K, while some of them
offer Py3K (or both.)  The [PKGBUILDer][] is in Py3K, because it requires
`pyalpm` and the `AUR` module (I could rewrite the AUR module in Py2K, but
pyalpm is much harder to modify.)  My other projects (like KWDv2, another
rewrite, this time with minimal changes and 30% less code or my first ever
Python project, trash.py, a partial XDG trash standard implementation) use
the old Py2K (usually v2.6, because I need compatibility with my shell
server.)

I would rewrite this blog into [Django][] if I’d *own* a VPS or
a dedicated server.

[Python]: http://python.org/ "Python"
[PKGBUILDer]: https://github.com/Kwpolska/kru/tree/master/pkgbuilder "kru/pkgbuilder"
[pyver]: http://wiki.python.org/moin/Python2orPython3 "Py2K or Py3K?"
[Django]: https://www.djangoproject.com/ "Django"
