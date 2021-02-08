<?php
//direct access not allowed
if (getcwd() == dirname(__FILE__)) {
    http_response_code(404);
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home | Social Code</title>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<!--bootstrap assets-->
    <link rel="stylesheet" href="assets/css/boot.css">
	<link rel="stylesheet" href="assets/css/newsfeed.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/fontawesome.min.css">
	<script type="text/javascript" src="assets/js/jquery-modified.min.js"></script>
	<link rel="stylesheet" href="assets/css/emojionearea.css">
	<script type="text/javascript" src="assets/js/emojionearea.js"></script>
	<link href="assets/css/videojs.css" rel="stylesheet" />
  	<script src="assets/js/videojs.js"></script>
  	<script src="assets/js/spectrum.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/css/spectrum.css">
	<link rel="stylesheet" type="text/css" href="assets/css/fontselect.css" />
    <script src="assets/js/fontselect.js"></script>
    <link href="https://pagecdn.io/lib/easyfonts/fonts.css" rel="stylesheet" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/logo/favicon.ico">
	<style type="text/css">
      .no-js #loader { display: none;  }
      .js #loader { display: block; position: absolute; left: 100px; top: 0; }
      .k2-loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: center no-repeat #fff;
      }
    </style>
</head>
<body style="background-image: none;background-color: whitesmoke;">
<!--loader-->
	<div class="k2-loader"><span class='spinner-border spinner-border-lg' style="top:50%;left:50%;position:fixed;" role='status' aria-hidden='true'></span></div>
<!--loader-->

    
    <?php
    	require("api/inc/global.php");
    ?>

