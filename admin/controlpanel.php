<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: /admin/index.html');
	exit;
}
?>
<style>
.inputButton{
    background-color:#d3d3d3;
    padding:7px;
}

img{
    height:100px;
    max-width:200px;
    order:1;
}

.preview{
background-color:white;
width:100%;
}
.viewpre{
background-color:white;
width:100%;
}

.albumList{
background-color:white;
width:100%;
}


.dropdown {
    float: left;
    overflow: hidden;
}

.dropdown .dropbtn {
    width=100%;
    font-size: 16px;
    border: none;
    outline: none;
    color: black;
    padding: 14px 16px;
    background-color:#d3d3d3;
    font-family: inherit;
    margin: 0;
}

.dropdown .dropbtn2 {
    font-size: 16px;
    border: none;
    outline: none;
    color: black;
    padding: 14px 16px;
    background-color: inherit;
    font-family: inherit;
    margin: 0;
}

.navbar a:hover, .dropdown:hover .dropbtn {
    background-color: red;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}
.dropdown-content input {
    float: none;
    color: black;
    text-decoration: none;
    display: inline-block;
}

.dropdown-content a {
    float: none;
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
}

.dropdown-content a:hover {
    background-color: #ddd;
}

.dropdown:hover .dropdown-content {
    display: block;
}


</style>

