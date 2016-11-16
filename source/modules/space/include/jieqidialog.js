var ModalDialogWindow;
var ModalDialogInterval;
var ModalDialog = new Object;

ModalDialog.value = '';
ModalDialog.eventhandler = '';
 

function ModalDialogMaintainFocus()
{
  try
  {
    if (ModalDialogWindow.closed)
     {
        window.clearInterval(ModalDialogInterval);
        eval(ModalDialog.eventhandler);       
        return;
     }
    ModalDialogWindow.focus(); 
  }
  catch (everything) {   }
}
        
 function ModalDialogRemoveWatch()
 {
    ModalDialog.value = '';
    ModalDialog.eventhandler = '';
 }
        
 function ModalDialogShow(Title,BodyText,Buttons,EventHandler)
 {

   ModalDialogRemoveWatch();
   ModalDialog.eventhandler = EventHandler;

   var args='width=350,height=125,left=325,top=300,toolbar=0,channelmode=0';
       args+='location=0,status=0,menubar=0,scrollbars=1,resizable=0';  

   ModalDialogWindow=window.open("","",args); 
   ModalDialogWindow.document.open(); 
   ModalDialogWindow.document.write('<html>');
   ModalDialogWindow.document.write('<head>'); 
   ModalDialogWindow.document.write('<title>' + Title + '</title>');
   ModalDialogWindow.document.write('<script' + ' language=JavaScript>');
   ModalDialogWindow.document.write('function CloseForm(Response) ');
   ModalDialogWindow.document.write('{ ');
   ModalDialogWindow.document.write(' window.close(); ');
   ModalDialogWindow.document.write('} ');
   ModalDialogWindow.document.write('</script' + '>');        
   ModalDialogWindow.document.write('</head>');   
   ModalDialogWindow.document.write('<body onblur="window.focus();">');
   ModalDialogWindow.document.write('<table border=0 width="95%" align=center cellspacinb=0 cellpaddinb=2>');
   ModalDialogWindow.document.write('<tr><td align=left>' + BodyText + '</td></tr>');
   ModalDialogWindow.document.write('<tr><td align=left><br></td></tr>');
   ModalDialogWindow.document.write('<tr><td align=center>' + Buttons + '</td></tr>');
   ModalDialogWindow.document.write('</body>');
   ModalDialogWindow.document.write('</html>'); 
   ModalDialogWindow.document.close(); 
   ModalDialogWindow.focus(); 
   ModalDialogInterval = window.setInterval("ModalDialogMaintainFocus()",5);

 }


