
  function changeText(el)
  {
      $(".request_friend_sent").text("Delete friend request");
  }
              
  function defaultText()
  {
      $(".request_friend_sent").text("Friend request sent");
  }

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
            '                   <div class="comment-header">'+
            '                     <img src="'+comments.comments[i].comment_replies[k].reply_author.dplink+'" class="comment-avatar">'+
            '                     <div class="comment-writer">'+
            '                       <span class="comment-author"><a target="_self" href="'+host+'/profile/'+comments.comments[i].comment_replies[k].reply_author.username+'">'+comments.comments[i].comment_replies[k].reply_author.name+'</a></span>'+
            '                       <span class="comment-time timeupdate">'+comments.comments[i].comment_replies[k].time+'</span>'+
            '                     </div>'+
            '   '+
            '                     <div class="comment-conf">'+
            '                       <div class="btn-group dropleft">'+
            '                         <svg role="button" id="comment-configure" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots comment-viewer-options" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
            '                           <path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>'+
            '                         </svg>'+
            '                     '+
            '                         <div class="dropdown-menu" aria-labelledby="comment-configure">'+
            '                           <span class="dropdown-item" href="#">Edit</span>'+
            '                           <span class="dropdown-item" href="#">Delete</span>'+
            '                         </div>'+
            '                       </div>'+
            '                     </div>'+
            '                   </div>'+
            '                   <div class="comment-body">'+
            '                     <span class="comment-text">'+comments.comments[i].comment_replies[k].reply_text+
            '                   </div>'+
            '                   <div class="comment-footer">'+
            '                     <div class="comment-footer-contents"><div class=\'comment-like\'>'+cmnt_liked+
            '        </div><span class="comment-heart-counts">'+total_likes_reply+'</span></div>'+
            '                     <div class="comment-reply">'+
            '                       <span class="reply-button">Reply</span>'+
            '                     </div>'+
            '                   </div>'+
            '                 </div>';
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
  $(".k2-loader").fadeOut("slow");
  var action = window.location.hash.substr(1);
	 var host="http://"+location.hostname+"/fyp/";
   var scoll_flag=false;
   var uuid=$(".user-profilename").attr("id");

    $(window).scroll(function() {
       if (((window.innerHeight + window.scrollY) > document.body.offsetHeight) && scoll_flag==false) {
          scoll_flag=true;
           get_new_posts();
       }
    });


    //get post for newsfeed
  $.get(host+"/api/posts/get/?action=get_posts&id="+uuid, function(data) {
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
    $.get(host+"/api/posts/get/?id="+uuid).done(function(data) { 
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

  //send request
  $('body').delegate('.request_friend','click',function() {
      var id=$(".user-profilename").attr("id");
      ldata={'id':id};
      var sdata =JSON.stringify(ldata);
      $.post(host+"/api/friends/send-request/",
          {
            data:btoa(sdata)
          },
          function(data, status){
            $('.request_friend').replaceWith('<span class="btn btn-outline-success request_friend_sent" onmouseover="changeText(this)" onmouseout="defaultText(this)">Friend request sent</span>');
          });
  });
  $('body').delegate('.request_friend_sent','click',function() {
      var id=$(".user-profilename").attr("id");
      ldata={'id':id};
      var sdata =JSON.stringify(ldata);
      $.post(host+"/api/friends/send-request/?action=delete",
          {
            data:btoa(sdata)
          },
          function(data, status){
            $('.request_friend_sent').replaceWith('<button type="button" class="btn btn-outline-success request_friend">Add Friend</button>');
          });
  });
  //send request

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
            '                   <div class="comment-header">'+
            '                     <img src="'+udp+'" class="comment-avatar">'+
            '                     <div class="comment-writer">'+
            '                       <span class="comment-author"><a target="_self" href="'+host+'/profile/'+username+'">'+uname+'</a></span>'+
            '                       <span class="comment-time timeupdate">'+timee+'</span>'+
            '                     </div>'+
            '   '+
            '                     <div class="comment-conf">'+
            '                       <div class="btn-group dropleft">'+
            '                         <svg role="button" id="comment-configure" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots comment-viewer-options" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'+
            '                           <path fill-rule="evenodd" d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>'+
            '                         </svg>'+
            '                     '+
            '                         <div class="dropdown-menu" aria-labelledby="comment-configure">'+
            '                           <span class="dropdown-item" href="#">Edit</span>'+
            '                           <span class="dropdown-item" href="#">Delete</span>'+
            '                         </div>'+
            '                       </div>'+
            '                     </div>'+
            '                   </div>'+
            '                   <div class="comment-body">'+
            '                     <span class="comment-text">'+comment_text+''+
            '                   </div>'+
            '                   <div class="comment-footer">'+
            '                     <div class="comment-footer-contents"><div class=\'comment-like\'>'+
            '            <svg width=\'1em\' height=\'1em\' viewBox=\'0 0 16 16\' class=\'bi bi-heart hearts post-acts\' fill=\'currentColor\' xmlns=\'http://www.w3.org/2000/svg\'>'+
            '                <path fill-rule=\'evenodd\' d=\'M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z\'/>'+
            '            </svg>'+
            '        </div><span class="comment-heart-counts"></span></div>'+
            '                     <div class="comment-reply">'+
            '                       <span class="reply-button">Reply</span>'+
            '                     </div>'+
            '                   </div>'+
            '                 </div>';
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
    var el= $(this);
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
      ' <div class=\'add-comment-avatart-user\'>'+
      '   <img src=\''+udp+'\' class=\'comment-avatar\'>'+
      ' </div>'+
      ' <div class=\'comment-type\'>'+
      '   <textarea class=\'comment-content\' placeholder=\'Type your comment here.\'></textarea>'+
      ' </div>'+
      ' <div class=\'add-comment-btn\'>'+
      '   <button class=\'add-comment-btn-text\'>'+
      '     <svg width=\'2em\' height=\'2em\' viewBox=\'0 0 16 16\' class=\'bi bi-cursor-fill add-cmnt-btn\' fill=\'currentColor\' xmlns=\'http://www.w3.org/2000/svg\'>'+
      '       <path fill-rule=\'evenodd\' d=\'M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z\'></path>'+
      '     </svg>'+
      '   </button>'+
      ' </div>'+
      '</div>';
      cmnt_box.append(comment_box);
    }
  });
  //show comment add box in post

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
      liked='<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-heart hearts post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg">                     <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path></svg>';
    }
    if(total_likes==0){
      total_likes="";
    }
    if(media_ids.length==1){
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
    $(".progress-bar").css("width","75%");
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



$('.edit-div-cover').show();	
	$("#dpupload").click(function(){
		$('#dpuloadmodalbody').html("<div class='container'><div class='row'><div class='col-sm-12' style='text-align: center;'><div class='upload-demo-wrap'><div id='upload-demo'></div></div></div></div><!--upload row--><div class='row' style='padding-top: 50px;'><div class='col-sm-12' style='text-align: center;'><div class='actions'><a class='btn file-btn'><label for='uploaddp' class='btn btn-success'>Select Image</label><input type='file' id='uploaddp' value='Choose a file' style='display: none;' accept='image/*' /></a><br /><br /><button class='upload-result btn btn-success' id='dpsave'>Save</button></div></div></div><!--upload row--></div>");
    	$('#dpupload-iframe').modal('toggle');
    	 dpchange.init();
  	});

  	$(".text-cover-cancel").click(function(){
  		$('.text-cover-drag').hide();
  		$('.text-cover-save').hide();
		$('.text-cover-cancel').hide();
		location.reload();
		resetcrop();
		$image_crop.croppie('destroy');
		$image_crop=null;
  	});

  	$('.text-cover-save').click(function () {
			$image_crop.croppie('result', {
				type: 'canvas',
				size: 'original',
				format :'png',
				backgroundColor:'white'
			}).then(function (resp) {
				ldata={'image': resp};
				$('.text-cover-save').hide();
				$('.text-cover-cancel').hide();
				$('.text-cover-drag').hide();
				resetcrop();
				$image_crop.croppie('destroy');
				$image_crop=null;
      					var sdata =JSON.stringify(ldata);
      					$.post(host+"/api/upload/coverpic/",
      						{
        						data:btoa(sdata)
      						},
      						function(data, status){
      							var response = JSON.parse(JSON.stringify(data));
      							if (response.isupdated=="true") {
      								$(".img-fluid").attr("src", response.link);
      								location.reload();
      							}
      						}
      					);
			});
		});

  	$("#coverupload").click(function(){
  		$('.edit-div-cover').hide();
  		$('#cover-upload-iframe').modal('toggle');
		coveredit();
  	});
  function coveredit(){
  	$('.edit-div-cover').show();
	$('#uploadcover').on('change', function () { 
		var reader = new FileReader();
		reader.onload = function (e) {
		r = e.target.result;
        simg = r.split(';');
        type = simg[0];
        if (type == 'data:image/jpeg' || type == 'data:image/png') {
        	$image_crop = $('.img-fluid').croppie({
			viewport: {
				width: 1120,
				height: 300,
				type: 'square'
			},
			boundary: {
				width: 1120,
				height: 300
			},
			enableOrientation: true
		});
        	$image_crop.croppie('bind', {
				url: e.target.result
			}).then(function(){
				$('.text-cover-save').show();
				$('.text-cover-cancel').show();
				$('.text-cover-drag').show();
				$('#cover-upload-iframe').modal('toggle');
			});
        }
        else{
        	alert("Sorry - Only Png & Jpg files are allowed");
        }			
	}
	reader.readAsDataURL(this.files[0]);
	});
  }

  function resetcrop(){
  	$('.img-fluid').removeClass('ready');
    $('#uploadcover').val(''); // this will clear the input value of croppie.
    $image_crop.croppie('bind', {
        url : ''
    }).then(function () {
    });
  }
  var fnameflag=1;
  var lnameflag=1;
  var bioflag=1;
  var dateflag=1;
  var monthflag=1;
  var yearflag=1;
  function disable_submit(con){
    $('#settings-save-personal').prop('disabled', con);
  }

//personal setting save
  $("#settings-fname").on('input paste',function(){
  	var firstname = $("#settings-fname").val();
    var fnameregex=/^[A-Z ]+$/i;
    $('#settings-fname').popover({ trigger:"hover click focus", placement:"top" ,title: 'Invalid First Name', content: "Only Alphabets are allowed(a-z A-Z)" });
  	if (!firstname.match(fnameregex) && firstname!="") {
  		$('#settings-fname').popover('toggle');
      	$('#settings-fname').css('box-shadow', '0 0 0 0.2rem red');
      	disable_submit(true);
      	fnameflag=0;
  	}
  	else{
  		fnameflag=1;
  		$('#settings-fname').popover('dispose');
      	$('#settings-fname').css('box-shadow', '0 0 0 0.1rem green');
  	}
  });

  $("#settings-lname").on('input paste',function(){
  	var lastname = $("#settings-lname").val();
    var lnameregex=/^[A-Z ]+$/i;
    $('#settings-lname').popover({ trigger:"hover click focus", placement:"top" ,title: 'Invalid Last Name', content: "Only Alphabets are allowed(a-z A-Z)" });
  	if (!lastname.match(lnameregex) && lastname!="") {
  		$('#settings-lname').popover('toggle');
      	$('#settings-lname').css('box-shadow', '0 0 0 0.2rem red');
      	disable_submit(true);
      	lnameflag=0;
  	}
  	else{
  		lnameflag=1;
  		$('#settings-lname').popover('dispose');
      	$('#settings-lname').css('box-shadow', '0 0 0 0.1rem green');
  	}
  });

  $("#settings-bio").on('input paste',function(){
  	var bio = $("#settings-bio").val();
  	var n = bio.length;
  	var max=100;
    $('#bio-remaining').html("Characters remaining:"+parseInt(max - n));
  	if (n>100 && bio!="") {
      	$('#settings-bio').css('box-shadow', '0 0 0 0.2rem red');
      	disable_submit(true);
      	bioflag=0;
  	}
  	else{
  		bioflag=1;
      	$('#settings-bio').css('box-shadow', '0 0 0 0.1rem green');
  	}
  });

  $("#settings-date").change(function(){
  	var date = $("#settings-date").val();
    $('#settings-date').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Date is invalid" });
    var regex=/^[0-9]+$/i;
   if (!date.match(regex)) {
      $('#settings-date').popover('toggle');
      $('#settings-date').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      dateflag=0;
    }
    else if(parseInt(date)<1 || parseInt(date)>31){
      $('#settings-date').popover('toggle');
      $('#settings-date').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      dateflag=0;
    }
    else{
      $('#settings-date').popover('dispose');
      $('#settings-date').css('box-shadow', '0 0 0 0.1rem green');
      dateflag=1;
    }
  });

  $("#settings-year").change(function(){
    var year = $("#settings-year").val();
    $('#settings-year').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Year is invalid" });
    var regex=/^[0-9]+$/i;
    if (!year.match(regex)) {
      $('#settings-year').popover('toggle');
      $('#settings-year').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      yearflag=0;
    }
    else if(parseInt(year)<1905 || parseInt(year)>2010){
      $('#settings-year').popover('toggle');
      $('#settings-year').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      yearflag=0;
    }
    else{
      $('#settings-year').popover('dispose');
      $('#settings-year').css('box-shadow', '0 0 0 0.1rem green');
      yearflag=1;
    }
  });

  $("#settings-month").change(function(){
    var month = $("#settings-month").val();
    $('#settings-month').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Month is invalid" });
    var regex=/^[a-z]+$/i;
    if (!month.match(regex)) {
      $('#settings-month').popover('toggle');
      $('#settings-month').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      monthflag=0;
    }
    else{
      $('#settings-month').popover('dispose');
      $('#settings-month').css('box-shadow', '0 0 0 0.1rem green');
      monthflag=1;
    }
  });
  function submit_check(){
      if (fnameflag==1 && lnameflag==1 && bioflag==1 && dateflag==1 && monthflag==1 && yearflag==1) {
        disable_submit(false);
      }
    }
    setInterval(submit_check, 1000);


  $("#settings-save-personal").click(function(){
  	var month=$("#settings-month").val();
  	var year=$("#settings-year").val();
  	var date=$("#settings-date").val();
  	var bio=$("#settings-bio").val();
  	var lname=$("#settings-lname").val();
  	var fname=$("#settings-fname").val();
  	ldata={'fname': fname,'lname':lname,'bio':bio,'date':date,'month':month,'year':year};
      var sdata =JSON.stringify(ldata);
      $('#settings-save-personal').html("<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>");
      disable_submit(true);
      lnameflag=0;
      $.post(host+"/api/settings/personal/",
      	{
        	data:btoa(sdata)
      	},
      	function(data, status){
      		var response = JSON.parse(JSON.stringify(data));
      		$('#settings-save-personal').html("Save");
      		disable_submit(false);
      		lnameflag=1;
      		$('#settings-res').html(response.msg);
      		if (response.isupdated=="true") {
            $("#profile-bio").html(sanitization(bio));
            $(".user-profilename").html("<h1>"+capitalize(fname)+" "+capitalize(lname)+"</h1>");
           }
      	}
     );
  });
  //personal setting save

  function sanitization(str){

    
    return str.replace(/\&/g, '&amp;')
        .replace(/\</g, '&lt;')
        .replace(/\>/g, '&gt;')
        .replace(/\"/g, '&quot;')
        .replace(/\'/g, '&#x27')
        .replace(/\//g, '&#x2F');
  }

  const capitalize = (s) => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
  }

  //change pass settings
    $("#setchangepass").click(function(){
      var oldpass=$("#settings-opass").val();
      var newpass=$("#settings-npass").val();
      changepass(oldpass,newpass);
    });

    function changepass(oldpass,newpass){
      var ldata={'opass':oldpass,'npass':newpass};
      var sdata =JSON.stringify(ldata);
      $.post(host+"/api/settings/security/changepass/",
        {
          data:btoa(sdata)
        },
        function(data, status){
          var response = JSON.parse(JSON.stringify(data));
          alert(response.msg);
        }
      );
    }
  //change pass settings

  //change about
  $("#setabout").click(function(){
    var country=$("#countryId").val();
    var city=$("#cityId").val();
    var workplace=$("#settings-workplace").val();
    var worktitle=$("#settings-worktitle").val();
    var classname=$("#settings-deg-title").val();
    var institue=$("#settings-deg-institute").val();
    changeabout(country,city,workplace,worktitle,classname,institue);
  });
  function changeabout(country,city,workplace,worktitle,classname,institue){
    var ldata={'country':country,'city':city,'workplace':workplace,'worktitle':worktitle,'classname':classname,'institue':institue};
      var sdata =JSON.stringify(ldata);
      $('#setabout').html("<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>");
      $('#setabout').prop("disabled",true);
      $.post(host+"/api/settings/about/",
        {
          data:btoa(sdata)
        },
        function(data, status){
          $('#setabout').prop("disabled",false);
          $('#setabout').html("Save");
          var response = JSON.parse(JSON.stringify(data));
          $("#about-res").html(response.msg);
        }
      );
  }
  //change about


  //unfriend already_friend
  $('body').delegate('.un_friend','click',function() {
    var id=$(this).parents(".one_friend").attr("id");
    var ldata={'id':id};
    var sdata =JSON.stringify(ldata);
    $.post(host+"/api/friends/unfriend/",
        {
          data:btoa(sdata)
        },
        function(data, status){
            $("#"+id).remove();
        }
      );
    $("#"+id).remove();
  });
  $('body').delegate('.already_friend','click',function() {
    var id=$(".user-profilename").attr("id");
    var ldata={'id':id};
    var sdata =JSON.stringify(ldata);
    $.post(host+"/api/friends/unfriend/",
        {
          data:btoa(sdata)
        },
        function(data, status){
          location.reload();
        }
      );
  });
   //unfriend


   //accept friend request
   $('body').delegate('.accept_friend','click',function() {
    var id=$(this).parents(".one_friend").attr("id");
    var ldata={'id':id};
    var sdata =JSON.stringify(ldata);
    $.post(host+"/api/friends/accept/",
        {
          data:btoa(sdata)
        },
        function(data, status){
            $("#"+id).remove();
        }
      );
    $("#"+id).remove();
  });
   $('body').delegate('.not_accept_friend','click',function() {
    var id=$(this).parents(".one_friend").attr("id");
    var ldata={'id':id};
    var sdata =JSON.stringify(ldata);
    $.post(host+"/api/friends/accept/?action=delete",
        {
          data:btoa(sdata)
        },
        function(data, status){
            $("#"+id).remove();
        }
      );
    $("#"+id).remove();
  });
   //accept friend request


  //show friends
  console.log(action);
  show_friends();
  function show_friends(){
    if (action=="friends") {
    $.get(host+"api/friends/my/", function(data) {
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
  }
  //show friends

  //show friend requests
  show_friend_requests();
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
  //show friend requests

});