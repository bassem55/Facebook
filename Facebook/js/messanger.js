var public_room1_status = false,
    public_room1_value = 0,
    public_room1_title = '';

var public_room2_status = false,
 	public_room2_value = 0,
 	public_room2_title = '';

var public_room3_status = false,
	public_room3_value = 0,
	public_room3_title = '';


var room1 = document.getElementById("chat_room1"),
	room2 = document.getElementById("chat_room2"),
	room3 = document.getElementById("chat_room3");

var title1 = document.getElementById("title1"),
	title2 = document.getElementById("title2"),
	title3 = document.getElementById("title3");
function show(name , id)
{
	//first we will check if click many time on the same name and id 
	//so if do this will do nothing
	
	if(id != public_room1_value && id != public_room2_value && id != public_room3_value)
	{
		//it is meaning that first click and all chat rooms closed 
		if(public_room1_status == false && public_room2_status == false && public_room3_status == false)
		{
			title1.innerHTML = name;
			room1.style.display = "block";
			public_room1_status = true;
			public_room1_value = id;
			public_room1_title = name;
		}
		//it is meaning that chat room1 is open and chat room 2 and 3 are closed
		else if(public_room1_status == true && public_room2_status == false && public_room3_status == false)
		{
			title2.innerHTML = name;
			room2.style.display = "block";
			public_room2_status = true;
			public_room2_value = id;
			public_room2_title = name;
		}
		else if(public_room1_status == true && public_room2_status == true && public_room3_status == false)
		{
			title3.innerHTML = name;
			room3.style.display = "block";
			public_room3_status = true;
			public_room3_value = id;
			public_room3_title = name;
		}
	}
}

function close1(num)
{
	
	//num == the number of chat room 
	
	if(num == "1" || num == "2" || num == "3")
	{
		if(num == "1")
		{
			//we want to know if chat room 2 and 3 are opened or closed
			
			//it is meaning that chat room2 and 3 are opened so we will switch rooms values
			//so chat rooms 1 and 2 will opened and close chat room3
			if(public_room2_status == true && public_room3_status == true)
			{
				public_room1_status = true;
				public_room1_value  = public_room2_value;
				public_room1_title = public_room2_title;
				title1.innerHTML = public_room1_title;
				
				public_room2_status = true;
				public_room2_value  = public_room3_value;
				public_room2_title = public_room3_title;
				title2.innerHTML = public_room2_title;
				
				public_room3_status = false;
				public_room3_value  = 0;
				public_room3_title = '';
				title3.innerHTML = "";
				
				room3.style.display = "none";
				
				
			}
			//it is meaning that chat room 2 is opened and chat room 3 is closed
			//so we will close chat room 2 and switch rooms values
			else if(public_room2_status == true && public_room3_status == false)
			{
				public_room1_status = true;
				public_room1_value  = public_room2_value;
				public_room1_title = public_room2_title;
				title1.innerHTML = public_room1_title;
				
				public_room2_status = false;
				public_room2_value  = 0;
				public_room2_title = '';
				title2.innerHTML = "";
				
				room2.style.display = "block";
				
				
			}
			//it is meaning that chat rooms 2 and 3 are closed
			else if(public_room2_status == false && public_room3_status == false)
			{
				public_room1_status = false;
				public_room1_value  = 0;
				public_room1_title = "";
				title1.innerHTML = "";
				
				room1.style.display = "none";
			}
		}
		else if(num == "2")
		{
			if(public_room3_status == true)
			{
				public_room2_status = true;
				public_room2_value = public_room3_value;
				public_room2_title = public_room3_title;
				title2.innerHTML = public_room2_title;
				
				public_room3_status = false;
				public_room3_title = "";
				public_room3_value = 0;
				title3.innerHTML = "";
				
				room3.style.display = "none";
			}
			else if(public_room3_status == false)
			{
				public_room2_status = false;
				public_room2_title = "";
				public_room2_value = 0;
				title2.innerHTML = "";
				
				room2.style.display = "none";
				
			}
		}
		else
		{
			public_room3_status = false;
			public_room3_title = "";
			public_room3_value = 0;
			
			title3.innerHTML = "";
			room3.style.display = "none";
		}
	}
	
}

function smart_chat()
{
	//this function used to put data on right plase like
	//will check if chat room 1 are open so put the data of user 1 on room1
	
	if(public_room1_status == true)
	{
		//room1.style.display = "block";
		//title1.innerHTML = public_room1_title;
		get_msgs(public_room1_value);
	}
	if(public_room2_status == true)
	{
		//room2.style.display = "block";
		//title2.innerHTML = public_room2_title;
		get_msgs(public_room2_value);
	}
	if(public_room3_status == true)
	{
		//room3.style.display = "block";
		//title3.innerHTML = public_room3_title;
		get_msgs(public_room3_value);
	}
}
setInterval(function(){
    if(public_room1_status == true || public_room2_status == true || public_room3_status == true)
        smart_chat();
} , 1000);
