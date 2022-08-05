<?php
    include '../../db.php';

    //get the album that is to be edited
    $groupName=iconv('iso-8859-2','utf-8',$_REQUEST["gName"]) or die("Brak nazwy Grupy :(");

    askSQL("INSERT INTO Grupa (NazwaGrp) VALUES ('".$groupName."')") or die('Błąd dodawania grupy!');
    header("Location: /admin/controlpanel.php");

?>