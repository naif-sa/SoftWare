<?php 
include"includes/header.php";

if(isset($_GET['action']) &&!empty($_GET['action'])){
	$action = $_GET['action'];
	if(
	$action !="setting" &&
	$action !="add" &&
	$action !="delete" &&
	$action !="edit" &&
	$action !="blocks"
	){header("Location: block.php?action=setting");}
	if($action == "setting"){
		echo'
			<table>
			<tr>
			<td><a href="block.php?action=add">إضافة قائمة</a></td>
			<td><a href="block.php?action=blocks">التحكم بالقوائم</a></td>
			</tr>
			</table>
		';
	}
	if($action == "add"){
		if(isset($_POST['add'])){
		@$block_title	        = filter_var(stripslashes($_POST['block_title']), FILTER_SANITIZE_STRING);
		@$seltitle              = mysqli_query($config,"select * from block where block_id='$block_title'");
		@$block_active	        = filter_var(stripslashes($_POST['block_active']), FILTER_SANITIZE_STRING);
		@$block_dir	            = filter_var(stripslashes($_POST['block_dir']), FILTER_SANITIZE_STRING);
		@$block_text	        = mysqli_real_escape_string($config,stripslashes($_POST['block_text']));
		if(empty($block_title) or empty($block_active) or empty($block_dir) or empty($block_text)){
		masseg("الرجاء ملئ جميع البيانات","block.php?action=add");}
		else if(mysqli_num_rows($seltitle) > 0){masseg("عنوان القائمة موجود مسبقا","block.php?action=add");}
		else{
		$insert = mysqli_query($config,"
		insert into block(block_title,block_active,block_dir,block_text)
		values('$block_title','$block_active','$block_dir','$block_text')");
		if($insert){masseg("تم إضافة القائمة بنجاح","block.php?action=add");}
		else{masseg("يوجد خطئ في إضافة القائمة","block.php?action=add");}
		}
		}
		echo'
			<form action="" method="POST" autocomplete="off">
			<table>
			<tr>
			<td>عنوان القائمة</td>
			<td><input type="text" name="block_title" class="input-t" /></td>
			</tr>
			<tr>
			<td>حالة القائمة</td>
			<td>
			<select class="select-panel" name="block_active">
			<option value="1">مفعلة</option>
			<option value="2">غير مفعلة</option>
			</td>
			</tr>
			<tr>
			<td>مكان القائمة</td>
			<td>
			<select class="select-panel" name="block_dir">
			<option value="1">يمين الموقع</option>
			<option value="2">وسط الموقع</option>
			<option value="3">يسار الموقع</option>
			</td>
			</tr>
			<tr>
			<td>محتوى القائمة</td>
			<td><textarea class="text-a-p" name="block_text"></textarea></td>
			</tr>
			</table>
			<table>
			<tr colspan="3">
			<td><button class="button-panel" name="add">إضافة القائمة</button></td>
			</tr>
			</table>
			</form>
		';
	}
	if($action == "blocks"){
		echo'
			<table>
			<tr>
			<td>عنوان القائمة</td>
			<td>موقع القائمة</td>
			<td>حالة القائمة</td>
			<td colspan="3">التحكم بالقائمة</td>
			</tr>
		';
		$selectblock = mysqli_query($config,"select * from block");
		if(mysqli_num_rows($selectblock) == "0"){
		echo"
		<tr>
		<td><h2>لايوجد قوائم جانبية في الموقع</h2></td>
		</tr>
		";
		}
		else{
		while($block = mysqli_fetch_object($selectblock)){

		$block_dir = $block->block_dir;
		if($block_dir == "1"){$blockdir = "يمين الموقع";}
		else if($block_dir == "2"){$blockdir = "وسط الموقع";}
		else{$blockdir = "يسار الموقع";}

		$block_active = $block->block_active;
		if($block_active == "1"){$blockactive = "مفعلة";}
		else{$blockactive = "غير مفعلة";}

		echo"
		<tr>
		<td>$block->block_title</td>
		<td>$blockdir</td>
		<td>$blockactive</td>
		<td><a href='block.php?action=edit&id=$block->block_id'>تعديل</a></td>
		<td><a href='block.php?action=delete&confirm=$block->block_id'>حذف</a></td>
		</tr>
		";
		}
		}
		echo'
			</table>
		';
	}
	if($action == "delete"){
		if (isset($_GET['confirm']) && !empty($_GET['confirm'])) {
		$confirm = filter_var($_GET['confirm'], FILTER_SANITIZE_NUMBER_INT);
		confirm("هل انت متأكد من حذف القائمة", "?action=delete&id=$confirm","block.php?action=blocks");
		}
		if (isset($_GET['id']) && !empty($_GET['id'])) {
		$block_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		$delete = mysqli_query($config, "delete from block where block_id='$block_id'");
		if ($delete) {
		masseg("تم حذف القائمة بنجاح", "block.php?action=blocks");
		} else {
		masseg("يوجد خطئ في حذف القائمة", "block.php?action=blocks");
		}
		}
	}
	if($action == "edit"){
		if(isset($_GET['id']) &&!empty($_GET['id'])){
		$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		$selectblock = mysqli_query($config,"select * from block where block_id='$id'");
		if(mysqli_num_rows($selectblock) == "0"){header("Location:block.php?action=blocks");exit;}
		$feschblock = mysqli_fetch_object($selectblock);
		if(isset($_POST['update'])){
		@$block_title	        = filter_var(stripslashes($_POST['block_title']), FILTER_SANITIZE_STRING);
		@$seltitle              = mysqli_query($config,"select * from block where block_id='$block_title'");
		@$block_active	        = filter_var(stripslashes($_POST['block_active']), FILTER_SANITIZE_STRING);
		@$block_dir	            = filter_var(stripslashes($_POST['block_dir']), FILTER_SANITIZE_STRING);
		@$block_text	        = mysqli_real_escape_string($config,stripslashes($_POST['block_text']));
		if(empty($block_title) or empty($block_active) or empty($block_dir) or empty($block_text)){
		masseg("الرجاء ملئ جميع البيانات","");}
		else{
		$update = mysqli_query($config,"
		update block set
		block_title='$block_title',
		block_active='$block_active',
		block_dir='$block_dir',
		block_text='$block_text' where block_id='$id'
		");
		if($update){masseg("تم تعديل القائمة بنجاح","");}
		else{masseg("يوجد خطئ في تعديل القائمة","");}
		}
		}}else{header("Location:block.php?action=blocks");}
		echo"
			<form action='' method='POST' autocomplete='off'>
			<table>
			<tr>
			<td>عنوان القائمة</td>
			<td><input type='text' value='$feschblock->block_title' name='block_title' class='input-t' /></td>
			</tr>
			<tr>
			<td>حالة القائمة</td>
			<td>
			<select class='select-panel' name='block_active'>
		";
		$block_active = $feschblock->block_active;
		if($block_active == "1"){
		echo'
		<option value="1">مفعلة</option>
		<option value="2">غير مفعلة</option>
		';
		}
		else{
		echo'
		<option value="2">غير مفعلة</option>
		<option value="1">مفعلة</option>
		';
		}
		echo"
			</td>
			</tr>
			<tr>
			<td>مكان القائمة</td>
			<td>
			<select class='select-panel' name='block_dir'>
		";
		$block_dir = $feschblock->block_dir;
		if($block_dir == "1"){
		echo '
		<option value="1">يمين الموقع</option>
		<option value="2">وسط الموقع</option>
		<option value="3">يسار الموقع</option>
		';
		}
		else if($block_dir == "2"){
		echo '
		<option value="2">وسط الموقع</option>
		<option value="1">يمين الموقع</option>
		<option value="3">يسار الموقع</option>
		';
		}
		else{
		echo '
		<option value="3">يسار الموقع</option>
		<option value="2">وسط الموقع</option>
		<option value="1">يمين الموقع</option>
		';	
		}
		echo"
			</td>
			</tr>
			<tr>
			<td>محتوى القائمة</td>
			<td><textarea class='text-a-p' name='block_text'>$feschblock->block_text</textarea></td>
			</tr>
			</table>
			<table>
			<tr colspan='3'>
			<td><button class='button-panel' name='update'>تعديل القائمة</button></td>
			</tr>
			</table>
			</form>
		";
	}
}else{header("Location: block.php?action=setting");}

include"includes/footer.php";
?>