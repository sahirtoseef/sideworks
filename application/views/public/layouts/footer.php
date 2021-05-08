<div class="global_footer">
<ul>
	<li><a href="<?php echo base_url(''); ?>">Home</a></li>
	<li><a href="<?php echo base_url('request-a-demo'); ?>">Request A Demo</a></li>
	<li><a href="<?php echo base_url('login'); ?>">Login</a></li>
	<li><a href="<?php echo base_url('register'); ?>">Register</a></li>
    <li><a href="<?php echo base_url('contact'); ?>">Contact Us</a></li>
	<li><a href="<?php echo base_url('admin/login'); ?>">Admin</a></li>
</ul>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="<?= assets('default/js/custom.js'); ?>"></script>
<?= isset($scriptInject) ? $scriptInject : ''; ?>
</body>
</html>
