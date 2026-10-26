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
function import($masseg1,$masseg2,$page){
echo "
<table>
<tr><td>
<div id='masseg'>$masseg1</div>
<script>
setTimeout(() => {document.getElementById('masseg').innerText = '$masseg2';}, 3000);
</script>
</td></tr></table><style>
.animated-text {
display: inline-block;
animation: rotateAnimation 3s infinite; opacity: 1;}
.color {color: yellow;font-weight:bold;}
@keyframes rotateAnimation {
0% {transform: rotate(0deg);}
25% {transform: rotate(180deg);}
50% {transform: rotate(180deg);}
75% {transform: rotate(360deg);}
100% {transform: rotate(360deg);}
}</style><table id='text'>
<tr><td>
<br><br>
<div class='animated-text'>[<span class='color'>::::::::::::::::</span>]</div>
<br><br><br></td></tr></table>
<script>setTimeout(() => {document.getElementById('text').style.opacity = '0';}, 3000);</script>
<meta http-equiv='refresh' content='5;URL=$page' />
";
exit;
}
?>