<?php
if(isset($_GET['action']) &&!empty($_GET['action'])){
	$action = $_GET['action'];
	if(
	$action !="setting" &&
	$action !="add" &&
	$action !="styles" &&
	$action !="delete" &&
	$action !="export" &&
	$action !="templates" &&
	$action !="get" &&
	$action !="import"
	){header("Location:template.php?action=setting");}
	if($action == "setting"){
		include"includes/header.php";
		if(isset($_POST['update'])){
		$style_default = filter_var($_POST['style_default'], FILTER_SANITIZE_NUMBER_INT);
		$update = mysqli_query($config,"update setting set site_style='$style_default'");
		if($update){masseg("تم تعين الاستايل كفتراضي","template.php?action=setting");}
		else{masseg("يوجد خطئ في تعين الاستايل كفتراضي","template.php?action=setting");}
		}
		echo'
			<table>
			<tr>
			<td>ستايل الموقع</td>
			<td>
			<form action="" method="POST">
			<select class="select-panel" name="style_default">
		';
		$site_style = setting("site_style");
		$style = mysqli_fetch_assoc(mysqli_query($config,"select * from style where style_id='$site_style'"));
		$style_id = $style['style_id'];
		$style_title = $style['style_title'];
		echo "<option value='$style_id'>$style_title</option>";
		$loopstyle = mysqli_query($config,"select * from style where style_id !=$style_id");
		while($loop_style = mysqli_fetch_assoc($loopstyle)){
		$styleid = $loop_style['style_id'];
		$styletitle = $loop_style['style_title'];
		echo "<option value='$styleid'>$styletitle</option>";
		}
		echo'
			</select>
			</td>
			<td><button class="button-panel" name="update">تعين كفتراضي</button></td>
			</form>
			</tr>
			</table>
			<table>
			<tr>
			<td><a href="template.php?action=add">إنشاء استايل</a></td>
			<td><a href="template.php?action=styles">التحكم بالستايلات</a></td>
			<td><a href="template.php?action=templates">التحكم بالقوالب</a></td>
			<td><a href="template.php?action=import">استيراد استايل</a></td>
			</tr>
			</table>
		';
		include"includes/footer.php";
	}
	if($action == "add"){
		include"includes/header.php";
		if(isset($_POST['add'])){
		@$style_title	        = filter_var(stripslashes($_POST['style_title']), FILTER_SANITIZE_STRING);
		@$style_version	        = filter_var(stripslashes($_POST['style_version']), FILTER_SANITIZE_STRING);
		@$seltitle              = mysqli_query($config,"select * from style where style_title='$style_title' and style_version='$style_version'");
		if(empty($style_title) or empty($style_version)){masseg("الرجاء ملئ جميع البيانات","template.php?action=add");}
		else if(mysqli_num_rows($seltitle) > 0){masseg("الاستايل موجود مسبقا","template.php?action=add");}
		else{
		if (mysqli_query($config,"insert into style (style_title,style_version) values ('$style_title','$style_version')")) {
		$new_style_id = mysqli_insert_id($config);
		$sel = mysqli_query($config,"select * from template where style_id='1'");
		if (mysqli_num_rows($sel) > 0) {
		$stmt = mysqli_prepare($config, "insert into template (template_title, template_name, template_code, template_version, style_id) values (?, ?, ?, ?, ?)");
		mysqli_stmt_bind_param($stmt, "ssssi", $template_title, $template_name, $template_code, $template_version, $new_style_id);
		while ($template = mysqli_fetch_assoc($sel)) {
		$template_title   = $template['template_title'];
		$template_name    = $template['template_name'];
		$template_code    = $template['template_code'];
		$template_version = $template['template_version'];
		if (!mysqli_stmt_execute($stmt)) {
		echo "error executing query: " . mysqli_error($config);
		}
		}
		mysqli_stmt_close($stmt);
		}
		}
		masseg("تم إنشاء الاستايل بنجاح","template.php?action=add");
		}
		}
		echo'
			<form action="" method="post" autocomplete="off">
			<table>
			<tr>
			<td>عنوان الاستايل</td>
			<td><input type="text" name="style_title" class="input-t" /></td>
			</tr>
			<tr>
			<td>اصدار الاستايل</td>
			<td><input type="text" name="style_version" class="input-t" /></td>
			</tr>
			</table>
			<table>
			<tr colspan="3">
			<td><button class="button-panel" name="add">إنشاء الاستايل</button></td>
			</tr>
			</table>
			</form>
		';
		include"includes/footer.php";
	}
	if($action == "styles"){
		include"includes/header.php";
		echo'
			<table>
			<tr>
			<td>عنوان الاستايل</td>
			<td>اصدار الاستايل</td>
			<td colspan="3">التحكم بالاستايل</td>
			</tr>
		';
		$selectstyle = mysqli_query($config,"select * from style");
		while($style = mysqli_fetch_assoc($selectstyle)){
		$styletitle = $style['style_title'];
		$styleversion = $style['style_version'];
		$styleid = $style['style_id'];
		$setting_style = setting("site_style");
		if($styleid == $setting_style){
		echo"
		<tr>
		<td dir='ltr'><b style='font-size:17px;color:green;'>(:: الافتراضي ::)</b> $styletitle</td>
		<td>$styleversion</td>
		<td><a href='../index.php?styleid=$styleid'>معاينة</a></td>
		<td><a href='template.php?action=export&styleid=$styleid'>تصدير</a></td>
		<td><a href='template.php?action=delete&confirm=$styleid'>حذف</a></td>
		</tr>
		";
		}
		else{
		echo"
		<tr>
		<td>$styletitle</td>
		<td>$styleversion</td>
		<td><a href='../index.php?styleid=$styleid'>معاينة</a></td>
		<td><a href='template.php?action=export&styleid=$styleid'>تصدير</a></td>
		<td><a href='template.php?action=delete&confirm=$styleid'>حذف</a></td>
		</tr>
		";
		}
		}
		include"includes/footer.php";
	}
	if($action == "delete"){
		include"includes/header.php";
		if (isset($_GET['confirm']) && !empty($_GET['confirm'])) {
		$confirm = filter_var($_GET['confirm'], FILTER_SANITIZE_NUMBER_INT);
		confirm("هل انت متأكد من حذف الاستايل","template.php?action=delete&id=$confirm","template.php?action=styles");
		}
		if (isset($_GET['id']) && !empty($_GET['id'])) {
		$styleid = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		if ($styleid == "1" or $styleid == setting("site_style")) {
		masseg("لا يمكنك حذف الاستايل المدمج مع السكربت او الافتراضي","template.php?action=styles");
		} else {
		$delete = mysqli_query($config, "delete from style where style_id='$styleid'");
		if ($delete) {
		mysqli_query($config, "delete from template where style_id='$styleid'");
		masseg("تم حذف الاستايل بنجاح","template.php?action=styles");
		} else {
		masseg("يوجد خطأ في حذف الاستايل","template.php?action=styles");
		}
		}
		}
		include"includes/footer.php";
	}
	if($action == "import"){
		include"includes/header.php";
		if(isset($_POST['import'])){
		if (isset($_FILES['xmlfile']) && $_FILES['xmlfile']['error'] == 0) {
		$fileTmpPath = $_FILES['xmlfile']['tmp_name'];
		$fileType = $_FILES['xmlfile']['type'];
		if ($fileType != 'text/xml' && $fileType != 'application/xml') {
		masseg("يوجد خطئ في استيراد الاستايل","template.php?action=import");
		}
		$xml = simplexml_load_file($fileTmpPath);
		if (!isset($xml->style)) {
            masseg("الاستايل غير صالح","template.php?action=import");
        }
		if ($xml === false) {
		masseg("يوجد خطئ في استيراد الاستايل","template.php?action=import");
		}
		$styletitle = mysqli_real_escape_string($config, (string)$xml->style->title);
		$styleversion = mysqli_real_escape_string($config, (string)$xml->style->version);
		$sel   = mysqli_query($config,"select * from style where style_title='$styletitle' and style_version='$styleversion'");
		if(mysqli_num_rows($sel) !=0){masseg("الاستايل موجود مسبقا", "template.php?action=import");}
		$sqlStyle = "insert into style (style_title, style_version) values ('$styletitle', '$styleversion')";
		if (mysqli_query($config, $sqlStyle)) {
		$styleid = mysqli_insert_id($config);
		} else {
		masseg("يوجد خطئ في استيراد الاستايل","template.php?action=import");
		}
		foreach ($xml->template as $template) {
		$templateversion = mysqli_real_escape_string($config,$template['version']);
		$templatename = mysqli_real_escape_string($config,$template['name']);
		$templatetitle = mysqli_real_escape_string($config,$template['title']);
		$templatecode = mysqli_real_escape_string($config,$template);
		$sqlTemplate = "insert into template (template_version, template_name, template_title, template_code, style_id) values ('$templateversion', '$templatename', '$templatetitle', '$templatecode', $styleid)";
		if (!mysqli_query($config, $sqlTemplate)) {
		masseg("يوجد خطئ في استيراد الاستايل","template.php?action=import");
		}
		}
		import("جاري استيراد الاستايل","تم استيراد الاستايل بنجاح","template.php?action=import");
		} else {
		masseg("الرجاء اختيار ملف الاستايل","template.php?action=import");
		}
		mysqli_close($config);
		}
		echo'
			<form action="" method="post" enctype="multipart/form-data">
			<table>
			<tr>
			<td>ملف الاستايل</td>
			<td><input class="input-f" type="file" name="xmlfile" accept=".xml"></td>
			</tr>
			</table>
			<table>
			<tr>
			<td><button class="button-panel" name="import">استيراد</button></td>
			</tr>
			</table>
			</form>
		';
		include"includes/footer.php";
	}
	if($action == "templates"){
		include "includes/header.php"; 
		echo'
			<form method="GET" action="template.php">
			<input type="hidden" name="action" value="get">
			<table>
			<tr>
			<td>اختار الاستايل</td>
			<td>
			<select class="select-panel" name="style" onchange="this.form.submit()">
			<option value="">---------------[ اختار ]---------------</option>
		';
		$style = mysqli_query($config,"select * from style");
		while($loop = mysqli_fetch_assoc($style)){
		$id = $loop['style_id'];
		$title = $loop['style_title'];
		echo"<option value='$id'>$title</option>";
		}
		echo'
			</select>
			</td>
			</table>
			</form>
		';
		include "includes/footer.php";
	}
	if($action == "get"){
		include "includes/header.php";
		echo'
			<form method="GET" action="">
			<input type="hidden" name="action" value="get">
		';
		$style_id = isset($_GET['style']) ? $_GET['style'] : '';
		if(isset($_GET['style']) &&!empty($_GET['style'])){
		$id = filter_var($_GET['style'], FILTER_SANITIZE_NUMBER_INT); 
		$sel = mysqli_query($config,"select * from style where style_id='$id'");
		if(mysqli_num_rows($sel) !=0){
		echo"<input type='hidden' name='style' value='$id'>";
		}else{header("Location:template.php?action=templates");}
		}else{header("Location:template.php?action=templates");}
		echo'
			<table>
			<tr>
		';
			echo'
			<td>اختار القالب</td>
			<td>
			<select class="select-panel" name="template" onchange="this.form.submit()">
		';
		if(isset($_GET['template']) &&!empty($_GET['template'])){
		$ttt_id = filter_var($_GET['template'], FILTER_SANITIZE_NUMBER_INT); 
		$templateii = mysqli_query($config, "select * from template where template_id = '$ttt_id'");
		while ($loop = mysqli_fetch_assoc($templateii)) {
		$id = $loop['template_id'];
		$title = $loop['template_title'];
		echo "<option value='$id'>$title</option>";
		}
		}
		if(isset($ttt_id)){
		$templates = mysqli_query($config, "select * from template where style_id = '$style_id' and template_id !=$ttt_id");
		}
		else{
		echo'<option value="">---------------[ اختار ]---------------</option>';
		$templates = mysqli_query($config, "select * from template where style_id = '$style_id'");
		}
		while ($loop = mysqli_fetch_assoc($templates)) {
		$id = $loop['template_id'];
		$title = $loop['template_title'];
		echo "<option value='$id'>$title</option>";
		}
		echo'
			</select>
			</td>
			</tr>
			</table>
			</form>
		';
		if(isset($_GET['template'])){
		$temp_id = filter_var($_GET['template'], FILTER_SANITIZE_NUMBER_INT);
		$sel = mysqli_query($config,"select * from template where template_id='$temp_id'");
		if(mysqli_num_rows($sel) !=0){
		$template = mysqli_fetch_object($sel);
		if(isset($_POST['savetemplate'])){
		$template_code = $_POST['template_code'];
		if(empty($template_code)){masseg("الرجاء ملئ جميع البيانات","");}
		else{
		$template_code = mysqli_real_escape_string($config, $template_code);
		$temp_id = mysqli_real_escape_string($config, $temp_id);
		$update = mysqli_query($config, "update template set template_code='$template_code' where template_id='$temp_id'");
		if($update){masseg("تم حفظ القالب بنجاح","");}
		else{masseg("يوجد خطئ في حفظ القالب","");}
		}
		}
		}else{header("Location:template.php?action=templates");}
		echo"
			<form method='POST' action=''>
			<table>
			<tr>
			<td>اسم القالب</td>
			<td>$template->template_title</td>
			</tr>
			<tr>
			<td>اصدار القالب</td>
			<td>$template->template_version</td>
			</tr>
			<tr>
			<td>كود القالب</td>
			<td>
			<textarea class='text-a-p' name='template_code'>$template->template_code</textarea>
			</td>
			</tr>
			</table>
			<table>
			<tr colspan='3'>
			<td><button class='button-panel' name='savetemplate'>حفظ القالب</button></td>
			</tr>
			</table>
		";
		}
		echo'</form>';
		include "includes/footer.php";
	}
	if($action == "export"){
		include"../includes/config.php";
		if (isset($_GET['styleid']) && !empty($_GET['styleid'])) {
		$styleid = filter_var($_GET['styleid'], FILTER_SANITIZE_NUMBER_INT);
		$stylesQuery = "select * from style where style_id='$styleid'";
		$templatesQuery = "select * from template where style_id='$styleid'";
		$stylesResult = mysqli_query($config,$stylesQuery);
		$templatesResult = mysqli_query($config,$templatesQuery);
		header('Content-Type: application/xml; charset=UTF-8');
		header('Content-Disposition: attachment; filename="style_'.rand(1111111,9999999).'.xml"');
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo "\n<templates>\n";
		if (mysqli_num_rows($stylesResult) > 0) {
		while ($style = mysqli_fetch_assoc($stylesResult)) {
		echo "<style>\n";
		echo "<title>" . htmlspecialchars($style['style_title']) . "</title>\n";
		echo "<version>" . htmlspecialchars($style['style_version']) . "</version>\n";
		echo "</style>\n";
		}}
		if (mysqli_num_rows($templatesResult) > 0) {
		while ($template = mysqli_fetch_assoc($templatesResult)) {
		echo '<template version="' . htmlspecialchars($template['template_version']) . '" name="' . htmlspecialchars($template['template_name']) . '" title="' . htmlspecialchars($template['template_title']) . '">';
		$template_code = trim($template['template_code']);
		echo "\n<![CDATA[$template_code]]>\n";
		echo "</template>\n";}}
		echo '</templates>';
		mysqli_close($config);
		}else{header("Location:template.php?action=styles");exit;}
	}
	
}else{header("Location:template.php?action=setting");}
?>