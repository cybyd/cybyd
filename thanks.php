<?php

// xDNS.ro & xLiST.ro

// xLiST .::. xDNS
// http://xDNS.ro/
// http://xLiST.ro/
// Modified By cybernet2u

if (isset($_POST["infohash"]))
{

  $THIS_BASEPATH = dirname(__FILE__);
  require("$THIS_BASEPATH/include/functions.php");
  include(load_language("lang_torrents.php"));
  dbconn();

  $uid = intval(0+$CURUSER['uid']);
  if(preg_match("/([a-z0-9]{40})/",$_POST['infohash']))
  {
  $infohash = ($_POST["infohash"]);
  } else {
  die('Hacking Attempt!');
  }

  $out="";

  $rt = @mysqli_query($GLOBALS["___mysqli_ston"], "SELECT uploader FROM {$TABLE_PREFIX}files WHERE info_hash=$infohash AND uploader=$uid");
  // he's not the uploader
  if (mysqli_num_rows($rt) == 0)
     $button = true;
  else
     $button = false;

// saying thank you.
  if (isset($_POST["thanks"]) && $button)
  {
	((mysqli_free_result($rt) || (is_object($rt) && (get_class($rt) == "mysqli_result"))) ? true : false);
	$rt = @mysqli_query($GLOBALS["___mysqli_ston"], "SELECT userid FROM {$TABLE_PREFIX}files_thanks WHERE userid=$uid AND infohash=$infohash");
	// never thanks for this file
	if (mysqli_num_rows($rt) == 0)
	{
           @@mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO {$TABLE_PREFIX}files_thanks (infohash, userid) VALUES ($infohash, $uid)");
	}
  }

((mysqli_free_result($rt) || (is_object($rt) && (get_class($rt) == "mysqli_result"))) ? true : false);
$rt = @mysqli_query($GLOBALS["___mysqli_ston"], "SELECT u.id, u.username, ul.prefixcolor, ul.suffixcolor FROM {$TABLE_PREFIX}files_thanks t LEFT JOIN {$TABLE_PREFIX}users u ON u.id=t.userid LEFT JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id WHERE infohash=$infohash");
  if (mysql_num_rows($rt) == 0)
     $out = $language["THANKS_BE_FIRST"];


  while ($ty = mysqli_fetch_assoc($rt))
    {
      if ($ty["id"] == $uid) // already thank
        $button = false;
      $out .= "<a href=\"$BASEURL/index.php?page=userdetails&amp;id=".$ty["id"]."\">".unesc($ty["prefixcolor"].$ty["username"].$ty["suffixcolor"])."</a> ";
  }
  if ($button && $CURUSER["uid"]>1)
     $out .= "|0";
  else
     $out .= "|1";

}
else
  $out = "no direct access!";

echo $out;
die;
?>
