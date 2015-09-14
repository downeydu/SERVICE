<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
use Think\Page;

class IndexController extends Controller {
    public function index(){
    	$this->display();
    }
    
    public function verify(){
    	
    	$verify = new Verify();
    	$verify->fontSize=20;
    	$verify->length=4;
//     	$verify->useNoise=true;
    	$verify->codeSet='0123456789';
    	$verify->imageH='39';
//     	$verify->imageW='150';
    	$verify->entry();
    }


    //登陆验证方法,不同用户不同跳转
    public function loginVerify(){
        if(!IS_POST){
            $this->error('操作错误!!!',__APP__);
        }
        $vc=$_POST['verify'];
        if(!check_verify($vc))
        {
            $this->error('验证码不正确请重新输入');
            echo "<script>location='__APP__';</script>";
            exit;
        }
        $username=I('post.username','','htmlspecialchars'); // 采用htmlspecialchars方法对
        $password=md5(md5(I('password')));
        $user=M("user");
        $re=$user->where("user='{$username}'")->find();
        if(!$re || $re['password'] != $password)
        {
            $this->error('用户名或密码错误');

        }else{
            session('id',$re['id']);
            session('name',$re['name']);
            session('auth',$re['auth']);
            session('role',$re['role']);
            session('login','yes');
            if($re['role']=='it'){
            $this->redirect('/Home/Support/index','',2, '<h2>登陆成功,页面跳转中...</h2>');     
            }else if($re['role']=='it'){
                $this->success("登陆成功","Home/Support/index");
            }else{
                $this->success("登陆成功","servicePhone");    
            }
        }


    }

    public function servicePhone(){
        checkPhoneLogin();
        $this->assign('title','话务员首页');
        //vip 领导的任务
        $m=M('mission');
        $vipmnum=$m->where("(dname='公司领导' or subdname='部门领导') and step!='save' ")->count();
        $this->assign('vipmnum',$vipmnum);

        //临时保存的任务
        $today=date('Y-m-d');
        $psmnum=$m->where("step='save'")->count();
        $this->assign('psmnum',$psmnum);

        //已经发布待接收的任务
        $himnum=$m->where("step='issue'")->count();
        $this->assign('himnum',$himnum);

        //已经接受的任务
        $hamnum=$m->where("step='accept'")->count();
        $this->assign('hamnum',$hamnum);

        //等待评价的任务
        $wjmnum=$m->where("step='judge'")->count();
        $this->assign('wjmnum',$wjmnum);


       // 已经完成的任务
        $hfmnum=$m->where("step='finish'")->count();
        $this->assign('hfmnum',$hfmnum);



        $this->display();
    }

    public function service(){

         $this->display();
    }

     public function distMission(){
        checkPhoneLogin();

        $applynum='M'.date('Ymd')."00";
        $today=date("Y-m-d");
        $this->assign('applynum',$applynum);
        $this->assign('title','任务分配');
        $dept=M('dept');
        $list = $dept->select();
        $this->assign('d',$list);


        $dist=M('user');
        $list2=$dist->where("role='it'")->select();
        $this->assign('u',$list2);
        
        $Mnum = M('mission');
        $num=$Mnum->where("optime='{$today}'")->count();
        $num++;
        $num=$applynum.$num;
        $this->assign('applynum',$num);

        $now=date('Y-m-d');
        $this->assign('now',$now);
        $weekarray=array("日","一","二","三","四","五","六");
        $week="星期".$weekarray[date('w')];
        $this->assign('week',$week);



        $this->display();
    }

    //利用ajax 获取部门的处室
    public function getSub(){
        checkPhoneLogin();
        if(!IS_GET)
        {
            $this->error('ERROR PAGE!!',logout());
        }
        $deptno=$_GET['q'];
        $subdept = M('subdept');
        $sub=$subdept->where("deptno={$deptno}")->select();
        $this->assign('subdept',$sub);
        $this->display();
    }

    //保存未发布的任务  硬件
    public function hwSave(){
        checkPhoneLogin();
        if(!IS_POST)
        {
            $this->error('ERROR PAGE!!',__APP__);
        }
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['tp']=$_POST['tp'];
        $data['subdname']=$_POST['subdname'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['status']=$_POST['status'];
        $data['distribute']=$_POST['distribute'];
        $data['status']='创建中';
        $data['ctime']=date('Y-m-d H:i:s');
        $data['optime']=date('Y-m-d');
        $data['step']='save';
        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];

        $hwdata['hwtype']=I('hwtype');
        $hwdata['applynum']=I('applynum');
        $hwdata['tinfo']=I('tinfo');
        $h=M('hwp');



        $tmpsave = M('mission');

        if($tmpsave->add($data) && $h->add($hwdata))
        {
            $this->success('保存成功',U("hwtDisplay",array('applynum'=>$_POST['applynum'])));
        }

    }



        //保存未发布的任务 软件
    public function sfSave(){
        checkPhoneLogin();
        if(!IS_POST)
        {
            $this->error('ERROR PAGE!!',__APP__);
        }
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['tp']=$_POST['tp'];
        $data['subdname']=$_POST['subdname'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['status']=$_POST['status'];
        $data['distribute']=$_POST['distribute'];
        $data['status']='创建中';
        $data['ctime']=date('Y-m-d H:i:s');
        $data['optime']=date('Y-m-d');
        $data['step']='save';
        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];

        $sfdata['hwtype']=I('hwtype');
        $sfdata['applynum']=I('applynum');
        $sfdata['tinfo']=I('tinfo');
        $sf=M('sfp');



        $tmpsave = M('mission');

        if($tmpsave->add($data) && $sf->add($sfdata))
        {
            $this->success('保存成功',U("sftDisplay",array('applynum'=>$_POST['applynum'])));
        }

    }

    //资产分配 历史保存
    public function ppSave(){
        checkPhoneLogin();
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['tp']=$_POST['tp'];
        $data['subdname']=$_POST['subdname'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['status']=$_POST['status'];
        $data['distribute']=$_POST['distribute'];
        $data['status']='创建中';
        $data['ctime']=date('Y-m-d H:i:s');
        $data['optime']=date('Y-m-d');
        $data['step']='save';
        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];

        $protype=I('protype');
        $ppdata['protype']=implode(',', $protype);
        $ppdata['distreason']=I('distreason');
        $ppdata['applynum']=I('applynum');
        $p=M('pp');



        $tmpsave = M('mission');

        if($tmpsave->add($data) && $p->add($ppdata))
        {
            $this->success('任务保存成功',U("pptDisplay",array('applynum'=>$_POST['applynum'])));
        }

    }

