<?php

class profile
{
    private $con ;
    public function profile($host , $dbuser , $dbpass , $dbname)
    {
        $this->con = new mysqli($host , $dbuser , $dbpass , $dbname);
    }
    public function get_name($id)
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
    public function show_friends($account_id)
    {
        $output = '';
       
        $select =$this->con->query('SELECT friend_id FROM _'. $account_id . '_friends');
        if($select->num_rows > 0)
        {
            while($row = $select->fetch_assoc())
            {
                $name = $this->get_name($row['friend_id']);
                $output .='<input type="button" class="friend_btn" value="'.$name.'"  onclick="show(\''. $name .'\' , '. $row['friend_id'] .')">';
            }
        }
        else
        {
            $output = '';
        }
        
        return $output;
    }
}


?>
<?php
$name = '';
$friends = '';
if(isset($_SESSION['id']) || isset($_COOKIE['login']))
{
    if(isset($_SESSION['id']))
        $id = $_SESSION['id'];
    else if(isset($_COOKIE['login']))
        $id = $_COOKIE['login'];
    
    
    $profile = new profile("localhost" , "root" , "" , "facebook");
    $name = $profile->get_name($id);
    $friends = $profile->show_friends($id);
}
else
    header('Location: http://localhost/websites/Facebook/signup.php');
