function showSub(str)
{ 
	if (str=="")
		{
		document.getElementById("subd").innerHTML="";
		return;
		} 
			xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	 {
	 	alert ("Browser does not support HTTP Request")
	 return
	 }
	var url="getSub"
	url=url+"?q="+str
	// alert(url)
	// exit;
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,false)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 document.getElementById("subd").innerHTML=xmlHttp.responseText 
 } 
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
