function isnull(Form)
{
	if(Form.username.value=='' ||Form.username.value==null){
		alert('用户名不能为空！');
		Form.username.focus();
		return false;
	}
	else if(Form.password.value=='' ||Form.password.value==null){
		alert('密码不能为空！');
		Form.password.focus();
		return false;
	}
	else if(Form.verify.value=='' ||Form.verify.value==null){
		alert('请输入验证码！');
		Form.verify.focus();
		return false;
	}
}