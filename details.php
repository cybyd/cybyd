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


$id = AddSlashes((isset($_GET["id"])?$_GET["id"]:false));

if (!isset($id) || !$id)
    stderr($language["ERROR"],$language["ERROR_ID"].": $id",$GLOBALS["usepopup"]);

require_once(load_language("lang_torrents.php"));

if (isset($_GET["act"]) && $_GET["act"]=="update")
   {
       //die("<center>".$language["TORRENT_UPDATE"]."</center>");
       require_once(dirname(__FILE__)."/include/getscrape.php");

       scrape(urldecode($_GET["surl"]),$id);

       redirect("index.php?page=torrent-details&id=$id");
       exit();
   }


if (isset($_GET["vote"]) && $_GET["vote"]==$language["VOTE"])
   {
   if (isset($_GET["rating"]) && $_GET["rating"]==0)
   {
        err_msg($language["ERROR"],$language["ERR_NO_VOTE"],$GLOBALS["usepopup"]);
        stdfoot(($GLOBALS["usepopup"]?false:true),false);
        exit();
   }
   else {
      do_sqlquery("INSERT INTO {$TABLE_PREFIX}ratings SET infohash='$id',userid=$CURUSER[uid],rating=".intval($_GET["rating"]).",added='".time()."'",true);
      redirect("index.php?page=torrent-details&id=$id");
      exit();
   }
   exit();
}

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


if(!$CURUSER || $CURUSER["view_torrents"]!="yes")
{
    err_msg($language["ERROR"],$language["NOT_AUTHORIZED"]." ".$language["MNU_TORRENT"]."!<br />\n".$language["SORRY"]."...");
    stdfoot();
    exit();
}

// Torrent Image Upload by Real_ptr / start
// f.screen1, f.screen2, f.screen3, f.image,
// Torrent Image Upload by Real_ptr / end

$res = get_result("SELECT f.info_hash, f.filename, f.url, f.screen1, f.screen2, f.screen3, f.image, UNIX_TIMESTAMP(f.data) as data, f.size, f.comment, f.uploader, c.name as cat_name, $tseeds, $tleechs, $tcompletes, f.speed, f.external, f.announce_url,UNIX_TIMESTAMP(f.lastupdate) as lastupdate,UNIX_TIMESTAMP(f.lastsuccess) as lastsuccess, f.anonymous, u.username FROM $ttables LEFT JOIN {$TABLE_PREFIX}categories c ON c.id=f.category LEFT JOIN {$TABLE_PREFIX}users u ON u.id=f.uploader WHERE f.info_hash ='" . $id . "'",true, $btit_settings['cache_duration']);
//die("SELECT f.info_hash, f.filename, f.url, UNIX_TIMESTAMP(f.data) as data, f.size, f.comment, f.uploader, c.name as cat_name, $tseeds, $tleechs, $tcompletes, f.speed, f.external, f.announce_url,UNIX_TIMESTAMP(f.lastupdate) as lastupdate,UNIX_TIMESTAMP(f.lastsuccess) as lastsuccess, f.anonymous, u.username FROM $ttables LEFT JOIN {$TABLE_PREFIX}categories c ON c.id=f.category LEFT JOIN {$TABLE_PREFIX}users u ON u.id=f.uploader WHERE f.info_hash ='" . $id . "'");
if (count($res)<1)
   stderr($language["ERROR"],"Bad ID!",$GLOBALS["usepopup"]);
$row=$res[0];

$spacer = "&nbsp;&nbsp;";


