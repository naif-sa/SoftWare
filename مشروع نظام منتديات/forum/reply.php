<?php include"includes/header.php"; ?>
<table>
<tr>
<td><a href='index.html'>تجربة برمجة سكربت منتدى</a></td>
</tr>
</table>
<?php
if(isset($_GET['action']) &&!empty($_GET['action'])){
	$action = $_GET['action'];
	if($action == "add"){
		if(isset($_GET['t']) &&!empty($_GET['t'])){
		$t_id = filter_var(stripslashes($_GET['t']), FILTER_SANITIZE_NUMBER_INT);
		$q_t = mysqli_query($config,"select * from therads where therad_id='$t_id'");
		if(mysqli_num_rows($q_t) !="0"){
		$f_id = mysqli_fetch_object($q_t);
		$f_id = $f_id->forum_id;
		if(isset($_POST['add'])){
		@$reply_text	        = filter_var(stripslashes($_POST['reply_text']), FILTER_SANITIZE_STRING);
		if(empty($reply_text)){masseg("الرجاء ملئ جميع البيانات","");}
		else{
			$insert = mysqli_query($config,"insert into replys (therad_id,forum_id,reply_text) values('$t_id','$f_id','$reply_text')");
			$new_id = mysqli_insert_id($config);
			if($insert){masseg("تم إضافة الرد بنجاح","t$t_id.html#$new_id");}
			else{masseg("يوجد خطئ في إضافة الرد","t$t_id.html");}
		}
		}
		}else{header("Location:index.html");}
		}else{header("Location:index.html");}
			echo"
				<table>
				<tr>
				<td>إضافة رد</td>
				</tr>
				</table>
			";	
			echo"
			<form action='' method='POST' autocomplete='OFF'>
			<table>
			<tr>
			<td>محتوى الرد</a></td>
			<td><textarea class='texta-t' name='reply_text'></textarea></td>
			</tr>
			</table>
			<table>
			<tr colspan='3'>
			<td><button class='button-panel' name='add'>إضافة الرد</button></td>
			</tr>
			</table>
			";
	}
	if($action == "edit"){
		if(isset($_GET['r']) &&!empty($_GET['r'])){
			$r_id = filter_var(stripslashes($_GET['r']), FILTER_SANITIZE_NUMBER_INT);
			$q_r = mysqli_query($config,"select * from replys where reply_id='$r_id'");
			if(mysqli_num_rows($q_r) !="0"){
			$fetch = mysqli_fetch_object($q_r);
			if(isset($_POST['edit'])){
			@$reply_text	        = filter_var(stripslashes($_POST['reply_text']), FILTER_SANITIZE_STRING);
			if(empty($reply_text)){masseg("الرجاء ملئ جميع البيانات","");}
			else{
				$update = mysqli_query($config,"update replys set reply_text='$reply_text' where reply_id='$r_id'");
				if($update){masseg("تم تعديل الرد بجاح","t$fetch->therad_id.html#$r_id");}
				else{masseg("يوجد خطئ في تعديل الرد","t$fetch->therad_id.html#$r_id");}
			}
			}
			echo"
			<table>
			<tr>
			<td>تعديل الرد</td>
			</tr>
			</table>
			<form action='' method='POST' autocomplete='OFF'>
			<table>
			<tr>
			<td>محتوى الرد</a></td>
			<td><textarea class='texta-t' name='reply_text'>$fetch->reply_text</textarea></td>
			</tr>
			</table>
			<table>
			<tr colspan='3'>
			<td><button class='button-panel' name='edit'>تعديل الرد</button></td>
			</tr>
			</table>
			";
			}
			else{header("Location:index.html");}
		}else{header("Location:index.html");}
	}
	if($action == "delete"){
		if(isset($_GET['confirm']) &&!empty($_GET['confirm'])){
		$confirm = filter_var(stripslashes($_GET['confirm']), FILTER_SANITIZE_NUMBER_INT);
		$sel = mysqli_fetch_object(mysqli_query($config,"select * from replys where reply_id='$confirm'"));
		$therad_id = $sel->therad_id;
		confirm("هل انت متأكد من حذف الرد", "?action=delete&r=$confirm","t$therad_id.html");
		}
		if(isset($_GET['r']) &&!empty($_GET['r'])){
		$r_id = filter_var(stripslashes($_GET['r']), FILTER_SANITIZE_NUMBER_INT);
		$sel = mysqli_fetch_object(mysqli_query($config,"select * from replys where reply_id='$r_id'"));
		$therad_id = $sel->therad_id;
		$delete = mysqli_query($config,"delete from replys where reply_id='$r_id'");
		if($delete){masseg("تم حذف الرد بنجاح","t$therad_id.html");}
		else{masseg("يوجد خطئ في حذف الرد","t$therad_id.html");}
		}
	
}
}
else{header("Location:index.html");}
?>
<?php include"includes/footer.php"; ?>