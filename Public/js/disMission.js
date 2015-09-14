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
	else if(Form.ptype.value=="none"){
		alert('请选择问题类型');
		Form.ptype.focus();
		return false;
	}
	if(Form.tinfo.value=='' ||Form.tinfo.value==null){
		alert('请填写故障描述');
		Form.tinfo.focus();
		return false;
	}
	document.form1.action="tempSave";
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
	else if(Form.ptype.value=="none"){
		alert('请选择问题类型');
		Form.ptype.focus();
		return false;
	}
	if(Form.tinfo.value=='' ||Form.tinfo.value==null){
		alert('请填写故障描述');
		Form.tinfo.focus();
		return false;
	}
	document.form1.action="mIssue";
	document.form1.submit();
}

function smission(Form){
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
	else if(Form.ptype.value=="none"){
		alert('请选择问题类型');
		Form.ptype.focus();
		return false;
	}
	if(Form.tinfo.value=='' ||Form.tinfo.value==null){
		alert('请填写故障描述');
		Form.tinfo.focus();
		return false;
	}
	document.form1.action="saveM";
	document.form1.submit();
}












