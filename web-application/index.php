<?php
error_reporting(0); // Disable all errors.
require("api/login/index.php"); 
require("api/inc/index.php");
require("api/logout/index.php");

// set intro cookie
if (isset($_GET["intro"])) {
  setcookie("intro" ,"false", mktime (0, 0, 0, 12, 31, 2021));
}
// set intro cookie

//show intro
if (!isset($_COOKIE["intro"]) && !isset($_GET["intro"])) {
  require("intro.php");
  die();
}
//show intro

//user is demanding intro
if(isset($_GET["intro"])){
  if ($_GET["intro"]=="true") {
    require("intro.php");
    die();
  }
}
//user is demanding intro

//If user is logged in
if(is_logged_in()!=0){
 check_login();//update user session
 require("api/inc/feed.php");//show newsfeed instead of login/signup
 require("api/logout/index.php"); //include api for logout
 die();
}
//else show the below page
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--bootstrap assets-->
      <link rel="stylesheet" href="assets/css/boot.css">
      <script type="text/javascript" src="assets/js/jquery-modified.min.js"></script>
    <!--bootstrap assets-->
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
    <link rel="stylesheet" type="text/css" href="assets/css/global.css">
    </head>
    <body>

      <div class="container">

        <div class="row h-100">
         <div class="col-sm-12">

          <!--------------------forgot password row-------------------->
          <div class="row" id="forgot_form" style="display: none;">
              <div class="col-sm-12"  >
                <div class="d-flex justify-content-center"  >
                    <div class="card" style="margin-top: 5%; width: 20em;">
                      <div class="singin-container">
                        <!--logo in login form--> 
                    <div class="row">
                      <div class="col-sm-12" style="padding-bottom: 30px;padding-top: 30px; text-align: center;">
                        <img src="assets/logo/new/logo_small.png" style="width: 6em;">
                      </div>
                    </div>
                    <!--logo in login form-->
                    <hr>
                        <form >
                            <div class="form-group">
                              <label for="useremail">Enter your email</label>
                              <input type="email" class="form-control" placeholder="Email" id="useremail">
                            </div>
                            <div style="text-align: center;">
                              <button type="button" class="btn btn-success" id="forgot-sub">Submit</button>
                              <p id="forgot-response"></p>
                              <hr>
                              <button type="button" class="simple_button" id="hideforgot">Login</button>
                            </div>
                  
                        </form>
                      </div>
                  </div>
                </div>
              </div>
          </div>


         	<!--------------------login form row-------------------->
        	<div class="row" id="login_form">
          		<div class="col-sm-12"  >
            		<div class="d-flex justify-content-center"  >
              			<div class="card" style="margin-top: 5%; width: 20em;">
              				<div class="singin-container">
              					<!--logo in login form--> 
         						<div class="row">
         							<div class="col-sm-12" style="padding-bottom: 30px;padding-top: 30px; text-align: center;">
         								<img src="assets/logo/new/logo_small.png" style="width: 6em;">
         							</div>
         						</div>
         						<!--logo in login form-->
         						<hr>
                				<form>
                  					<div class="form-group">
                    					<input type="email" class="form-control" placeholder="Email" id="lemail">
                   					</div>
                  					<div class="form-group">
                    					<input type="password" class="form-control" placeholder="Password" id="lpwd">
                  					</div>
                  					<div style="text-align: center;">
                  						<button type="button" class="btn btn-success" id="login-sub">Login</button>
                  						<p id="response"></p>
                  						<hr>
                  						<button type="button" class="simple_button" id="getforgot">Forgot Password?</button>
                   						<p>Don't have account? <button type="button" id="dosignup" class="simple_button">Sign Up</button></p>
                  					</div>
                				</form>
              				</div>
            			</div>
            		</div>
          		</div>
        	</div>
        	<!--------------------login form row-------------------->
      		<!--------------------Sign up form row-------------------->
       		<div class="row" id="signup_form" style="display: none;">     
          		<div class="col-sm-12" >
            		<div class="d-flex justify-content-center"  >
             			<div class="card" style="margin-top: 3%;">  
            				<div class="singup-container">
                				<div class="logo" style="text-align: center;"><img src="assets/logo/new/logo_small.png" style="width: 6em;"></div>
                				<hr>
                					<form> 
                						<div class="form-row">
    										<div class="form-group col-md-6">
      											<input type="email" placeholder="Email" class="form-control" id="uemail">
    										</div>
    										<div class="form-group col-md-6">
      											<input type="text" placeholder="User name" class="form-control" id="uid">
    										</div>
  										</div>
  										<div class="form-row">
    										<div class="form-group col-md-6">
      											<input type="password" placeholder="Password" class="form-control" id="upwd">
    										</div>
    										<div class="form-group col-md-6">
      											<input type="password" placeholder="Re-Enter Password" class="form-control" id="upwd2">
    										</div>
  										</div>
  										<div class="form-row">
    										<div class="form-group col-md-6">
      											<input type="text" placeholder="First name" class="form-control" id="unamefirst">
    										</div>
    										<div class="form-group col-md-6">
      											<input type="text" placeholder="Last name" class="form-control" id="unamelast">
    										</div>
  										</div>

  										<div class="form-row">
    										<div class="form-group col-md-4">
      											<select class='custom-select custom-select-lg mb-3' id='date' name='birthday-date'>
													<option selected="1">Day</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option>
													<option value='4'>4</option><option value='5'>5</option><option value='6'>6</option>
													<option value='7'>7</option><option value='8'>8</option><option value='9'>9</option>	<option value='10'>10</option><option value='11'>11</option><option value='12'>12</option><option value='13'>13</option><option value='14'>14</option><option value='15'>15</option><option value='16'>16</option><option value='17'>17</option><option value='18'>18</option><option value='19'>19</option><option value='20'>20</option><option value='21'>21</option><option value='22'>22</option><option value='23'>23</option><option value='24'>24</option><option value='25'>25</option><option value='26'>26</option><option value='27'>27</option><option value='28'>28</option><option value='29'>29</option><option value='30'>30</option><option value='31'>31</option>
												</select>
    										</div>
    										<div class="form-group col-md-4">
      											<select class='custom-select custom-select-lg mb-3' id='month' name='birthday-month'>
													<option selected="1">Month</option><option value='Jan'>Jan</option><option value='Feb'>Feb</option><option value='Mar'>Mar</option><option value='Apr'>Apr</option><option value='May'>May</option><option value='Jun'>Jun</option><option value='Jul'>Jul</option><option value='Aug'>Aug</option><option value='Sep'>Sep</option><option value='Oct'>Oct</option><option value='Nov'>Nov</option><option value='Dec'>Dec</option>
												</select>
    										</div>
    										<div class="form-group col-md-4">
      											<select name='signup-birthday-year' id='year' class='custom-select custom-select-lg mb-3'><option selected="1">Year</option><option value='2010'>2010</option><option value='2009'>2009</option><option value='2008'>2008</option><option value='2007'>2007</option><option value='2006'>2006</option><option value='2005'>2005</option><option value='2004'>2004</option><option value='2003'>2003</option><option value='2002'>2002</option><option value='2001'>2001</option><option value='2000'>2000</option><option value='1999'>1999</option><option value='1998'>1998</option><option value='1997'>1997</option><option value='1996'>1996</option><option value='1995'>1995</option><option value='1994'>1994</option><option value='1993'>1993</option><option value='1992'>1992</option><option value='1991'>1991</option><option value='1990'>1990</option><option value='1989'>1989</option><option value='1988'>1988</option><option value='1987'>1987</option><option value='1986'>1986</option><option value='1985'>1985</option><option value='1984'>1984</option><option value='1983'>1983</option><option value='1982'>1982</option><option value='1981'>1981</option><option value='1980'>1980</option><option value='1979'>1979</option><option value='1978'>1978</option><option value='1977'>1977</option><option value='1976'>1976</option><option value='1975'>1975</option><option value='1974'>1974</option><option value='1973'>1973</option><option value='1972'>1972</option><option value='1971'>1971</option><option value='1970'>1970</option><option value='1969'>1969</option><option value='1968'>1968</option><option value='1967'>1967</option><option value='1966'>1966</option><option value='1965'>1965</option><option value='1964'>1964</option><option value='1963'>1963</option><option value='1962'>1962</option><option value='1961'>1961</option><option value='1960'>1960</option><option value='1959'>1959</option><option value='1958'>1958</option><option value='1957'>1957</option><option value='1956'>1956</option><option value='1955'>1955</option><option value='1954'>1954</option><option value='1953'>1953</option><option value='1952'>1952</option><option value='1951'>1951</option><option value='1950'>1950</option><option value='1949'>1949</option><option value='1948'>1948</option><option value='1947'>1947</option><option value='1946'>1946</option><option value='1945'>1945</option><option value='1944'>1944</option><option value='1943'>1943</option><option value='1942'>1942</option><option value='1941'>1941</option><option value='1940'>1940</option><option value='1939'>1939</option><option value='1938'>1938</option><option value='1937'>1937</option><option value='1936'>1936</option><option value='1935'>1935</option><option value='1934'>1934</option><option value='1933'>1933</option><option value='1932'>1932</option><option value='1931'>1931</option><option value='1930'>1930</option><option value='1929'>1929</option><option value='1928'>1928</option><option value='1927'>1927</option><option value='1926'>1926</option><option value='1925'>1925</option><option value='1924'>1924</option><option value='1923'>1923</option><option value='1922'>1922</option><option value='1921'>1921</option><option value='1920'>1920</option><option value='1919'>1919</option><option value='1918'>1918</option><option value='1917'>1917</option><option value='1916'>1916</option><option value='1915'>1915</option><option value='1914'>1914</option><option value='1913'>1913</option><option value='1912'>1912</option><option value='1911'>1911</option><option value='1910'>1910</option><option value='1909'>1909</option><option value='1908'>1908</option><option value='1907'>1907</option><option value='1906'>1906</option><option value='1905'>1905</option></select>
    										</div>
  										</div>
                  							<button type="button" class="btn btn-success btn-block" id="sup">Sign Up</button>
                					</form>
                					<div style="text-align: center;">
                						</br>
                						<p>Already have an account?<button type="button" class="simple_button" id="dologin">Log In</button></p>
                					</div>
            					</div>
        					</div>
    					</div>         
					</div>   
       			</div>  
      		</div>
    	</div>
    	<!--------------------Sign up form row-------------------->

    	<!--modal to confirm successful sign up-->
    	<!-- Modal -->
<div class="modal fade" id="signup-res" tabindex="-1" role="dialog" aria-labelledby="signup-res" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModal" class="signup-msg-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="signup-msg">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="getlogin">Login</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="errorsignup">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->




	</div>
    </div>
     <!--bootstrap assets-->
        <script type="text/javascript" src="assets/js/popper.js" ></script>
        <script type="text/javascript" src="assets/js/boot.js"></script>
        <!--bootstrap assets--> 

    <script type="text/javascript" src="assets/js/index.js">
    </script>       

    </body>
    </html>    
