<?php
    class messanger
    {
        private $con;
        public function messanger($server_name , $db_user , $db_pass , $db_name)
        {
            $this->con = new mysqli($server_name , $db_user , $db_pass , $db_name);
        }
        public function is_friend($account_id , $friend_id)
        {
            //we want to show if friend_name  founded in  $account_name friends
            $select = $this->con->query("SELECT friend_id FROM _" . $account_id ."_friends WHERE friend_id ='".$friend_id."'");
            if($select->num_rows >0)
            {
                return "friend";
            }

            //we want to ask if account_id was send friend_request to friend_name
            $select = $this->con->query("SELECT friend_id FROM _" . $friend_id . "_requests WHERE friend_id='".$account_id."'");
            
            if($select->num_rows >0)
            {
                return "send";
            }
            return "false";
        }
        
       
        public function send_msg($account_id , $friend_id , $msg)
        {
            if($account_id > $friend_id)
            {
                $first_id = $friend_id;
                $last_id = $account_id;
            }
            else if($account_id < $friend_id)
            {
                $first_id = $account_id;
                $last_id = $friend_id;
            }
                
           

            //we want to know last num of msg of poth of them
            $select_num = $this->con->query("SELECT ". $account_id . "_num , ".$friend_id."_num"." FROM _" .$first_id . "_" . $last_id);

            $last_num = 0;
            if($select_num->num_rows >0)
            {
                while($row = $select_num->fetch_assoc())
                {
                    if($row[$account_id . "_num"] > $row[$friend_id . "_num"])
                    {
                        $last_num = $row[$account_id . "_num"];
                    }
                    else
                        $last_num = $row[$friend_id . "_num"];
                }
            }
            $next_num = $last_num + 1;

            //in end we will send msg
            //if $name1 == account_name so it is ok but if name1 != account_name we will make some change
            $insert = 'INSERT INTO _' . $first_id . '_' . $last_id . ' ('. $account_id .'_num , ' . $account_id . '_msg) VALUES ("'.$next_num.'" , "'.$msg.'")';
            
            $this->con->query($insert);
            //msg sent
        }
        public function my_msgs($account_id , $friend_id)
        {
            if($account_id > $friend_id)
            {
                $first_id = $friend_id;
                $last_id = $account_id;
            }
            else if($account_id < $friend_id)
            {
                $first_id = $account_id;
                $last_id = $friend_id;
            }
            
           
            $table_name = "_".$first_id . "_" . $last_id;
            $select = $this->con->query("SELECT " . $account_id . "_num ," .$account_id."_msg ,".$friend_id."_num ,".$friend_id . "_msg FROM " .$table_name);

            $chat ='';
            if($select->num_rows > 0)
            {
                while($row = $select->fetch_assoc())
                {
                    if($row[$account_id . "_num"] != 0 && $row[$friend_id . "_num"] !=0 )//it is meaning that is two msg in row
                    {
                        if($row[$account_id . "_num"] < $row[$friend_id . "_num"])
                        {
                            $chat .= '<div class="to">'.$row[$account_id . "_msg"].'</div><br><br>';
                            $chat .= '<div class="from">'.$row[$friend_id . "_msg"].'</div><br><br>';
                        }
                        else if($row[$account_id . "_num"] > $row[$friend_id . "_num"])
                        {
                            $chat .= '<div class="from">'.$row[$friend_id . "_msg"].'</div><br><br>';
                            $chat .= '<div class="to">'.$row[$account_id . "_msg"].'</div><br><br>';
                        }
                    }
                    else if($row[$account_id . "_num"] != 0 && $row[$friend_id . "_num"] ==0)//it is meaning that is one msg in row
                    {
                        $chat .= '<div class="to">'.$row[$account_id . "_msg"].'</div><br><br>';
                    }
                    else if($row[$account_id . "_num"] == 0 && $row[$friend_id . "_num"] !=0)//it is meaning that is one msg in row
                    {
                        $chat .= '<div class ="from">'.$row[$friend_id . "_msg"].'</div><br><br>';
                    }
                }
            }
            
            return $chat;
        }
       
        
    }
    ?>
<?php
$msg ='';
    $chat = new messanger("localhost" , "root" , "" , "Facebook");
    if(isset($_POST['account_id']) && isset($_POST['friend_id']) && isset($_POST['msg']))
    {
        $chat->send_msg($_POST['account_id'] , $_POST['friend_id'],$_POST['msg']);
        echo 'done';
    }
    else if(isset($_POST['account_id']) && isset($_POST['friend_id']))
    {
        $msg = $chat->my_msgs($_POST['account_id'] , $_POST['friend_id']);
        echo $msg;
    }
    
    
?>
