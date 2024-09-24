$(document).ready(function($) {
    $('#buttonChatImg').click(function() {
        $('.chat-transition').fadeIn();
        return false;
    });

    $('.chatClose').click(function() {
        $(this).parents('.chat-transition').fadeOut();
        return false;
    });
});