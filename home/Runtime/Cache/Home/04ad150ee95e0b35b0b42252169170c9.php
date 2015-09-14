<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>IT服务台登陆首页</title>
	
	<meta charset='utf-8' />
	<script type="text/javascript" src='/itservice/Public/boot/jquery-1.9.1.min.js'></script>
	<link rel="stylesheet" type="text/css" href="/itservice/Public/css/index/index.css">
	<script type="text/javascript" src="/itservice/Public/js/index/index.js" ></script>

	<style type="text/css">
		div.pic {
			background-image: url('/itservice/Public/image/index/bg.jpg');
			background-size:100% 100%;
		}
	</style>
	<script language="JavaScript"> 
		 function freshVerify(){  
		    //重载验证码 
		        document.getElementById('verifyImg').src= "<?php echo U('/Home/Public/verify');?>"; 
		 } 
	 </script> 
 	<script type="text/javascript">
 	function focus()
 	{
 		document.getElementById("user").focus();

 	}


 	</script>
</head>
<body onload='return focus()'>
	<div class='header'>
		
	</div>
	<div class='login'>
		<div class='pic'>
			
		</div>
		<div class='form'>
			<form action="<?php echo U('Home/Index/loginVerify');?>" method='post' name='form1' onsubmit='return isnull(form1)'>
				 <table >
				 	<tr>
				 		<td>
						用户名:<input type='text' name='username' id='user' .>
				 			
				 		</td>

				 	</tr>
				 	<tr>
				 		<td>
						密<span style='color:#F0F8FF'>空</span>码:<input type='password' name='password' />
				 			
				 		</td>
				 	</tr>
				 	<tr>
				 		<td>
				 			
						 验证码:<input type='text' name='verify' />
				 		</td>
				 	</tr>
				 	<tr>
				 		<td class='verify'>
				 			<!-- <button type='button'  id='btn' onclick="history.go(0)" >刷新</button> -->
				 			<img id='verifyImg' class='verify' src="<?php echo U('/Home/Public/verify');?>" alt='验证码' onclick="freshVerify()">
				 		</td>
				 	</tr>
				 	<tr>
				 		<td>
				 			<input type='submit' class='submit' value='提交'>
				 		</td>
				 	</tr>
				 </table>

			</form>
		</div>
		
	</div>
		<div class='footer'>
			<span class='footer'><!-- Created by DuBin with thinkphp 3.2.3 --><br>
    		Copyright DavidDu.All Rights Reserved<span>
		</div>
	
</body>
</html>