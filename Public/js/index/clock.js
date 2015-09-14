function clock(){
			var now=new Date();
			var hour=now.getHours();
			var min=now.getMinutes();
			var sec=now.getSeconds();			
			min=cktime(min);
			sec=cktime(sec);
			document.getElementById('txt').innerHTML=hour+":"+min+":"+sec;
			setTimeout("clock()",500);
			function cktime(t)
			{
				if(t<10)
				{
					t='0'+t;
				}
				return t;
			}
		}
window.onload=clock;