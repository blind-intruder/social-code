var host="http://"+location.hostname+"/fyp/";
var medias=[];
var post_options_viewer=["Report","Save","Don't show me this again"];
var post_options_owner=["Edit","Delete","Report","Save","Don't show me this again"];
var username=""; //username of current user
var uname=""; //full name of current user
var udp=""; //dp link of current user
var cuid=""; //id of current user
var uemail=""; //email of current user
var udob=[]; //dob of current user
var ucover=""; //cover of current user
var bio=""; //bio of current user

function get_modal(el){
	$("#post-media-thumb").modal('show');
	var d = new Date();
	if($(el.parentNode).hasClass("post-media-videos")){ 
		//main media is video
		var main_media="video";
		var main_img=$(el.parentNode).find(".vjs-tech").attr("src");
		var id="my-video"+md5(main_img)+d.getTime();
		$("#post-media-thumb").find(".media-thumb").html('<video-js id="'+id+'" class="video-js modal-post-media-videos" controls preload="auto" data-setup="{}"><source src="'+main_img+'"/></video-js>');
		videojs(document.querySelector('#'+id));
		$("#post-media-thumb").find(".media-thumb").find(".vjs-picture-in-picture-control").remove();
	}
	else{
		//main media is picture
		var main_media="picture";
		var main_img=$(el).css("background-image");
		$("#post-media-thumb").find(".media-thumb").css("background-image",main_img);
	}
	var carousels=$(el).parents(".post-media-area").find(".next-img");
	var post_text=$((el)).parents(".post-body").find(".post-text-content").attr("data-fulltext");
	var u_avatar=$((el)).parents(".one-post-div").find(".user-avatar-post-header").attr("src");
	var u_name=$((el)).parents(".one-post-div").find(".owner-name").find("a").text();
	var u_link=$((el)).parents(".one-post-div").find(".owner-name").find("a").attr("href");
	var post_id=$((el)).parents(".one-post-div").attr("id");
	var like_counts=$((el)).parents(".one-post-div").find(".heart-counts").text();
	var heart=$((el)).parents(".one-post-div").find(".isliked").html();
	var comment_counts=$((el)).parents(".one-post-div").find(".comment-counts").text();
	var share_counts=$((el)).parents(".one-post-div").find(".share-counts").text();
	$(".modal-post-body").find(".comment-counts").text(comment_counts);
	$(".modal-post-body").find(".heart-counts").text(like_counts);
	$(".modal-post-body").find(".share-counts").text(share_counts); 
	$(".modal-post-body").find(".isliked").html(heart);
	//post has carousel
	if(carousels.length){
		$(".modal-post-media-area").append("<div class='hover-carousel'><div class='modal-media-next'></div></div>");
		$("#post-media-thumb").find(".media-thumb").css("height","75%");
		carousels.each(function(){
			//carousel is a video
			if($(this).find(".media-carousel-videos").length){
				var id="my-video"+md5($(this).find(".vjs-tech").attr("src"))+d.getTime();;
				$(".modal-media-next").append("<div class='next-img modal-next-img'><video id='"+id+"' class='media-carousel-video modal-media-carousel-video video-js' controls preload='auto' data-setup='{}'><source src='"+$(this).find(".vjs-tech").attr("src")+"'/></video></div>");
				videojs(document.querySelector('#'+id));
			}
			else{//carousel is a picture
				$(".modal-media-next").append("<div class='next-img modal-next-img'><img src='"+$(this).find(".img-in-que").attr('src')+"' class='modal-img-in-que'></div>");
			}
		});
	}
	//post don't has carousel
	else{
		$("#post-media-thumb").find(".media-thumb").css("height","100%");
	}

	$(".modal-post-body").find(".modal-user-avatar").attr("src",u_avatar);
	$(".modal-post-body").find(".owner-name").find("a").attr("href",u_link);
	$(".modal-post-body").find(".owner-name").find("a").text(u_name);
	$(".modal-post-body").find(".post-text-content").html(decodeURI(atob(post_text)));
	$(".modal-post-body").attr("id",post_id);
	$(".modal-title").text(u_name+"'s post");
	get_comments(post_id.replace("post",""));
}

function get_simple_modal(el){
	$("#simple-post-media-thumb").modal('show');
	var post_text=$((el)).parents(".post-body").find(".post-text-content").attr("data-fulltext");
	var u_avatar=$((el)).parents(".one-post-div").find(".user-avatar-post-header").attr("src");
	var u_name=$((el)).parents(".one-post-div").find(".owner-name").find("a").text();
	var u_link=$((el)).parents(".one-post-div").find(".owner-name").find("a").attr("href");
	var post_id=$((el)).parents(".one-post-div").attr("id");
	var like_counts=$((el)).parents(".one-post-div").find(".heart-counts").text();
	var heart=$((el)).parents(".one-post-div").find(".isliked").html();
	var comment_counts=$((el)).parents(".one-post-div").find(".comment-counts").text();
	var share_counts=$((el)).parents(".one-post-div").find(".share-counts").text();
	$(".modal-post-body").find(".comment-counts").text(comment_counts);
	$(".modal-post-body").find(".heart-counts").text(like_counts);
	$(".modal-post-body").find(".share-counts").text(share_counts); 
	$(".modal-post-body").find(".isliked").html(heart);
	$(".modal-post-body").find(".modal-user-avatar").attr("src",u_avatar);
	$(".modal-post-body").find(".owner-name").find("a").attr("href",u_link);
	$(".modal-post-body").find(".owner-name").find("a").text(u_name);
	$(".modal-post-body").find(".post-text-content").html(decodeURI(atob(post_text)));
	$(".modal-post-body").attr("id",post_id);
	$(".simple-modal-title").text(u_name+"'s post");
	get_comments(post_id.replace("post",""));
}

//function to get the comments of post
	function get_comments(post_id){
		ldata={'post_id': post_id};
		var sdata =JSON.stringify(ldata);
		$.post(host+"/api/posts/get-comments/",
				{
					data:btoa(sdata)
				},
				function(data, status){
					var response = JSON.parse(JSON.stringify(data)); 
					if(response.fetched=="ok"){
						append_comments(response)
					}
					else{
						return 0;
					}
				}
			);
	}
	//function to get the comments of post

	//append post comments & replies
	function append_comments(comments){
		var post_cmnt_area=$(".modal-post-show").find(".post-comments");
		for(var i=0;i<comments.comments.length;i++){
			var cmnt_liked='<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart hearts post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path></svg>';
			var cmnt_likes=comments.comments[i].total_likes;
			if(cmnt_likes=="0"){cmnt_likes="";}
			if(comments.comments[i].me_liked==true){
				cmnt_liked='<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart-fill post-acts" fill="red" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"></path></svg>';
			}
			var new_cmnt = '<div class=\'one-comment\' id=\''+comments.comments[i].main_comment_id+'comment\'>'+
						'    <div class=\'comment-header\'>'+
						'        <img src=\''+comments.comments[i].comment_author.dplink+'\' class=\'comment-avatar\'>'+
						'        <div class=\'comment-writer\'>'+
						'            <span class=\'comment-author\'><a target="_self" href="'+host+'/profile/'+comments.comments[i].comment_author.username+'">'+comments.comments[i].comment_author.name+'</a></span>'+
						'            <span class=\'comment-time timeupdate\'>'+comments.comments[i].comment_time+'</span>'+
						'        </div>'+
						''+
						'        <div class=\'comment-conf\'>'+
						'            <div class=\'btn-group dropleft\'>'+
						'                <svg role=\'button\' id=\'comment-configure\' data-toggle=\'dropdown\' aria-haspopup=\'true\' aria-expanded=\'false\' width=\'1em\' height=\'1em\' viewBox=\'0 0 16 16\' class=\'bi bi-three-dots comment-viewer-options\' fill=\'currentColor\' xmlns=\'http://www.w3.org/2000/svg\'>'+
						'                    <path fill-rule=\'evenodd\' d=\'M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z\'/>'+
						'                </svg>'+
						'        '+
						'                <div class=\'dropdown-menu\' aria-labelledby=\'comment-configure\'>'+
						'                    <span class=\'dropdown-item\' href=\'#\'>Edit</span>'+
						'                    <span class=\'dropdown-item\' href=\'#\'>Delete</span>'+
						'                </div>'+
						'            </div>'+
						'        </div>'+
						'    </div>'+
						'    <div class=\'comment-body\'>'+
						'        <span class=\'comment-text\'>'+comments.comments[i].comment_text+'</span>'+
						'    </div>'+
						'    <div class=\'comment-footer\'>'+
						'        <div class="comment-footer-contents"><div class=\'comment-like\'>'+cmnt_liked+
						'        </div><span class="comment-heart-counts">'+cmnt_likes+'</span></div>'+
						'        <div class=\'comment-reply\'>'+
						'             <span class=\'reply-button\'>Reply</span>'+
						'        </div>'+
						'    </div><div class="comment-replies"><div class="reply-div"></div></div>';
						post_cmnt_area.append(new_cmnt);
						crop_larger_txt_frm_coments(comments.comments[i].main_comment_id+"comment");
			for(var k=0;k<comments.comments[i].comment_replies.length;k++){
				var reply_area=$(".modal-post-show").find("#"+comments.comments[i].main_comment_id+"comment").find(".reply-div");
				var cmnt_liked='<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart hearts post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path></svg>';
				var total_likes_reply=comments.comments[i].comment_replies[k].total_likes;
				if(total_likes_reply=="0"){
					total_likes_reply="";
				}
				if(comments.comments[i].comment_replies[k].me_liked==true){
					cmnt_liked='<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart-fill post-acts" fill="red" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"></path></svg>';
				}
				var new_cmnt = '                  <div class="one-comment-reply-div" id=\''+comments.comments[i].comment_replies[k].reply_id+'reply\'>'+
						'										<div class="comment-header">'+
						'											<img src="'+comments.comments[i].comment_replies[k].reply_author.dplink+'" class="comment-avatar">'+
						'											<div class="comment-writer">'+
						'												<span class="comment-author"><a target="_self" href="'+host+'/profile/'+comments.comments[i].comment_replies[k].reply_author.username+'">'+comments.comments[i].comment_replies[k].reply_author.name+'</a></span>'+
						'												<span class="comment-time timeupdate">'+comments.comments[i].comment_replies[k].time+'</span>'+
						'											</div>'+
						'		'+
						'											<div class="comment-conf">'+
						'												<div class="btn-group dropleft">'+
						'													<svg role="button" id="comment-configure" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots comment-viewer-options" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
						'														<path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>'+
						'													</svg>'+
						'											'+
						'													<div class="dropdown-menu" aria-labelledby="comment-configure">'+
						'														<span class="dropdown-item" href="#">Edit</span>'+
						'														<span class="dropdown-item" href="#">Delete</span>'+
						'													</div>'+
						'												</div>'+
						'											</div>'+
						'										</div>'+
						'										<div class="comment-body">'+
						'											<span class="comment-text">'+comments.comments[i].comment_replies[k].reply_text+
						'										</div>'+
						'										<div class="comment-footer">'+
						'											<div class="comment-footer-contents"><div class=\'comment-like\'>'+cmnt_liked+
						'        </div><span class="comment-heart-counts">'+total_likes_reply+'</span></div>'+
						'											<div class="comment-reply">'+
						'												<span class="reply-button">Reply</span>'+
						'											</div>'+
						'										</div>'+
						'									</div>';
						reply_area.append(new_cmnt);
						crop_larger_txt_frm_coments(comments.comments[i].comment_replies[k].reply_id+"reply");
			}
			$(".modal-post-show").find(".one-comment-reply-div").each(function() {
				$(this).css("display","none");
			});
			if(comments.comments[i].comment_replies.length){
				$(".modal-post-show").find("#"+comments.comments[i].main_comment_id+"comment").find(".reply-div").prepend('<div class="view-replies">View Replies</div>');
			}
		}
	}
	//append post comments & replies

	//function to crop the long text of comments
	function crop_larger_txt_frm_coments(comment_id){
		var full_text=$('#'+comment_id).find('.comment-text').text();
		var el=$('#'+comment_id).find('.comment-text');
		if(el.text().length>200){
			el.text(el.text().substring(0, 200));
			el.append("<span class='read-more-comment'>... read more</span>");
			el.attr("data-fulltext",btoa(full_text));
		}
	}
	//function to crop the long text of comments


