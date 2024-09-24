<?
$mysqli = new mysqli("localhost", "nt_3008", "cJ5rP8uT", "project");
require "../db.php";
require "../mobiledetect.php";
$detect = new Mobile_Detect;


if (isset($_POST)) {
    $data = $_POST;
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_c = $_POST['password_c'];


    if (empty($_POST['login'])) {
            echo '<script>note({
              content: `<b><span class="object-alert">Регистрация</span><br></b><b>Придумайте логин.</b>`,
              type: "error",
              time: 10
            });
            </script>';
        }
    elseif (empty($_POST['email'])) {
        echo '<script>note({
              content: `<b><span class="object-alert">Регистрация</span><br></b><b>Введите E-mail</b>`,
              type: "error",
              time: 10
            });</script>';
    } elseif ((filter_var($email, FILTER_VALIDATE_EMAIL)) == false) {
        echo '<script>note({
              content: `<b><span class="object-alert">Регистрация</span><br></b><b>Некоректный E-mail.</b> (корр. example@domain.ru)`,
              type: "error",
              time: 10
            });</script>';
    }

    elseif (stristr($login, " ")) {
        echo '<script>note({
          content: `<b><span class="object-alert">Регистрация</span><br></b><b>Имя пользователя не может содержать пробелов.</b>`,
          type: "error",
          time: 10
        });
        </script>';

    }

    elseif (empty($_POST['password'])) {
        echo '<script>note({
          content: `<b><span class="object-alert">Регистрация</span><br></b><b>Придумайте пароль.</b>`,
          type: "error",
          time: 10
        });
        </script>';

    }

    elseif (!preg_match("/\A(\w){6,20}\Z/", $_POST['password'])) {
        echo '<script>note({
          content: `<b><span class="object-alert">Регистрация</span><br></b>Пароль должен быть <b>от 8 до 20</b> символов`,
          type: "error",
          time: 10
        });
        </script>';

    } elseif (empty($_POST['password_c'])) {
        echo '<script>note({
          content: `<b><span class="object-alert">Регистрация</span><br></b><b>Повторите</b> ваш пароль`,
          type: "error",
          time: 10
        });
        </script>';

    } elseif ($_POST['password'] != $_POST['password_c']) {
        echo '<script>note({
          content: `<b><span class="object-alert">Регистрация</span><br></b>Введенные пароль<b> не совпадают</b>`,
          type: "error",
          time: 10
        });
        </script>';


    } else {



        $checkemail = R::findAll('users', 'email = ?', [$email]);
        $checklogin = R::findAll('users', 'login = ?', [$login]);

        if(!empty($checkemail)) {
            echo '<script>note({
              content: `<b><span class="object-alert">Регистрация</span><br></b>Пользователь с таким E-mail <b>уже зарегистрирован</b>`,
              type: "error",
              time: 10
            });
            </script>';
        }
        else if (!empty($checklogin))
        {
            echo '<script>note({
              content: `<b><span class="object-alert">Регистрация</span><br></b>Пользователь с таким логином <b>уже зарегистрирован</b>`,
              type: "error",
              time: 10
            });
            </script>';
        }

        else {

            if (strlen($login) >= 3 && strlen($login) <= 32) {
                if (preg_match('/[a-zA-Z0-9_]+/', $login)) {


                $user = R::dispense('users');
                $user->login = $data['login'];
                $user->email = $data['email'];
                $user->verification = 0;
                $user->admin = 0;
                $user->ip = $_SERVER['REMOTE_ADDR'];
                $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
                $user->description = "";

                $user->subscribers = "";
                $user->self_subscribe = "";
                $user->saved_data = "";

                $user->cookie = "";
                $user->cookie_mobile = "";

                R::store($user);
                $_SESSION['user'] = $user;

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

                    exit ('<script>note({
                  content: `<b><span class="object-alert">Регистрация</span><br></b><b>Вы успешно зарегестрировались!</b>`,
                  type: "success",
                  time: 10
                });
                setTimeout(() => { window.location.reload(); }, 1000);
                </script>');

                } else {
                    echo '<script>note({
              content: `<b><span class="object-alert">Регистрация</span><br></b>Некорректный username`,
              type: "error",
              time: 10
            });
            </script>';
                }

            }
            else {
                echo '<script>note({
              content: `<b><span class="object-alert">Регистрация</span><br></b>Username должен быть длинной от 3 до 32 симоволов.`,
              type: "error",
              time: 10
            });
            </script>';
            }
        }

    }
}




?>