    public function cpSave(){
        checkPhoneLogin();
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['tp']=$_POST['tp'];
        $data['subdname']=$_POST['subdname'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['status']=$_POST['status'];
        $data['distribute']=$_POST['distribute'];
        $data['status']='创建中';


        $data['ctime']=date('Y-m-d H:i:s');
        $data['optime']=date('Y-m-d');
        $data['step']='save';
        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];

        $date=I('cdate');
        $h=I('ctimeh');
        $m=I('ctimem');
        $time=$h.":".$m.":"."00";
        $convtime=$date." ".$time;
        $cpdata['convtime']=$convtime;
        $cpdata['applynum']=I('applynum');
        $cpdata['isd']=I('isd');
        $cpdata['ispc']=I('ispc');
        $cpdata['convtype']=I('convtype');
        $cpdata['croom']=I('croom');
        $cpdata['participant']=I('participant');
        $p=M('cp');



        $tmpsave = M('mission');

        if($tmpsave->add($data) && $p->add($cpdata))
        {
            $this->success('任务保存成功',U("cptDisplay",array('applynum'=>$_POST['applynum'])));
        }
    }

    //更改保存的任务
    public function saveM(){
        checkPhoneLogin();
        if(!IS_POST)
        {
            $this->error('ERROR PAGE!!',__APP__);
        }
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['subdname']=$_POST['subdname'];
        $applynum=I('applynum');
        $data['room']=$_POST['room'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['step']='save';
        $data['status']=I('status');
        $data['distribute']=$_POST['distribute'];
        $data['tinfo']=$_POST['tinfo'];
        $data['ctime']=$_POST['ctime'];
        $data['status']=I('status');
        $data['ctime']=date('Y-m-d H:i:s');

        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];


        $m = M('mission');

        if($m->where("applynum='{$applynum}'")->save($data))
        {
            $this->success('保存成功',U("tDisplay",array('applynum'=>$_POST['applynum'])));
        }

    }

    
    public function hwIssue(){
        checkPhoneLogin();
        if(!IS_POST)
        {
            $this->error('ERROR PAGE!!',__APP__);
        }
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['tp']=$_POST['tp'];
        $data['subdname']=$_POST['subdname'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['status']=$_POST['status'];
        $data['distribute']=$_POST['distribute'];
        $data['status']='已发布';
        $data['ctime']=date('Y-m-d H:i:s');
        $data['optime']=date('Y-m-d');
        $data['step']='issue';
        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];

        $hwdata['hwtype']=I('hwtype');
        $hwdata['applynum']=I('applynum');
        $hwdata['tinfo']=I('tinfo');
        $h=M('hwp');



        $tmpsave = M('mission');

        if($tmpsave->add($data) && $h->add($hwdata))
        {
            $this->success('任务发布成功',U("hwiDisplay",array('applynum'=>$_POST['applynum'])));
        }


    }

    public function sfIssue(){
        checkPhoneLogin();
        if(!IS_POST)
        {
            $this->error('ERROR PAGE!!',__APP__);
        }
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['tp']=$_POST['tp'];
        $data['subdname']=$_POST['subdname'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['status']=$_POST['status'];
        $data['distribute']=$_POST['distribute'];
        $data['status']='已发布';
        $data['ctime']=date('Y-m-d H:i:s');
        $data['optime']=date('Y-m-d');
        $data['step']='issue';
        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];

        $sfdata['hwtype']=I('hwtype');
        $sfdata['applynum']=I('applynum');
        $sfdata['tinfo']=I('tinfo');
        $sfdata['sftype']=I('sftype');
        $s=M('sfp');



        $tmpsave = M('mission');

        if($tmpsave->add($data) && $s->add($sfdata))
        {
            $this->success('任务发布成功',U("sfiDisplay",array('applynum'=>$_POST['applynum'])));
        }


    }

    public function ppIssue(){
        checkPhoneLogin();
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['tp']=$_POST['tp'];
        $data['subdname']=$_POST['subdname'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['status']=$_POST['status'];
        $data['distribute']=$_POST['distribute'];
        $data['status']='已发布';
        $data['ctime']=date('Y-m-d H:i:s');
        $data['optime']=date('Y-m-d');
        $data['step']='issue';
        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];

        $protype=I('protype');
        $ppdata['protype']=implode(',', $protype);
        $ppdata['distreason']=I('distreason');
        $ppdata['applynum']=I('applynum');
        $p=M('pp');



        $tmpsave = M('mission');

        if($tmpsave->add($data) && $p->add($ppdata))
        {
            $this->success('任务发布成功',U("ppiDisplay",array('applynum'=>$_POST['applynum'])));
        }


    }


    //会议支持服务 发布
    public function cpIssue(){
        checkPhoneLogin();
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['tp']=$_POST['tp'];
        $data['subdname']=$_POST['subdname'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['status']=$_POST['status'];
        $data['distribute']=$_POST['distribute'];
        $data['status']='已发布';
        $data['ctime']=date('Y-m-d H:i:s');
        $data['optime']=date('Y-m-d');
        $data['step']='issue';
        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];

        $date=I('cdate');
        $h=I('ctimeh');
        $m=I('ctimem');
        $time=$h.":".$m.":"."00";
        $convtime=$date." ".$time;
        $cpdata['convtime']=$convtime;
        $cpdata['applynum']=I('applynum');
        $cpdata['isd']=I('isd');
        $cpdata['ispc']=I('ispc');
        $cpdata['convtype']=I('convtype');
        $cpdata['croom']=I('croom');
        $cpdata['participant']=I('participant');
        $p=M('cp');



        $tmpsave = M('mission');

        if($tmpsave->add($data) && $p->add($cpdata))
        {
            $this->success('任务发布成功',U("cpiDisplay",array('applynum'=>$_POST['applynum'])));
        }
    }


    public function hwiDisplay(){
        checkPhoneLogin();
        if(!IS_GET)
        {
            $this->error('ERROR PAGE!!',__APP__);
            exit;
        }
        $applynum=I('applynum');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.ctime,h.hwtype,h.tinfo from mission m,hwp h where m.applynum=h.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已发布硬件任务');

        if($mission[0]['distribute']=='all')
        {
            $this->assign('distribute','所有人');
        }else{
            $this->assign('distribute',$mission[0]['distribute']);

        }
        $this->assign('row',$mission);
        $this->display();
    }




