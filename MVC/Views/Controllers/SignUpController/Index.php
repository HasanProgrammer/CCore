@Extends('Layout')

@Start('Title', 'SignUpPage')

@Start('StyleSheet')

    <link rel="stylesheet" href="StyleSheet/Controllers/SignUpController/Index.css">

@Close('StyleSheet')

@Start('Body')

    <fieldset>
        <legend></legend>
        <div id="Box_SignUp_Controller">
            <form name="FRM_SIGNUP" action="" method="POST">
            <div id="Box_Form_1">
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
                <a href="SignIn">
                <button name="BTN_BACK" type="button" title="Login">صفحه ی ورود</button>
                </a>
            </div>
            <div id="Box_Form_2">
                <!--S Box's Email-->
                <div id="Box_Title_Input_Email">
                    <div id="Title_Input_Email">پست الکترونیکی</div>
                </div>
                <div id="Box_Input_Email">
                    <input type="text" name="email">
                </div>
                <div id="Border_Box_Input_Email">
                    <div id="Inner_Border_Box_Input_Email"></div>
                </div>
                <!--E Box's Email-->

                <!--S Box's Phone-->
                <div id="Box_Title_Input_Phone">
                    <div id="Title_Input_Phone">شماره تماس</div>
                </div>
                <div id="Box_Input_Phone">
                    <input type="text" name="phone">
                </div>
                <div id="Border_Box_Input_Phone">
                    <div id="Inner_Border_Box_Input_Phone"></div>
                </div>
                <!--E Box's Phone-->
                <button name="BTN_SUBMIT" type="submit" title="SignUp">ثبت اطلاعات</button>
            </div>
            </form>
        </div>
    </fieldset>

@Close('Body')

@Start('JavaScript')

    <script type="text/javascript" src="JavaScript/Controllers/SignUpController/Index.js"></script>

@Close('JavaScript')