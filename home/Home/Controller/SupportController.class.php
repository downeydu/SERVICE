<?php
namespace Home\Controller;
use Think\Controller;
use Think\Page;
use Think\Upload;

class SupportController extends Controller {
	//it支持首页

	public function index(){
		checkItLogin();
		$self=session('name');
		$this->assign('title','IT支持首页');
		$today=date('Y-m-d');
		//过去的待接收任务
		$pm = M('mission');
		$pmnum=$pm->where("dname!='公司领导' and subdname!='部门领导' and optime<'{$today}' and distribute='所有人' and step='issue' ")->count();
		$this->assign('pmnum',$pmnum);

		//今日待接收任务
		$tm = M('mission');
		$tmnum=$tm->where("dname!='公司领导' and subdname!='部门领导' and optime='{$today}' and distribute='所有人' and step='issue'")->count();
		$this->assign('tmnum',$tmnum);

		//分配给指定人员的任务
		$prm = M('mission');
		$prmnum=$prm->where("distribute='{$self}' and step='issue'")->count();
		$this->assign('prmnum',$prmnum);

		//公司领导的待接收的任务
		$vipm=M('mission');
		$vipmnum=$vipm->where("(dname='公司领导' or subdname='部门领导') and distribute='所有人' and step='issue'")->count();
		$this->assign('vipmnum',$vipmnum);

		//个人接受的普通任务
		$pam=M('mission');
		$pamnum=$pam->where("dname!='公司领导' and subdname!='部门领导' and solver='{$self}' and step='accept'")->count();

		$this->assign('pamnum',$pamnum);

		//个人接受的vip任务
		$vipam=M('mission');
		$vipamnum=$vipam->where("(dname='公司领导' or subdname='部门领导') and solver='{$self}' and step='accept'")->count();
		$this->assign('vipamnum',$vipamnum);

		//待评价个人任务
		$pjm=M('mission');
		$pjmnum=$pjm->where("dname!='公司领导' and subdname!='部门领导' and solver='{$self}' and step='finish'")->count();
		$this->assign('pjmnum',$pjmnum);

		//待评价vip任务
		$vipjm=M('mission');
		$vipjmnum=$vipjm->where("(dname='公司领导' or subdname='部门领导') and solver='{$self}' and step='finish'")->count();
		$this->assign('vipjmnum',$vipjmnum);



		$this->display();
	}

	//呈现过去待接受的任务
	public function pMission(){
		checkItLogin();
		$this->assign('title','过去任务');
		$today=date('Y-m-d');
		$pm=D('mission');
		$count=$pm->where("dname!='公司领导' and subdname!='部门领导' and optime<'{$today}' and distribute='所有人' and step='issue' ")->count();
		$page=new Page($count,15);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','末页');
		$page->setConfig('link_page','页');
		$page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$show=$page->show();
		$pmission=$pm->where("dname!='公司领导' and subdname!='部门领导' and optime<'{$today}' and distribute='所有人' and step='issue' ")->limit($page->firstRow,$page->listRows)->select();
		$this->assign('pm',$pmission);
		$this->assign('page',$show);
		$this->display();
	}

	//今日发布任务
	public function tMission(){
		checkItLogin();
		$this->assign('title','今日发布任务');
		$today=date('Y-m-d');
		$tm=D('mission');
		$count=$tm->where("dname!='公司领导' and subdname!='部门领导' and optime='{$today}' and distribute='所有人' and step='issue'")->count();
		$page=new Page($count,15);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','末页');
		$page->setConfig('link_page','页');
		$page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$show=$page->show();
		$tmission=$tm->where("dname!='公司领导' and subdname!='部门领导' and optime='{$today}' and distribute='所有人' and step='issue' ")->limit($page->firstRow,$page->listRows)->select();
		$this->assign('tm',$tmission);
		$this->assign('page',$show);
		$this->display();

	}

	//指定给你的任务
	public function priMission(){
		checkItLogin();
		$this->assign('title','指定任务');
		$today=date('Y-m-d');
		$self=session('name');
		$prim=D('mission');
		$count=$prim->where("distribute='{$self}' and step='issue'")->count();
		$page=new Page($count,15);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','末页');
		$page->setConfig('link_page','页');
		$page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$show=$page->show();
		$primission=$prim->where("distribute='{$self}' and step='issue'")->limit($page->firstRow,$page->listRows)->select();
		$this->assign('prim',$primission);
		$this->assign('page',$show);
		$this->display();

	}

	//vip任务
	public function vipMission()
	{
		checkItLogin();
		$this->assign('title','vip任务');
		$vipm=D('mission');
		$count=$vipm->where("(dname='公司领导' or subdname='部门领导') and distribute='所有人' and step='issue'")->count();
		$page=new Page($count,15);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','末页');
		$page->setConfig('link_page','页');
		$page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$show=$page->show();
		$vipmission=$vipm->where("(dname='公司领导' or subdname='部门领导') and distribute='所有人' and step='issue'")->select();
		$this->assign('vipm',$vipmission);
		$this->assign('page',$show);
		$this->display();


	}





	//it支持接受,存储信息
	public function accept(){
		checkItLogin();
		$applynum=I('applynum');

		//判断这个任务事先有没有被接受
		$j=M('mission');
		$jd=$j->where("applynum='{$applynum}'")->find();
		if($jd['step']!='issue'){
			$this->error("抱歉,此任务已经被他人接受",U("index"));
		}

		$now=date('Y-m-d H:i:s');
		$ac=M('mission');
		$data['status']='已接受';
		$ptype=I('ptype');
		$data['atime']=$now;
		$data['step']='accept';
		$data['solver']=session('name');
		if($ac->where("applynum='{$applynum}'")->save($data))
        {
        	if($ptype=='硬件问题'){
        		$this->success("接受任务成功",U("hwaDisplay",array('applynum'=>$applynum)));
        	}
        	else if($ptype=='软件问题'){
        		$this->success("接受任务成功",U("sfaDisplay",array('applynum'=>$applynum)));
        	}
        	else if($ptype=='资产分配'){
        		$this->success("接受任务成功",U("ppaDisplay",array('applynum'=>$applynum)));
        	}
        	else if($ptype=='会议支持'){
        		$this->success("接受任务成功",U("cpaDisplay",array('applynum'=>$applynum)));
        	}else{

            	$this->error("接受失败2");

        	}
        }else{
            $this->error("接受失败1");

        }
	}


