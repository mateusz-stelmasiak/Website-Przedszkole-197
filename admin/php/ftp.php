<?php
    //ftp acces parameters
    $host = "ftp.server169532.nazwa.pl";
    $user = "***";
    $pass = "***";
    $workDir = "/home/server169532/ftp/Przedszkole/temp";
    // open connection
    $conn = ftp_connect($host) or die ("Cannot initiate connection to host");
    // send access parameters
    ftp_login($conn, $user, $pass) or die("Cannot login");

    // Function to delete an entire folder
    function ftp_rrmdir($conn_id, $directory){
        $lists = ftp_mlsd($conn_id, $directory);
        unset($lists[0]);
        unset($lists[1]);

        foreach($lists as $list){
            $full = $directory . '/' . $list['name'];

            if($list['type'] == 'dir'){
                ftp_rrmdir($conn_id, $full);
            }else{
                ftp_delete($conn_id, $full);
            }
        }

        ftp_rmdir($conn_id, $directory);
        return true;
    }
?>