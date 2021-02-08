var host="http://"+location.hostname+"/fyp/";
$(document).ready(function(){

	//get friends
	$.get(host+"api/messenger/friends/", function(data) {
		var response = JSON.parse(JSON.stringify(data));
		for (var i = 0; i<response.friends.length; i++) {

			temp='<!--one chat head-->'
                +'<div class="chat-head" id="'+response.friends[i].user_id+'" style="display: flex;">'
                  +'<div class="chat-head-img">'
                    +'<img class="chat-avatar" src="'+response.friends[i].dp_link+'">'
                  +'</div>'
                  +'<div class="chat-head-name">'
                   +'<span class="f_name">'+response.friends[i].Name+'</span>'
                  +'</div>'
                +'</div>'
                +'<!--one chat head-->';
            $(".chat-friends").append(temp);
		}
	});
	//get friends




	//clicked on one chat head
	$('body').delegate('.chat-head','click',function() {
		var f_id=$(this).attr("id");
		var f_name=$(this).find(".f_name").text();
		var f_avatar=$(this).find(".chat-avatar").attr("src");	
		$(".main-chat").find(".chat-avatar").attr("src",f_avatar);
		$(".main-chat").find(".f_name").html(f_name);
		$(".main-chat").attr("id",f_id);
		$(".main-chat").show();
		get_msg(f_id);
		clear_unreads(f_id);
	});
	//clicked on one chat head


	//clear unread msgs
	function clear_unreads(id){
		$(".chat-friends").find("#"+id).find(".unreads").replaceWith("");
	}
	//clear unread msgs

	//show msg time
	$('body').delegate('.sent, .received','click',function() {
		$(".msg-time").css("display","none");
		if ($(this).find(".msg-time").css("display")=="block") {
			$(this).find(".msg-time").css("display","none");
		}	
		else{
			$(this).find(".msg-time").css("display","block");
		}
	});
	//show msg time


	//get msgs with one friend
	function get_msg(id){
		$(".messeges").html('<div class="chat-loading"><div class="spinner-border text-success" role="status">'
  								+'<span class="sr-only">Loading...</span>'
							+'</div></div>');
		$.get(host+"api/messenger/get-msgs/?id="+id, function(data) {
			var response = JSON.parse(JSON.stringify(data));
			if (response.status=="200 OK") {
				$(".messeges").html("");
			}
			if (response.msgs!=="null") {
				for (var i = 0; i <response.msgs.length; i++) {
					if (response.msgs[i].type=="sent") {
						temp=' <!---sent msg-->'
	                    +'<span class="sent">'
	                      +response.msgs[i].text
	                      +'<span class="msg-time">'
	                        +response.msgs[i].time
	                      +'</span>'
	                    +'</span>'
	                    +'<!---sent msg-->';
					}
					else{
						temp=' <!---received msg-->'
	                    +'<span class="received">'
	                      +response.msgs[i].text
	                      +'<span class="msg-time">'
	                        +response.msgs[i].time
	                      +'</span>'
	                    +'</span>'
	                    +'<!---received msg-->';
					}
					$(".messeges").append(temp);
				}
			}
		});

		$(".messeges").animate({ scrollTop: $(".messeges")[0].scrollHeight*9999999999}, 1000);
	}
	//get msgs with one friend

	//send msg
	$('body').delegate('.send-msg','click',function() {
		var id=$(".main-chat").attr("id");
		if ($(".msg-text").val().length !== 0) {
			ldata={'text': $(".msg-text").val(),'f_id':id};
			var sdata =JSON.stringify(ldata);
			$.post(host+"/api/messenger/send/",
				{
					data:btoa(sdata)
				},
				function(data, status){
					var response = JSON.parse(JSON.stringify(data)); 
					if(response.iscreated=="true"){
						var temp='<!---sent msg-->'
                    			+'<span class="sent">'
                      				+$(".msg-text").val()
                      				+'<span class="msg-time">'
                        				+response.date
                      				+'</span>'
                    			+'</span>'
                    			+'<!---sent msg-->';
						$(".messeges").append(temp);
						$(".msg-text").val("");
						$(".messeges").animate({ scrollTop: $(".messeges")[0].scrollHeight*9999999999}, 1000);
					}
					else{
						alert("message not sent. (Something went wrong)");
					}
				}
			);
		}
	});
	//send msg


	//check for messeges
	window.setInterval(function(){
  		$.get(host+"api/messenger/unread/", function(data) {
			var response = JSON.parse(JSON.stringify(data));
			if (response.msgs!=="null") {
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
		});
	}, 2000);
	//check for messeges

	//get active chat unreads
	function active_unreads(id){
		$.get(host+"api/messenger/unread/?id="+id, function(data) {
			var response = JSON.parse(JSON.stringify(data));
			if (response.msgs!=="null") {
				for (var i = 0; i <response.msgs.length; i++) {
					temp=' <!---received msg-->'
	                    +'<span class="received">'
	                      +response.msgs[i].text
	                      +'<span class="msg-time">'
	                        +response.msgs[i].time
	                      +'</span>'
	                    +'</span>'
	                    +'<!---received msg-->';
	                    $(".messeges").append(temp);
				}
				$(".messeges").animate({ scrollTop: $(".messeges")[0].scrollHeight*9999999999}, 1000);
			}
		});
	}
	//get active chat unreads


	//get online friends
	//check for messeges
	window.setInterval(function(){
		$.get(host+"api/messenger/active/", function(data) {
			var response = JSON.parse(JSON.stringify(data));
			if (response.friends!=="null") {
				for (var i = 0; i <response.friends.length; i++) {
					var data=response.friends[i];
					for (var key in data) {
						var obj="#"+key;
						if (data[key]=="online") {
							if (!$(".chat-friends").find(obj).find(".online").length) {
								$(".chat-friends").find(obj).find(".chat-head-name").append('<span class="online">online</span>');
							}
							if ($(".main-chat").attr("id")==key) {
								if (!$(".main-chat").find(".online").length) {
									$(".main-chat").find(".head-name").append('<span class="online">online</span>');
								}
							}
						}
						else{
							$(".chat-friends").find(obj).find(".online").replaceWith("");
							if ($(".main-chat").attr("id")==key) {
								$(".main-chat").find(".head-name").find(".online").replaceWith("");
							}
						}
					}
				}
			}
		});
	},2000);
	//get online friends

});