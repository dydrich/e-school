<?php

require_once "../lib/start.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", "0");

function getDirectory( $path = '.', $level = 0, $ftp_conn, $percorso, $locale ){
	//echo ftp_pwd($ftp_conn);

    $ignore = array( 'cgi-bin', '.', '..', 'upd.zip' );
    // Directories to ignore when listing output. Many hosts
    // will deny PHP access to the cgi-bin.

    $dh = @opendir( $path );
    // Open the directory to the handle $dh
    
    while( false !== ( $file = readdir( $dh ) ) ){
    // Loop through the directory
    
        if( !in_array( $file, $ignore ) ){
        // Check that this file is not to be ignored
            
            $spaces = str_repeat( '&nbsp;', ( $level * 4 ) );
            // Just to add spacing to the list, to better
            // show the directory tree.
            
            if( is_dir( "$path/$file" ) ){
            // Its a directory, so we need to keep reading down...
            
                echo "<strong>$spaces $file</strong><br />";
                getDirectory( "$path/$file", ($level+1), $ftp_conn, "$percorso/$file", "$locale/$file");
                // Re-call this same function but on a new directory.
                // this is what makes function recursive.
            
            } else {
            	ftp_chdir($ftp_conn, $percorso);
                echo "$spaces $file: ".ftp_pwd($ftp_conn)."<br />";
                if(ftp_put($ftp_conn, "$file", "$path/$file",  FTP_ASCII)){
                	echo "trasferimento di $path/$file ok";
                }
                else{
                	echo "trasferimento di $path/$file fallito";
                }
            
            }
        
        }
    
    }
    
    closedir( $dh );
    // Close the directory handle

} 

/*
$url 	= "ftp.rbachis.net";
$user 	= "rbachisn";
$pass 	= "6m96Z2jonE";
$dir	= "rclasse_updates";
$rfile  = "upd.zip";

$fc = ftp_connect($url) or die("Couldn't connect to $ftp_server"); 
if (ftp_login($fc, $user, $pass)) {
	echo "Connected as $user\n";
} 
else {
	echo "Couldn't connect as $ftp_user\n";
}
ftp_chdir($fc, $dir);
chdir("../downloads");
$lfile	= fopen("upd.zip", "w");
if(ftp_fget($fc, $lfile, $rfile, FTP_BINARY)){
	echo "trasferimento ok";
}
else{
	echo "trasferimento fallito";
}
ftp_close($fc);
fclose($lfile);

$zip = new ZipArchive();
if ($zip->open('upd.zip') === TRUE) {
	$zip->extractTo('.');
	$zip->close();
	echo 'ok';
} else {
	echo 'unzip failed';
}
*/
$method = "indirect";
if ( function_exists('getmyuid') && function_exists('fileowner') ){
	$temp_file_name = 'temp-write-test-';
	$temp_handle = @fopen($temp_file_name, 'w');
	if ( $temp_handle ) {
		if ( getmyuid() == @fileowner($temp_file_name) )
			$method = 'direct';
		@fclose($temp_handle);
		@unlink($temp_file_name);
	}
}
print "#####".$method."####";