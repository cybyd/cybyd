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


function usertable($res, $frame_caption) {

 global $STYLEPATH, $extratpl, $language;

 $num = 0;
 $user=array();
 foreach ($res as $id=>$a) {
   $num++;

   if ($a["downloaded"]>0) {
     $ratio = $a["uploaded"] / $a["downloaded"];
     $ratio = number_format($ratio, 2);
   }
   else
     $ratio = $language["INFINITE"];

   $user[$num-1]["rank"]=$num;
   $user[$num-1]["username"]=($a["id"]>1?"<a href=\"index.php?page=userdetails&amp;id=" . $a["id"] . "\"><b>" . $a["username"] ."</b></a>":"<b>" . $a["username"] ."</b>");
   $user[$num-1]["uploaded"]=makesize($a["uploaded"]);
   $user[$num-1]["downloaded"]=makesize($a["downloaded"]);
   $user[$num-1]["ratio"]=$ratio;
   }

   $extratpl->set("language",$language);
   $extratpl->set("user",$user);
   return set_block($frame_caption,"center",$extratpl->fetch(load_template("extra-stats.user.tpl")));
}

function _torrenttable($rt, $frame_caption,$speed=false) {

 global $STYLEPATH, $extratpl, $language;

 $torrent=array();
 $num = 0;
 foreach ($rt as $id=>$a) {
     $num++;
     if ($a["leechers"]>0)
     {
       $r = $a["seeds"] / $a["leechers"];
       $ratio = number_format($r, 2);
     }
     else
       $ratio = $language["INFINITE"];

     $torrent[$num-1]["rank"]=$num;
     if ($GLOBALS["usepopup"])
         $torrent[$num-1]["filename"]="<a href=\"javascript:popdetails('index.php?page=details&amp;id=".$a['hash']."');\">".unesc($a["name"])."</a>";
     else
         $torrent[$num-1]["filename"]="<a href=\"index.php?page=details&amp;id=".$a['hash']."\">".unesc($a["name"])."</a>";

     $torrent[$num-1]["complete"]=number_format($a["finished"]);
     $torrent[$num-1]["seeds"]=number_format($a["seeds"]);
     $torrent[$num-1]["leechers"]=number_format($a["leechers"]);
     $torrent[$num-1]["peers"]=number_format($a["leechers"] + $a["seeds"]);
     $torrent[$num-1]["ratio"]=$ratio;
     if ($speed)
        $torrent[$num-1]["speed"]=makesize($a["speed"]);


   }

   $extratpl->set("language",$language);
   $extratpl->set("torrent",$torrent);
   $extratpl->set("DISPLAY_SPEED",$speed,true);
   $extratpl->set("DISPLAY_SPEED1",$speed,true);

   return set_block($frame_caption,"center",$extratpl->fetch(load_template("extra-stats.torrent.tpl")));

}


if ($XBTT_USE)
   {
    $tseeds="f.seeds+ifnull(x.seeders,0)";
    $tleechs="f.leechers+ifnull(x.leechers,0)";
    $tcompletes="f.finished+ifnull(x.completed,0)";
    $ttables="{$TABLE_PREFIX}files f LEFT JOIN xbt_files x ON x.info_hash=f.bin_hash";
    $udownloaded="u.downloaded+IFNULL(x.downloaded,0)";
    $uuploaded="u.uploaded+IFNULL(x.uploaded,0)";
    $utables="{$TABLE_PREFIX}users u LEFT JOIN xbt_users x ON x.uid=u.id";
   }
else
    {
    $tseeds="f.seeds";
    $tleechs="f.leechers";
    $tcompletes="f.finished";
    $ttables="{$TABLE_PREFIX}files f";
    $udownloaded="u.downloaded";
    $uuploaded="u.uploaded";
    $utables="{$TABLE_PREFIX}users u";
    }

$out="";

$cpage=get_cached_version("extra-stats".$CURUSER["id_level"]);
if ($cpage)
  {
    $out=$cpage;
    return;
}


$extratpl=new bTemplate();

