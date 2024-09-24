<?php
require "../db.php";
require "../mobiledetect.php";
$detect = new Mobile_Detect;

if (isset($_POST)) {
    $data = $_POST;
    $username = $_POST['login'];
    $password = $_POST['password'];


    if(empty($data['login'])) {
        exit ('<script>note({
              content: `<b><span class="object-alert">Авторизация</span><br></b>Введите ваш <b>логин</b>`,
              type: "error",
              time: 1000
            });</script>');


    } else if(empty($password)) {
        exit ('<script>note({
              content: `<b><span class="object-alert">Авторизация</span><br></b>Введите ваш <b>пароль</b>`,
              type: "error",
              time: 10
            });</script>');
    }   else {
            $user = R::findOne('users', 'login = ?', array($data['login']));

            if ($user) {
                if (password_verify($data['password'], $user->password)) {
                    session_start();
                    function generateSalt()
                    {
                        $salt = '';
                        $saltLength = 8; //длина соли
                        for($i=0; $i<$saltLength; $i++) {
                            $salt .= chr(mt_rand(33,126)); //символ из ASCII-table
                        }
                        return $salt;
                    }

                    $detect = new Mobile_Detect;
//                    20005151

//                    $_SESSION['user'] = $user;
                    $_SESSION['auth'] = true;
                    $_SESSION['id'] = $user->id;
                    $_SESSION['login'] = $user->login;

                    $key = generateSalt();
                    setcookie('id', $user->id, time()+60*60*24*30, '/');
                    setcookie('login', $user->login, time()+60*60*24*30, '/');
                    setcookie('key', $key, time()+60*60*24*30, '/');
                    if ($detect->isMobile() && !$detect->isAndroidOS()) {
                        $user->cookie_mobile = $key;
                    } else {
                        $user->cookie = $key;
                    }

                    R::store($user);

                    echo '<script>note({
              content: `<b><span class="object-alert">Авторизация</span><br></b>Вы успешно авторизовались!</b>`,
              type: "success",
              time: 10
            });</script>';
                echo '<script>window.location.replace("../");</script>';
                }
                else {
                    exit ('<script>note({
              content: `<b><span class="object-alert">Авторизация</span><br>Неверный <b>логин или пароль</b>`,
              type: "error",
              time: 10
            });
            </script>');
                }
            }
                else {
                    exit ('<script>note({
              content: `<b><span class="object-alert">Авторизация</span><br>Пользователь </b>не найден<b></b>`,
              type: "error",
              time: 10
            });</script>');
                }



    }
}
?>


