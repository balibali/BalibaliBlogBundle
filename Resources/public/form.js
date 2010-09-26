$(function() {
    $('input[type=submit]').attr('disabled', false);
    $('form').submit(function() {
        setTimeout(function() { $('input[type=submit]').attr('disabled', true); }, 1);
        setTimeout(function() { $('input[type=submit]').attr('disabled', false); }, 3000);
    });
});
