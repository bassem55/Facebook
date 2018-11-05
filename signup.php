<!DOCTYPE html>
<html>
    <head>
        <title>Facebook</title>
        <link type="text/css" rel="stylesheet" href="css/login.css">
    </head>
    <body>

    <div class="navbar">
        <div class="container">
            <div class="brand">
                <h1>facebook</h1>
            </div>
            <div class="form_login">

                    <div class="contai_label_input">
                        <label>Email or phone </label>
                        <input type="text" id="username" placeholder="Email or phone" maxlength="50">
                    </div>
                    <div class="contai_label_input">
                        <label>password </label>
                        <input type="password" id="login_password" placeholder="password"maxlength="50" minlength="2" >
                    </div>
                    <div class="contai_label_input log_button" >
                        <input type="button" value ="Log In" id="log_button" onclick="login()">
                    </div>

            </div>

        </div>
        <div class="container">
            <div class="forgotten">
                <a href="#">Forgotten account?</a>
            </div>
        </div>
    </div>

    <div class="create_new_account aside">

        <h1>create new account</h1>
        <span>It's free and always will be</span>
        <div class="form_signUp">

            <div class="contain_half_width">
                <input class="" type="text" id="first_name" placeholder="First Name" maxlength="20" minlength="3" >
                <input class="surname" type="text" id="last_name" placeholder="surname" maxlength="20">
            </div>
            <div class="contain_full_width">
                <input
                    class="full_width"
                    type="text"
                    id="email_or_phone"
                    placeholder="mobile number or Email">

                <input
                    class="full_width"
                    type="password"
                    id="signup_password"
                    placeholder="new password">
            </div>
            <h3>birthday</h3>
            <div class="contain_selects">
                <select id="day">
                    <option>day</option>
                    <?php for($day = 1 ;$day <= 31 ; $day++){
                        echo "<option value = $day >$day</option>";
                    }
                    ?>
                </select>
                <select id="month">
                    <option>month</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>


                </select>
                <select id="year">
                    <option>year</option>
                    <?php for($year = 1950 ;$year <2018 ; $year++){
                            echo "<option value ='".$year."' >$year</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="radio">
                <input type="radio"  id="male" name='sex' value="male">
                <label for="male">male</label>

                <input type="radio"  id="female" name='sex' value="female">
                <label for='female'>female</label>

            </div>

            <input type="button" class="signUp" id="button"  value="Sign Up" onclick="sign_up()">

        </div>
    </div>

    <div id='cont'></div>
    <div class="image">
        <img>
    </div>
    <div id="error_div">
        <div id="error">errors</div>
        <input type="button" value="Ok" id="error_btn" onmouseover="over()" onmouseout="out()">
    </div>

        <script type="text/javascript" src = "js/signup.js"></script>
    </body>

</html>

