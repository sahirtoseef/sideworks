(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Post Form Simple
  
  $('.postform').each(function(){
	var form = $(this);
	
	
		$(this).submit(function(e){
			var resp = form.find('.response');
			var reload = form.attr("reload")=='true' ? true : false;
			var redirect = form.attr("redirect")==undefined ? false : form.attr("redirect");
			var lang = form.attr("lang")==undefined || form.attr("lang").length > 3 ? false : form.attr("lang");
			e.preventDefault();
			resp.html($('<div>').addClass('alert alert-info').html('Please wait...'));
			$.post(gapi,form.serialize(),function(data){
				console.log(data);
				try{
					var json = $.parseJSON(data);
				}catch(err){
					console.log(err);
					var json = {"ERR":"UNKNOWN_RESPONSE"};
				}
					$.each(json, function(i,v){
						var rclass;
						if(i === 'token'){
							$('#token').val(v);
						}else{
								switch(i){
									case 'ERR':
										rclass='danger';
										reload = false;
									break;
									case 'WAR':
										rclass='warning';
										reload = false;
									break;
									case 'RES':
										rclass='success';
									break;
									case 'INF':
										rclass='info';
										reload = false;
									break;
									case 'RED':
										window.location.href = decodeURIComponent(v);
										return;
									break;
									case 'REL':
										rclass='success';
										reload = true;
									break;
									default:
										rclass='default';
										reload = false;
									break;
								}
						}
						//console.log(typeof v);
						if(lang && typeof v == 'string'){
							$.post(gapi,{action:"request::translate", val: v, token: $('.token').val()},function(result){
								console.log(result);
								resp.html($('<div>').addClass('alert alert-' + rclass).html(result));
								
							});
						}else{
							resp.html($('<div>').addClass('alert alert-' + rclass).html(v));
						}
						
						if(redirect){
							if(i!=='ERR'){
							setTimeout(function(){
								
									window.location.href = redirect;
								
							}, 1000);
							}
						}
						if(reload){
							if(i!=='ERR'){
								form.find('button[type=submit]').attr('disabled','disabled');
								
								var sm = $('<small>');
								resp.find('.alert').append("<br />").append(sm);
								window.location.reload();
							}
						}
					});
				
				
			});
		});
	});
	
  //	Post Form Multipart

	$('.multipart').each(function(){
		var myform = $(this);
		var pbar = $('.progress-bar');
		var percent = $('.progress-percent');
		var pstatus = $('.progress-status');
		
		$(this).submit(function(e){
			var resp = myform.find('.response');
			var reload = myform.attr("reload")=='true' ? true : false;
			var redirect = myform.attr("redirect")==undefined ? false : myform.attr("redirect");
			var lang = myform.attr("lang")==undefined || myform.attr("lang").length > 3 ? false : myform.attr("lang");
			e.preventDefault();
			resp.html($('<div>').addClass('alert alert-info').html('Please wait...'));
			var frm = new FormData();
			
			
			myform.find('input[type="file"]').each(function(e){
				if(($(this)[0].files.length > 0)){
					var Name = $(this).attr('name');
					var file = $(this)[0].files[0];
					frm.append(Name,file);
				}
			});
			$(myform.serializeArray()).each(function(k,v){
				
				frm.append(v.name,v.value);
			});
			$.ajax({
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false,
				url: gapi,
				data: frm,
				beforeSend: function() {
					pstatus.empty();
					var percentVal = '0%';
					pbar.width(percentVal);
					percent.html(percentVal);
				},
				uploadProgress: function(event, position, total, percentComplete) {
					var percentVal = percentComplete + '%';
					pbar.width(percentVal);
					percent.html(percentVal);
				},
				success: function(result){
					console.log(result);
					try{
						var json = $.parseJSON(result);
					}catch(err){
						console.log(err);
						var json = {"ERR":"UNKNOWN_RESPONSE"};
					}
						$.each(json, function(i,v){
							var rclass;
							if(i === 'tkn'){
								localStorage.setItem("bkt", v);

								var bkt = localStorage.getItem('bkt');
							}else{
									switch(i){
										case 'ERR':
											rclass='danger';
											reload = false;
										break;
										case 'WAR':
											rclass='warning';
											reload = false;
										break;
										case 'RES':
											rclass='success';
										break;
										case 'INF':
											rclass='info';
											reload = false;
										break;
										case 'RED':
											window.location.href = decodeURIComponent(v);
											return;
										break;
										default:
											rclass='default';
											reload = false;
										break;
									}
							}
							//console.log(typeof v);
							if(lang && typeof v == 'string'){
								$.post(gapi,{action:"request::translate", val: v, token: $('.token').val()},function(res){
									console.log(res);
									resp.html($('<div>').addClass('alert alert-' + rclass).html(res));
									
								});
							}else{
								resp.html($('<div>').addClass('alert alert-' + rclass).html(v));
							}
							
							if(redirect){
								if(i!=='ERR'){
								setTimeout(function(){
									
										window.location.href = redirect;
									
								}, 3000);
								}
							}
							if(reload){
								if(i!=='ERR'){
									myform.find('button[type=submit]').attr('disabled','disabled');
									
									var sm = $('<small>');
									resp.find('.alert').append("<br />").append(sm);
									window.location.reload();
								}
							}
						});
					
					
				}
			}); // ajax ends
		}); // submit ends
	});

	$('.xlink').each(function(){
	
		$(this).on('click',function(e){
			var the = $(this);
			var req = the.attr('xlink');
			
			var p = the.attr('params') !== undefined ? {"action": req, "params": the.attr('params'), "xtoken": $('.token').val()} : { "action": req, "xtoken": $('.token').val() };
			var cnf =  $(this).attr('confirm') === undefined ? true : confirm($(this).attr('confirm'));
			console.log(p);
			if(cnf){
				$.post(gapi, p, function(res){
					try{
						var data = $.parseJSON(res);
						$('.token').val(data.token);
						//the.attr('token') = data.token !== undefined ? data.token : '';
						if(data.msg !== undefined){
							alert(data.msg);
							if(the.attr('reload')=="true"){
									window.location.reload();
							}
						}
						console.log(data);
					}catch(err){
						console.log(err);
					}
				});
			}
		});
	});
	
  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

})(jQuery); // End of use strict
