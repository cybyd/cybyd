<?php

function getConnection () {

    $CURRENTPATH = dirname(__FILE__);

    include($CURRENTPATH."/settings.php"); # contains the given DB setup $database, $dbhost, $dbuser, $dbpass
    
    $Econn = ($GLOBALS["___mysqli_ston"] = mysqli_connect($dbhost,  $dbuser,  $dbpass));
    if (!$Econn) {
            echo "Connection to DB was not possible!";
            end;
        }
        if (!((bool)mysqli_query( $Econn, "USE $database"))) {
            echo "No DB with that name seems to exist on the server!";
            end;
        }
        
        if($GLOBALS["charset"]=="UTF-8")
		mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES utf8");
        
        return $Econn;
}

function getPrefix () {

    $CURRENTPATH = dirname(__FILE__);

    include($CURRENTPATH."/settings.php"); # contains the given DB setup $database, $dbhost, $dbuser, $dbpass
    
    return $TABLE_PREFIX;
}
?>
