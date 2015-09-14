function save(Form){
	if(Form.name.value=='' ||Form.name.value==null){
		alert('姓名不能为空！');
		Form.name.focus();
		return false;
	}
	else if(Form.dname.value=='none'){
		alert('请选择部门！');
		Form.dname.focus();
		return false;
	}
	else if(Form.subdname.value=='none'){
		alert('请选择处室！');
		Form.subdname.focus();
		return false;
	}
	else if(Form.distribute.value=="none"){
		alert('请选择分配人员');
		Form.distribute.focus();
		return false;
	}
	else if(Form.cdate.value=='' ||Form.cdate.value==null){
		alert('请选择会议开始的时间！');
		Form.cdate.focus();
		return false;
	}
	else if(Form.convtype.value=="none"){
		alert('请选择会议类型');
		Form.convtype.focus();
		return false;
	}
	else if(Form.croom.value=="none"){
		alert('请选择会议室');
		Form.croom.focus();
		return false;
	}
	 var ids = document.getElementsByName("isd");               
                var flag = false ;               
                for(var i=0;i<ids.length;i++){
                    if(ids[i].checked){
                        flag = true ;
                        break ;
                    }
                }
                if(!flag){
                    alert("请选择是否需要数据双流！");
                    return false ;
                }
     var ids = document.getElementsByName("ispc");               
                var flag = false ;               
                for(var i=0;i<ids.length;i++){
                    if(ids[i].checked){
                        flag = true ;
                        break ;
                    }
                }
                if(!flag){
                    alert("请选择是否自备电脑！");
                    return false ;
                }
  if(Form.participant.value=='' ||Form.participant.value==null){
		alert('参会者不能为空！');
		Form.participant.focus();
		return false;
	}

	document.form1.action='cpSave';
	document.form1.submit();
}



function issue(Form){
	if(Form.name.value=='' ||Form.name.value==null){
		alert('姓名不能为空！');
		Form.name.focus();
		return false;
	}
	else if(Form.dname.value=='none'){
		alert('请选择部门！');
		Form.dname.focus();
		return false;
	}
	else if(Form.subdname.value=='none'){
		alert('请选择处室！');
		Form.subdname.focus();
		return false;
	}
	else if(Form.distribute.value=="none"){
		alert('请选择分配人员');
		Form.distribute.focus();
		return false;
	}
	else if(Form.cdate.value=='' ||Form.cdate.value==null){
		alert('请选择会议开始的时间！');
		Form.cdate.focus();
		return false;
	}
	else if(Form.convtype.value=="none"){
		alert('请选择会议类型');
		Form.convtype.focus();
		return false;
	}
	else if(Form.croom.value=="none"){
		alert('请选择会议室');
		Form.croom.focus();
		return false;
	}
	 var ids = document.getElementsByName("isd");               
                var flag = false ;               
                for(var i=0;i<ids.length;i++){
                    if(ids[i].checked){
                        flag = true ;
                        break ;
                    }
                }
                if(!flag){
                    alert("请选择是否需要数据双流！");
                    return false ;
                }
     var ids = document.getElementsByName("ispc");               
                var flag = false ;               
                for(var i=0;i<ids.length;i++){
                    if(ids[i].checked){
                        flag = true ;
                        break ;
                    }
                }
                if(!flag){
                    alert("请选择是否自备电脑！");
                    return false ;
                }
     if(Form.participant.value=='' ||Form.participant.value==null){
		alert('参会人不能为空！');
		Form.participant.focus();
		return false;
	}
	document.form1.action='cpIssue';
	document.form1.submit();
}
