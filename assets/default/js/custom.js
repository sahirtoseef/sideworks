(function($) {
  "use strict"; // Start of use strict
// Post Form Simple
  $(document).ready(function(){
  	$('.selector.required').each(function(){
  		var the = $(this);
  		the.find('.btn-next').hide();
  		the.find('input[type=checkbox], input[type=radio]').click(function(e){
  			var len = the.find('input[type=checkbox]:checked, input[type=radio]:checked').length;
  			if(len < 1){
  				the.find('.btn-next').hide();
  			}else{
  				the.find('.btn-next').show();
  			}
  			
  		})
  		
  	});
  	
	  });
 
	$('.postform').each(function(){
	var form = $(this);
	
	
		$(this).submit(function(e){
			var resp = form.find('.response');
			var reload = form.attr("reload")=='true' ? true : false;
			var redirect = form.attr("redirect")==undefined ? false : form.attr("redirect");
			var lang = form.attr("lang")==undefined || form.attr("lang").length > 3 ? false : form.attr("lang");
			e.preventDefault();
			resp.html($('<div>').addClass('alert alert-info').html('Please wait...'));
			var forceNoReload = false;
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
							$('.token').val(v).trigger('change');;
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
									case 'RES_NO_RELOAD':
										rclass='success';
										reload = false;
										forceNoReload = true;
									break;
									default:
										rclass='default';
										reload = false;
									break;
								}
						}
					
						var respc = form.attr('response') !== undefined ? $.parseJSON(form.attr('response')) : false;
						if(lang && typeof v == 'string'){
							$.post(gapi,{action:"request::translate", val: v, token: $('.token').val()},function(result){
								console.log(result);
								resp.html($('<div>').addClass('alert alert-' + rclass).html(result));
								
							});
						}else{
							if(respc[v] !== undefined){
								resp.html($('<div>').addClass('alert alert-' + rclass).html(respc[v]));
							}else{
								resp.html($('<div>').addClass('alert alert-' + rclass).html(v));
							}
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
						} else {
							if(i!=='ERR'){
								form.find('button[type=submit]').attr('disabled','disabled');
								var sm = $('<small>');
								resp.find('.alert').append("<br />").append(sm);

								$('.postform').trigger('reset');
								$('.postform').find('button[type=submit]').removeAttr('disabled');
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

	// Search for Are you Employee page
	$('.search-btn').on('click',function(e){
			console.log("Heyaaaa");
			var the = $(this);
			var req = 'user::employer::search';
			var p = {
				"action": req,
				"empname": $('input[name=empname]').val(), 
				"city_or_state": $('input[name=city_or_state]').val(), 
				"xtoken": $('#token').val()
			};
			$.post(gapi, p, function(res){
				try{
					var data = $.parseJSON(res);
					// $('.token').val(data.token);
					// the.attr('token') = data.token !== undefined ? data.token : '';
					if(data.RESULT !== undefined){
						var htmlList = "";
						for (var i in data.RESULT) {
							htmlList += "<option value='"+ data.RESULT[i]['empname'] +"'>"+ data.RESULT[i]['storeid'] +" - "+ data.RESULT[i]['estate'] +"</option>";
						}
					}
					$('datalist#employers').html(htmlList);
				}catch(err){
					console.log(err);
				}
			});
		});
	
	})(jQuery);