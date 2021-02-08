

$(document).ready(function(){
    $(".k2-loader").fadeOut("slow");
       var host="http://"+location.hostname+"/fyp/";
  
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
                            $.post(host+"/api/pgupload/coverpic/",
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
  
        $("#coverupload").click(function () {
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
  
  });