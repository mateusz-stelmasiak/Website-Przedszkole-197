<?php
    include '../../db.php';

    //get the group that is to be edited
    $groupName=iconv('iso-8859-2','utf-8',$_REQUEST["groupS"]) or die("Brak nazwy Grupy :(");
    $edit=iconv('iso-8859-2','utf-8',$_REQUEST["editGS"]) or die("Brak opcji edycji :(");
    $changeTo=iconv('iso-8859-2','utf-8',$_REQUEST[$edit]);

    //delete group
    if($edit=="delete"){
        askSQL("DELETE FROM Grupa WHERE Id_Grp LIKE '".$groupName."'")  or die("<br>Usuwanie nie powiodło się :(");
        header("Location: /admin/controlpanel.php");
    }

    //update appropriate parameter
    askSQL("UPDATE Grupa SET ".$edit."='".$changeTo."' WHERE  Id_Grp LIKE '".$groupName."'")  or die("<br>Zmiana nie powodła się :(");
    header("Location: /admin/controlpanel.php?success=true");
?>