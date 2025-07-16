Dim response, counter
counter = 0

Do
    If counter < 5 Then
        response = MsgBox("r u bald??", vbYesNo + vbQuestion, "Baldness Check")
    ElseIf counter < 10 Then
        response = MsgBox("r u sure ur not bald????", vbYesNo + vbQuestion, "Baldness Check")
    Else
        Do
            response = MsgBox("LIAR! UR THE BALDEST BAALD IN BALDIE UNIVERSE JAJAJAJAJAJAJ!", vbExclamation, "Baldness Confirmed")
        Loop
    End If

    counter = counter + 1
Loop