	//显示待完成的任务
	public function aDisplay(){
		checkItLogin();
		$this->assign('title','待完成任务');
		$applynum=I('applynum');
		$fm=M('mission');
		$fmission=$fm->where("applynum='{$applynum}'")->select();
		$this->assign("row",$fmission);
		$self=session('name');
		$it=M('user');
		$user=$it->where("role='it' and name!='{$self}'")->select();
		$this->assign('role',$user);

		if($fmission[0]['distribute']=='all')
		{
			$this->assign('distribute','所有人');
		}else{
			$this->assign('distribute',$fmission[0]['distribute']);
		}

		$this->display();
	}


	//任务完成,等待评价
	public function judge(){
		checkItLogin();
		$this->assign('title','待评价任务');
		$applynum=I('applynum');
		$data['status']='待评价';
		$data['step']='judge';
		$data['brand']=I('brand');
		$data['sn']=I('sn');
		$data['pnum']=I('pnum');
		$data['treason']=I('treason');
		$data['method']=I('method');
		$data['stime']=date('Y-m-d H:i:s');
		$data['fixinfo']=I('fixinfo');
		$data['fixpay']=I('fixpay');
		$jm=M('mission');
		if($jm->where("applynum='{$applynum}'")->save($data))
		{
			$this->success('提交成功,等待评价',U("jDisplay",array('applynum'=>$applynum)));
		}else{
			$this->error('提交失败');
		}
	}

	//个人接受的任务
	public function priAMission(){
		checkItLogin();
		$this->assign('title','您接受任务');
		$today=date('Y-m-d');
		$self=session('name');
		$prim=D('mission');
		$count=$prim->where("dname!='公司领导' and subdname!='部门领导' and solver='{$self}' and step='accept'")->count();
		$page=new Page($count,15);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','末页');
		$page->setConfig('link_page','页');
		$page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$show=$page->show();
		$primission=$prim->where(" dname!='公司领导' and subdname!='部门领导' and solver='{$self}' and step='accept'")->order("optime desc")->limit($page->firstRow,$page->listRows)->select();
		$this->assign('prim',$primission);
		$this->assign('page',$show);
		$this->display();
	}

	//个人接受的vip任务
	public function vipAMission(){
		checkItLogin();
		$this->assign('title','您接收的vip任务');
		$vipm=D('mission');
		$self=session('name');
		$count=$vipm->where("(dname='公司领导' or subdname='部门领导') and solver='{$self}' and step='accept'")->count();
		$page=new Page($count,15);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','末页');
		$page->setConfig('link_page','页');
		$page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$show=$page->show();
		$vipmission=$vipm->where("(dname='公司领导' or subdname='部门领导') and solver='{$self}' and step='accept'")->select();
		$this->assign('vipm',$vipmission);
		$this->assign('page',$show);
		$this->display();
	}

	//个人待评价的任务
	public function priJMission(){
		checkItLogin();
		$this->assign('title','待评价个人任务');
		$today=date('Y-m-d');
		$self=session('name');
		$prim=D('mission');
		$count=$prim->where("dname!='公司领导' and subdname!='部门领导' and solver='{$self}' and step='finish'")->count();
		$page=new Page($count,15);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','末页');
		$page->setConfig('link_page','页');
		$page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$show=$page->show();
		$primission=$prim->where("dname!='公司领导' and subdname!='部门领导' and solver='{$self}' and step='finish'")->limit($page->firstRow,$page->listRows)->select();
		$this->assign('prim',$primission);
		$this->assign('page',$show);
		$this->display();
	}

	//待评价的vip任务
	public function vipJMission(){
		checkItLogin();
		$this->assign('title','vip任务');
		$vipm=D('mission');
		$self=session('name');
		$count=$vipm->where("(dname='公司领导' or subdname='部门领导') and solver='{$self}' and step='finish'")->count();
		$page=new Page($count,15);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','末页');
		$page->setConfig('link_page','页');
		$page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$show=$page->show();
		$vipmission=$vipm->where("(dname='公司领导' or subdname='部门领导') and solver='{$self}' and step='finish'")->limit($page->firstRow,$page->listRows)->select();
		$this->assign('vipm',$vipmission);
		$this->assign('page',$show);
		$this->display();
	}

	public function jDisplay(){
		checkItLogin();
		$this->assign('title','等待评价');
		$applynum=I('applynum');
		$jm=M('mission');
		$jmission=$jm->where("applynum='{$applynum}'")->select();
		$this->assign('row',$jmission);
		if($jmission[0]['distribute']=='all')
		{
			$this->assign('distribute','所有人');
		}else{
			$this->assign('distribute',$jmission[0]['distribute']);
		}
		$this->display();
	}