$torrenttpl=new bTemplate();
$torrenttpl->set("language",$language);
// Torrent Image Upload by Real_ptr / start
$torrenttpl->set("IMAGEIS",!empty($row["image"]),TRUE);
$torrenttpl->set("SCREENIS1",!empty($row["screen1"]),TRUE);
$torrenttpl->set("SCREENIS2",!empty($row["screen2"]),TRUE);
$torrenttpl->set("SCREENIS3",!empty($row["screen3"]),TRUE);
$torrenttpl->set("uploaddir",$uploaddir);
if (!empty($row["image"]))
{
$image1 = "".$row["image"]."";
$uploaddir = $GLOBALS["uploaddir"];
$image_new = "torrentimg/$image1"; //url of picture
//$image_new = str_replace(' ','%20',$image_new); //take url and replace spaces
$max_width= "490"; //maximum width allowed for pictures
$resize_width= "490"; //same as max width
$size = getimagesize("$image_new"); //get the actual size of the picture
$width= $size[0]; // get width of picture
$height= $size[1]; // get height of picture
if ($width>$max_width){
$new_width=$resize_width; // Resize Image If over max width
}else {
$new_width=$width; // Keep original size from array because smaller than max
}
$torrenttpl->set("width",$new_width);
}
// Torrent Image Upload by Real_ptr / end
if ($CURUSER["uid"]>1 && ($CURUSER["uid"]==$row["uploader"] || $CURUSER["edit_torrents"]=="yes" || $CURUSER["delete_torrents"]=="yes"))
   {
    $torrenttpl->set("MOD",TRUE,TRUE);
    $torrent_mod="<br />&nbsp;&nbsp;";
    $torrenttpl->set("SHOW_UPLOADER",true,true);
   }
else
   {
    $torrenttpl->set("SHOW_UPLOADER",$SHOW_UPLOADER,true);
    $torrenttpl->set("MOD",false,TRUE);
   }

// edit and delete picture/link
if ($CURUSER["uid"]>1 && ($CURUSER["uid"]==$row["uploader"] || $CURUSER["edit_torrents"]=="yes")) {
      if ($GLOBALS["usepopup"])
        $torrent_mod.="<a href=\"javascript: windowunder('index.php?page=edit&amp;info_hash=".$row["info_hash"]."&amp;returnto=".urlencode("index.php?page=torrent-details&id=$row[info_hash]")."')\">".image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>&nbsp;&nbsp;";
      else
        $torrent_mod.="<a href=\"index.php?page=edit&amp;info_hash=".$row["info_hash"]."&amp;returnto=".urlencode("index.php?page=torrent-details&id=$row[info_hash]")."\">".image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>&nbsp;&nbsp;";

}

if ($CURUSER["uid"]>1 && ($CURUSER["uid"]==$row["uploader"] || $CURUSER["delete_torrents"]=="yes")) {
      if ($GLOBALS["usepopup"])
        $torrent_mod.="<a href=\"javascript: windowunder('index.php?page=delete&amp;info_hash=".$row["info_hash"]."&amp;returnto=".urlencode("index.php?page=torrents")."')\">".image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"])."</a>&nbsp;&nbsp;";
      else
        $torrent_mod.="<a href=\"index.php?page=delete&amp;info_hash=".$row["info_hash"]."&amp;returnto=".urlencode("index.php?page=torrents")."\">".image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"])."</a>";
}


$torrenttpl->set("mod_task",$torrent_mod);

if (!empty($row["comment"]))
   $row["description"]=format_comment($row["comment"]);

if (isset($row["cat_name"]))
    $row["cat_name"]=unesc($row["cat_name"]);
else
    $row["cat_name"]=unesc($language["NONE"]);

$vres = get_result("SELECT sum(rating) as totrate, count(*) as votes FROM {$TABLE_PREFIX}ratings WHERE infohash = '$id'",true, $btit_settings['cache_duration']);
$vrow = $vres[0];
if ($vrow && $vrow["votes"]>=1)
   {
   $totrate=round($vrow["totrate"]/$vrow["votes"],1);
   if ($totrate==5)
      $totrate="<img src=\"$STYLEURL/images/5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
   elseif ($totrate>4.4 && $totrate<5)
      $totrate="<img src=\"$STYLEURL/images/4.5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
   elseif ($totrate>3.9 && $totrate<4.5)
      $totrate="<img src=\"$STYLEURL/images/4.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
   elseif ($totrate>3.4 && $totrate<4)
      $totrate="<img src=\"$STYLEURL/images/3.5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
   elseif ($totrate>2.9 && $totrate<3.5)
      $totrate="<img src=\"$STYLEURL/images/3.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
   elseif ($totrate>2.4 && $totrate<3)
      $totrate="<img src=\"$STYLEURL/images/2.5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
   elseif ($totrate>1.9 && $totrate<2.5)
      $totrate="<img src=\"$STYLEURL/images/2.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
   elseif ($totrate>1.4 && $totrate<2)
      $totrate="<img src=\"$STYLEURL/images/1.5.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
   else
      $totrate="<img src=\"$STYLEURL/images/1.gif\" title=\"$vrow[votes] ".$language["VOTES_RATING"].": $totrate/5.0)\" alt=\"\" />";
   }
