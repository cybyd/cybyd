<?php

// CyBerFuN.ro & xList.ro

// xList .::. xDNS
// http://xDNS.ro/
// http://xLIST.ro/
// Modified By cybernet2u

require_once ("include/functions.php");
require_once ("include/config.php");
dbconn();
  if ($CURUSER["uid"] > 1)
    {
  $uid=$CURUSER['uid'];
  //$r=do_sqlquery("SELECT * from {$TABLE_PREFIX}users where id=$uid");
  //$c=mysql_result($r,0,"seedbonus");
  $c=$CURUSER["seedbonus"];
if($c>=$GLOBALS["price_ct"]) {
          if (isset($_POST["title"])) $custom=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST["title"])
             else $custom = "";
    if ("$custom"=="")
        {
          do_sqlquery("UPDATE {$TABLE_PREFIX}users SET custom_title=NULL WHERE id='".$userid."'");
        }
    else
        {
          do_sqlquery("UPDATE {$TABLE_PREFIX}users SET custom_title='".htmlspecialchars($custom)."' WHERE id=$CURUSER[uid]");
        }
        $p=$GLOBALS["price_ct"];
        do_sqlquery("UPDATE {$TABLE_PREFIX}users SET seedbonus=seedbonus-$p WHERE id=$CURUSER[uid]");
        }
header("Location: index.php?page=modules&module=seedbonus");
   }
else header("Location: index.php");
?>
