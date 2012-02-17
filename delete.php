<?php

// CyBerFuN.ro & xList.ro

// xList .::. xDNS
// http://xDNS.ro/
// http://xLIST.ro/
// Modified By cybernet2u

/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2012  Btiteam
//
//    This file is part of xbtit.
//
// Redistribution and use in source and binary forms, with or without modification,
// are permitted provided that the following conditions are met:
//
//   1. Redistributions of source code must retain the above copyright notice,
//      this list of conditions and the following disclaimer.
//   2. Redistributions in binary form must reproduce the above copyright notice,
//      this list of conditions and the following disclaimer in the documentation
//      and/or other materials provided with the distribution.
//   3. The name of the author may not be used to endorse or promote products
//      derived from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED
// WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
// MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
// IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
// TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
// PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
// LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
// EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
////////////////////////////////////////////////////////////////////////////////////


if (!defined("IN_BTIT"))
      die("non direct access!");


$id = mysql_real_escape_string($_GET["info_hash"]);

if (!isset($id) || !$id)
    die("Error ID");

// Torrent Image Upload by Real_ptr / start
// f.screen1, f.screen2, f.screen3, f.image,
// Torrent Image Upload by Real_ptr / end

if ($XBTT_USE)
   $res = do_sqlquery("SELECT f.info_hash, f.uploader, f.filename, f.screen1, f.screen2, f.screen3, f.image, f.url, UNIX_TIMESTAMP(f.data) as data, f.size, f.comment, c.name as cat_name, f.seeds+ ifnull(x.seeders,0) as seeds, f.leechers+ ifnull(x.leechers,0) as leechers, f.finished+ ifnull(x.completed,0) as finished, f.speed FROM {$TABLE_PREFIX}files f LEFT JOIN xbt_files x ON x.info_hash=f.bin_hash LEFT JOIN {$TABLE_PREFIX}categories c ON c.id=f.category WHERE f.info_hash ='" . $id . "'",true);
else
   $res = do_sqlquery("SELECT f.info_hash, f.uploader, f.filename, f.screen1, f.screen2, f.screen3, f.image, f.url, UNIX_TIMESTAMP(f.data) as data, f.size, f.comment, c.name as cat_name, f.seeds, f.leechers, f.finished, f.speed FROM {$TABLE_PREFIX}files f LEFT JOIN {$TABLE_PREFIX}categories c ON c.id=f.category WHERE f.info_hash ='" . $id . "'",true);

$row = mysql_fetch_assoc($res);
// Torrent Image Upload by Real_ptr / start
$image_drop = "" . $row["image"]. "";
$image_drop1 = "" . $row["screen1"]. "";
$image_drop2 = "" . $row["screen2"]. "";
$image_drop3 = "" . $row["screen3"]. "";
// Torrent Image Upload by Real_ptr / end
if (!$CURUSER || $CURUSER["uid"]<2 || ($CURUSER["delete_torrents"]!="yes" && $CURUSER["uid"]!=$row["uploader"]))
   {
   stderr($language["SORRY"],$language["CANT_DELETE_TORRENT"]);
}

$scriptname = htmlspecialchars($_SERVER["PHP_SELF"]);

$link = urldecode($_GET["returnto"]);
$hash = AddSlashes($_GET["info_hash"]);

if ($link=="")
   $link="index.php?page=torrents";

if (isset($_POST["action"])) {

   if ($_POST["action"]==$language["FRM_DELETE"]) {

      $ris = do_sqlquery("SELECT info_hash,filename,url FROM {$TABLE_PREFIX}files WHERE info_hash=\"$hash\"",true);
      if (mysql_num_rows($ris)==0)
            {
            stderr("Sorry!", "torrent $hash not found.");
            }
      else
            {
            list($torhash,$torname,$torurl)=mysql_fetch_array($ris);
            }
// Torrent Image Upload by Real_ptr / start
      if (!empty($image_drop))
        @unlink("".$GLOBALS["uploaddir"]."$image_drop");
      if (!empty($image_drop1))
        @unlink("".$GLOBALS["uploaddir"]."$image_drop1");
      if (!empty($image_drop2))
        @unlink("".$GLOBALS["uploaddir"]."$image_drop2");
      if (!empty($image_drop3))
        @unlink("".$GLOBALS["uploaddir"]."$image_drop3");
// Torrent Image Upload by Real_ptr / end
      write_log("Deleted torrent $torname ($torhash)","delete");

      @mysql_query("DELETE FROM {$TABLE_PREFIX}files WHERE info_hash=\"$hash\"");
      @mysql_query("DELETE FROM {$TABLE_PREFIX}timestamps WHERE info_hash=\"$hash\"");
      @mysql_query("DELETE FROM {$TABLE_PREFIX}comments WHERE info_hash=\"$hash\"");
      @mysql_query("DELETE FROM {$TABLE_PREFIX}ratings WHERE infohash=\"$hash\"");
      @mysql_query("DELETE FROM {$TABLE_PREFIX}peers WHERE infohash=\"$hash\"");
      @mysql_query("DELETE FROM {$TABLE_PREFIX}history WHERE infohash=\"$hash\"");

      IF ($XBTT_USE)
          mysql_query("UPDATE xbt_files SET flags=1 WHERE info_hash=UNHEX('$hash')") or die(mysql_error());

      unlink($TORRENTSDIR."/$hash.btf");

      redirect($link);
      exit();

   }

   else {

   redirect($link);
   exit();

   }

}


$torrenttpl=new bTemplate();
$torrenttpl->set("language",$language);

$torrent=array();
$torrent["filename"]=$row["filename"];
$torrent["info_hash"]=$row["info_hash"];
$torrent["description"]=format_comment($row["comment"]);
$torrent["catname"]=$row["cat_name"];
$torrent["size"]=makesize($row["size"]);
include(dirname(__FILE__)."/include/offset.php");
$torrent["date"]=date("d/m/Y",$row["data"]-$offset);
if (!$XBTT_USE)
{
   if ($row["speed"] < 0) {
     $speed = "N/D";
   }
   else if ($row["speed"] > 2097152) {
     $speed = round($row["speed"]/1048576,2) . " MB/sec";
   }
   else {
     $speed = round($row["speed"] / 1024, 2) . " KB/sec";
   }
   $torrenttpl->set("NO_XBBT",true,true);
}
else
   $torrenttpl->set("NO_XBBT",false,true);

$torrent["speed"]=$speed;
$torrent["complete"]=$row["finished"];
$torrent["peers"]=$language["PEERS"]." :" .$row["seeds"].",".$language["LEECHERS"] .": ". $row["leechers"]."=". ($row["leechers"]+$row["seeds"]). " ". $language["PEERS"];
$torrent["return"]=urlencode($link);

unset($row);
mysql_free_result($res);

$torrenttpl->set("torrent",$torrent);

?>
