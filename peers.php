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


$i=0;
$scriptname = htmlspecialchars($_SERVER["PHP_SELF"]."?page=peers&amp;id=$_GET[id]");
$addparam = "";
$id = AddSlashes($_GET["id"]);
if (!isset($id) || !$id)
    die("Error ID");


$res = get_result("SELECT size FROM {$TABLE_PREFIX}files WHERE info_hash='$id'",true);
if ($res) {
   $row=$res[0];
   if ($row) {
      $tsize=0+$row["size"];
      }
}
else
    die("Error ID");

if ($XBTT_USE)
   $res = get_result("SELECT x.uid,x.completed, x.downloaded, x.uploaded, x.left as bytes, IF(x.left=0,'seeder','leecher') as status, x.mtime as lastupdate, u.username, u.flag, c.flagpic, c.name FROM xbt_files_users x LEFT JOIN xbt_files ON x.fid=xbt_files.fid LEFT JOIN {$TABLE_PREFIX}files f ON f.bin_hash=xbt_files.info_hash LEFT JOIN {$TABLE_PREFIX}users u ON u.id=x.uid LEFT JOIN {$TABLE_PREFIX}countries c ON u.flag=c.id WHERE f.info_hash='$id' AND active=1 ORDER BY status DESC, lastupdate DESC",true,$btit_settings['cache_duration']);
else
    $res = get_result("SELECT * FROM {$TABLE_PREFIX}peers p LEFT JOIN {$TABLE_PREFIX}countries c ON p.dns=c.domain WHERE infohash='$id' ORDER BY bytes ASC, status DESC, lastupdate DESC",true,$btit_settings['cache_duration']);

require(load_language("lang_peers.php"));

$peerstpl=new bTemplate();
$peerstpl->set("language",$language);
$peerstpl->set("peers_script","index.php");

if (count($res)==0)
  $peerstpl->set("NOPEERS",TRUE,TRUE);
else
    {
    $peerstpl->set("NOPEERS",FALSE,TRUE);

    foreach ($res as $id=>$row)
    {
      // for user name instead of peer
     if ($XBTT_USE)
        $resu=TRUE;
     elseif ($PRIVATE_ANNOUNCE)
        $resu=get_result("SELECT u.username,u.id,c.flagpic,c.name FROM {$TABLE_PREFIX}users u LEFT JOIN {$TABLE_PREFIX}countries c ON c.id=u.flag WHERE u.pid='".$row["pid"]."' LIMIT 1",true,$btit_settings['cache_duration']);
     else
        $resu=get_result("SELECT u.username,u.id,c.flagpic,c.name FROM {$TABLE_PREFIX}users u LEFT JOIN {$TABLE_PREFIX}countries c ON c.id=u.flag WHERE u.cip='".$row["ip"]."' LIMIT 1",true,$btit_settings['cache_duration']);

     if ($resu)
        {
        if($XBTT_USE)
        {
            $rowuser["username"]=$row["username"];
            $rowuser["id"]=$row["uid"];
            $rowuser["flagpic"]=$row["flagpic"];
            $rowuser["name"]=$row["name"];
        }
        else
            $rowuser=$resu[0];

        if ($rowuser && $rowuser["id"]>1)
          {
          if ($GLOBALS["usepopup"]){
           $peers[$i]["USERNAME"]="<a href=\"javascript: windowunder('index.php?page=userdetails&amp;id=".$rowuser["id"]."')\">".unesc($rowuser["username"])."</a>";
           $peers[$i]["PM"]="<a href=\"javascript: windowunder('index.php?page=usercp&amp;do=pm&amp;action=edit&amp;uid=$CURUSER[uid]&amp;what=new&amp;to=".urlencode(unesc($rowuser["username"]))."')\">".image_or_link("$STYLEPATH/images/pm.png","","PM")."</a>";
      }    else {
            $peers[$i]["USERNAME"]="<a href=\"index.php?page=userdetails&amp;id=".$rowuser["id"]."\">".unesc($rowuser["username"])."</a>";
            $peers[$i]["PM"]="<a href=\"index.php?page=usercp&amp;do=pm&amp;action=edit&amp;uid=".$CURUSER["uid"]."&amp;what=new&amp;to=".urlencode(unesc($rowuser["username"]))."\">".image_or_link("$STYLEPATH/images/pm.png","","PM")."</a>";
           }
          }
        else
          {
           $peers[$i]["USERNAME"]=$language["GUEST"];
           $peers[$i]["PM"]="";
        }
      }
      else
        {
         $peers[$i]["USERNAME"]=$language["GUEST"];
         $peers[$i]["PM"]="";
      }

      if ($row["flagpic"]!="" && $row["flagpic"]!="unknown.gif")
        $peers[$i]["FLAG"]="<img src=\"images/flag/".$row["flagpic"]."\" alt=\"".unesc($row["name"])."\" />";
      elseif ($rowuser["flagpic"]!="" && !empty($rowuser["flagpic"]))
        $peers[$i]["FLAG"]="<img src=\"images/flag/".$rowuser["flagpic"]."\" alt=\"".unesc($rowuser["name"])."\" />";
      else
        $peers[$i]["FLAG"]="<img src=\"images/flag/unknown.gif\" alt=\"".$language["UNKNOWN"]."\" />";

    if (!$XBTT_USE)
      $peers[$i]["PORT"]=$row["port"];
      $stat=floor((($tsize - $row["bytes"]) / $tsize) *100);
      $progress="<table width=\"100\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"progress\" align=\"left\">";
      $progress.="<img height=\"10\" width=\"".number_format($stat,0)."\" src=\"$STYLEURL/images/progress.jpg\" alt=\"\" /></td></tr></table>";
    $peers[$i]["PROGRESS"]=$stat."%<br />" . $progress;

    $peers[$i]["STATUS"]=$row["status"];
    if(!$XBTT_USE)
    $peers[$i]["CLIENT"]=htmlspecialchars(getagent(unesc($row["client"]),unesc($row["peer_id"])));
      $dled=makesize($row["downloaded"]);
      $upld=makesize($row["uploaded"]);
    $peers[$i]["DOWNLOADED"]=$dled;
    $peers[$i]["UPLOADED"]=$upld;

    //Peer Ratio
      if (intval($row["downloaded"])>0) {
         $ratio=number_format($row["uploaded"]/$row["downloaded"],2);}
      else {$ratio='&#8734;';}
      $peers[$i]["RATIO"]=$ratio;
    //End Peer Ratio

      $peers[$i]["SEEN"]=get_elapsed_time($row["lastupdate"])." ago";
    $i++;
    }
}


if ($GLOBALS["usepopup"])
    $peerstpl->set("BACK2","<br /><br /><center><a href=\"javascript:window.close()\"><tag:language.CLOSE /></a></center>");
else
   $peerstpl->set("BACK2", "<br /><br /><center><a href=\"javascript: history.go(-1);\"><tag:language.BACK /></a></center>");
$peerstpl->set("XBTT",$XBTT_USE,TRUE);
$peerstpl->set("XBTT2",$XBTT_USE,TRUE);
$peerstpl->set("XBTT3",$XBTT_USE,TRUE);
$peerstpl->set("XBTT4",$XBTT_USE,TRUE);
$peerstpl->set("peers",$peers);

?>