<!-------------------Newsfeed body area--------------->
<div class="container body-area" style="display: flex!important;">
	<!--div class="row"-->
		<!---------------left widget area---------------->
		<div class="col-sm-3 left-widgets sticky-top" style="top: 100px!important; z-index: 0!important;">
			<div class="">
				<!---------------suggested pages to like------------->
				<div class="card suggested-pages-widget" style="border-radius: 15px 50px;">
				  <div class="card-body">
				  	<div class="widget-title-area">
				  		<h5 class="card-title widget-title">Pages you may like</h5>
				  	</div>
				  	<div class="suggest-pages">
					    <!--content will be added using jquery after getting data from server-->
				  	</div>
				  </div>
				</div>
				<!---------------suggested pages to like-------------->
			</div>
		</div>
		<!---------------left widget area---------------->

		<!---------------main post widget area---------------->
		<div class="col-sm-6">

			<!---------------create post area---------------->
			<div class="card create_post_area">
			  <div class="card-body">

			    <div class="nav nav-tabs">
  					<button class="list-group-item heading-text first-type">
						<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-card-text" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
							<path fill-rule="evenodd" d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
						</svg>  
						Post
					</button>
 					<button class="list-group-item heading-text status-open-button">
						<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-images" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M12.002 4h-10a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1zm-10-1a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-10zm4 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
							<path fill-rule="evenodd" d="M4 2h10a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1v1a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2h1a1 1 0 0 1 1-1z"/>
						</svg>
						 Status
					</button>
				</div>
				<div class="create-main">
					<div>
						<img class="user-avatar-create-post-area" src="../images/user1.jpg">
					</div>
					<div style="width: 100%;margin: 2px;">
						<textarea class="create-post emojionearea" id="post-text-area" placeholder="Share something with your friends"></textarea>
					</div>
				</div>
				<div class="post-multimedia">
					<!--content will added on demand:)-->
				</div>
				<hr class="aaa">
				<div class="options-post">
					<div class="create-post-extras">
						<div class="post-create-add" data-toggle="tooltip" data-placement="top" title="Add photos/videos">
							<input type="file" id="img-post-create" name="img" accept="image/png,image/jpg,video/mp4">
							<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-camera add-media-in-post" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M15 12V6a1 1 0 0 0-1-1h-1.172a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 9.173 3H6.828a1 1 0 0 0-.707.293l-.828.828A3 3 0 0 1 3.172 5H2a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z"/>
								<path fill-rule="evenodd" d="M8 11a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
								<path d="M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
							</svg>
						</div>


						<div class="post-create-add" id="emojies-in-post" data-toggle="tooltip" data-placement="top" title="Emojies">
							<svg width="1.5em" height="1.5em" viewBox="0 0 473.931 473.931" style="enable-background:new 0 0 473.931 473.931;" xml:space="preserve">
								<circle style="fill:#FFC10E;" cx="236.966" cy="236.966" r="236.966"/>
								<g>
									<path style="fill:#ED3533;" d="M182.13,109.97c-14.133,0-27.262,6.892-35.154,18.432l-1.628,2.069l-1.089-1.388
										c-7.802-11.962-20.954-19.109-35.232-19.109c-24.363,0-42.042,17.684-42.042,42.039c0,35.962,65.107,88.968,69.855,92.784
										c2.241,2.144,5.175,3.323,8.288,3.323s6.047-1.175,8.288-3.326c4.763-3.847,70.753-57.683,70.753-92.781
										C224.169,127.654,206.489,109.97,182.13,109.97z"/>
									<path style="fill:#ED3533;" d="M366.696,109.97c-14.133,0-27.262,6.892-35.154,18.432l-1.628,2.069l-1.089-1.388
										c-7.802-11.962-20.954-19.109-35.232-19.109c-24.363,0-42.042,17.684-42.042,42.039c0,35.962,65.107,88.968,69.855,92.784
										c2.241,2.144,5.175,3.323,8.288,3.323c3.109,0,6.043-1.175,8.288-3.326c4.76-3.843,70.749-57.683,70.749-92.781
										C408.735,127.654,391.055,109.97,366.696,109.97z"/>
								</g>
								<path style="fill:#333333;" d="M343.254,316.86c-59.281,60.325-154.662,59.853-213.449-0.898c-8.4-8.681-21.616,4.561-13.227,13.227
									c65.769,67.969,173.644,68.332,239.903,0.898C364.941,321.481,351.718,308.246,343.254,316.86L343.254,316.86z"/>
							</svg>
						</div>
					</div>
					<div class="create-post-button right">
						<button type="button" class="btn custom-button submit-post" id="create-post-submit">Post</button>
					</div>
				</div>
			  </div>
			</div>
			<!---------------create post area---------------->

			<!------------------show post area----------------->
			<div class="main_post_area">

			<!-----------------------Post will be added here-------------------------->
			</div>
			<!------------------show post area----------------->

		</div>
		<!-------------------main post widget area------------------->


		<!----------------------right widget area--------------------->
		<div class="col-sm-3 right-widgets sticky-top" style="top: 100px!important; z-index: 0!important;">

			<!---------------------people you may know--------------------->
			<div class="card suggested-friends-widget" style="border-radius: 16px 50px 5px 5px;">
			  <div class="card-body">
			  	<div class="widget-title-area">
			  		<h5 class="card-title widget-title">Friends suggestion</h5>
			  	</div>
			  	<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
				  <div class="carousel-inner suggested_friends">
				   <!---item will added by query----->
				  </div>
				  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev" style="
				    background-color: #00000014;">
				    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
				    <span class="sr-only">Previous</span>
				  </a>
				  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next" style="
				    background-color: #00000014;">
				    <span class="carousel-control-next-icon" aria-hidden="true"></span>
				    <span class="sr-only">Next</span>
				  </a>
				</div>
			  </div>
			</div>
			<!---------------------people you may know--------------------->

			<!---------------------Ads--------------------->
			<div class="card activity-feed-widget" style="">
			  <div class="card-body">
			  	<figure class="figure">
				  <img src="assets/images/ad.png" class="figure-img img-fluid rounded" alt="A generic square placeholder image with rounded corners in a figure.">
				  <figcaption class="figure-caption">Sponsored</figcaption>
				</figure>
			  </div>
			</div>
			<!---------------------Ads--------------------->

		</div>
		<!-----------------------right widget area------------------->
	<!--/div-->
</div>
<!-------------------Newsfeed body area--------------->


