<!-- Testimonials Section -->

    <section id="conatcat-info">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="media contact-info wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                        <!--<div class="pull-left">
                            <i class="fa fa-phone"></i>
                        </div>-->
                        <div class="media-body">
                            <h2>Have a question or need a custom quote?</h2>
                            <?= form_open('webcontroller/add_contact',"class='subscribe'");  ?>
                              <div class="subscribe-hide">
                              
                                <input class="subscribe-email" type="email" id="subscribe-email" name="email" required>
                                <button  type="submit" id="subscribe-submit" class="btn">Subscribe Now</button>   

                              </div><!-- /.subscribe-hide -->                              
                            <?= form_close(); ?><!-- /.subscribe -->
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/.container-->    
    </section><!--/#conatcat-info-->

    <section id="bottom">
        <div class="container wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <h3>OUR SERVICES</h3>
                        <ul>
                            <li><?php echo anchor('Webcontroller/viewPage/webServices.php', 'Web Services') ?></li>
                                <li><?php echo anchor('Webcontroller/viewPage/mobilityS.php', 'Mobility Solution') ?></li>
                                <li><?php echo anchor('Webcontroller/viewPage/businessA.php', 'Business analysis') ?></li>
                                <li><?php echo anchor('Webcontroller/viewPage/responsiveS.php', 'Responsive Solution') ?></li>
                                <li><?php echo anchor('Webcontroller/viewPage/softwareT.php', 'Software Testing') ?></li>
                                <li><?php echo anchor('Webcontroller/viewPage/softwareD.php', 'Software Development') ?></li>
                        </ul>
                    </div>    
                </div><!--/.col-md-3-->

                <div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <h3>QUICK LINKS</h3>
                        <ul>

                            <li <?php if($page=="index.php"){echo "class='active'"; } ?>><a href="index.php">Home</a></li>
                            <li <?php if($page=="about.php"){echo "class='active'"; } ?>><?php echo anchor('Webcontroller/viewPage/about.php', 'About') ?></li>
                            <li <?php if($page=="services.php"){echo "class='active'"; } ?>><?php echo anchor('Webcontroller/viewPage/services.php', 'Services') ?></li>
                            <li <?php if($page=="portfolio.php"){echo "class='active'"; } ?>><?php echo anchor('Webcontroller/viewPage/portfolio.php', 'Portfolio') ?></li>
                            <li <?php if($page=="career.php"){echo "class='active'"; } ?>><?php echo anchor('Webcontroller/viewPage/career.php', 'Career') ?></li>
                            <li <?php if($page=="contact.php"){echo "class='active'"; } ?>><?php echo anchor('Webcontroller/viewPage/contact.php', 'Contact Us') ?></li>
                        </ul>
                    </div>    
                </div><!--/.col-md-3-->

                <div class="col-md-3 col-sm-6 info">
                    <div class="widget">
                        <h3>CONTACT</h3>
                        <ul>
                            <li><p>103, Sai Ram Plaza, Bhawarkua A.B. Road, Indore (M.P.)</p></li>   
                            <li><p><i class="fa fa-phone"></i> &nbsp; 0731-4279840</p></li>
                            <li><p><i class="fa fa-mobile"></i> &nbsp; +91-7828407092</p></li>
                            <li><p><i class="fa fa-envelope-o"></i> &nbsp; info@technorizen.com</p></li>
                        </ul>
                    </div>    
                </div><!--/.col-md-3-->

                <div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <h3>Our Partners</h3>
                        <ul>
                            <li><a href="#">Adipisicing Elit</a></li>
                            <li><a href="#">Eiusmod</a></li>
                            <li><a href="#">Tempor</a></li>
                            <li><a href="#">Veniam</a></li>
                            <li><a href="#">Exercitation</a></li>
                            <li><a href="#">Ullamco</a></li>
                        </ul>
                    </div>    
                </div><!--/.col-md-3-->
            </div>
        </div>
    </section><!--/#bottom-->

    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    &copy; 2016 <a id="flink" target="_blank" href="http://technorizen.com/" title="Technorizen Software Solution">Technorizen Software Solution</a>. All Rights Reserved.
                </div>
                <div class="col-sm-6">
                       <div class="social">
                            <ul class="social-share">
                                <li><a href="https://www.facebook.com/technorizen/" title="facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://twitter.com/InfoTechnorizen" title="twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#" title="linkdin" target="_blank"><i class="fa fa-linkedin"></i></a></li> 
                                <li><a href="#" title="google plus" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                            <!--<div class="search">
                                <form role="form">
                                    <input type="text" class="search-form" autocomplete="off" placeholder="Search">
                                    <i class="fa fa-search"></i>
                                </form>
                           </div>-->
                       </div>
                    </div>
            </div>
        </div>
    </footer><!--/#footer-->

    <div id="scroll-to-top" class="scroll-to-top">
        <span>
            <i class="fa fa-chevron-up"></i>    
        </span>
    </div><!-- /#scroll-to-top -->

    <!-- slide enquiry form -->
    <div class="slideBtn">
        <span>
            <i class="fa fa-phone fa-spin"></i>    
        </span>
    </div><!-- /#slideBtn -->
    <div id="slidediv">
        <div class="row contact-wrap"> 
                <?= form_open('webcontroller/add_contact',"id='main-contact-form' class='contact-form' name='contact-form'");  ?>
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="form-group">
                            <label>Name <span>*</span></label>
                            <input type="text" name="name" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label>Email <span>*</span></label>
                            <input type="email" name="email" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label>Phone <span>*</span></label>
                            <input type="text" name="mobile" class="form-control" required="required">
                        </div>
                        
                        
                        <div class="form-group">
                            <label>Message </label>
                            <textarea name="message" id="message" required="required" class="form-control" rows="4"></textarea>
                        </div>                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg form-control" style="border:none;">Submit Message</button>
                        </div>                 
                    </div>
                    
                <?= form_close(); ?>
            </div><!--/.row-->
    </div>
    <!-- //slide enquiry form -->

    <script src="<?= base_url('js/jquery.js'); ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('js/jquery.prettyPhoto.js'); ?>"></script>
    <script src="<?= base_url('js/jquery.isotope.min.js'); ?>"></script>
    <script src="<?= base_url('js/main.js'); ?>"></script>
    <script src="<?= base_url('js/wow.min.js'); ?>"></script>
    <script src="<?= base_url('js/jquery-1.11.2.min.js'); ?>"></script>
     <!-- Include jQuery Js -->
      
      <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
      <script src="<?= base_url('js/custom.min.js'); ?>"></script>
    <script type="text/javascript">
        $('#scroll-to-top').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
         });

        //speed control of carousel slide
          $(document).ready(function() {  
            //$('.next').trigger('click');
            //$('.prev').trigger('click');
            $('.prev').trigger('click');
          });

        // jquery for fixed header
        $(window).scroll(function(){
          var sticky = $('.navbar'),
              scroll = $(window).scrollTop();

          if (scroll >= 200) sticky.addClass('fixed-navbar');
          else sticky.removeClass('fixed-navbar');
        });

        //jquery for nicescroll color
        $(document).ready(function() {    
            $(".nicescroll-rails div").css('background-color','rgb(1, 192, 243)');            
        });

        // jquery for slide enquiry
        $(document).ready(function() {  
          $(".slideBtn").click(function() {
            $('#slidediv').toggle('slide');
          });
        });

        // jquery for dropdown open
        /*$(document).ready(function() {  
          $(".dropdown-toggle").click(function() {
            $('.dropdown-menu').toggle('slide');
          });
        });*/

    </script>
        <script>
        $(document).ready(function(){
          $('.dropdown a.dropdown-toggle').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
          });
        });
        </script>

</body>
</html>
<?php if($this->session->flashdata('msg'))
{
?>
<script> alert('Thank you for contact us...!'); </script>
<?php
}
?>