<?php
//error_reporting(0); // Disable all errors.
header("Content-type: text/html");
if (isset($_GET["pid"])) {
  require("../api/db-con/db.php");
  $pid=$_GET["pid"];

  //validation
  $pid=mysqli_real_escape_string($conn,$pid);
  $pid=strip_tags($pid);
  $pid=trim($pid);
  $pid = filter_var($pid, FILTER_SANITIZE_STRING);
  //validation

  if (checkpage($pid)) {   //check if page exists or not
    get_page_data($pid);
  }
  else{
    show404();
    die();
  }
  
}
else{
  show404();
  die();
}
?>

<?php
function showpage($pid,$name,$bio,$dplink,$coverlink,$firstname,$website){
  //sanitization
  $firstname=sanitization($firstname);
  $name=sanitization($name);
  $bio=sanitization($bio);
  //sanitization
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <link rel="stylesheet" href="../assets/css/boot.css">
    <script type="text/javascript" src="../assets/js/jquery-modified.min.js"></script>
    <title><?php echo htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" type="text/css" href="../assets/css/page.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/icons.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/global.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/croppie.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/sweet.css" />
    <link href="https://pagecdn.io/lib/easyfonts/fonts.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../assets/css/dpupload.css" />
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
    <body style="background-image: none; background-color: whitesmoke;">
    <!--loading-->
    <div class="k2-loader"><span class='spinner-border spinner-border-lg' style="top:50%;left:50%;position:fixed;" role='status' aria-hidden='true'></span></div>
      <!--loading-->

      <?php
        require("../api/inc/global.php");
      ?>


        <div class="container-fluid" style="margin-top: 60px;">
        <div class="row">
           <div class="col-sm-12" style="background-image: linear-gradient(rgba(171, 171, 171, 0.42) , white);">
               
            <div class="d-flex justify-content-center">
              <div class="profile-cover border">
              <img class="img-fluid" src=<?php echo "'".$coverlink."'"; ?>>
                   <?php
                        if (checklogin($pid)) {
                      ?>
                        <div class='text-cover-drag'>Drag to adjust</div>
                        <div class='text-cover-save btn btn-success'>Save</div>
                          <div class='text-cover-cancel btn btn-success'>Cancel</div>
                        <div class='edit-div-cover' id='coverupload'>
                          <div class='text-cover'>Edit</div>
                        </div>
                      <?php
                        }
                      ?>
              </div>
               
            </div>

           </div>

        </div>

        <div class="row">
          <div class="col-sm-12" style="background-color: white;">
           <div class="d-flex justify-content-center">
             <div class="circular--portrait">
             <img id="dp" src= <?php echo "'".$dplink."'"; ?>>
             <?php
                  if (checklogin($pid)) {
                  ?>
                    <div class='edit-div' id='dpupload'>
                      <div class='text'>Edit</div>
                    </div>
                  <?php
                  }
                  ?>
             </div>
           </div>

          </div>

         </div>
         <div class="row">
          <div class="col-sm-12" style="background-color: white;">
            <div class="d-flex justify-content-center">
              <div class="user-profilename">
                <h1><?php echo $name; ?></h1>
              </div>
            </div>
          </div>
         </div>
         <div class="row">
          <div class="col-sm-12" style="background-color: white;">
            <div class="d-flex justify-content-center">
              <div class="user-bio">
               <p><span id="profile-bio"><?php echo htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'); ?></span></p>
                </div>
               </div>
             </div>
          </div>  
          <div class="row">
            <div class="col-sm-12" style="background-color: white;">
              <div class="d-flex justify-content-center">
                <div class="troops-bar">
                  
                </div>
              </div>
            </div>
          </div>
          <div class="row"> 
            <div class="col-sm-12">
            <div class="d-flex justify-content-center">
              <div class="data-posts">
              <div class="row">
                <!--user Data section begins-->
              <div class="col-sm-4">
                 <div class="row">
                   <div class="col-sm-12">
                     <div class="intro-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">About</h4>
                        <p class="card-text">
                          <ul class="user-about" >
                          <?php if ($firstname!=NULL) {?>
                                    <hr>
                                  <li class="user-work" >
                                    <svg class="bi bi-geo-alt k2-icons" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                      <path fill-rule="evenodd" d="M8 16s6-5.686 6-10A6 6 0 002 6c0 4.314 6 10 6 10zm0-7a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                    </svg><?php echo " ".htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8'); ?>
                                  </li>
                                  <?php }?>
                                  <!--full name-->
                                  <!--bio-->
                                  <?php if ($bio!=NULL) {?>
                                  <li class="user-work">
                                    <svg class="bi bi-briefcase-fill k2-icons" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                      <path fill-rule="evenodd" d="M0 12.5A1.5 1.5 0 001.5 14h13a1.5 1.5 0 001.5-1.5V6.85L8.129 8.947a.5.5 0 01-.258 0L0 6.85v5.65z" clip-rule="evenodd"/>
                                      <path fill-rule="evenodd" d="M0 4.5A1.5 1.5 0 011.5 3h13A1.5 1.5 0 0116 4.5v1.384l-7.614 2.03a1.5 1.5 0 01-.772 0L0 5.884V4.5zm5-2A1.5 1.5 0 016.5 1h3A1.5 1.5 0 0111 2.5V3h-1v-.5a.5.5 0 00-.5-.5h-3a.5.5 0 00-.5.5V3H5v-.5z" clip-rule="evenodd"/>
                                    </svg><?php echo " ".htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'); ?>
                                  </li>
                                  <?php }?>
                                  <!--bio-->
                                  <!--website-->
                                  <?php if ($website!=NULL) {?>
                                  <li class="user-work">
                                    <svg class="bi bi-book-half k2-icons" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                      <path fill-rule="evenodd" d="M3.214 1.072C4.813.752 6.916.71 8.354 2.146A.5.5 0 018.5 2.5v11a.5.5 0 01-.854.354c-.843-.844-2.115-1.059-3.47-.92-1.344.14-2.66.617-3.452 1.013A.5.5 0 010 13.5v-11a.5.5 0 01.276-.447L.5 2.5l-.224-.447.002-.001.004-.002.013-.006a5.017 5.017 0 01.22-.103 12.958 12.958 0 012.7-.869zM1 2.82v9.908c.846-.343 1.944-.672 3.074-.788 1.143-.118 2.387-.023 3.426.56V2.718c-1.063-.929-2.631-.956-4.09-.664A11.958 11.958 0 001 2.82z" clip-rule="evenodd"/>
                                      <path fill-rule="evenodd" d="M12.786 1.072C11.188.752 9.084.71 7.646 2.146A.5.5 0 007.5 2.5v11a.5.5 0 00.854.354c.843-.844 2.115-1.059 3.47-.92 1.344.14 2.66.617 3.452 1.013A.5.5 0 0016 13.5v-11a.5.5 0 00-.276-.447L15.5 2.5l.224-.447-.002-.001-.004-.002-.013-.006-.047-.023a12.582 12.582 0 00-.799-.34 12.96 12.96 0 00-2.073-.609z" clip-rule="evenodd"/>
                                    </svg><?php echo " ".htmlspecialchars($website, ENT_QUOTES, 'UTF-8'); ?>
                                  </li>
                                <?php }?>
                                  <!--website-->
                          </ul>
                        </p>
                        <?php
                                if (checklogin($pid)) {
                              ?>
                              <div class="btn-editbio">
                              <!--setting open-->
                              <button type="button" class="btn btn-success" id="lauch-settings" data-toggle="modal" data-target="#settingsiframe"> 
                                <svg class="bi bi-gear-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 01-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 01.872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 012.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 012.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 01.872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 01-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 01-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 100-5.86 2.929 2.929 0 000 5.858z" clip-rule="evenodd"/>
                                </svg> Settings
                              </button>
                              <!--setting open-->
                              </div>
                            <?php }?>
                      </div>
                        </div>
                        </div>
                   </div>
                 </div>
                 <!--User friend section-->
                 <div class="row" style="margin-top: 1em;">
                  <div class="col-sm-12">
                    <div class="intro-card">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Videos</h4>
                          <p class="card-text">Videos will show here </p>
                          <?php
                          if (checklogin($pid)) {
                         ?>
                          <div class="btn-editbio">
                            <button type="button" class="btn btn-outline-success">Manage People </button>
                            </div>
                           <?php
                            }
                          ?> 
                          </div>
                          </div>
                    </div>
                  </div>
                 </div> 
                 <!--User photo section-->
                 <div class="row" style="margin-top: 1em;">
                  <div class="col-sm-12">
                    <div class="intro-card">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Photos</h4>
                          <p class="card-text">Pages pictures will show here </p>
                          <?php
                           if (checklogin($pid)) {
                           ?>
                          <div class="btn-editbio">
                            <button type="button" class="btn btn-outline-success">Manage Photos </button>
                            </div>
                           <?php
                           }
                           ?> 
                          </div>
                          </div>
                    </div>
                  </div>
                 </div> 
            </div>
            <div class="col-sm-1" style="margin-right: -3em;" ><!--just nothing space between user data and post --></div>
             <!--User posts section begin-->
             <div class="col-sm-7">
              <div class="row">
                <div class="col-sm-12">
                  <div class="share-card">
                    <div class="card">
                      <div class="card-body">
                          <div class="d-flex flex-row">
                          <div><img src="./assets/images/user1.jpg" alt="Avatar" class="user-avatar"></div>
                          <div class="say-some"><input type="text" class="form-control form-control-lg" placeholder="Share something with troops " style="border-radius: 20px; background-color: whitesmoke;">
                          </div>
                        </div>
                        <div class="photo-video">
                          <div class="row">
                            <div class="col-sm-4"><button class="clk">
                              <div class="d-flex p-3">
                              <div class="photo-avatar"><img src="./assets/images/icons troops/icons png/photof.png"></div>
                              <div class="photo-text">Photo/Video</div>
                              </div>
                            </button>
                          </div>
                            <div class="col-sm-4"><button class="clk">
                              <div class="d-flex p-3">
                                <div class="photo-avatar"><img src="./assets/images/icons troops/icons png/feelingf.png"></div>
                                <div class="photo-text">Feelings</div>
                                </div>
                            </button>
                            </div>
                            <div class="col-sm-4"><button class="clk">
                              <div class="d-flex p-3">
                                <div class="photo-avatar"><img src="./assets/images/icons troops/icons png/storyf.png"></div>
                                <div class="photo-text">Story</div>
                                </div>
                            </button>
                          </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>

               </div>
               </div>   
               <div class="row">
                <div class="col-sm-12">
                  <div class="share-card">
                    <div class="card">
                      <div class="card-body">
                          <h3>Posts</h3>
                      </div>
                      </div>
                  </div>
                </div>
                </div>
              <!--User post Display area-->
                <div class="row">
                  <div class="col-sm-12">
                    <div class="share-card">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex">
                            <div><img src="./assets/images/user1.jpg" alt="Avatar" class="user-avatar"></div>
                            <div class="uname-post flex-grow-1">
                              <div class="d-flex flex-column">
                                <div>Username</div>
                                <div>Date</div>
                              </div>  
                            </div>
                           <div class="flex-grow-1">
                             Troop Name here
                        </div>
                        <div>
                          . . .
                     </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="post-photo">
                              posted video or photo column 
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="people-react">
                              You reacted "maza nahi aya" 
                            </div> 
                            </div>
                          </div>
                         <div class="row">
                            <div class="col-sm-12">
                              <div class="like-comment">
                                <div class="d-flex">
                                  <div class="flex-fill">Like</div>
                                  <div class="flex-fill">Comment</div>
                                  <div class="flex-fill">Share</div>
                                </div>
                              </div> 
                              </div>
                          </div> 
                        </div>
                        </div>
                    </div>
                  </div>
                  </div>
            </div>
            <!--user post section end here-->
            <?php if (checklogin($pid)){?>
<!-- Modal dp change -->
<div class="modal fade" id="dpupload-iframe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change profile picture</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="dpuloadmodalbody">
        <div class='container'>
            <div class='row'>
                <div class='col-sm-12' style='text-align: center;'>
                    <div class='upload-demo-wrap'>
                        <div id='upload-demo'></div>
                    </div>
                </div>
            </div>

            <!--upload row-->
            <div class='row' style='padding-top: 50px;'>
                <div class='col-sm-12' style='text-align: center;'>
                    <div class='actions'>
                        <a class='btn file-btn'>
                            <label for='uploaddp' class='btn btn-success'>Select Image</label>
                            <input type='file' id='uploaddp' value='Choose a file' style='display: none;' accept='image/*' />
                        </a>
                        <br /><br />
                        <button class='upload-result btn btn-success' id='dpsave'>Save</button>
                    </div>
                </div>
            </div>
            <!--upload row-->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal dp change -->


<!-- Modal cover change -->
<div class="modal fade" id="cover-upload-iframe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change cover picture</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="coveruloadmodalbody">
        <div class='container'>
            <!--upload row-->
            <div class='row' style='padding-top: 50px;'>
                <div class='col-sm-12' style='text-align: center;'>
                    <div class='actions'>
                        <a class='btn file-btn'>
                            <label for='uploadcover' class='btn btn-success'>Select Image</label>
                            <input type='file' id='uploadcover' value='Choose a file' style='display: none;' accept='image/*' />
                        </a>
                        <br /><br />
                    </div>
                </div>
            </div>
            <!--upload row-->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal cover change -->

<div class="modal fade" id="settingsiframe" tabindex="-1" role="dialog" aria-labelledby="settings" aria-hidden="true">
  <div class="modal-dialog" role="document" style="min-width: 60%!important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settings">Settings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="settingsbody">
        
        <div class="container">
          <div class="row">
            <div class="col-sm-4">

              <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="personal-pill-v" data-toggle="pill" href="#personal-pill" role="tab" aria-controls="personal-pill" aria-selected="true">Personal</a>
                <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">extra</a>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="tab-content" id="v-pills-tabContent">
                <!--personal-->
                <div class="tab-pane fade show active" id="personal-pill" role="tabpanel" aria-labelledby="personal-pill-v">
                  <!---first and last name row-->
                  <div class="form-row">
                    <div class="col">
                      <label for="settings-fname">Company Full Name</label>
                    <input type="text" class="form-control" id="settings-fname" aria-describedby="first name" value=<?php echo "'".$firstname."'"; ?>>
                    </div>
                  </div>
                  <hr>
                  <!---first and last name row-->
                  <!--bio row-->
                  <div class="form-row">
                    <div class="col">
                      <label for="settings-bio"><h5>About Company</h5></label>
                      <textarea class="form-control" id="settings-bio" maxlength="100" rows="3"><?php echo htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'); ?></textarea>
                      <small id="bio-remaining" class="form-text text-muted"></small>
                    </div>
                  </div>
                  <hr>
                  <!--bio row-->
                  <!--dob row-->
                  <div class="form-row">
                    <div class="col">
                      <label for="settings-bio"><h5>Company Website</h5></label>
                      <textarea class="form-control" id="settings-bio" maxlength="100" rows="3"><?php echo htmlspecialchars($website, ENT_QUOTES, 'UTF-8'); ?></textarea>
                      <small id="bio-remaining" class="form-text text-muted"></small>
                    </div>
                  </div>
                      <!--dob row-->
                      <div style="text-align: center;">
                        <small id="settings-res" class="form-text text-muted"></small>
                        <button type="button" class="btn btn-success" id="settings-save-personal">
                          Save
                        </button>
                      </div>
                  </div>
                <!--personal-->
                <!--security-->
               
                <!--about-->
               
                <!--about-->
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
              </div>
              </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>

<?php }?>
            </div>
            </div>
            </div>
            </div>
          </div>
        </div>
    
    <script type="text/javascript" src="../assets/js/popper.js" ></script>
    <script type="text/javascript" src="../assets/js/boot.js"></script>
    <script type="text/javascript" src="../assets/js/sweet.js"></script>
    <script type="text/javascript" src="../assets/js/croppie.js"></script>
    <script type="text/javascript" src="../assets/js/pagedpupload.js"></script>
    <script src="//geodata.solutions/includes/countrystatecity.js"></script>
    <script type="text/javascript" src="../assets/js/global.js"></script>
    <!--bootstrap assets--> 

     <script type="text/javascript" src="../assets/js/page.js">
     </script>       
</body>
</html>

<?php
}
?>

