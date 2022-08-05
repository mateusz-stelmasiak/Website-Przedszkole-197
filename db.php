<?php

    function askSQL($sql){
        //CREATE CONNECTION
        $servername = "mysql.nazwa.pl.server169532.nazwa.pl:3306";
        $username = "***";
        $password = "***";
        $dbname = "server169532_Przedszkole";

	    $conn = new mysqli($servername, $username, $password, $dbname);
	    $temp=$conn->query($sql);
	    $conn->close();
        return $temp;
    }


?>