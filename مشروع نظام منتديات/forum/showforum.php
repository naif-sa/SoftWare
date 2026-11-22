<?php include"includes/header.php"; ?>
<table>
<tr>
<td><a href='index.html'>تجربة برمجة سكربت منتدى</a></td>
</tr>
</table>
<?php
if(isset($_GET['f']) &&!empty($_GET['f'])){
	$forum_id = filter_var(stripslashes($_GET['f']), FILTER_SANITIZE_NUMBER_INT);
	$info_forum = mysqli_query($config,"select * from forums where forum_active='1' and forum_id='$forum_id'");
	############################################### بداية جلب المنتيديات الفرعية
if(mysqli_num_rows($info_forum) != "0"){
    $query_forum_is = mysqli_query($config,"select * from forums where forum_is='$forum_id'");
    if(mysqli_num_rows($query_forum_is) != "0"){
        echo '
        <table>
        <tr>
        <td width="40%">المنتديات الفرعية</td>
        </tr>
        </table>			
        <table>			
        <tr>
        <td width="40%">المنتدى</td>
        <td>آخر مشاركة</td>
        <td width="40">المواضيع</td>
        <td width="40">المشاركات</td>
        </tr>
        </table>
        ';

        while($forum_is = mysqli_fetch_object($query_forum_is)){
            $num_thread = mysqli_num_rows(mysqli_query($config,"select * from therads where forum_id='$forum_is->forum_id'"));
            $num_reply = mysqli_num_rows(mysqli_query($config,"select * from replys where forum_id='$forum_is->forum_id'"));
            $limit_reply = mysqli_query($config,"select * from replys where forum_id='$forum_is->forum_id' order by reply_id desc limit 1");
			if(mysqli_num_rows($limit_reply) > 0 ){
			$fetch = mysqli_fetch_object($limit_reply);
			$repm = mb_substr($fetch->reply_text,0,20,'UTF-8') . '...';
			$repm = "<a href='t$fetch->therad_id.html#$fetch->reply_id'>$repm</a>";
			}else{
			$repm = 'لا يوجد مشاركات';
			}
            echo "
            <table>
            <tr>
            <td width='40%'><a href='f$forum_is->forum_id.html'>$forum_is->forum_title</a></td>
            <td>$repm</td>
            <td width='80'>$num_thread</td>
            <td width='48'>$num_reply</td>
            </tr>
            </table>
            ";
        }
        echo '
        <table>
        <tr>
        <td width="40%">مواضيع المنتدى</td>
        </tr>
        </table>
        ';
    }
		########################################### نهاية جلب المنتديات الفرعية
		echo"
		<table style='width:30%;'>
		<tr>
		<td><a href='thread.php?action=add&f=$forum_id'>موضوع جديد</a></td>
		</tr>
		</table>
		<table>
		<tr>
		<td width='50%'>الموضوع</td>
		<td>اخر مشاركة</td>
		<td width='40'>المشاهدات</td>
		</tr>
		";
		$get_therad = mysqli_query($config,"select * from therads where forum_id='$forum_id' order by therad_id desc");
		if(mysqli_num_rows($get_therad) !="0"){
			while($therad = mysqli_fetch_object($get_therad)){
				$qr = mysqli_query($config,"select * from replys where therad_id='$therad->therad_id' order by reply_id desc limit 1");
				$therad_title = mb_substr($therad->therad_title,'0','40','UTF-8').'.....';
				if(mysqli_num_rows($qr) !="0"){
					$fet = mysqli_fetch_object($qr);
					$reply = "<a href='t$therad->therad_id.html#$fet->reply_id'>".mb_substr($fet->reply_text,'0','20','UTF-8').'.....</a>';
				}else{$reply = "لا يوجد مشاركة";}
				echo"
				<td width='50%'><a href='t$therad->therad_id.html'>$therad_title</a></td>
				<td>$reply</td>
				<td width='40'>$therad->therad_veiws</td>
				</tr>
				";
			}
			echo "
			
					<table style='width:30%;'>
		<tr>
		<td><a href='thread.php?action=add&f=$forum_id'>موضوع جديد</a></td>
		</tr>
		</table>
			";
		}
		else{
		echo'
		<tr>
		<td>لا يوجد مواضيع في هذا المنتدى</td>
		</tr>';
		}
		echo'</table>';
	}else{header("Location:index.html");}
}
else{header("Location:index.html");}
?>
<?php include"includes/footer.php"; ?>