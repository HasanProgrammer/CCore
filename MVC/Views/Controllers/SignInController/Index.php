@Extends('Layout')

@Start('Title', 'CCore-SignInPage')

@Start('StyleSheet')

    <link rel="stylesheet" href="StyleSheet/Controllers/SignInController/Index.css">

@Close('StyleSheet')

@Start('Body')

    <fieldset>
        <legend></legend>
        <div id="Box_Login_Controller">
            <form name="FRM_LOGIN" action="" method="POST">

            <!--S Box's Username-->
            <div id="Box_Title_Input_Username">
                <div id="Title_Input_Username">نام کاربری</div>
            </div>
            <div id="Box_Input_Username">
                <input type="text" name="username">
            </div>
            <div id="Border_Box_Input_Username">
                <div id="Inner_Border_Box_Input_Username"></div>
            </div>
            <!--E Box's Username-->

            <!--S Box's Password-->
            <div id="Box_Title_Input_Password">
                <div id="Title_Input_Password">رمز عبور</div>
            </div>
            <div id="Box_Input_Password">
                <input type="text" name="password">
            </div>
            <div id="Border_Box_Input_Password">
                <div id="Inner_Border_Box_Input_Password"></div>
            </div>
            <!--E Box's Password-->

            <button name="BTN_SUBMIT" type="submit">ورود</button>
            </form>
        </div>
    </fieldset>

@Close('Body')

@Start('JavaScript')

    <script type="text/javascript" src="JavaScript/Controllers/SignInController/Index.js"></script>

@Close('JavaScript')