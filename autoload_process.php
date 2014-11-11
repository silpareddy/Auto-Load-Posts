<?php
include("config.php"); //include config file

if($_POST)
{
	//sanitize post value
	$group_number = filter_var($_POST["group_no"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($group_number)){
		header('HTTP/1.1 500 Invalid number!');
		exit();
	}
	
	//get current starting point of records
	$position = ($group_number * $items_per_group);
	
	//Limit our results within a specified range. 
	$results = $mysqli->query("SELECT id,post_title,post_content FROM sol_posts WHERE post_status = 'publish'
           AND post_type = 'post'
           ORDER BY id ASC LIMIT $position, $items_per_group");
	
	if ($results) { 
		//output results from database
		
		while($obj = $results->fetch_object())
		{
			echo '<li id="item_'.$obj->id.'">'.$obj->id.' - <strong>'.$obj->post_title.'</strong></span> &mdash; <span class="page_message">'.$obj->post_content.'</span></li>';
		}
	
	}
	unset($obj);
	$mysqli->close();
}
?>