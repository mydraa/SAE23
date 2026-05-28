// copyright 2002 kai 'oswald' seidler, osw@ldism.de // published under the gnu public license, http://www.gnu.org

// (c) rasmus lerdorf
// taken from his famous "30 second AJAX Tutorial"
function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}
// rasmus end

function on(n,w)
{
	document.images[n].src="img/"+w+"_on.jpg";
}

function on2(n,w)
{
	img=eval("document."+n);
	img.src="img/"+w+"_on.jpg";
}

function off(n,w)
{
	document.images[n].src="img/"+w+"_off.jpg";
}

function onv4(n,w,l)
{
	document.images[n].src="/img/"+w+"_on-"+l+".jpg";
}

function offv4(n,w,l)
{
	document.images[n].src="/img/"+w+"_off-"+l+".jpg";
}

function onv5(n,w,l)
{
	img=eval("document."+w);
	img.src="/img/"+w+"_on-"+l+".jpg";
}

function offv5(n,w,l)
{
	img=eval("document."+w);
	img.src="/img/"+w+"_off-"+l+".jpg";
}


function highlight1(x)
{
	//x.style.background="#7c7c99";
	x.style.color="#ffffff";
}

function lowlight1(x)
{
	//x.style.background="#cccce9";
	x.style.color="#000000";
}


function highlight(x)
{
	//x.style.background="#d84f01";
	x.style.color="#ffffff";
}

function lowlight(x)
{
	//x.style.background="#f1b700";
	x.style.color="#000000";
}

function logout()
{
	return confirm("Sicher, dass Du Dich abmelden möchtest?");
}

function sure()
{
	return confirm("Sure?");
}

function stopround()
{
	return confirm("Sicher, dass Du Die Runde beenden möchtest?");
}

function cmsdialog(u)
{
	x=800;
	y=460;
	window.open(u,"","scrollbars=yes,resizable=yes,width="+x+",height="+y);
	return false;
}

function popupdialog(u)
{
	x=420;
	y=400;
	window.open(u,"","scrollbars=yes,resizable=yes,width="+x+",height="+y);
	return false;
}

function center () {
  if (document.layers) {
  	    windowWidth = window.innerWidth;
  }
  else if (document.all) {
  	    windowWidth = document.body.clientWidth;
  }
  else if (document.getElementById) {
  	    windowWidth = window.innerWidth;
  }
  return Math.floor(windowWidth / 2);
}

function cmslink(id)
{
        var xmlhttp = new createRequestObject();
        xmlhttp.open("GET", "http://www.apachefriends.org/xmllink.php?id="+id, false);
        xmlhttp.send(null);
        //document.location = xmlhttp.responseText;
	//alert(xmlhttp.responseText);
	return true;
}
