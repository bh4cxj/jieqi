var tipname = "tips";
var tiptag = "a,label,div,img,span"; 
var tipx = 0;
var tipy = 15;
var tipobj = null;

function tipinit() {
	var tipNameSpaceURI = "http://www.w3.org/1999/xhtml";
	if(!tipContainerID){ var tipContainerID = tipname;}
	var tipContainer = document.getElementById(tipContainerID);

	if(!tipContainer) {
	  tipContainer = document.createElementNS ? document.createElementNS(tipNameSpaceURI, "div") : document.createElement("div");
		tipContainer.setAttribute("id", tipContainerID);
	  document.getElementsByTagName("body").item(0).appendChild(tipContainer);
	}

	if (!document.getElementById) return;
	tipobj = document.getElementById (tipname);
	if (tipobj) document.onmousemove = function (evt) {tipmove (evt)};

	var a, sTitle, elements;
	
	var elementList = tiptag.split(",");
	for(var j = 0; j < elementList.length; j++)
	{	
		elements = document.getElementsByTagName(elementList[j]);
		if(elements)
		{
			for (var i = 0; i < elements.length; i ++)
			{
				a = elements[i];
				sTitle = a.getAttribute("title");				
				if(sTitle)
				{
					a.setAttribute("tiptitle", sTitle);
					a.removeAttribute("title");
					a.removeAttribute("alt");
					a.onmouseover = function() {tipshow(this.getAttribute('tiptitle'))};
					a.onmouseout = function() {tiphide()};
				}
			}
		}
	}
}

function tipmove(evt) {
	var x=0, y=0;
	if (document.all) {
		x = (document.documentElement && document.documentElement.scrollLeft) ? document.documentElement.scrollLeft : document.body.scrollLeft;
		y = (document.documentElement && document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.body.scrollTop;
		x += window.event.clientX;
		y += window.event.clientY;
	} else {
		x = evt.pageX;
		y = evt.pageY;
	}
	tipobj.style.left = (x + tipx) + "px";
	tipobj.style.top = (y + tipy) + "px";
}

function tipshow(text) {
	if (!tipobj) return;
	tipobj.innerHTML = text;
	tipobj.style.display = "block";
}

function tiphide() {
	if (!tipobj) return;
	tipobj.innerHTML = "";
	tipobj.style.display = "none";
}

if (document.all){
	window.attachEvent('onload',tipinit);
}else{
	window.addEventListener('load',tipinit,false);
} 