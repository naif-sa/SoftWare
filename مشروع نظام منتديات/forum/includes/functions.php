<?php
function masseg($masseg,$page)
{
	echo "
<table>
	<tr>
		<td>
   $masseg
		</td>
	</tr>
</table>
";
echo "<meta http-equiv='refresh' content='4;URL=$page' />";
exit;
}
function confirm($Message,$Confirm,$Cancel) {
echo "
<table>
<tr><td>تأكيد</td></tr>
<tr>
<td>$Message</td>
</tr>
</table>
<table>
<tr>
<td><a href='$Cancel'><button class='button-panel'>لا</button></a></td>
<td><a href='$Confirm'><button class='button-panel'>نعم</button></a></td>
</tr>
</table>
";
exit;
}
?>