	//将任务转交于他人
	public function change(){
		checkItLogin();
		$solver=I('solver');
		$presolver=I('presolver');
		if($solver==$presolver)
		{
			$this->error("操作失败,无法转交给自己");
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

	public function hwiDisplay(){
		checkItLogin();
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

	public function sfiDisplay(){
		checkItLogin();
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

	public function ppiDisplay(){
		checkItLogin();
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

	public function cpiDisplay(){
		checkItLogin();
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

	public function cpaDisplay(){
		checkItLogin();
		$applynum=I('applynum');
		$name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,m.solver,c.convtime,c.isd,c.ispc,c.convtype,c.croom,c.participant from mission m,cp c where m.applynum=c.applynum and m.applynum = '{$applynum}'");
        $it = M('user');
        $ituser=$it->where("role='it' and name!='{$name}'")->select();
        $this->assign('title','已接受会议支持任务');
        $this->assign('it',$ituser);
        $this->assign('row',$mission);
        $this->display();
	}

	public function hwaDisplay(){
		checkItLogin();
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
		checkItLogin();
		$applynum=I('applynum');
		$name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,m.solver,s.sftype,s.tinfo from mission m,sfp s where m.applynum=s.applynum and m.applynum = '{$applynum}'");
        $it = M('user');
        $ituser=$it->where("role='it' and name!='{$name}'")->select();
        $this->assign('title','已接受会议支持任务');
        $this->assign('it',$ituser);
        $this->assign('row',$mission);
        $this->display();
	}

	public function ppaDisplay(){
		checkItLogin();
		$applynum=I('applynum');
		$name=session('name');
        $now=date('Y-m-d');
        $this->assign('date',$now);
        $this->assign('applynum',$applynum);
        $m = M();
        $mission = $m->query("select m.name,m.applynum,m.ctime,m.phone,m.tp,m.dname,m.subdname,m.ptype,m.creator,m.distribute,m.status,m.atime,p.distreason,p.protype from mission m,pp p where m.applynum=p.applynum and m.applynum = '{$applynum}'");
        $it = M('user');
        $ituser=$it->where("role='it' and name!='{$name}'")->select();
        $this->assign('title','已接受会议支持任务');
        $this->assign('it',$ituser);
        $this->assign('row',$mission);
        $this->display();

	}

	public function sTrack(){
		checkItLogin();
        $this->assign('title','追踪任务');
        $m=M('mission');
        $solver=session('name');
        $count=$m->where("step in ('accept','finish') and solver='{$solver}'")->count();

        $anum=$m->where("step='accept' and solver='{$solver}' ")->count();
        $this->assign('anum',$anum);

        $fnum=$m->where("step='finish' and solver='{$solver}'")->count();
        $this->assign('fnum',$fnum);


        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $ham=$m->where("step in ('accept','finish') and solver='{$solver}'")->order('applynum desc')->limit($page->firstRow.','.$page->listRows)->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('mis',$ham);
        $this->display();
    }


    public function finish(){
    	checkItLogin();
    	if(!IS_POST){
    		$this->error('操作失败');
    	}
    	$applynum=I('applynum');
    	$ptype=I('ptype');
    	$name=session('name');
    	$date=date('Y-m-d');

    	
    	$upload = new Upload();// 实例化上传类
	    $upload->maxSize   =     15728640 ;// 设置附件上传大小
	    $upload->exts      =     array('pdf');// 设置附件上传类型
	    $upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
	    $upload->saveName  =     $applynum.'-'.$name.'-'.mt_rand(); // 设置附件上传（子）目录

    	if($ptype=='硬件问题'){
		    // 上传文件 
		    $info   =   $upload->uploadOne($_FILES['sform']);
		    if(!$info) {// 上传错误提示错误信息
		        $this->error($upload->getError());
		        exit;
		    }else{
		    	// 上传成功
		        $url=$info['savepath'].$info['savename'];
		        $sfurl= __ROOT__.'/Public/Uploads/'.$url;
		        // $this->success('上传成功！');
		    }
    		$hwdata['brand']=I('brand');
    		$hwdata['sn']=I('sn');
    		$hwdata['treason']=I('treason');
    		$hwdata['method']=I('method');
    		$hwdata['sfurl']=$sfurl;
			$hw = M('hwp');


			$hwmdata['stime']=date('Y-m-d H:i:s');
			$hwmdata['sfurl']=$sfurl;
			$hwmdata['status']='已完成';
			$hwmdata['step']='finish';
			$hwm=M('mission');
			if($hw->where("applynum='{$applynum}'")->save($hwdata) and $hwm->where("applynum='{$applynum}'")->save($hwmdata)){
				$this->success('提交成功',U("hwfDisplay",array('applynum'=>$applynum)));
			}else{
				$this->error('操作错误');
			}

    	}
    	else if($ptype=='软件问题'){
    		// 上传文件 
		    $info   =   $upload->uploadOne($_FILES['sform']);
		    if(!$info) {// 上传错误提示错误信息
		        $this->error($upload->getError());
		        exit;
		    }else{
		    	// 上传成功
		        $url=$info['savepath'].$info['savename'];
		        $sfurl= __ROOT__.'/Public/Uploads/'.$url;
		        // $this->success('上传成功！');
		    }
     		$mdata['stime']=date('Y-m-d H:i:s');
    		$sfdata['brand']=I('brand');
    		$sfdata['sn']=I('sn');
    		$sfdata['treason']=I('treason');
    		$sfdata['method']=I('method');
    		$sf=M('sfp');

    		$data['stime']=date('Y-m-d H:i:s');
			$data['sfurl']=$sfurl;
			$data['status']='已完成';
			$data['step']='finish';
			$sfm=M('mission');

			if($sf->where("applynum='{$applynum}'")->save($sfdata) and $sfm->where("applynum='{$applynum}'")->save($data)){
				$this->success('提交成功',U("sffDisplay",array('applynum'=>$applynum)));
			}else{
				$this->error('操作错误');
			}

    		

    	}
    	else if($ptype=='资产分配'){
    		// 上传文件 
		    $info   =   $upload->uploadOne($_FILES['sform']);
		    if(!$info) {// 上传错误提示错误信息
		        $this->error($upload->getError());
		        exit;
		    }else{
		    	// 上传成功
		        $url=$info['savepath'].$info['savename'];
		        $sfurl= __ROOT__.'/Public/Uploads/'.$url;
		        // $this->success('上传成功！');
		    }

    		$ppdata['brand']=I('brand');
    		$ppdata['sn']=I('sn');
    		$pp=M('pp');

    		$data['stime']=date('Y-m-d H:i:s');
			$data['sfurl']=$sfurl;
			$data['status']='已完成';
			$data['step']='finish';
			$pm=M('mission');

			if($pp->where("applynum='{$applynum}'")->save($ppdata) and $pm->where("applynum='{$applynum}'")->save($data)){
				$this->success('提交成功',U("ppfDisplay",array('applynum'=>$applynum)));
			}else{
				$this->error('操作错误');
			}


    	}
    	else if($ptype=='会议支持'){
			// 上传文件 
		    $info   =   $upload->uploadOne($_FILES['sform']);
		    if(!$info) {// 上传错误提示错误信息
		        $this->error($upload->getError());
		        exit;
		    }else{
		    	// 上传成功
		        $url=$info['savepath'].$info['savename'];
		        $sfurl= __ROOT__.'/Public/Uploads/'.$url;
		        // $this->success('上传成功！');
		    }  


		    $data['stime']=date('Y-m-d H:i:s');
			$data['sfurl']=$sfurl;
			$data['status']='已完成';
			$data['step']='finish';
			$cpm=M('mission');
			if($cpm->where("applynum='{$applynum}'")->save($data)){
				$this->success('提交成功',U("cpfDisplay",array('applynum'=>$applynum)));
			}else{
				$this->error('操作错误');
			}


		}
    }


    public function hwfDisplay(){
    	checkItLogin();
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
    	checkItLogin();
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
    	checkItLogin();
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
    	checkItLogin();
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

    //已经完成的任务
    public function hfMission(){
    	checkItLogin();
        $this->assign('title','已经完成的任务');
        $name=session('name');
        $m=M('mission');
        $count=$m->where("step='finish' and solver='{$name}' ")->count();
        $page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
        $hfm=$m->where("step='finish' and solver='{$name}' ")->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('hfm',$hfm);
        $this->display();
    }

    public function slist(){
    	checkItLogin();
    	if(!IS_POST){
    		$this->error("操作失败");
    	}
    	$applynum=I('applynum');
    	$ptype=I('ptype');

    	//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
		import("Org.Util.PHPExcel");
		import("Org.Util.PHPExcel.Writer.Excel5");
		import("Org.Util.PHPExcel.IOFactory.php");
		import("Org.Util.PHPExcel.IComparable.php");
		import("Org.Util.PHPExcel.Style");
		import("Org.Util.PHPExcel.Style.php");
		import("Org.Util.PHPExcel.Style.Aligment.php");

		if($ptype=='硬件问题'){
    	//依据请求编号,从数据中读取数据
		$m = M('mission');
		$hw = $m->query("select m.name,m.dname,m.subdname,m.applynum,m.distribute,m.phone,m.tp,m.ptype,m.ctime,m.atime,h.hwtype,h.tinfo from mission m,hwp h where m.applynum = h.applynum and m.applynum = '{$applynum}'");
		//创建PHPExcel对象，注意，不能少了\
	    $objPHPExcel = new \PHPExcel();
		
		//excel 信息
		$objPHPExcel->getProperties()->setCreator("DuBin")
						 ->setLastModifiedBy("abclife-staff")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("service list of hardware")
						 ->setKeywords("office 2007 openxml php")
						 ->setCategory("hwlist");

		//excel 特殊字体要求 设置
	    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)
	        ->setName('宋体')
	        ->setSize(15)
	        ->getColor()->setRGB('000000');

	    $objPHPExcel->getActiveSheet()->getStyle("A13")->getFont()->setBold(false)
	        ->setName('楷体')
	        ->setSize(11)
	        ->getColor()->setRGB('000000');

	    //首行和末行 行水平和垂直居中设置,因为其他为循环设置,所以单独写于这里
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A13")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A13')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

		//合并单元格设置
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B9:D9'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B10:D10'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B11:D11'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B12:D12'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('A13:D13'); 

	    //设置行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(36);
	    for($j=2;$j<=12;$j++){
	        if($j<=8){
	            $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(22);
	        }else{
	            $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(40);
	        }
	    }
	    $objPHPExcel->getActiveSheet()->getRowDimension('13')->setRowHeight(32);

	    //设个表格样式
	    $col=array('A','C');
		foreach($col as $key => $value){
		    if($value=='A'){
		        for($i=2;$i<=12;$i++){
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
		}

		$col=array('B','C','D');
		foreach($col as $key => $value){
		    if($value=='B'){
		        for($i=2;$i<=12;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		            // $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->getStartColor()->setARGB('FFDCDCDC');
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


		        }
		    }
		    else if($value=='C'){
		        for($i=9;$i<=12;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		            // $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->getStartColor()->setARGB('FFDCDCDC');
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
		        for($i=2;$i<=12;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		            // $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->getStartColor()->setARGB('FFADADAD');
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
		            ->setCellValue('A1', 'IT服务台--硬件支持服务单')
		            ->setCellValue('A2', '申请人')
		            ->setCellValue('A3', '部门')
		            ->setCellValue('A4', '处室')
		            ->setCellValue('A5', '请求编号')
		            ->setCellValue('A6', '申请人签字')
		            ->setCellValue('A7', '解决人签字')
		            ->setCellValue('A8', '品牌型号')
		            ->setCellValue('A9', '故障描述')
		            ->setCellValue('A10', '故障原因')
		            ->setCellValue('A11', '解决方法')
		            ->setCellValue('A12','服务评价')
		            ->setCellValue('C2','座机')
		            ->setCellValue('C3','手机')
		            ->setCellValue('C4','问题类型')
		            ->setCellValue('C5','硬件类型')
		            ->setCellValue('C6','创建时间')
		            ->setCellValue('C7','接收时间')
		            ->setCellValue('C8','主机序列号')
		            ->setCellValue('B12','优秀        良好        合格        未解决')
		            ->setCellValue('A13','农银人寿保险股份有限公司 信息技术部 网络运维处');

		//为excel插入数据
		$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B2', "{$hw[0]['name']}")
        ->setCellValue('B3', "{$hw[0]['dname']}")
        ->setCellValue('B4', "{$hw[0]['subdname']}")
        ->setCellValue('B5', "{$hw[0]['applynum']}")
        ->setCellValue('B9', "{$hw[0]['tinfo']}")
        ->setCellValue('D2', "{$hw[0]['phone']}")
        ->setCellValue('D3', "{$hw[0]['tp']}")
        ->setCellValue('D4', "{$hw[0]['ptype']}")
        ->setCellValue('D5', "{$hw[0]['hwtype']}")
        ->setCellValue('D6', "{$hw[0]['ctime']}")
        ->setCellValue('D7', "{$hw[0]['atime']}");

	    
		// title名
		$objPHPExcel->getActiveSheet()->setTitle("硬件{$applynum}");


		// 只激活第一个index
		$objPHPExcel->setActiveSheetIndex(0);

		//不保存直接直接下载下来
		$fileName=$applynum.$ptype."服务单.xls";
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

 

		
	}else if($ptype=='软件问题'){
		//依据请求编号,从数据中读取数据
		$m = M('mission');
		$sf = $m->query("select m.name,m.dname,m.subdname,m.applynum,m.distribute,m.phone,m.tp,m.ptype,m.ctime,m.atime,s.sftype,s.tinfo from mission m,sfp s where m.applynum = s.applynum and m.applynum = '{$applynum}'");
		//创建PHPExcel对象，注意，不能少了\
	    $objPHPExcel = new \PHPExcel();
		
		//excel 信息
		$objPHPExcel->getProperties()->setCreator("DuBin")
						 ->setLastModifiedBy("abclife-staff")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("service list of software")
						 ->setKeywords("office 2007 openxml php")
						 ->setCategory("sflist");
		//字体设置
	    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)
	        ->setName('宋体')
	        ->setSize(15)
	        ->getColor()->setRGB('000000');

	    $objPHPExcel->getActiveSheet()->getStyle("A13")->getFont()->setBold(false)
	        ->setName('楷体')
	        ->setSize(11)
	        ->getColor()->setRGB('000000');

	    //单元格水平垂直居中
	    $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A13")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A13')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B9:D9'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B10:D10'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B11:D11'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B12:D12'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('A13:D13'); 

	    //设置航高度
	    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(36);
	    for($j=2;$j<=12;$j++){
	        if($j<=8){
	            $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(22);
	        }else{
	            $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(40);
	        }
	    }
	    $objPHPExcel->getActiveSheet()->getRowDimension('13')->setRowHeight(32);


		$col=array('A','C');
		foreach($col as $key => $value){
		    if($value=='A'){
		        for($i=2;$i<=12;$i++){
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
		}

		$col=array('B','C','D');
		foreach($col as $key => $value){
		    if($value=='B'){
		        for($i=2;$i<=12;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


		        }
		    }
		    else if($value=='C'){
		        for($i=9;$i<=12;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
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
		        for($i=2;$i<=12;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
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
		            ->setCellValue('A1', 'IT服务台--软件支持服务单')
		            ->setCellValue('A2', '申请人')
		            ->setCellValue('A3', '部门')
		            ->setCellValue('A4', '处室')
		            ->setCellValue('A5', '请求编号')
		            ->setCellValue('A6', '申请人签字')
		            ->setCellValue('A7', '解决人签字')
		            ->setCellValue('A8', '品牌型号')
		            ->setCellValue('A9', '故障描述')
		            ->setCellValue('A10', '故障原因')
		            ->setCellValue('A11', '解决方法')
		            ->setCellValue('A12','服务评价')
		            ->setCellValue('C2','座机')
		            ->setCellValue('C3','手机')
		            ->setCellValue('C4','问题类型')
		            ->setCellValue('C5','软件类型')
		            ->setCellValue('C6','创建时间')
		            ->setCellValue('C7','接收时间')
		            ->setCellValue('C8','主机序列号')
		            ->setCellValue('B12','优秀        良好        合格        未解决')
		            ->setCellValue('A13','农银人寿保险股份有限公司 信息技术部 网络运维处');

		//写入内容
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', "{$sf[0]['name']}")
            ->setCellValue('B3', "{$sf[0]['dname']}")
            ->setCellValue('B4', "{$sf[0]['subdname']}")
            ->setCellValue('B5', "{$sf[0]['applynum']}")
            ->setCellValue('B9', "{$sf[0]['tinfo']}")
            ->setCellValue('D2', "{$sf[0]['phone']}")
            ->setCellValue('D3', "{$sf[0]['tp']}")
            ->setCellValue('D4', "{$sf[0]['ptype']}")
            ->setCellValue('D5', "{$sf[0]['sftype']}")
            ->setCellValue('D6', "{$sf[0]['ctime']}")
            ->setCellValue('D7', "{$sf[0]['atime']}");

        	// title名
			$objPHPExcel->getActiveSheet()->setTitle("软件{$applynum}");


			// 只激活第一个index
			$objPHPExcel->setActiveSheetIndex(0);

			//不保存直接直接下载下来
			$fileName=$applynum.$ptype."服务单.xls";
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



	}else if($ptype=='会议支持'){
		//依据请求编号,从数据中读取数据
		$m = M('mission');
		$cp = $m->query("select m.name,m.dname,m.subdname,m.applynum,m.distribute,m.phone,m.tp,m.ptype,m.ctime,m.atime,c.isd,c.ispc,c.convtime,c.croom,c.participant,c.convtype from mission m,cp c where m.applynum = c.applynum and m.applynum = '{$applynum}'");
		//创建PHPExcel对象，注意，不能少了\
	    $objPHPExcel = new \PHPExcel();
		
		//excel 信息
		$objPHPExcel->getProperties()->setCreator("DuBin")
						 ->setLastModifiedBy("abclife-staff")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("service list of convention support")
						 ->setKeywords("office 2007 openxml php")
						 ->setCategory("sflist");
		//字体设置
	    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)
	        ->setName('宋体')
	        ->setSize(15)
	        ->getColor()->setRGB('000000');

	    $objPHPExcel->getActiveSheet()->getStyle("A12")->getFont()->setBold(false)
	        ->setName('楷体')
	        ->setSize(11)
	        ->getColor()->setRGB('000000');

	    $objPHPExcel->getActiveSheet()->getStyle("C8")->getFont()->setBold(true)
	        ->setName('楷体')
	        ->setSize(13)
	        ->getColor()->setRGB('FF00000');

	    //水平,垂直居中
	    $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle("A12")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A12')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

	    //合并单元格
	    $objPHPExcel->getActiveSheet()->mergeCells('A1:D1'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B10:D10'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B11:D11'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('A12:D12');

	    //设置行高度
	    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(36);
	    for($j=2;$j<=12;$j++){
	        if($j<=8){
	            $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(22);
	        }else{
	            $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(40);
	        }
	    }
	    $objPHPExcel->getActiveSheet()->getRowDimension('13')->setRowHeight(32);

	    //设置单元格样式
	    $col=array('A','C');
		foreach($col as $key => $value){
		    if($value=='A'){
		        for($i=2;$i<=11;$i++){
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
		        for($i=2;$i<=9;$i++){
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
		}

		$col=array('B','C','D');
		foreach($col as $key => $value){
		    if($value=='B'){
		        for($i=2;$i<=11;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


		        }
		    }
		    else if($value=='C'){
		        for($i=9;$i<=11;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
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
		        for($i=2;$i<=11;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
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
		            ->setCellValue('A1', 'IT服务台--会议支持服务单')
		            ->setCellValue('A2', '申请人')
		            ->setCellValue('A3', '部门')
		            ->setCellValue('A4', '处室')
		            ->setCellValue('A5', '请求编号')
		            ->setCellValue('A6', '申请人签字')
		            ->setCellValue('A7', '解决人签字')
		            ->setCellValue('A8', '会议室')
		            ->setCellValue('A9', '是否数据双流')
		            ->setCellValue('A10', '参会方')
		            ->setCellValue('A11', '服务评价')
		            ->setCellValue('C2','座机')
		            ->setCellValue('C3','手机')
		            ->setCellValue('C4','问题类型')
		            ->setCellValue('C5','会议类型')
		            ->setCellValue('C6','创建时间')
		            ->setCellValue('C7','接收时间')
		            ->setCellValue('C8','会议时间')
		            ->setCellValue('C9','是否自备电脑')
		            ->setCellValue('B11','优秀        良好        合格        未解决')
		            ->setCellValue('A12','农银人寿保险股份有限公司 信息技术部 网络运维处');

		$objPHPExcel->setActiveSheetIndex(0)
		        ->setCellValue('B2', "{$cp[0]['name']}")
		        ->setCellValue('B3', "{$cp[0]['dname']}")
		        ->setCellValue('B4', "{$cp[0]['subdname']}")
		        ->setCellValue('B5', "{$cp[0]['applynum']}")
		        ->setCellValue('B8',"{$cp[0]['croom']}")
		        ->setCellValue('B9',"{$cp[0]['isd']}")
		        ->setCellValue('B10',"{$cp[0]['participant']}")
		        ->setCellValue('D2', "{$cp[0]['phone']}")
		        ->setCellValue('D3', "{$cp[0]['tp']}")
		        ->setCellValue('D4', "{$cp[0]['ptype']}")
		        ->setCellValue('D5', "{$cp[0]['convtype']}")
		        ->setCellValue('D6', "{$cp[0]['ctime']}")
		        ->setCellValue('D7', "{$cp[0]['atime']}")
		        ->setCellValue('D8', "{$cp[0]['convtime']}")
		        ->setCellValue('D9', "{$cp[0]['ispc']}");

		// title名
		$objPHPExcel->getActiveSheet()->setTitle("会议{$applynum}");


		// 只激活第一个index
		$objPHPExcel->setActiveSheetIndex(0);

		//不保存直接直接下载下来
		$fileName=$applynum.$ptype."服务单.xls";
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




	 
	}else if($ptype=='资产分配'){
		//依据请求编号,从数据中读取数据
		$m = M('mission');
		$pp = $m->query("select m.name,m.dname,m.subdname,m.applynum,m.distribute,m.phone,m.tp,m.ptype,m.ctime,m.atime,p.distreason,p.protype from mission m,pp p where m.applynum = p.applynum and m.applynum = '{$applynum}'");
		//创建PHPExcel对象，注意，不能少了\
	    $objPHPExcel = new \PHPExcel();
		
		//excel 信息
		$objPHPExcel->getProperties()->setCreator("DuBin")
						 ->setLastModifiedBy("abclife-staff")
						 ->setTitle("Office 2007 XLSX Test Document")
						 ->setSubject("Office 2007 XLSX Test Document")
						 ->setDescription("service list of property distribution")
						 ->setKeywords("office 2007 openxml php")
						 ->setCategory("pplist");
		//字体设置
	    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)
	        ->setName('宋体')
	        ->setSize(15)
	        ->getColor()->setRGB('000000');

	    $objPHPExcel->getActiveSheet()->getStyle("A12")->getFont()->setBold(false)
	        ->setName('楷体')
	        ->setSize(11)
	        ->getColor()->setRGB('000000');

	    //水平,垂直居中
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle("A12")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A12')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

	    //合并单元格
	    $objPHPExcel->getActiveSheet()->mergeCells('A1:D1'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B11:D11'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('A12:D12'); 
	    $objPHPExcel->getActiveSheet()->mergeCells('B8:D8'); 

	    //设置行高度
	    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(36);
	    for($j=2;$j<=12;$j++){
	        if($j<=10){
	            $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(22);
	        }else{
	            $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(40);
	        }
	    }
	    $objPHPExcel->getActiveSheet()->getRowDimension('13')->setRowHeight(32);
	    $objPHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(40);

	    //单元格样式
	    $col=array('A','C');
		foreach($col as $key => $value){
		    if($value=='A'){
		        for($i=2;$i<=11;$i++){
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
		        for($i=2;$i<=7;$i++){
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
		}
		            $objPHPExcel->getActiveSheet()->getStyle('A12')->getFill()->getStartColor()->setARGB('FFFFFFFF');


		for($i=9;$i<=11;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getFill()->getStartColor()->setARGB('FFDCDCDC');
		            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


		        }

		$col=array('B','C','D');
		foreach($col as $key => $value){
		    if($value=='B'){
		        for($i=2;$i<=11;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		            $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);



		        }
		    }
		    else if($value=='C'){
		        for($i=9;$i<=11;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
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
		        for($i=2;$i<=11;$i++){
		            $objPHPExcel->getActiveSheet()->getStyle($value.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
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

		    // $objPHPExcel->getActiveSheet()->mergeCells('A12:D12'); 

		//设置列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('20');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('25');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('20');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('25');

		//表头信息
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'IT服务台--资产分配服务单')
		            ->setCellValue('A2', '申请人')
		            ->setCellValue('A3', '部门')
		            ->setCellValue('A4', '处室')
		            ->setCellValue('A5', '请求编号')
		            ->setCellValue('A6', '申请人签字')
		            ->setCellValue('A7', '解决人签字')
		            ->setCellValue('A8', '资产类别')
		            ->setCellValue('A9', '型号/版本')
		            ->setCellValue('A10', '型号/版本')
		            ->setCellValue('A11', '服务评价')
		            ->setCellValue('C2','座机')
		            ->setCellValue('C3','手机')
		            ->setCellValue('C4','问题类型')
		            ->setCellValue('C5','分配原因')
		            ->setCellValue('C6','创建时间')
		            ->setCellValue('C7','接收时间')
		            ->setCellValue('C8','资产类别')
		            ->setCellValue('C9','SN/KEY')
		            ->setCellValue('C10','SN/KEY')
		            ->setCellValue('B11','优秀        良好        合格        未解决')
		            ->setCellValue('A12','农银人寿保险股份有限公司 信息技术部 网络运维处');

		//写入数据库数据
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', "{$pp[0]['name']}")
            ->setCellValue('B3', "{$pp[0]['dname']}")
            ->setCellValue('B4', "{$pp[0]['subdname']}")
            ->setCellValue('B5', "{$pp[0]['applynum']}")
            ->setCellValue('B8',"{$pp[0]['protype']}")
            ->setCellValue('D2', "{$pp[0]['phone']}")
            ->setCellValue('D3', "{$pp[0]['tp']}")
            ->setCellValue('D4', "{$pp[0]['ptype']}")
            ->setCellValue('D5', "{$pp[0]['distreason']}")
            ->setCellValue('D6', "{$pp[0]['ctime']}")
            ->setCellValue('D7', "{$pp[0]['atime']}");

		// title名
		$objPHPExcel->getActiveSheet()->setTitle("资产分配{$applynum}");


		// 只激活第一个index
		$objPHPExcel->setActiveSheetIndex(0);

		//不保存直接直接下载下来
		$fileName=$applynum.$ptype."服务单.xls";
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


	}

	public function sTSearch(){
		checkItLogin();
		if(!IS_POST){
			$this->error('操作错误');
		}
		$applynum=trim(I('applynum'));
		$solver=session('name');
		//搜索姓名
		if(preg_match("/^[\x7f-\xff]+$/",$applynum)){
			$name=$applynum;
			$m=M('mission');
			$apm=$m->where("name='{$name}' and solver='{$solver}'")->select();
			$num=$m->where("name='{$name}' and solver='{$solver}'")->count();
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
					$apm=$m->where("applynum='{$applynum}' and solver='{$solver}'")->select();
					$num=$m->where("applynum='{$applynum}'and solver='{$solver}'")->count();
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

	//按照问题类别去统计
	public function sType(){
		checkItLogin();
		$m=M('mission');
		//一共发布的任务
		$hwn=$m->where("ptype='硬件问题' and step!='save' ")->count();
		$sfn=$m->where("ptype='软件问题' and step!='save' ")->count();
		$cpn=$m->where("ptype='会议支持' and step!='save' ")->count();
		$ppn=$m->where("ptype='资产分配' and step!='save' ")->count();
		$this->assign('sum',$hwn+$sfn+$cpn+$ppn);

		//此人接受的任务数量
		$solver=session('name');
		$hwan=$m->where("ptype='硬件问题' and step in ('accept','finish') and solver='{$solver}'")->count();
		$sfan=$m->where("ptype='软件问题' and step in ('accept','finish') and solver='{$solver}'")->count();
		$ppan=$m->where("ptype='资产分配' and step in ('accept','finish') and solver='{$solver}'")->count();
		$cpan=$m->where("ptype='会议支持' and step in ('accept','finish') and solver='{$solver}'")->count();
		$asum=$hwan+$sfan+$cpan+$ppan;
		$this->assign('asum',$asum);

		//此人完成的任务数量
		$hwfn=$m->where("ptype='硬件问题' and step = 'finish' and solver='{$solver}'")->count();
		$sffn=$m->where("ptype='软件问题' and step = 'finish' and solver='{$solver}'")->count();
		$ppfn=$m->where("ptype='资产分配' and step = 'finish' and solver='{$solver}'")->count();
		$cpfn=$m->where("ptype='会议支持' and step = 'finish' and solver='{$solver}'")->count();
		$fsum=$hwfn+$sffn+$ppfn+$cpfn;
		$this->assign('fsum',$fsum);


		//完成率
		// $hwfrate=100*sprintf("%.4f", $hwfn/$hwan)."%";
		// $sffrate=100*sprintf("%.4f", $sffn/$sfan)."%";
		// $cpfrate=100*sprintf("%.4f", $cpfn/$cpan)."%";
		// $ppfrate=100*sprintf("%.4f", $ppfn/$ppan)."%";
		$avfrate=100*sprintf("%.2f",($fsum/$asum))."%";
		$this->assign('avfrate',$avfrate);
		$this->assign('hwfrate',100*sprintf("%.4f", $hwfn/$hwan)."%");
		$this->assign('sffrate',100*sprintf("%.4f", $sffn/$sfan)."%");
		$this->assign('cpfrate',100*sprintf("%.4f", $cpfn/$cpan)."%");
		$this->assign('ppfrate',100*sprintf("%.4f", $ppfn/$ppan)."%");



		//接受率
		$hwarate=100*sprintf("%.4f",$hwan/$hwn)."%";
		$sfarate=100*sprintf("%.4f",$sfan/$sfn)."%";
		$pparate=100*sprintf("%.4f",$ppan/$ppn)."%";
		$cparate=100*sprintf("%.4f",$cpan/$cpn)."%";
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

	public function aSType(){
		checkItLogin();
		$ptype=I('ptype');
		$name=session('name');
		if($ptype=='硬件问题'){
			$m=M('mission');
			$count = $m->where("solver='{$name}' and ptype='硬件问题' ")->count();

			$page=new Page($count,15);
	        $page->setConfig('first','首页');
	        $page->setConfig('prev','上一页');
	        $page->setConfig('next','下一页');
	        $page->setConfig('last','末页');
	        $page->setConfig('link_page','页');
	        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
	        $hw=$m->where("solver='{$name}' and ptype='硬件问题' ")->order('applynum desc')->limit($page->firstRow,$page->listRows)->select();

	        $show=$page->show();
	        $this->assign('page',$show);


			$this->assign('title','硬件支持');
			$this->assign('hw',$hw);
		}else if($ptype=='软件问题'){
			$m=M('mission');
			$count = $m->where("solver='{$name}' and ptype='软件问题' ")->count();

			$page=new Page($count,15);
	        $page->setConfig('first','首页');
	        $page->setConfig('prev','上一页');
	        $page->setConfig('next','下一页');
	        $page->setConfig('last','末页');
	        $page->setConfig('link_page','页');
	        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
	        $hw=$m->where("solver='{$name}' and ptype='软件问题' ")->order('applynum desc')->limit($page->firstRow,$page->listRows)->select();

	        $show=$page->show();
	        $this->assign('page',$show);


			$this->assign('title','软件支持');
			$this->assign('hw',$hw);
		}else if($ptype=='会议支持'){
			$m=M('mission');
			$count = $m->where("solver='{$name}' and ptype='会议支持' ")->count();

			$page=new Page($count,15);
	        $page->setConfig('first','首页');
	        $page->setConfig('prev','上一页');
	        $page->setConfig('next','下一页');
	        $page->setConfig('last','末页');
	        $page->setConfig('link_page','页');
	        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
	        $hw=$m->where("solver='{$name}' and ptype='会议支持' ")->order('applynum desc')->limit($page->firstRow,$page->listRows)->select();

	        $show=$page->show();
	        $this->assign('page',$show);


			$this->assign('title','会议支持');
			$this->assign('hw',$hw);
		}else if($ptype=='资产分配'){
			$m=M('mission');
			$count = $m->where("solver='{$name}' and ptype='资产分配' ")->count();

			$page=new Page($count,15);
	        $page->setConfig('first','首页');
	        $page->setConfig('prev','上一页');
	        $page->setConfig('next','下一页');
	        $page->setConfig('last','末页');
	        $page->setConfig('link_page','页');
	        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
	        $hw=$m->where("solver='{$name}' and ptype='资产分配' ")->order('applynum desc')->limit($page->firstRow,$page->listRows)->select();

	        $show=$page->show();
	        $this->assign('page',$show);


			$this->assign('title','资产分配');
			$this->assign('hw',$hw);
		}

			$this->display();


	}

	public function sDname(){
		checkItLogin();
		$name=session('name');
		$m=M('mission');
		$mn = $m->field("dname ,count(applynum) as dnum")->where("step in('accept','finish') and solver='{$name}'")->group('dname')->select();
		$count = count($mn);
		$page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$dm = $m->field("dname ,count(applynum) as dnum")->where("step in('accept','finish') and solver='{$name}'")->group('dname')->limit($page->firstRow,$page->listRows)->select();
		$this->assign("dm",$dm);
		$this->assign("title","部门分类");

		$show=$page->show();
        $this->assign('page',$show);

		$this->display();



	}

	public function DDisplay(){
		checkItLogin();
		$name=session('name');
		$dname=I('dname');
		$m=M('mission');
		$count=$m->where("dname='{$dname}' and step in ('accept','finish') and solver='{$name}'")->order("step desc")->count();
		$page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$dm = $m->where("dname='{$dname}' and step in ('accept','finish') and solver='{$name}'")->order("step asc")->limit($page->firstRow,$page->listRows)->select();
		$this->assign("dm",$dm);
		$this->assign("title",$dname);

		$dma = $m->where("dname='{$dname}' and step='accept' and solver='{$name}'")->count();
		$dman = $m->where("dname='{$dname}' and step in ('accept','finish') and solver='{$name}'")->count();
		$dmfn = $m->where("dname='{$dname}' and step = 'finish' and solver='{$name}'")->count();
		$rate = 100*sprintf("%.4f",$dmfn/$dman)."%";

		$this->assign('dma',$dma);
		$this->assign('dman',$dman);
		$this->assign('dmfn',$dmfn);
		$this->assign('rate',$rate);


		$this->display();


	}

	public function sWeek(){
		checkItLogin();
		$name=session('name');
		$date=date('Y-m-d');  //当前日期
		$first=1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
		$w=date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
		//获取本周开始日期，如果$w是0，则表示周日，减去 6 天
		$start=date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); 
		$end=date('Y-m-d',strtotime("$start+6 days"));
		$m=M('mission');
		$count = $m->where("optime>='{$start}' and optime<='{$end}' and step in ('accept','finish') and solver='{$name}'")->count();
		$page=new Page($count,15);
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');
        $page->setConfig('link_page','页');
        $page->setConfig('theme','共%TOTAL_ROW%个任务 共%TOTAL_PAGE%页 %FIRST% %UP_PAGE% %DOWN_PAGE% %END% ');
		$week = $m->where("optime>='{$start}' and optime<='{$end}' and step in ('accept','finish') and solver='{$name}'")->limit($page->firstRow,$page->listRows)->select();
        $show=$page->show();
        $this->assign('page',$show);
        $this->assign('week',$week);
        $this->assign('title',"本周任务");

        $unfn = $m->where("optime>='{$start}' and optime<='{$end}' and step='accept' and solver='{$name}'")->count();
        $fn   = $m->where("optime>='{$start}' and optime<='{$end}' and step='finish' and solver='{$name}'")->count();
        $sum = $unfn+$fn;
        $rate = 100*sprintf("%.4f",$fn/$sum)."%";

        $this->assign('unfn',$unfn);
        $this->assign('fn',$fn);
        $this->assign('rate',$rate);
		$this->display();
	}

	public function sCustom(){ 
		checkItLogin();

		$this->assign('title','自定义统计');

		$this->display();
	}

	public function sCSearch(){
		checkItLogin();
		$name=session('name');
		$start=I('start');
		$end=I('end');
		$ptype = I('ptype');
		$m=M('mission');
		if(strtotime($start)>strtotime($end)){
			$this->error("起始时间不能大于终止时间");
		}
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
        $this->assign('time',$time);
        $this->assign('page',$show);
		$this->assign('title','时间查询');
		$this->display();
	}

	//导出自定义统计数据
	public function impStatic(){
		checkItLogin();
		$name=session('name');
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

		//各种问题此人的完成率
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

	//显示用户信息
	public function userinfo(){
		checkItLogin();
		$userid=session('id');
		// $name=session('name');
		$m=M('user');
		$user=$m->where("id='{$userid}'")->select();

		$this->assign('user',$user);
		$this->assign('title','用户信息');


		$this->display();
	}

	//编辑用户信息
	public function edituser(){
		checkItLogin();
		$userid=I('userid');
		$m=M('user');
		$user=$m->where("id='{$userid}'")->select();

		$this->assign('user',$user);
		$this->assign('title','编辑用户信息');
		$this->display();

	}

	public function updateuser(){
		checkItLogin();
		$userid=I('userid');
		$data['phone']=trim(I('phone'));
		$data['telephone']=trim(I('telephone'));
		$data['company']=trim(I('company'));
		$password=I('password');
		$repassword=I('repassword');
		if($password == $repassword){
			if(strlen($password)>8){
				$data['password']=md5(md5($password));
			}else{
				$this->error("密码长度要大于8位");
			}
			
		}else{
			$this->error("你输入的两次密码不相符");
		}
		$m=M('user');
		if($m->where("id='{$userid}'")->save($data)){
			$this->success("用户信息更新成功",U("userinfo",array('id',$userid)));
		}else{
			$this->error("信息更新有误");
		}
	}

	public function sysinfo(){
		$this->assign("title",'系统说明');
		$this->display();
	}

	public function help(){
		$this->assign('title','问题与帮助');
		$this->display();
	}



}//class_end


