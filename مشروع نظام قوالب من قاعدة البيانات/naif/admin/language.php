<?php
if(isset($_GET['action']) &&!empty($_GET['action'])){
	$action = $_GET['action'];
	if(
	$action !="setting"&&
	$action !="add"&&
	$action !="delete"&&
	$action !="edit"&&
	$action !="import"&&
	$action !="export"&&
	$action !="phrases"&&
	$action !="get"&&
	$action !="languages"
	){header("Location:language.php?action=setting");}
	if($action == "setting"){
		include"includes/header.php";
		if(isset($_POST['update'])){
		$language_default = filter_var($_POST['language_default'], FILTER_SANITIZE_NUMBER_INT);
		$update = mysqli_query($config,"update setting set site_language='$language_default'");
		if($update){masseg("تم تعين اللغة كفتراضية","language.php?action=setting");}
		else{masseg("يوجد خطئ في تعين اللغة كفتراضية","language.php?action=setting");}
		}
		echo'
			<table>
			<tr>
			<td>لغة الموقع</td>
			<td>
			<form action="" method="POST">
			<select class="select-panel" name="language_default">
		';
		$id_set = setting("site_language");
		$def_language = mysqli_fetch_assoc(mysqli_query($config,"select * from language where language_id='$id_set'"));
		$id = $def_language['language_id'];
		$title = $def_language['language_title'];
		echo"<option value='$id'>$title</option>";
		$loop_lang = mysqli_query($config,"select * from language where language_id !=$id_set");
		while($loop_language = mysqli_fetch_assoc($loop_lang)){
		$id = $loop_language['language_id'];
		$title = $loop_language['language_title'];
		echo"<option value='$id'>$title</option>";
		}
		echo'
			</select>
			</td>
			<td><button class="button-panel" name="update">تعين كفتراضية</button></td>
			</form>
			</tr>
			</table>
			<table>
			<tr>
			<td><a href="language.php?action=add">إنشاء لغة</a></td>
			<td><a href="language.php?action=languages">التحكم باللغات</a></td>
			<td><a href="language.php?action=phrases">التحكم بالعبارات</a></td>
			<td><a href="language.php?action=import">استيراد لغة</a></td>
			</tr>
			</table>
		';
		include"includes/footer.php";
	}
	if($action == "add"){
		include"includes/header.php";
		if(isset($_POST['add'])){
		@$language_title	        = filter_var(stripslashes($_POST['language_title']), FILTER_SANITIZE_STRING);
		@$language_version	        = filter_var(stripslashes($_POST['language_version']), FILTER_SANITIZE_STRING);
		@$language_dir	            = filter_var(stripslashes($_POST['language_dir']), FILTER_SANITIZE_STRING);
		@$seltitle                   = mysqli_query($config,"select * from language where language_title='$language_title' and language_version='$language_version'");
		if(mysqli_num_rows($seltitle) !=0){masseg("اللغة موجودة مسبقا","language.php?action=add");}   
		else if(empty($language_title) or empty($language_version) or empty($language_dir)){
		masseg("الرجاء ملئ جميع البيانات","language.php?action=add");
		}
		else{
		$insert = mysqli_query($config,"insert into language 
		(language_title,language_version,language_dir)
		values('$language_title','$language_version','$language_dir')
		");
		if($insert){
		$new_id_language = mysqli_insert_id($config);
		$query = mysqli_query($config,"select * from phrase where language_id='1'");
		while($phrase2 = mysqli_fetch_object($query)){
		$phrase_name = $phrase2->phrase_name;
		$phrase_text = $phrase2->phrase_text;
		$phrase_version = $phrase2->phrase_version;
		mysqli_query($config,"insert into phrase 
		(phrase_name,phrase_text,phrase_version,language_id) 
		values ('$phrase_name','$phrase_text','$phrase_version','$new_id_language')");
		}
		}
		masseg("تم إنشاء اللغة بنجاح","language.php?action=add");
		}
		}
		echo'
			<form action="" method="post" autocomplete="off">
			<table>
			<tr>
			<td>عنوان اللغة</td>
			<td><input type="text" name="language_title" class="input-t" /></td>
			</tr>
			<tr>
			<td>اصدار اللغة</td>
			<td><input type="text" name="language_version" class="input-t" /></td>
			</tr>
			<tr>
			<td>اتجاه اللغة</td>
			<td>
			<select class="select-panel" name="language_dir">
			<option value="rtl">من اليمين لليسار</option>
			<option value="ltr">من اليسار لليمين</option>
			</select>
			</td>
			</tr>
			</table>
			<table>
			<tr colspan="3">
			<td><button class="button-panel" name="add">إنشاء اللغة</button></td>
			</tr>
			</table>
			</form>
		';
		include"includes/footer.php";
	}
	if($action == "delete"){
		include"includes/header.php";
		if (isset($_GET['confirm']) && !empty($_GET['confirm'])) {
		$confirm = filter_var($_GET['confirm'], FILTER_SANITIZE_NUMBER_INT);
		confirm("هل انت متأكد من حذف اللغة","?action=delete&id=$confirm","language.php?action=languages");
		}
		if (isset($_GET['id']) && !empty($_GET['id'])) {
		$language_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		if($language_id == "1" or $language_id == setting("site_language"))
		{masseg("لايمكنك حذف اللغة الافتراضية او المدمجة مع السكربت","language.php?action=languages");}
		else if(mysqli_num_rows(mysqli_query($config,"select * from language where language_id='$language_id'")) == "0"){
		header("Location:language.php?action=languages");
		}
		else{
		$delete = mysqli_query($config,"delete from language where language_id='$language_id'");
		if($delete){
		mysqli_query($config,"delete from phrase where language_id='$language_id'");
		masseg("تم حذف اللغة بنجاح","language.php?action=languages");
		}
		else{masseg("يوجد خطئ في حذف اللغة","language.php?action=languages");}
		}
		}else{header("Location:language.php?action=languages");}
		include"includes/footer.php";
	}
	if($action == "edit"){
		include"includes/header.php";
		if(isset($_GET['language_id']) &&!empty($_GET['language_id'])){
		$language_id = filter_var($_GET['language_id'], FILTER_SANITIZE_NUMBER_INT);
		$sel = mysqli_query($config,"select * from language where language_id='$language_id'");
		if(mysqli_num_rows($sel) == "0"){header("Location:language.php?action=languages");}
		else{
		$fetch = mysqli_fetch_assoc($sel);
		if(isset($_POST['update'])){
		@$language_title	        = filter_var(stripslashes($_POST['language_title']), FILTER_SANITIZE_STRING);
		@$language_version	        = filter_var(stripslashes($_POST['language_version']), FILTER_SANITIZE_STRING);
		@$language_dir	            = filter_var(stripslashes($_POST['language_dir']), FILTER_SANITIZE_STRING);
		if(empty($language_title) or empty($language_version) or empty($language_dir)){masseg("الرجاء ملئ جميع البيانات","");}
		else{
		$update = mysqli_query($config,"update language set 
		language_title='$language_title',
		language_version='$language_version',
		language_dir='$language_dir' where language_id='$language_id'
		");
		if($update){masseg("تم تعديل اللغة بنجاح","");}
		else{masseg("يوجد خطئ في تعديل اللغة","");}
		}
		}
		}
		}else{header("Location:language.php?action=languages");}
		$tl = $fetch['language_title'];
		$vl = $fetch['language_version'];
		echo"
			<form action='' method='post' autocomplete='off'>
			<table>
			<tr>
			<td>عنوان اللغة</td>
			<td><input type='text' name='language_title' value='$tl' class='input-t' /></td>
			</tr>
			<tr>
			<td>اصدار اللغة</td>
			<td><input type='text' name='language_version' value='$vl' class='input-t' /></td>
			</tr>
			<tr>
			<td>اتجاه اللغة</td>
			<td>
			<select class='select-panel' name='language_dir'>
		";
		$dir = $fetch['language_dir'];
		if($dir == "rtl"){
		echo"
			<option value='rtl'>من اليمين لليسار</option>
			<option value='ltr'>من اليسار لليمين</option>
		";
		}
		else{
		echo"
			<option value='ltr'>من اليسار لليمين</option>
			<option value='rtl'>من اليمين لليسار</option>
		";
		}
		echo'
			</select>
			</td>
			</tr>
			</table>
			<table>
			<tr colspan="3">
			<td><button class="button-panel" name="update">تعديل اللغة</button></td>
			</tr>
			</table>
			</form>
		';
		include"includes/footer.php";
	}
	if($action == "import"){
		include"includes/header.php";
		if(isset($_POST['import'])){
		if (isset($_FILES['xmlfile']) && $_FILES['xmlfile']['error'] == 0) {
		$filetmppath = $_FILES['xmlfile']['tmp_name'];
		$filetype = $_FILES['xmlfile']['type'];
		if ($filetype != 'text/xml' && $filetype != 'application/xml') {
		masseg("يوجد خطئ في استيراد اللغة", "language.php?action=import");
		}
		$xml = simplexml_load_file($filetmppath);
		if (!isset($xml->language)) {
            masseg("اللغة غير صالحة","language.php?action=import");
        }
		if ($xml === false) {
		masseg("يوجد خطئ في استيراد اللغة", "language.php?action=import");
		}
		$language_title     = $xml->language->title;
		$language_version   = $xml->language->version;
		$language_dir       = $xml->language->dir;
		$sel           = mysqli_query($config,"select * from language where language_title='$language_title' and language_version='$language_version'");
		if(mysqli_num_rows($sel) !=0){masseg("اللغة موجودة مسبقا", "language.php?action=import");}
		$sql = "insert into language (language_title,language_version,language_dir) values ('$language_title','$language_version','$language_dir')";
		if (mysqli_query($config,$sql)) {
		$language_id = mysqli_insert_id($config);
		} else {
		masseg("يوجد خطئ في استيراد اللغة","language.php?action=import");
		}
		foreach ($xml->phrase as $phrase) {
		$phrase_name = $phrase['name'];
		$phrase_version = $phrase['version'];
		$phrase = $phrase;
		$sql = "insert into phrase (phrase_name,phrase_version,phrase_text,language_id) values ('$phrase_name','$phrase_version','$phrase','$language_id')";
		if (!mysqli_query($config,$sql)) {
		masseg("يوجد خطئ في استيراد اللغة","language.php?action=import");
		}
		}
		import("جاري استيراد اللغة","تم إستيراد اللغة بنجاح","language.php?action=import");
		} else {
		masseg("الرجاء اختيار ملف اللغة","language.php?action=import");
		}
		}
		echo'
			<form action="" method="post" enctype="multipart/form-data">
			<table>
			<tr>
			<td>ملف اللغة</td>
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
	if($action == "export"){
		include"../includes/config.php";
		if (isset($_GET['language_id']) && !empty($_GET['language_id'])) {
		$language_id = filter_var($_GET['language_id'], FILTER_SANITIZE_NUMBER_INT);
		if(mysqli_num_rows(mysqli_query($config,"select * from language where language_id='$language_id'")) == "0"){header("Location:language.php?action=languages");exit;}
		header('Content-Type: application/xml; charset=UTF-8');
		header('Content-Disposition: attachment; filename="language_'.rand(1111111,9999999).'.xml"');
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo "\n<languages>\n";
		$query_language = mysqli_query($config,"select * from language where language_id='$language_id'");
		$query_phrase = mysqli_query($config,"select * from phrase where language_id='$language_id'");
		while($language = mysqli_fetch_assoc($query_language)){
		$title = $language['language_title'];
		$version = $language['language_version'];
		$dir = $language['language_dir'];
		echo"<language>\n";
		echo"<title>$title</title>\n";
		echo"<version>$version</version>\n";
		echo"<dir>$dir</dir>\n";
		echo"</language>\n";
		}
		while($phrase = mysqli_fetch_assoc($query_phrase)){
		$name = $phrase['phrase_name'];
		$version = $phrase['phrase_version'];
		$text = trim($phrase['phrase_text']);
		echo"<phrase name='$name' version='$version'>\n";
		echo"<![CDATA[$text]]>\n";
		echo"</phrase>\n";
		}
		echo '</languages>';
		}else{header("Location:language.php?action=languages");exit;}
	}
	if($action == "phrases"){
		include "includes/header.php";
		echo'
			<form method="GET" action="language.php">
			<input type="hidden" name="action" value="get">
			<table>
			<tr>
			<td>اختار اللغة</td>
			<td>
			<select class="select-panel" name="language" onchange="this.form.submit()">
			<option value="">---------------[ اختار ]---------------</option>
		';
		$q = mysqli_query($config,"select * from language");
		while($lan = mysqli_fetch_assoc($q)){
		$id    = $lan['language_id'];
		$title = $lan['language_title'];
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
		if (isset($_GET['language']) && !empty($_GET['language'])) {
		$pgget_language = filter_var($_GET['language'], FILTER_SANITIZE_NUMBER_INT);
		$pgget_sel = mysqli_num_rows(mysqli_query($config,"select * from language where language_id='$pgget_language'"));
		if($pgget_sel !=0){echo"<input type='hidden' name='language' value='$pgget_language'>";}else{header("Location:language.php?action=phrases");}
		}else{header("Location:language.php?action=phrases");}
		echo'
			<table>
			<tr>
			<td>اختار العبارة</td>
			<td>
			<select class="select-panel" name="phrase" onchange="this.form.submit()">
		';
		if(isset($_GET['phrase']) &&!empty($_GET['phrase'])){
		$phrasephrase = filter_var($_GET['phrase'], FILTER_SANITIZE_NUMBER_INT); 
		$pgget_query_phrase = mysqli_query($config,"select * from phrase where phrase_id='$phrasephrase'");
		if(mysqli_num_rows($pgget_query_phrase) == "0"){header("Location:language.php?action=phrases");}
		while($pgget_phrase7 = mysqli_fetch_assoc($pgget_query_phrase)){
		$i_d = $pgget_phrase7['phrase_id'];
		$n_ame = $pgget_phrase7['phrase_name'];
		echo"<option value='$i_d'>$n_ame</option>";
		}
		}
		if(isset($phrasephrase)){
		$pgget_query = mysqli_query($config,"select * from phrase where language_id='$pgget_language' and phrase_id !=$phrasephrase");
		}
		else{
		echo'<option value="">---------------[ اختار ]---------------</option>';
		$pgget_query = mysqli_query($config,"select * from phrase where language_id='$pgget_language'");
		}
		while($pgget_phrase = mysqli_fetch_assoc($pgget_query)){
		$id = $pgget_phrase['phrase_id'];
		$name = $pgget_phrase['phrase_name'];
		echo"<option value='$id'>$name</option>";
		}
		echo'
		</select>
		</td>
		</tr>
		</table>
		</form>
		';
		if(isset($_POST['savephrase'])){
		$pg_phrase_text = stripslashes($_POST['phrase_text']);
		if(empty($pg_phrase_text)){masseg("الرجاء ملئ جميع البيانات","");}
		else{
		$pg_phrase = filter_var($_GET['phrase'], FILTER_SANITIZE_NUMBER_INT);
		$pg_update = mysqli_query($config,"update phrase set phrase_text='$pg_phrase_text' where phrase_id='$pg_phrase'");
		if($pg_update){masseg("تم حفظ العبارة بنجاح","");}
		else{masseg("يوجد خطئ في حفظ العبارة","");}
		}
		}
		if (isset($_GET['phrase']) && !empty($_GET['phrase'])) {
		$pg_phrase = filter_var($_GET['phrase'], FILTER_SANITIZE_NUMBER_INT);
		$pg_fetch_phrase = mysqli_fetch_object(mysqli_query($config,"select * from phrase where phrase_id='$pg_phrase'"));
		echo"
			<form method='POST' action=''>
			<table>
			<tr>
			<td>اسم العبارة</td>
			<td>$pg_fetch_phrase->phrase_name</td>
			</tr>
			<tr>
			<td>اصدار العبارة</td>
			<td>$pg_fetch_phrase->phrase_version</td>
			</tr>
			<tr>
			<td>نص العبارة</td>
			<td>
			<textarea class='text-a-p' name='phrase_text'>$pg_fetch_phrase->phrase_text</textarea>
			</td>
			</tr>
			</table>
			<table>
			<tr colspan='3'>
			<td><button class='button-panel' name='savephrase'>حفظ العبارة</button></td>
			</tr>
			</table>
		";
		}
		echo'</form>';
		include "includes/footer.php";
	}
	if($action == "languages"){
		include "includes/header.php";
		echo'
			<table>
			<tr>
			<td>عنوان اللغة</td>
			<td>اصدار اللغة</td>
			<td colspan="3">التحكم باللغة</td>
			</tr>
			';
		$pglangs_select = mysqli_query($config,"select * from language");
		$pglangs_dafulat_language = setting("site_language");
		while($pglangs_language = mysqli_fetch_object($pglangs_select)){
		$id = $pglangs_language->language_id;
		$title = $pglangs_language->language_title;
		$version = $pglangs_language->language_version;
		if($id == $pglangs_dafulat_language){
		echo"
		<tr>
		<td dir='ltr'><b style=color:green;font-size:18px;'>(:: الافتراضية ::)</b> $title</td>
		<td>$version</td>
		<td><a href='language.php?action=edit&language_id=$id'>تعديل</a></td>
		<td><a href='language.php?action=export&language_id=$id'>تصدير</a></td>
		<td><a href='language.php?action=delete&confirm=$id'>حذف</a></td>
		</tr>
		";
		}
		else{
		echo"
		<tr>
		<td>$title</td>
		<td>$version</td>
		<td><a href='language.php?action=edit&language_id=$id'>تعديل</a></td>
		<td><a href='language.php?action=export&language_id=$id'>تصدير</a></td>
		<td><a href='language.php?action=delete&confirm=$id'>حذف</a></td>
		</tr>
		";
		}
		}
		echo'</table>';
		include "includes/footer.php";
	}
}else{header("Location:language.php?action=setting");}
?>