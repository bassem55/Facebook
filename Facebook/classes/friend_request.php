<?php
class friend
{
    
    private $con;
    public function friend($server_name , $db_user , $db_pass , $db_name)
    {
        $this->con = new mysqli($server_name , $db_user , $db_pass , $db_name);
    }
    
    public function send_friend_request($account_id , $friend_id)
    {
        //friend id will receive a friend request so we will put this request on database(table of friend requests of friend name)
        if($this->is_friend($account_id , $friend_id) == "false")
        {
            $insert = "INSERT INTO _" . $friend_id . "_requests (friend_id) VALUES (".$account_id.")" ;
            $this->con->query($insert);
        }
    }
    public function cancel_my_request($account_id , $friend_id)
    {
        //the account owner send friend request to friend  the account owner want cancel the request
        //so we will remove accounr is from friend_id_requests table
        
        if($this->is_friend($account_id, $friend_id) == "send")
        { 
            $cancel = 'DELETE FROM _' . $friend_id . '_requests WHERE friend_id=' . $account_id;
            $this->con->query($cancel);
        }
    }
    
    public function remove_friend_request($account_id , $friend_id)
    {
        if($this->is_friend($account_id , $friend_id) == "false")
        {
            //we want delete friend_name from friend requests of account_name
            
            $delete = "DELETE FROM _" . $account_id . "_requests  WHERE friend_id=".$friend_id;
            $this->con->query($delete);
        }
    }
    
    public function confirm_friend_request($account_id , $friend_id)
    {
        if($this->is_friend($account_id , $friend_id) == "false")
        {
            //we want delete friend_id from friend requests of account_id
           
            
            $delete = "DELETE FROM _" . $account_id . "_requests  WHERE friend_id=".$friend_id;
            $this->con->query($delete);
            
            //and add him to account_name friends
            $insert = "INSERT INTO _" . $account_id . "_friends (friend_id) VALUES (".$friend_id.")";
            $this->con->query($insert);
            
            //and add account id to friends table of friend
            
            $insert = 'INSERT INTO _' . $friend_id . '_friends (friend_id) VALUES ('.$account_id.')';
            $this->con->query($insert);
            
            
                //we want to create chat table between account name and friend name 
                //table name will big id and _ and small id like this :: 345555_234224
                
                
                if($account_id < $friend_id)
                {
                    $first = $account_id;
                    $last  = $friend_id;
                }
                else
                {
                    $first = $friend_id;
                    $last  = $account_id;
                }
                $create = "CREATE TABLE _".$first . "_" . $last . "
                (
                    ".$first."_num"." INT(6) DEFAULT 0,
                    ".$first."_msg"." TEXT ,
                    ".$last."_num"." INT(6) DEFAULT 0,
                    ".$last."_msg"." TEXT
                );";
                
                $this->con->query($create);
                
                
        }
    }
    private function is_friend($account_id , $friend_id)
    {
        //we want to show if friend_name  founded in  $account_name friends
        $select = $this->con->query("SELECT friend_id FROM _" . $account_id ."_friends WHERE friend_id =".$friend_id);
        if($select->num_rows >0)
        {
            return "friend";
        }
        
        //we want to ask if account_name was send friend_request to friend_name
        $select = $this->con->query("SELECT friend_id FROM _" . $friend_id . "_requests WHERE friend_id=".$account_id);
        if($select->num_rows >0)
        {
            return "send";
        }
        return "false";
    }
    
    private function is_email($email , $last_part)
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
    private function is_phone_number($phone , $len)
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
}
?>
<?php
if(isset($_POST['account_id']) && isset($_POST['friend_id']) && isset($_POST['fun']))
{
    $account_id = $_POST['account_id'];
    $friend_id  = $_POST['friend_id'];
    if($_POST['fun'] == "fr" || $_POST['fun'] == 'cr' || $_POST['fun'] == "rr" || $_POST['fun'] == 'cmr')
    {
        $friend = new friend("localhost" , "root" , "" , "facebook");
        
        
        if($_POST['fun'] == "fr")//friend request
        {
            $friend->send_friend_request($account_id, $friend_id);
            
            echo 1;
        }
        else if($_POST['fun'] == "cmr")//cancel my request
        {
            $friend->cancel_my_request($account_id, $friend_id);
            
            echo 1;
        }
        else if($_POST['fun'] == "cr")//confirm request
        {
            $friend->confirm_friend_request($account_id, $friend_id);
            
            echo 1;
        }
        else if($_POST['fun'] == "rr")//remove request
        {
            $friend->remove_friend_request($account_id, $friend_id);
            
            echo 1;
        }
        else
        {
            echo "error";
        }
    }
}
?>