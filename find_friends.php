<?php
class friend_things
{
    private $con;
    private $account_id;
    public function friend_things( $account_id , $host , $user , $pass , $dbname)
    {
        $this->con = new mysqli($host , $user , $pass , $dbname);
        $this->account_id  = $account_id; 
    }
    public function show_friend_requests()
    {
        
        $select = $this->con->query('SELECT friend_id FROM _' . $this->account_id . '_requests');
        
        $data = '';
        if($select->num_rows >0)
        {
            while($row = $select->fetch_assoc())
            {
                $name = $this->get_name($row['friend_id']);
                $data .= '<div class="surronded">
                        <div class="friends" id="friend_div_'.$row['friend_id'].'">
                           <div class="image">
                           <img>
                           </div>
                           <div class="head"><a class="friend_name"  href="home.php?id='.$row['friend_id'].'">'.$name.'</a>
                           <!--<span> about this frinds </span>-->
                       </div>
                       <div class="control" id="control'.$row['friend_id'].'">
                            <input type="button" class="confirm" id="c-friend'.$row['friend_id'].'" value="Confirm" onclick=confirm("'.$row['friend_id'].'")>
                            <input type="button" class="remove"  id="r-friend'.$row['friend_id'].'" value="delete" onclick=remove("'.$row['friend_id'].'")>
                      </div>
                      </div>
                    </div>';
            }
        }
        return $data;
    }
    private function get_name($id)
    {
        
        
        $select = $this->con->query('SELECT first_name , last_name FROM signup WHERE id =' . $id) ;
        
        $name = '';
        if($select->num_rows >0)
        {
            while($row = $select->fetch_assoc())
            {
                $name = $row['first_name'] . ' ' . $row['last_name'];
            }
        }
        
        return $name;
        
    }
    private function friend_status($account_id,$friend_id)
    {
        //first check if  he is a friend
        $select1 = $this->con->query('SELECT friend_id FROM _'.$account_id . '_friends WHERE friend_id =' . $friend_id);
        if($select1->num_rows == 1)
        {
            return 'friend';
        }
        //secand check if friend was sent friend request to account 
        $select2 = $this->con->query('SELECT friend_id FROM _'.$account_id . '_requests WHERE friend_id=' .$friend_id );
        if($select2->num_rows == 1)
        {
            return 'request';
        }
        //third check if account was sent friend request to friend
        $select3 = $this->con->query('SELECT friend_id FROM _' . $friend_id . '_requests WHERE friend_id=' . $account_id);
        if($select3->num_rows == 1)
        {
            return 'sent';
        }
        
        return 'normal';//if he come here so he search about new email or any thing
    }
    private function get_last_part($id , $status)
    {
        if($status == 'friend')//if he friend do not any button
        {
            $data = ' <div class="control" id="control'.$id.'">
                            <div class="confirm">Friends</div>
                      </div>
                      </div>
                    </div>';
        }
        else if($status == 'request')//if friend that i search about him send request to me
        {
            $data = '<div class="control" id="control'.$id.'">
                            <input type="button" class="confirm" id="c-friend'.$id.'" value="Confirm" onclick=confirm("'.$id.'")>
                            <input type="button" class="remove"  id="r-friend'.$id.'" value="delete" onclick=remove("'.$id.'")>
                      </div>
                      </div>
                    </div>';
        }
        else if($status == 'sent')//if account was sent friend request
        {
            $data = '  <div class="control" id="control'.$id.'">
                            <input type="button" class="b-req"  id="b-req'.$id.'" value="Cancel Friend Request" onclick=cancel_request("'.$id.'")>
                      </div>
                      </div>
                    </div>';
        }
        else 
        {
            $data = '<div class="control" id="control'.$id.'">
                            <input type="button" class="confirm" id="a-friend'.$id.'" value="Add Friend" onclick=send_request("'.$id.'")>
                      </div>
                      </div>
                    </div>';
        }
        
        return $data;
    }
    private function  get_all_data( $account_id , $friend_id , $first_name , $last_name)
    {
        $data = '<div class="surronded">
                        <div class="friends" id="friend_div_'.$friend_id.'">
                           <div class="image">
                           <img>
                           </div>
                           <div class="head"><a class="friend_name"  href="home.php?id='.$friend_id.'">'.$first_name . ' ' . $last_name.'</a>
                           <!--<span> about this frinds </span>-->
                       </div>';
        
        $status = $this->friend_status($account_id, $friend_id);
        $data .= $this->get_last_part($friend_id, $status);
        
        return $data;
    }
    public function search($search)
    {
        $data = '';
        if($this->is_email($search , "gmail.com") || $this->is_email($search , "yahoo.com"))
        {
            $select =$this->con->query('SELECT id , first_name , last_name FROM signup WHERE email="' . $search . '"');
            
            if($select->num_rows == 1)
            {
                while($row = $select->fetch_assoc())
                {
                    $data = $this->get_all_data( $this->account_id , $row['id'] , $row['first_name'], $row['last_name']);
                }
               
            }
            
            return $data;
        }
        else if($this->is_phone_number($search , 12) || $this->is_phone_number( "2" .$search , 12))
        {
            $select1 = $this->con->query('SELECT id , first_name , last_name FROM signup WHERE phone="' . $search . '"');
            if($select1->num_rows == 1)
            {
                while($row = $select->fetch_assoc())
                {
                    $data = $this->get_all_data( $this->account_id , $row['id'] , $row['first_name'], $row['last_name']);
                }
                
            }
            
            return  $data;
            
        }
        else if($this->is_email_without_end($search))
        {
            $email1 = $search . "@gmail.com";
            $email2 = $search . "@yahoo.com";
            $select = $this->con->query('SELECT id , first_name , last_name FROM signup WHERE email="' .$email1 .'" OR email="' . $email2 . '"');
            
            if($select->num_rows >0)
            {
                while($row = $select->fetch_assoc())
                {
                    $data = $this->get_all_data( $this->account_id , $row['id'] , $row['first_name'], $row['last_name']);
                }
            }
        }
        else
        {
            $name_count = $this->name_count($search);
            
            if($name_count == 1)
            {
                $select = $this->con->query('SELECT id , first_name , last_name FROM signup WHERE first_name ="' . $search . '" OR last_name="' . $search .'"');
                
                if($select->num_rows > 0)
                {
                    while($row = $select->fetch_assoc())
                    {
                        $data = $this->get_all_data( $this->account_id , $row['id'] , $row['first_name'], $row['last_name']);
                    }
                }
            }
            else if($name_count == 2)
            {
                $arr = explode(" " , $search);
                
                $select1 = $this->con->query('SELECT id , first_name , last_name FROM signup WHERE first_name="' . $arr[0] .'" AND last_name="' . $arr[1] . '"');
                $select2 = $this->con->query('SELECT id , first_name , last_name FROM signup WHERE first_name="' . $arr[1] .'" AND last_name="' . $arr[0] . '"');
                
                if($select1->num_rows > 0)
                {
                    while($row = $select1->fetch_assoc())
                    {
                        $data .= $this->get_all_data( $this->account_id , $row['id'] , $row['first_name'], $row['last_name']);
                    }
                }
                
                if($select2->num_rows >0)
                {
                    while($row = $select2->fetch_assoc())
                    {
                        $data .= $this->get_all_data( $this->account_id , $row['id'] , $row['first_name'], $row['last_name']);
                    }
                }
                
                return $data;
            }
        }
        
        return 'null';
    }
    private function is_email_without_end($email)
    {
        $email1 = $email . "@gmail.com";
        $email2 = $email . "@yahoo.com";
        $select = $this->con->query('SELECT email FROM login WHERE email="' .$email1 .'" OR email="' . $email2 . '"');
        
        if($select->num_rows >0)
        {
            return true;
        }
        else 
            return false;
    }
    private function name_count($name)
    {
        $arr = explode(" " , $name);
        
        $count = count($arr);
        
        
        return $count;
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
$data = '';
$id = '';
    session_start();
    
   
   if(isset($_SESSION['id']) || isset($_COOKIE['login']))
    {
        
        
        
        if(isset($_SESSION['id']))
            $id = $_SESSION['id'];
        else if(isset($_COOKIE['login']))
            $id = $_COOKIE['login'];
        
        $friend = new friend_things( $id , "localhost" , "root" , "" , "facebook");
       
        if((isset($_SESSION['id']) || isset($_COOKIE['login'])) && isset($_GET['search']))
        {
            $data = $friend->search($_GET['search']);
        }
        else
        {
            $data =  $friend->show_friend_requests($id);
        }    
           
    }
    else 
        header('Location: http://localhost/websites/Facebook/signup.php');
?>
<html>
    <head>
    	<title>Facebook</title>
    	<link rel="stylesheet" href="css/find_friends.css">
    	<link rel="stylesheet" href="css/font-awesome.min.css">
    	<link rel="stylesheet" href="css/normalize.css">
    	
    	<script type="text/javascript">


			function send_request(friend_id)
			{
				let id = <?php echo $id; ?> ;
            	let req = new XMLHttpRequest();
        		
            	req.onreadystatechange = function()
        		{
        			if(req.readystate === 4 || req.status === 200)
        			{
        				if(req.responseText == 1)
        				{
            				
        					let btn1 = document.getElementById("a-friend" + friend_id);
        					
    					    btn1.setAttribute('class' , 'b-req');
    					    btn1.setAttribute('onclick' , 'cancel_request("' + friend_id + '")');
    					    btn1.setAttribute('id' , 'b-req' + friend_id);
    					    btn1.setAttribute('value' , 'Cancel Friend Request');

    					    
        				}
        			}
        		};
        		
        		req.open("POST" , "classes/friend_request.php" , true);
        		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        		req.send("account_id=" + <?php echo $id; ?> + "&friend_id=" + friend_id + "&fun=fr");
			}
			function cancel_request(friend_id)
			{
				
				
				let id = <?php echo $id; ?> ;
            	let req = new XMLHttpRequest();
        		
            	req.onreadystatechange = function()
        		{
            		
        			if(req.readystate === 4 || req.status === 200)
        			{
            			
        				if(req.responseText == 1)
        				{
        					let btn1 = document.getElementById("b-req" + friend_id);
        					
    					    btn1.setAttribute('class' , 'confirm');
    					    btn1.setAttribute('onclick' , 'send_request("' + friend_id + '")');
    					    btn1.setAttribute('id' , 'a-friend' + friend_id);
    					    btn1.setAttribute('value' , 'Add Friend');

    					    
        				}
        			}
        		};
        		
        		req.open("POST" , "classes/friend_request.php" , true);
        		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        		req.send("account_id=" + <?php echo $id; ?> + "&friend_id=" + friend_id + "&fun=cmr");
			}
        	function confirm(friend_id)
        	{
            	let id = <?php echo $id; ?> ;
            	let req = new XMLHttpRequest();
        		
            	req.onreadystatechange = function()
        		{
        			if(req.readystate === 4 || req.status === 200)
        			{
        				if(req.responseText == 1)
        				{
            				
        					let btn1 = document.getElementById("c-friend" + friend_id),
        					    btn2 = document.getElementById("r-friend" + friend_id);

        					
    					    btn2.style.display = "none";

    					    btn1.setAttribute("class" , "b-friends");
    					    btn1.setAttribute("onclick" , "");
    					    btn1.setAttribute("id" , "b-friends");
    					    btn1.setAttribute("value" , "friends");
    					    
        				}
        			}
        		};
        		
        		req.open("POST" , "classes/friend_request.php" , true);
        		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        		req.send("account_id=" + <?php echo $id; ?> + "&friend_id=" + friend_id + "&fun=cr");
        	}
        	function remove(friend_id)
        	{
            	let id = <?php echo $id; ?> ;
            	let req = new XMLHttpRequest();
        		
            	req.onreadystatechange = function()
        		{
        			if(req.readystate === 4 || req.status === 200)
        			{
        				if(req.responseText == 1)
        				{
            				
        					let friend_div = document.getElementById("friend_div_" + friend_id);

        					
    					    friend_div.style.display = "none";  
        				}
        			}
        		};
        		
        		req.open("POST" , "classes/friend_request.php" , true);
        		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        		req.send("account_id=" + <?php echo $id; ?> + "&friend_id=" + friend_id + "&fun=rr");
        	}
    	</script>
    </head>
    <body>
    	<div class="container_friends">
            <div class="your_friends_request">
            	<h3>Respond to Your Friend Requests</h3>
            	<div class="requests"><?php echo $data; ?></div>
            </div>
            </div>
           
            <div class="container_friends">
            	<div class="people_you_now">
            		<h3>people you maybe now</h3>
            	</div>
            </div>
    </body>
</html>