?>
<html>
	<head>
		<title>Facebook</title>
    	<link rel="stylesheet" type="text/css" href="css/login.css">
    	<link rel="stylesheet" type="text/css" href="css/profile.css">
    	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    	<link rel="stylesheet" type="text/css" href="css/find_friends.css">
		<meta charset="UTF-8">
		
		<script type="text/javascript">
    		function get_msgs(friend_id)
    		{
    			
        		var room = "data1";
        		if(friend_id == public_room1_value)
            		room = "data1";
        		else if(friend_id == public_room2_value)
            		room = "data2";
        		else if(friend_id == public_room3_value)
            		room = "data3";

        		
    			 let req = new XMLHttpRequest();
    			 req.onreadystatechange = function(){
        			 
    				 if(req.readystate === 4 || req.status === 200)
    				 {
    					 document.getElementById(room).innerHTML = req.responseText;
    				 }
    					
    			 };
    			 
    			 req.open("POST" , "classes/messanger.php" , true);
    			 req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    			req.send("account_id=" + <?php echo $id; ?> + "&friend_id=" + friend_id);
    		}
    		function send(num)
    		{
        		
        		if(num == '1' || num == '2' || num == '3')
            	{
        			var friend_id;
        			var msg_name;
                	if(num == '1')
                    {
                		 
                		 friend_id = public_room1_value;
                		 msg_name = "msg1";
                		 
                    }   
                	else if(num  == '2')
                    {
                		friend_id = public_room2_value;
                		msg_name = "msg2";
                    }  
                	else
                    {
                		friend_id = public_room3_value;
                		msg_name = "msg3";
                    }
                    	
					let msg = document.getElementById(msg_name);
					if(msg.value != "")
					{
						let req = new XMLHttpRequest();

	                	req.onreadystatechange = function(){

	                    	if(req.readystate === 4 || req.status === 200)
	                        {
		                        
	                            if(req.responseText === 'done')
	                            {
	                            	msg.value = "";
	                            }
	                        }
	                    };
	                    req.open("POST" , "classes/messanger.php" , true);
	           			req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	           			req.send("account_id=" + <?php echo $id; ?> + "&friend_id=" + friend_id + "&msg=" + msg.value);
					}
                	
            	}
        	}
		</script>
	</head>
	<body>
		<div class="row header main-back"><!-- Start of class row -->
            <div class="contanier"><!--Start container class-->
            
             <div class="col-1 logo col-m-12"><!-- Start of logo class-->
                 <span class="display"> <a> <i class="fa fa-facebook main-color"> </i> </a> </span>
             </div><!--End of logo class-->
             
             <div class=" col-4 search margin-top"><!-- Start of search class-->
                 <form action="find_friends.php" method="get">
                     <input type="search" name="search" placeholder="Search">
                     <button> <i class="fa fa-search"> </i></button>
                     
                 </form>
             </div><!-- End of search class-->
             
             <div class=" col-3 margin-top links "><!-- Start of links class-->
                 <ul>
                        <li title="profile"> <img src="http://localhost/websites/facebook/images/default.jpg"> <a href="#"><?php echo $name; ?></a> </li>
                        <li> <a href="#">Home </a> </li>
                        <li> <a href="find_friends.php">Find Friends </a> </li>
                    
                 </ul>
             </div><!-- End of links class-->
             
             <div class=" col-3 margin-top contact"><!-- Start  of contact class-->
             	
                <ul>
                   <li id="click">
                    <a href="#"><img src="http://localhost/websites/facebook/images/images.png"> </a>
                    <span id= "tool"class="tooltiptext">Find Friends</span>
                  
                        <div id="Friend_request" class="Firend-requested hidden"><!--Start of class Firend requested-->
                        
                                   <div class="header"><!--Start of class header for firends requested-->
                                          <span>Firend request </span>
                                            <a href="#"> Sitting </a>
                                            <a>Find request </a>
                                     </div>  <!--End of class header for frineds requesed-->
            
                                     <div class="friends_confirm">
                                     	<div class="friends_confirm_image"style ='float: left;margin-left: 7px'>
                                     		<img src="http://localhost/websites/facebook/images/abanoub.jpg"
                                     		style='height: 50px; width:50px'>
                                     	</div>
                                        <div class="confirm_head">
                                        	<h3><a href='#'>abanoub talaat</a></h3>
                                        </div>
                                        <div class="button">
                                        	<button>confirm</button>
                                        	<button>remove</button>
                                        </div>
                                     </div><!--friends confirm -->
            
                                     <div class="friends_confirm">
                                     	<div class="friends_confirm_image"style ='float: left;margin-left: 7px'>
                                     		<img src="http://localhost/websites/facebook/images/abanoub.jpg"
                                     		style='height: 50px; width:50px'>
                                     	</div>
                                        <div class="confirm_head">
                                        	<h3>gaid talaat</h3>
                                        </div>
                                        <div class="button">
                                        	<button>confirm</button>
                                        	<button>remove</button>
                                        </div>
                                     </div><!--friends confirm -->
                                 
                                 <div class="friends_confirm">
                                     	<div class="friends_confirm_image"style ='float: left;margin-left: 7px'>
                                     		<img src="http://localhost/websites/facebook/images/abanoub.jpg"
                                     		style='height: 50px; width:50px'>
                                     	</div>
                                        <div class="confirm_head">
                                        	<h3>mena saad</h3>
                                        </div>
                                        <div class="button">
                                        	<button>confirm</button>
                                        	<button>remove</button>
                                        </div>
                                     </div><!--friends confirm -->
                                 
            
                                 <div class="friends_confirm">
                                     	<div class="friends_confirm_image"style ='float: left;margin-left: 7px'>
                                     		<img src="http://localhost/websites/facebook/images/abanoub.jpg"
                                     		style='height: 50px; width:50px'>
                                     	</div>
                                        <div class="confirm_head">
                                        	<h3>marco shehata</h3>
                                        </div>
                                        <div class="button">
                                        	<button>confirm</button>
                                        	<button>remove</button>
                                        </div>
                                     </div><!--friends confirm -->
                                 
            
                                 <div class="people_maybe_now">
                                 	<h3>peope maybe now</h3>
                                     <?php 
                                     // this get all this person from database 
                                      // $friends = $this->data['name'];
                                       ?>
                                         <div class="container_info" hidden>
                                         	<button class="user_name" value="User Name">	
                                         	</button>
                                         	<button class="email" value="email">
                                         	</button>
                                         </div>
                                       <?php
                                       /*
                                       foreach ($friends as $value) {
                                       	echo "<div class='friends_add'>
                                     	<div class='friends_add_image'style ='float: left;margin-left: 7px'>
                                     		<img src='http://localhost/websites/facebook/images/abanoub.jpg'
                                     		style='height: 50px; width:50px'>
                                     	</div>
                                        <div class='add_head'>
                                        	<h3><a href='#'>{$value['firstname']} {$value['surname']}</a></h3>
                                        </div>
                                        <div class='button'>
                                        	<button value ={$value['id_user']} class='add_friends'>add friends</button>
                                        	<button>remove</button>
                                        </div>
                                     </div>";
                                 }
                                 */
                                     ?>
            
            
                                 	  <!--friends confirm -->
                                 </div>
                         
                        </div><!--End of class Firend requesed-->
                   </li>
                   
                   <li id="messages"><!--The second li -->
                            <a href="#"><img src="http://localhost/websites/facebook/images/messanger.png"> </a>
                            <span class="tooltiptext">Messages</span>
                            
                                <div id="message" class="messages hidden"><!--Start of class messges-->
                    
                                    <div class="header"><!--Start of class header for firends requested-->
                                           <span>Recent </span>
                                           <span> Message Request</span>
                                             <a href="#"> New group </a>
                                            <a href="#"> New Message</a> 
                                      </div>  <!--End of class header for frineds requesed-->
                                  
                                      <div class="message"><!-- Start of class mesage -->
                                        <div class="info-message"><!-- Start of info-message -->
                                            <img src="http://localhost/websites/facebook/images/pop.jpg">
                                            <h3> Abanoub talaat</h3>
                                            <p>«Ì… «·«Œ»«— Ì« —Ì” </p>
                                            
                                        </div><!-- End of info info-message -->
                                        <div class="date"><!-- Start of date class-->
                                             <span id="first" title="Today" class="first">abanoub</span>
                                             <span  id = "second"class="second" >0</span>              
                                        </div><!-- End of date class-->
                                
                                      </div><!-- End of class message-->
                                            
                                   <div class="conthead"><!-- Start of class conthead-->
                                   
                                        <div class="head"><!--Start of class head-->
                                           <h4>People may you know</h4>
                                        </div><!-- End of class head-->
                                        
                                        <div class="link"><!-- Start of class link -->
                                           <a href="#"> View All</a>
                                        </div><!-- End of class link-->
                                   </div>  <!-- End of class conthead-->
                       
                               </div><!--End of class messages-->
                   
                   </li><!-- End of second li-->
                   
                   <li><!-- Start of third li-->
                    <a href="#"><img src="http://localhost/websites/facebook/images/earth.png"> </a>
                    <span class="tooltiptext">Notifiaction</span>
                   </li><!-- End of third li-->
                   
                   <li><!--Start of fourth-->
                    <a href="#"> <img src="http://localhost/websites/facebook/images/help.png"> </a>
                    <span class="tooltiptext">Quick Help</span>
                   </li><!-- End of fourth -->
                   
                    <li><!-- start of fivith li-->
                        <a href="#"> <img src="http://localhost/websites/facebook/images/60995.png"> </a>
                    </li><!-- End of fivith li-->
                </ul>
            
            </div><!-- End of contact class-->
            
            
            <div class="chat_rooms">
            <div  class="messanger" id="chat_room1"> <!-- Start of messanger class-->
                <div id="messanger-head" class="messanger-head"><!-- Start of messanger-head-->
                    <h4> <a id="title1" href=""></a></h4>
                    <ul>
                        <li> <i class="fa fa-plus fa-x"></i> </li>
                        <li>  <i  class="fa fa-video-camera" aria-hidden="true"></i>  </li>
                        <li>  <i  class="fa fa-phone" aria-hidden="true"></i>  </li>
                        <li>  <i  class="fa fa-cog" aria-hidden="true"></i>  </li>
                       <li>  <i  id="close" class="fa fa-times" aria-hidden="true" onclick="close1('1')"></i> </li>
                    </ul>
                    
                </div><!-- End of messanger-head-->
            <div class="messanger-content"><!-- Start messanger-content--->
                    <div class="content" id="data1"><!-- Starat of content-->
                        <!-- <img src="http://localhost/facebook/images/pop.jpg"> -->
                       
                      </div><!-- End of content class-->
            </div><!-- End of messanger-content-->
            <div class="footer"><!-- Start of footer class-->
                <form>
                    <input id ="msg1" type="text" placeholder="Type a message">
                    <input type="button" id="sendMessage" value="Send" onclick="send('1')">
                </form>
                    
              </div><!-- End of footer class-->
              
            </div><!-- End of messanger class-->
            
            <div  class="messanger" id="chat_room2"> <!-- Start of messanger class-->
                <div id="messanger-head" class="messanger-head"><!-- Start of messanger-head-->
                    <h4> <a id="title2" href=""></a></h4>
                    <ul>
                        <li> <i class="fa fa-plus fa-x"></i> </li>
                        <li>  <i  class="fa fa-video-camera" aria-hidden="true"></i>  </li>
                        <li>  <i  class="fa fa-phone" aria-hidden="true"></i>  </li>
                        <li>  <i  class="fa fa-cog" aria-hidden="true"></i>  </li>
                        <li>  <i  class="fa fa-times" aria-hidden="true" onclick="close('2')"></i> </li>
                    </ul>
                </div><!-- End of messanger-head-->
            <div class="messanger-content"><!-- Start messanger-content--->
                    <div class="content" id="data2"><!-- Starat of content-->
                        
                      </div><!-- End of content class-->
            </div><!-- End of messanger-content-->
            <div class="footer"><!-- Start of footer class-->
                <form>
                    <input id ="msg2" type="text" placeholder="Type a message">
                    <input type="button" id="sendMessage" value="Send" onclick = "send('2')">
                </form>
                    
             
              </div><!-- End of footer class-->
              
            </div><!-- End of messanger class-->
            
            
            <div  class="messanger" id="chat_room3"> <!-- Start of messanger class-->
                <div id="messanger-head" class="messanger-head"><!-- Start of messanger-head-->
                    <h4> <a id="title3" href=""></a></h4>
                    <ul>
                        <li> <i class="fa fa-plus fa-x"></i> </li>
                        <li>  <i  class="fa fa-video-camera" aria-hidden="true"></i>  </li>
                        <li>  <i  class="fa fa-phone" aria-hidden="true"></i>  </li>
                        <li>  <i  class="fa fa-cog" aria-hidden="true"></i>  </li>
                        <li>  <i  class="fa fa-times" aria-hidden="true" onclick="close('3')"></i> </li>
                    </ul>
                </div><!-- End of messanger-head-->
            <div class="messanger-content"><!-- Start messanger-content--->
                    <div class="content" id="data3"><!-- Starat of content-->
                       
                      </div><!-- End of content class-->
            </div><!-- End of messanger-content-->
            <div class="footer"><!-- Start of footer class-->
                <form>
                    <input id ="msg3" type="text" placeholder="Type a message">
                    <input type="button" id="sendMessage" value="Send" value="send('3')">
                </form>
                    
             
              </div><!-- End of footer class-->
              
            </div><!-- End of messanger class-->
            
            </div><!-- End container class-->
            </div> <!-- The End of class row -->
            </div><!-- end of class chat rooms -->
            <div id = "friends"><?php echo $friends;?></div>
            <!-- <script type="text/javascript" src="js/profile.js"></script> -->
            <script type="text/javascript" src="js/messanger.js"></script>
	</body>
</html>
