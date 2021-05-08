<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>form</title>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" href="css/myDefault.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
	
<script type="text/javascript" src="js/timezones.js"></script>
<script type="text/javascript" src="js/timezones.full.js"></script>
</head>
<body class="" >
	<div class="container cart mt20 mb20">
		<div class="">
			<div class="heading text-center "><h3>event Name</h3></div>
    		<div class="panel panel-default">
	        	<div class="panel-body">
        			<div class="row">
        				<div class="col-xs-1">
        					<i class="fa fa-map-marker fs18 clg" aria-hidden="true"></i>
        				</div><!--col-xs-1-->
        				<div class="col-xs-10">
        					<div class="fs16 fw700">Vijay Nagar</div>
        					<div class="fs16 ">Indore, Madhya Pradesh, India</div>
        				</div><!--col-xs-10-->
        			</div><!--row-->

        			<div class="row mt20">
        				<div class="col-xs-1">
        					<i class="fa fa-bars fs18 clg" aria-hidden="true"></i>
        				</div><!--col-xs-1-->
        				<div class="col-xs-10">
        					<div class="fs16 " style="font-style: italic;">Cffhb</div>
        				</div><!--col-xs-10-->
        			</div><!--row-->

        			<div class="row mt20">
        				<div class="col-xs-1">
        					<i class="fa fa-clock-o fs18 clg" aria-hidden="true"></i>

        				</div><!--col-xs-1-->
        				<div class="col-xs-10">
        					<div class="fs16">All times displayed in <select class="form-control input-sm mt10" id="timezones"></select></div>
        				</div><!--col-xs-10-->
        			</div><!--row-->
					

				<div class="bs-example mt20">
					<div class="row ">
        				<div class="col-xs-8 col-sm-8 pr5">
        				<div class="borderBox h120"></div>
        				</div><!--col-xs-1-->
        				<div class="col-xs-4 col-sm-4  pl0">
        					<div class="fs16 borderBox pl10  h120" >
        						Aug <br> <span class="fw700 fs18">16</span> <br> WED
        						<div>
        							<span>4:30 PM - 4:45 PM </span>
        						</div>
        					</div>
        				</div><!--col-xs-10-->
        			</div><!--row-->

        			<div class="row  ">
        				<div class="col-xs-6 pr0">
        					<div class="fs16 borderBox pl10 pt5 pb5 h36"><span>1</span> participants</div>
        				</div><!--col-xs-1-->

						<div class="col-xs-2 pl0 pr5">
        					<div class="text-center  fs16 borderBox pt7 h36 ">
        					<span class="fw700 createRow">
							   <a href="#" > <i class="fa fa-plus fa18" aria-hidden="true"></i></a></span>
        					</div>
        				</div><!--col-xs-10-->

        				<div class="col-xs-4 pl0">
        					<div class="fs16 pt5 pb5  h36 text-center borderBox  ">
        						<span class="fw700 cr ">1</span>
        					</div>
        				</div><!--col-xs-10-->
        			</div><!--row-->

				<div class="table">
        			

        			<div class="row ">
        				<div class="col-xs-2 pr0 ">
        				<div class="text-center borderBox h36 pt4 circleImgBox"><img src="user_icon.png" class="img-circle" width="25px"></div>
        				<div class="text-center borderBox h36 pt7 deletBox"><a href="javascript:void(0)" onclick="deletBox();"><i class="fa fa-trash fs18" aria-hidden="true"></i></a></div>
        				</div><!--col-xs-2-->

        				<div class="col-xs-4 pl0 pr0 inputBox">
        					<div class="fs16 borderBox  h36" style="padding: 2.5px;">
        						<input type="text" class="form-control input-sm valInput" placeholder="Enter your name" disabled="disabled" value="Vijay">
        					</div>
        				</div><!--col-xs-1-->

        				<div class="col-xs-2 pl0 pr5">
        					<div class="text-center  fs16 borderBox pt7 h36 ">
        					<span class="fw700 cr editBox">
							   <a href="#" > <i class="fa fa-pencil fa18" aria-hidden="true"></i></a></span>
        					</div>
        				</div><!--col-xs-10-->

        				<div class="col-xs-4 pl0 ">
        					<div class="text-center  fs16 borderBox h36 checkBox">
        					<span class="fw700 cr">
							    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
        					</span>
        					</div>
        				</div><!--col-xs-10-->
        			</div><!--row-->

				</div><!--table-->
				</div><!--bs-example-->



				<div class="row ">
					<div class="col-xs-2 pr0 ">
        				<div class="text-center  h36 mt30 checkNum">
        					
        				</div>
        			</div><!--col-xs-2-->
    				<div class="col-xs-10  mt10">
    					<button id="uplo_button" class="btn btn-primary btn-sm btn-block h50 fw700">Done <div class="btnTextChange fw100">Enter your name first</div></button>
    				</div><!--col-xs-1-->
        		</div><!--row-->

	        	</div><!--panel-body-->
    		</div><!--panel-default-->
		</div>




    <div class="panel panel-default">

        <div class="panel-heading">Comments</div>

        <div class="panel-body">
	        <div class="row ">
		    	<div class="col-xs-12  ">
		    		<textarea class="form-control" placeholder="Add a comment" rows="1" id="comment"></textarea>
		    	</div><!--col-xs-12-->

		    	<div class="col-xs-12  mt10">
    				<button class="btn btn-success btn-sm btn-block">Send</button>
    			</div><!--col-xs-1-->


    		<div class="col-xs-12 mt10">

    		<ul class="list-group-item showCommentlist">
				<li class="list-group-item">
					<div class="row">
        				<div class="col-xs-1">
        					<i class="fa fa-comment fs18" style="color: #33A0CF;" aria-hidden="true"></i>
        				</div><!--col-xs-1-->
        				<div class="col-xs-10">
        					<div class="fs16 ">Show comments <span>(2)</span></div>
        				</div><!--col-xs-10-->
        			</div><!--row-->
				</li><!--list-group-item-->
			</ul>


		    <ul class="list-group-item Commentlist">
				<li class="list-group-item" >
					<div class="row">
						<div class="col-xs-2 pl5">
							<img src="user_icon.png" class="img-circle" width="40px">
						</div><!--col-xs-1-->
						<div class="col-xs-10">
							<div class="fs16 fw700">Vijay Nagar</div>
							<span class="fs14 clg">13 days ago</span>
							<div class="fs16 ">Hello</div>
						</div><!--col-xs-10-->
		    		</div><!--row-->
        		</li><!--list-group-item-->


        		<li class="list-group-item " >
					<div class="row">
						<div class="col-xs-2 pl5">
							<img src="user_icon.png" class="img-circle" width="40px">
						</div><!--col-xs-1-->
						<div class="col-xs-10">
							<div class="fs16 fw700">Vijay Nagar</div>
							<span class="fs14 clg">13 days ago</span>
							<div class="fs16 ">Hi</div>
						</div><!--col-xs-10-->
		    		</div><!--row-->
        		</li><!--list-group-item-->
			</ul>

		    </div><!--col-xs-12-->
	        </div><!--row-->
        </div>

    </div>

	</div><!--container-->
