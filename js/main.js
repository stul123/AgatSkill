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
                        localStorage.setItem("id", data);
                        window.location.href = `/profile?id=${data}`;
                    } else {
                        error('неверные учетные данные');
                    }
                },
                error: function(data) {
                    var res = data.responseText;
                    if (res != "error") {
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

async function getreg(email, password) {
    let y = await $.ajax({
        url: "/db/reg.php",
        method: "post",
        dataType: 'json',
        data: {
            "email": email,
            "password": password,
        },
        success: function(data) {
            return data.responseText;
        },
        error: function(data) {
            return data.responseText;
        }
    });
    return await y;
}

$('#signup_btn').click(async function() {
    var mail = $('#email').val();
    var pass = $('#password').val();
    var passx2 = $('#passwordx2').val();
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (pass != "" && mail != "" && passx2 != "") {
        if (pass == passx2) {
            if (emailPattern.test(mail)) {
                var res = await getreg(mail, pass);
                if (res != 'error') {
                    localStorage.setItem("id", res);
                    window.location.href = `/profile?id=${res}`;
                } else {
                    error("ошибка регистрация");
                }
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

$('#save_data').click(function() {
    var name = $('#name').val();
    var surname = $('#surname').val();
    var email = $('#email').val();
    var pass = $('#pass').val();
    var tel = $('#tel').val();

    if (name != '' || surname != '' || email != '' || pass != '' || tel != '') {
        $.ajax({
            url: "/db/change_settings.php",
            method: "post",
            dataType: 'json',
            data: {
                "name": name,
                "surname": surname,
                "phone": tel,
                "email": email,
                "password": pass,
            },
            success: function(data) {
                let res = data.responseText;
                if (res == 'like') {
                    window.location.reload();
                } else if (res == 'error') {
                    error("редактирования профиля");
                } else if (res == 'нет данных для обновления') {
                    error(res);
                }
            },
            error: function(data) {
                let res = data.responseText;
                if (res == 'like') {
                    window.location.reload();
                } else if (res == 'error') {
                    error("редактирования профиля");
                } else if (res == 'нет данных для обновления') {
                    error(res);
                }
            }
        });
    } else {
        error('заполните хотябы 1 поле');
    }
});

$('.dell-btn').click(function() {
    if (confirm('Вы уверены, что хотите удалить ваш аккаунт навсегда ?')) {
        $.ajax({
            url: "/db/delete.php",
            method: "post",
            dataType: 'json',
            data: {},
            success: function(data) {
                let res = data.responseText;
                if (res == 'error') {
                    error("не получилось удалить профиль");
                } else if (res == 'delete') {
                    window.location.href = '/';
                }
            },
            error: function(data) {
                let res = data.responseText;
                if (res == 'error') {
                    error("не получилось удалить профиль");
                } else if (res == 'delete') {
                    window.location.href = '/';
                }
            }
        });
    }
});


$('.logout-btn').click(function() {
    $.ajax({
        url: "/db/logout.php",
        method: "post",
        dataType: 'json',
        data: {},

        success: function(data) {
            window.location.href = '/';
        },
        error: function(data) {
            window.location.href = '/';
        }
    });
});

async function getcoursesbyid(id) {
    if (id > 0) {
        let res = await $.ajax({
            url: "/db/loadcourses.php",
            method: "post",
            dataType: 'json',
            data: {
                "id": id
            },
            success: function(data) {
                return data;
            },
            error: function(data) {
                return data;
            }
        });
        return await res;
    }
}

async function loadcourses() {
    var id = siteuserid;
    var res = await getcoursesbyid(id);
    $('.subc').html('<h2>Подписки</h2>');
    if (res[0].name == 'нету курсов') {
        $('.subc').html('<h2>Подписки</h2><p>Пока что нету курсов в подписках</p>');
    } else {
        res.forEach(element => {
            let name = element.name;
            let period = element.period;
            let id = element.id;
            let block = document.createElement('div');
            block.className = 'design-graf';
            block.innerHTML = `
                            <a href="/course?id=${id}" target="_blank" class="dhead">
                                <div class="dhead-nav">
                                    <p>Курс</p>
                                    <h3>${name}</h3>
                                </div>
                                <p>Срок обучения: ${period} месяцев</p>
                            </a>`;
            $('.subc').append(block);
        });
    }
}

async function getallcourses() {
    let res = await $.ajax({
        url: "/db/getcourses.php",
        method: "post",
        dataType: 'json',
        data: {},
        success: function(data) {
            return data;
        },
        error: function(data) {
            return data;
        }
    });
    return await res;
}

async function loadallcourses() {
    var res = await getallcourses();

    $('.all_courses h3').html(`Курсы (${res.length})`)
    $('.first_half').html('');
    res.forEach(element => {
        let name = element.course_name;
        let id = element.courses_id;
        let period = element.period;
        let block = document.createElement('div');
        block.className = 'card1';
        block.innerHTML = `
        <a href="/course?id=${id}" target="_blank" class="dhead">
            <div class="dhead-nav">
                <p>Курс</p>
                <h3>${name}</h3>
            </div>
            <p>Срок обучения: ${period} месяцев</p>
        </a>
        <div data-id="${id}" class="btn_podpicka">Добавить в подписки</div>`;
        $('.first_half').append(block);
    });
    $('.btn_podpicka').click(function() {
        var id = $(this).attr('data-id');
        $.ajax({
            url: "/db/addcourse.php",
            method: "post",
            dataType: 'json',
            data: {
                "course_to_add": id
            },
            success: function(data) {
                let res = data.responseText;
                if (res == 'Курс успешно добавлен в список пользователя.') {
                    good(res);
                } else {
                    error(res);
                }
            },
            error: function(data) {
                let res = data.responseText;
                if (res == 'Курс успешно добавлен в список пользователя.') {
                    good(res);
                } else {
                    error(res);
                }
            }
        });
    });
}



async function getallcourses() {
    let res = await $.ajax({
        url: "/db/getcourses.php",
        method: "post",
        dataType: 'json',
        data: {},
        success: function(data) {
            return data;
        },
        error: function(data) {
            return data;
        }
    });
    return await res;
}

async function getcourse_lessons() {
    let res = await $.ajax({
        url: "/db/getcourselessons.php",
        method: "post",
        dataType: 'json',
        data: {
            "lesson_ids": courseLessons
        },
        success: function(data) {
            return data;
        },
        error: function(data) {
            return data;
        }
    });
    return await res;
}

async function course_lessons() {
    if (courseLessons == 0) {
        $('.first_list').html('<h2>Пока нету уроков ;-)</h2>');
    } else {
        var res = await getcourse_lessons();
        $('.first_list').html('');
        res.forEach(element => {
            var description = element.description;
            var id = element.lesson_id;
            var name = element.lesson_name;
            var block = document.createElement('div');
            block.className = 'card';
            block.innerHTML = `
            <div class="card_text">
                        <h2>${name}</h2>
                        <p>${description}</p>
            </div>
            <a href="/lesson?id=${id}" class="btn">Начать обучение</a>`;
            $('.first_list').append(block);
        });
    }
}

$('#btn-send_lesson').click(function() {
    var text = $('#lessoninput').val();
    if (text != '') {
        $.ajax({
            url: "/db/addanswer.php",
            method: "post",
            dataType: 'json',
            data: {
                "lesson_id": lessonjsid,
                "text": text,
            },
            success: function(data) {
                let res = data.responseText;
                if (res == "error") {
                    error('что-то пошло не так при ответе на урок');
                } else {
                    good(res);
                    setTimeout(function() { window.location.reload(); }, 2000);
                }
            },
            error: function(data) {
                let res = data.responseText;
                if (res == "error") {
                    error('что-то пошло не так при ответе на урок');
                } else {
                    good(res);
                    setTimeout(function() { window.location.reload(); }, 2000);
                }
            }
        });
    }
});
async function getovet() {
    let res = await $.ajax({
        url: "/db/getotvet.php",
        method: "post",
        dataType: 'json',
        data: {
            "lesson_id": lessonjsid,
        },
        success: function(data) {
            return data;
        },
        error: function(data) {
            return data;
        }
    });
    return await res;
}
async function checkforocenka() {
    var res = await getovet();
    if (res != 'запись не найдена') {
        var ocenka = res.appraisal;
        var otvet = res.text;
        if (otvet != '' && ocenka == null) {
            $('.send-less_con .nav div p').html('Оценка: ответ на проверке');
            $('#lessoninput').val(otvet);
        } else if (otvet != '' && ocenka != '') {
            $('.send-less_con .nav div p').html(`Оценка: ${ocenka}`);
            $('#lessoninput').val(otvet);
        }
    }
}

$('#create_less').click(function() {
    var curs = $('#selectkurs').val();
    var name = $('#name').val();
    var description = $('#description').val();
    var objectives = $('#objectives').val();
    var less_steps = $('#less_steps').val();
    var homework = $('#homework').val();
    var text = $('#send_message').val();
    var time = $('#timetable').val();
    var date = $('#day').val();
    if (curs != '') {
        if (name != '') {
            if (description != '') {
                if (less_steps != '') {
                    if (homework != '') {
                        if (text != '') {
                            if (time != '' && date != '') {
                                $.ajax({
                                    url: "/db/addlesson.php",
                                    method: "post",
                                    dataType: 'json',
                                    data: {
                                        "courses_id": curs,
                                        "lesson_name": name,
                                        "description": description,
                                        "steps": less_steps,
                                        "homework": homework,
                                        "objectives": objectives,
                                        "timing": `${date} ${time}`,
                                    },
                                    success: function(data) {
                                        let res = data.responseText;
                                        if (res = 'Урок успешно добавлен.') {
                                            good('урок успешно добавлен');
                                            setTimeout(function() { window.location.reload(); }, 2000);
                                        } else {
                                            error('при добовление урока')
                                        }
                                    },
                                    error: function(data) {
                                        let res = data.responseText;
                                        if (res = 'Урок успешно добавлен.') {
                                            good('урок успешно добавлен')
                                            setTimeout(function() { window.location.reload(); }, 2000);
                                        } else {
                                            error('при добовление урока')
                                        }
                                    }
                                });
                            } else {
                                error('время / дата')
                            }
                        } else {
                            error('текст дз')
                        }
                    } else {
                        error('ДЗ')
                    }
                } else {
                    error('шаги урока')
                }
            } else {
                error('описание урока')
            }
        } else {
            error('имя урока')
        }
    } else {
        error('курс не выбран');
    }

});

$('#cratteacherbtn').click(function() {
    var userid = $('#id').val();
    var course_name = $('#course_name').val();
    var desc = $('#desc').val();
    if (userid > 0) {
        if (course_name != '') {
            if (desc != "") {
                $.ajax({
                    url: "/db/addprepod.php",
                    method: "post",
                    dataType: 'json',
                    data: {
                        "course_name": course_name,
                        "course_info": desc,
                        "user_id": userid
                    },
                    success: function(data) {
                        let res = data.responseText;
                        if (res = "Данные успешно обновлены.") {
                            good(res)
                        } else {
                            error('ошибка реги препода')
                        }
                        console.log(res);
                    },
                    error: function(data) {
                        let res = data.responseText;
                        if (res = "Данные успешно обновлены.") {
                            good(res)
                        } else {
                            error('ошибка реги препода')
                        }
                        console.log(res);
                    }
                });
            } else {
                error('описание курса');
            }
        } else {
            error('название курса');
        }
    } else {
        error('некорректный user id');
    }
});

function error(ms) {
    $('.error').html("Ошибка: " + ms);
    $('.error').removeClass('none');
    setTimeout(function() {
        $('.error').addClass('none');
    }, 5000)
}

function good(ms) {
    $('.error').html(ms);
    $('.error').removeClass('none');
    $('.error').addClass("good");
    setTimeout(function() {
        $('.error').addClass('none');
        $('.error').removeClass("good");
    }, 5000)
}