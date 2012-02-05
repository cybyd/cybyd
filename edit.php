<?php
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



$link = urldecode($_GET["returnto"]);

if ($link=="")
   $link="index.php?page=torrents";

// save editing and got back from where i come

if ((isset($_POST["comment"])) && (isset($_POST["name"]))){

   if ($_POST["action"]==$language["FRM_CONFIRM"]) {

   if ($_POST["name"]=='')
        {
        stderr("Error!","You must specify torrent name.");
   }

   if ($_POST["comment"]=='')
        {
        stderr("Error!","You must specify description.");
   }

   $fname=htmlspecialchars(AddSlashes(unesc($_POST["name"])));
   $torhash=AddSlashes($_POST["info_hash"]);
   write_log("Modified torrent $fname ($torhash)","modify");
   do_sqlquery("UPDATE {$TABLE_PREFIX}files SET filename='$fname', comment='" . AddSlashes($_POST["comment"]) . "', category=" . intval($_POST["category"]) . " WHERE info_hash='" . $torhash . "'",true);
   redirect($link);
   exit();
   }

   else {
        redirect($link);
        exit();
   }
}

// view torrent's details
if (isset($_GET["info_hash"])) {

   if ($XBTT_USE)
      {
       $tseeds="f.seeds+ifnull(x.seeders,0) as seeds";
       $tleechs="f.leechers+ifnull(x.leechers,0) as leechers";
       $tcompletes="f.finished+ifnull(x.completed,0) as finished";
       $ttables="{$TABLE_PREFIX}files f LEFT JOIN xbt_files x ON x.info_hash=f.bin_hash";
      }
   else
       {
       $tseeds="f.seeds as seeds";
       $tleechs="f.leechers as leechers";
       $tcompletes="f.finished as finished";
       $ttables="{$TABLE_PREFIX}files f";
       }

  $query ="SELECT f.info_hash, f.filename, f.url, UNIX_TIMESTAMP(f.data) as data, f.size, f.comment, f.category as cat_name, $tseeds, $tleechs, $tcompletes, f.speed, f.uploader FROM $ttables WHERE f.info_hash ='" . AddSlashes($_GET["info_hash"]) . "'";
  $res = do_sqlquery($query,true);
  $results = mysql_fetch_assoc($res);

  if (!$results || mysql_num_rows($res)==0)
     err_msg($language["ERROR"],$language["TORRENT_EDIT_ERROR"]);

  else {

    if (!$CURUSER || $CURUSER["uid"]<2 || ($CURUSER["edit_torrents"]=="no" && $CURUSER["uid"]!=$results["uploader"]))
       {
           stderr($language["ERROR"],$language["CANT_EDIT_TORR"]);
       }

    $torrenttpl=new bTemplate();
    $torrenttpl->set("language",$language);
/*
    $s = "<select name=\"type\">\n<option value=\"0\">(".$language["CHOOSE_ONE"].")</option>\n";
    $cats = genrelist();

    foreach ($cats as $row) {
        $s .= "<option value=\"" . $row["id"] . "\"";
        if ($row["id"] == $results["cat_name"])
            $s .= " \"selected\"";
        $s .= ">" . unesc($row["name"]) . "</option>\n";
    }
    $s .= "</select>\n";
*/

    $torrent=array();
    $torrent["link"]="index.php?page=edit&info_hash=".$results["info_hash"]."&returnto=".urlencode($link);
    $torrent["filename"]=$results["filename"];
    $torrent["info_hash"]=$results["info_hash"];
    $torrent["description"]=textbbcode("edit","comment",unesc($results["comment"]));
    $torrent["size"]=makesize($results["size"]);

    include(dirname(__FILE__)."/include/offset.php");

    $torrent["date"]=date("d/m/Y",$results["data"]-$offset);
    $torrent["complete"]=$results["finished"]." ".$language["X_TIMES"];
    $torrent["peers"]=$language["SEEDERS"] .": " .$results["seeds"].",".$language["LEECHERS"] .": ". $results["leechers"]."=". ($results["leechers"]+$results["seeds"]). " ". $language["PEERS"];
    $torrent["cat_combo"]= categories($results["cat_name"]); //$s;

    $torrenttpl->set("torrent",$torrent);

    unset($results);
    mysql_free_result($res);

  }
}
?>