</body>
</html>
<style type="text/css">
	.editBox{ opacity: 0;     transition: all 0.5s ease 0s;}
	.deletBox{ display: none; }
	.row:hover .editBox{ opacity:1; }
</style>
<script type="text/javascript">
$(document).ready(function () {

// timezones
  $('#timezones').timezones();
// timezones  
$('.Commentlist').css('display', 'none');

$('.showCommentlist').click(function() {
	$(this).css('display', 'none');
	$('.Commentlist').css('display', 'block');
	
});

// editBox
$('.editBox').click(function (e) {
	 e.preventDefault();
	$(this).parents('.row').find('.circleImgBox').css('display','none');
	$(this).parents('.row').find('.deletBox').css('display','block');
	$(this).parents('.row').find('.inputBox').addClass('col-xs-6 pr5').removeClass('col-xs-4 pr0').find('input').removeAttr('disabled','');
	$(this).parents('.row').find('.checkBox').html('<span class="fw700 cr"><div class="checkbox mt6 mb0"><label style="font-size: 1em" class="pl0"><input type="checkbox"   class="checkBox"   onchange="checkbox();" ><span class="cr" id="checkbox"><i class="cr-icon fa fa-check"></i></span></label></div></span>');
	$(this).parents('.row').prev().remove();
	$(this).parents('.table').prev('.row').find('.col-xs-6').addClass('col-xs-8 pr5').removeClass('col-xs-6 pr0');
	$(this).parents('.table').prev('.row').find('.col-xs-2').remove();
	$(this).parents('.col-xs-2').hide();
});
// editBox

// createRow
$('.createRow').click(function (e) {
	 e.preventDefault();
	$(this).parents('.row').siblings('.table').prepend('<div class="row "><div class="col-xs-2 pr0 "><div class="text-center borderBox h36 pt4"><img src="user_icon.png" class="img-circle" width="25px"></div></div><div class="col-xs-6 pl0 pr5"><div class="fs16 borderBox h36" style="padding: 2.5px;"> <input type="text"  class="form-control input-sm valInput" placeholder="Enter your name"></div></div><div class="col-xs-4 pl0"><div class="text-center fs16 borderBox h36"> <span class="fw700 cr"><div class="checkbox mt6 mb0"> <label style="font-size: 1em" class="pl0"> <input type="checkbox" value="" class="checkBox" onchange="checkbox();"> <span class="cr"><i class="cr-icon fa fa-check"></i></span> </label></div> </span></div></div></div>');
	$(this).parents('.row').find('.col-xs-6').addClass('col-xs-8 pr5').removeClass('col-xs-6 pr0').find('input').removeAttr('disabled','');
	$(this).parents('.col-xs-2').hide();
});
// createRow

var checkbox = $('.checkbox input');
var checkNum = $('.checkNum');
var valInput = $('.valInput');

$('#uplo_button').attr('disabled','disabled');   



  // checkbox.change(function(){


  // });


   valInput.keyup(function(){

  	if(valInput.val()!=0){
    $("#uplo_button").removeAttr("disabled"); 
    }

  	if(valInput.val()==0){
    $("#uplo_button").attr("disabled","disabled"); 
  	}


  	if(valInput.val()!=0 && checkbox.prop('checked')==false){
    	$("#uplo_button").removeAttr("disabled"); 
    	$("#uplo_button").removeClass("btn-primary"); 
    	$("#uplo_button").addClass("btn-default");
    	$(".btnTextChange").text("Cannot attend");
  	}

  	if(valInput.val()!=0 && checkbox.is(":checked")){

    	$("#uplo_button").removeAttr("disabled"); 
    	$("#uplo_button").removeClass("btn-default"); 
    	$("#uplo_button").addClass("btn-primary");
    	$(".btnTextChange").text("");
  	}

  	 if(valInput.val()==0 && checkbox.is(":checked")){

    	$("#uplo_button").removeAttr("disabled"); 
    	$("#uplo_button").removeClass("btn-default"); 
    	$("#uplo_button").addClass("btn-primary");
    	$(".btnTextChange").text("Enter your name first");
  	}

  	if(valInput.val()==0 && checkbox.prop('checked')==false){

    	$("#uplo_button").removeAttr("disabled"); 
    	$("#uplo_button").removeClass("btn-default"); 
    	$("#uplo_button").addClass("btn-primary");
    	$(".btnTextChange").text("Enter your name first");
  	}


      
    });
});


 function checkbox()
 {
 
    if($('.checkbox input').is(":checked")){
    	
		$('.checkNum').html("<span><i class='cr-icon fa fa-check cr'></i>1</span>");
    }
    if($('.valInput').val()!=0 && $('.checkbox input').is(":checked")){

    	$("#uplo_button").removeAttr("disabled"); 
    	$("#uplo_button").removeClass("btn-default"); 
    	$("#uplo_button").addClass("btn-primary");
    	$(".btnTextChange").text("");

  	}
    else{
    	$("#uplo_button").attr("disabled","disabled");
    	$('.checkNum').html("");
        }


}

  function deletBox(){ 
  	$(this).parents('.row').prev().remove();
  	$('.table').html('<div class="row "><div class="col-xs-2 pr0 "><div class="text-center borderBox h36 pt4 circleImgBox"><img src="user_icon.png" class="img-circle" width="25px"></div><div class="text-center borderBox h36 pt7 deletBox"><a href="javascript:void(0)" onclick="deletBox();"><i class="fa fa-trash fs18" aria-hidden="true"></i></a></div></div><div class="col-xs-6 pl0 pr5 inputBox"><div class="fs16 borderBox h36" style="padding: 2.5px;"> <input class="form-control input-sm valInput" placeholder="Enter your name" value="" type="text"></div></div><div class="col-xs-4 pl0"><div class="text-center fs16 borderBox h36"> <span class="fw700 cr"><div class="checkbox mt6 mb0"> <label style="font-size: 1em" class="pl0"> <input value="" onchange="checkbox();"  class="checkBox" type="checkbox"> <span class="cr"><i class="cr-icon fa fa-check"></i></span> </label></div> </span></div></div></div></div>')
  }

</script>