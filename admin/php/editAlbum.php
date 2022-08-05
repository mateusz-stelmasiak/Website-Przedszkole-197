<?php

    include '../../db.php';
    include 'ftp.php';

    //get the album that is to be edited
    $albumId=iconv('iso-8859-2','utf-8',$_REQUEST["albumS"])or die("Brak albumu do edycji");
    //get what is being edited
    $edit=iconv('iso-8859-2','utf-8',$_REQUEST["editS"]);
    //get new value of edited parameter
    $changeTo=iconv('iso-8859-2','utf-8',$_REQUEST[$edit]);

    //get ALbum name from Id_Alb
    $albumName=mysqli_fetch_array(askSQL("SELECT NazwaAlb FROM Album WHERE Id_Alb LIKE ".$albumId))[0];

    //ftp destination folder
    $destDir = "Przedszkole/galeria/".$albumName;

    //deleteAlbum
    if($edit=="delete")
    {
       ftp_rrmdir($conn, $destDir) or die("Nie można usunąć folderu!");
       askSQL("DELETE FROM Album WHERE Id_Alb LIKE ".$albumId)  or die("<br>Usuwanie nie powiodło się :(");
       //redirect back to controlPAnel
       header("Location: /admin/controlpanel.php?success=true");
    }
    //change album cover
    if($edit=="cCove")
    {
        //upload the cover
        $filename = iconv('iso-8859-2','utf-8',$_FILES['cCove']['name']);
        $tmpName = basename($_FILES['cCove']['tmp_name']);
        move_uploaded_file($_FILES['cCove']['tmp_name'], $workDir."/".$tmpName) or die("Okładka- Cannot move uploaded file to working directory<br>");
        ftp_put($conn, $destDir."/".iconv('iso-8859-2','utf-8',$_FILES['cCove']['name']), $workDir."/".$tmpName, FTP_BINARY) or die("Okładka- Cannot upload <br>");
        unlink($workDir."/".$tmpName) or die("Cannot delete uploaded file from working directory -- manual deletion recommended");

        //delete previous cover
        askSQL("UPDATE Zdjecie SET czyOkladka=0 WHERE Id_Alb LIKE ".$albumId)  or die("<br>Zmiana nie powodła się :(");

        //insert new one into database
        askSQL(
            "INSERT INTO Zdjecie (Id_Alb, UrlZdj, czyOkladka) VALUES ('".$albumId."','galeria/".$albumName."/".iconv('iso-8859-2','utf-8',$_FILES['cCove']['name'])."',1)");

        ftp_close($conn);
        //redirect back to controlPAnel
        header("Location: /admin/controlpanel.php?success=true");
    }

    if($edit=="aCon")
    {

    //loop through FILES and upload all of them
    // Count total files
    $countfiles = count($_FILES['file']['name']);
    for($i=0;$i<$countfiles;$i++){
        $filename = iconv('iso-8859-2','utf-8',$_FILES['file']['name'][$i]);
        // get temporary file name for the uploaded file
        $tmpName = basename($_FILES['file']['tmp_name'][$i]);
        // copy uploaded file into current directory
        move_uploaded_file($_FILES['file']['tmp_name'][$i], $workDir."/".$tmpName) or die("Plik $i- Cannot move uploaded file to working directory<br>");
        // perform file upload
        $upload = ftp_put($conn, $destDir."/".iconv('iso-8859-2','utf-8',$_FILES['file']['name'][$i]), $workDir."/".$tmpName, FTP_BINARY);
        // check upload status
          // delete local copy of uploaded file
        unlink($workDir."/".$tmpName) or die("Cannot delete uploaded file from working directory -- manual deletion recommended");
        //add to database
        askSQL("INSERT INTO `Zdjecie` (`Id_Alb`, `UrlZdj`) VALUES ('".$albumId."','galeria/".$albumName."/".iconv('iso-8859-2','utf-8',$_FILES['file']['name'][$i])."')");

    }
     ftp_close($conn);
    //redirect back to controlPAnel
    header("Location: /admin/controlpanel.php?success=true");
    }

    //deleting some of the contents
    if($edit=="cCon")
    {
       for($i=0;$i<$_COOKIE["toDelete"];$i++)
       {

         $idZdj=$_COOKIE["delete".strval($i)];
         //delete file from server
         $filePath= mysqli_fetch_array(askSQL("SELECT UrlZdj FROM Zdjecie WHERE Id_Zdj LIKE ".$idZdj))[0];
         ftp_delete($conn, "Przedszkole/". $filePath) or die("Nie można usunąć pliku!");
         //delete record from database
         askSQL("DELETE FROM Zdjecie WHERE Id_Zdj LIKE ".$idZdj) or die("<br>Usuwanie nie powiodło się :(");

       }
       ftp_close($conn);
       //redirect back to controlPAnel
       header("Location: /admin/controlpanel.php?success=true");
    }

    if($edit=="NazwaAlb")
    {
        $newDir="galeria/".$changeTo;
        $oldLen=strlen($albumName);
        //update folder name
        ftp_rename($conn, $destDir, "Przedszkole/galeria/".$changeTo);
        //update all the pictures links from the album
        askSQL("UPDATE Zdjecie SET UrlZdj=CONCAT('".$newDir."',SUBSTRING(UrlZdj,".strval(9+ $oldLen).", LENGTH(UrlZdj))) WHERE Id_Alb LIKE ".$albumId) or die("<br>Zmiana zdjęć nie powiodłą się :(");
    }

    //all the other parameters can just be changed
    askSQL("UPDATE Album SET ".$edit."='".$changeTo."' WHERE Id_Alb LIKE ".$albumId)  or die("<br>Zmiana nie powodła się :(");

    ftp_close($conn);
    //redirect back to controlPAnel
    header("Location: /admin/controlpanel.php?success=true");
?>