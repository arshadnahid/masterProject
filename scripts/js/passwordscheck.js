$(document).ready(function ()
{
    $('#password').keyup(function ()
    {
        $('#result').html(checkStrength($('#password').val()))
    })
    $('#password2').keyup(function ()
    {
        $('#result2').html(checkStrength2($('#password2').val()))
    })


    $('#password').on('blur', function () {
        var password2 = $("#password2").val();
        var password = $("#password").val();
        if (password == password2) {
            $('#result2').removeClass();
            $('#result2').addClass('match');
            $('#result2').html('The confirm passwords match with password!');
            $('#btn_submit').attr('disabled', false);
        }
        if (password2 != '' && password != password2) {
            $('#result2').removeClass();
            $('#result2').addClass('notmatch');
            $('#result2').html('The passwords that you entered do not match');
        }
    });

    $('#password2').on('blur', function () {
        var password2 = $("#password2").val();
        var password = $("#password").val();
        if (password == password2) {
            $('#result2').removeClass();
            $('#result2').addClass('match');
            $('#result2').html('The confirm passwords match with password!');
            $('#btn_submit').attr('disabled', false);
        } else {
            $('#result2').removeClass();
            $('#btn_submit').attr('disabled', true);
            $('#result2').addClass('notmatch');
            $('#result2').html('The passwords that you entered do not match');
        }
    });




    function checkStrength(password)
    {
        var strength = 0
        $('#result2').removeClass();
        $('#result2').html('');
        $('#btn_submit').attr('disabled', true);
        if (password.length < 6) {
            $('#result').removeClass()
            $('#result').addClass('short')
            return 'Too short'
        }

        if (password.length > 7)
            strength += 1

        //If password contains both lower and uppercase characters, increase strength value.
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))
            strength += 1

        //If it has numbers and characters, increase strength value.
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))
            strength += 1

        //If it has one special character, increase strength value.
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))
            strength += 1

        //if it has two special characters, increase strength value.
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/))
            strength += 1


        //Calculated strength value, we can return messages



        //If value is less than 2

        if (strength < 2)
        {
            $('#result').removeClass()
            $('#result').addClass('weak')
            return 'Weak'
        } else if (strength == 2)
        {
            $('#result').removeClass()
            $('#result').addClass('good')
            return 'Good'
        } else
        {
            $('#result').removeClass()
            $('#result').addClass('strong')
            return 'Strong'
        }
    }
    function checkStrength2(password)
    {
        var strength = 0

        if (password.length < 6) {
            $('#result2').removeClass()
            $('#result2').addClass('short')
            return 'Too short'
        }

        if (password.length > 7)
            strength += 1

        //If password contains both lower and uppercase characters, increase strength value.
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))
            strength += 1

        //If it has numbers and characters, increase strength value.
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))
            strength += 1

        //If it has one special character, increase strength value.
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))
            strength += 1

        //if it has two special characters, increase strength value.
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/))
            strength += 1


        //Calculated strength value, we can return messages



        //If value is less than 2

        if (strength < 2)
        {
            $('#result2').removeClass()
            $('#result2').addClass('weak')
            return 'Weak'
        } else if (strength == 2)
        {
            $('#result2').removeClass()
            $('#result2').addClass('good')
            return 'Good'
        } else
        {
            $('#result2').removeClass()
            $('#result2').addClass('strong')
            return 'Strong'
        }
    }
});