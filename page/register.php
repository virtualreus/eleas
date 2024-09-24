<!--<div id="Register">-->
<!--    <h1><span class="titlePage"><b>Регистрация</b></span></h1>-->

<!--    <form action="setting/action_register.php" id="regForm">-->
<!--        <input type="text" name="login" id="register_login" class="reginput" placeholder="Логин"><br>-->
<!--        <input type="email" name="email" id="register_email" class="reginput" placeholder="E-Mail"><br>-->
<!--        <input type="password" name="password" id="register_password" class="reginput" placeholder="Пароль"><br>-->
<!--        <input type="password" name="password_confirm"  id="register_password_confirm" class="reginput" placeholder="Подтвердите пароль"><br>-->
<!--        <input type="submit" name="enter" id="regInput" value="зарегестрироваться">-->
<!--    </form>-->
<!--</div>-->


<div id="mainBlock">
    <div id="inform"></div>
    <div id="registerImg">
        <div id="registerImgInfo">
            <div id="registerBlurInfo">
                <div id="inform_login"></div>
                <span class="textingDesc">eleas.ru</span>
                <hr style="width: 33%; margin-left: 0;">
                <span class="textingDesc" style="font-size: 18px; margin-top:10px"><b>Мы</b></span>

                <span class="textingDesc" style="font-size: 30px;  margin-top:9px">Приглашаем вас<strong> присоедениться к нам!</strong></span>

                <div id="registerLink">
                    <span class="textingDesc" style="font-size: 18px"><b>Уже есть аккаунт?</b></span>
                    <b class="linkDeco"><a class="nonUnderline" href="/signin.php">Войти</a></b>

                </div>
            </div>
        </div>
    </div>



    <div id="login">
        <div class="authInfo">
            <div id="kek1"></div>
            <h1 id="h1Header"><b>Регистрация</b></h1>

            <form action="setting/action_login.php" method="post">


                <span class="authDesc">Введите ваш логин</span>
                <div class="group">
                    <input type="text" name="username" id="register_login" class="login_input" required>
                    <span class="bar"></span>
                    <label class="login_label">Login</label>
                </div>

                <span class="authDesc">Введите ваш E-mail</span>
                <div class="group">
                    <input type="email" name="email" id="register_email" class="login_input" required>
                    <span class="bar"></span>
                    <label class="login_label">E-mail</label>
                </div>


                <span class="authDesc">Введите пароль</span>
                <div class="group">
                    <input  onkeyup="trigger2()" type="password" name="password"  id="register_password" class="login_input" required>
                    <span class="bar"></span>
                    <label class="login_label">Password</label>
                    <span class="showBtn2"><img src="images/eyeopen.png" width="20px"></span>
                </div>


                <span class="authDesc">Подтвердите пароль</span>
                <div class="group">
                    <input  onkeyup="trigger2()" type="password" name="password_confirm"  id="register_password_confirm" class="login_input" required>
                    <span class="bar"></span>
                    <label class="login_label">Password</label>
                    <span class="showBtn3"><img src="images/eyeopen.png" width="20px"></span>
                </div>
                <input type="submit" name="enter" id="regInput" value="Зарегестрироваться"><br><br>
            </form>
        </div>
    </div>

</div>

<div id="mobileLogin">
    <div id="mobileDesc">
        <div id="mobileBlurMainBlock">
            <h1 class="mobileTitle">Присоединяйтесь к нам!</h1>
            <span class="mobileBlurDesc">Уже есть аккаунт?</span>
            <a href="/signin" class="mobileBlurDescLink">Авторизироваться</a>
            <span class="mobileTextingDesc">eleas.ru</span>
        </div>
    </div>

    <form action="setting/action_login.php" method="post" class="mobileRegForm">
        <div id="mobileLoginForms">
            <div class="mobile_group">
                <span class="authDescMobile">Введите ваш логин</span>
                <input type="text" name="username" id="register_login_mobile" class="login_input_mobile" placeholder="Login" required>
            </div>

            <div class="mobile_group">
                <span class="authDescMobile">Введите ваш e-mail</span>
                <input type="email" name="username" id="register_email_mobile" class="login_input_mobile" placeholder="E-mail" required>
            </div>

            <div class="mobile_group">
                <span class="authDescMobile">Введите ваш пароль</span>
                <input type="password" name="username" id="register_password_mobile" class="login_input_mobile" placeholder="Password" required>
            </div>

            <div class="mobile_group">
                <span class="authDescMobile">Повторите ваш пароль</span>
                <input type="password" name="username" id="register_password_confirm_mobile" class="login_input_mobile" placeholder="Confirm password" required>
            </div>

            <input type="submit" name="enter" id="regInputMobile" value="Зарегестрироваться"><br><br>

        </div>
    </form>
</div>



<style>
    .group {
        margin-bottom: 15px;
        margin-top: 10px;
    }

    #mobileLogin {
        height: 1500px;
        padding-bottom: 5%;
    }
</style>
<script>
    const showBtnLogin = document.querySelector(".showBtn2");
    const inputLogin = document.querySelector("#register_password");

    const showBtnLogin2 = document.querySelector(".showBtn3");
    const inputLogin2 = document.querySelector("#register_password_confirm");


    function trigger2() {
        showBtnLogin.onclick = function () {
            if (inputLogin.type == "password") {
                inputLogin.type = "text";
                showBtnLogin.innerHTML = `<img src="images/eyeclosed.png" width="20px">`;
                showBtnLogin.style.color = "#23ad5c";
            } else {
                inputLogin.type = "password";
                showBtnLogin.innerHTML = `<img src="images/eyeopen.png" width="20px">`;
                showBtnLogin.style.color = "#000";
            }
        }


        showBtnLogin2.onclick = function () {
            if (inputLogin2.type == "password") {
                inputLogin2.type = "text";
                showBtnLogin2.innerHTML = `<img src="images/eyeclosed.png" width="20px">`;
                showBtnLogin2.style.color = "#23ad5c";
            } else {
                inputLogin2.type = "password";
                showBtnLogin2.innerHTML = `<img src="images/eyeopen.png" width="20px">`;
                showBtnLogin2.style.color = "#000";
            }
        }
    }
</script>