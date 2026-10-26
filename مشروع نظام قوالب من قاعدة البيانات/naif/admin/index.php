<?php include"includes/header.php";?>
<?php
if(isset($_GET['action']) &&!empty($_GET['action'])){
	$action = $_GET['action'];
	if($action !="setting"){
		header("Location:index.php");
	}
	if($action == "setting"){
		if(isset($_POST['update'])){
		@$site_name	        = filter_var(stripslashes($_POST['site_name']), FILTER_SANITIZE_STRING);
		@$site_url 	        = filter_var(stripslashes($_POST['site_url']), FILTER_SANITIZE_STRING);
		@$site_key          = filter_var(stripslashes($_POST['site_key']), FILTER_SANITIZE_STRING);
		@$site_desc         = filter_var(stripslashes($_POST['site_desc']), FILTER_SANITIZE_STRING);
		@$site_open         = filter_var(stripslashes($_POST['site_open']), FILTER_SANITIZE_STRING);
		@$site_copy         = filter_var(stripslashes($_POST['site_copy']), FILTER_SANITIZE_STRING);
		@$site_masseg_close = mysqli_real_escape_string($config,stripslashes($_POST['site_masseg_close']));
		if(
		empty($site_name) or
		empty($site_url) or
		empty($site_key) or
		empty($site_desc) or
		empty($site_open) or
		empty($site_copy) or
		empty($site_masseg_close)
		){masseg("الرجاء ملئ جميع البيانات","index.php?action=setting");}
		else{
		$update = mysqli_query($config,"
		update setting set
		site_name='$site_name',
		site_url='$site_url',
		site_desc='$site_desc',
		site_open='$site_open',
		site_masseg_close='$site_masseg_close',
		site_copy='$site_copy'
		");
		if($update){masseg("تم حفظ اعدادات الموقع بنجاح","index.php?action=setting");}
		else{masseg("يوجد خطئ في حفظ اعدادات الموقع","index.php?action=setting");}
		}
		}
		}
		echo"
			<form action='' method='POST' autocomplete='off'>
			<table>
			<tr>
			<td>اسم الموقع</td>
			<td><input type='text' value='".setting('site_name')."' name='site_name' class='input-t' /></td>
			</tr>
			<tr>
			<td>رابط الموقع</td>
			<td><input type='text' value='".setting('site_url')."' name='site_url' class='input-t' /></td>
			</tr>
			<tr>
			<td>الكلمات المفتاحية</td>
			<td><input type='text' value='".setting('site_key')."' name='site_key' class='input-t' /></td>
			</tr>
			<tr>
			<td>وصف الموقع</td>
			<td><textarea class='text-a' name='site_desc'>".setting('site_desc')."</textarea></td>
			</tr>			
			<tr>
			<td>حقوق الموقع</td>
			<td><textarea class='text-a' name='site_copy'>".setting('site_copy')."</textarea></td>
			</tr>
			<tr>
			<td>حالة الموقع</td>
			<td>
			<select class='select-panel' name='site_open'>
		";
		$open = setting('site_open');
		if($open == "1"){
			echo'
			<option value="1">مفتوح</option>
			<option value="2">مغلق</option>
			';
		}
		else{
		   echo'
			<option value="2">مغلق</option>
			<option value="1">مفتوح</option>
		';
		}
		echo"
			</td>
			</tr>
			<tr>
			<td>رسالة اغلاق الموقع</td>
			<td><textarea class='text-a' name='site_masseg_close'>".setting('site_masseg_close')."</textarea></td>
			</tr>
			</table>
			<table>
			<tr colspan='3'>
			<td><button class='button-panel' name='update'>حفظ الاعدادات</button></td>
			</tr>
			</table>
			</form>
		";
	}
?>
<?php include"includes/footer.php"; ?>