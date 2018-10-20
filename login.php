<?php
    class login
    {
        private  $name;
        private  $name_error ;
        private $pass;
        private $pass_error;

        private $con ;
        public function login($server_name , $db_user , $db_pass , $db_name)
        {
            $this->con = new mysqli($server_name , $db_user , $db_pass , $db_name);
        }
        public function start_login($name2 , $pass2)
        {
            $this->name = $name2;

            $fn_output = $this->check_name($name2);
            if($fn_output == "tag")
                $this->name_error =  "do not write tag or any fucking things again";

            else if($fn_output == "names")
                $this->name_error = "you write more than name";

            else if($fn_output == "good")
                $this->name_error = "good";

            $this->pass = $pass2;
            $this->pass_error = $this->check_pass($pass2);

            if($this->name_error == "good")
            {

                if($this->pass_error == "good")
                {
                    $select = $this->con->query('SELECT name , password FROM login WHERE name ="' . $this->name . '" AND password ="' . $this->pass . '" ');

                    if($select->num_rows > 0)
                    {
                        $this->name_error = "member";
                        $this->pass_error = "member";
                    }
                    else
                     {
                         $this->name_error = "not member";
                         $this->pass_error = "not member";
                     }
                }
                else if($this->pass_error == "tag" || $this->pass_error == "names")
                {
                    $select = $this->con->query('SELECT name , password FROM login WHERE name ="' . $this->name .'"');

                    if($select->num_rows > 0)
                    {
                        while($row = $select->fetch_assoc())
                        {
                            if($row['password'] == $this->pass)
                            {
                                $this->name_error = "member";
                                $this->pass_error = "member";
                            }
                        }
                    }
                    else
                    {
                        $this->name_error = "not member";
                        $this->pass_error = "not member";
                    }


                }
            }
        }
        private function check_name($name)
        {
            $l1 = strlen($name);
            $l2 = strlen(filter_var($name , FILTER_SANITIZE_STRING));

            if($l1 > $l2)
                return "tag";

            else if($this->have_space($name))
                return "names";

            else
                return "good";

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
        public function get_name_error()
        {
            return $this->name_error;
        }
        public function get_pass_error()
        {
            return $this->pass_error;
        }
    }
//here will become the design of login page
