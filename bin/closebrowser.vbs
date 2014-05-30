Dim WshShell, oExec
Set WshShell = CreateObject("WScript.Shell")

Set oExec = WshShell.Exec("taskkill /fi ""imagename eq iexplore.exe""")

Do While oExec.Status = 0
     WScript.Sleep 100
Loop