else
    $totrate=$language["NA"];

unset($vrow);
unset($vres);

if ($row["username"]!=$CURUSER["username"] && $CURUSER["uid"]>1)
   {
   $ratings = array(5 => $language["FIVE_STAR"] ,4 =>$language["FOUR_STAR"] ,3 =>$language["THREE_STAR"] ,2 =>$language["TWO_STAR"] ,1 =>$language["ONE_STAR"] );
   $xres = do_sqlquery("SELECT rating, added FROM {$TABLE_PREFIX}ratings WHERE infohash = '$id' AND userid = " . $CURUSER["uid"],true);
   $xrow = @mysql_fetch_array($xres);
   if ($xrow)
       $s = $totrate. " (".$language["YOU_RATE"]." \"" . $ratings[$xrow["rating"]] . "\")";
   else {
       $s = "<form method=\"get\" action=\"index.php\" name=\"frm_vote\">\n";
       $s .="<input type=\"hidden\" name=\"page\" value=\"torrent-details\" />\n";
       $s .= "<input type=\"hidden\" name=\"id\" value=\"$id\" />\n";
       $s .= "<select name=\"rating\">\n";
       $s .= "<option value=\"0\">(".$language["ADD_RATING"].")</option>\n";
       foreach ($ratings as $k => $v) {
           $s .= "<option value=\"$k\">$v</option>\n";
       }
       $s .= "</select>\n";
       $s .= "<input type=\"submit\" name=\"vote\" value=\"".$language["VOTE"]."\" />";
       $s .= "</form>\n";
       }
}
else
    {
    $s = $totrate;
}
$row["rating"]=$s;
$row["size"]=makesize($row["size"]);
// files in torrent - by Lupin 20/10/05

require_once(dirname(__FILE__)."/include/BDecode.php");
if (file_exists($row["url"]))
  {
    $torrenttpl->set("DISPLAY_FILES",TRUE,TRUE);
    $ffile=fopen($row["url"],"rb");
    $content=fread($ffile,filesize($row["url"]));
    fclose($ffile);
    $content=BDecode($content);
    $numfiles=0;
    if (isset($content["info"]) && $content["info"])
      {
        $thefile=$content["info"];
        if (isset($thefile["length"]))
          {
          $dfiles[$numfiles]["filename"]=htmlspecialchars($thefile["name"]);
          $dfiles[$numfiles]["size"]=makesize($thefile["length"]);
          $numfiles++;
          }
        elseif (isset($thefile["files"]))
         {
           foreach($thefile["files"] as $singlefile)
             {
               $dfiles[$numfiles]["filename"]=htmlspecialchars(implode("/",$singlefile["path"]));
               $dfiles[$numfiles]["size"]=makesize($singlefile["length"]);
               $numfiles++;
             }
         }
       else
         {
            // can't be but...
         }
     }
     $row["numfiles"]=$numfiles.($numfiles==1?" file":" files");
     unset($content);
  }
else
    $torrenttpl->set("DISPLAY_FILES",false,TRUE);

$torrenttpl->set("files",$dfiles);

// end files in torrents
include(dirname(__FILE__)."/include/offset.php");
$row["date"]=date("d/m/Y",$row["data"]-$offset);

if ($row["anonymous"]=="true")
{
   if ($CURUSER["edit_torrents"]=="yes")
       $uploader="<a href=\"index.php?page=userdetails&amp;id=".$row['uploader']."\">".$language["TORRENT_ANONYMOUS"]."</a>";
   else
      $uploader=$language["TORRENT_ANONYMOUS"];
   }
else
    $uploader="<a href=\"index.php?page=userdetails&amp;id=".$row['uploader']."\">".$row["username"]."</a>";

$row["uploader"]=$uploader;

if ($row["speed"] < 0) {
  $speed = "N/D";
}
else if ($row["speed"] > 2097152) {
  $speed = round($row["speed"]/1048576,2) . " MB/sec";
}
else {
  $speed = round($row["speed"] / 1024, 2) . " KB/sec";
}

$torrenttpl->set("NOT_XBTT",!$XBBT_USE,TRUE);

