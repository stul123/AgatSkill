$('.input_block input').on("blur", function() {
    var val = $(this).val();
    if (val == "") {
        $(this).next("label").removeClass("focused")
    } else {
        $(this).next("label").addClass("focused")
    }
});

$('.input_block input').on("focus", function() {
    var val = $(this).val();
    if (val != "") {
        $(this).next("label").removeClass("focused")
    }
});

$('#signuin_btn').click(function() {
    var mail = $('#email').val();
    var pass = $('#password').val();
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (pass != "" && mail != "") {
        if (emailPattern.test(mail)) {
            $.ajax({
                url: "/db/login.php",
                method: "post",
                dataType: 'json',
                data: {
                    "email": mail,
                    "password": pass
                },
                success: function(data) {
                    var res = data.responseText;
                    if (res != "error") {
                        console.log(data);
                        localStorage.setItem("id", data);
                        window.location.href = `/profile?id=${data}`;
                    } else {
                        error('неверные учетные данные');
                    }
                },
                error: function(data) {
                    var res = data.responseText;
                    if (res != "error") {
                        console.log(data);
                        localStorage.setItem("id", data);
                        window.location.href = `/profile?id=${data}`;
                    } else {
                        error('неверные учетные данные');
                    }
                }
            });
        } else {
            error("неверный формат email");
        }
    } else {
        error("заполните все поля")
    }
});

$('#signup_btn').click(function() {
    var mail = $('#email').val();
    var pass = $('#password').val();
    var passx2 = $('#passwordx2').val();
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (pass != "" && mail != "" && passx2 != "") {
        if (pass == passx2) {
            if (emailPattern.test(mail)) {
                $.ajax({
                    url: "/db/reg.php",
                    method: "post",
                    dataType: 'json',
                    data: {
                        "email": mail,
                        "password": pass,
                    },
                    success: function(data) {
                        var res = data.responseText;
                        if (res != 'error') {
                            localStorage.setItem("id", data);
                            window.location.href = `/profile?id=${data}`;
                        } else {
                            error("ошибка регестрации");
                        }
                    },
                    error: function(data) {
                        var res = data.responseText;
                        if (res != 'error') {
                            localStorage.setItem("id", data);
                            window.location.href = `/profile?id=${data}`;
                        } else {
                            error("ошибка регестрации");
                        }
                    }
                });
            } else {
                error("неверный формат email");
            }
        } else {
            error("пароли не совпадают");
        }
    } else {
        error("заполните все поля");
    }
});

function error(ms) {
    $('.error').html("Ошибка: " + ms);
    $('.error').removeClass('none');
    setTimeout(function() {
        $('.error').addClass('none');
    }, 5000)
}