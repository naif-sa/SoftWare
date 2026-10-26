<?php include"includes/header.php"; ?>
<table>
<tr>
<td><a href='index.html'>تجربة برمجة سكربت منتدى</a></td>
</tr>
</table>
<table>
<tr>
<td width='40%'>المنتدى</td>
<td>آخر مشاركة</td>
<td width="130">مجموع المواضيع</td>
<td width="130">مجموع المشاركات</td>
</tr>
</table>
<?php
$query = mysqli_query($config,"select * from forums where forum_is='0' and forum_active='1' order by forum_order asc");
if(mysqli_num_rows($query) !=0){
	while($forum = mysqli_fetch_object($query)){
		echo"
		<table>
		<tr>
		<td><a href='f$forum->forum_id.html'>$forum->forum_title</a></td>
		</tr>
		<tr>
		<td style='padding:2px; font-size:14px;'>$forum->forum_desc</td>
		</tr>
		</table>
		";
		$query_is = mysqli_query($config,"select * from forums where forum_is='$forum->forum_id' and forum_active='1' order by forum_order asc");
		while($forum_is = mysqli_fetch_object($query_is)){
			$num_thread = mysqli_num_rows(mysqli_query($config,"select * from therads where forum_id='$forum_is->forum_id'"));
			$num_all_thread = mysqli_num_rows(mysqli_query($config,"select * from therads where forum_id='$forum->forum_id'"));
			$all_num_t = $num_thread+$num_all_thread;
			$num_reply = mysqli_num_rows(mysqli_query($config,"select * from replys where forum_id='$forum_is->forum_id'"));
			$all_num_reply = mysqli_num_rows(mysqli_query($config,"select * from replys where forum_id='$forum->forum_id'"));
			$all_num_r     = $num_reply+$all_num_reply;
			$limit_reply = mysqli_query($config,"select * from replys where forum_id='$forum_is->forum_id' order by reply_id desc limit 1");
				if(mysqli_num_rows($limit_reply) > 0 ){
				$fetch = mysqli_fetch_object($limit_reply);
				$repm = mb_substr($fetch->reply_text,0,20,'UTF-8') . '...';
				$repm = "<a href='t$fetch->therad_id.html#$fetch->reply_id'>$repm</a>";
			}else{
				$repm = 'لا يوجد مشاركات';
			}
			echo"
		<table>
		<tr>
		<td width='40%'><a href='f$forum_is->forum_id.html'>$forum_is->forum_title</a></td>
		<td>$repm</td>
		<td width='130'>$all_num_t</td>
		<td width='130'>$all_num_r</td>
		</tr>
		</table>
		</table>
			";
		}
	}
	
}
else{echo'
<table>
<tr>
<td>لا يوجد منتديات حاليا</td>
</tr>
</table>
';}
?>
<?php include"includes/footer.php"; ?>