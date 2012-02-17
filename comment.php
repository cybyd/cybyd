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


if (!$CURUSER || $CURUSER["uid"]==1)
   {
   stderr($language["ERROR"],$language["ONLY_REG_COMMENT"]);
}

$comment = ($_POST["comment"]);

$id = $_GET["id"];
if (isset($_GET["cid"]))
    $cid = intval($_GET["cid"]);
else
    $cid=0;


if (isset($_GET["action"]))
 {
  if ($CURUSER["delete_torrents"]=="yes" && $_GET["action"]=="delete")
    {
     do_sqlquery("DELETE FROM {$TABLE_PREFIX}comments WHERE id=$cid",true);
     redirect("index.php?page=torrent-details&id=$id#comments");
     exit;
    }
 }

$tpl_comment=new bTemplate();

$tpl_comment->set("language",$language);
$tpl_comment->set("comment_id",$id);
$tpl_comment->set("comment_username",$CURUSER["username"]);
$tpl_comment->set("comment_comment",textbbcode("comment","comment",htmlspecialchars(unesc($comment))));


if (isset($_POST["info_hash"])) {
   if ($_POST["confirm"]==$language["FRM_CONFIRM"]) {
   $comment = addslashes($_POST["comment"]);
      $user=AddSlashes($CURUSER["username"]);
      if ($user=="") $user="Anonymous";
	  if(empty($comment)){
     stderr($language["ERROR"],$language['ERR_COMMENT_EMPTY']);
     exit();
     }
	 else{	 
  do_sqlquery("INSERT INTO {$TABLE_PREFIX}comments (added,text,ori_text,user,info_hash) VALUES (NOW(),\"$comment\",\"$comment\",\"$user\",\"" . mysql_real_escape_string(StripSlashes($_POST["info_hash"])) . "\")",true);
  redirect("index.php?page=torrent-details&id=" . StripSlashes($_POST["info_hash"])."#comments");
  die();
  }
}
# Comment preview by miskotes
#############################

if ($_POST["confirm"]==$language["FRM_PREVIEW"]) {

$tpl_comment->set("PREVIEW",TRUE,TRUE);
$tpl_comment->set("comment_preview",set_block($language["COMMENT_PREVIEW"],"center",format_comment($comment),false));

#####################
# Comment preview end
}
  else
    {
    redirect("index.php?page=torrent-details&id=" . StripSlashes($_POST["info_hash"])."#comments");
    die();
  }
}
else
    $tpl_comment->set("PREVIEW",FALSE,TRUE);

?>
