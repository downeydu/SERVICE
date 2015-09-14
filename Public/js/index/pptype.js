function save(Form){
	if(Form.name.value=='' ||Form.name.value==null){
		alert('姓名不能为空2！');
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
	else if(Form.distreason.value=="none"){
		alert('请选择资产分配原因');
		Form.distreason.focus();
		return false;
	}

	 var ids = document.getElementsByName("protype[]");               
                var flag = false ;               
                for(var i=0;i<ids.length;i++){
                    if(ids[i].checked){
                        flag = true ;
                        break ;
                    }
                }
                if(!flag){
                    alert("请最少选择一项资产类别！");
                    return false ;
                }
	document.form1.action="ppSave";
	document.form1.submit();
}

function issue(Form){
	if(Form.name.value=='' ||Form.name.value==null){
		alert('姓名不能为空2！');
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
	else if(Form.distreason.value=="none"){
		alert('请选择资产分配原因');
		Form.distreason.focus();
		return false;
	}

	 var ids = document.getElementsByName("protype[]");               
                var flag = false ;               
                for(var i=0;i<ids.length;i++){
                    if(ids[i].checked){
                        flag = true ;
                        break ;
                    }
                }
                if(!flag){
                    alert("请最少选择一项资产类别！");
                    return false ;
                }
	document.form1.action='ppIssue';
	document.form1.submit();
}
