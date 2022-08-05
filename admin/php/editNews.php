<?php
    include '../../db.php';
    include 'ftp.php';

    //get the newsID that is to be edited
    $newsID=iconv('iso-8859-2','utf-8',$_REQUEST["newsS"]) or die("Brak ID wpisu :(");
    $edit=iconv('iso-8859-2','utf-8',$_REQUEST["editNS"]) or die("Brak opcji edycji :(");
    $changeTo=iconv('iso-8859-2','utf-8',$_REQUEST[$edit]);

    //delete news
    if($edit=="delete"){
        askSQL("DELETE FROM Aktualnosc WHERE Id_Akt LIKE '". $newsID."'")  or die("<br>Usuwanie nie powiodło się :(");
        header("Location: /admin/controlpanel.php?success=true");
    }
    if($edit=="ncCov"){
        $destDir = "Przedszkole/miniaturyAlbumow";

        //upload the cover
        $filename = iconv('iso-8859-2','utf-8',$_FILES['cCov']['name']);
        $tmpName = basename($_FILES['cCov']['tmp_name']);
        move_uploaded_file($_FILES['cCov']['tmp_name'], $workDir."/".$tmpName) or die("Okładka- Cannot move uploaded file to working directory<br>");
        ftp_put($conn, $destDir."/".iconv('iso-8859-2','utf-8',$_FILES['cCov']['name']), $workDir."/".$tmpName, FTP_BINARY) or die("Okładka- Cannot upload <br>");
        unlink($workDir."/".$tmpName) or die("Cannot delete uploaded file from working directory -- manual deletion recommended");

        //insert into database
        askSQL(
            "INSERT INTO Zdjecie (Id_Alb, UrlZdj, czyOkladka) VALUES (NULL,'miniaturyAlbumow"."/".iconv('iso-8859-2','utf-8',$_FILES['nCov']['name'])."',1)");

        //get miniature ID for further operations
        $idMin= mysqli_fetch_array(askSQL("SELECT Id_Zdj FROM Zdjecie ORDER BY Id_Zdj DESC LIMIT 1"))[0];

        //update minature ID in aktualnosc
        askSQL("UPDATE Aktualnosc SET Id_Min=".$idMin." WHERE Id_Akt LIKE ".$newsID)  or die("<br>Zmiana nie powodła się :(");

        ftp_close($conn);
        //redirect back to controlPAnel
        header("Location: /admin/controlpanel.php?success=true");
    }
    
    askSQL("UPDATE Aktualnosc SET ".$edit."='".$changeTo."' WHERE  Id_Akt LIKE '".$newsID."'")  or die("<br>Zmiana nie powodła się :(");
    ftp_close($conn);
    header("Location: /admin/controlpanel.php?success=true");
?>