    //任务发布
    public function mIssue(){
        checkPhoneLogin();
        
        $data['name']=$_POST['name'];
        $data['phone']=$_POST['phone'];
        $data['tp']=$_POST['tp'];
        $data['subdname']=$_POST['subdname'];
        $data['ptype']=$_POST['ptype'];
        $data['creator']=$_POST['creator'];
        $data['applynum']=$_POST['applynum'];
        $data['status']=$_POST['status'];
        $data['distribute']=$_POST['distribute'];
        $data['status']='已发布';
        $data['ctime']=date('Y-m-d H:i:s');
        $data['optime']=date('Y-m-d');
        $data['step']='issue';
        $deptno=I('dname');
        $dept = M('dept');
        $d=$dept->where("deptno={$deptno}")->select();
        $data['dname']=$d[0]['dname'];

        $hwdata['hwtype']=I('hwtype');
        $hwdata['applynum']=I('applynum');
        $hwdata['tinfo']=I('tinfo');
        $h=M('hwp');

        $tmpsave = M('mission');

        if($tmpsave->add($data) && $h->add($hwdata))
        {
            $this->success('任务发布成功',U("iDisplay",array('applynum'=>$_POST['applynum'])));
        }else{
            $this->error("发布失败");
        }
    }

   //保存的任务,显示
     public function tDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $this->assign('applynum',$applynum);
        $m = M('mission');
        $mission = $m->where("applynum='{$applynum}'")->select();
        $this->assign('title','已保存任务');
        if($mission[0]['distribute']=='all')
        {
            $this->assign('distribute','所有人');
        }else{
            $this->assign('distribute',$mission[0]['distribute']);

        }

