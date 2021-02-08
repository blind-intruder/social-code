
$(document).ready(function(){

  //TODO: remove /fyp from host
  var host="http://"+location.hostname+"/fyp";
  function disable_submit(con){
    $('#sup').prop('disabled', con);
  }

  disable_submit(true);
  var uidflag=0;
  var firstnameflag=0;
  var lastnameflag=0;
  var emailflag=0;
  var passflag=0;
  var dateflag=0;
  var monthflag=0;
  var yearflag=0;


  //enable submit button if all fields are entered and correct
    function submit_check(){
      if (uidflag==1 && emailflag==1 && firstnameflag==1 && lastnameflag==1 && passflag==1 && dateflag==1 && monthflag==1 && yearflag==1) {
        disable_submit(false);
      }
    }
    setInterval(submit_check, 1000);
  //enable submit button if all fields are entered and correct
 //hide login form
  $("#dosignup").click(function(){
    $("#signup_form").show();
    $("#login_form").hide();
  });
  //hide login form

  //hide sign up form
  $("#dologin").click(function(){
    $("#signup_form").hide();
    $("#login_form").show();
  });
  //hide sign up form

  //get login after signup
  $("#getlogin").click(function(){
    $("#signup_form").hide();
    $("#login_form").show();
  });
  //get login after signup

  //check first name from sign up form
  $("#unamefirst").on('input paste',function(){
    var firstname = $("#unamefirst").val();
    var fnameregex=/^[A-Z ]+$/i;
    $('#unamefirst').popover({ trigger:"hover click focus", placement:"top" ,title: 'Invalid First Name', content: "Only Alphabets are allowed(a-z A-Z)" });
    if (!firstname.match(fnameregex) && firstname!="") {
      $('#unamelast').popover('toggle');
      $('#unamefirst').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      firstnameflag=0;
    }
    else{
      $('#unamefirst').popover('dispose');
      $('#unamefirst').css('box-shadow', '0 0 0 0.1rem green');
      firstnameflag=1;
    }
  });
  //check first name from sign up form

  //check last name from sign up form
  $("#unamelast").on('input paste',function(){
    var lastname = $("#unamelast").val();
    var lnameregex=/^[A-Z ]+$/i;
    $('#unamelast').popover({ trigger:"hover click focus", placement:"top" , title: 'Invalid Last Name', content: "Only Alphabets are allowed(a-z A-Z)" });
    if (!lastname.match(lnameregex) && lastname!="") {
      $('#unamelast').popover('toggle');
      $('#unamelast').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      lastnameflag=0;
    }
    else{
      $('#unamelast').popover('dispose');
      $('#unamelast').css('box-shadow', '0 0 0 0.1rem green');
      lastnameflag=1;
    }
  });
  //check last name from sign up form

  //match password from sign up form
  $("#upwd2").on('input paste',function(){
    var pass1 = $("#upwd").val();
    var pass2 = $("#upwd2").val();
    $('#upwd2').popover({ trigger:"hover click focus", placement:"top" , title: "Password Error", content: "Password not matched" });
    if (pass1!=pass2) {
      $('#upwd2').popover('toggle');
      $('#upwd2').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
       passflag=0;
    }
    else{
      $('#upwd2').popover('dispose');
      $('#upwd2').css('box-shadow', '0 0 0 0.1rem green');
      $('#upwd').css('box-shadow', '0 0 0 0.1rem green');
      passflag=1;
    }
  });
  //match password from sign up form

  //check if email already exists or not from sign up form
  $("#uemail").change(function(){
    var email=$("#uemail").val();
    $('#uemail').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Invalid Email" });
    var emailregex =   
/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i;
    if (email.match(emailregex)) {
      $('#uemail').popover('dispose');
      $('#uemail').css('box-shadow', 'none');
      ldata={'email': email};
      var sdata =JSON.stringify(ldata);
      $.post(host+"/api/registration/checkemail/",
      {
        data:btoa(sdata)
      },
      function(data, status){
        var response = JSON.parse(JSON.stringify(data));
        if (response.ispresent=="error") {
          $('#uemail').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: response.msg });
          $('#uemail').popover('toggle');
          $('#uemail').css('box-shadow', '0 0 0 0.2rem red');
          disable_submit(true);
          emailflag=0;
        }
        if (response.ispresent=="true") {
          $('#uemail').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: response.msg });
          $('#uemail').popover('toggle');
          $('#uemail').css('box-shadow', '0 0 0 0.2rem red');
          disable_submit(true);
          emailflag=0;
        }
        if (response.ispresent=="false") {
          $('#uemail').css('box-shadow', '0 0 0 0.1rem green');
          $('#uemail').popover('dispose');
          emailflag=1;
        }
      });
    }
    else{
      $('#uemail').popover('toggle');
      $('#uemail').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      emailflag=0;
    }
  });

  $("#uemail").on('input paste',function(){
    var email = $("#uemail").val();
    var emailregex =   
/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i;
    $('#uemail').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Invalid email" });
    if (!email.match(emailregex)) {
      $('#uemail').popover('toggle');
      $('#uemail').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
       passflag=0;
    }
    else{
      $('#uemail').popover('dispose');
      $('#uemail').css('box-shadow', '0 0 0 0.1rem green');
      passflag=1;
    }
  });
  //check if email already exists or not from sign up form

  //check if username already exists or not from sign up form
  $("#uid").change(function(){
    var username=$("#uid").val();
    $('#uid').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Invalid Username (only a-z A-Z 0-9)" });
    var unameregex =/^[A-Z0-9]+$/i;
    if (username.match(unameregex)) {
      $('#uid').popover('dispose');
      $('#uid').css('box-shadow', 'none');
      ldata={'username': username};
      var sdata =JSON.stringify(ldata);
      $.post(host+"/api/registration/checkusername/",
      {
        data:btoa(sdata)
      },
      function(data, status){
        var response = JSON.parse(JSON.stringify(data));
        if (response.ispresent=="error") {
          $('#uid').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: response.msg });
          $('#uid').popover('toggle');
          $('#uid').css('box-shadow', '0 0 0 0.2rem red');
          disable_submit(true);
          uidflag=0;
        }
        if (response.ispresent=="true") {
          $('#uid').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: response.msg });
          $('#uid').popover('toggle');
          $('#uid').css('box-shadow', '0 0 0 0.2rem red');
          disable_submit(true);
          uidflag=0;
        }
        if (response.ispresent=="false") {
          $('#uid').css('box-shadow', '0 0 0 0.1rem green');
          $('#uid').popover('dispose');
          uidflag=1;
        }
      });
    }
    else{
      $('#uid').popover('toggle');
      $('#uid').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      uidflag=0;
    } 
  });
  $("#uid").on('input paste',function(){
    var uid = $("#uid").val();
    $('#uid').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Invalid Username (only a-z A-Z 0-9)" });
    var regex=/^[A-Z0-9]+$/i;
    if (!uid.match(regex)) {
      $('#uid').popover('toggle');
      $('#uid').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      uidflag=0;
    }
    else{
      $('#uid').popover('dispose');
      $('#uid').css('box-shadow', '0 0 0 0.1rem green');
      uidflag=1;
    }
  });
  //check if username already exists or not from sign up form

  //check date is set or not
  $("#date").change(function(){
    var date = $("#date").val();
    $('#date').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Date is invalid" });
    var regex=/^[0-9]+$/i;
    if (!date.match(regex)) {
      $('#date').popover('toggle');
      $('#date').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      dateflag=0;
    }
    else if(parseInt(date)<1 || parseInt(date)>31){
      $('#date').popover('toggle');
      $('#date').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      dateflag=0;
    }
    else{
      $('#date').popover('dispose');
      $('#date').css('box-shadow', '0 0 0 0.1rem green');
      dateflag=1;
    }
  });
  //check date is set or not

  //check year is set or not
  $("#year").change(function(){
    var year = $("#year").val();
    $('#year').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Year is invalid" });
    var regex=/^[0-9]+$/i;
    if (!year.match(regex)) {
      $('#year').popover('toggle');
      $('#year').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      yearflag=0;
    }
    else if(parseInt(year)<1905 || parseInt(year)>2010){
      $('#year').popover('toggle');
      $('#year').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      yearflag=0;
    }
    else{
      $('#year').popover('dispose');
      $('#year').css('box-shadow', '0 0 0 0.1rem green');
      yearflag=1;
    }
  });
  //check year is set or not

  //check month is set or not
  $("#month").change(function(){
    var month = $("#month").val();
    $('#month').popover({ trigger:"hover click focus", placement:"top" , title: "Error", content: "Month is invalid" });
    var regex=/^[a-z]+$/i;
    if (!month.match(regex)) {
      $('#month').popover('toggle');
      $('#month').css('box-shadow', '0 0 0 0.2rem red');
      disable_submit(true);
      monthflag=0;
    }
    else{
      $('#month').popover('dispose');
      $('#month').css('box-shadow', '0 0 0 0.1rem green');
      monthflag=1;
    }
  });
  //check month is set or not

  //submit response
  $("#sup").click(function(){
    var username=$("#uid").val();
    var email=$("#uemail").val();
    var password=$("#upwd").val();
    var firstname=$("#unamefirst").val();
    var lastname=$("#unamelast").val();
    var date=$("#date").val();
    var month=$("#month").val();
    var year=$("#year").val();
      ldata={'email': email,'password':password,'uid':username,'firstname':firstname,'lastname':lastname,'date':date,'month':month,'year':year};
      var sdata =JSON.stringify(ldata);
      $.post(host+"/api/registration/signup/",
      {
        data:btoa(sdata)
      },
      function(data, status){
        var response = JSON.parse(JSON.stringify(data));
        if (response.isregister=="error") {
          $('.signup-msg-title').html("Error");
          $('#signup-res').modal('toggle');
          $('#getlogin').hide();
          $('#errorsignup').show();
          $('#signup-msg').html(response.msg);

           //hide sign up form
            $("#dologin").click(function(){
            $("#signup_form").hide();
            $("#login_form").show();
            });
            //hide sign up form

        }
        if (response.isregister=="true") {
          $('.signup-msg-title').html("Congrats!");
          $('#signup-res').modal('toggle');
          $('#getlogin').show();
          $('#errorsignup').hide();
          $('#signup-msg').html("Login to continue");
          clearvalues();
        }
      });
  });
  //submit response

  //clear values of sign up form
  function clearvalues(){
    $("#uid").val('');
    $("#uemail").val('');
    $("#upwd").val('');
    $("#upwd2").val('');
    $("#unamefirst").val('');
    $("#unamelast").val('');
    $("#date").val('Day');
    $("#month").val('Month');
    $("#year").val('Year');

    $('#year').css('box-shadow', 'none');
    $('#date').css('box-shadow', 'none');
    $('#month').css('box-shadow', 'none');
    $('#uid').css('box-shadow', 'none');
    $('#uemail').css('box-shadow', 'none');
    $('#unamefirst').css('box-shadow', 'none');
    $('#unamelast').css('box-shadow', 'none');
    $('#upwd').css('box-shadow', 'none');
    $('#upwd2').css('box-shadow', 'none');

    disable_submit(false);
  }
  //clear values of sign up form

  //login check
  $("#login-sub").click(function(){
    var email=$("#lemail").val();
    var pass=$("#lpwd").val();
    ldata={'email': email,'password':pass };
      var sdata =JSON.stringify(ldata);
      $.post(host+"/",
      {
        data:btoa(sdata)
      },function(data, status){
        var response = JSON.parse(JSON.stringify(data));
        if (response.islogin=="false") {
          $("#response").html("<span style='color:red;'>Login unsuccessful:\n"+response.msg+"</span>");
        }
        else if (response.islogin=="true") { 
          $("#response").html("<span style='color:green;'>Login successful:\n"+response.msg+"</span>");
          $("#lemail").val('');
          $("#lpwd").val('');
          location.reload(true);
        }
        else{
          $("#response").html("<span style='color:red;'>Login unsuccessful:\n"+response.msg+"</span>");
        }
      });
  });
  //login check

   //logout
  $("#logout_sub").click(function(){
      ldata={'logout':'true', 'agla_lagao':'true'};
      var sdata =JSON.stringify(ldata);
      $.post(host+"/",
      {
        logout:btoa(sdata)
      },function(data, status){
        var response = JSON.parse(JSON.stringify(data));
        if (response.islogin=="false") {
          location.reload(true);
        }
        else{
          alert("something went wrong");
        }
      });
  });
  //logout

  //get forgot form//
    $("#forgot-sub").click(function(){
      var email=$("#useremail").val();
      ldata={'email': email};
      var sdata =JSON.stringify(ldata);
      $('#forgot-sub').html("<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>Wait");
      $('#forgot-sub').prop('disabled', true);
      $.post(host+"/api/forgot/",
      {
        data:btoa(sdata)
      },function(data, status){
        var response = JSON.parse(JSON.stringify(data));
        $("#forgot-response").html(response.msg);
          $('#forgot-sub').html("Submit");
          $('#forgot-sub').prop('disabled', false);
          if (response.issent=="true") {
            $("#useremail").val('');
          }
      });
    });
  //get forgot form//

  //hide forgot form
  $("#getforgot").click(function(){
    $("#forgot_form").show();
    $("#login_form").hide();
  });
  //hide forgot form

  //hide forgot form
  $("#hideforgot").click(function(){
    $("#forgot_form").hide();
    $("#login_form").show();
  });
  //hide forgot form

});
