<?php
session_start();
$db = new SQLite3('../db/db.db');

$user_id = $_SESSION['user_id'];
$stmt = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    // чет если не залогинен чел
}
if ($user['avatar'] == '') {
    $avatar = "/img/avatar.png";
} else {
    $avatar = $user['avatar'];
}
$db->close();

?>
    <header>
        <div class="header_con">
            <div class="left">
                <div class="logo">
                    <img src="/img/logo.svg" alt="logo" />
                    <p>AgatSkill</p>
                </div>
                <a href="/courses" class="check_courses">
                    <img src="/img/Book 2.svg" alt="Book 2" /> Просмотр уроков
                </a>
                <a href="/courses" class="check_courses">
                    <img src="/img/grad.svg" alt="Book 2" /> Мои оценки
                </a>
            </div>
            <div class="profile">
                <a href="/profile?id=<?echo $user['user_id'];?>" class="profile_block">
                    <img src="<?echo $avatar;?>" alt="profile" /> Личный профиль
                </a>
            </div>
        </div>
    </header>