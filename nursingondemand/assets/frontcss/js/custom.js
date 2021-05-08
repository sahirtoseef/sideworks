// JavaScript Document

$(document).ready(function() {

//home page slider js
      $("#owl-demo").owlCarousel({

      navigation : true,
      slideSpeed : 300,
      paginationSpeed : 400,
      singleItem : true,
	  autoPlay:true,
	  pagination: false,
	  navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"]
      });
//home page slider js ends

//mega menu js
jQuery("#menuzord").menuzord({
                align: "left"
            });
            
            $(".close-cart i").click(function(){
                $(".cart-media").hide();
            });
            
            $(".cart-toggle").click(function(){
                $(".cart-ul").toggle();
            });
//mega menu js

//offer slider js
var owl = $("#offer-slide");

      owl.owlCarousel({
		pagination: false,
		navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        itemsCustom : [
          [0, 2],
          [450, 3],
          [600, 3],
          [700, 3],
          [1000, 4],
          [1200, 4],
          [1400, 4],
          [1600, 4]
        ],
        navigation : true
	  });
//offer slider js

//testimonial slider js
$("#testimonial").owlCarousel({
        autoPlay : 3000,
		stopOnHover : true,
		navigation:true,
		pagination: false,
		navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		paginationSpeed : 1000,
		goToFirstSpeed : 2000,
		singleItem : true,
		autoHeight : true,
		transitionStyle:"fade"
});
// testimonial slider js ends

//whats new slider js
var owl = $("#whats-new");

      owl.owlCarousel({

      items : 3, //10 items above 1000px browser width
      itemsDesktop : [1000,2], //5 items between 1000px and 901px
      itemsDesktopSmall : [900,2], // 3 items betweem 900px and 601px
      itemsTablet: [600,2], //2 items between 600 and 0;
      itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
      
	  autoPlay : 3000,
	  stopOnHover : true,
	  pagination: false,
	  navigation : true,
	  navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"]
	  
      });
	  
      // Custom Navigation Events
      $(".next").click(function(){
        owl.trigger('owl.next');
      })
      $(".prev").click(function(){
        owl.trigger('owl.prev');
      })
      $(".play").click(function(){
        owl.trigger('owl.play',1000);
      })
      $(".stop").click(function(){
        owl.trigger('owl.stop');
      })
//whats new slider js ends

//wow special slider js
var owl = $("#wow-sp");

      owl.owlCarousel({

      items : 1, //10 items above 1000px browser width
      itemsDesktop : [1000,1], //5 items between 1000px and 901px
      itemsDesktopSmall : [900,1], // 3 items betweem 900px and 601px
      itemsTablet: [600,1], //2 items between 600 and 0;
      itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
      
	  autoPlay : 3000,
	  stopOnHover : true,
	  pagination: false,
	  navigation : true,
	  navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"]

	  
      });
	
//wow special slider js ends

//sidebar collapse menu bar starts
//var mtree = $('ul.mtree');
//	  
//	  // Skin selector for demo
//	  mtree.wrap('<div class=mtree-demo></div>');
//	  var skins = ['bubba','skinny','transit','jet','nix'];
//	  mtree.addClass(skins[0]);
//	 
//	  $.each(skins, function(index, val) {
//		find('ul').append('<li><button class="small skin">' + val + '</button></li>');
//	  });
//	  find('ul').append('<li><button class="small csl active">Close Same Level</button></li>');
//	  find('button.skin').each(function(index){
//		$(this).on('click.mtree-skin-selector', function(){
//		  find('button.skin.active').removeClass('active');
//		  $(this).addClass('active');
//		  mtree.removeClass(skins.join(' ')).addClass(skins[index]);
//		});
//	  })
//	  find('button:first').addClass('active');
//	  find('.csl').on('click.mtree-close-same-level', function(){
//		$(this).toggleClass('active'); 
//	  });
//sidebar collapse menu bar ends

//increment decrement


//tab of description (product detail)
$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})
//tab of description (product detail)

//bootstrap slider
$("#ex2").slider({});
//bootstrap slider ends

});



//select box js
(function() {
	[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
		new SelectFx(el);
	} );
})();
//select box js

$(function() {
    $( "#accordion" ).accordion();
});


wow = new WOW(
      {
        animateClass: 'animated',
        offset:       100,
        callback:     function(box) {
          console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
        }
      }
    );
    wow.init();
    document.getElementById('moar').onclick = function() {
      var section = document.createElement('section');
      section.className = 'section--purple wow fadeInDown';
      this.parentNode.insertBefore(section, this);
};