<!-------------modal for showing post media--------------->
<div class="modal fade" id="post-media-thumb" tabindex="-1" role="dialog" aria-labelledby="post-media-thumbLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="post-media-thumbLabel">Amazing user's Post</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body modal-post-show">
			<div class="modal-post-media-area">
				<div class="media-thumb">
				</div>
			</div>
			<div class="post-body modal-post-body one-post-div">
				<div class="post-header">
					<div style="float: left;">
						<img class="user-avatar-post-header modal-user-avatar" src="">
					</div>
					<div style="float: left;" class="post-details">
						<div class="owner-name"><a target="_self" href="#"></a></div>
						<div class="post-time"></div>
					</div>
				</div>
				<div class="post-text-area">
					<div class="post-text-div">
						<span class="post-text-content"></span>
					</div>
				</div>
				<div class="post-footer">
					<hr>
					<div class="post-stats">
						<div class="like">
							<div class="isliked">
								
							</div>
							<span class="heart-counts"></span>
						</div>
						<div class="comment right">
							<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-chat-left-text post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v11.586l2-2A2 2 0 0 1 4.414 11H14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"></path>
								<path fill-rule="evenodd" d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"></path>
							</svg>
							<div class="comment-counts">
								12
							</div>
						</div>
					</div>
				</div>
				<div>
					<hr>
				</div>
				<div class="post-comments"></div>
			</div>
		</div>
	  </div>
	</div>
  </div>
  <div class="modal fade" id="simple-post-media-thumb" tabindex="-1" role="dialog" aria-labelledby="post-media-thumbLabel" aria-hidden="true">
	<div class="modal-dialog simple-modal" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="simple-modal-title" id="post-media-thumbLabel">Amazing user's Post</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body modal-post-show">
			<div class="post-body modal-post-body one-post-div">
				<div class="post-header">
					<div style="float: left;">
						<img class="user-avatar-post-header modal-user-avatar" src="">
					</div>
					<div style="float: left;" class="post-details">
						<div class="owner-name"><a target="_self" href="#"></a></div>
						<div class="post-time"></div>
					</div>
				</div>
				<div class="post-text-area">
					<div class="post-text-div">
						<span class="post-text-content"></span>
					</div>
				</div>
				<div class="post-footer">
					<hr>
					<div class="post-stats">
						<div class="like">
							<div class="isliked">
								
							</div>
							<span class="heart-counts"></span>
						</div>
						<div class="comment right">
							<svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-chat-left-text post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v11.586l2-2A2 2 0 0 1 4.414 11H14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"></path>
								<path fill-rule="evenodd" d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"></path>
							</svg>
							<div class="comment-counts">
								12
							</div>
						</div>
					</div>
				</div>
				<div>
					<hr>
				</div>
				<div class="post-comments"></div>
			</div>
		</div>
	  </div>
	</div>
  </div>
  <!-------------modal for showing post media--------------->

  <!-----------modal for creating status------------------->
	  <div class="modal" id="status_create_modal" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" style="color: #515365c9!important;
    font-weight: 700!important;
    font-size: 1rem!important;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis!important;
    line-height: 1.3em!important;">Add Status</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<div class="create_status_text">
		      		<div class="text_status_settings d-flex" style="display: flex;">
		      			<input id="font_select" type="text">
		      			<div class="text_bg float-right" style="text-align: end;">
		      				<label for="color-picker" class="label_status" style="vertical-align: text-bottom;">Select Color:</label>
		      				<input id="color-picker" value='#05cd51' style="width: 4em;" placeholder="" />
		      			</div>
		      		</div>
		        	<div class="status_background" style="width: 100%;height: 300px;">
		        		<textarea class="status_text_box" maxlength="250" style="font-family: Times New Roman;">Enter text here</textarea>
		        	</div>
		      	</div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-primary add_status">Add</button>
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
	  </div>
  <!-----------modal for creating status------------------->

<!--bootstrap assets-->
<script type="text/javascript" src="assets/js/popper.js" ></script>
<script type="text/javascript" src="assets/js/boot.js"></script>
<script type="text/javascript" src="assets/js/newsfeed.js"></script>
<script type="text/javascript" src="assets/js/global.js"></script>
<!--bootstrap assets--> 
<script src="assets/js/video.js"></script>

<script>
	//initialize tooltips on document ready
	$(".options-post,.aaa").hide();
	$(document).ready(function(){
  		$('[data-toggle="tooltip"]').tooltip({ boundary: 'window', animation: false});
	});
	$('#color-picker').spectrum({
  		type: "component"
	});
</script>

</body>
</html>