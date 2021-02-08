$(document).ready(function(){
	//var search = window.location.hash.substr(1);
	var host="http://"+location.hostname+"/fyp/";
	var $_GET = {};
	if(document.location.toString().indexOf('?') !== -1) {
	    var query = document.location
	                   .toString()
	                   // get the query string
	                   .replace(/^.*?\?/, '')
	                   // and remove any existing hash string (thanks, @vrijdenker)
	                   .replace(/#.*$/, '')
	                   .split('&');

	    for(var i=0, l=query.length; i<l; i++) {
	       var aux = decodeURIComponent(query[i]).split('=');
	       $_GET[aux[0]] = aux[1];
	    }
	}
	//get the 'index' query parameter
	var search =$_GET["q"];

	$.get(host+"api/search/?q="+search, function(data) {
      var response = JSON.parse(JSON.stringify(data));
      if (response.friends!="null") {
      	for (var i = 0; i < response.friends.length; i++) {
      		var user_uid=response.friends[i].user_id;
  			var avatar_url=response.friends[i].dp_link;
  			var name=response.friends[i].Name;
  			var uname=response.friends[i].username;

			var temp='<div id="'+user_uid+'" class="friends-widget-content-area one_friend">'
				      	+'<div style="max-height: 40px;float:left;">'
				      	+'	<img class="widget-content-avatar" src="'+avatar_url+'">'
				      	+'</div>'
				      	+'<div class="widget-text-area">'
				      	+'	<a href="'+host+'profile/'+uname+'">'
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
				      +'</div>';

			$('.search_peoples').append(temp);
      	}
      }
      if (response.posts!="null") {
      	for (var i = 0; i < response.posts.length; i++) {
      		add_new_post(response.posts[i].post_id,response.posts[i].post_text,response.posts[i].post_media,response.posts[i].post_time, response.posts[i].post_author.username, response.posts[i].post_author.Name, response.posts[i].post_author.dp_link, response.posts[i].total_likes, response.posts[i].me_liked, response.posts[i].total_comments,false);
      	}
      }
  	});
	

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
      liked='<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-heart hearts post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg">                     <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path></svg>';
    }
    if(total_likes==0){
      total_likes="";
    }
    if (media_ids==null) {
    	var a=0;
    }
    else{
    	var a=media_ids.length;
    }
    if(a==1){
      //only one media is in post
      if(media_ids[0].includes(".png")){
        //media is picture
        media_template='      <div class="post-media" style="position: relative; background-image: url('+media_ids[0]+');">'+
        '             </div>';
      }
      else{
        //media is video
        ids[0]="my-video"+md5(media_ids[0])+d.getTime();
        media_template='<video-js id="'+ids[0]+'" class="video-js post-media-videos" controls preload="auto" data-setup="{}"><source src="'+media_ids[0]+'"/></video-js>';
      }
        media_lenght=1;
    }
    else if(a>1){
      //multiple medias in post
      media_lenght=a;
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
        media_template='      <div class="post-media" style="position: relative; background-image: url('+media_ids[0]+');">'+

        '             </div><div class="is-post-carousel show-carousel">'+
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
    var new_post = '<div class="card one-post-div" id="'+post_id+'post">'+
    '         <div class="post-body">'+
    '           <div class="post-header">'+
    '             <div style="float: left;">'+
    '               <img class="user-avatar-post-header" src="'+avatar+'">'+
    '             </div>'+
    '             <div style="float: left;" class="post-details">'+
    '               <div class="owner-name"><a target="_self" href="'+host+'/profile/'+username+'">'+name+'</a></div>'+
    '               <div class="post-time">'+time+'</div>'+
    '             </div>'+
    '             <div style="float: right;">'+
    '               <div class="dropdown show dropleft">'+
    '                 <svg role="button" id="post-options" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots post-viewer-options" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
    '                   <path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>'+
    '                 </svg>'+
    '                 '+
    '                 <div class="dropdown-menu post-option-dropdown" aria-labelledby="post-options">'+
    '                 </div>'+
    '               </div>'+
    '             </div>'+
    '           </div>'+
    '           <div class="post-text-area">'+
    '             <div class="post-text-div">'+
    '               <span class="post-text-content">'+post_text+'</span>'+
    '             </div>'+
    '           </div>'+
    '           <div class="post-media-area">'+media_template+
    '           </div>'+
    '           <div class="post-footer">'+
    '             <hr class="upper-line">'+
    '             <div class="post-stats">'+
    '               <div class="like">'+
    '                 <div class="isliked">'+liked+'</div>'+
    '                 <span class="heart-counts">'+total_likes+'</span>'+
    '               </div>'+
    '               <div class="comment right">'+
    '                 <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-chat-left-text post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
    '                   <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v11.586l2-2A2 2 0 0 1 4.414 11H14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>'+
    '                   <path fill-rule="evenodd" d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>'+
    '                 </svg>'+
    '                 <div class="comment-counts">'+total_comments+'</div>'+
    '               </div>'+
    '             </div>'+
    '           </div>'+
    '           <div>'+
    '             <hr class="bottom-line">'+
    '           </div>'+
    '           <div class="post-comments">         '+
    '           </div>'+
    '         </div>'+
    '       </div>';
    $(".progress-bar").css("width","100%");
    $(".main_post_area").find(".container1").remove();
    $('.submit-post').removeClass("disabled");
    if (created==true) {
      $(".search_posts").prepend(new_post);
    }
    else{
      $(".search_posts").append(new_post);  
    }
    get_videojs(ids);
    check_videojs();
    $(".search_posts").find(".post_loading").remove();
  }

function get_videojs(ids){
    for (var i = 0; i < ids.length; i++) {
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


  function sanitization(str){
    
    return str.replace(/\&/g, '&amp;')
        .replace(/\</g, '&lt;')
        .replace(/\>/g, '&gt;')
        .replace(/\"/g, '&quot;')
        .replace(/\'/g, '&#x27')
        .replace(/\//g, '&#x2F');
  }

});