        $this->assign('row',$mission);
        $this->display();

    }

     //保存的硬件任务,显示 硬件
     public function hwtDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,h.hwtype,h.tinfo from mission m,hwp h where m.applynum=h.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已保存硬件任务');

        if($mission[0]['distribute']=='all')
        {
            $this->assign('distribute','所有人');
        }else{
            $this->assign('distribute',$mission[0]['distribute']);

        }
        $this->assign('row',$mission);
        $this->display();

    }

    //保存的硬件任务,显示 软件
     public function sftDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,s.sftype,s.tinfo from mission m,sfp s where m.applynum=s.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已保存软件任务');

        $this->assign('row',$mission);
        $this->display();

    }

    public function pptDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,p.distreason,p.protype,p.brand,p.sn from mission m,pp p where m.applynum=p.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','保存的资产分配任务');

        $this->assign('row',$mission);
        $this->display();
    }

    //会议支持历史保存页面
    public function cptDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $this->assign('title','保存的会议支持任务');
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,c.convtime,c.isd,c.ispc,c.convtype,c.croom,c.participant from mission m,cp c where m.applynum=c.applynum and m.applynum = '{$applynum}'");

        $this->assign('row',$mission);
        $this->assign('date',date('Y-m-d'));
        $this->display();
    }

        //发布的软件任务,显示 软件
     public function sfiDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,s.sftype,s.tinfo from mission m,sfp s where m.applynum=s.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已发布软件任务');

        $this->assign('row',$mission);
        $this->display();

    }

       //保存的资产分配任务,显示 软件
     public function ppiDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,p.distreason,p.protype,p.brand,p.sn from mission m,pp p where m.applynum=p.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已发布资产分配任务');

        $this->assign('row',$mission);
        $this->display();

    }

    //发布的会议支持任务
      public function cpiDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,c.convtime,c.isd,c.ispc,c.convtype,c.croom,c.participant from mission m,cp c where m.applynum=c.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已发布会议支持任务');

        $this->assign('row',$mission);
        $this->display();

    }



    //保存的任务,发布
    public function tIssue(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $this->assign('applynum',$applynum);

        $ptype=I('ptype');
        // echo $applynum." ".$ptype;
        // exit;
        $data['status']='已发布';
        $data['step']='issue';
        $m = M('mission');
        if($m->where("applynum='{$applynum}'")->save($data))
        {
            if($ptype=='硬件问题')
            {
                $this->success("任务发布成功",U("hwiDisplay",array('applynum'=>$applynum)));
            }
            else if($ptype=='软件问题')
            {
                $this->success("任务发布成功",U("sfiDisplay",array('applynum'=>$applynum)));

            }
            else if($ptype=='资产分配')
            {
                $this->success("任务发布成功",U("ppiDisplay",array('applynum'=>$applynum)));

            }
            else if($ptype=='会议支持')
            {
                $this->success("任务发布成功",U("cpiDisplay",array('applynum'=>$applynum)));

            }
        }else{
            $this->error("发布失败");

        }
    }


    //发布的任务,显示
    public function iDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $this->assign('applynum',$applynum);
        $m = M('mission');
        $mission = $m->where("applynum='{$applynum}'")->select();
        $this->assign('title','已发布任务');
        if($mission[0]['distribute']=='all')
        {
            $this->assign('distribute','所有人');
        }else{
            $this->assign('distribute',$mission[0]['distribute']);

        }

        $this->assign('row',$mission);
        $this->display();

    }


    public function tUpdate(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $dept=M('dept');
        $list = $dept->select();
        $this->assign('d',$list);


        $dist=M('user');
        $list2=$dist->where("role='it'")->select();
        $this->assign('u',$list2);

        $m=M('mission');
        $mission =$m->where("applynum='{$applynum}'")->select();
        if($mission[0]['distribute']=='all')
        {
            $this->assign('distribute','所有人');
        }else{
            $this->assign('distribute',$mission[0]['distribute']);
        }
        $this->assign('row',$mission);
        $this->assign('title','编辑任务');
        $this->display();

    }

    



    public function vipMission(){
        checkPhoneLogin();
        $this->assign('title','领导人物');
        $m=D('mission');
        $count=$m->where("(dname='公司领导' or subdname='部门领导') and step!='save' ")->count();
        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $show=$page->show();
        $vipm=$m->where("dname='公司领导' or subdname='部门领导' and step!='save' ")->limit($page->firstRow.','.$page->listRows)->select();

        //计算个数
        $vsum=$count;
        $vasum = $m->where("(dname='公司领导' or subdname='部门领导') and step in ('accept','finish') ")->count();
        $vfsum = $m->where("(dname='公司领导' or subdname='部门领导') and step = 'finish'")->count();
        $this->assign('vsum',$vsum);
        $this->assign('vasum',$vasum);
        $this->assign('vfsum',$vfsum);

        $this->assign('frate',100*sprintf("%.4f",$vfsum/$vasum)."%");

        $this->assign('vipm',$vipm);
        $this->assign('page',$show);
        $this->display();
    }

    public function jDisplay(){
        checkPhoneLogin();
        $this->assign('title','等待评价');
        $applynum=I('applynum');
        // echo $applynum;
        // exit;
        $jm=M('mission');
        $jmission=$jm->where("applynum='{$applynum}'")->select();
         if($jmission[0]['distribute']=='all')
        {
            $this->assign('distribute','所有人');
        }else{
            $this->assign('distribute',$jmission[0]['distribute']);
        }
        $this->assign('row',$jmission);
        $this->display();
    }


    //接受的任务展示
    public function aDisplay(){
        checkPhoneLogin();
        $this->assign('title','接受的任务');
        $applynum=I('applynum');
        $m=M('mission');
        $am=$m->where("applynum='{$applynum}'")->select();
         if($am[0]['distribute']=='all')
        {
            $this->assign('distribute','所有人');
        }else{
            $this->assign('distribute',$am[0]['distribute']);

        }
        $this->assign('row',$am);


        $this->display();
    }


    public function fMission(){
        checkPhoneLogin();
        // if(!IS_GET)
        // {
        //     $this->error("操作不合法");
        //     exit;
        // }
        $applynum=I('applynum');
        $grade=I('grade');
        $data['grade']=$grade;
        $data['status']='已完成';
        $data['ftime']=date('Y-m-d H:i:s');
        $data['step']='finish';
        $fm=M('mission');
        if($fm->where("applynum='{$applynum}'")->save($data))
        {
            $this->success("任务完成,谢谢",U("fDisplay",array('applynum'=>$applynum)));
        }else{
            $this->error("任务提交失败");
        }
    }

    public function fDisplay(){
        checkPhoneLogin();
        $this->assign('title','任务已完成');
        $applynum=I('applynum');
        $fm=M('mission');
        $fmission=$fm->where("applynum='{$applynum}'")->select();
         if($fmission[0]['distribute']=='all')
        {
            $this->assign('distribute','所有人');
        }else{
            $this->assign('distribute',$fmission[0]['distribute']);
        }
        $this->assign('row',$fmission);
        $this->display();

    }


    //过去保存的任务
    public function psMission(){
        checkPhoneLogin();
        $this->assign('title','保存的任务');
        $m=M('mission');
        $count=$m->where("step='save'")->count();
        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $psm=$m->where("step='save'")->limit($page->firstRow.','.$page->listRows)->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('psm',$psm);
        $this->display();
    }

    //已经发布的任务
    public function hiMission(){
        checkPhoneLogin();
        $this->assign('title','保存的任务');
        $m=M('mission');
        $count=$m->where("step='issue'")->count();
        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $him=$m->where("step='issue'")->order('ctime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('him',$him);
        $this->display();
    }

    //已经接受的任务
    public function haMission(){
        checkPhoneLogin();
        $this->assign('title','接受的任务');
        $m=M('mission');
        $count=$m->where("step='accept'")->count();
        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $ham=$m->where("step='accept'")->order('ctime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('ham',$ham);
        $this->display();
    }

    //等待评价的任务
    public function wjMission(){
        checkPhoneLogin();
        $this->assign('title','等待评价的任务');
        $m=M('mission');
        $count=$m->where("step='judge'")->count();
        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $wjm=$m->where("step='judge'")->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('wjm',$wjm);
        $this->display();
    }

    //已经完成的任务
    public function hfMission(){
        checkPhoneLogin();
        $this->assign('title','已经完成的任务');
        $m=M('mission');
        $count=$m->where("step='finish'")->count();
        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $hfm=$m->where("step='finish'")->order('ctime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('hfm',$hfm);
        $this->display();
    }

    //所有任务统计
    public function allStatic(){
        checkPhoneLogin();
       $m=M('mission');
       //计算每个类别发布的总数
        $hwn=$m->where("ptype='硬件问题' and step!='save' ")->count();
        $sfn=$m->where("ptype='软件问题' and step!='save' ")->count();
        $cpn=$m->where("ptype='会议支持' and step!='save' ")->count();
        $ppn=$m->where("ptype='资产分配' and step!='save' ")->count();

        $isum = $hwn+$sfn+$cpn+$ppn;
        $this->assign("isum",$isum);

        //计算每个类别接搜的总数
        $solver=session('name');
        $hwan=$m->where("ptype='硬件问题' and step in ('accept','finish') ")->count();
        $sfan=$m->where("ptype='软件问题' and step in ('accept','finish') ")->count();
        $ppan=$m->where("ptype='资产分配' and step in ('accept','finish') ")->count();
        $cpan=$m->where("ptype='会议支持' and step in ('accept','finish') ")->count();
        $asum=$hwan+$sfan+$ppan+$cpan;
        $this->assign("asum",$asum);

        //计算每个类别完成的总数
        $hwfn=$m->where("ptype='硬件问题' and step = 'finish' ")->count();
        $sffn=$m->where("ptype='软件问题' and step = 'finish' ")->count();
        $ppfn=$m->where("ptype='资产分配' and step = 'finish' ")->count();
        $cpfn=$m->where("ptype='会议支持' and step = 'finish' ")->count();
        $fsum=$hwfn+$sffn+$ppfn+$cpfn;
        $this->assign("fsum",$fsum);



        $this->assign('hwfrate',100*sprintf("%.4f", $hwfn/$hwan)."%");
        $this->assign('sffrate',100*sprintf("%.4f", $sffn/$sfan)."%");
        $this->assign('cpfrate',100*sprintf("%.4f", $cpfn/$cpan)."%");
        $this->assign('ppfrate',100*sprintf("%.4f", $ppfn/$ppan)."%");

        $avfrate=sprintf("%.2f",(100*sprintf("%.4f", $hwfn/$hwan)+100*sprintf("%.4f", $sffn/$sfan)+100*sprintf("%.4f", $cpfn/$cpan)+100*sprintf("%.4f", $ppfn/$ppan))/4)."%";
        $this->assign('avfrate',$avfrate);

        //计算接受率
        $hwarate=100*sprintf("%.4f",$hwan/$hwn)."%";
        $sfarate=100*sprintf("%.4f",$sfan/$sfn)."%";
        $pparate=100*sprintf("%.4f",$ppan/$ppn)."%";
        $cparate=100*sprintf("%.4f",$cpan/$cpn)."%";

        //得出接受率平局值
        $avarate = sprintf("%.2f",($hwarate+$sfarate+$pparate+$cparate)/4)."%";
        $this->assign('avarate',$avarate);
        $this->assign('hwarate',100*sprintf("%.4f",$hwan/$hwn)."%");
        $this->assign('sfarate',100*sprintf("%.4f",$sfan/$sfn)."%");
        $this->assign('pparate',100*sprintf("%.4f",$ppan/$ppn)."%");
        $this->assign('cparate',100*sprintf("%.4f",$cpan/$cpn)."%");



        $this->assign('hwn',$hwn);
        $this->assign('cpn',$cpn);
        $this->assign('ppn',$ppn);
        $this->assign('sfn',$sfn);

        $this->assign('hwan',$hwan);
        $this->assign('sfan',$sfan);
        $this->assign('ppan',$ppan);
        $this->assign('cpan',$cpan);

        $this->assign('hwfn',$hwfn);
        $this->assign('sffn',$sffn);
        $this->assign('ppfn',$ppfn);
        $this->assign('cpfn',$cpfn);

        $this->assign('title','分类统计');
        $this->display();

    }


    public function getPType(){
        checkPhoneLogin();
        

        $name=I('q');
        $p=M('ptype');
        $ptype=$p->where("name='{$name}'")->find();
        $this->assign('name',$name);
        $this->assign('p',$ptype);

        $this->display();
    }

    public function hwptype(){
        checkPhoneLogin();
        $dist=M('user');
        $list2=$dist->where("role='it'")->select();
        $this->assign('u',$list2);

        $applynum='M'.date('Ymd')."00";
        $today=date("Y-m-d");
        $this->assign('applynum',$applynum);
        $this->assign('title','硬件问题任务');

        $dept=M('dept');
        $list = $dept->select();
        $this->assign('d',$list);

        $Mnum = M('mission');
        $num=$Mnum->where("optime='{$today}'")->count();
        $num++;
        $num=$applynum.$num;
        $this->assign('applynum',$num);

        $this->assign('ptype','硬件问题');
        $this->assign('date',date("Y-m-d"));
        $this->display();

    }

        public function sfptype(){
            checkPhoneLogin();
        $dist=M('user');
        $list2=$dist->where("role='it'")->select();
        $this->assign('u',$list2);

        $applynum='M'.date('Ymd')."00";
        $today=date("Y-m-d");
        $this->assign('applynum',$applynum);
        $this->assign('title','软件问题任务');

        $dept=M('dept');
        $list = $dept->select();
        $this->assign('d',$list);

        $Mnum = M('mission');
        $num=$Mnum->where("optime='{$today}'")->count();
        $num++;
        $num=$applynum.$num;
        $this->assign('applynum',$num);

        $this->assign('ptype','软件问题');
        $this->assign('date',date("Y-m-d"));
        $this->display();

    }


    public function pptype(){
        checkPhoneLogin();
        $dist=M('user');
        $list2=$dist->where("role='it'")->select();
        $this->assign('u',$list2);

        $applynum='M'.date('Ymd')."00";
        $today=date("Y-m-d");
        $this->assign('applynum',$applynum);
        $this->assign('title','资产分配任务');

        $dept=M('dept');
        $list = $dept->select();
        $this->assign('d',$list);

        $Mnum = M('mission');
        $num=$Mnum->where("optime='{$today}'")->count();
        $num++;
        $num=$applynum.$num;
        $this->assign('applynum',$num);

        $this->assign('ptype','资产分配');
        $this->assign('date',date("Y-m-d"));
        $this->display();

    }


    public function cptype(){
        checkPhoneLogin();
        $dist=M('user');
        $list2=$dist->where("role='it'")->select();
        $this->assign('u',$list2);

        $applynum='M'.date('Ymd')."00";
        $today=date("Y-m-d");
        $this->assign('applynum',$applynum);
        $this->assign('title','会议支持');

        $dept=M('dept');
        $list = $dept->select();
        $this->assign('d',$list);

        $Mnum = M('mission');
        $num=$Mnum->where("optime='{$today}'")->count();
        $num++;
        $num=$applynum.$num;
        $this->assign('applynum',$num);

        $this->assign('ptype','会议支持');
        $this->assign('date',date("Y-m-d"));
        $this->display();

    }

    public function sTrack(){
        checkPhoneLogin();
        $this->assign('title','追踪任务');
        $m=M('mission');
        $count=$m->where("step!='save'")->count();

        $inum=$m->where("step='issue'")->count();
        $this->assign('inum',$inum);

        $anum=$m->where("step='accept'")->count();
        $this->assign('anum',$anum);

        $fnum=$m->where("step='finish'")->count();
        $this->assign('fnum',$fnum);

        $num=$m->where("step!='save'")->count();
        $this->assign('num',$num);

        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $ham=$m->where("step!='save'")->order('ctime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('mis',$ham);
        $this->display();
    }

     public function hwfDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $name=session('name');
        $now=date('Y-m-d');

        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,h.brand,h.sn,h.treason,h.method,m.solver,m.stime,m.sfurl,h.hwtype,h.tinfo from mission m,hwp h where m.applynum=h.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已接受硬件维护任务');
        $this->assign('it',$ituser);
        $this->assign('row',$mission);
        $this->display();

    }

    public function sffDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,m.solver,m.stime,m.sfurl,s.brand,s.sn,s.treason,s.method,s.sftype,s.tinfo from mission m,sfp s where m.applynum=s.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已完成软件任务');
        $this->assign('row',$mission);
        $this->display();
    }
    public function ppfDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,m.stime,m.sfurl,m.solver,p.brand,p.sn,p.distreason,p.protype from mission m,pp p where m.applynum=p.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已完成资产分配任务');
        $this->assign('row',$mission);
        $this->display();
    }

    public function cpfDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,m.solver,m.stime,m.sfurl,c.convtime,c.isd,c.ispc,c.convtype,c.croom,c.participant from mission m,cp c where m.applynum=c.applynum and m.applynum = '{$applynum}'");
        $this->assign('title','已接受会议支持任务');
        $this->assign('row',$mission);
        $this->display();
    }

    public function aSType(){
        checkPhoneLogin();
        $ptype=I('ptype');
        $name=session('name');
        if($ptype=='硬件问题'){
            $m=M('mission');
            $count = $m->where("ptype='硬件问题' ")->count();
            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $hw=$m->where("ptype='硬件问题' ")->order('applynum desc')->limit($page->firstRow,$page->listRows)->select();

            $show=$page->show();
            $this->assign('page',$show);


            $this->assign('title','硬件支持');
            $this->assign('hw',$hw);
        }else if($ptype=='软件问题'){
            $m=M('mission');
            $count = $m->where("ptype='软件问题' ")->count();

            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $hw=$m->where(" ptype='软件问题' ")->order('applynum desc')->limit($page->firstRow,$page->listRows)->select();

            $show=$page->show();
            $this->assign('page',$show);


            $this->assign('title','软件支持');
            $this->assign('hw',$hw);
        }else if($ptype=='会议支持'){
            $m=M('mission');
            $count = $m->where(" ptype='会议支持' ")->count();

            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $hw=$m->where(" ptype='会议支持' ")->order('applynum desc')->limit($page->firstRow,$page->listRows)->select();

            $show=$page->show();
            $this->assign('page',$show);


            $this->assign('title','会议支持');
            $this->assign('hw',$hw);
        }else if($ptype=='资产分配'){
            $m=M('mission');
            $count = $m->where(" ptype='资产分配' ")->count();

            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $hw=$m->where(" ptype='资产分配' ")->order('applynum desc')->limit($page->firstRow,$page->listRows)->select();

            $show=$page->show();
            $this->assign('page',$show);


            $this->assign('title','资产分配');
            $this->assign('hw',$hw);
        }

            $this->display();

    }

    public function sPerson(){
        checkPhoneLogin();
        $this->assign("title",'按执行人');
        $m=M('mission');
        $u=M('user');
        $sol=$u->where("role='it' ")->select();
        $n=count($sol);
        //将所有it服务人员的名字放到一个数组中
        for($i=0;$i<$n;$i++){
            $name[$i]=$sol[$i]['name'];
        }

        foreach ($name as $key => $val){
            $ar[$val]=array(
                'asum'=>$m->where("solver='{$val}' and step in ('accept','finish')")->count(),
                'fsum'=>$m->where("solver='{$val}' and step='finish' ")->count(),
                'arate'=>100*sprintf("%.4f",($m->where("solver='{$val}' and step in ('finish','accept') ")->count())/($m->where("step!='save' ")->count()))."%",
                'frate'=>100*sprintf("%.4f",($m->where("solver='{$val}' and step='finish' ")->count())/($m->where("solver='{$val}' and step in ('accept','finish')")->count()))."%",
            );
        }
        // echo "<pre>";
        // print_r($ar);
        // echo "</pre>";
        // exit;


        $it = $u->where("role='it'")->select();
        $this->assign('it',$it);
        $this->assign('ar',$ar);
        $this->display();
    }

    public function sWeek(){
        checkPhoneLogin();
        $date=date('Y-m-d');  //当前日期
        $first=1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w=date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $start=date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); 
        $end=date('Y-m-d',strtotime("$start+6 days"));
        $m=M('mission');
        $count = $m->where("optime>='{$start}' and optime<='{$end}' and step in ('accept','finish')")->count();
        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $week = $m->where("optime>='{$start}' and optime<='{$end}' and step in ('accept','finish')")->order('applynum desc')->limit($page->firstRow,$page->listRows)->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('week',$week);
        $this->assign('title',"本周任务");

        $unfn = $m->where("optime>='{$start}' and optime<='{$end}' and step='accept' ")->count();
        $fn   = $m->where("optime>='{$start}' and optime<='{$end}' and step='finish' ")->count();
        $sum = $unfn+$fn;
        $rate = 100*sprintf("%.4f",$fn/$sum)."%";

        $this->assign('unfn',$unfn);
        $this->assign('fn',$fn);
        $this->assign('rate',$rate);
        $this->display();
    }

    public function sCustom(){
        checkPhoneLogin();
        $this->assign('title','自定义统计');
        $u=M('user');
        $user=$u->where("role='it' ")->select();
        $this->assign('user',$user);

        $this->display();
    }

   

    public function sTSearch(){
        checkPhoneLogin();
        if(!IS_POST){
            $this->error('操作错误');
        }
        $applynum=trim(I('applynum'));
        //搜索姓名
        if(preg_match("/^[\x7f-\xff]+$/",$applynum)){
            $name=$applynum;
            $m=M('mission');
            $apm=$m->where("name='{$name}'")->select();
            $num=$m->where("name='{$name}'")->count();
            if($num==0){
                $this->error("没有此人的服务请求");
            }else{
                $this->assign("apm",$apm);
            }

        //搜索请求编号
        }else{
            $a=substr($applynum,0,1);
            $b=substr($applynum,1);
            if ($a=='M'){
                //资产编号正确格式
                if(is_numeric($b)){
                    $m=M('mission');
                    $apm=$m->where("applynum='{$applynum}'")->select();
                    $num=$m->where("applynum='{$applynum}'")->count();
                    if($num==0){
                        $this->error("不存在此请求编号");
                    }else{
                        $this->assign('apm',$apm);
                    }
                    
                    
                }else{
                    $this->error("请输入请求编号或者申请人名字");
                }
            }else{
                    $this->error("请输入请求编号或者申请人名字");
            }
        }


        $this->display();
    }

    public function hwaDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,m.solver,h.hwtype,h.tinfo from mission m,hwp h where m.applynum=h.applynum and m.applynum = '{$applynum}'");

        $it = M('user');
        $ituser=$it->where("role='it' and name!='{$name}'")->select();
        $this->assign('title','已接受硬件维护任务');
        $this->assign('it',$ituser);
        $this->assign('row',$mission);
        $this->display();
    }

    public function sfaDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,m.solver,s.sftype,s.tinfo from mission m,sfp s where m.applynum=s.applynum and m.applynum = '{$applynum}'");
        $it = M('user');
        $ituser=$it->where("role='it' and name!='{$mission[0]['solver']}'")->select();
        $this->assign('title','已接受会议支持任务');
        $this->assign('it',$ituser);
        $this->assign('row',$mission);
        $this->display();
    }

    public function ppaDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,m.solver,p.distreason,p.protype from mission m,pp p where m.applynum=p.applynum and m.applynum = '{$applynum}'");
        $it = M('user');
        $ituser=$it->where("role='it' and name!='{$mission[0]['solver']}'")->select();
        $this->assign('title','已接受会议支持任务');
        $this->assign('it',$ituser);
        $this->assign('row',$mission);
        $this->display();

    }

    public function cpaDisplay(){
        checkPhoneLogin();
        $applynum=I('applynum');
        $name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,m.solver,c.convtime,c.isd,c.ispc,c.convtype,c.croom,c.participant from mission m,cp c where m.applynum=c.applynum and m.applynum = '{$applynum}'");
        $it = M('user');
        $ituser=$it->where("role='it' and name!='{$mission[0]['solver']}'")->select();
        $this->assign('title','已接受会议支持任务');
        $this->assign('it',$ituser);
        $this->assign('row',$mission);
        $this->display();
    }


    //将任务转交于他人
    public function change(){
        checkPhoneLogin();
        $solver=I('solver');
        $presolver = I('presolver');
        if($solver==$presolver)
        {
            $this->error("操作失败,无法转交给相同的人");
        }
        $applynum=I('applynum');
        $data['step']='issue';
        $data['status']='已发布';
        $data['solver']=$solver;
        $data['distribute']=$solver;
        $chm=M('mission');
        if($chm->where("applynum='{$applynum}'")->save($data))
        {
            $this->success("任务转交成功",U("index"));

        }else{
            $this->error("操作失败");
        }

    }

     //将任务转交于他人
    public function fchange(){
        checkPhoneLogin();
        $solver=I('solver');
        $ptype=I('ptype');
        $presolver = I('presolver');
        if($solver==$presolver)
        {
            $this->error("操作失败,无法转交给相同的人");
        }
        $applynum=I('applynum');
        $data['step']='issue';
        $data['status']='已发布';
        $data['solver']=I('solver');
        $data['distribute']=I('solver');
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // exit;
        $chm=M('mission');
        if($chm->where("applynum='{$applynum}'")->save($data))
        {
            if($ptype=='硬件问题'){
                $this->success("任务转交成功",U("hwaDisplay",array('applynum'=>$applynum)));
            }else if($ptype=='软件问题'){
                $this->success("任务转交成功",U("sfaDisplay",array('applynum'=>$applynum)));
            }else if($ptype=='资产分配'){
                $this->success("任务转交成功",U("ppaDisplay",array('applynum'=>$applynum)));
            }else if($ptype=='会议支持'){
                $this->success("任务转交成功",U("cpaDisplay",array('applynum'=>$applynum)));
            }

        }else{
            $this->error("操作失败");
        }

    }


    public function sCSearch(){
        checkPhoneLogin();
        $name=I('name');
        $start=I('start');
        $end=I('end');
        $ptype = I('ptype');
        $m=M('mission');

        $u=M('user');
        $user=$u->where("role='it' ")->select();
        $this->assign('user',$user);

        if(strtotime($start)>strtotime($end)){
            $this->error("起始时间不能大于终止时间");
        }
        if($name!='none'){
        if($ptype=='none'){
            $count=$m->where("optime>='{$start}' and optime<='{$end}' and solver='{$name}' and step in ('accept','finish')")->order("applynum asc")->count();
            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $time=$m->where("optime>='{$start}' and optime<='{$end}' and solver='{$name}' and step in ('accept','finish')")->order("applynum desc")->limit($page->firstRow,$page->listRows)->select();
            $show=$page->show();

        }else{
            $count=$m->where("optime>='{$start}' and optime<='{$end}' and solver='{$name}' and step in ('accept','finish') and ptype='{$ptype}'")->order("applynum asc")->count();
            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $time=$m->where("optime>='{$start}' and optime<='{$end}' and solver='{$name}' and step in ('accept','finish') and ptype='{$ptype}'")->order("applynum desc")->limit($page->firstRow,$page->listRows)->select();
            $show=$page->show();

        }
    }else{
        if($ptype=='none'){
            $count=$m->where("optime>='{$start}' and optime<='{$end}' and step in ('accept','finish')")->order("applynum asc")->count();
            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $time=$m->where("optime>='{$start}' and optime<='{$end}' and step in ('accept','finish')")->order("applynum desc")->limit($page->firstRow,$page->listRows)->select();
            $show=$page->show();

        }else{
            $count=$m->where("optime>='{$start}' and optime<='{$end}' and step in ('accept','finish') and ptype='{$ptype}'")->order("applynum asc")->count();
            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $time=$m->where("optime>='{$start}' and optime<='{$end}' and step in ('accept','finish') and ptype='{$ptype}'")->order("applynum desc")->limit($page->firstRow,$page->listRows)->select();
            $show=$page->show();

        }
    }
        $this->assign('time',$time);
        $this->assign('page',$show);
        $this->assign('title','时间查询');
        $this->display();
    }


    //导出自定义统计数据
    public function impStatic(){
        checkPhoneLogin();
        $name=I('name');

        $start=I('start');
        $end=I('end');
        $m=M('mission');
        if(strtotime($start)>strtotime($end)){
            $this->error("起始时间不能大于终止时间");
        }
        $m=M('mission');
        //各种问题发布总数
        $hwsum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='硬件问题' and step!='save' ")->count();
        $sfsum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='软件问题' and step!='save' ")->count();
        $ppsum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='资产分配' and step!='save' ")->count();
        $cpsum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='会议支持' and step!='save' ")->count();
        $isum = $hwsum+$sfsum+$ppsum+$cpsum;

        //此人接受的任务总数
        $hwasum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='硬件问题' and step in ('accept','finish') and solver='{$name}'")->count();
        $sfasum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='软件问题' and step in ('accept','finish') and solver='{$name}'")->count();
        $ppasum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='资产分配' and step in ('accept','finish') and solver='{$name}'")->count();
        $cpasum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='会议支持' and step in ('accept','finish') and solver='{$name}'")->count();
        $asum = $hwasum+$sfasum+$ppasum+$cpasum;

        //各种问题此人的接受率
        $hwarate = 100*sprintf("%.4f",$hwasum/$hwsum)."%";
        $sfarate = 100*sprintf("%.4f",$sfasum/$sfsum)."%";
        $pparate = 100*sprintf("%.4f",$ppasum/$ppsum)."%";
        $cparate = 100*sprintf("%.4f",$cpasum/$cpsum)."%";

        $arate = 25*(sprintf("%.4f",$hwasum/$hwsum)+sprintf("%.4f",$sfasum/$sfsum)+sprintf("%.4f",$ppasum/$ppsum)+sprintf("%.4f",$cpasum/$cpsum));
        $arate = sprintf("%.2f",$arate)."%";

        //各种问题此人的完成个数
        $hwfsum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='硬件问题' and step='finish' and solver='{$name}'")->count();
        $sffsum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='软件问题' and step='finish' and solver='{$name}'")->count();
        $ppfsum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='资产分配' and step='finish' and solver='{$name}'")->count();
        $cpfsum = $m->where("optime>='{$start}' and optime<='{$end}' and ptype='会议支持' and step='finish' and solver='{$name}'")->count();

        //各种问题此人的成功率
        $hwrate = 100*sprintf("%.4f",$hwfsum/$hwasum)."%";
        $sfrate = 100*sprintf("%.4f",$sffsum/$sfasum)."%";
        $pprate = 100*sprintf("%.4f",$ppfsum/$ppasum)."%";
        $cprate = 100*sprintf("%.4f",$cpfsum/$cpasum)."%";
        $fsum = $hwfsum+$sffsum+$ppfsum+$cpfsum;

        $frate = 100*sprintf("%.4f",$fsum/$asum)."%";

        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        import("Org.Util.PHPExcel.IComparable.php");
        import("Org.Util.PHPExcel.Style");
        import("Org.Util.PHPExcel.Style.php");
        import("Org.Util.PHPExcel.Style.Aligment.php");

        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        
        //excel 信息
        $objPHPExcel->getProperties()->setCreator("DuBin")
                         ->setLastModifiedBy("abclife-staff")
                         ->setTitle("Office 2007 XLSX Test Document")
                         ->setSubject("Office 2007 XLSX Test Document")
                         ->setDescription("static list of IT Service")
                         ->setKeywords("office 2007 openxml php")
                         ->setCategory("static list");
        //字体设置
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)
            ->setName('宋体')
            ->setSize(15)
            ->getColor()->setRGB('000000');

        $objPHPExcel->getActiveSheet()->getStyle("A9")->getFont()->setBold(false)
            ->setName('楷体')
            ->setSize(11)
            ->getColor()->setRGB('000000');


         //水平,垂直居中
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A9")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:D1'); 
        $objPHPExcel->getActiveSheet()->mergeCells('B2:D2'); 
        $objPHPExcel->getActiveSheet()->mergeCells('B3:D3'); 
        $objPHPExcel->getActiveSheet()->mergeCells('B4:D4'); 
        $objPHPExcel->getActiveSheet()->mergeCells('B5:D5'); 
        $objPHPExcel->getActiveSheet()->mergeCells('B6:D6'); 
        $objPHPExcel->getActiveSheet()->mergeCells('B7:D7'); 
        $objPHPExcel->getActiveSheet()->mergeCells('B8:D8'); 
        $objPHPExcel->getActiveSheet()->mergeCells('A9:D9'); 

        //设置行高度
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(36);
        for($j=2;$j<=8;$j++){
                $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(22);
        }
        $objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(40);

        $col=array('A','B','C','D');
        foreach($col as $key => $value){
            if($value=='A'){
                for($i=2;$i<=8;$i++){
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->getStartColor()->setARGB('FFDCDCDC');
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                }
            }
            else{
                for($i=2;$i<=8;$i++){
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->getStartColor()->setARGB('FFFFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


                }

            }
        }

        //设置列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('25');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('20');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('25');

        //表头信息
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'IT服务台--任务统计清单')
                    ->setCellValue('A2', '姓名')
                    ->setCellValue('A3', '时间段')
                    ->setCellValue('A4', '硬件问题')
                    ->setCellValue('A5', '软件问题')
                    ->setCellValue('A6', '资产分配')
                    ->setCellValue('A7', '会议支持')
                    ->setCellValue('A8', '总数和平均值')
                    ->setCellValue('A9','农银人寿保险股份有限公司 信息技术部 网络运维处');

        //写入数据库数据
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B2',"{$name}")
                    ->setCellValue('B3',"{$start}"."~"."{$end}")
                    ->setCellValue('B4',"发布总数{$hwsum}个,接受总数{$hwasum}个,完成总数{$hwfsum}个,接受率:{$hwarate},完成率:{$hwrate}")
                    ->setCellValue('B5',"发布总数{$sfsum}个,接受总数{$sfasum}个,完成总数{$sffsum}个,接受率:{$sfarate},完成率:{$sfrate}")
                    ->setCellValue('B6',"发布总数{$ppsum}个,接受总数{$ppasum}个,完成总数{$ppfsum}个,接受率:{$pparate},完成率:{$pprate}")
                    ->setCellValue('B7',"发布总数{$cpsum}个,接受总数{$cpasum}个,完成总数{$cpfsum}个,接受率:{$cparate},完成率:{$cprate}")
                    ->setCellValue('B8',"发布总数{$isum}个,接受总数{$asum}个,完成总数:{$fsum}个,接受率:{$arate},完成率:{$frate}");

        // title名
        $objPHPExcel->getActiveSheet()->setTitle("{$name}任务统计");


        // 只激活第一个index
        $objPHPExcel->setActiveSheetIndex(0);

        //不保存直接直接下载下来
        $fileName="{$name}任务统计{$start}~{$end}.xls";
        $fileName = iconv("utf-8", "gb2312", $fileName);
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        Header ( "Content-type: application/octet-stream" );  
        Header ( "Accept-Ranges: bytes" );  
        header("Content-Disposition: attachment;filename=".$fileName);
        header('Cache-Control: max-age=0');



        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;




    }
        public function SPri(){
            checkPhoneLogin();
            if(!IS_GET){
                $this->error("你的操作有误");
            }

            $solver = I('name');
            $m=M('mission');

            $count = $m->where("solver='{$solver}' and step in ('finish','accept')")->count();
            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $pri = $m->where("solver='{$solver}' and step in ('finish','accept')")->limit($page->firstRow,$page->listRows)->select();
            $show=$page->show();
            $this->assign('page',$show);
            $this->assign('pri',$pri);

            $this->assign('title',$solver);
            $this->display();
        }

        public function sDname(){
            checkPhoneLogin();
            $m=M('mission');
            $mn = $m->field("dname ,count(applynum) as dnum")->where("step in('accept','finish')")->group('dname')->select();
            $count = count($mn);
            $page=new Page($count,15);
            $page->setConfig('first','首页');
            $page->setConfig('prev','上一页');
            $page->setConfig('next','下一页');
            $page->setConfig('last','末页');
            $page->setConfig('link_page','页');
            $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
            $dm = $m->field("dname ,count(applynum) as dnum")->where("step in('accept','finish') ")->group('dname')->limit($page->firstRow,$page->listRows)->select();
            $this->assign("dm",$dm);
            $this->assign("title","部门分类");

            $show=$page->show();
            $this->assign('page',$show);

            $this->display();
        }

        public function DDisplay(){
        checkPhoneLogin();
        $name=session('name');
        $dname=I('dname');
        $m=M('mission');
        $count=$m->where("dname='{$dname}' and step in ('accept','finish') ")->order("step desc")->count();
        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $dm = $m->where("dname='{$dname}' and step in ('accept','finish') ")->order("step asc")->limit($page->firstRow,$page->listRows)->select();
        $this->assign("dm",$dm);
        $this->assign("title",$dname);

        $dma = $m->where("dname='{$dname}' and step='accept'")->count();
        $dman = $m->where("dname='{$dname}' and step in ('accept','finish') ")->count();
        $dmfn = $m->where("dname='{$dname}' and step = 'finish' ")->count();
        $rate = 100*sprintf("%.4f",$dmfn/$dman)."%";

        $this->assign('dma',$dma);
        $this->assign('dman',$dman);
        $this->assign('dmfn',$dmfn);
        $this->assign('rate',$rate);


        $this->display();


    }

    
    public function sysinfo(){
        $this->assign("title",'系统说明');
        $this->display();
    }

    public function help(){
        $this->assign('title','问题与帮助');
        $this->display();
    }








   












 
}
    











