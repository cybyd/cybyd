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


$id = AddSlashes($_GET["id"]);
if (!isset($id) || !$id)
    die("Error ID");
$scriptname = htmlspecialchars($_SERVER["PHP_SELF"]."?history&id=$id");
$addparam = "";

// control if torrent exist in our db
$res = get_result("SELECT size FROM {$TABLE_PREFIX}files WHERE info_hash='$id'",true,$btit_settings['cache_duration']);

if ($res) {
   $row=$res[0];
   if ($row) {
      $tsize=0+$row["size"];
      }
}
else
    die("Error ID");

if ($XBTT_USE)
   $res = get_result("SELECT IF(h.active=1,'yes','no') as active, 'unknown' as agent, h.downloaded, h.uploaded, h.mtime as date, h.uid, u.username, c.name AS country, c.flagpic, ul.level, ul.prefixcolor, ul.suffixcolor FROM xbt_files_users h LEFT JOIN xbt_files xf ON xf.fid=h.fid LEFT JOIN {$TABLE_PREFIX}users u ON h.uid=u.id LEFT JOIN {$TABLE_PREFIX}countries c ON u.flag=c.id LEFT JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id WHERE HEX(xf.info_hash)='$id' AND h.completed=1 ORDER BY h.mtime DESC LIMIT 0,30",true,$btit_settings['cache_duration']);
else
    $res = get_result("SELECT h.*, u.username, c.name AS country, c.flagpic, ul.level, ul.prefixcolor, ul.suffixcolor FROM {$TABLE_PREFIX}history h LEFT JOIN {$TABLE_PREFIX}users u ON h.uid=u.id LEFT JOIN {$TABLE_PREFIX}countries c ON u.flag=c.id LEFT JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id WHERE h.infohash='$id' AND h.date IS NOT NULL ORDER BY date DESC LIMIT 0,30",true,$btit_settings['cache_duration']);


require(load_language("lang_history.php"));

$historytpl=new bTemplate();
$historytpl->set("language",$language);
$historytpl->set("history_script","index.php");

if (count($res)==0)
    $historytpl->set("NOHISTORY",TRUE,TRUE);
else
   {
    $historytpl->set("NOHISTORY",FALSE,TRUE);

    foreach ($res as $id=>$row)
    {
      if ($GLOBALS["usepopup"])
        {
        $history[$i]["USERNAME"]="<a href=\"javascript: windowunder('index.php?page=userdetails&amp;id=".$row["uid"]."')\">".unesc($row["username"])."</a>";
        $history[$i]["PM"]=(strtolower($row["username"])=="guest"?"":"<a href=\"javascript: windowunder('index.php?page=usercp&amp;do=pm&action=edit&uid=$CURUSER[uid]&what=new&to=".urlencode(unesc($row["username"]))."')\">".image_or_link("$STYLEPATH/images/pm.png","","PM")."</a>");
      }
      else
        {
        $history[$i]["USERNAME"]="<a href=\"index.php?page=userdetails&amp;id=".$row["uid"]."\">".unesc($row["username"])."</a>";
        $history[$i]["PM"]=(strtolower($row["username"])=="guest"?"":"<a href=\"index.php?page=usercp&amp;do=pm&action=edit&uid=$CURUSER[uid]&what=new&to=".urlencode(unesc($row["username"]))."\">".image_or_link("$STYLEPATH/images/pm.png","","PM")."</a>");
      }
      if ($row["flagpic"]!="")
        $history[$i]["FLAG"]="<img src=images/flag/".$row["flagpic"]." alt=".$row["country"]." />";
      else
        $history[$i]["FLAG"]="<img src=images/flag/unknown.gif alt=".$language["UNKNOWN"]." />";
      $history[$i]["ACTIVE"]=$row["active"];
      $history[$i]["CLIENT"]=htmlspecialchars($row["agent"]);
      $dled=makesize($row["downloaded"]);
      $upld=makesize($row["uploaded"]);
      $history[$i]["DOWNLOADED"]=$dled;
      $history[$i]["UPLOADED"]=$upld;
    //Peer Ratio
      if (intval($row["downloaded"])>0) {
         $ratio=number_format($row["uploaded"]/$row["downloaded"],2);}
      else {$ratio='&#8734;';}
      $history[$i]["RATIO"]=$ratio;
    //End Peer Ratio

      $history[$i]["FINISHED"]=get_elapsed_time($row["date"])." ago";
    $i++;
    }
}

if ($GLOBALS["usepopup"])
    $historytpl->set("BACK2","<br /><br /><center><a href=\"javascript:window.close()\"><tag:language.CLOSE /></a></center>");
else
   $historytpl->set("BACK2", "</div><br /><br /><center><a href=\"javascript: history.go(-1);\"><tag:language.BACK /></a>");
$historytpl->set("XBTT",!$XBTT_USE,TRUE);
$historytpl->set("XBTT2",!$XBTT_USE,TRUE);
$historytpl->set("history",$history);

?>