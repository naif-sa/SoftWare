<?php include"includes/header.php"; ?>
<?php
echo'
<table>
<tr>
<td><center><a href="forum.php">المنتديات</a></center></td>
<td><center><a href="index.html">رئيسية الموقع</a></center></td>
</tr>
</table>
<table>
<tr>
<td><a href="forum.php?action=add">إضافة منتدى</a></td>
<td><a href="forum.php?action=forums">التحكم بالمنتديات</a></td>
</tr>
</table><br>
';
if(isset($_GET['action']) && !empty($_GET['action'])){
	$action = $_GET['action'];
	if($action == "add"){
		if(isset($_POST['add'])){
		@$forum_title	        = filter_var(stripslashes($_POST['forum_title']), FILTER_SANITIZE_STRING);
		@$forum_desc	        = filter_var(stripslashes($_POST['forum_desc']), FILTER_SANITIZE_STRING);
		@$forum_active	        = filter_var(stripslashes($_POST['forum_active']), FILTER_SANITIZE_NUMBER_INT);
		@$forum_order	        = filter_var(stripslashes($_POST['forum_order']), FILTER_SANITIZE_NUMBER_INT);
		@$forum_is	     	    = filter_var(stripslashes($_POST['forum_is']), FILTER_SANITIZE_NUMBER_INT);
		if(
		empty($forum_title) or
		empty($forum_desc) or
		empty($forum_active) or
		empty($forum_order)
		){masseg("الرجاء ملئ جميع البيانات","");}
		else{
		$insert = mysqli_query($config,"insert into forums (forum_title,forum_desc,forum_active,forum_order,forum_is)
		values('$forum_title','$forum_desc','$forum_active','$forum_order','$forum_is')
		");
		if($insert){masseg("تم إضافة المنتدى بنجاح","");}
		else{masseg("يوجد خطئ في إضافة المنتدى","");}
		}
		}
		echo'	
			<form action="" method="POST" autocomplete="OFF">
			<table>
			<tr>
			<td width="40%">عنوان المنتدى</a></td>
			<td><input type="text" name="forum_title" class="input-t"></td>
			</tr>
			<tr>
			<td>وصف المنتدى</a></td>
			<td><textarea class="texta-t" name="forum_desc"></textarea></td>
			</tr>
			<tr>
			<td>حالة المنتدى</a></td>
			<td>
			<select class="select-p" name="forum_active">
			<option value="1">مفعل</option>
			<option value="2">غير مفعل</option>
			</select>
			</td>
			</tr>
			<tr>
			<td>ترتيب العرض</a></td>
			<td><input type="text" name="forum_order" class="input-t"></td>
			</tr>
			<tr>
			<td>نوع المنتدى</a></td>
			<td>
			<select class="select-p" name="forum_is">
			<option value="0">منتدى رئيسي</option>
		';
			$get_forum = mysqli_query($config,"select * from forums");
			while($forum = mysqli_fetch_object($get_forum)){
			echo"<option value='$forum->forum_id'>فرعي لــ $forum->forum_title</option>";
			}
			echo'
				</select>
				</td>
				</tr>
				</table>
				<table>
				<tr colspan="3">
				<td><button class="button-panel" name="add">إضافة المنتدى</button></td>
				</tr>
				</table>
				</form>
			';
	}
	if($action == "forums"){
		echo'
		<table>
		<tr>
		<td>اسم المنتدى</td>
		<td>نوع المنتدى</td>
		<td>حالة المنتدى</td>
		<td colspan="3">التحكم بالمنتدى</td>
		</tr>
		';
		$query = mysqli_query($config,"select * from forums");
		if(mysqli_num_rows($query) !=0){
		while($forum = mysqli_fetch_object($query)){
			$id = $forum->forum_id;
			$title = $forum->forum_title;
			$forum_active = $forum->forum_active;
			$forum_is = $forum->forum_is;
			if($forum_active == "2"){$active = "غير مفعل";}
			else{$active = "مفعل";}			
			if($forum_is == "0"){$is = "منتدى رئيسي";}
			else{$is = "منتدى فرعي";}
			echo"
			<tr>
			<td>$title</td>
			<td>$is</td>
			<td>$active</td>
			<td><a href='?action=edit&id=$id'>تعديل</a></td>
			<td><a href='?action=delete&confirm=$id'>حذف</a></td>
			</tr>
			";
		}
		echo'</table>';
		}else{
			echo"
			<tr>
			<td>لاتوجد منتديات حاليا</td>
			</tr>
			";
		}
	}
	if($action == "edit"){
		if(isset($_GET['id']) &&!empty($_GET['id'])){
		$id	 = filter_var(stripslashes($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
		if(isset($_POST['edit'])){
		@$forum_title	        = filter_var(stripslashes($_POST['forum_title']), FILTER_SANITIZE_STRING);
		@$forum_desc	        = filter_var(stripslashes($_POST['forum_desc']), FILTER_SANITIZE_STRING);
		@$forum_active	        = filter_var(stripslashes($_POST['forum_active']), FILTER_SANITIZE_NUMBER_INT);
		@$forum_order	        = filter_var(stripslashes($_POST['forum_order']), FILTER_SANITIZE_NUMBER_INT);
		@$forum_is	     	    = filter_var(stripslashes($_POST['forum_is']), FILTER_SANITIZE_NUMBER_INT);
		if(
		empty($forum_title) or
		empty($forum_desc) or
		empty($forum_active) or
		empty($forum_order)
		){masseg("الرجاء ملئ جميع البيانات","");}
		else{
			$update = mysqli_query($config,"
			update forums set
			forum_title='$forum_title',
			forum_desc='$forum_desc',
			forum_active='$forum_active',
			forum_order='$forum_order',
			forum_is='$forum_is'
			where forum_id='$id'
			");
			if($update){masseg("تم تعديل المنتدى بنجاح","");}
			else{masseg("يوجد خطئ في تعديل المنتدى","");}
		}
		}
		if(mysqli_num_rows(mysqli_query($config,"select * from forums where forum_id='$id'")) == "0"){header("Location:forum.php");}
		$fetch_data = mysqli_fetch_object(mysqli_query($config,"select * from forums where forum_id='$id'"));
		echo"
			<form action='' method='POST' autocomplete='OFF'>
			<table>
			<tr>
			<td width='40%'>عنوان المنتدى</a></td>
			<td><input type='text' value='$fetch_data->forum_title' name='forum_title' class='input-t'></td>
			</tr>
			<tr>
			<td>وصف المنتدى</a></td>
			<td><textarea class='texta-t' name='forum_desc'>$fetch_data->forum_desc</textarea></td>
			</tr>
			<tr>
			<td>حالة المنتدى</a></td>
			<td>
			<select class='select-p' name='forum_active'>
		";
		if($fetch_data->forum_active == "1"){
		echo"
			<option value='1'>مفعل</option>
			<option value='2'>غير مفعل</option>
		";
		}else{
		echo"
			<option value='2'>غير مفعل</option>
			<option value='1'>مفعل</option>
		";
		}
		echo"
			</select>
			</td>
			</tr>
			<tr>
			<td>ترتيب العرض</a></td>
			<td><input type='text' value='$fetch_data->forum_order' name='forum_order' class='input-t'></td>
			</tr>
			<tr>
			<td>نوع المنتدى</a></td>
			<td>
			<select class='select-p' name='forum_is'>
		";	
			$forum_is = $fetch_data->forum_is;
			if($forum_is == "0"){
			echo'<option value="0">منتدى رئيسي</option>';
			$q = mysqli_query($config,"select * from forums where forum_id !='$id'");
			while($fm = mysqli_fetch_object($q)){
			echo"<option value='$fm->forum_id'>فرعي لمنتدى $fm->forum_title</option>";
			}
			}else{
			$query = mysqli_query($config,"select * from forums where forum_id='$id'");
			$fe = mysqli_fetch_object($query);
			$qqq = mysqli_fetch_object(mysqli_query($config,"select * from forums where forum_id='$fe->forum_is'"));
			echo"<option value='$qqq->forum_id'>فرعي لمنتدى $qqq->forum_title</option>";
			echo'<option value="0">منتدى رئيسي</option>';
			$q2 = mysqli_query($config,"select * from forums where forum_id !='$id'");
			while($fm2 = mysqli_fetch_object($q2)){
			echo"<option value='$fm2->forum_id'>فرعي لمنتدى $fm2->forum_title</option>";
			}
			}
		echo'
			</select>
			</td>
			</tr>
			</table>
			<table>
			<tr colspan="3">
			<td><button class="button-panel" name="edit">تعديل المنتدى</button></td>
			</tr>
			</table>
			</form>
		';
		}else{header("Location:forum.php");}
	}
	if($action == "delete"){
		if (isset($_GET['confirm']) && !empty($_GET['confirm'])) {
		$confirm = filter_var($_GET['confirm'], FILTER_SANITIZE_NUMBER_INT);
		confirm("هل انت متأكد من حذف المنتدى مع الاقسام الفرعية و المواضيع", "?action=delete&id=$confirm","?action=forums");
		}
		if (isset($_GET['id']) && !empty($_GET['id'])) {
		$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		$delete = mysqli_query($config,"delete from forums where forum_id='$id'");
		mysqli_query($config,"delete from forums where forum_is='$id'");
		mysqli_query($config,"delete from therads where forum_id='$id'");
		mysqli_query($config,"delete from replys where forum_id='$id'");
		if($delete){masseg("تم حذف المنتدى مع الاقسام الفرعية و المواضيع","?action=forums");}
		else{masseg("يوجد خطئ في حذف المنتديات و الاقسام الفرعية مع المواضيع","?action=forums");}
		}
	}
}
?>
<?php include"includes/footer.php"; ?>