$row["speed"]=$speed;
if (($XBTT_USE && !$PRIVATE_ANNOUNCE) || $row["external"]=="yes") 
   {
$row["downloaded"]=$row["finished"]." " . $language["X_TIMES"];
$row["peers"]=($row["leechers"]+$row["seeds"])." ".$language["PEERS"];
$row["seeds"]=$language["SEEDERS"].": ".$row["seeds"];
$row["leechers"]=$language["LEECHERS"].": " . $row["leechers"];
   }
else
   {
$row["downloaded"]="<a href=\"index.php?page=torrent_history&amp;id=".$row["info_hash"]."\">" . $row["finished"] . "</a> " . $language["X_TIMES"];
$row["peers"]="<a href=\"index.php?page=peers&amp;id=".$row["info_hash"]."\">" . ($row["leechers"]+$row["seeds"]) . "</a> ".$language["PEERS"];
$row["seeds"]=$language["SEEDERS"].": <a href=\"index.php?page=peers&amp;id=".$row["info_hash"]."\">" . $row["seeds"] . "</a>";
$row["leechers"]=$language["LEECHERS"].": <a href=\"index.php?page=peers&amp;id=".$row["info_hash"]."\">" . $row["leechers"] ."</a>";
   }
if ($row["external"]=="yes")
   {
     $torrenttpl->set("EXTERNAL",TRUE,TRUE);
     $row["update_url"]="<a href=\"index.php?page=torrent-details&amp;act=update&amp;id=".$row["info_hash"]."&amp;surl=".urlencode($row["announce_url"])."\">".$language["UPDATE"]."</a>";
     $row["announce_url"]="<b>".$language["EXTERNAL"]."</b><br />".$row["announce_url"];
     $row["lastupdate"]=get_date_time($row["lastupdate"]);
     $row["lastsuccess"]=get_date_time($row["lastsuccess"]);
   }
else
   $torrenttpl->set("EXTERNAL",false,TRUE);

// comments...
$subres = get_result("SELECT c.id, text, UNIX_TIMESTAMP(added) as data, user, u.id as uid FROM {$TABLE_PREFIX}comments c LEFT JOIN {$TABLE_PREFIX}users u ON c.user=u.username WHERE info_hash = '" . $id . "' ORDER BY added DESC",true,$btit_settings['cache_duration']);
if (!$subres || count($subres)==0) {
     if($CURUSER["uid"]>1)
       $torrenttpl->set("INSERT_COMMENT",TRUE,TRUE);
     else
       $torrenttpl->set("INSERT_COMMENT",false,TRUE);

    $torrenttpl->set("NO_COMMENTS",true,TRUE);
}
else {

     $torrenttpl->set("NO_COMMENTS",false,TRUE);

     if($CURUSER["uid"]>1)
       $torrenttpl->set("INSERT_COMMENT",TRUE,TRUE);
     else
       $torrenttpl->set("INSERT_COMMENT",false,TRUE);
     $comments=array();
     $count=0;
     foreach ($subres as $subrow) {
       $comments[$count]["user"]="<a href=\"index.php?page=userdetails&amp;id=".$subrow["uid"]."\">" . unesc($subrow["user"]);
       $comments[$count]["date"]=date("d/m/Y H.i.s",$subrow["data"]-$offset);
       // only users able to delete torrents can delete comments...
       if ($CURUSER["delete_torrents"]=="yes")
         $comments[$count]["delete"]="<a onclick=\"return confirm('". str_replace("'","\'",$language["DELETE_CONFIRM"])."')\" href=\"index.php?page=comment&amp;id=$id&amp;cid=" . $subrow["id"] . "&amp;action=delete\">".image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"])."</a>";
       $comments[$count]["comment"]=format_comment($subrow["text"]);
       $count++;
        }
     unset($subrow);
     unset($subres);
}

$torrenttpl->set("current_username",$CURUSER["username"]);

if ($GLOBALS["usepopup"])
    $torrenttpl->set("torrent_footer","<a href=\"javascript: window.close();\">".$language["CLOSE"]."</a>");
else
    $torrenttpl->set("torrent_footer","<a href=\"javascript: history.go(-1);\">".$language["BACK"]."</a>");


$torrenttpl->set("torrent",$row);
$torrenttpl->set("comments",$comments);
$torrenttpl->set("files",$dfiles);

?>
