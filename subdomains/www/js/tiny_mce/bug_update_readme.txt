http://sourceforge.net/tracker/index.php?func=detail&aid=1581905&group_id=103281&atid=635682


Followups:

Comments

Date: 2006-11-20 07:27
Sender: barisozdil
Logged In: YES 
user_id=1649849
Originator: NO

I had the same issue and think I solved the problem. However I have only
tested on IE and Firefox.
The update fuction under TinyMCE_Menu where the html is generated for the
contextmenu is where the functionality is broken.
The entry which sets the event handlers on the html menu should be changed
such that the javascript for "onmouseup" and "onmousedown" is swapped.
In the update functions switch statement the following entry;

h += '<tr><td><a href="#" onclick="return tinyMCE.cancelEvent(event);"
onmousedown="return tinyMCE.cancelEvent(event);" onmouseup="' +
tinyMCE.xmlEncode(m[i].js) + ';return tinyMCE.cancelEvent(event);"><span'
+ c +'>' + t + '</span></a>';

should be changed to this;
h += '<tr><td><a href="#" onclick="return tinyMCE.cancelEvent(event);"
onmouseup="return tinyMCE.cancelEvent(event);" onmousedown="' +
tinyMCE.xmlEncode(m[i].js) + ';return tinyMCE.cancelEvent(event);"><span'
+ c +'>' + t + '</span></a>';

I'm using version 2.0.8

							

Date: 2006-10-21 11:18
Sender: khsjr1970
Logged In: YES 
user_id=1626451

I'd like to add that this also occurs on the tinymce test 
site.
							