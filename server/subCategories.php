 <?php
	session_start();
	$category = $_GET['category'];
	echo 'subCategories.html?category='.$category;
	exit();	
?> 
