function finish(Form)
{
	if(Form.brand.value=='' ||Form.brand.value==null){
		alert('品牌型号不能为空！');
		Form.brand.focus();
		return false;
	}
	else if(Form.sn.value=='' ||Form.sn.value==null){
		alert('序列号不能为空！');
		Form.sn.focus();
		return false;
	}
	else if(Form.treason.value=='' ||Form.treason.value==null){
		alert('请输入故障原因！');
		Form.treason.focus();
		return false;
	}
	else if(Form.method.value=='' ||Form.method.value==null){
		alert('请输入解决方法！');
		Form.method.focus();
		return false;
	}

	document.form1.action="/itservice/Support/judge";
	document.form1.submit();

}


function change()
{
	
	document.form1.action="/itservice/Support/change";
	document.form1.submit();

}
