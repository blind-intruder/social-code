$(document).ready(function(){
  var host="http://localhost:8079/fyp/";
$('#reset-sub').prop('disabled', true);

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

//match password 
  $("#passv2").on('input paste',function(){
    var pass1 = $("#passv1").val();
    var pass2 = $("#passv2").val();
    $('#passv2').popover({ trigger:"hover click focus", placement:"top" , title: "Password Error", content: "Password not matched" });
    if (pass1!=pass2) {
      $('#passv2').popover('toggle');
      $('#passv2').css('box-shadow', '0 0 0 0.2rem red');
  		$('#reset-sub').prop('disabled', true);
       passflag=0;
    }
    else{
      $('#passv2').popover('dispose');
      $('#passv2').css('box-shadow', '0 0 0 0.1rem green');
      $('#passv1').css('box-shadow', '0 0 0 0.1rem green');
      $('#reset-sub').prop('disabled', false);
      passflag=1;
    }
  });
  //match password

  //submit reset password
  $("#reset-sub").click(function(){
    var pass1 = $("#passv1").val();
    var pass2 = $("#passv2").val();
    var token=getUrlParameter('token');
    ldata={'password':pass1,'token':getUrlParameter('token')};
      var sdata =JSON.stringify(ldata);
      $.post(location.hostname,
      {
        data:btoa(sdata)
      },function(data, status){
        var response = JSON.parse(JSON.stringify(data));
          $("#forgot-response").html(response.msg);
      });
  });
  //submit reset password

});