<?php
    include '../../db.php';
    include 'ftp.php';

    //get all data passed from the form
    $newsTitle = iconv('iso-8859-2', 'utf-8', $_REQUEST["nName"]) or die("Brak tytułu :(");
    $newsContent= iconv('iso-8859-2', 'utf-8', $_REQUEST["nCont"]) or die("Brak treści :(");
    //folder to ftp news covers
    $destDir = "Przedszkole/miniaturyAlbumow";

    //upload the cover
    $filename = iconv('iso-8859-2','utf-8',$_FILES['nCov']['name']);
    if($filename!=NULL){
        $tmpName = basename($_FILES['nCov']['tmp_name']);
        move_uploaded_file($_FILES['nCov']['tmp_name'], $workDir."/".$tmpName) or die("Okładka- Cannot move uploaded file to working directory<br>");
        ftp_put($conn, $destDir."/".iconv('iso-8859-2','utf-8',$_FILES['nCov']['name']), $workDir."/".$tmpName, FTP_BINARY);
        unlink($workDir."/".$tmpName) or die("Cannot delete uploaded file from working directory -- manual deletion recommended");
        //insert into database
        askSQL(
            "INSERT INTO Zdjecie (Id_Alb, UrlZdj, czyOkladka) VALUES (NULL,'miniaturyAlbumow"."/".iconv('iso-8859-2','utf-8',$_FILES['nCov']['name'])."',1)");

        //get cover ID for further operations
        $idMin= mysqli_fetch_array(askSQL("SELECT Id_Zdj FROM Zdjecie ORDER BY Id_Zdj DESC LIMIT 1"))[0];
    }
    else {
        $idMin='NULL';
    }

    askSQL("INSERT INTO Aktualnosc (Id_Min,TrescAkt,TytulAkt) VALUES (".$idMin.",'" . $newsContent . "','" . $newsTitle . "')") or die('Błąd dodawania do bazy danych!');
    header("Location: /admin/controlpanel.php?success=true");
?>