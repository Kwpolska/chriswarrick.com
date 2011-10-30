function getFile(filename, elementtoupdate)
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp=new XMLHttpRequest();
    }
    else
    {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById(elementtoupdate).innerHTML=xmlhttp.responseText;
        } else {
            document.getElementById(elementtoupdate).innerHTML='<img src="/images/spinner.gif" alt="Loading..."><br>Loading...<br>Please wait a short moment or use the direct non-AJAX URL.';
        }
    }
    var myDate = new Date();
    var myTime = myDate.getTime();
    var url=filename+'?rand='+myTime;
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}
