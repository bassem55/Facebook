function sign_up()
{
    let first_name = document.getElementById("first_name"),
        last_name  = document.getElementById("last_name"),
        email_or_phone = document.getElementById("email_or_phone"),
        pass       = document.getElementById("signup_password"),
        day        = document.getElementById("day"),
        month      = document.getElementById("month"),
        year       = document.getElementById("year"),
        sex        = document.getElementsByName("sex");

    let error = document.getElementById("error"),
        error_div = document.getElementById("error_div");

    day = day.options[day.selectedIndex].value;
    month = month.options[month.selectedIndex].value;
    year = year.options[year.selectedIndex].value;

    if(first_name.value == "" || last_name.value == "" || email_or_phone.value == "" || pass.value == "" || (!sex[0].checked && !sex[1].checked) || day == "day" || month == "month" || year == "year")
    {
        if(first_name.value === "")
        {
            show_error(error_div , error , "Please Enter Your First Name");
        }
        else if(last_name.value == "")
        {
            show_error(error_div , error , "Please Enter Your Last Name");
        }
        else if(email_or_phone.value == "")
        {
            show_error(error_div , error , "Please Enter Your Email Or Phone Number");
        }
        else if(pass.value == "")
        {
            show_error(error_div , error , "Please Enter Your Password");
        }
        else if(day == "day" || month == "month" || year == "year")
        {
            show_error(error_div,error,"Please Enter Full Birthday");
        }
        else if((!sex[0].checked && !sex[1].checked))
        {
            show_error(error_div,error,"Please choose your Gender");
        }
    }
    else
    {
        if(sex[0].checked)
        {
            sex = sex[0].value;
        }
        else if(sex[1].checked)
        {
            sex = sex[1].value;
        }
        let req = new XMLHttpRequest();
        req.onreadystatechange = function()
        {
            if (req.status === 200 && req.readyState === 4)
            {
                if (req.responseText == 1)
                {
                    window.location.assign("profile.php");
                    //show_error(error_div , error , "Sign Up Done Welcome To Facebook ");

                }
                else
                {
                    show_error(error_div , error ,  req.responseText);
                }
            }
        };
        req.open("POST", "classes/signup.php", true);
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        req.send("first_name=" + first_name.value + "&last_name=" + last_name.value + "&email_or_phone=" + email_or_phone.value + "&password=" + pass.value + "&year=" + year + "&month=" + month + "&day=" + day + "&sex=" + sex);
    }

}
function login()
{
    let username = document.getElementById("username"),
        pass     = document.getElementById("login_password");


    let error = document.getElementById("error"),
        error_div = document.getElementById("error_div");

    if(username.value == "" || pass.value == "")
    {
        if(username.value == "" && pass.value == "")
        {
            show_error(error_div ,error , "Enter Username And Password To Access To Your Account");
        }
        else if(username.value == "" && pass.value != "")
        {
            show_error(error_div ,error , "Enter Username To Access To Your Account");
        }
        else if(username.value != "" && pass.value == "")
        {
            show_error(error_div ,error , "Enter Password To Access To Your Account");
        }
    }
    else
    {
        let req = new XMLHttpRequest();

        req.onreadystatechange = function ()
        {
            if(req.status === 200 || req.readyState === 4)
            {
                if(req.responseText == 1)
                {
                    window.location.assign("profile.php");
                    //show_error(error_div , error , "Log In Done Welcome To Facebook");
                }
                else
                {
                    show_error(error_div , error , req.responseText);
                }
            }
        };
        req.open("post" , "classes/login.php" , true);
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        req.send("username=" + username.value + "&password=" + pass.value);
    }

}
function show_error(output , output_txt , error)
{
    output.style.display = "block";
    output_txt.innerHTML = error;
}
let error_btn = document.getElementById("error_btn"),
    error_div = document.getElementById("error_div");

error_btn.onclick = function()
{
    error_div.style.display = "none";
};

function over()
{
    error_btn.style.color = "#e7eede";
}
function out()
{
    error_btn.style.color = "#000";
}