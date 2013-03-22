.. title: Cookies
.. slug: cookies
.. date: 2013-02-07 00:00:00
.. link: 
.. description: Cookies?  What are cookies?

**Cookies** are small text files saved on your computer, containing various
information required by the page.  The most promintent example is keeping you
logged in without the need to authenticate every once in a while (every request, in fact).

.. TEASER_END

Full disclosure
===============

The following cookies are used on this website:

1. Google Analytics cookies.
2. EU Cookie Law compliance cookies (see below.)

EU Cookie Law Compliance
========================

The EU wants me to ask people for permission for setting cookies.  I don’t
provide a way to disable setting cookies, you must do it yourself.

.. raw:: html

    <p>Status: <span id="eustatus"><span class="badge badge-inverse">Requires JavaScript and a fully loaded page.</span></span></p>
    <p><span id="changestatus"><button class="btn btn-inverse btn-primary btn-large disabled" disabled="disabled">Fetching status…</button></span> <button class="btn btn-info btn-mini" onclick="eu_refresh_status()" id="eu-refresh">Refresh status</button></p>

    <script>
    function eu_kill_eu() {
        kill_eu();
        eu_check_status();
    }
    function eu_resurrect_eu() {
        resurrect_eu();
        eu_check_status();
    }

    function eu_refresh_status() {
        eu_check_status();
        $('#eu-refresh').html('Done');
        setTimeout(function() {$('#eu-refresh').html('Refresh status');}, 500);
    }

    function eu_check_status() {
        if(eu_status()) {
            $('#eustatus').html('<span class="badge badge-success">Cookies are accepted</span>');
            $('#changestatus').html('<button class="btn btn-danger btn-primary btn-large" onclick="eu_resurrect_eu()">Change</button>');
        } else {
            $('#eustatus').html('<span class="badge badge-important">Cookies are off or aren’t accepted</span>');
            $('#changestatus').html('<button class="btn btn-success btn-primary btn-large" onclick="eu_kill_eu()">Change</button>');
        }
    }
    $(document).ready(function() {
        eu_check_status();
        setInterval(eu_check_status, 5000);
    });
    </script>


