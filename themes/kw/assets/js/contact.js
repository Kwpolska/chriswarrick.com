$(document).ready(function() {
    /* http://forum.jquery.com/topic/getjson-using-post */
    jQuery.extend({
       postJSON: function( url, data, callback) {
          return jQuery.post(url, data, callback, "json");
       }
    });
    $('#contact').submit(function() {
        var name = $('#name').val();
        var mail = $('#mail').val();
        var subject = $('#subject').val();
        var sqrt = $('#sqrt').val();
        var message = $('#message').val();
        var currenterr = '';
        var ragequit = false
        $('#cg-name').removeClass('error');
        $('#cg-mail').removeClass('error');
        $('#cg-subject').removeClass('error');
        $('#cg-sqrt').removeClass('error');
        $('#cg-message').removeClass('error');
        if (name == '') {
            $('#cg-name').addClass('error');
            $('#messages-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: the <strong>Name</strong> field was empty.</div>');
            currenterr = $('#messages-go-here').html();
            ragequit = true;
        }

        if (mail == '') {
            $('#cg-mail').addClass('error');
            $('#messages-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: the <strong>Mail address</strong> field was empty.</div>');
            currenterr = $('#messages-go-here').html();
            ragequit = true;
        }

        if (subject == '') {
            $('#cg-subject').addClass('error');
            $('#messages-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: the <strong>Subject</strong> field was empty.</div>');
            currenterr = $('#messages-go-here').html();
            ragequit = true;
        }

        if (parseInt(sqrt) != '4') {
            $('#cg-sqrt').addClass('error');
            $('#messages-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: wrong answer to <strong>sqrt(16)</strong> security question.</div>');
            currenterr = $('#messages-go-here').html();
            ragequit = true;
        }

        if (message == '') {
            $('#cg-message').addClass('error');
            $('#messages-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: the <strong>Message</strong> field was empty.</div>');
            currenterr = $('#messages-go-here').html();
            ragequit = true;
        }

        if (ragequit == true) {
            return false;
        }

        $('#messages-go-here').html('<div class="alert alert-info fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Sending…</strong>');
        $.ajax({
            cache: false,
            type: "POST",
            url: "/php/contact-send-json.php",
            data: {name: name, mail: mail, subject: subject, sqrt: sqrt, message: message},
            dataType: 'json',
        }).done(function(d) {
            if (d['status'] == 'ok') {
                $('#messages-go-here').html('<div class="alert alert-success fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Done!</strong> The message has been successfully sent.  Please expect an answer in the next 24 hours, most likely in the CET afternoon.</div>');
            } else if (d['status'] == 'error') {
                $('#messages-go-here').html('<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> An unknown error occurred.</div>');
            } else if (d['status'] == 'fields') {
                $('#messages-go-here').html('<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Some fields were empty, even though the JavaScript backend thought otherwise.</div>');
            } else if (d['status'] == 'sqrt') {
                $('#messages-go-here').html('<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> sqrt test failed.</div>');
            } else {
                $('#messages-go-here').html('<div class="alert fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Broken!</strong> The Send service returned the following garbage:' + d + '</div>');
            }

        });

        return false;
    });
});
