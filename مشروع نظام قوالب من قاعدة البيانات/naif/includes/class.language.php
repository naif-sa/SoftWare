<?php
class language {
	private $config;
	public function __construct($config) {
	$this->config = $config;
	}
	public function get_phrase($phrase){
	$seting = setting("site_language");
	$language = mysqli_query($this->config,"select * from phrase where language_id='$seting' and phrase_name='$phrase'");
	if(mysqli_num_rows($language) !=0){
	$fetch_phrase = mysqli_fetch_object($language);
	$phrase_text = $fetch_phrase->phrase_text;
	return $phrase_text;
	}else{
	echo"<center><h1 style='color:red;'>[----------[ LANGUAGE ERROR ]----------]</h1></center>";
	exit;
	}
	}
	public function get_phrase2($phrase){
	$seting = setting("site_language");
	$language = mysqli_query($this->config,"select * from phrase where language_id='$seting' and phrase_name='$phrase'");
	if(mysqli_num_rows($language) !=0){
	$fetch_phrase = mysqli_fetch_object($language);
	$phrase_text = $fetch_phrase->phrase_text;
	echo $phrase_text;
	}else{
	echo"<center><h1 style='color:red;'>[----------[ LANGUAGE ERROR ]----------]</h1></center>";
	exit;
	}
	}
	public function get_dir(){
	$seting = setting("site_language");
	$query = mysqli_query($this->config,"select * from language where language_id='$seting'");
	if(mysqli_num_rows($query) !=0){
	$fetch_language = mysqli_fetch_object($query);
	$language_dir = $fetch_language->language_dir;
	echo $language_dir;
	}
	}
}
$language = new language($config);
?>