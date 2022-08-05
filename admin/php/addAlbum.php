<?php
    include '../../db.php';
    include 'ftp.php';

    $albumName=iconv('iso-8859-2','utf-8',$_REQUEST["aName"]) or die("Brak nazwy!");


    //for ftp transfer
    $destDir = "Przedszkole/galeria/".$albumName;
    //create destination directory if need be
    ftp_mkdir($conn, $destDir) or die("Album o tej nazwie już istnieje! (".$albumName.")");


    //calculate current accademic year of when the photos were taken
    $albumDate=$_REQUEST["aDate"];
    $cYear= intval(substr($albumDate,0,4));
    $cMonth= intval(substr($albumDate,5,7));
    if($cMonth>8){$academic=strval($cYear)."/".strval($cYear+1);}
    else{$academic=strval($cYear-1)."/".strval($cYear);}


    //create the album in database
    $albumDsc=iconv('iso-8859-2','utf-8',$_REQUEST["aDesc"]);
    $idOkl= mysqli_fetch_array(askSQL("SELECT YEAR(CURRENT_DATE)"))[0];
    askSQL(
    "INSERT INTO Album (NazwaAlb, DataAlb, OpisAlb, RokAkademicki)
        VALUES ('".$albumName."','".$albumDate ."','".$albumDsc."','".$academic."')");
    //get album ID for further operations
    $idAlb= mysqli_fetch_array(askSQL("SELECT Id_Alb FROM Album ORDER BY Id_Alb DESC LIMIT 1"))[0];

    //create appropriate entries in GroupAlbum table
   for($i=0;$i<$_COOKIE["groups"];$i++)
   {
       $idGrp=$_COOKIE["group".strval($i)];
       askSQL("INSERT INTO GrupyWAlbumie VALUES('".$idGrp."','".$idAlb."') ") or die("<br>Dodawanie grup nie powiodło się :(");
   }


    //upload the cover (seperate form)
    $filename = iconv('iso-8859-2','utf-8',$_FILES['aCov']['name']) or die("<br>Dodawanie grup nie powiodło się :(");
    $tmpName = basename($_FILES['aCov']['tmp_name']);
    move_uploaded_file($_FILES['aCov']['tmp_name'], $workDir."/".$tmpName) or die("Okładka- Cannot move uploaded file to working directory<br>");
    ftp_put($conn, $destDir."/".iconv('iso-8859-2','utf-8',$_FILES['aCov']['name']), $workDir."/".$tmpName, FTP_BINARY);
    unlink($workDir."/".$tmpName) or die("Cannot delete uploaded file from working directory -- manual deletion recommended");
    //insert into database
    askSQL(
        "INSERT INTO Zdjecie (Id_Alb, UrlZdj, czyOkladka) VALUES ('".$idAlb."','galeria/".$albumName."/".iconv('iso-8859-2','utf-8',$_FILES['aCov']['name'])."',1)");


    //loop through FILES and upload all of them
    //Count total files
    $countfiles = count($_FILES['file']['name']);
    for($i=0;$i<$countfiles;$i++){
        $filename = iconv('iso-8859-2','utf-8',$_FILES['file']['name'][$i]);
        // get temporary file name for the uploaded file
        $tmpName = basename($_FILES['file']['tmp_name'][$i]);
        // copy uploaded file into current directory
        move_uploaded_file($_FILES['file']['tmp_name'][$i], $workDir."/".$tmpName) or die("Plik $i- Cannot move uploaded file to working directory<br>");
        // perform file upload
        ftp_put($conn, $destDir."/".iconv('iso-8859-2','utf-8',$_FILES['file']['name'][$i]), $workDir."/".$tmpName, FTP_BINARY);
        // delete local copy of uploaded file
        unlink($workDir."/".$tmpName) or die("Cannot delete uploaded file from working directory -- manual deletion recommended");

        //add to database
        askSQL(
        "INSERT INTO `Zdjecie` (`Id_Alb`, `UrlZdj`) VALUES ('".$idAlb."','galeria/".$albumName."/".iconv('iso-8859-2','utf-8',$_FILES['file']['name'][$i])."')");
    }
    // close the FTP stream
    ftp_close($conn);
    header("Location: /admin/controlpanel.php");

?>