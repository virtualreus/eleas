
<div id="mainBlock">
    <div id="registerImg">
        <div id="registerImgInfo">
            <div id="registerBlurInfo">
                <div id="inform_login"></div>
                <span class="textingDesc">eleas.ru</span>
                <hr style="width: 33%; margin-left: 0;">
                <span class="textingDesc" style="font-size: 18px; margin-top:10px"><b>Мы</b></span>

                <span class="textingDesc textingDescMain">Приглашаем вас<strong> присоедениться к нам!</strong></span>


                <div id="registerLink">
                    <span class="textingDesc" style="font-size: 18px"><b>Еще нет аккаунта?</b></span>
                    <b class="linkDeco"><a class="nonUnderline" href="/signup.php">Зарегестрироваться</a></b>

                </div>
            </div>
        </div>
    </div>



    <div id="login">
        <div class="authInfo">
            <div id="kek1"></div>
            <h1 id="h1Header"><b>Авторизация</b></h1>

            <form action="setting/action_login.php" method="post">
                <span class="authDesc">Введите логин</span>


                <div class="group">
                    <input type="text" name="username" id="login_login" class="login_input" required>
                    <span class="bar"></span>
                    <label class="login_label">Login</label>
                </div>


                <span class="authDesc">Введите пароль</span>


                <div class="group">
                    <input  onkeyup="trigger2()" type="password" name="password"  id="login_password" class="login_input" required>
                    <span class="bar"></span>
                    <label class="login_label">Password</label>
                    <span class="showBtn3"><img src="images/eyeopen.png" width="20px"></span>
                </div>


                <input type="submit" name="enter" id="logInput" value="Войти"><br><br>
            </form>
        </div>
    </div>


</div>


<div id="mobileLogin">
    <div id="mobileDesc">
        <div id="mobileBlurMainBlock">
            <h1 class="mobileTitle">Присоединяйтесь к нам!</h1>
            <span class="mobileBlurDesc">Еще нет аккаунта?</span>
            <a href="/signup" class="mobileBlurDescLink">Зарегестрироваться</a>
            <span class="mobileTextingDesc">eleas.ru</span>
        </div>
    </div>

        <form action="setting/action_login.php" method="post" class="mobileLoginForm">
                <div id="mobileLoginForms">
                    <div class="mobile_group">
                        <span class="authDescMobile">Введите ваш логин</span>
                        <input type="text" name="username" id="login_login_mobile" class="login_input_mobile" placeholder="Login" required>
                    </div>

                    <div class="mobile_group">
                        <span class="authDescMobile">Введите ваш пароль</span>
                        <input type="password" name="username" id="login_password_mobile" class="login_input_mobile" placeholder="Password" required>
                    </div>

                    <input type="submit" name="enter" id="logInputMobile" value="Войти"><br><br>
                </div>
        </form>




</div>


<script>
    const showBtnLogin = document.querySelector(".showBtn3");
    const inputLogin = document.querySelector("#login_password");

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
    }
</script>