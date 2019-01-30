<?php ob_start(); ?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css');?>">
	<style type="text/css">
	body{
		font-family: 'Helvetica';
	}
	h2{
		color:red;
		font-weight: bold;
	}
	.x{
		width:100%;
		border:1px solid #CCC;
		padding:10px;
	}
	small{
		color:#CCC;
	}
</style>
</head>
<body>
	<span class="">Don del Rosario</span> <small>Programmer</small><br>
	<span>Engtech Global Solutions Inc.</span>
	<h2><span class="fa fa-user"></span> SAMPLE HEADER</h2>
	<div class="x">
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	</div>
</body>
</html>
<?php ob_end_flush(); ?>