// the display the box only if number of rows is > 0
if ($CURUSER["view_users"]=="yes")
{
  $r = get_result("SELECT u.username, $udownloaded as downloaded, $uuploaded as uploaded FROM $utables WHERE $uuploaded>0 ORDER BY $uuploaded DESC LIMIT 10",true,$CACHE_DURATION);
  if (count($r)>0) { $out.=usertable($r, $language["TOP_10_UPLOAD"]); $out.= "<br /><br />"; }
  $r = get_result("SELECT u.username, $udownloaded as downloaded, $uuploaded as uploaded FROM $utables WHERE $uuploaded>0 AND $udownloaded>0 ORDER BY $udownloaded DESC LIMIT 10",true,$CACHE_DURATION);
  if (count($r)>0) { $out.=usertable($r, $language["TOP_10_DOWNLOAD"]); $out.= "<br /><br />";}
  $r = get_result("SELECT u.username, $udownloaded as downloaded, $uuploaded as uploaded FROM $utables WHERE $udownloaded > 104857600 ORDER BY $uuploaded / if($udownloaded>0,$udownloaded,1) DESC, $uuploaded DESC LIMIT 10",true,$CACHE_DURATION);
  if (count($r)>0) { $out.=usertable($r, $language["TOP_10_SHARE"]." ".$language["MINIMUM_100_DOWN"].""); $out.= "<br /><br />";}
  $r = get_result("SELECT u.username, $udownloaded as downloaded, $uuploaded as uploaded FROM $utables WHERE $udownloaded > 104857600 ORDER BY $uuploaded / if($udownloaded>0,$udownloaded,1) ASC, $udownloaded DESC LIMIT 10",true,$CACHE_DURATION);
  if (count($r)>0) { $out.=usertable($r, $language["TOP_10_WORST"]." ".$language["MINIMUM_100_DOWN"].""); $out.= "<br /><br />"; }
 }
if ($CURUSER["view_torrents"]=="yes")
{
 $r = get_result("SELECT f.info_hash as hash, $tseeds as seeds, $tleechs as leechers, $tcompletes as finished, dlbytes as dwned , filename as name, url as url, info, speed as speed, uploader FROM $ttables ORDER BY $tseeds + $tleechs DESC LIMIT 10",true,$CACHE_DURATION);
  if (count($r)>0) { $out.=_torrenttable($r, $language["TOP_10_ACTIVE"]); $out.= "<br /><br />";}
 $r = get_result("SELECT f.info_hash as hash, $tseeds as seeds, $tleechs as leechers, $tcompletes as finished, dlbytes as dwned , filename as name, url as url, info, speed as speed, uploader FROM $ttables WHERE $tseeds >= 5 ORDER BY  seeds DESC, $tseeds / $tleechs DESC LIMIT 10",true,$CACHE_DURATION);
  if (count($r)>0) { $out.=_torrenttable($r, $language["TOP_10_BEST_SEED"]." (".$language["MINIMUM_5_SEED"].")"); $out.= "<br /><br />";}
 $r = get_result("SELECT f.info_hash as hash, $tseeds as seeds, $tleechs as leechers, $tcompletes as finished, dlbytes as dwned , filename as name, url as url, info, speed as speed, uploader FROM $ttables WHERE $tleechs >= 5 AND $tcompletes > 0 ORDER BY $tleechs DESC, $tseeds / $tleechs ASC LIMIT 10",true,$CACHE_DURATION);
  if (count($r)>0) { $out.=_torrenttable($r, $language["TOP_10_WORST_SEED"]." (".$language["MINIMUM_5_LEECH"].")"); $out.= "<br /><br />";}

if (!$XBTT_USE)
  {
   $r = get_result("SELECT f.info_hash as hash, $tseeds as seeds, $tleechs as leechers, $tcompletes as finished, dlbytes as dwned , filename as name, url as url, info, speed as speed, uploader FROM $ttables WHERE external='no' ORDER BY speed DESC, $tseeds DESC LIMIT 10",true,$CACHE_DURATION);
    if (count($r)>0) { $out.=_torrenttable($r, $language["TOP_10_BSPEED"],true); $out.= "<br /><br />";}
   $r = get_result("SELECT f.info_hash as hash, $tseeds as seeds, $tleechs as leechers, $tcompletes as finished, dlbytes as dwned , filename as name, url as url, info, speed as speed, uploader FROM $ttables WHERE external='no' ORDER BY speed ASC, $tseeds DESC LIMIT 10",true,$CACHE_DURATION);
    if (count($r)>0) { $out.=_torrenttable($r, $language["TOP_10_WSPEED"],true); $out.= "<br /><br />";}
  }
}

unset($r);

write_cached_version("extra-stats".$CURUSER["id_level"],$out);

?>
