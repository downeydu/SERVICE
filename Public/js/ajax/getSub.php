<?php 
header("content-type:text/html;charset='utf-8'");
mysql_connect('localhost','root','cuug');
mysql_select_db('itservice');
mysql_query("set names utf8");
$deptno=9;
$query="select subdname,subno from subdept where deptno='{$deptno}'";
$re=mysql_query($query);



 ?>
 <select>
 	<option>请选择处室</option>
 	<?php 
 		while($row=mysql_fetch_assoc($re))
 		{
 			echo "<option name='{$row['subdname']}'>{$row['subdname']}</option>";
 		}


 	 ?>
 </select>