$(document).ready(function() {
    $('.field input').keyup(function() {

        var empty = false;
        $('.field input').each(function() {
            if ($(this).val().length == 0) {
                empty = true;
            }
        });

        if (empty) {
            $('.actions input').attr('disabled', 'disabled');
        } else {
            $('.actions input').attr('disabled', false);
        }
    });
    $('.field2 input').keyup(function() {

        var empty2 = false;
        $('.field2 input').each(function() {
            if ($(this).val().length == 0) {
                empty2 = true;
            }
        });

        if ($('#register_password').val() != $('#register_confirm').val()) {
            empty2 = true;
        }

        if (empty2) {
            $('.actions2 input').attr('disabled', 'disabled');
        } else {
            $('.actions2 input').attr('disabled', false);
        }
    });
});