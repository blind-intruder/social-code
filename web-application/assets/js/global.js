var host="http://"+location.hostname+"/fyp/";
var username=""; //username of current user
var uname=""; //full name of current user
var udp=""; //dp link of current user
var cuid=""; //id of current user
var uemail=""; //email of current user
var udob=[]; //dob of current user
var ucover=""; //cover of current user
var bio=""; //bio of current user
var status_list={}; 


$(document).ready(function(){
	var host="http://"+location.hostname+"/fyp/";
	$(".global-profile-link").attr("href",host+"profile");
	$(".global-home-link").attr("href",host);

	$(window).scroll(function () {
			if ($(this).scrollTop() > 50) {
				$('#back-to-top').fadeIn();
			} else {
				$('#back-to-top').fadeOut();
			}
		});
		// scroll body to 0px on click
	$('#back-to-top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 400);
		return false;
	});

	//quick logout
	$('body').delegate('.logout-quick, .logout-quick-global','click',function() {
		$.post(host+"/",
				{
					logout:'true'
				},
				function(data, status){
					location.reload();
				}
			);
	});
	//quick logout

	//search
	$(".global_form").submit(function(){
		var t=$(".global_form").find(".global-search").val();
		window.location.replace(host+"search/?q="+t);
	});
	//search

	//get details of current logged in user
	$.get(host+"/api/user/me", function(data) {
		var response = JSON.parse(JSON.stringify(data));
		username=response.username;
		uname=response.firstname+" "+response.lastname;
		udp=response.dp;
		cuid=response.id;
		uemail=response.email;
		udob[0]=response.dob[0];
		udob[1]=response.dob[1];
		udob[2]=response.dob[2];
		ucover=response.cover;
		bio=response.bio;
		$(".user-global-avatar").attr("src",udp);
		$(".home_link").attr("href",host);
		$(".profile_link").attr("href",host+"profile/"+username);
		$(".friends_link").attr("href",host+"profile/"+username+"#friends");
		$(".friend_requests").attr("href",host+"profile/"+username+"#friend_request");
		$(".messenger_link").attr("href",host+"messenger");
	});
	//get details of current logged in user

	//get status
	$.get(host+"/api/status/get", function(data) {
		var response = JSON.parse(JSON.stringify(data));
		for(var i=0;i<response.status.length;i++){
			if (response.status[i].friend.user_status.length) {
				$(".global-chats").prepend('<div class="quick-chat-item">'
	  								+'<img class="user-avatar quick-status" data-status="true" data-name="'+response.status[i].friend.user.Name+'" id="'+response.status[i].friend.user.user_id+'" src="'+response.status[i].friend.user.dp_link+'">'
	  								+'</div>');
				//var new_status=[response.status[i].friend.user.user_id,btoa(JSON.stringify(response.status[i].friend.user_status))]
				status_list[""+response.status[i].friend.user.user_id+""]=btoa(JSON.stringify(response.status[i].friend.user_status));
				show_online(response.status[i].friend.user.user_id);
			}
			else{
				$(".global-chats").append('<div class="quick-chat-item">'
	  								+'<img class="user-avatar quick-status" data-status="false" id="'+response.status[i].friend.user.user_id+'" src="'+response.status[i].friend.user.dp_link+'">'
	  								+'</div>');
			}
		}
	});
	//get status


	//open friends
	$('body').delegate('.friends_link','click',function() {
		if ((location.href).includes(host+"profile/"+username)) {
			show_friends();
		}
	});
	$('body').delegate('.friend_requests','click',function() {
		if ((location.href).includes(host+"profile/"+username)) {
			show_friend_requests();
		}
	});


	function show_friend_requests(){
    if (action=="friend_request") {
    $.get(host+"api/friends/get-request/", function(data) {
      var response = JSON.parse(JSON.stringify(data));
      if (response.friends!="null") {
        $(".data-posts").empty();
        for (var i = 0; i <response.friends.length; i++) {
          var name=response.friends[i].Name;
          var dp=response.friends[i].dp_link;
          var id=response.friends[i].user_id;
          var username=response.friends[i].username;
          var temp='<div class="card one_friend" id='+id+' style="margin:1em;">'
                      +'<div class="card-body" style="margin: 1em!important;">'
                        +'<div class="user_big_avatar">'
                          +'<img src="'+dp+'" class="user_big_image" >'
                        +'</div>'
                        +'<div class="user_name">'
                          +'<p class="user_friend_name"><a href="'+host+"profile/"+username+'">'+name+'</a></p>'
                        +'</div>'
                        +'<div class="unfriend_btn">'
                          +'<button type="button" class="btn btn-outline-success request_action accept_friend"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg></button>'
                          +'<button type="button" class="btn btn-outline-success request_action not_accept_friend"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg></button>'
                        +'</div>'
                      +'</div>'
                    +'</div>';
          $(".data-posts").append(temp);
        }
        $(".data-posts").css("display","flex");
      }
      else{
        alert("No requests.");
      }
    });
  }
  }


	 function show_friends(){
	    $.get(host+"api/friends/my/", function(data) {
	      var response = JSON.parse(JSON.stringify(data));
	      if (response.friends!="null") {
	        $(".data-posts").empty();
	        for (var i = 0; i <response.friends.length; i++) {
	          var name=response.friends[i].Name;
	          var dp=response.friends[i].dp_link;
	          var id=response.friends[i].user_id;
	          var username=response.friends[i].username;
	          var temp='<div class="card one_friend" id='+id+' style="width: 25%;'
	                    +'margin: 1em;">'
	                      +'<div class="card-body" style="margin: 1em!important;">'
	                        +'<div class="user_big_avatar">'
	                          +'<img src="'+dp+'" class="user_big_image" >'
	                        +'</div>'
	                        +'<div class="user_name">'
	                          +'<p class="user_friend_name"><a href="'+host+"profile/"+username+'">'+name+'</a></p>'
	                        +'</div>'
	                        +'<div class="unfriend_btn">'
	                          +'<button type="button" class="btn btn-outline-success un_friend">Un-Friend</button>'
	                        +'</div>'
	                      +'</div>'
	                    +'</div>';
	          $(".data-posts").append(temp);
	        }
	        $(".data-posts").css("display","flex");
	      }
	      else{
	        alert("No friends.");
	      }
	    });
  }
  //open friends


	//open status
	$('body').delegate('.quick-status','click',function() {
		if ($(this).attr("data-status")!="false") {
			$("#status_show_modal").modal("show");
			var json=JSON.parse(atob(status_list[$(this).attr("id")]));
			if (json.length==1) {
				var text=sanitization(json[0].status_text);
				var color=json[0].bg_color;
				var font=json[0].font;
				$("#status_show_modal").find(".status-text").text(text);
				$("#status_show_modal").find(".show_status").css("background-color",color);
				$("#status_show_modal").find(".status-text").css("font-family",font); 
				$("#status_show_modal").find(".status_author_name").text($(this).attr("data-name")+"'s status");
			}
		}
	});
	//open status

	//register callback
	window.setInterval(function(){
		$.get(host+"api/actions/callback/", function(data) {
			
		});
	}, 2000);


	window.setInterval(function(){
			$.get(host+"api/friends/get-request/", function(data) {
		      var response = JSON.parse(JSON.stringify(data));
		      if (response.friends!="null") {	
		      	$( ".quick_requests" ).attr('data-original-title', response.friends.length)
          									.tooltip('show');
          			$( ".friend_requests").find(".pages").css("color","#05cd51");
		      }
		  	});
	}, 4000);

	function show_online(id){
		$('#'+id).css('border', '0.3em solid #05cd51');
	}
	function show_offline(id){
		$('#'+id).css('border', 'none');	
	}


	//check for messeges
	window.setInterval(function(){
  		$.get(host+"api/messenger/unread/", function(data) {
			var response = JSON.parse(JSON.stringify(data));
			if (response.msgs!=="null") {
				var unread_total=0;
				if (location.href==host+"messenger/") {
					for (var i = 0; i <response.msgs.length; i++) {
					//$("#"+response.msgs[i].)
						var data=response.msgs[i];
						for (var key in data) {
							var obj="#"+key;
							if ($(".main-chat").attr("id")==key) {
								active_unreads(key);
							}
							else{
								if ($(".chat-friends").find(obj).find(".unreads").length) {
									$(".chat-friends").find(obj).find(".unreads").replaceWith('<span class="badge badge-pill badge-warning unreads">'+data[key]+'</span>');
								}
								else{
									$(".chat-friends").find(obj).append('<span class="badge badge-pill badge-warning unreads">'+data[key]+'</span>');
								}	
							}
						}
					}
				}
				else{
					$( ".quick_messenger" ).attr('data-original-title', response.msgs.length)
          									.tooltip('show');
          			$( ".messenger_link").find(".pages").css("color","#05cd51");
				}
			}
		});
	}, 2000);
	//check for messeges
	
	//make responsive using jquery
  	var width = $(window).width();
  	var h = $(window).height();
  	h=h-60;
  	$(".quick-chats").css("height",h+'px');

  	if (width > 1300){
  		width=width-150;
    	$(".container").css("max-width",width);
  	}
  	else if(width < 1300){
  		width=width-150;
  		var p=(width/2)-5;
    	$(".container").css("max-width",width);
    	$(".col-sm-6").css("max-width",p+'%');
  	}




  	$(window).resize(function() {
  		var width = $(window).width();
  		var h = $(window).height();
  		h=h-60;
  		$(".quick-chats").css("height",h+'px');
  		if (width < 1300){
  			width=width-150;
  			var p=(width/2)-5;
    		$(".container").css("max-width",width);
    		$(".col-sm-6").css("max-width",p+'%');
  		}
  		if (width > 1300){
  		width=width-150;
    	$(".container").css("max-width",width);
  	}
	});
  	//make responsive using jquery

  	//quick logout
	$('body').delegate('.logout-quick','click',function() {
		$.post(host+"/",
				{
					logout:'true'
				},
				function(data, status){
					location.reload();
				}
			);
	});
	//quick logout

	//basic front-end sanitization to prevent xss
	function sanitization(str){


		return str.replace(/\&/g, '&amp;')
			.replace(/\</g, '&lt;')
			.replace(/\>/g, '&gt;')
			.replace(/\"/g, '&quot;')
			.replace(/\'/g, '&#x27')
			.replace(/\//g, '&#x2F');
	}
});