set a = createobject("wscript.shell")


dim result
result = msgbox("Hello! Do we start?", 4 , "SpamOur")

If result=6 then
msgbox "Ok! Let's Start!",,"SpamOur"
else
msgbox "Ok! Bye!",,"SpamOur"
WScript.Quit
end if


mytxt=inputbox("What text do you want to spam?","SpamOur","")
mynum=inputbox("How many times do you want to spam the text?","SpamOur","100") 
myspeed=inputbox("How fast to spam... In milisecs!","SpamOur","0") 
mywait=inputbox("How much time do you need to select the textbox?","SpamOur","5")

msgbox"You have " & mywait & " secs to put your mouse in the textbox!",,"SpamOur"
wscript.sleep (mywait*1000) 
for i=1 to mynum
	a.sendkeys (mytxt)
	a.sendkeys ("{ENTER}")
	wscript.sleep (myspeed)
next
msgbox"Finished Spamming!",,"SpamOur"