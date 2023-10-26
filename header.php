
    <header>
        <div class="header_con">
            <div class="left">
                <a href="/" class="logo">
                    <img src="/img/logo.svg" alt="logo" />
                    <p>AgatSkill</p>
                </a>
                <?php
                if ($user != 'no') {
                    echo '
                    <a href="/courses" class="check_courses">
                    <img src="/img/Book 2.svg" alt="Book 2" /> Просмотр курсов
                </a>
                ';
                // <a href="/courses" class="check_courses">
                //     <img src="/img/grad.svg" alt="Book 2" /> Мои оценки
                // </a>
                }
                ?>             
            </div>
            <div class="profile">
            <?php
                    if ($user == 'no') {
                        echo '<a class="login_btn_header" href="/signin"> <img src="/img/User Plus Rounded.svg"> Войти в аккаунт</a>';
                    } else {
                    $userheaderid = $_SESSION['user_id'];
                    if ($mainavatar == '') {
                        $mainavatar = '/img/avatar.png';
                    }
                    echo '
                        <a href="/profile?id='.$userheaderid.'" class="profile_block">
                            <img src="'.$mainavatar.'" alt="profile" /> Личный профиль
                        </a>';
                    }
            ?>
            </div>
        </div>
    </header>