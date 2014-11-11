<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Auto Loading Records</title>
<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>

<?php
include("config.php");
$results = $mysqli->query("SELECT COUNT(*) as t_records FROM sol_posts WHERE post_status = 'publish'
           AND post_type = 'post'");
$total_records = $results->fetch_object();
$total_groups = ceil($total_records->t_records/$items_per_group);
$results->close(); 
?>

<script type="text/javascript">
$(document).ready(function() {
	var track_load = 0; //total loaded record group(s)
	var loading  = false; //to prevents multipal ajax loads
	var total_groups = <?php echo $total_groups; ?>; //total record group(s)
	
	$('#results').load("autoload_process.php", {'group_no':track_load}, function() {track_load++;}); //load first group
	
	$(window).scroll(function() { //detect page scroll
		
		if($(window).scrollTop() + $(window).height() == $(document).height())  //user scrolled to bottom of the page?
		{
			
			if(track_load <= total_groups && loading==false) //there's more data to load
			{
				loading = true; //prevent further ajax loading
				$('.animation_image').show(); //show loading image
				
				//load data from the server using a HTTP POST request
				$.post('autoload_process.php',{'group_no': track_load}, function(data){
									
					$("#results").append(data); //append received data into the element

					//hide loading image
					$('.animation_image').hide(); //hide loading image once data is received
					
					track_load++; //loaded group increment
					loading = false; 
				
				}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
					
					alert(thrownError); //alert with HTTP error
					$('.animation_image').hide(); //hide loading image
					loading = false;
				
				});
				
			}
		}
	});
});
</script>
<style>
body,html {
	width:100%;
	height: 100%;
}
body {
	display: block;
	float: left;
	background-color: #000;
	color: #fff;
}
body,td,th {font-family: Georgia, Times New Roman, Times, serif;font-size: 15px;}
.animation_image {background: #F9FFFF;border: 1px solid #E1FFFF;padding: 10px;width: 500px;margin-right: auto;margin-left: auto;}
#results{width: 500px;margin-right: auto;margin-left: auto;}
#resultst ol{margin: 0px;padding: 0px;}
#results li{margin-top: 20px;border-top: 1px dotted #E1FFFF;padding-top: 20px;}
.container {
	width: 80%;
	float:none;
	margin:0 auto;
}
</style>
</head>

<body>
<div class="container">
<ol id="results">
</ol>
<div class="animation_image" style="display:none" align="center"><img src="ajax-loader.gif"></div>
</div>

</body>
</html>
