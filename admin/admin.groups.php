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

if (!defined("IN_ACP"))
      die("non direct access!");

$admintpl->set("add_new",false,true);
$admintpl->set("smf_in_use_1", ((substr($FORUMLINK,0,3)=="smf")?true:false), true);
$admintpl->set("smf_in_use_2", ((substr($FORUMLINK,0,3)=="smf")?true:false), true);
$admintpl->set("smf_in_use_3", ((substr($FORUMLINK,0,3)=="smf")?true:false), true);
$admintpl->set("ipb_in_use_1", (($FORUMLINK=="ipb")?true:false), true);
$admintpl->set("ipb_in_use_2", (($FORUMLINK=="ipb")?true:false), true);
$admintpl->set("ipb_in_use_3", (($FORUMLINK=="ipb")?true:false), true);

switch ($action)
    {
        
        case 'delete':
          $id=max(0,$_GET["id"]);
          // controle if this level can be cancelled
          $rcanc=do_sqlquery("SELECT can_be_deleted FROM {$TABLE_PREFIX}users_level WHERE id=$id");
          if (!$rcanc || mysql_num_rows($rcanc)==0)
            {
             err_msg($language["ERROR"], $language["ERR_CANT_FIND_GROUP"]);
             stdfoot(false,false,true);
             die;
            }
          $rcancanc=mysql_fetch_array($rcanc);
          if ($rcancanc["can_be_deleted"]=="yes")
             {
             do_sqlquery("DELETE FROM {$TABLE_PREFIX}users_level WHERE id=$id",true);
             success_msg($language["SUCCESS"],$language["GROUP_DELETED"]."<br />\n<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=groups\">".$language["ACP_USER_GROUP"]."</a>");
             stdfoot(false,false,true);
             die;
             }
          else
             {
              err_msg($language["ERROR"],$language["CANT_DELETE_GROUP"]);
              stdfoot(false,false,true);
              die;
             }
          break;

        case 'edit':
          $block_title=$language["GROUP_EDIT_GROUP"];
          $gid=max(0,$_GET["id"]);
          $admintpl->set("list",false,true);
          $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=groups&amp;action=save&amp;id=$gid");
          $admintpl->set("language",$language);
          $rgroup=get_result("SELECT * FROM {$TABLE_PREFIX}users_level WHERE id=$gid",true);
          $current_group=$rgroup[0];
          unset($rgroup);
          $current_group["prefixcolor"]=unesc($current_group["prefixcolor"]);
          $current_group["suffixcolor"]=unesc($current_group["suffixcolor"]);
          $current_group["level"]=unesc($current_group["level"]);
          $current_group["view_torrents"]=($current_group["view_torrents"]=="yes"?"checked=\"checked\"":"");
          $current_group["edit_torrents"]=($current_group["edit_torrents"]=="yes"?"checked=\"checked\"":"");
          $current_group["delete_torrents"]=($current_group["delete_torrents"]=="yes"?"checked=\"checked\"":"");
          $current_group["view_users"]=($current_group["view_users"]=="yes"?"checked=\"checked\"":"");
          $current_group["edit_users"]=($current_group["edit_users"]=="yes"?"checked=\"checked\"":"");
          $current_group["delete_users"]=($current_group["delete_users"]=="yes"?"checked=\"checked\"":"");
          $current_group["view_news"]=($current_group["view_news"]=="yes"?"checked=\"checked\"":"");
          $current_group["edit_news"]=($current_group["edit_news"]=="yes"?"checked=\"checked\"":"");
          $current_group["delete_news"]=($current_group["delete_news"]=="yes"?"checked=\"checked\"":"");
          $current_group["view_forum"]=($current_group["view_forum"]=="yes"?"checked=\"checked\"":"");
          $current_group["edit_forum"]=($current_group["edit_forum"]=="yes"?"checked=\"checked\"":"");
          $current_group["delete_forum"]=($current_group["delete_forum"]=="yes"?"checked=\"checked\"":"");
          $current_group["can_upload"]=($current_group["can_upload"]=="yes"?"checked=\"checked\"":"");
          $current_group["can_download"]=($current_group["can_download"]=="yes"?"checked=\"checked\"":"");
          $current_group["admin_access"]=($current_group["admin_access"]=="yes"?"checked=\"checked\"":"");
          if(substr($FORUMLINK,0,3)=="smf")
          {
              $current_group["forumlist"]=$language["SMF_LIST"];
              $res=get_result("SELECT ".(($FORUMLINK=="smf")?"`ID_GROUP` `idg`, `groupName` `gn`":"`id_group` `idg`, `group_name` `gn`")." FROM `{$db_prefix}membergroups` ORDER BY `idg` ASC", true, $btit_settings["cache_duration"]);
              if(count($res)>0)
              {
                  foreach($res as $row)
                  {
                      $current_group["forumlist"].=$row["gn"] . " = " . $row["idg"] . "<br />";
                  }
              }
              $current_group["smf_group_mirror"]=unesc($current_group["smf_group_mirror"]);
          }
          elseif($FORUMLINK=="ipb")
          {
              $current_group["forumlist"].=$language["IPB_LIST"];
              $res=do_sqlquery("SELECT * FROM `{$ipb_prefix}forum_perms` ORDER BY `perm_id` ASC",true);
              if(@mysql_num_rows($res)>0)
              {
                  while($row=mysql_fetch_assoc($res))
                  {
                      $current_group["forumlist"].=$row["perm_name"] . " = " . $row["perm_id"] . "<br />";
                  }
              }
              $current_group["ipb_group_mirror"]=unesc($current_group["ipb_group_mirror"]);
          }
          $admintpl->set("group",$current_group);
          break;

        case 'add':
          $admintpl->set("add_new",true,true);
          $block_title=$language["GROUP_ADD_NEW"];
          $admintpl->set("list",false,true);
          $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=groups&amp;action=save&amp;mode=new");
          $admintpl->set("language",$language);
          $frm_dropdown="\n<select name=\"base_group\" size=\"1\">";
          $rlevel=do_sqlquery("SELECT DISTINCT id_level,predef_level FROM {$TABLE_PREFIX}users_level ORDER BY id_level",true);
          while($level=mysql_fetch_array($rlevel))
                $frm_dropdown.="\n<option value=\"".$level["id_level"]."\">".$level["predef_level"]."</option>";
          $frm_dropdown.="\n</select>";
          $admintpl->set("groups_combo",$frm_dropdown);
          break;


        case 'save':
          if ($_POST["write"]==$language["FRM_CONFIRM"])
            {
              if (isset($_GET["mode"]) && $_GET["mode"]=="new")
                 {
                   $gid=max(0,$_POST["base_group"]);
                   $gname=sqlesc($_POST["gname"]);
                   $rfields=get_result("SELECT * FROM {$TABLE_PREFIX}users_level WHERE id=$gid",true);
                   // we have unique record, set the first and unique to our array
                   $rfields=$rfields[0];
                   foreach($rfields as $key=>$value)
                          if ($key!="id" && $key!="level" && $key!="can_be_deleted") $fields[]=$key;
                   do_sqlquery("INSERT INTO {$TABLE_PREFIX}users_level (can_be_deleted,level,".implode(",",$fields).") SELECT 'yes',$gname,".implode(",",$fields)." FROM {$TABLE_PREFIX}users_level WHERE id=$gid",true);
                   unset($fields);
                   unset($rfields);
                 }
              else
                 {
                   $gid=max(0,$_GET["id"]);
                   $update=array();
                   $update[]="level=".sqlesc($_POST["gname"]);
                   $update[]="view_torrents=".sqlesc(isset($_POST["vtorrents"])?"yes":"no");
                   $update[]="edit_torrents=".sqlesc(isset($_POST["etorrents"])?"yes":"no");
                   $update[]="delete_torrents=".sqlesc(isset($_POST["dtorrents"])?"yes":"no");
                   $update[]="view_users=".sqlesc(isset($_POST["vusers"])?"yes":"no");
                   $update[]="edit_users=".sqlesc(isset($_POST["eusers"])?"yes":"no");
                   $update[]="delete_users=".sqlesc(isset($_POST["dusers"])?"yes":"no");
                   $update[]="view_news=".sqlesc(isset($_POST["vnews"])?"yes":"no");
                   $update[]="edit_news=".sqlesc(isset($_POST["enews"])?"yes":"no");
                   $update[]="delete_news=".sqlesc(isset($_POST["dnews"])?"yes":"no");
                   $update[]="view_forum=".sqlesc(isset($_POST["vforum"])?"yes":"no");
                   $update[]="edit_forum=".sqlesc(isset($_POST["eforum"])?"yes":"no");
                   $update[]="delete_forum=".sqlesc(isset($_POST["dforum"])?"yes":"no");
                   $update[]="can_upload=".sqlesc(isset($_POST["upload"])?"yes":"no");
                   $update[]="can_download=".sqlesc(isset($_POST["down"])?"yes":"no");
                   $update[]="admin_access=".sqlesc(isset($_POST["admincp"])?"yes":"no");
                   $update[]="WT=".sqlesc($_POST["waiting"]);
                   $update[]="prefixcolor=".sqlesc($_POST["pcolor"]);
                   $update[]="suffixcolor=".sqlesc($_POST["scolor"]);
                   if(substr($FORUMLINK,0,3)=="smf")
                       $update[]="smf_group_mirror=".((int)0+$_POST["smf_group_mirror"]);
                   elseif($FORUMLINK=="ipb")
                       $update[]="ipb_group_mirror=".((int)0+$_POST["ipb_group_mirror"]);
                   $strupdate=implode(",",$update);
                   do_sqlquery("UPDATE {$TABLE_PREFIX}users_level SET $strupdate WHERE id=$gid",true);
                   unset($update);
                   unset($strupdate);
                 }
                
            }

            // we don't break, so now we read ;)

        case '':
        case 'read':
        default:

          $block_title=$language["USER_GROUPS"];
          $admintpl->set("list",true,true);
          $admintpl->set("group_add_new","<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=groups&amp;action=add\">".$language["INSERT_USER_GROUP"]."</a>");
          $admintpl->set("language",$language);
          $rlevel=do_sqlquery("SELECT * from {$TABLE_PREFIX}users_level ORDER BY id_level",true);
          $groups=array();
          $i=0;
          while ($level=mysql_fetch_array($rlevel))
            {
                $groups[$i]["user"]="<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=groups&amp;action=edit&amp;id=".$level["id"]."\">".unesc($level["prefixcolor"]).unesc($level["level"]).unesc($level["suffixcolor"])."</a>";
                $groups[$i]["torrent_aut"]=$level["view_torrents"]."/".$level["edit_torrents"]."/".$level["delete_torrents"];
                $groups[$i]["users_aut"]=$level["view_users"]."/".$level["edit_users"]."/".$level["delete_users"];
                $groups[$i]["news_aut"]=$level["view_news"]."/".$level["edit_news"]."/".$level["delete_news"];
                $groups[$i]["forum_aut"]=$level["view_forum"]."/".$level["edit_forum"]."/".$level["delete_forum"];
                $groups[$i]["can_upload"]=$level["can_upload"];
                $groups[$i]["can_download"]=$level["can_download"];
                $groups[$i]["admin_access"]=$level["admin_access"];
                $groups[$i]["WT"]=$level["WT"];
                if(substr($FORUMLINK,0,3)=="smf")
                    $groups[$i]["smf_group_mirror"]=$level["smf_group_mirror"];
                elseif($FORUMLINK=="ipb")
                    $groups[$i]["ipb_group_mirror"]=$level["ipb_group_mirror"];
                $groups[$i]["delete"]=($level["can_be_deleted"]=="no"?"No":"<a onclick=\"return confirm('".AddSlashes($language["DELETE_CONFIRM"])."')\" href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=groups&amp;action=delete&amp;id=".$level["id"]."\">".image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"])."</a>");
                $i++;
          }

          unset($level);
          mysql_free_result($rlevel);

          $admintpl->set("groups",$groups);

}

?>