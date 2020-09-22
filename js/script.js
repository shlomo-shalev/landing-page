$('#form-data').on('submit', function(e){

    e.preventDefault();
    $('.text-danger').text('');
    $('#submit').attr('disabled', true);

    var $name = $('#name'),
        $email = $('#email'),
        $phone = $('#phone'),
        $type = $('#room-type'),
        emailRegExp = /^(?!.*\.\.)[\w.\-#!$%&'*+\/=?^_`{}|~]{1,35}@[\w.\-]+\.[a-zA-Z]{2,15}$/,
        phoneRegExp = /^0[2-9]\d{7,8}$/,
        validData = true;

    var data = {
        name: $name.val().trim(),
        email: $email.val().trim(),
        phone: $phone.val().trim(),
        type: $type.val().trim(),
    };

    if(data.name.length <= 2 || data.name.length > 70){

        $name.next().text('חובה מינימום של שתי תווים לפחות');
        validData = false;

    }
    
    if(data.email.length < 6 || !emailRegExp.test(data.email)){

        $email.next().text('האימייל לא תקין');
        validData = false;

    }

    if(data.phone.length < 9 || !phoneRegExp.test(data.phone)){

        $phone.next().text('הטלפון לא תקין');
        validData = false;

    }

    if(data.type.length == ''){

        $type.next().text('בחר סוג חדר');
        validData = false;

    }

    if(validData){

        $.ajax({
            url: 'send_lid.php',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (res) {
                if(res.status == 'success'){

                    window.location = './thank.html';

                }else if(res.status == 'error 1'){

                    $type.next().text('המייל או הטלפון שלך נשלחו אילנו היום, חזור/י מחר על מנת לשלוח עוד פעם');

                }
            },
        });

    }else {

        $('#submit').attr('disabled', false);

    }

});

$('.btn-room').on('click', function(e){

    e.preventDefault();

    var type = $(this).data('type-room');

    $('#room-type').val('');
    $('#room-type').children('option').attr('selected', false);
    $('#room-type').children('option[value=\'' + type + '\']').attr('selected', true);

    $("body, html").animate({scrollTop:0}, 1000, 'swing');

});