<?php
function get_page_data($pid){
  require("../api/db-con/db.php");
  $sql = "SELECT * FROM pages where pid='$pid'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) ==1) {
    while($row = mysqli_fetch_assoc($result)) {
      $firstname=$row["pname"];
      $bio=$row["pbio"];
      $dp=$row["dplink"];
      $cover=$row["coverlink"];
      $website=$row["website"];
      
    }
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
      $scheme = 'https://';
    } else {
      $scheme = 'http://';
    }
    $host=$scheme.$_SERVER['SERVER_NAME'];
    $dplink=$host."/fyp/page-content/display-pic/".$dp;
    $coverlink=$host."/fyp/page-content/cover-pic/".$cover;
    $fname=ucfirst($firstname);
    showpage($pid,$fname,$bio,$dplink,$coverlink,ucfirst($firstname),$website);
  }
}

function checkpage($pid){
  require("../api/db-con/db.php");
  $sql = "SELECT * FROM pages where pid='$pid'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) ==1) {
    return true;
  }
  else{
    return false;
  }
}

function checklogin($pid){
  if (isset($_COOKIE["json_token"]) && isset($_COOKIE["ultra_cookie"]) && isset($_COOKIE["k2_cookie"]) && isset($_COOKIE["k2_extra"])) {
      require("../api/db-con/db.php");
      $json=$_COOKIE["json_token"];
      $ultra=$_COOKIE["ultra_cookie"];
      $k2=$_COOKIE["k2_cookie"];
      $extra=$_COOKIE["k2_extra"];
      $json=mysqli_real_escape_string($conn,$json);
      $ultra=mysqli_real_escape_string($conn,$ultra);
      $k2=mysqli_real_escape_string($conn,$k2);
      $extra=mysqli_real_escape_string($conn,$extra);

      $json=base64_encode($json);
      $ultra=base64_encode($ultra);
      $k2=base64_encode($k2);
      $extra=base64_encode($extra);
      $sql = "SELECT * FROM do_login where k2_cookie='$k2' and ultra_cookie='$ultra' and json_token='$json' and extra='$extra'";
      $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) >0) {
      while($row = mysqli_fetch_assoc($result)) {
        $userid=$row["uid"];
        }
        $sql1 = "SELECT * FROM pages where paid='$userid'";
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) >0) {
          while($row1 = mysqli_fetch_assoc($result1)) {
            $pageid=$row1["pid"];
          }
          if ($pageid==$pid) {
            return true;
          }
          else{
            return false;
          }
        }
        else{
          return false;
        }
     }
     else{
        return false;
     }     

} 
else{
  return false;
}
}

function sanitization($word){
  $word=strip_tags($word);
  $word=trim($word);
  $word = filter_var($word, FILTER_SANITIZE_STRING);
  return $word;
}
function show404(){
  ?>
  <!DOCTYPE html>
  <html lang="en">
  
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,900" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../assets/css/404.css" />
  </head>
  <body>
    <div id="notfound">
      <div class="notfound">
        <div class="notfound-404">
          <h1>Oops!</h1>
        </div>
        <h2>404 - Page not found</h2>
        <p>This page is not available.</p>
      </div>
    </div>

  </body>
  </html>
  <?php
  }
  ?>
