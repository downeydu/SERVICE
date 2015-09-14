<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
class PublicController extends Controller{
 	public function verify(){
    	
    	$verify = new Verify();
    	$verify->fontSize=18;
    	$verify->length=4;
    	$verify->useNoise=true;
    	$verify->codeSet='0123456789';
    	$verify->imageH=34;
//     	$verify->imageW='150';
        $verify->expire='100';
    	$verify->entry();
    }

    public function logout(){
        session(null);
        redirect(__APP__);
        

    }


   

   
   
	
	
}