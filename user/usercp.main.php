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


    $uid = intval($CURUSER["uid"]);
    //$res=do_sqlquery("SELECT u.lip,u.username, $udownloaded as downloaded,$uuploaded as uploaded, UNIX_TIMESTAMP(u.joined) as joined, u.flag, c.name, c.flagpic FROM $utables LEFT JOIN {$TABLE_PREFIX}countries c ON u.flag=c.id WHERE u.id=$uid",true);
    $res=get_result("SELECT c.name, c.flagpic FROM {$TABLE_PREFIX}countries c WHERE id=".$CURUSER["flag"],true,$btit_settings['cache_duration']);
    $row = $res[0];

    if (max(0,$CURUSER["downloaded"])>0)
     {
       $sr = $CURUSER["uploaded"]/$CURUSER["downloaded"];
       if ($sr >= 4)
         $s = "images/smilies/thumbsup.gif";
       else if ($sr >= 2)
         $s = "images/smilies/grin.gif";
       else if ($sr >= 1)
         $s = "images/smilies/smile1.gif";
       else if ($sr >= 0.5)
         $s = "images/smilies/noexpression.gif";
       else if ($sr >= 0.25)
         $s = "images/smilies/sad.gif";
       else
         $s = "images/smilies/thumbsdown.gif";
      $ratio=number_format($sr,2)."&nbsp;&nbsp;<img src=\"$s\" alt=\"\" />";
     }
    else
       $ratio='&#8734;';

  $ucptpl=array();
  $ucptpl["username"]=unesc($CURUSER["username"]);
  if ($CURUSER["avatar"] && $CURUSER["avatar"]!="")
     $ucptpl["avatar"]="<img border=\"0\" onload=\"resize_avatar(this);\" src=\"".htmlspecialchars($CURUSER["avatar"])."\" alt=\"\" />";
  else
     $ucptpl["avatar"]="";
  $ucptpl["email"]=htmlspecialchars($CURUSER["email"]);
  $ucptpl["lastip"]=long2ip($CURUSER["lip"]);
  $ucptpl["userlevel"]=unesc($CURUSER["level"]);
  $ucptpl["userjoin"]=($CURUSER["joined"]==0 ? "N/A" : get_date_time($CURUSER["joined"]));
  $ucptpl["lastaccess"]=($CURUSER["lastconnect"]==0 ? "N/A" : get_date_time($CURUSER["lastconnect"]));
  $ucptpl["country"]=($CURUSER["flag"]==0 ? "":unesc($row['name']))."&nbsp;&nbsp;<img src=\"images/flag/".(!$row["flagpic"] || $row["flagpic"]==""?"unknown.gif":$row["flagpic"])."\" alt='".($CURUSER["flag"]==0 ? "unknow":unesc($row['name']))."' />";
  $ucptpl["download"]=makesize($CURUSER["downloaded"]);
  $ucptpl["upload"]=makesize($CURUSER["uploaded"]);
  $ucptpl["ratio"]=$ratio;
  $usercptpl->set("ucp",$ucptpl);
  $usercptpl->set("AVATAR",$CURUSER["avatar"] && $CURUSER["avatar"]!="",true);
  $usercptpl->set("CAN_EDIT",$CURUSER["edit_users"]=="yes" || $CURUSER["admin_access"]=="yes",true);

  // Only show if forum is internal
  if ( $GLOBALS["FORUMLINK"] == '' || $GLOBALS["FORUMLINK"] == 'internal' )
     {
     $sql = get_result("SELECT count(*) as tp FROM {$TABLE_PREFIX}posts p INNER JOIN {$TABLE_PREFIX}users u ON p.userid = u.id WHERE u.id = " . $CURUSER["uid"],true,$btit_settings['cache_duration']);
     $posts = $sql[0]['tp'];
     unset($sql);
     $memberdays = max(1, round( ( time() - $CURUSER['joined'] ) / 86400 ));
     $posts_per_day = number_format(round($posts / $memberdays,2),2);
     $usercptpl->set("INTERNAL_FORUM",true,true);
     $usercptpl->set("posts",$posts."&nbsp;[" . sprintf($language["POSTS_PER_DAY"], $posts_per_day) . "]");
  }
  elseif (substr($GLOBALS["FORUMLINK"],0,3)=="smf")
  {
     $forum=get_result("SELECT `date".(($GLOBALS["FORUMLINK"]=="smf")?"R":"_r")."egistered`, `posts` FROM `{$db_prefix}members` WHERE ".(($GLOBALS["FORUMLINK"]=="smf")?"`ID_MEMBER`":"`id_member`")."=".$CURUSER["smf_fid"],true,$btit_settings['cache_duration']);
     $forum=$forum[0];
     $memberdays = max(1, round( ( time() - (($GLOBALS["FORUMLINK"]=="smf")?$forum["dateRegistered"]:$forum["date_registered"]) ) / 86400 ));
     $posts_per_day = number_format(round($forum["posts"] / $memberdays,2),2);
     $usercptpl->set("INTERNAL_FORUM",true,true);
     $usercptpl->set("posts",$forum["posts"]."&nbsp;[" . sprintf($language["POSTS_PER_DAY"], $posts_per_day) . "]");
     unset($forum);
  }
  elseif ($GLOBALS["FORUMLINK"]=="ipb")
  {
     $forum=get_result("SELECT `joined`, `posts` FROM `{$ipb_prefix}members` WHERE `member_id`=".$CURUSER["ipb_fid"],true,$btit_settings['cache_duration']);
      $forum=$forum[0];
      $memberdays = max(1, round( ( time() - $forum["joined"] ) / 86400 ));
      $posts_per_day = number_format(round($forum["posts"] / $memberdays,2),2);
      $usercptpl->set("INTERNAL_FORUM",true,true);
      $usercptpl->set("posts",$forum["posts"]."&nbsp;[" . sprintf($language["POSTS_PER_DAY"], $posts_per_day) . "]");
   unset($forum);
}


  if ($XBTT_USE)
     {
      $tseeds="f.seeds+ifnull(x.seeders,0)";
      $tleechs="f.leechers+ifnull(x.leechers,0)";
      $tcompletes="f.finished+ifnull(x.completed,0)";
      $ttables="{$TABLE_PREFIX}files f INNER JOIN xbt_files x ON x.info_hash=f.bin_hash";
     }
  else
      {
      $tseeds="f.seeds";
      $tleechs="f.leechers";
      $tcompletes="f.finished";
      $ttables="{$TABLE_PREFIX}files f";
      }

  $resuploaded = get_result("SELECT count(*) as tf FROM {$TABLE_PREFIX}files WHERE uploader=$uid ORDER BY data DESC",true,$btit_settings['cache_duration']);
  $numtorrent=$resuploaded[0]['tf'];
  unset($resuploaded);

  $utorrents = intval($CURUSER["torrentsperpage"]);

  if ($numtorrent>0)
     {
     list($pagertop, $pagerbottom, $limit) = pager(($utorrents==0?15:$utorrents), $numtorrent, "index.php?page=usercp&amp;uid=$uid&amp;",array("pagename" => "ucp_uploaded"));

     $usercptpl->set("pagertop",$pagertop);

     $resuploaded = get_result("SELECT f.filename, UNIX_TIMESTAMP(f.data) as added, f.size, $tseeds as seeds, $tleechs as leechers, $tcompletes as finished, f.info_hash as hash FROM $ttables WHERE uploader=$uid ORDER BY data DESC $limit",true,$btit_settings['cache_duration']);
  }
  if ($resuploaded && count($resuploaded)>0)
     {
         include("include/offset.php");
         $usercptpl->set("RESULTS",true,true);
         $uptortpl=array();
         $i=0;
         foreach ($resuploaded as $id=>$rest)
                 {
                  $uptortpl[$i]["filename"]=cut_string(unesc($rest["filename"]),intval($btit_settings["cut_name"]));
                  $uptortpl[$i]["added"]=date("d/m/Y",$rest["added"]-$offset);
                  $uptortpl[$i]["size"]=makesize($rest["size"]);
                  $uptortpl[$i]["seedcolor"]=linkcolor($rest["seeds"]);
                  $uptortpl[$i]["seeds"]=$rest[seeds];
                  $uptortpl[$i]["leechcolor"]=linkcolor($rest["leechers"]);
                  $uptortpl[$i]["leechers"]=$rest[leechers];
                  $uptortpl[$i]["completed"]=($rest["finished"]>0?$rest["finished"]:"---");
                  $uptortpl[$i]["editlink"]="index.php?page=edit&amp;info_hash=".$rest["hash"]."&amp;returnto=".urlencode("index.php?page=torrents")."";
                  $uptortpl[$i]["dellink"]="index.php?page=delete&amp;info_hash=".$rest["hash"]."&amp;returnto=".urlencode("index.php?page=torrents")."";
                  $uptortpl[$i]["editimg"]=image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"]);
                  $uptortpl[$i]["delimg"]=image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"]);
                  $i++;
               }
             $usercptpl->set("uptor",$uptortpl);
    }
  else
      {
        $usercptpl->set("RESULTS",false,true);
        $usercptpl->set("pagertop","");
 }

?>