$(document).ready(function(){
	  var has_post_media=false;
	  var post_media_count=0;
	  var scoll_flag=false;
	  var status_type_selected="";

	  $(window).scroll(function() {
		   if (((window.innerHeight + window.scrollY) > document.body.offsetHeight) && scoll_flag==false) {
		   		scoll_flag=true;
		       get_new_posts();
		   }
		});
	  
	$("#post-text-area").emojioneArea({
		autoHideFilters: true,
		pickerPosition: "bottom",
	});


	var lastScrollTop = 0;
	$(window).scroll(function(event){
	var st = $(this).scrollTop();
	if (st > lastScrollTop){
		//console.log($(".sticky").css("height"));
	} else {
		//$(".sticky").css("top",st+100);
	}
	lastScrollTop = st;
	});

	//get detaisl of current logged in user
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
		$(".user-avatar-create-post-area").attr("src",udp);
	});
	//get detaisl of current logged in user

	//get post for newsfeed
	$.get(host+"/api/posts/friends/?action=get_posts", function(data) {
		var response = JSON.parse(JSON.stringify(data));
		append_new_posts(response);
	});

	function get_new_posts(){
		if ($(".main_post_area").find(".post_loading").length) {

		}
		else{
			var template='<div class="post_loading" style="text-align: center;"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div>';
			$(".main_post_area").append(template);
		}
		$.get(host+"/api/posts/friends/").done(function(data) { 
			var response = JSON.parse(JSON.stringify(data));
			append_new_posts(response);
		}).fail(function( jqXHR, textStatus, errorThrown ) {
			scoll_flag=false;
		});
	}
	//get post for newsfeed

	//function to process json data for posts
	function append_new_posts(data){
		$(".k2-loader").fadeOut("slow");
		if(data.posts=="null"){
			//there are no posts for this user
			$(".post_loading").replaceWith("");
			if ($(".main_post_area").find(".no-posts").length) {

			}else{
				var template="<div class='no-posts'><span class='no-posts-text'>No more posts to show</br></span></div>";
				$(".main_post_area").append(template);
				scoll_flag=true;
			}
		}
		else{
			for(var i=0;i<data.posts.length;i++){
				add_new_post(data.posts[i].post_id,data.posts[i].post_text,data.posts[i].post_media,data.posts[i].post_time,data.posts[i].post_author.username,data.posts[i].post_author.Name,data.posts[i].post_author.dp_link,data.posts[i].total_likes,data.posts[i].me_liked,data.posts[i].total_comments,false);
			}	
		}
	}
	//function to process json data for posts

	//get hashtags
	function getHashTags(inputText) {  
    	var regex = /(?:^|\s)(?:#)([a-zA-Z\_\d]+)/gm;
    	var matches = [];
    	var match;

    	while ((match = regex.exec(inputText))) {

        	matches.push(match[1]);
    	}

    	return matches;
	}
	//get hashtags

	//open single post in modal
	$('body').delegate('.post-media','click',function() {
		get_modal(this);
	});

	$('body').delegate('.post-text-area','click',function() {
		if ($(this).parents(".modal-body").length) {
			//if this is modal just do nothing
		}
		else{
			if($(this).parents(".post-body").find(".post-media-area").find(".post-media-videos").length){
			//post has media
				$(this).parent(".post-body").find(".post-media-area").find(".post-media-videos").find(".vjs-picture-in-picture-control").click();
			}
			else if ($(this).parents(".post-body").find(".post-media-area").find(".post-media").length) {
				//post has media
				get_modal($(this).parents(".post-body").find(".post-media-area").find(".post-media"));
			}
			else{
				//post has no media
				get_simple_modal(this);
			}
		}
	});
	//open single post in modal

	//view replies of a comment
	$('body').delegate('.view-replies','click',function() {
		var replies=$(this).parents(".one-comment").find(".reply-div").find(".one-comment-reply-div");
		var count=1;
		var el=$(this);
		var last_shown="";
		for(var k=0;k<replies.length;k++){
			if($(replies[k]).css("display")=="none"){
				count++;
				$(replies[k]).css("display","grid");
				last_shown=$(replies[k]);
			}
			else{
				continue;
			}
			if(count==6){
				break;
			}
		}
		replies.each(function(){
			if($(this).css("display")=="none"){
				$(el).insertAfter(last_shown);
			}
			else{
				$(el).remove();
			}
		});
	});
	//view replies of a comment

	//reset modal
	$("#post-media-thumb").on("hidden.bs.modal", function () {
		$(".modal-media-next").empty().remove();
		$(".media-thumb").css("height","100%");
		$(".modal-post-media-area").find(".hover-carousel").remove();
		$(".modal-post-show").find(".one-post-div").find(".isliked").empty();
		$(".modal-post-show").find(".post-comments").empty();
		$(".modal-post-show").find(".media-thumb").empty();	 
		$(".modal-post-show").find(".modal-media-next").empty();
		$(".modal-post-show").find(".media-thumb").css("background-image",'none');
	});
	$("#simple-post-media-thumb").on("hidden.bs.modal", function () {
		$(".modal-media-next").empty().remove();
		$(".media-thumb").css("height","100%");
		$(".modal-post-media-area").find(".hover-carousel").remove();
		$(".modal-post-show").find(".one-post-div").find(".isliked").empty();
		$(".modal-post-show").find(".post-comments").empty();
		$(".modal-post-show").find(".media-thumb").empty();	 
		$(".modal-post-show").find(".media-thumb").css("background-image",'none');
	});
	//reset modal

	//change post main image in post modal
	$('body').delegate('.modal-img-in-que','click',function() {
		if ($(this).parents(".modal-post-media-area").find(".media-thumb").find(".modal-post-media-videos").length) {
			//previous media is video
			var main_img=$(this).attr('src');
			var previous_img=$(this.parentNode).parents(".modal-post-media-area").find(".modal-post-media-videos").find(".vjs-tech").attr("src");
			$(this.parentNode).parents(".modal-post-media-area").find(".media-thumb").replaceWith('<div class="media-thumb" style="height:75%; background-image: url('+main_img+');"></div>');
			var id='my-video'+md5(previous_img)+"posting";
			$(this).replaceWith("<video-js id='"+id+"' class='media-carousel-video modal-media-carousel-video video-js' controls preload='auto' data-setup='{}'><source src='"+previous_img+"'/></video-js>");
			videojs(document.querySelector('#'+id));

		}
		else{
			//previouse image is picture
			var main_img=$(this).attr('src');
			var previous_img=$(this.parentNode).parents(".modal-post-media-area").find(".media-thumb").css("background-image");
			$(this.parentNode).parents(".modal-post-media-area").find(".media-thumb").css("background-image","url("+main_img+")");
			previous_img=previous_img.replace('url("','').replace('"','').replace(')','');
			$(this).attr('src',previous_img);
		}
	});
	

	$("body").delegate('.modal-media-carousel-video','click',function() {
		var d = new Date();
		if ($(this.parentNode).parents(".modal-post-media-area").find(".media-thumb").find(".modal-post-media-videos").length) {
			//previous media is video
			var main_video=$(this).find(".vjs-tech").attr("src");
			var previous_video=$(this.parentNode).parents(".modal-post-media-area").find(".media-thumb").find(".modal-post-media-videos").find(".vjs-tech").attr("src");
			var main_new_id='my-video'+md5(main_video)+d.getTime();
			var previous_new_id='my-video'+md5(previous_video)+d.getTime();
			$(this.parentNode).parents(".hover-carousel").parents(".modal-post-media-area").find(".media-thumb").empty();
			$(this.parentNode).parents(".hover-carousel").parents(".modal-post-media-area").find(".media-thumb").html('<video-js id="'+main_new_id+'" class="video-js modal-post-media-videos" controls preload="auto" data-setup="{}"><source src="'+main_video+'"/></video-js>');
			$(this).parents(".modal-next-img").empty().html("<video id='"+previous_new_id+"' class='media-carousel-video modal-media-carousel-video video-js' controls preload='auto' data-setup='{}'><source src='"+previous_video+"'/></video>"); 
			//$(this).parents(".modal-next-img").html("<video id='"+previous_new_id+"' class='media-carousel-video modal-media-carousel-video video-js' controls preload='auto' data-setup='{}'><source src='"+previous_video+"'/></video>");
			videojs(document.querySelector('#'+main_new_id));
			videojs(document.querySelector('#'+previous_new_id));
		}
		else{
			//previous media is image
			var this_video=$(this).find(".vjs-tech").attr("src");
			var previous_image=$(this.parentNode).parents(".modal-post-media-area").find(".media-thumb").css("background-image");
			previous_image=previous_image.replace('url("','').replace('"','').replace(')','');
			var new_id='my-video'+md5(this_video)+d.getTime();
			$(this.parentNode).parents(".hover-carousel").parents(".modal-post-media-area").find(".media-thumb").html('<video-js id="'+new_id+'" class="video-js modal-post-media-videos" controls preload="auto" data-setup="{}"><source src="'+this_video+'"/></video-js>');
			$(this).parents(".modal-next-img").empty().html('<img src="'+previous_image+'" class="modal-img-in-que">');
			//$(this.parentNode).parents(".modal-post-media-area").find(".media-thumb").html('<video-js id="'+new_id+'" class="video-js modal-post-media-videos" controls preload="auto" data-setup="{}"><source src="'+this_video+'"/></video-js>');
			videojs(document.querySelector('#'+new_id));
		}
	});

	//change post main image in post modal

	//add reply comment box to comment
	$('body').delegate('.reply-button','click',function() {
		//TODO: add the avatar of the current logged in user
		if($(this.parentNode).parents(".one-comment").find(".add-comment").length){
			//no need to add, its already there!
			$(this).animate({ scrollTop: $(document).height()-$(window).height() });
		}
		else{
			var add_comment_box_reply = '<div class=\'add-comment\' style=\'background-color:transparent;\'>'+
			'    <div class=\'add-comment-avatart-user\'>'+
			'        <img src=\''+udp+'\' class=\'comment-avatar\'>'+
			'    </div>'+
			'    <div class=\'comment-type\'>'+
			'        <textarea class=\'comment-content\' placeholder=\'Type your comment here.\'></textarea>'+
			'    </div>'+
			'    <div class=\'add-comment-btn\'>'+
			'        <button class=\'add-comment-btn-text\'>'+
			'            <svg width=\'2em\' height=\'2em\' viewBox=\'0 0 16 16\' class=\'bi bi-cursor-fill add-cmnt-btn\' fill=\'currentColor\' xmlns=\'http://www.w3.org/2000/svg\'>'+
			'                <path fill-rule=\'evenodd\' d=\'M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z\'/>'+
			'            </svg>'+
			'        </button>'+
			'    </div>'+
			'</div>';
			$(this.parentNode).parents(".one-comment").find(".comment-replies").append(add_comment_box_reply);
			$(this.parentNode).parents(".one-comment").find(".comment-replies").find(".comment-content").attr("placeholder","Type your comment here.");
		}
	});
	//add reply comment box to comment


	//add comments to a post
	$('body').delegate('.add-cmnt-btn','click',function() {
		if($(this.parentNode).parents(".one-comment").length){
			//reply to a comment
			var comment_id=$(this.parentNode).parents(".one-comment");
			var comment_text=$(this.parentNode).parents(".add-comment").find(".comment-content").val();
			if(comment_text.trim().length){
				//comment has content, send it to backend :)
				add_new_reply_comment(this,comment_id,comment_text);
			}
		}
		else{
			//new comment
			var post_id=$(this.parentNode).parents(".one-post-div").attr("id");
			var comment_text=$(this.parentNode).parents(".add-comment").find(".comment-content").val();
			if(comment_text.trim().length){
				//comment has content, send it to backend :)
				add_new_comment(this,post_id,comment_text);
			}
		}
	});
	//add comments to a post 

	//function to add reply to a comment
	function add_new_reply_comment(el,comment_id,comment_text){
		//TODO: add the avatar of the current logged in user
		var cmnt_reply_area=$(el.parentNode).parents(".one-comment").find(".reply-div");
		var cmnt_id=$(el.parentNode).parents(".one-comment").attr("id").replace("comment","");
		var timee="";
		var reply_id="";
		comment_text=sanitization(comment_text);
		ldata={'comment_id': cmnt_id,'text':comment_text};
		var sdata =JSON.stringify(ldata);
		$.post(host+"/api/actions/comment/reply/",
				{
					data:btoa(sdata)
				},
				function(data, status){
					var response = JSON.parse(JSON.stringify(data)); 
					if (response.iscreated=="true"){
						reply_id=response.reply_id+"reply";
						timee=response.time;
						var new_cmnt = '                  <div class="one-comment-reply-div" id=\''+reply_id+'\'>'+
						'										<div class="comment-header">'+
						'											<img src="'+udp+'" class="comment-avatar">'+
						'											<div class="comment-writer">'+
						'												<span class="comment-author"><a target="_self" href="'+host+'/profile/'+username+'">'+uname+'</a></span>'+
						'												<span class="comment-time timeupdate">'+timee+'</span>'+
						'											</div>'+
						'		'+
						'											<div class="comment-conf">'+
						'												<div class="btn-group dropleft">'+
						'													<svg role="button" id="comment-configure" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots comment-viewer-options" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
						'														<path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>'+
						'													</svg>'+
						'											'+
						'													<div class="dropdown-menu" aria-labelledby="comment-configure">'+
						'														<span class="dropdown-item" href="#">Edit</span>'+
						'														<span class="dropdown-item" href="#">Delete</span>'+
						'													</div>'+
						'												</div>'+
						'											</div>'+
						'										</div>'+
						'										<div class="comment-body">'+
						'											<span class="comment-text">'+comment_text+''+
						'										</div>'+
						'										<div class="comment-footer">'+
						'											<div class="comment-footer-contents"><div class=\'comment-like\'>'+
						'            <svg width=\'1em\' height=\'1em\' viewBox=\'0 0 16 16\' class=\'bi bi-heart hearts post-acts\' fill=\'currentColor\' xmlns=\'http://www.w3.org/2000/svg\'>'+
						'                <path fill-rule=\'evenodd\' d=\'M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z\'/>'+
						'            </svg>'+
						'        </div><span class="comment-heart-counts"></span></div>'+
						'											<div class="comment-reply">'+
						'												<span class="reply-button">Reply</span>'+
						'											</div>'+
						'										</div>'+
						'									</div>';
						cmnt_reply_area.append(new_cmnt);
						crop_larger_txt_frm_coments(reply_id);
					}
				}
			);

		$(el.parentNode).parents(".add-comment").find(".comment-content").val('');
	}
	//function to add reply to a comment

	//process comment like comment-like
	$('body').delegate('.comment-like','click',function() {
		var el=$(this);
		var like_counts=el.parents(".comment-footer-contents").find(".comment-heart-counts").text();
		if(like_counts==""){like_counts=0;}
		if($(this.parentNode).parents(".one-comment-reply-div").length){
			// it is the reply comment
			var reply_id=$(this.parentNode).parents(".one-comment-reply-div").attr("id").replace("reply","");
			ldata={'reply_id':reply_id};
			var sdata =JSON.stringify(ldata);
			$.post(host+"/api/actions/comment/reply/like/",
					{
						data:btoa(sdata)
					},
					function(data, status){
						var response = JSON.parse(JSON.stringify(data)); 
						if(response.iscreated=="true"){
							//comment liked
							el.html('<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart-fill post-acts" fill="red" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"></path></svg>');
							el.parents(".comment-footer-contents").find(".comment-heart-counts").text(parseInt(like_counts)+1);
						}
						if(response.isdeleted=="true"){
							//comment was already liked, so it has been disliked
							el.html('<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart hearts post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path></svg>');
							var now_likes=parseInt(like_counts)-1;
							if(now_likes==0){now_likes="";}
							el.parents(".comment-footer-contents").find(".comment-heart-counts").text(now_likes);
						}
					}
				);
		}
		else{
			// it is the main comment
			var comment_id=$(this.parentNode).parents(".one-comment").attr("id").replace("comment","");
			ldata={'comment_id':comment_id};
			var sdata =JSON.stringify(ldata);
			$.post(host+"/api/actions/comment/like/",
				{
					data:btoa(sdata)
				},
				function(data, status){
					var response = JSON.parse(JSON.stringify(data)); 
					if(response.iscreated=="true"){
						//comment liked
						el.html('<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart-fill post-acts" fill="red" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"></path></svg>');
						el.parents(".comment-footer-contents").find(".comment-heart-counts").text(parseInt(like_counts)+1);
					}
					if(response.isdeleted=="true"){
						//comment was already liked, so it has been disliked
						el.html('<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart hearts post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path></svg>');
						var now_likes=parseInt(like_counts)-1;
						if(now_likes==0){now_likes="";}
						el.parents(".comment-footer-contents").find(".comment-heart-counts").text(now_likes);
					}
				}
			);
		}
	});
	//process comment like

	//function to add new comment in post
	function add_new_comment(el,post_id,comment_text){
		//TODO: add the avatar of the current logged in user
		var post_cmnt_area=$(el.parentNode).parents(".one-post-div").find(".post-comments");
		comment_text=sanitization(comment_text);
		ldata={'post_id': post_id.replace("post",""),'text':comment_text};
		var sdata =JSON.stringify(ldata);
		var timee="";
		var cmnt_id="";
		$.post(host+"/api/actions/comment/",
				{
					data:btoa(sdata)
				},
				function(data, status){
					var response = JSON.parse(JSON.stringify(data)); 
					if (response.iscreated=="true"){
						timee=response.time;
						cmnt_id=response.comment_id+"comment";
						var new_cmnt = '<div class=\'one-comment\' id=\''+cmnt_id+'\'>'+
						'    <div class=\'comment-header\'>'+
						'        <img src=\''+udp+'\' class=\'comment-avatar\'>'+
						'        <div class=\'comment-writer\'>'+
						'            <span class=\'comment-author\'><a target="_self" href="'+host+'/profile/'+username+'">'+uname+'</a></span>'+
						'            <span class=\'comment-time timeupdate\'>'+timee+'</span>'+
						'        </div>'+
						''+
						'        <div class=\'comment-conf\'>'+
						'            <div class=\'btn-group dropleft\'>'+
						'                <svg role=\'button\' id=\'comment-configure\' data-toggle=\'dropdown\' aria-haspopup=\'true\' aria-expanded=\'false\' width=\'1em\' height=\'1em\' viewBox=\'0 0 16 16\' class=\'bi bi-three-dots comment-viewer-options\' fill=\'currentColor\' xmlns=\'http://www.w3.org/2000/svg\'>'+
						'                    <path fill-rule=\'evenodd\' d=\'M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z\'/>'+
						'                </svg>'+
						'        '+
						'                <div class=\'dropdown-menu\' aria-labelledby=\'comment-configure\'>'+
						'                    <span class=\'dropdown-item\' href=\'#\'>Edit</span>'+
						'                    <span class=\'dropdown-item\' href=\'#\'>Delete</span>'+
						'                </div>'+
						'            </div>'+
						'        </div>'+
						'    </div>'+
						'    <div class=\'comment-body\'>'+
						'        <span class=\'comment-text\'>'+comment_text+'</span>'+
						'    </div>'+
						'    <div class=\'comment-footer\'>'+
						'        <div class="comment-footer-contents"><div class=\'comment-like\'>'+
						'            <svg width=\'1em\' height=\'1em\' viewBox=\'0 0 16 16\' class=\'bi bi-heart hearts post-acts\' fill=\'currentColor\' xmlns=\'http://www.w3.org/2000/svg\'>'+
						'                <path fill-rule=\'evenodd\' d=\'M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z\'/>'+
						'            </svg>'+
						'        </div><span class="comment-heart-counts"></span></div>'+
						'        <div class=\'comment-reply\'>'+
						'             <span class=\'reply-button\'>Reply</span>'+
						'        </div>'+
						'    </div><div class="comment-replies"><div class="reply-div"></div></div>';
						post_cmnt_area.append(new_cmnt);
						crop_larger_txt_frm_coments(cmnt_id);
					}
				}
			);
		$(el.parentNode).parents(".add-comment").find(".comment-content").val('');
	}
	//function to add new comment in post






	//select status type
	$(".status_type").on('click',function(){
		var text=$(this).text();
		if (text.indexOf("Text") > -1) {
			status_type_selected="text";
			//text status selected
			$(this).parents(".status_type_select_div").hide(); 
			$(this).parents(".modal-body").find(".create_status_text").show();
			
		}
		else{
			//media status selected
			status_type_selected="media";	
		}
	});
	$(".add_status").on('click',function(){
		status_type_selected="text";
		if (status_type_selected=="text") {
			//status to be added is text
			var text=$(".status_text_box").val();
			var bg=$(".status_text_box").css("background-color");
			var font=$(".status_text_box").css('font-family');
			ldata={'type': status_type_selected, 'text':text,'media':"none",'bg_color':bg,'font':font};
			var sdata =JSON.stringify(ldata);
			$.post(host+"api/status/add/",
				{
					data:btoa(sdata)
				},
				function(data, status){	
					var response = JSON.parse(JSON.stringify(data)); 
					if (response.isuplaoded=="true"){
						status_type_selected="";
						$(".status_text_box").val("Enter text here");
						$('#status_create_modal').modal('toggle');
					}
					else{
						alert("Unable to upload status");	
					}
				});
		}
		else if(status_type_selected=="media"){
			//status to be added is media
			status_type_selected="";
		}
	});
	//select status type

	//function to crop the long text of comments
	function crop_larger_txt_frm_coments(comment_id){
		var full_text=$('#'+comment_id).find('.comment-text').text();
		var el=$('#'+comment_id).find('.comment-text');
		if(el.text().length>200){
			el.text(el.text().substring(0, 200));
			el.append("<span class='read-more-comment'>... read more</span>");
			el.attr("data-fulltext",btoa(full_text));
		}
	}
	//function to crop the long text of comments

	//function to crop large text of post
	function crop_larger_txt_frm_post(post_id){
		var full_text=$('#'+post_id).find('.post-text-content').text();
		var regex = /(?:^|\s)(?:#)([a-zA-Z\_\d]+)/gm;
    	var match;

    	while ((match = regex.exec(full_text))) {
    		full_text=full_text.replace("#"+match[1],"<p class='tags'>#"+match[1]+"</p>");
    	}
		var el=$('#'+post_id).find('.post-text-content');
		if(el.text().length>500){
			el.text(el.text().substring(0, 500));
			el.append("<span class='read-more-post'>... read more</span>");
			el.attr("data-fulltext",btoa(encodeURI(full_text)));
		}
		else{
			el.attr("data-fulltext",btoa(encodeURI(full_text)));
		}
	}
	//function to crop large text of post

	//initialize post options//
	$('body').delegate('.dropleft','click',function() {
		//TODO: check user type and send it to function as argument
		post_options(this,post_options_owner);
	});
	//initialize post options//

	//get full text when read more clicked
	$('body').delegate('.read-more-comment','click',function() {
		var full_text=$(this.parentNode).attr("data-fulltext");
		$(this.parentNode).text(atob(full_text));
		$(this).remove();
	});
	//get full text when read more clicked

	//get full text when read more clicked
	$('body').delegate('.read-more-post','click',function() {
		var full_text=$(this.parentNode).attr("data-fulltext");
		$(this.parentNode).text(decodeURI(atob(full_text)));
		$(this).remove();
	});
	//get full text when read more clicked


	$('body').delegate('.dropleft','focusin',function() {
		console.log("focusin");
	});

	$('body').delegate('.dropleft','focusout',function() {
		console.log("focusout");
	})

	function post_options(el,type){
		var clas="";	
		for(var i=0; i<type.length;i++){
			if(type[i]=="Don't show me this again"){
				clas="not show"
			}
			else{
				clas=type[i].toLowerCase();
			}
			$(el).find(".post-option-dropdown").append("<span class='dropdown-item post-option-"+clas+"' href='#'>"+type[i]+"</span>");
		}
	}


	//basic front-end sanitization to prevent xss
	function sanitization(str){


		return str.replace(/\&/g, '&amp;')
			.replace(/\</g, '&lt;')
			.replace(/\>/g, '&gt;')
			.replace(/\"/g, '&quot;')
			.replace(/\'/g, '&#x27')
			.replace(/\//g, '&#x2F');
	}
	  var carousel=false;
	  $('body').delegate('.left-carousel','click',function() {
		carousel=true;
	  });

	  if($(".comment-text").text().length>200){
		$(".comment-text").text($(".comment-text").text().substring(0, 200));
		$(".comment-text").append("<span class='read-more-comment'>... read more</span>");	
	  }



	  function change_post_main_img(el,img){
		$(el.parentNode).parents(".post-media-area").find(".post-media").css("background-image","url("+img+")");
	  }

	  //change main image post in newsfeed
	  $('body').delegate('.img-in-que','click',function() {
		if($(this).parents(".post-media-area").find(".post-media").length){
			//previouse image is picture
			var main_img=$(this).attr('src');
			var previous_img=$(this.parentNode).parents(".post-media-area").find(".post-media").css("background-image");
			previous_img=previous_img.replace('url("','').replace('")','');
			change_post_main_img(this,main_img);
			$(this).attr('src',previous_img);	
		}
		else{
			//previous media is video
			var main_img=$(this).attr('src');
			var previous_img=$(this.parentNode).parents(".post-media-area").find(".post-media-videos").find(".vjs-tech").attr("src");
			$(this.parentNode).parents(".post-media-area").find(".post-main-media-video").replaceWith('<div class="post-media" style="position: relative; background-image: url('+main_img+');"></div>');
			var id='my-video'+md5(previous_img);
			$(this).replaceWith("<div class='media-carousel-videos'><video-js id='"+id+"' class='media-carousel-video video-js' controls preload='auto' data-setup='{}'><source src='"+previous_img+"'/></video-js></div>");
			videojs(document.querySelector('#'+id));
		}
	  });
	  //change main image post in newsfeed

	  //change main image post in newsfeed
	  $('body').delegate('.media-carousel-videos','click',function() {
		var main_img=$(this).find(".vjs-tech").attr("src");
		if($(this).parents(".post-media-area").find(".post-media").length){
			//previouse image is picture
			var previous_img=$(this.parentNode).parents(".post-media-area").find(".post-media").css("background-image");
			$(this.parentNode).parents(".post-media-area").find(".post-media").replaceWith('<div class="post-main-media-video"><video-js id="my-video'+md5(previous_img)+'" class="video-js post-media-videos" controls preload="auto" data-setup="{}"><source src="'+main_img+'"/></video-js><div>');
			videojs(document.querySelector('#my-video'+md5(previous_img)));
			previous_img=previous_img.replace('url(','').replace('"','').replace(')','');
			$(this).replaceWith('<img src="'+previous_img+'" class="img-in-que">');
		}
		else{
			//previouse image is video
			var previous_img=$(this.parentNode).parents(".post-media-area").find(".post-media-videos").find(".vjs-tech").attr("src");
			$(this).find(".vjs-tech").attr("src",previous_img);
			$(this.parentNode).parents(".post-media-area").find(".post-media-videos").find(".vjs-tech").attr("src",main_img);
		}
	  });
	  //change main image post in newsfeed


	  //delete a post post-option-edit
	  $('body').delegate('.post-option-delete','click',function() {  
	  	var p_id=$(this).parents(".one-post-div").attr("id").replace("post","");
	  	ldata={'post_id': p_id};
		var sdata =JSON.stringify(ldata);
		$.post(host+"/api/delete/post/",
				{
					data:btoa(sdata)
				},
				function(data, status){
					var response = JSON.parse(JSON.stringify(data)); 
					if (response.isdeleted=="true"){
						console.log("working");
						$("#"+p_id+"post").remove();
					}
				});
	  });
	  //delete a post



	//post like click process
	$('body').delegate('.isliked','click',function() {  
		var el=	$(this);
		var p_hearts=$(this.parentNode).find('.heart-counts').text();
		if(p_hearts==""){p_hearts=0;}
		var post_id=$(this.parentNode).parents(".one-post-div").attr("id");
		post_id=post_id.replace("post","");
		ldata={'post_id': post_id};
		var sdata =JSON.stringify(ldata);
		$.post(host+"/api/actions/like/",
				{
					data:btoa(sdata)
				},
				function(data, status){
					var response = JSON.parse(JSON.stringify(data)); 
					if (response.isdeleted=="true"){
						el.html('<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-heart hearts post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path></svg>');
						$("#"+post_id+"post").find(".isliked").html('<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-heart hearts post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path></svg>');
						$(el).parents(".like").find('.heart-counts').text(parseInt(p_hearts)-1);
						$("#"+post_id+"post").find(".heart-counts").text(parseInt(p_hearts)-1);
						if($(el).parents(".like").find('.heart-counts').text()==0){$(el).parents(".like").find('.heart-counts').text("");}
						if($("#"+post_id+"post").find(".heart-counts").text()==0){$("#"+post_id+"post").find(".heart-counts").text("");}
					}
					if(response.iscreated=="true"){
						el.html('<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-heart-fill post-acts" fill="red" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/></svg>');
						$("#"+post_id+"post").find(".isliked").html('<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-heart-fill post-acts" fill="red" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/></svg>');
						$("#"+post_id+"post").find(".heart-counts").text(parseInt(p_hearts)+1);
						$(el).parents(".like").find('.heart-counts').text(parseInt(p_hearts)+1);
					}
				}
			);
	});
	//post like click process

	//show comment add box in post
	$('body').delegate('.comment','click',function() { 
		var cmnt_box=$(this.parentNode).parents(".post-body");
		console.log(0);
		if(cmnt_box.find(".add-comment").length){
			//comment add box already present
		}
		else{
			//comment add box not present, add it.
			var comment_box = '<div class=\'add-comment\'>'+
			'	<div class=\'add-comment-avatart-user\'>'+
			'		<img src=\''+udp+'\' class=\'comment-avatar\'>'+
			'	</div>'+
			'	<div class=\'comment-type\'>'+
			'		<textarea class=\'comment-content\' placeholder=\'Type your comment here.\'></textarea>'+
			'	</div>'+
			'	<div class=\'add-comment-btn\'>'+
			'		<button class=\'add-comment-btn-text\'>'+
			'			<svg width=\'2em\' height=\'2em\' viewBox=\'0 0 16 16\' class=\'bi bi-cursor-fill add-cmnt-btn\' fill=\'currentColor\' xmlns=\'http://www.w3.org/2000/svg\'>'+
			'				<path fill-rule=\'evenodd\' d=\'M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z\'></path>'+
			'			</svg>'+
			'		</button>'+
			'	</div>'+
			'</div>';
			cmnt_box.append(comment_box);
		}
	});
	//show comment add box in post

	//open status create modal
	$(".status-open-button").on('click',function(){ 
		$("#status_create_modal").modal("show");
		$("#status_create_modal").find("#color-picker").css("background-color","#05cd51");
		$("#status_create_modal").find(".status_background").css("background-color","#05cd51");
		$("#status_create_modal").find(".status_text_box").css("background-color","#05cd51");
		$("#status_create_modal").find(".sp-colorize-container").css("width","0em");
		$("#status_create_modal").find(".sp-colorize-container").css("border","none");

	});
	//open status create modal

	//font select
	$(function(){
	  $('#font_select').fontselect().change(function(){

	    // replace + signs with spaces for css
	    var font = $(this).val().replace(/\+/g, ' ');

	    // split font into family and weight
	    font = font.split(':');

	    // set family on paragraphs
	    $('.status_text_box').css('font-family', font[0]);
	  });
	});
	//font select

	  $("create-post").removeClass('emojionearea');
	  $(".first-type").addClass('create-post-types-active');
	 // $(".list-group-item").click(function() {
		//$(this).addClass('create-post-types-active');
	//	$(this).removeClass('create-post-types-nonactive');
		//$(this).siblings().addClass('create-post-types-nonactive');
	//	$(this).siblings().removeClass('create-post-types-active');
	//  });


	//change status background color
	$("#color-picker").on('input paste change',function(){
		$(".status_background").css("background-color",$("#color-picker").val());
		$(".status_text_box").css("background-color",$("#color-picker").val());
		$(this).css("background-color",$("#color-picker").val());
	});
	//change status background color
	
	$(document).click(function() {
		$(".create_post_area").css("height","215px");
		$(".create-post,.emojionearea-editor").css("height","85px");
		$(".emojionearea-editor").css("height","85px");
		$(".options-post,.aaa,.post-multimedia").hide(0);
		carousel=false;
	});

	$(".create_post_area").click(function(event) {
		if(has_post_media==false){
			$(".create_post_area").css("height","290px");
			$(".create-post,.emojionearea-editor").css("height","130px");
			$(".emojionearea-editor").css("height","130px");
			$(".options-post,.aaa,.post-multimedia").show(10);
		}
		if(has_post_media==true){
			$(".create_post_area").css("height","380px");
			$(".create-post,.emojionearea-editor").css("height","130px");
			$(".emojionearea-editor").css("height","130px");
			$(".options-post,.aaa,.post-multimedia").show(10);
		}
		
		event.stopPropagation();
	});

	$(".create-post").on("input paste",function(){
		if($(".create-post").val().length>200){
			$(".create-post,.emojionearea-editor").css("font-size","medium");
			$(".emojionearea-editor").css("font-size","medium");
		}
		else{
			$(".create-post,.emojionearea-editor").css("font-size","larger");
			$(".emojionearea-editor").css("font-size","larger");
		}
	});

	$("body").on('DOMSubtreeModified', ".emojionearea-editor", function() {
		$('.emojionearea-editor').scrollTop($('.emojionearea-editor')[0].scrollHeight);

		if($(".emojionearea-editor").text().length>200){
			$(".create-post,.emojionearea-editor").css("font-size","medium");
			$(".emojionearea-editor").css("font-size","medium");
		}
		else{
			$(".create-post,.emojionearea-editor").css("font-size","larger");
			$(".emojionearea-editor").css("font-size","larger");
		}
	});

	$(".add-media-in-post").click(function(){
		$("#img-post-create").click();
	});

	$("#emojies-in-post").click(function(){
		$(".emojionearea-button").click();
	});

	function check_post(){
		if(post_media_count==0){
			$(".create_post_area").css("height","290px");
			$(".create-post,.emojionearea-editor").css("height","130px");
			$(".emojionearea-editor").css("height","130px");
			$(".options-post,.aaa,.post-multimedia").show(10);
		}
		if(post_media_count>0){
			$(".create_post_area").css("height","380px");
			$(".create-post,.emojionearea-editor").css("height","130px");
			$(".emojionearea-editor").css("height","130px");
			$(".options-post,.aaa,.post-multimedia").show(10);
		}
	}

	$('.post-multimedia').on('click', '.delete-media', function() {
		var med_id=$((this.parentNode).parentNode.parentNode).attr("id");
		$(this).parent().parent().parent().parent().remove();
		ldata={'media_id': med_id};
		var sdata =JSON.stringify(ldata);
		var chk = medias.indexOf(med_id);
		if (chk > -1) {
			medias.splice(chk, 1);
		}
		post_media_count-=1;
		check_post();
		$.post(host+"/api/delete/media/",
				{
					data:btoa(sdata)
				},
				function(data, status){
					var response = JSON.parse(JSON.stringify(data)); 
					if (response.isdeleted=="true") {
						
					}
				}
			);
		if(post_media_count==0){
			has_post_media=false;
		}
	});

	$('body').delegate('.vjs-text-track-display','change',function() {
		alert(1);
	});

	$('body').delegate('#img-post-create','change',function() {
		if(this.files[0].size > 5000000){
       		alert("Video is too big!");
       		this.value = "";
    	}
		if(post_media_count<6 && this.value!=""){
			var file = this.files[0];
		var reader  = new FileReader();
		var img='';
		reader.onloadend = function () {
			$(".create_post_area").css("height","380px");
			has_post_media=true;
			img=reader.result;
			var d=new Date();
			var temp_id=Math.floor((Math.random() * 10) + 1)+d.getTime()+Math.floor((Math.random() * 10) + 1);
			ldata={'media': reader.result};
			var sdata =JSON.stringify(ldata);
			var preview=$(".post-multimedia").append("<div class='create-post-multimedia' id='"+temp_id+"'><div class='inner'><div class='content spinn'><div class='text'><div class='spinner-border text-success' role='status'><span class='sr-only'>Loading...</span></div></div></div></div></div>");       
			$.post(host+"/api/upload/post-media/",
				{
					data:btoa(sdata)
				},
				function(data, status){
					var response = JSON.parse(JSON.stringify(data)); 
					if (response.isuploaded=="true") {
						if(response.type=="picture"){
							$("#"+temp_id).empty().append("<div id='"+response.m_id+"' class='post-media-preview-div'><img class='create-post-multimedia-preview' src='"+img+"'><div class='content'><div class='text'><svg width='1.5em' height='1.5em' class='delete-media' viewBox='0 0 26 26' style='enable-background:new 0 0 26 26;'><path style='fill:#030104;' d='M21.125,0H4.875C2.182,0,0,2.182,0,4.875v16.25C0,23.818,2.182,26,4.875,26h16.25C23.818,26,26,23.818,26,21.125V4.875C26,2.182,23.818,0,21.125,0z M18.78,17.394l-1.388,1.387c-0.254,0.255-0.67,0.255-0.924,0L13,15.313L9.533,18.78c-0.255,0.255-0.67,0.255-0.925-0.002L7.22,17.394c-0.253-0.256-0.253-0.669,0-0.926l3.468-3.467L7.221,9.534c-0.254-0.256-0.254-0.672,0-0.925l1.388-1.388c0.255-0.257,0.671-0.257,0.925,0L13,10.689l3.468-3.468c0.255-0.257,0.671-0.257,0.924,0l1.388,1.386c0.254,0.255,0.254,0.671,0.001,0.927l-3.468,3.467l3.468,3.467C19.033,16.725,19.033,17.138,18.78,17.394z'/></svg></div></div></div>");
							post_media_count+=1;
							medias.push(response.m_id);
						}
						if(response.type=="video"){
							$("#"+temp_id).empty().append("<div id='"+response.m_id+response.m_id+"' style='height: inherit;'><video-js id='my-video"+response.m_id+"' class='video-js post-video-added' controls preload='auto' data-setup='{}'><source src='"+response.link+"'/></video-js><div class='content'><div class='text'><svg width='1.5em' height='1.5em' class='delete-media' viewBox='0 0 26 26' style='enable-background:new 0 0 26 26;'><path style='fill:#030104;' d='M21.125,0H4.875C2.182,0,0,2.182,0,4.875v16.25C0,23.818,2.182,26,4.875,26h16.25C23.818,26,26,23.818,26,21.125V4.875C26,2.182,23.818,0,21.125,0z M18.78,17.394l-1.388,1.387c-0.254,0.255-0.67,0.255-0.924,0L13,15.313L9.533,18.78c-0.255,0.255-0.67,0.255-0.925-0.002L7.22,17.394c-0.253-0.256-0.253-0.669,0-0.926l3.468-3.467L7.221,9.534c-0.254-0.256-0.254-0.672,0-0.925l1.388-1.388c0.255-0.257,0.671-0.257,0.925,0L13,10.689l3.468-3.468c0.255-0.257,0.671-0.257,0.924,0l1.388,1.386c0.254,0.255,0.254,0.671,0.001,0.927l-3.468,3.467l3.468,3.467C19.033,16.725,19.033,17.138,18.78,17.394z'/></svg></div></div></div>");
							videojs(document.querySelector('#my-video'+response.m_id));
							post_media_count+=1;
							medias.push(response.m_id);
						}
						if(medias.length==4){
							$(".post-multimedia").append('<hr>');
						}
					}
				}
			); 
        }
		reader.readAsDataURL(file);
		$('#img-post-create').attr('value', '');
		}
		else{
			alert("Only 6 photos/videos can be added with one post.");
		}
		 
	});

	//reset post create area when post is created
	function reset_post_create_area(){
		$('#img-post-create').attr('value', '');
		$('.post-multimedia').empty();
		post_media_count=0;
		has_post_media=false;
		$(".create-post").val('');
		$(".emojionearea-editor").empty();
		check_post();
		medias=[];
		$(".create_post_area").css("height","215px");
		$(".create-post,.emojionearea-editor").css("height","85px");
		$(".emojionearea-editor").css("height","85px");
		$(".options-post,.aaa,.post-multimedia").hide(10);
	}
	//reset post create area when post is created

	//function to add new post in newsfeed
	function add_new_post(post_id,post_text,media_ids,time,username,name,avatar,total_likes,me_liked,total_comments,created){
		var medias=[];
		var media_template="";
		var media_lenght=0;
		var liked='';
		var ids=[];
		var p_text=post_text;
		var d = new Date();
		if(total_comments==0){total_comments="";}
		if(me_liked){
			liked='<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-heart-fill post-acts" fill="red" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"></path></svg>';
		}
		else{
			liked='<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-heart hearts post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg">											<path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path></svg>';
		}
		if(total_likes==0){
			total_likes="";
		}
		if(media_ids.length==1){
			//only one media is in post
			if(media_ids[0].includes(".png")){
				//media is picture
				media_template='			<div class="post-media" style="position: relative; background-image: url('+media_ids[0]+');">'+
				'							</div>';
			}
			else{
				//media is video
				ids[0]="my-video"+md5(media_ids[0])+d.getTime();
				media_template='<video-js id="'+ids[0]+'" class="video-js post-media-videos" controls preload="auto" data-setup="{}"><source src="'+media_ids[0]+'"/></video-js>';
			}
				media_lenght=1;
		}
		else if(media_ids.length>1){
			//multiple medias in post
			media_lenght=media_ids.length;
			var carousels="";
			for(var i=1;i<media_ids.length;i++){
				if(media_ids[i].includes(".png")){
					//media is picture
					carousels+="<div class='next-img'>"+
								"<img src='"+media_ids[i]+"' class='img-in-que'>"+
							"</div>";
				}
				else{
					//media is video
					var tempid="my-video"+md5(media_ids[i])+d.getTime();
					ids.push(tempid);
					carousels+="<div class='next-img'><div class='media-carousel-videos'>"+
								"<video-js id='"+tempid+"' class='media-carousel-video video-js' controls preload='auto' data-setup='{}'><source src='"+media_ids[i]+"'/></video-js>"+
							"</div></div>";
				}
			}
			if(media_ids[0].includes(".png")){
				media_template='			<div class="post-media" style="position: relative; background-image: url('+media_ids[0]+');">'+

				'							</div><div class="is-post-carousel show-carousel">'+
				'<div class="carousel-next-imgs">'+carousels+'</div>'+
			     '</div>';
			}
			else{
				var tempid="my-video"+md5(media_ids[0])+d.getTime();
				ids.push(tempid);
				media_template='<div class="post-media-video"><video-js id="'+tempid+'" class="video-js post-media-videos" controls preload="auto" data-setup="{}"><source src="'+media_ids[0]+'"/></video-js></div><div class="is-post-carousel show-carousel">'+
				'<div class="carousel-next-imgs">'+carousels+'</div>'+
			     '</div>';
			}
		}
		else{
			//no media in post
			//text only post
			media_template="";
			media_lenght=0;
		}
		var regex = /(?:^|\s)(?:#)([a-zA-Z\_\d]+)/gm;
    	var matches = [];
    	var match;

    	p_text=sanitization(p_text);
		$(".progress-bar").css("width","75%");
		var new_post = '<div class="card one-post-div" id="'+post_id+'post">'+
		'					<div class="post-body">'+
		'						<div class="post-header">'+
		'							<div style="float: left;">'+
		'								<img class="user-avatar-post-header" src="'+avatar+'">'+
		'							</div>'+
		'							<div style="float: left;" class="post-details">'+
		'								<div class="owner-name"><a target="_self" href="'+host+'/profile/'+username+'">'+name+'</a></div>'+
		'								<div class="post-time">'+time+'</div>'+
		'							</div>'+
		'							<div style="float: right;">'+
		'								<div class="dropdown show dropleft">'+
		'									<svg role="button" id="post-options" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots post-viewer-options" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
		'										<path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>'+
		'									</svg>'+
		'								  '+
		'									<div class="dropdown-menu post-option-dropdown" aria-labelledby="post-options">'+
		'									</div>'+
		'								</div>'+
		'							</div>'+
		'						</div>'+
		'						<div class="post-text-area">'+
		'							<div class="post-text-div">'+
		'								<span class="post-text-content">'+post_text+'</span>'+
		'							</div>'+
		'						</div>'+
		'						<div class="post-media-area">'+media_template+
		'						</div>'+
		'						<div class="post-footer">'+
		'							<hr class="upper-line">'+
		'							<div class="post-stats">'+
		'								<div class="like">'+
		'									<div class="isliked">'+liked+'</div>'+
		'									<span class="heart-counts">'+total_likes+'</span>'+
		'								</div>'+
		'								<div class="comment right">'+
		'									<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-chat-left-text post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
		'										<path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v11.586l2-2A2 2 0 0 1 4.414 11H14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>'+
		'										<path fill-rule="evenodd" d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>'+
		'									</svg>'+
		'									<div class="comment-counts">'+total_comments+'</div>'+
		'								</div>'+
		'							</div>'+
		'						</div>'+
		'						<div>'+
		'							<hr class="bottom-line">'+
		'						</div>'+
		'						<div class="post-comments">					'+
		'						</div>'+
		'					</div>'+
		'				</div>';
		$(".progress-bar").css("width","100%");
		$(".main_post_area").find(".container1").remove();
		$('.submit-post').removeClass("disabled");
		if (created==true) {
			$(".main_post_area").prepend(new_post);
		}
		else{
			$(".main_post_area").append(new_post);	
		}
		get_videojs(ids);
		check_videojs();
		crop_larger_txt_frm_post(post_id+"post");
		scoll_flag=false;
		$(".main_post_area").find(".post_loading").remove();
	}

	function get_videojs(ids){
		for (var i = 0; i < ids.length; i++) {
		  console.log(ids[i]);
		  videojs(document.querySelector('#'+ids[i]));
		} 
	}

	function check_videojs(){
		$('post-media-videos').each(function(){
			if($(this).find(".vjs-tech").lenght){
				//just pass it
			}
			else{
				videojs(document.querySelector('#'+$(this).attr("id")));
			}
		});
	}

	//function to add new post in newsfeed

	//process the new post created
	$('.submit-post').bind('click',function() {
		if ($('.submit-post').hasClass("disabled")==false) {
			var simple_text=$(".create-post").val();
			simple_text=simple_text.replace("\n"," ");
			ldata={'text': simple_text,'media_id':medias};
			var sdata =JSON.stringify(ldata);
			$(".main_post_area").prepend('<div class="container1"><div class="card1"><div style="text-align:center;" class="process-post">Processing post</div><div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div></div></div>');
			$(".submit-post").addClass('disabled');
			$(".progress-bar").css("width","25%");
			$.post(host+"/api/upload/post/",
					{
						data:btoa(unescape(encodeURIComponent(sdata)))
					},
					function(data, status){
						var response = JSON.parse(JSON.stringify(data)); 
						if(response.iscreated=="true"){
							$(".progress-bar").css("width","50%");
							add_new_post(response.post_id,simple_text,response.medias,response.time,username,uname,udp,0,false,0,true);
						}
						reset_post_create_area();
					}
				);	
		}
	});
	//process the new post created


	function show_online(id){
		$('#'+id).css('border', '0.2em solid #05cd51');
	}
	function show_offline(id){
		$('#'+id).css('border', 'none');	
	}

	show_online('11');
	show_online('10');
	//Future work: get all friends from API and loop the line below
	//to add them in quick chat list
	//$( "#quick-chats-online" ).append( "<p>Test</p>" );


    //suggested pages
	var dummy_pages={"Pages":[
		{"id":"1","Name":"John the ripper", "category":"Education", "avatar_url":"https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d?ixid=MXwxMjA3fDB8MHxzZWFyY2h8MXx8aHVtYW58ZW58MHx8MHw%3D&ixlib=rb-1.2.1&w=1000&q=80"},
		{"id":"2","Name":"Blind Intruder", "category":"Noob", "avatar_url":"https://www.esa.int/var/esa/storage/images/esa_multimedia/images/2020/07/solar_orbiter_s_first_views_of_the_sun5/22136942-2-eng-GB/Solar_Orbiter_s_first_views_of_the_Sun_pillars.gif"},
		{"id":"3","Name":"Haq Se K2", "category":"Community", "avatar_url":"user-content/display-pic/default.jpg"},
		{"id":"5","Name":"SMIU", "category":"Organization", "avatar_url":"user-content/display-pic/default.jpg"},
		{"id":"4","Name":"Buffer Overflow Buffer Overflow", "category":"Exploitation", "avatar_url":"https://www.esa.int/var/esa/storage/images/esa_multimedia/images/2020/07/solar_orbiter_s_first_views_of_the_sun5/22136942-2-eng-GB/Solar_Orbiter_s_first_views_of_the_Sun_pillars.gif"}
	]};


	for (var i = 0; i < Object.keys(dummy_pages.Pages).length; i++) {
  		var name=dummy_pages.Pages[i].Name;
  		var category=dummy_pages.Pages[i].category;
  		var avatar_url=dummy_pages.Pages[i].avatar_url;
  		var page_uid=dummy_pages.Pages[i].id

  		$('.suggest-pages').append('<div id="'+page_uid+'" class="pages-widget-content-area"><div style="max-height: 40px;float:left;"><img class="widget-content-avatar" src="'+avatar_url+'"></div><div class="widget-text-area"><a href="#"><h5 class="widget-content-title">'+name+'</h5></a><h5 class="widget-content-text">'+category+'</h5></div><div style="height: 40px;overflow: auto;"><span class="page-like-icon" alt="Like"><svg width="1.2em" height="2em" viewBox="0 0 16 16" class="bi bi-star page-like-icons" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/></svg></span></div></div>');
	}

	$('body').delegate('.page-like-icon','click',function() {

		//to delete the suggested page from list use this line
		//$((this.parentNode).parentNode).fadeOut(500, function() { $(this).remove(); });
		var n=Math.floor(Math.random() * 4);
		var name=dummy_pages.Pages[n].Name;
  		var category=dummy_pages.Pages[n].category;
  		var avatar_url=dummy_pages.Pages[n].avatar_url;

  		//to change the id of the parent div to add the id of new suggested page
  		$((this.parentNode).parentNode).attr("id",n);
  		$((this.parentNode).parentNode).fadeOut(500, function() { $(this).empty().append('<div style="max-height: 40px;float:left;"><img class="widget-content-avatar" src="'+avatar_url+'"></div><div class="widget-text-area"><a href="#"><h5 class="widget-content-title">'+name+'</h5></a><h5 class="widget-content-text">'+category+'</h5></div><div style="height: 40px;overflow: auto;"><span class="page-like-icon" alt="Like"><svg width="1.2em" height="2em" viewBox="0 0 16 16" class="bi bi-star page-like-icons" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/></svg></span></div>').fadeIn(500); });
  		//TODO: send a request to server and get another suggested page and show it in this position.
  	});
	//suggested pages


	//add friend add_friend
	$('body').delegate('.add_friend','click',function() {
		var id=$(this).parents(".one_friend").attr("id");
		ldata={'id':id};
      	var sdata =JSON.stringify(ldata);
      	$.post(host+"/api/friends/send-request/",
          {
            data:btoa(sdata)
          },
          function(data, status){
            $("#"+id).find('.add_friend').empty();
          });
	});
	//add friend

	//suggested friends
	$.get(host+"api/friends/suggestions/", function(data) {
      var response = JSON.parse(JSON.stringify(data));
      if (response.friends!="null") {
      	var i=0;
      	for (var i = 0; i < response.friends.length; i++) {
      		var name=response.friends[i].Name;
  			var user_uid=response.friends[i].user_id;
  			var avatar_url=response.friends[i].dp_link;
  			if (i==0) {
  				var t="active";
  			}
  			else{
  				var t="";
  			}
  			var temp='<div class="carousel-item '+t+'">'
				      +'<div id="'+user_uid+'" class="friends-widget-content-area one_friend">'
				      	+'<div style="max-height: 40px;float:left;">'
				      	+'	<img class="widget-content-avatar" src="'+avatar_url+'">'
				      	+'</div>'
				      	+'<div class="widget-text-area">'
				      	+'	<a href="#">'
				      	+'		<h5 class="widget-content-title">'+name+'</h5>'
				      	+'	</a>'
				      	+'	<h5 class="widget-content-text">Suggested</h5>'
				      	+'</div>'
				      	+'<div style="height: 40px;overflow: auto;">'
				      	+'	<span class="add-like-icon add_friend" alt="Like">'
				      	+'		<svg width="1.3em" height="2em" viewBox="0 0 16 16" class="bi bi-person-plus add-like-icons" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'
				      	+'			<path fill-rule="evenodd" d="M8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10zM13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"></path>'
				      	+'		</svg>'
				      	+'	</span>'
				      	+'</div>'
				      +'</div>'
				    +'</div>';
  			$('.suggested_friends').append(temp); 
  			i+=1;     	
  		}
      }
  	});
	//suggested friends

});

//function to make md5 hash
function md5(e) {
	function h(a, b) {
		var c, d, e, f, g;
		e = a & 2147483648;
		f = b & 2147483648;
		c = a & 1073741824;
		d = b & 1073741824;
		g = (a & 1073741823) + (b & 1073741823);
		return c & d ? g ^ 2147483648 ^ e ^ f : c | d ? g & 1073741824 ? g ^ 3221225472 ^ e ^ f : g ^ 1073741824 ^ e ^ f : g ^ e ^ f
	}

	function k(a, b, c, d, e, f, g) {
		a = h(a, h(h(b & c | ~b & d, e), g));
		return h(a << f | a >>> 32 - f, b)
	}

	function l(a, b, c, d, e, f, g) {
		a = h(a, h(h(b & d | c & ~d, e), g));
		return h(a << f | a >>> 32 - f, b)
	}

	function m(a, b, d, c, e, f, g) {
		a = h(a, h(h(b ^ d ^ c, e), g));
		return h(a << f | a >>> 32 - f, b)
	}

	function n(a, b, d, c, e, f, g) {
		a = h(a, h(h(d ^ (b | ~c), e), g));
		return h(a << f | a >>> 32 - f, b)
	}

	function p(a) {
		var b = "",
			d = "",
			c;
		for (c = 0; 3 >= c; c++) d = a >>> 8 * c & 255, d = "0" + d.toString(16), b += d.substr(d.length - 2, 2);
		return b
	}
	var f = [],
		q, r, s, t, a, b, c, d;
	e = function(a) {
		a = a.replace(/\r\n/g, "\n");
		for (var b = "", d = 0; d < a.length; d++) {
			var c = a.charCodeAt(d);
			128 > c ? b += String.fromCharCode(c) : (127 < c && 2048 > c ? b += String.fromCharCode(c >> 6 | 192) : (b += String.fromCharCode(c >> 12 | 224), b += String.fromCharCode(c >> 6 & 63 | 128)), b += String.fromCharCode(c & 63 | 128))
		}
		return b
	}(e);
	f = function(b) {
		var a, c = b.length;
		a = c + 8;
		for (var d = 16 * ((a - a % 64) / 64 + 1), e = Array(d - 1), f = 0, g = 0; g < c;) a = (g - g % 4) / 4, f = g % 4 * 8, e[a] |= b.charCodeAt(g) << f, g++;
		a = (g - g % 4) / 4;
		e[a] |= 128 << g % 4 * 8;
		e[d - 2] = c << 3;
		e[d - 1] = c >>> 29;
		return e
	}(e);
	a = 1732584193;
	b = 4023233417;
	c = 2562383102;
	d = 271733878;
	for (e = 0; e < f.length; e += 16) q = a, r = b, s = c, t = d, a = k(a, b, c, d, f[e + 0], 7, 3614090360), d = k(d, a, b, c, f[e + 1], 12, 3905402710), c = k(c, d, a, b, f[e + 2], 17, 606105819), b = k(b, c, d, a, f[e + 3], 22, 3250441966), a = k(a, b, c, d, f[e + 4], 7, 4118548399), d = k(d, a, b, c, f[e + 5], 12, 1200080426), c = k(c, d, a, b, f[e + 6], 17, 2821735955), b = k(b, c, d, a, f[e + 7], 22, 4249261313), a = k(a, b, c, d, f[e + 8], 7, 1770035416), d = k(d, a, b, c, f[e + 9], 12, 2336552879), c = k(c, d, a, b, f[e + 10], 17, 4294925233), b = k(b, c, d, a, f[e + 11], 22, 2304563134), a = k(a, b, c, d, f[e + 12], 7, 1804603682), d = k(d, a, b, c, f[e + 13], 12, 4254626195), c = k(c, d, a, b, f[e + 14], 17, 2792965006), b = k(b, c, d, a, f[e + 15], 22, 1236535329), a = l(a, b, c, d, f[e + 1], 5, 4129170786), d = l(d, a, b, c, f[e + 6], 9, 3225465664), c = l(c, d, a, b, f[e + 11], 14, 643717713), b = l(b, c, d, a, f[e + 0], 20, 3921069994), a = l(a, b, c, d, f[e + 5], 5, 3593408605), d = l(d, a, b, c, f[e + 10], 9, 38016083), c = l(c, d, a, b, f[e + 15], 14, 3634488961), b = l(b, c, d, a, f[e + 4], 20, 3889429448), a = l(a, b, c, d, f[e + 9], 5, 568446438), d = l(d, a, b, c, f[e + 14], 9, 3275163606), c = l(c, d, a, b, f[e + 3], 14, 4107603335), b = l(b, c, d, a, f[e + 8], 20, 1163531501), a = l(a, b, c, d, f[e + 13], 5, 2850285829), d = l(d, a, b, c, f[e + 2], 9, 4243563512), c = l(c, d, a, b, f[e + 7], 14, 1735328473), b = l(b, c, d, a, f[e + 12], 20, 2368359562), a = m(a, b, c, d, f[e + 5], 4, 4294588738), d = m(d, a, b, c, f[e + 8], 11, 2272392833), c = m(c, d, a, b, f[e + 11], 16, 1839030562), b = m(b, c, d, a, f[e + 14], 23, 4259657740), a = m(a, b, c, d, f[e + 1], 4, 2763975236), d = m(d, a, b, c, f[e + 4], 11, 1272893353), c = m(c, d, a, b, f[e + 7], 16, 4139469664), b = m(b, c, d, a, f[e + 10], 23, 3200236656), a = m(a, b, c, d, f[e + 13], 4, 681279174), d = m(d, a, b, c, f[e + 0], 11, 3936430074), c = m(c, d, a, b, f[e + 3], 16, 3572445317), b = m(b, c, d, a, f[e + 6], 23, 76029189), a = m(a, b, c, d, f[e + 9], 4, 3654602809), d = m(d, a, b, c, f[e + 12], 11, 3873151461), c = m(c, d, a, b, f[e + 15], 16, 530742520), b = m(b, c, d, a, f[e + 2], 23, 3299628645), a = n(a, b, c, d, f[e + 0], 6, 4096336452), d = n(d, a, b, c, f[e + 7], 10, 1126891415), c = n(c, d, a, b, f[e + 14], 15, 2878612391), b = n(b, c, d, a, f[e + 5], 21, 4237533241), a = n(a, b, c, d, f[e + 12], 6, 1700485571), d = n(d, a, b, c, f[e + 3], 10, 2399980690), c = n(c, d, a, b, f[e + 10], 15, 4293915773), b = n(b, c, d, a, f[e + 1], 21, 2240044497), a = n(a, b, c, d, f[e + 8], 6, 1873313359), d = n(d, a, b, c, f[e + 15], 10, 4264355552), c = n(c, d, a, b, f[e + 6], 15, 2734768916), b = n(b, c, d, a, f[e + 13], 21, 1309151649), a = n(a, b, c, d, f[e + 4], 6, 4149444226), d = n(d, a, b, c, f[e + 11], 10, 3174756917), c = n(c, d, a, b, f[e + 2], 15, 718787259), b = n(b, c, d, a, f[e + 9], 21, 3951481745), a = h(a, q), b = h(b, r), c = h(c, s), d = h(d, t);
	return (p(a) + p(b) + p(c) + p(d)).toLowerCase()
};
//function to make md5 hash