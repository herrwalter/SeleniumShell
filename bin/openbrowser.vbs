Set objShell = WScript.CreateObject("WScript.Shell")
objShell.Run "iexplore.exe " & WScript.Arguments(0)
Wscript.Sleep 500
objShell.AppActivate "iexplore"
objShell.SendKeys "{F11}"

Wscript.Sleep 200
'Enable miserable ActiveX popup
objShell.SendKeys "{Tab}"
objShell.SendKeys "{Enter}"

