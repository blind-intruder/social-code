var host="http://"+location.hostname+"/";

var dpchange = (function() {

	function output(node) {
		var existing = $('#result .croppie-result');
		if (existing.length > 0) {
			existing[0].parentNode.replaceChild(node, existing[0]);
		}
		else {
			$('#result')[0].appendChild(node);
		}
	}

	function popupResult(result) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
			html = '<img src="' + result.src + '" />';
		}
		swal({
			title: '',
			html: true,
			text: html,
			allowOutsideClick: true
		});
		setTimeout(function(){
			$('.sweet-alert').css('margin', function() {
				var top = -1 * ($(this).height() / 2),
					left = -1 * ($(this).width() / 2);

				return top + 'px 0 0 ' + left + 'px';
			});
		}, 1);
	}


	function demoUpload() {
		var $uploadCrop;
		var dplink = $('#dp').attr( 'src' );

		function readFile(input) {
 			if (input.files && input.files[0]) { 
	            var reader = new FileReader();
	            $('#dpsave').attr('disabled',false);
	            reader.onload = function (e) {
	            	r = e.target.result;
        			simg = r.split(';');
        			type = simg[0];
            		if (type == 'data:image/jpeg' || type == 'data:image/png') {
            			$('.upload-demo').addClass('ready');
	            			$uploadCrop.croppie('bind', {
	            				url: e.target.result
	            			}).then(function(){
	            		});
            		}
            		else{
            			 alert("Sorry - Only Png & Jpg files are allowed");
            		}
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
		        //sswal("Sorry - you're browser doesn't support the FileReader API");
		    }
		}
		var dplink = $('#dp').attr( 'src' );
		var $uploadCrop = $('#upload-demo').croppie({ 
			viewport: {
				width: 250,
				height: 250,
				type: 'square'
			}
		});

		$('#uploaddp').on('change', function () { readFile(this); });
		$('.upload-result').on('click', function (ev) {
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'original',
				format :'png',
				backgroundColor:'white'
			}).then(function (resp) {
				ldata={'image': resp};
      					var sdata =JSON.stringify(ldata);
      					$('#dpuloadmodalbody').html("<div style='width:100%; text-align:center;'><span class='spinner-border spinner-border-sm loadingdp' role='status' aria-hidden='true'></span></div>");
      					$.post(host+"fyp/api/pgupload/displaypic/",
      						{
        						data:btoa(sdata)
      						},
      						function(data, status){
      							var response = JSON.parse(JSON.stringify(data));
      							$("#dp").attr("src", response.link);
      							$('#dpuloadmodalbody').html("<div class='container'><div class='row'><div class='col-sm-12' style='text-align: center;'><div class='upload-demo-wrap'><div id='upload-demo'></div></div></div></div><!--upload row--><div class='row' style='padding-top: 50px;'><div class='col-sm-12' style='text-align: center;'><div class='actions'><a class='btn file-btn'><label for='uploaddp' class='btn btn-success'>Select Image</label><input type='file' id='uploaddp' value='Choose a file' style='display: none;' accept='image/*' /></a><br /><br /><button class='upload-result btn btn-success' id='dpsave'>Save</button></div></div></div><!--upload row--></div>");
      							$('#dpupload-iframe').modal('toggle');
      						}
      					);
			});
		});
	}

	function bindNavigation () {
		var $body = $('body');
		$('nav a').on('click', function (ev) {
			var lnk = $(ev.currentTarget),
				href = lnk.attr('href'),
				targetTop = $('a[name=' + href.substring(1) + ']').offset().top;

			$body.animate({ scrollTop: targetTop });
			ev.preventDefault();
		});
	}

	function init() {
		$('#dpsave').attr('disabled',true);
		bindNavigation();
		demoUpload();
	}

	return {
		init: init
	};
})();


// Full version of `log` that:
//  * Prevents errors on console methods when no console present.
//  * Exposes a global 'log' function that preserves line numbering and formatting.
(function () {
  var method;
  var noop = function () { };
  var methods = [
      'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
      'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
      'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
      'timeStamp', 'trace', 'warn'
  ];
  var length = methods.length;
  var console = (window.console = window.console || {});
 
  while (length--) {
    method = methods[length];
 
    // Only stub undefined methods.
    if (!console[method]) {
        console[method] = noop;
    }
  }
 
 
  if (Function.prototype.bind) {
    window.log = Function.prototype.bind.call(console.log, console);
  }
  else {
    window.log = function() { 
      Function.prototype.apply.call(console.log, console, arguments);
    };
  }
})();
