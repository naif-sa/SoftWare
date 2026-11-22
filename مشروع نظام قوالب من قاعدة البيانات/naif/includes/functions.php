<?php
function block($value){
	global $config;
	global $template;
	$select = mysqli_query($config,"SELECT * FROM block WHERE block_active='1' AND block_dir='$value'");
	if(mysqli_num_rows($select) !=0){
	while($block = mysqli_fetch_object($select)){
		$title = $block->block_title;
		$text = $block->block_text;
		$template->get("block");
		$template->replace("{TITLE}",$title);
		$template->replace("{TEXT}",$text);
		$template->run();
	}
	}
}
function block_template($title,$text){
	global $template;
		$template->get("block");
		$template->replace("{TITLE}",$title);
		$template->replace("{TEXT}",$text);
		$template->run();
}
function setting($value){
	global $config;
	$sel = mysqli_fetch_object(mysqli_query($config,"SELECT * FROM setting"));
	if($sel){
	return $sel->$value;
	}	
}
function getTitle(){
	global $pageTitle;
	if(isset($pageTitle)){
		return setting("site_name") .' - '.$pageTitle;
	}else{
		return setting("site_name");
	}
}
?>