<!DOCTYPE html>
<meta charset="utf-8">
<html lang="pl-PL">
	<head>
		<meta charset="utf-8">
		<title>Panel Sterowania</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Przedszkole197</h1>
			</div>
		</nav>
		<div class="content">

			<p>Witaj w panelu administracyjnym <?=$_SESSION['name']?>!
			   <br>Poniżej znadują się narzędzia do obsługi galerii oraz aktualności.
			</p>

            <h2>Grupy w przedszkolu</h2>
            <div class="groups">
                Aktualne Grupy<br><br>
                <table>
                <tr style='width:25%;'>
                    <tr style='height:2vh'></tr>
                    <tr>
                        <td style='width:25%' ><b>Nazwa Grupy</b></td>
                    </tr>

                    <?php
                    include '../db.php';

                    $GROUPY = askSQL("SELECT NazwaGrp FROM Grupa");
                    while($grp = mysqli_fetch_array($GROUPY)){
                        echo" <tr style='horizontal-align: middle;''>
                                <td style='width:25%'>".$grp['NazwaGrp']."</td>
                               <tr>
                             ";
                    }
                    ?>
                </table>

            </div>

            <b>1.Stwórz nową grupę</b><br><br>
            <form action="php/addGroup.php"  enctype="multipart/form-data" method="post" style="display:inline-block; background-color:white;width:100%;padding:20px;" accept-charset="ISO-8859-2">
                <label for="gName">Nazwa Grupy</label> <input type="text" name="gName" id="gName" style="width:100%" required><br>
                <input type="submit" value="Stwórz Grupę" style="background-color:green;color:white;width:100%;height:5vh;">
            </form>

            <b>2.Edytuj Grupę</b><br><br>
            <form action="php/editGroup.php"  enctype="multipart/form-data" method="post" style="display:inline-block; background-color:white;width:100%;padding:20px;" accept-charset="ISO-8859-2">
                <label for="groupS">Wybierz Grupę</label>
                <select name="groupS" id="groupSelector" onchange="showGEditing()">
                    <?php
                    $GROUPS = askSQL("SELECT Id_Grp,NazwaGrp FROM Grupa");
                    while($group = mysqli_fetch_array($GROUPS)){
                        echo" <option value=".$group['Id_Grp'].">".$group['NazwaGrp']."</option>";
                    }
                    ?>
                </select><br>

                <label for="editGS">Wybierz zmianę</label>
                <select name="editGS" id="groupEditor" onchange="showGEditing()">
                    <option value="NazwaGrp">Zmień Nazwę</option>
                    <option value="delete">Usuń Grupę</option>
                </select><br>
                <div class='geditor' id='NazwaGrp' style="display:block">
                    <label for="NazwaGrp">Nowa nazwa </label> <input type="text" name="NazwaGrp" id="NazwaGrp"><br><br>
                </div>
                <input type="submit" value="Zapisz edycję" style="background-color:green;color:white;width:100%;height:5vh;">
            </form>

            <h2>Obsługa Aktualności </h2>
            <form action="php/addNews.php"  enctype="multipart/form-data" method="post" style="display:inline-block; background-color:white;width:100%;padding:20px;" accept-charset="ISO-8859-2" >
                <b>1.Stwórz nowy wpis</b><br><br>
                <label for="nName">Tytuł</label> <input type="text" name="nName" id="nName" required><br>
                <br>
                <label for="nCont">Treść</label> <textarea id="nCont" name="nCont" placeholder="(tu wpisz treść)" style="height:15vh;width:100%; white-space: pre-wrap;"></textarea> <br>
                <label for="nCov">Miniaturka</label> <input type="file" name="nCov" id="nCov"> <br><br>
                <input type="submit" value="Stwórz Wpis" style="background-color:green;color:white;width:100%;height:5vh;">
            </form>

            <b>2.Edytuj wpis</b><br><br>
            <form action="php/editNews.php"  enctype="multipart/form-data" method="post" style="display:inline-block; background-color:white;width:100%;padding:20px;" accept-charset="ISO-8859-2">
                <label for="newss">Wybierz wpis</label>
                <select name="newsS" id="newsSelector" onchange="showNEditing()">
                    <?php
                    $GROUPS = askSQL("SELECT Id_Akt,TytulAkt FROM Aktualnosc");
                    while($group = mysqli_fetch_array($GROUPS)){
                        echo" <option value=".$group['Id_Akt'].">".iconv('iso-8859-2', 'utf-8', $group['TytulAkt'])."</option>";
                    }
                    ?>
                </select><br>

                <label for="editNS">Wybierz zmianę</label>
                <select name="editNS" id="newsEditor" onchange="showNEditing()">
                    <option value="TytulAkt">Zmień tutuł</option>
                    <option value="TrescAkt">Zmień treść</option>
                    <option value="ncCov">Zmień miniaturkę</option>
                    <option value="delete">Usuń Wpis</option>
                </select><br>

                <div class='neditor' id='TytulAkt' style="display:block">
                    <label for="TytulAkt">Nowy tytuł </label> <input type="text" name="TytulAkt" id="TytulAkt"><br><br>
                </div>
                <div class='neditor' id='TrescAkt' style="display:none">
                    <b>Stara treść</b>
                    <?php
                    $CONTENTS= askSQL("SELECT Id_Akt,TrescAkt FROM Aktualnosc");
                    while( $cont=mysqli_fetch_array($CONTENTS)){
                        echo "<div class='ncontent' id='ncontent".$cont['Id_Akt']."' style='display:none;'>".iconv('iso-8859-2', 'utf-8', nl2br($cont['TrescAkt']))."</div>";
                    }
                    ?>
                    <br><br>
                    <label for="TrescAkt">Nowa treść</label>
                    <textarea id="TrescAkt" name="TrescAkt" style="height:15vh;width:100%;"></textarea><br><br>
                </div>
                <div class='neditor' id='ncCov' style="display:none">
                    <label for="cCov">Wybierz nową okładkę</label> <input type="file" name="cCov" id="cCov">
                </div>

                <input type="submit" value="Zapisz edycję" style="background-color:green;color:white;width:100%;height:5vh;">
            </form>



            <h2>Obsługa Galerii </h2>
			<div class="albumList">
			    Aktualne Albumy<br><br>
                    <table style='width:100%;'>
                        <tr style='height:2vh'></tr>
                        <tr>
                            <td style='width:25%' ><b>Nazwa Albumu</b></td>
                            <td style='width:25%' ><b>Opis</b></td>
                            <td style='width:25%'><b>Stworzony</b></td>
                            <td style='width:25%'><b>Ilość Zdjęć</b></td>
                        </tr>

                        <?php


                        $ALBUMS = askSQL("SELECT NazwaAlb, OpisAlb,Dodano ,LiczbaZdjec FROM Album ");
                        while($album = mysqli_fetch_array($ALBUMS)){
                            $albumDesc=$album['OpisAlb'];
                            if(strlen($albumDesc)>44){$albumDesc=substr($albumDesc,0,44)."...";}
                             echo" <tr style='horizontal-align: middle;''>
                                <td style='width:25%'>".$album['NazwaAlb']."</td>
                                <td style='width:25%'>".$albumDesc."</td>
                                <td style='width:25%'>".strval($album['Dodano'])."</td>
                                <td style='width:25%'>".$album['LiczbaZdjec']."</td>
                            </tr> ";
                        }
                        ?>

                    </table>
			</div>



            <form action="php/addAlbum.php"  enctype="multipart/form-data" method="post" style="display:inline-block; background-color:white;width:100%;padding:20px;" accept-charset="ISO-8859-2">
                <b>1.Stwórz nowy Album</b><br><br>
				<label for="aName">Nazwa</label> <input type="text" name="aName" id="aName" required><br>
				<label for="aDate">Data</label> <input type="date" name="aDate" id="aDate" required><br>
                <div class="dropdown">
                    <button id='allGalery' class="dropbtn" onClick="return false">Wybierz grupy<i class="fa fa-caret-down"></i></button>
                    <div class="dropdown-content">
                        <?php
                        $GROUPS = askSQL('SELECT DISTINCT Id_Grp,NazwaGrp FROM Grupa');
                        if($GROUPS != false)
                        {
                            while($group = mysqli_fetch_array($GROUPS))
                            {
                                echo "<label><input class='groupAdder' type=\"checkbox\" id='".$group['Id_Grp']."' onClick=\"addGroup(this)\">".$group['NazwaGrp']."</label><br>";
                            }
                        }
                        ?>
                    </div>
                </div>
				<br><br><br>
			    <label for="aDesc">Opis</label> <textarea id="aDesc" name="aDesc" placeholder="...(nie jest konieczny)" style="height:15vh;width:100%;"></textarea> <br>
			    <label for="aCov">Okładka</label> <input type="file" name="aCov" id="aCov" required> <br><br>
                <label for="file" class="inputButton">+Dodaj zdjęcia (PNG, JPG, itd.)</label> <input class="input" type="file" name="file[]" id="file" required multiple> <br><br>
                <div class="preview">
                    <p>Brak wybranych plików</p>
                </div>
				<input type="submit" value="Stwórz Album" style="background-color:green;color:white;width:100%;height:5vh;">
			</form>


            <form id="EditingForm" action="php/editAlbum.php"  enctype="multipart/form-data" method="post" style=" background-color:white;width:100%;padding:20px;" accept-charset="ISO-8859-2">
                <!--Check if there are any albums to edit first-->
                <?php
			    $SELECT = askSQL('SElECT Id_Alb FROM Album');
		        if(mysqli_num_rows($SELECT)==0){echo "<script>document.getElementById('EditingForm').style.display='none';</script>";}
			    ?>

                <b>2.Edytuj Album</b><br><br>
                    <label for="albumS">Wybierz Album</label>
                     <select name="albumS" id="albumSelector" onchange="showEditing()">
                     <?php
                        $ALBUMS = askSQL("SELECT Id_Alb, NazwaAlb FROM Album ");
                            while($album = mysqli_fetch_array($ALBUMS)){
                                 echo" <option value=".$album['Id_Alb'].">".$album['NazwaAlb']."</option>";
                            }
                     ?>
                    </select><br>

                    <label for="editS">Wybierz zmianę</label>
                    <select name="editS" id="albumEditor" onchange="showEditing()">
                        <option value="NazwaAlb">Zmień Nazwę</option>
                        <option value="OpisAlb">Zmień Opis</option>
                        <option value="cCove">Zmień okładkę</option>
                        <option value="DataAlb">Zmień datę</option>
                        <option value="aCon">Dodaj zdjęcia</option>
                        <option value="cCon">Usuń zdjęcia</option>
                        <option value="delete">Usuń album</option>
                    </select><br>


                     <div class='editingOptions' style='margin-top:3vh;'>
                        <div class='editor' id='NazwaAlb' style="display:block">
                            <label for="NazwaAlb">Nowa nazwa </label> <input type="text" name="NazwaAlb" id="NazwaAlb"><br><br>
                        </div>
                        <div class='editor' id='OpisAlb' style="display:none">
                            <b>Stary Opis</b>
                            <?php
                                 $DESCRIPTIONS= askSQL("SELECT Id_Alb,OpisAlb FROM Album");
                                 while( $desc=mysqli_fetch_array($DESCRIPTIONS)){
                                    echo "<div class='desriptor' id='desc".$desc['Id_Alb']."' style='display:none;'>".$desc['OpisAlb']."</div>";
                                 }
                              ?>
                             <br><br>
                            <label for="OpisAlb">Nowy Opis</label>
                            <textarea id="OpisAlb" name="OpisAlb" style="height:15vh;width:100%;"></textarea><br><br>
                        </div>
                         <div class='editor' id='cCove' style="display:none">
                            <label for="cCove">Wybierz nową okładkę</label> <input type="file" name="cCove" id="cCove">
                        </div>
                        <div class='editor' id='DataAlb' style="display:none">
                            <label for="DataAlb">Wybierz nową datę</label> <input type="date" name="DataAlb" id="DataAlb">
                        </div>
                        <div class='editor' id='aCon' style="display:none">
                            <label for="file2" class="inputButton">+Dodaj zdjęcia (PNG, JPG, itd.)</label>
                            <input class="input2" type="file" name="file[]" id="file2" multiple> <br><br>
                             <div class="preview2">
                                 <p>Brak wybranych plików</p>
                            </div>
                        </div>

                        <div class='editor' id='cCon' style="display:none">
                             <b>Aktualna zawartość</b><br>
                             Zaznacz (klikając na) zdjęcia, które należy usunąć a następnie zapisz edycję.
                             <?php
                                 //get all pictures from
                                 $ALBUMS = askSQL("SELECT Id_Alb FROM Album");
                                 while ($album = mysqli_fetch_array($ALBUMS))
                                 {
                                    $PICS = askSQL("SELECT DISTINCT Id_Zdj, UrlZdj, OpisZdj, z.Dodano FROM Zdjecie z WHERE z.Id_Alb LIKE '".$album['Id_Alb']."' AND z.czyOkladka LIKE 0");

                                    if($PICS!= false)
                                    {
                                        echo " <table class='albumContents' id='contents".$album['Id_Alb']."' style='width:100%;'><tr style='height:4vh'></tr>";
                                        $counter=0;
                                        $albumsperrow=6;
                                        while($pic = mysqli_fetch_array($PICS))
                                        {
                                        if($counter%$albumsperrow==0){echo "<tr style='max-height:7vh;''>";}
                                         echo "<td style='text-align: left;max-width:".strval(100/$albumsperrow)."%'>
                                                <div class='imgContainer' onClick='deleteFromAlbum(event)''>
                                                <img clicked='0' id='".$pic['Id_Zdj']."' class='myImg' src='../".$pic['UrlZdj']."' alt='".iconv('iso-8859-2','utf-8',$pic['OpisZdj'])." [".$pic['Dodano']."]'
                                                        style='height:100%;width:auto;'>
                                                </div>
                                                </td>
                                               ";
                                        $counter=$counter+1;
                                        if($counter%$albumsperrow==0){echo "</tr><tr style='height:2vh'></tr>";}
                                        }
                                        while($counter%$albumsperrow!=0){echo "<td style='text-align: left;width:".strval(100/$albumsperrow)."%'>"; $counter=$counter+1;}
                                        echo "</table>";
                                    }
                                 }
                              ?>
                        </div>




                    </div>
                    <input type="submit" value="Zapisz Edycję" style="background-color:green;color:white;width:100%;height:5vh;">

                </form>
        </div>

		</div>
	</body>
	<script>
        showAppropriateOldStuffs();
        showAppropriateOldStuffsN();

        function showAppropriateOldStuffsN()
        {
            var e = document.getElementById("newsSelector");
            var chosenOption= e.options[e.selectedIndex].value;

            //description
            var elements = document.getElementsByClassName("ncontent");
            for (var i = 0; i < elements.length; i++){
                elements[i].style.display = 'none';
            }
            document.getElementById("ncontent"+chosenOption).style.display= 'block';
        }

	    function showAppropriateOldStuffs()
	    {
	            var e = document.getElementById("albumSelector");
                var chosenOption= e.options[e.selectedIndex].value;

                //description
                var elements = document.getElementsByClassName("desriptor");
                 for (var i = 0; i < elements.length; i++){
                        elements[i].style.display = 'none';
                 }
                document.getElementById("desc"+chosenOption).style.display= 'block';

                //album contents
                 var elements = document.getElementsByClassName("albumContents");
                 for (var i = 0; i < elements.length; i++){
                        elements[i].style.display = 'none';
                 }
                document.getElementById("contents"+chosenOption).style.display= 'block';
	    }

	    //for debugging
        function createCookie(name, value, days) {
            var expires;
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
            }
            else {
                expires = "";
            }
            document.cookie = escape(name) + "=" +
                escape(value) + expires + "; path=/";
        }
        function getCookie(name) {
          const value = `; ${document.cookie}`;
          const parts = value.split(`; ${name}=`);
          if (parts.length === 2) return parts.pop().split(';').shift();
        }

	    function showEditing(){
	            var e = document.getElementById("albumEditor");
                var chosenOption= e.options[e.selectedIndex].value;

                 //hide all first
                 var elements = document.getElementsByClassName("editor");
                 for (var i = 0; i < elements.length; i++){
                        elements[i].style.display = 'none';
                 }
                 //if there aren't any albums hide the option to choose what is to be edited
                if (chosenOption!=""){
                    		//display the section pointed to by section argument
				document.getElementById(chosenOption).style.display= 'block';
                showAppropriateOldStuffs();

                }
	    }

	    function showGEditing() {
            var e = document.getElementById("groupEditor");
            var chosenOption = e.options[e.selectedIndex].value;

            //hide all first
            var elements = document.getElementsByClassName("geditor");
            for (var i = 0; i < elements.length; i++) {
                elements[i].style.display = 'none';
            }

            //if there aren't any albums hide the option to choose what is to be edited
            if (chosenOption != "") {
                //display the section pointed to by section argument
                document.getElementById(chosenOption).style.display = 'block';
            }
        }

        function showNEditing() {
            var e = document.getElementById("newsEditor");
            var chosenOption = e.options[e.selectedIndex].value;

            //hide all first
            var elements = document.getElementsByClassName("neditor");
            for (var i = 0; i < elements.length; i++) {
                elements[i].style.display = 'none';
            }

            //if there aren't any albums hide the option to choose what is to be edited
            if (chosenOption != "") {
                //display the section pointed to by section argument
                document.getElementById(chosenOption).style.display = 'block';
            }
            showAppropriateOldStuffsN();
        }
	</script>


    <script type="text/javascript">
        const input = document.querySelector('.input');
        const preview = document.querySelector('.preview');
        const input2 = document.querySelector('.input2');
        const preview2 = document.querySelector('.preview2');

        input.style.opacity = 0;
        input.addEventListener('change', updateImageDisplay);

        input2.style.opacity = 0;
        input2.addEventListener('change', updateImageDisplay2);

        function updateImageDisplay()
        {
          while(preview.firstChild) {
            preview.removeChild(preview.firstChild);
          }

          const curFiles = input.files;
          if(curFiles.length === 0) {
            const para = document.createElement('p');
            para.textContent = 'No files currently selected for upload';
            preview.appendChild(para);
          } else {
            const list = document.createElement('ol');
            preview.appendChild(list);

            for(const file of curFiles) {
              const listItem = document.createElement('li');
              const para = document.createElement('p');
              if(validFileType(file)) {
                para.textContent = `File name ${file.name}, file size ${returnFileSize(file.size)}.`;
                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                listItem.appendChild(image);
                listItem.appendChild(para);
              } else {
                para.textContent = `File name ${file.name}: Not a valid file type. Update your selection.`;
                listItem.appendChild(para);
              }

              list.appendChild(listItem);
            }
          }
        }

      function updateImageDisplay2()
        {
          while(preview2.firstChild) {
            preview2.removeChild(preview2.firstChild);
          }

          const curFiles = input2.files;
          if(curFiles.length === 0) {
            const para = document.createElement('p');
            para.textContent = 'No files currently selected for upload';
            preview2.appendChild(para);
          } else {
            const list = document.createElement('ol');
            preview2.appendChild(list);

            for(const file of curFiles) {
              const listItem = document.createElement('li');
              const para = document.createElement('p');
              if(validFileType(file)) {
                para.textContent = `File name ${file.name}, file size ${returnFileSize(file.size)}.`;
                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                listItem.appendChild(image);
                listItem.appendChild(para);
              } else {
                para.textContent = `File name ${file.name}: Not a valid file type. Update your selection.`;
                listItem.appendChild(para);
              }

              list.appendChild(listItem);
            }
          }
        }

        const fileTypes = [
          "image/apng",
          "image/bmp",
          "image/gif",
          "image/jpeg",
          "image/pjpeg",
          "image/png",
          "image/svg+xml",
          "image/tiff",
          "image/webp",
          "image/x-icon"
        ];

        function validFileType(file) {
          return fileTypes.includes(file.type);
        }

        function returnFileSize(number) {
          if(number < 1024) {
            return number + 'bytes';
          } else if(number >= 1024 && number < 1048576) {
            return (number/1024).toFixed(1) + 'KB';
          } else if(number >= 1048576) {
            return (number/1048576).toFixed(1) + 'MB';
          }
        }


    </script>
    <script type="text/javascript">
        var groupsToAdd=0;
        //get all divs
        var elements = document.getElementsByClassName("groupAdder");
        //click em all
        for (var i = 0; i < elements.length; i++) {
            elements[i].click();

        }
        groupsToAdd=elements.length;
        createCookie('groups',groupsToAdd,1);

        function addGroup(e)
        {
            var target = e;

            //select it and add a cookie to dele list
            if(target.checked)
            {
                createCookie('group'+groupsToAdd,target.getAttribute('id'),1);
                groupsToAdd=groupsToAdd+1;
            }

            else if(!target.checked)
            {


                //search for the right cookie to delete
                for(i=0;i<groupsToAdd;i++)
                {
                    if(getCookie('group'+i)==target.getAttribute('id'))
                    {
                        //transpose all the other cookies down
                        for(j=i;j<groupsToAdd;j++)
                        {
                            cookieplusone=(j+1);
                            createCookie('group'+j,getCookie('group'+cookieplusone),1);
                        }
                        break;
                    }
                }
                groupsToAdd=groupsToAdd-1;
                //delete last cookie
                createCookie('group'+groupsToAdd,-1,-1);
            }

            //update the number to delete cookie
            createCookie('groups',groupsToAdd,1);
        }
    </script>



      <script type="text/javascript">
        var numberToDelete=0;

        //if any cookies
        for(i=0;i<getCookie('toDelete');i++)
        {
             createCookie('delete'+i,-1,-1);
        }
        createCookie('toDelete',numberToDelete,1);

        function deleteFromAlbum(e)
        {
            var target = e.target;
            //select it and add a cookie to dele list
            if(target.getAttribute('clicked')=="0")
            {
               target.style.opacity='0.3';
               target.setAttribute('clicked','1');
               createCookie('delete'+numberToDelete,target.getAttribute('id'),1);
               numberToDelete=numberToDelete+1;
            }

            else if(target.getAttribute('clicked')=="1")
            {
                target.style.opacity='1';
                target.setAttribute('clicked','0');
                //search for the right cookie to delete
                for(i=0;i<numberToDelete;i++)
                {
                    if(getCookie('delete'+i)==target.getAttribute('id'))
                    {
                        //transpose all the other cookies down
                        for(j=i;j<numberToDelete;j++)
                        {
                           cookieplusone=(j+1);
                           createCookie('delete'+j,getCookie('delete'+cookieplusone),1);
                        }
                        break;
                    }
                }

                numberToDelete=numberToDelete-1;
                //delete last cookie
                createCookie('delete'+numberToDelete,-1,-1);

            }

             //update the number to delete cookie
             createCookie('toDelete',numberToDelete,1);
        }
    </script>
</html>