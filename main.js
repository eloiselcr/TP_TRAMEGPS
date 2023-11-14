(function($){
    $(window).load(function(){
        $('body').removeClass('preload');
        event.preventDefault();
    });

    var users = {
        "user": {
            "name": "testuser",
            "password": "password"
        }
    };

    $('#send-connexion').click(function(){
        var userinput = $('#user').val();
        var passwordinput = $('#password').val();

        $('#user, #password').removeClass('error');

        if(userinput == users.user.name && passwordinput == users.user.password){
            alert('Hello '+users.user.name+' !');
            $('#user').val('');
            $('#password').val('');
            return false;
        }
        else {
            if(userinput != users.user.name || userinput === ''){
                $('#user').removeClass('error').addClass('error');
            }
            if(passwordinput != users.user.password || passwordinput === ''){
                $('#password').removeClass('error').addClass('error');
            }

            return false;

        }
    });
})(jQuery);