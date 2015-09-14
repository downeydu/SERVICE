<?php 
function check_verify($code, $id = ''){
	$verify = new \Think\Verify();
 	return $verify->check($code, $id);

 }

  function checkPhoneLogin(){

        if(!isset($_SESSION['login'])){
			echo "<script>alert('登陆异常,请重新登陆')</script>";
            echo "<script>window.location='/itservice/'</script>";
        }else{
        	if($_SESSION['role']!='phone' && $_SESSION['role']!='sys'){
            // echo "<script>window.location='/itservice/'</script>";
        	echo "<script>alert('登陆异常,请重新登陆')</script>";
            echo "<script>window.location='/itservice/'</script>";
        	}
        }
    }

function checkItLogin(){

        if(!isset($_SESSION['login'])){
        	echo "<script>alert('登陆异常,请重新登陆')</script>";
            echo "<script>window.location='/itservice/'</script>";
        }else{
        	if($_SESSION['role']!='it'){
            // echo "<script>window.location='/itservice/'</script>";
        	echo "<script>alert('登陆异常,请重新登陆')</script>";
            echo "<script>window.location='/itservice/'</script>";
        	}
        }
    }






 ?>