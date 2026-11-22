<?php include"includes/header.php"; ?>
<table>
<tr>
<td><a href='index.html'>تجربة برمجة سكربت منتدى</a></td>
</tr>
</table>
<?php
if(isset($_GET['t']) &&!empty($_GET['t'])){
	$t_id = filter_var(stripslashes($_GET['t']), FILTER_SANITIZE_NUMBER_INT);
	$up = mysqli_query($config,"update therads set therad_veiws = therad_veiws + 1 where therad_id='$t_id'");
	$query = mysqli_query($config,"select * from therads where therad_id='$t_id'");
	$fetch = mysqli_fetch_object($query);
	if(mysqli_num_rows($query) !=0){
	    $therad_title =	wordwrap($fetch->therad_title,800, " ",true);
	    $therad_text =	wordwrap($fetch->therad_text,800, " ",true);
			echo"
			<br>
			<table style='width:30%;'>
			<tr>
			<td><a href='reply.php?action=add&t=$t_id'>إضافة رد</a></td>
			</tr>
			</table>
			";
			echo"
			<table>
			<tr>
			<td>$therad_title</td>
			</tr>
			</table>
			<table style='width:30%; margin-right:70%;'>
			<tr>
			<td><a href='thread.php?action=edit&t=$t_id'>تعديل الموضوع</a></td>
			<td><a href='thread.php?action=delete&confirm=$t_id'>حذف الموضوع</a></td>
			</tr>
			</table>
			<table>			
			<tr>
			<td>$therad_text</td>
			</tr>
			</table>
			";
			echo"
			<table style='width:30%;'>
			<tr>
			<td><a href='reply.php?action=add&t=$t_id'>إضافة رد</a></td>
			</tr>
			</table>
			";
			$query_r = mysqli_query($config,"select * from replys where therad_id='$t_id'");
			if(mysqli_num_rows($query_r) !="0"){
				echo'
				<br>
					<table>
					<tr>
					<td>الردود</td>
					</tr>
					</table>
				';
				while($therad_r = mysqli_fetch_object($query_r)){
					$reply_text =	wordwrap($therad_r->reply_text,30, " ",true);
					echo"
						<table style='width:30%; margin-right:70%;'>
						<tr>
						<td><a href='reply.php?action=edit&r=$therad_r->reply_id'>تعديل الرد</a></td>
						<td><a href='reply.php?action=delete&confirm=$therad_r->reply_id'>حذف الرد</a></td>
						</tr>
						</table>
					";
					echo"
					<table id='$therad_r->reply_id'>
					<tr>
					<td>$reply_text</td>
					</tr>
					</table>
					<br>
					";
				}
			}
	}else{header("Location:index.html");}
}
else{header("Location:index.html");}

?>
<?php include"includes/footer.php"; ?>