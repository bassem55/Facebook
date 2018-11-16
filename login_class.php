<?php
class login
{
    private  $name;
    private $pass;

    private $con ;

    private $error;
    public function login($server_name , $db_user , $db_pass , $db_name)
    {
        $this->con = new mysqli($server_name , $db_user , $db_pass , $db_name);
    }
    public function start_login($username , $pass)
    {
        $this->name = $username;
        $this->pass = $pass;

        //we want know the input email or phone number
        //we will allow gmail and yahoo emails and we can allow any email type by add condition on if
        if($this->is_email($username,"gmail.com") || $this->is_email($username , "yahoo.com"))
        {

            $email = $this->con->real_escape_string($this->name);
            if ($this->email_found($this->name) == true)//first check if email founded in database or not
            {
                if ($this->check_pass($this->pass) == "good")//if pass just number and chars
                {
                    $select = $this->con->query("SELECT  password FROM login WHERE email ='" . $email . "' AND password ='" . $pass2 . "'");

                    if ($select->num_rows == 1) {
                        $this->error = "member";
                    } else
                        $this->error = "Un Correct Password";
                }
                else if ($this->check_pass($this->pass) == "names" || $this->check_pass($this->pass) == "tag" || $this->check_pass($this->pass) == "special")
                {
                    $select = $this->con->query('SELECT  password FROM login WHERE email ="' . $email . '"');
                    if ($select->num_rows == 1)
                    {
                        while ($row = $select->fetch_assoc())
                        {
                            if ($row['password'] == $pass2)
                            {
                                $this->error = "member";
                            }
                        }

                    }
                }

            }
            else
            {
                $this->error = "Un Correct Email";
            }
        }

        else if($this->is_phone_number($username , 12))
        {

            if ($this->phone_found($username))//first check if phone found or not
            {
                if ($this->check_pass($this->pass) == "good")
                {
                    $select = $this->con->query('SELECT  password FROM login WHERE phone ="' . $username . '"  AND password ="'. $this->pass .'"');

                    if ($select->num_rows > 0)
                    {
                        $this->error = "member";
                    }
                    else
                        {
                        $this->error = 'Un Correct Password';
                    }
                }
                //we want to take care if he but any tag in password but not tell him that it is error
                else if ($this->check_pass($this->pass) == "names" || $this->check_pass($this->pass) == "tag" || $this->check_pass($this->pass) == "special")
                {
                    $select = $this->con->query('SELECT  password FROM login WHERE phone ="' . $username . '"');

                    if ($select->num_rows == 1)
                    {
                        while ($row = $select->fetch_assoc())
                        {
                            if ($row['password'] == $pass2)
                                $this->error = "member";

                        }
                    }
                    else
                    {
                        $this->error = "Un Correct Password";
                    }
                }
            }
            else
            {
                $this->error = "Un Correct Phone Number";
            }
        }
        else
        {
            $this->error = "Un Correct Input Try  Email Or Phone number";
        }

        //finally if error = member so he is real member we wil return true
        //and else we will return the error
        if($this->error == "member")
        {
            return "good";
        }
        else
            return $this->error;

    }
    private function email_found($email)
    {
        $select = 'SELECT email FROM login WHERE email ="' .$email .'"';

        $res = $this->con->query($select);

        if($res->num_rows == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    private function phone_found($phone)
    {
        $select = 'SELECT phone FROM login WHERE phone ="' . $phone . '"';

        $res = $this->con->query($select);

        if($res->num_rows == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function is_email($email , $last_part)
    {
        if(filter_var($email,FILTER_VALIDATE_EMAIL))
        {

            $arr = explode("@",$email);
            if(count($arr) == 2 && $arr[1] == $last_part)
            {
                return true;
            }
            else
                return false;
        }
        else
            return false;

    }
    public function is_phone_number($phone , $len)
    {
        if(strlen($phone) == $len)
        {
            for($i = 0; $i < $len ; $i++)
            {
                if( $phone[$i] == "0" || filter_var($phone[$i] , FILTER_VALIDATE_INT))
                {
                    continue;
                }
                else
                    return false;
            }
            return true;
        }
        else
            return false;

    }
    private function check_pass($pass)
    {
        if(strlen($pass) > strlen(filter_var($pass , FILTER_SANITIZE_STRING)))
        {
            return "tag";
        }
        else if($this->have_space($pass))
        {
            return "names";
        }
        else if(strlen($pass) > strlen($this->con->real_escape_string($pass)))
        {
          return "special";
        }
        else
            return "good";
    }
    private function have_space($string)
    {
        for($i =0 ;$i < strlen($string);$i++)
        {
            if($string[$i] == " ")
                return true;
        }
        return false;
    }
}
?>
<?php
if(isset($_POST['username']) && isset($_POST['password']))
{
    $login = new login("localhost" , "root" , "","facebook");

    $username = $_POST['username'];
    $password = $_POST['password'];

    //if user enter phone number like this 01202873616 we will rescive it 201202873616
    if($login->is_phone_number($_POST['username'] , 11) == true)
    {
      $username = "2" .  $_POST['username'] ;
    }
    else if($login->is_phone_number($_POST['username'] , 12) == true)
    {
      $username = $_POST['username'];
    }
    $output = $login->start_login($username , $password);
    if($output == "good")
    {
        session_start();
        if($login->is_email($username , "gmail.com") == true || $login->is_email($username , "yahoo.com") == true)
        {
          //session username will be the email but without @gmail.com and without @yahoo.com
          $arr = explode("@" , $username);
          $_SESSION['username'] = $arr[0];

          //this cookie will work for year
          setcookie('login' , arr[0] , time() + (86400 * 30 * 12) , '/');
        }
        else if($login->is_phone_number($username , 11) == true || $login->is_phone_number($username , 12) == true)
        {
          //session username will be phone number and in home page will be use him to get some information
          $_SESSION['username'] = $username;

          //this cookie will work for year
          setcookie('login' , $username  , time() + (86400 * 30 * 12) , '/');
        }


        echo 1;
    }
    else
        echo $output;
}
?>
