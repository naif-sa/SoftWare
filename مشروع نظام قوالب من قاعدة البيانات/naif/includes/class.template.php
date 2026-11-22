<?php
class template {
	private $config;
	private $template;
	public function __construct($config) {
	$this->config = $config;
	}
	public function get($template_name) {
	if (isset($_GET['styleid']) && !empty($_GET['styleid'])) {
	$styleid = filter_var($_GET['styleid'], FILTER_SANITIZE_NUMBER_INT);
	$template_name = mysqli_real_escape_string($this->config,$template_name);
	$query = "select * from template where template_name='$template_name' and style_id='$styleid'";
	$temp = mysqli_query($this->config,$query);
	if(mysqli_num_rows($temp) !=0){
	if ($temp) {
	$template = mysqli_fetch_assoc($temp);
	@$this->template = $template['template_code'];
	if(!@$this->template = $template['template_code']){echo"<center><h1 style='color:red;'>[----------[ TEMPLATE ERROR ]----------]</h1></center>";}
	mysqli_free_result($temp);
	} else {
	echo"<center><h1 style='color:red;'>[----------[ TEMPLATE ERROR ]----------]</h1></center>";
	exit;
	}
	}else{header("Location:index.php");exit();}
	}
	else{
	$template_name = mysqli_real_escape_string($this->config,$template_name);
	$site_style = setting('site_style');
	$query = "select * from template where template_name='$template_name' and style_id='$site_style'";
	$temp = mysqli_query($this->config,$query);
	if ($temp) {
	$template = mysqli_fetch_assoc($temp);
	@$this->template = $template['template_code'];
	if(!@$this->template = $template['template_code']){echo"<center><h1 style='color:red;'>[----------[ TEMPLATE ERROR ]----------]</h1></center>";}
	mysqli_free_result($temp);
	} else {
	echo"<center><h1 style='color:red;'>[----------[ TEMPLATE ERROR ]----------]</h1></center>";
	exit;
	}
	}
	}
	public function replace($sh,$rep) {
	$this->template = str_replace($sh,$rep,$this->template);
	}	
	public function css_template() {
	if (isset($_GET['styleid']) && !empty($_GET['styleid'])) {
	$styleid = filter_var($_GET['styleid'], FILTER_SANITIZE_NUMBER_INT);
	$selcsstemplate = mysqli_query($this->config,"select * from template where template_name='css' and style_id='$styleid'");
	if ($selcsstemplate && mysqli_num_rows($selcsstemplate) > 0) {
	$template = mysqli_fetch_assoc($selcsstemplate);
	$template_css = $template['template_code'];
	return $template_css;
	}
	}else{
	$site_style = setting('site_style');
	$selcsstemplate = mysqli_query($this->config,"select * from template where template_name='css' and style_id='$site_style'");
	if ($selcsstemplate && mysqli_num_rows($selcsstemplate) > 0) {
	$template = mysqli_fetch_assoc($selcsstemplate);
	$template_css = $template['template_code'];
	return $template_css;
	}
	}
	}
	public function run() {
	global $config;
	global $language;
	$this->replace('{YEAR}',date('Y'));
	@eval('?>'.$this->template.'<?php');
	}
	public function __destruct() {
	mysqli_close($this->config);
	}
	}
$template = new template($config);
?>