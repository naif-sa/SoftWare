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
		if(isset($_GET['f']) &&!empty($_GET['f'])){
			$forum_id = filter_var(stripslashes($_GET['f']), FILTER_SANITIZE_NUMBER_INT);
			$qf = mysqli_query($config,"select * from forums where forum_id='$forum_id' and forum_active='1'");
			if(mysqli_num_rows($qf) == "0"){header("Location:index.html");}
			$get_title_forum = mysqli_fetch_object($qf);
			if(isset($_POST['add'])){
		@$therad_title	        = filter_var(stripslashes($_POST['therad_title']), FILTER_SANITIZE_STRING);
		@$therad_text	        = filter_var(stripslashes($_POST['therad_text']), FILTER_SANITIZE_STRING);
			if(empty($therad_title) or empty($therad_text)){masseg("الرجاء ملئ جميع البيانات","");}
			else{
				$insert = mysqli_query($config,"insert into therads(therad_title,therad_text,forum_id) values('$therad_title','$therad_text','$forum_id')");
				if($insert){masseg("تم إضافة الموضوع بنجاح في منتدى $get_title_forum->forum_title","f$forum_id.html");}
				else{masseg("يوجد خطئ في إضافة الموضوع","");}
			}
			}
		}else{header("Location:index.html");}
		echo"
			<table>
			<tr>
			<td>إضافة موضوع لمنتدى $get_title_forum->forum_title</td>
			</tr>
			</table>
			<form action='' method='POST' autocomplete='OFF'>
			<table>
			<tr>
			<td width='40%'>عنوان الموضوع</a></td>
			<td><input type='text' name='therad_title' class='input-t'></td>
			</tr>
			<tr>
			<td>محتوى الموضوع</a></td>
			<td><textarea class='texta-t' name='therad_text'></textarea></td>
			</tr>
			</table>
			<table>
			<tr colspan='3'>
			<td><button class='button-panel' name='add'>إضافة الموضوع</button></td>
			</tr>
			</table>
			";
	}
	if($action == "edit"){
		if(isset($_GET['t']) &&!empty($_GET['t'])){
			$t_id = filter_var(stripslashes($_GET['t']), FILTER_SANITIZE_NUMBER_INT);
			$q_t = mysqli_query($config,"select * from therads where therad_id='$t_id'");
			if(mysqli_num_rows($q_t) !="0"){
			$fetch = mysqli_fetch_object($q_t);
			}else{header("Location:index.html");}
			if(isset($_POST['edit'])){
		@$therad_title	        = filter_var(stripslashes($_POST['therad_title']), FILTER_SANITIZE_STRING);
		@$therad_text	        = filter_var(stripslashes($_POST['therad_text']), FILTER_SANITIZE_STRING);
		if(empty($therad_title) or empty($therad_text)){masseg("الرجاء ملئ جميع البيانات","");}
		else{
			$update = mysqli_query($config,"update therads set therad_title='$therad_title',therad_text='$therad_text' where therad_id='$t_id'");
			if($update){masseg("تم تعديل الموضوع بنجاح","t$t_id.html");}
			else{masseg("يوجد خطئ في تعديل الموضوع","t$t_id.html");}
		}
			}
			
		}else{header("Location:index.html");}
			echo"
			<table>
			<tr>
			<td>تعديل موضوع</td>
			</tr>
			</table>
			<form action='' method='POST' autocomplete='OFF'>
			<table>
			<tr>
			<td width='40%'>عنوان الموضوع</a></td>
			<td><input type='text' value='$fetch->therad_title' name='therad_title' class='input-t'></td>
			</tr>
			<tr>
			<td>محتوى الموضوع</a></td>
			<td><textarea class='texta-t' name='therad_text'>$fetch->therad_text</textarea></td>
			</tr>
			</table>
			<table>
			<tr colspan='3'>
			<td><button class='button-panel' name='edit'>تعديل الموضوع</button></td>
			</tr>
			</table>
			";
	}
	if($action == "delete"){
		if(isset($_GET['confirm']) &&!empty($_GET['confirm'])){
		$confirm = filter_var(stripslashes($_GET['confirm']), FILTER_SANITIZE_NUMBER_INT);
		confirm("هل انت متأكد من حذف الموضوع مع المشاركات", "?action=delete&t=$confirm","t$confirm.html");
		}
		if(isset($_GET['t']) &&!empty($_GET['t'])){
		$t_id = filter_var(stripslashes($_GET['t']), FILTER_SANITIZE_NUMBER_INT);
		$delete = mysqli_query($config,"delete from therads where therad_id='$t_id'");
		mysqli_query($config,"delete from replys where therad_id='$t_id'");
		if($delete){masseg("تم حذف الموضوع بنجاح","index.html");}
		else{masseg("يوجد خطى في الحذف","index.html");}
		}
		else{header("Location:index.html");}
	}
}else{header("Location:index.html");}
?>
<?php include"includes/footer.php"; ?>