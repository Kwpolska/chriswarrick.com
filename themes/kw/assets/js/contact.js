$(document).ready(function() {
    $('#contact').submit(function() {
        var name = $('#name').val();
        var mail = $('#mail').val();
        var subject = $('#subject').val();
        var sqrt = $('#sqrt').val();
        var message = $('#message').val();
        var currenterr = $('#errors-go-here').html();
        var ragequit = false
        $('#cg-sqrt').removeClass('error');
        if (parseInt(sqrt) != '4') {
            $('#cg-sqrt').addClass('error');
            $('#errors-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: wrong answer to sqrt(16) security question.</div>');
            ragequit = true;
        }
        if (name == '') {
            $('#cg-name').addClass('error');
            $('#errors-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: the <strong>Name</strong> field was empty.</div>');
            ragequit = true;
        }

        if (mail == '') {
            $('#cg-mail').addClass('error');
            $('#errors-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: the <strong>Mail address</strong> field was empty.</div>');
            ragequit = true;
        }

        if (subject == '') {
            $('#cg-subject').addClass('error');
            $('#errors-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: the <strong>Subject</strong> field was empty.</div>');
            ragequit = true;
        }

        if (message == '') {
            $('#cg-message').addClass('error');
            $('#errors-go-here').html(currenterr + '<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Failed!</strong> Reason: the <strong>Message</strong> field was empty.</div>');
            ragequit = true;
        }

        if (ragequit == true) {
            return false;
        }
        $.ajaxSetup ({
            cache: false
        });
        return true;
    });
});
