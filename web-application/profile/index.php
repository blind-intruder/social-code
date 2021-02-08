<?php
//error_reporting(0); // Disable all errors.
header("Content-type: text/html");
require("../api/db-con/db.php");
if (isset($_GET["id"])) {
  $uid=$_GET["id"];
  //validation
  $uid=mysqli_real_escape_string($conn,$uid);
  $uid=strip_tags($uid);
  $uid=trim($uid);
  $uid = filter_var($uid, FILTER_SANITIZE_STRING);
  //validation


if (is_loggedin()==0) {
  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
  $scheme = 'https://';
} else {
  $scheme = 'http://';
}
$host=$scheme.$_SERVER['SERVER_NAME'];
header("location:".$host."/fyp");
}


  if (checkuser($uid)) {   //check if username exists or not
    get_user_data($uid);
  }
  else{
    show404();
    die();
  }
  
}
else{
  if (get_user()!=false) {   //check if username exists or not
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
      $scheme = 'https://';
    } else {
      $scheme = 'http://';
    }
    $host=$scheme.$_SERVER['SERVER_NAME'];
    $uri=$host."/fyp/profile/".get_user();

    header("location:".$uri);
  }
  else{
    show404();
    die();
  }
}
?>

<?php
function showprofile($uid,$name,$bio,$dplink,$coverlink,$firstname,$lastname,$date,$month,$year,$location,$work,$education,$uuid){
  //sanitization
  $firstname=sanitization($firstname);
  $lastname=sanitization($lastname);
  $name=sanitization($name);
  $bio=sanitization($bio);
  $location=base64_decode($location);
  $work=base64_decode($work);
  $education=base64_decode($education);
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
    <title><?php echo htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8')." ".htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" type="text/css" href="../assets/css/profile.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/icons.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/global.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/croppie.min.css" />
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/sweet.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/dpupload.css" />
    <link href="https://pagecdn.io/lib/easyfonts/fonts.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/emojionearea.css">
    <script type="text/javascript" src="../assets/js/emojionearea.js"></script>
    <link rel="stylesheet" href="../assets/css/newsfeed.css">
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

        <div class="container-fluid">
            <div class="row">
               <div class="col-sm-12" style="background-image: linear-gradient(rgba(171, 171, 171, 0.42) , white);">
                   
                <div class="d-flex justify-content-center">
                  <div class="profile-cover border">
                      <img class="img-fluid" src=<?php echo "'".$coverlink."'"; ?>>
                      <?php
                        if (checklogin($uid)) {
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
                  if (checklogin($uid)) {
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
                    <div class="user-profilename" id=<?php echo "".$uuid.""; ?>>
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
                    <div class="d-flex justify-content-center" style="    margin-bottom: 1em;">
                      <?php 
                      if (is_loggedin()!=$uuid) {
                        if (!is_requested(is_loggedin(),$uuid)) {
                          if(check_friend($uuid)){
                            echo '<button type="button" class="btn btn-outline-success already_friend">Un Friend</button>';
                          }
                          else{
                            echo '<button type="button" class="btn btn-outline-success request_friend">Add Friend</button>'; 
                          } 
                        }
                        else{
                          echo '<span class="btn btn-outline-success request_friend_sent" onmouseover="changeText(this)" onmouseout="defaultText(this)">Friend request sent</span>';
                        }
                      }
                      ?>
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
                           <div class="intro-card sticky-top" style="top: 100px!important; z-index: 0!important;">
                          <div class="card">
                            <div class="card-body" style="padding: 1.25rem!important;">
                              <h4 class="card-title text-center">About</h4>
                              <p class="card-text">
                                <ul class="user-about" >
                                  <!--lives-->
                                  <?php if ($location!=NULL) {?>
                                    <hr>
                                  <li class="user-work" >
                                    <svg class="bi bi-geo-alt k2-icons" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                      <path fill-rule="evenodd" d="M8 16s6-5.686 6-10A6 6 0 002 6c0 4.314 6 10 6 10zm0-7a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                    </svg><?php echo " ".htmlspecialchars($location, ENT_QUOTES, 'UTF-8'); ?>
                                  </li>
                                  <?php }?>
                                  <!--lives-->
                                  <!--work-->
                                  <?php if ($work!=NULL) {?>
                                  <li class="user-work">
                                    <svg class="bi bi-briefcase-fill k2-icons" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                      <path fill-rule="evenodd" d="M0 12.5A1.5 1.5 0 001.5 14h13a1.5 1.5 0 001.5-1.5V6.85L8.129 8.947a.5.5 0 01-.258 0L0 6.85v5.65z" clip-rule="evenodd"/>
                                      <path fill-rule="evenodd" d="M0 4.5A1.5 1.5 0 011.5 3h13A1.5 1.5 0 0116 4.5v1.384l-7.614 2.03a1.5 1.5 0 01-.772 0L0 5.884V4.5zm5-2A1.5 1.5 0 016.5 1h3A1.5 1.5 0 0111 2.5V3h-1v-.5a.5.5 0 00-.5-.5h-3a.5.5 0 00-.5.5V3H5v-.5z" clip-rule="evenodd"/>
                                    </svg><?php echo " ".htmlspecialchars($work, ENT_QUOTES, 'UTF-8'); ?>
                                  </li>
                                  <?php }?>
                                  <!--work-->
                                  <!--education-->
                                  <?php if ($education!=NULL) {?>
                                  <li class="user-work">
                                    <svg class="bi bi-book-half k2-icons" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                      <path fill-rule="evenodd" d="M3.214 1.072C4.813.752 6.916.71 8.354 2.146A.5.5 0 018.5 2.5v11a.5.5 0 01-.854.354c-.843-.844-2.115-1.059-3.47-.92-1.344.14-2.66.617-3.452 1.013A.5.5 0 010 13.5v-11a.5.5 0 01.276-.447L.5 2.5l-.224-.447.002-.001.004-.002.013-.006a5.017 5.017 0 01.22-.103 12.958 12.958 0 012.7-.869zM1 2.82v9.908c.846-.343 1.944-.672 3.074-.788 1.143-.118 2.387-.023 3.426.56V2.718c-1.063-.929-2.631-.956-4.09-.664A11.958 11.958 0 001 2.82z" clip-rule="evenodd"/>
                                      <path fill-rule="evenodd" d="M12.786 1.072C11.188.752 9.084.71 7.646 2.146A.5.5 0 007.5 2.5v11a.5.5 0 00.854.354c.843-.844 2.115-1.059 3.47-.92 1.344.14 2.66.617 3.452 1.013A.5.5 0 0016 13.5v-11a.5.5 0 00-.276-.447L15.5 2.5l.224-.447-.002-.001-.004-.002-.013-.006-.047-.023a12.582 12.582 0 00-.799-.34 12.96 12.96 0 00-2.073-.609z" clip-rule="evenodd"/>
                                    </svg><?php echo " ".htmlspecialchars($education, ENT_QUOTES, 'UTF-8'); ?>
                                  </li>
                                <?php }?>
                                  <!--education-->
                                </ul>
                              </p>
                              <?php
                                if (checklogin($uid)) {
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
                  </div>
                  
                   <!--User posts section begin-->
                   <div class="col-sm-8">
                      <!------------------show post area----------------->
                      <div class="main_post_area">

                      <!-----------------------Post will be added here-------------------------->
                      </div>
                      <!------------------show post area----------------->
                  </div>
        </div>

<?php if (checklogin($uid)){?>
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


<!-- Modal settings -->
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
                <a class="nav-link" id="security-pill-v" data-toggle="pill" href="#security-pill" role="tab" aria-controls="security-pill" aria-selected="false">Security</a>
                <a class="nav-link" id="about-pill-v" data-toggle="pill" href="#about-pill" role="tab" aria-controls="v-pills-messages" aria-selected="false">About</a>
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
                      <label for="settings-fname">First name</label>
                    <input type="text" class="form-control" id="settings-fname" aria-describedby="first name" value=<?php echo "'".$firstname."'"; ?>>
                    </div>
                    <div class="col">
                      <label for="settings-lname">Last name</label>
                    <input type="text" class="form-control" id="settings-lname" aria-describedby="first name" value=<?php echo "'".$lastname."'"; ?>>
                    </div>
                  </div>
                  <hr>
                  <!---first and last name row-->
                  <!--bio row-->
                  <div class="form-row">
                    <div class="col">
                      <label for="settings-bio"><h5>Bio</h5></label>
                      <textarea class="form-control" id="settings-bio" maxlength="100" rows="3"><?php echo htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'); ?></textarea>
                      <small id="bio-remaining" class="form-text text-muted"></small>
                    </div>
                  </div>
                  <hr>
                  <!--bio row-->
                  <!--dob row-->
                  <span><h5>Date of birth</h5></span>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                            <select class='custom-select custom-select-sm mb-3' id='settings-date' name='birthday-date'>
                          <option selected="1" value=<?php echo "'".$date."'"; ?>><?php echo $date; ?></option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option>
                          <option value='4'>4</option><option value='5'>5</option><option value='6'>6</option>
                          <option value='7'>7</option><option value='8'>8</option><option value='9'>9</option>  <option value='10'>10</option><option value='11'>11</option><option value='12'>12</option><option value='13'>13</option><option value='14'>14</option><option value='15'>15</option><option value='16'>16</option><option value='17'>17</option><option value='18'>18</option><option value='19'>19</option><option value='20'>20</option><option value='21'>21</option><option value='22'>22</option><option value='23'>23</option><option value='24'>24</option><option value='25'>25</option><option value='26'>26</option><option value='27'>27</option><option value='28'>28</option><option value='29'>29</option><option value='30'>30</option><option value='31'>31</option>
                        </select>
                        </div>
                        <div class="form-group col-md-4">
                            <select class='custom-select custom-select-sm mb-3' id='settings-month' name='birthday-month'>
                          <option selected="1" value=<?php echo "'".$month."'"; ?>><?php echo $month; ?></option><option value='Jan'>Jan</option><option value='Feb'>Feb</option><option value='Mar'>Mar</option><option value='Apr'>Apr</option><option value='May'>May</option><option value='Jun'>Jun</option><option value='Jul'>Jul</option><option value='Aug'>Aug</option><option value='Sep'>Sep</option><option value='Oct'>Oct</option><option value='Nov'>Nov</option><option value='Dec'>Dec</option>
                        </select>
                        </div>
                        <div class="form-group col-md-4">
                            <select name='signup-birthday-year' id='settings-year' class='custom-select custom-select-sm mb-3'><option selected="1" value=<?php echo "'".$year."'"; ?>><?php echo $year; ?></option><option value='2010'>2010</option><option value='2009'>2009</option><option value='2008'>2008</option><option value='2007'>2007</option><option value='2006'>2006</option><option value='2005'>2005</option><option value='2004'>2004</option><option value='2003'>2003</option><option value='2002'>2002</option><option value='2001'>2001</option><option value='2000'>2000</option><option value='1999'>1999</option><option value='1998'>1998</option><option value='1997'>1997</option><option value='1996'>1996</option><option value='1995'>1995</option><option value='1994'>1994</option><option value='1993'>1993</option><option value='1992'>1992</option><option value='1991'>1991</option><option value='1990'>1990</option><option value='1989'>1989</option><option value='1988'>1988</option><option value='1987'>1987</option><option value='1986'>1986</option><option value='1985'>1985</option><option value='1984'>1984</option><option value='1983'>1983</option><option value='1982'>1982</option><option value='1981'>1981</option><option value='1980'>1980</option><option value='1979'>1979</option><option value='1978'>1978</option><option value='1977'>1977</option><option value='1976'>1976</option><option value='1975'>1975</option><option value='1974'>1974</option><option value='1973'>1973</option><option value='1972'>1972</option><option value='1971'>1971</option><option value='1970'>1970</option><option value='1969'>1969</option><option value='1968'>1968</option><option value='1967'>1967</option><option value='1966'>1966</option><option value='1965'>1965</option><option value='1964'>1964</option><option value='1963'>1963</option><option value='1962'>1962</option><option value='1961'>1961</option><option value='1960'>1960</option><option value='1959'>1959</option><option value='1958'>1958</option><option value='1957'>1957</option><option value='1956'>1956</option><option value='1955'>1955</option><option value='1954'>1954</option><option value='1953'>1953</option><option value='1952'>1952</option><option value='1951'>1951</option><option value='1950'>1950</option><option value='1949'>1949</option><option value='1948'>1948</option><option value='1947'>1947</option><option value='1946'>1946</option><option value='1945'>1945</option><option value='1944'>1944</option><option value='1943'>1943</option><option value='1942'>1942</option><option value='1941'>1941</option><option value='1940'>1940</option><option value='1939'>1939</option><option value='1938'>1938</option><option value='1937'>1937</option><option value='1936'>1936</option><option value='1935'>1935</option><option value='1934'>1934</option><option value='1933'>1933</option><option value='1932'>1932</option><option value='1931'>1931</option><option value='1930'>1930</option><option value='1929'>1929</option><option value='1928'>1928</option><option value='1927'>1927</option><option value='1926'>1926</option><option value='1925'>1925</option><option value='1924'>1924</option><option value='1923'>1923</option><option value='1922'>1922</option><option value='1921'>1921</option><option value='1920'>1920</option><option value='1919'>1919</option><option value='1918'>1918</option><option value='1917'>1917</option><option value='1916'>1916</option><option value='1915'>1915</option><option value='1914'>1914</option><option value='1913'>1913</option><option value='1912'>1912</option><option value='1911'>1911</option><option value='1910'>1910</option><option value='1909'>1909</option><option value='1908'>1908</option><option value='1907'>1907</option><option value='1906'>1906</option><option value='1905'>1905</option></select>
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
                <div class="tab-pane fade" id="security-pill" role="tabpanel" aria-labelledby="security-pill-v">
                  <!--change pass-->
                  <span><h5>Change password</h5></span>
                  <div class="form-row">
                    <div class="col">
                      <label for="settings-fname">Current password</label>
                    <input type="password" class="form-control" id="settings-opass" aria-describedby="Current password">
                    </div>
                    <div class="col">
                      <label for="settings-lname">New password</label>
                    <input type="password" class="form-control" id="settings-npass" aria-describedby="New password">
                    </div>
                  </div>
                  </br>
                  <div class="form-row" style="text-align: right;">
                    <button type="button" class="btn btn-success" id="setchangepass" style="left: 40%;position: relative;">Change</button>
                  </div>
                  <!--change pass-->
                  <hr>
                  <!---change email-->
                  <span><h5>Change email</h5></span>
                  <div class="form-row">
                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="settings-email" value="Enter current email">
                    </div>
                    <div class="col-sm-2">
                      <button type="button" class="btn btn-success" id="setchangeemail">Send
                        </button>
                        </br>
                    </div>
                  </div>
                  <div class="form-row">
                    <small id="settings-res" class="form-text text-muted">You have to enter the current email address & we will send an email on that email address with a link to add new email address to ensure the account security.</small>
                  </div>
                  <!---change email-->
                </div>
                <!--security-->
                <!--about-->
                <div class="tab-pane fade" id="about-pill" role="tabpanel" aria-labelledby="about-pill-v">
                  <!--location-->
                  <span><h5>Location</h5></span>
                  <div class="form-row">
                    <div class="col">
                      <select name="country" class="countries order-alpha presel-byip custom-select custom-select-sm mb-3" id="countryId">
                        <option value="">Select Country</option>
                      </select>
                    </div>
                    <div class="col">
                      <select name="state" class="states order-alpha custom-select custom-select-sm mb-3" id="stateId">
                        <option value="">Select State</option>
                      </select>
                    </div>
                    <div class="col">
                      <select name="city" class="cities order-alpha custom-select custom-select-sm mb-3" id="cityId">
                        <option value="">Select City</option>
                      </select>
                    </div>
                  </div>
                  <!--location-->
                  <hr>
                  <!--work-->
                  <span><h5>Work</h5></span>
                  <div class="form-row">
                    <div class="col">
                      <input type="text" class="form-control" id="settings-workplace" placeholder="Workplace">
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" id="settings-worktitle" placeholder="Job tile">
                    </div>
                  </div>
                  <!--work-->
                  <hr>
                  <!--education-->
                  <span><h5>Education</h5></span>
                  <div class="form-row">
                    <div class="col">
                      <input type="text" class="form-control" id="settings-deg-title" placeholder="Class/Degree">
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" id="settings-deg-institute" placeholder="School/College/University">
                    </div>
                  </div>
                  <!--education-->
                  <!--save about-->
                  <br>
                  <div class="form-row text-center">
                    <div class="col">
                      <small id="about-res" class="form-text text-muted"></small>
                      <button type="button" class="btn btn-success" id="setabout">
                        Save
                      </button>
                    </div>
                  </div>
                  <!--save about-->
                </div>
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
<!-- Modal settings -->
<?php }?>

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
            <div class="share">
              <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-reply post-acts" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.502 5.013a.144.144 0 0 0-.202.134V6.3a.5.5 0 0 1-.5.5c-.667 0-2.013.005-3.3.822-.984.624-1.99 1.76-2.595 3.876C3.925 10.515 5.09 9.982 6.11 9.7a8.741 8.741 0 0 1 1.921-.306 7.403 7.403 0 0 1 .798.008h.013l.005.001h.001L8.8 9.9l.05-.498a.5.5 0 0 1 .45.498v1.153c0 .108.11.176.202.134l3.984-2.933a.494.494 0 0 1 .042-.028.147.147 0 0 0 0-.252.494.494 0 0 1-.042-.028L9.502 5.013zM8.3 10.386a7.745 7.745 0 0 0-1.923.277c-1.326.368-2.896 1.201-3.94 3.08a.5.5 0 0 1-.933-.305c.464-3.71 1.886-5.662 3.46-6.66 1.245-.79 2.527-.942 3.336-.971v-.66a1.144 1.144 0 0 1 1.767-.96l3.994 2.94a1.147 1.147 0 0 1 0 1.946l-3.994 2.94a1.144 1.144 0 0 1-1.767-.96v-.667z"></path>
                </svg>
              <span class="share-counts">
                24  
              </span>
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
  
    <!--bootstrap assets-->
    <script type="text/javascript" src="../assets/js/popper.js" ></script>
    <script type="text/javascript" src="../assets/js/boot.js"></script>
    <script type="text/javascript" src="../assets/js/sweet.js"></script>
    <script type="text/javascript" src="../assets/js/croppie.js"></script>
    <script type="text/javascript" src="../assets/js/dpupload.js"></script>
    <script src="//geodata.solutions/includes/countrystatecity.js"></script>
    <script type="text/javascript" src="../assets/js/global.js"></script>
    <!--bootstrap assets--> 

<script type="text/javascript" src="../assets/js/profile.js">
</script>       
</body>
</html> 
<?php
}

?>

<?php
function get_user_data($uid){
  require("../api/db-con/db.php");
  $sql = "SELECT * FROM users where uid='$uid'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) ==1) {
    while($row = mysqli_fetch_assoc($result)) {
      $firstname=$row["firstname"];
      $lastname=$row["lastname"];
      $bio=base64_decode($row["bio"]);
      $dp=$row["dplink"];
      $cover=$row["coverlink"];
      $date=$row["date"];
      $month=$row["month"];
      $year=$row["year"];
      $location=$row["location"];
      $work=$row["work"];
      $education=$row["education"];
      $id=$row["id"];
    }
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
      $scheme = 'https://';
    } else {
      $scheme = 'http://';
    }
    $host=$scheme.$_SERVER['SERVER_NAME'];
    $dplink=$host."/fyp/user-content/display-pic/".$dp;
    $coverlink=$host."/fyp/user-content/cover-pic/".$cover;
    $fname=ucfirst($firstname)." ".ucfirst($lastname);
    showprofile($uid,$fname,$bio,$dplink,$coverlink,ucfirst($firstname),ucfirst($lastname),$date,$month,$year,$location,$work,$education,$id);
  }
}

function checkuser($uid){
  require("../api/db-con/db.php");
  $sql = "SELECT * FROM users where uid='$uid'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) ==1) {
    return true;
  }
  else{
    return false;
  }
}

function checklogin($uid){
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
        $sql1 = "SELECT * FROM users where id='$userid'";
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) >0) {
          while($row1 = mysqli_fetch_assoc($result1)) {
            $username=$row1["uid"];
          }
          if ($username==$uid) {
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

function get_user(){
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
        $sql1 = "SELECT * FROM users where id='$userid'";
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) >0) {
          while($row1 = mysqli_fetch_assoc($result1)) {
            $username=$row1["uid"];
          }
          return $username; 
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


function is_requested($my_id,$uid){
  require("../api/db-con/db.php");
  $sql = "SELECT * FROM requests where sender='$my_id' and receiver='$uid'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) >0) {
    return true;
  }
  else{
    return false;
  }
}


function is_loggedin(){
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
          $uid=$row["uid"];
          }
          return $uid;     
     }
     else{
        return 0;
     }     

} 
else{
  return 0;
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

function check_friend($id){
  require("../api/db-con/db.php");
  $me=is_loggedin();
  $sql = "SELECT * FROM friends where two='$id' and one='$me'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) >0) {
      return true;
  }
  else{
    return false;
  }
}


?>