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


if ($CURUSER["view_users"]=="no")
   {    // start 'view_users'
       err_msg($language["ERROR"],$language["NOT_AUTHORIZED"]." ".$language["MEMBERS"]."!");
       stdfoot();
       exit;
}
else
    {
         global $CURUSER, $STYLEPATH, $CURRENTPATH, $TABLE_PREFIX, $XBTT_USE;

         if ($XBTT_USE)
            {
             $udownloaded="u.downloaded+IFNULL(x.downloaded,0)";
             $uuploaded="u.uploaded+IFNULL(x.uploaded,0)";
             $utables="{$TABLE_PREFIX}users u LEFT JOIN xbt_users x ON x.uid=u.id";
            }
         else
             {
             $udownloaded="u.downloaded";
             $uuploaded="u.uploaded";
             $utables="{$TABLE_PREFIX}users u";
             }

     if (!isset($_GET["searchtext"])) $_GET["searchtext"] = "";
     if (!isset($_GET["level"])) $_GET["level"] = "";

         $search=htmlspecialchars($_GET["searchtext"]);
         $addparams="";
         if ($search!="")
            {
            $where=" AND u.username LIKE '%".htmlspecialchars(mysql_real_escape_string($_GET["searchtext"]))."%'";
            $addparams="searchtext=$search";
            }
         else
             $where="";

         $level=intval(0+$_GET["level"]);
         if ($level>0)
            {
            $where.=" AND u.id_level=$level";
            if ($addparams!="")
               $addparams.="&amp;level=$level";
            else
                $addparams="level=$level";
            }

          $order_param=3;
          // getting order
          if (isset($_GET["order"]))
             {
             $order_param=(int)$_GET["order"];
             switch ($order_param)
               {
               case 1:
                    $order="username";
                    break;

               case 2:
                    $order="level";
                    break;

               case 3:
                    $order="joined";
                    break;

               case 4:
                    $order="lastconnect";
                    break;

               case 5:
                    $order="flag";
                    break;
                         
               case 6:
                    $order="ratio";
                    break;

               default:
                   $order="joined";

             }
          }
          else
              $order="joined";

          $by_param=1;
          if (isset($_GET["by"]))
           {
              $by_param=(int)$_GET["by"];
              $by=($by_param==1?"ASC":"DESC");
          }
          else
              $by="ASC";

         if ($addparams!="")
            $addparams.="&amp;";

         $scriptname = htmlspecialchars($_SERVER["PHP_SELF"]."?page=users");

         $res=get_result("select COUNT(*) as tu FROM {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id WHERE u.id>1 $where",true,$btit_settings['cache_duration']);
         $count = $res[0]['tu'];
         list($pagertop, $pagerbottom, $limit) = pager(20, $count,  $scriptname."&amp;" . $addparams.(strlen($addparam)>0?"&amp;":"")."order=$order_param&amp;by=$by_param&amp;");

        if ($by=="ASC")
            $mark="&nbsp;&uarr;";
        else
            $mark="&nbsp;&darr;";

// load language file
require(load_language("lang_users.php"));

$userstpl = new bTemplate();
$userstpl->set("language", $language);
$userstpl->set("users_search", $search);
$userstpl->set("users_search_level", $level==0 ? " selected=\"selected\" " : "");

$res=get_result("SELECT id,level FROM {$TABLE_PREFIX}users_level WHERE id_level>1 ORDER BY id_level",true,$btit_settings['cache_duration']);
$select="";
foreach($res as $id=>$row)
  {    // start while
  $select.="<option value='".$row["id"]."'";
  if ($level==$row["id"])
    $select.="selected=\"selected\"";
  $select.=">".$row["level"]."</option>\n";
  }    // end while
          
$userstpl->set("users_search_select", $select);
$userstpl->set("users_pagertop", $pagertop);
$userstpl->set("users_sort_username", "<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=1&amp;by=".($order=="username" && $by=="ASC"?"2":"1")."\">".$language["USER_NAME"]."</a>".($order=="username"?$mark:""));
$userstpl->set("users_sort_userlevel", "<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=2&amp;by=".($order=="level" && $by=="ASC"?"2":"1")."\">".$language["USER_LEVEL"]."</a>".($order=="level"?$mark:""));
$userstpl->set("users_sort_joined", "<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=3&amp;by=".($order=="joined" && $by=="ASC"?"2":"1")."\">".$language["USER_JOINED"]."</a>".($order=="joined"?$mark:""));
$userstpl->set("users_sort_lastaccess", "<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=4&amp;by=".($order=="lastconnect" && $by=="ASC"?"2":"1")."\">".$language["USER_LASTACCESS"]."</a>".($order=="lastconnect"?$mark:""));
$userstpl->set("users_sort_country", "<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=5&amp;by=".($order=="flag" && $by=="ASC"?"2":"1")."\">".$language["USER_COUNTRY"]."</a>".($order=="flag"?$mark:""));
$userstpl->set("users_sort_ratio", "<a href=\"$scriptname&amp;$addparam".(strlen($addparam)>0?"&amp;":"")."order=6&amp;by=".($order=="ratio" && $by=="ASC"?"2":"1")."\">".$language["RATIO"]."</a>".($order=="ratio"?$mark:""));

if ($CURUSER["uid"]>1)
  $userstpl->set("users_pm", $language["USERS_PM"]);
if ($CURUSER["edit_users"]=="yes")
  $userstpl->set("users_edit", $language["EDIT"]);
if ($CURUSER["delete_users"]=="yes")
  $userstpl->set("users_delete", $language["DELETE"]);
          
$query="select prefixcolor, suffixcolor, u.id, $udownloaded as downloaded, $uuploaded as uploaded, IF($udownloaded>0,$uuploaded/$udownloaded,0) as ratio, username, level, UNIX_TIMESTAMP(joined) AS joined,UNIX_TIMESTAMP(lastconnect) AS lastconnect, flag, flagpic, c.name as name, u.smf_fid FROM $utables INNER JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id LEFT JOIN {$TABLE_PREFIX}countries c ON u.flag=c.id WHERE u.id>1 $where ORDER BY $order $by $limit";

$rusers=get_result($query,true,$btit_settings['cache_duration']);
$userstpl->set("no_users", ($count==0), TRUE);

include ("$CURRENTPATH/offset.php");

$users=array();
$i=0;

foreach ($rusers as $id=>$row_user)
  {     // start while
  $users[$i]["username"] = "<a href=\"index.php?page=userdetails&amp;id=".$row_user["id"]."\">".unesc($row_user["prefixcolor"]).unesc($row_user["username"]).unesc($row_user["suffixcolor"])."</a>";
  $users[$i]["userlevel"] = $row_user["level"];
  $users[$i]["joined"] = $row_user["joined"]==0 ? $language["NOT_AVAILABLE"] : date("d/m/Y H:i:s",$row_user["joined"]-$offset);
  $users[$i]["lastconnect"] = $row_user["lastconnect"]==0 ? $language["NOT_AVAILABLE"] : date("d/m/Y H:i:s",$row_user["lastconnect"]-$offset);
  $users[$i]["flag"] = $row_user["flag"] == 0 ? "<img src='images/flag/unknown.gif' alt='".$language["UNKNOWN"]."' title='".$language["UNKNOWN"]."' />" : "<img src='images/flag/" . $row_user['flagpic'] . "' alt='" . $row_user['name'] . "' title='" . $row_user['name'] . "' />";
                       
//user ratio
if (intval($row_user["downloaded"])>0)
  $ratio=number_format($row_user["uploaded"]/$row_user["downloaded"],2);
else
  $ratio='&#8734;';

$users[$i]["ratio"] = $ratio;
                       
if ($CURUSER["uid"]>1 && $CURUSER["uid"]!=$row_user["id"])
  $users[$i]["pm"] = "<a href=\"index.php?page=usercp&amp;do=pm&amp;action=edit&amp;uid=$CURUSER[uid]&amp;what=new&amp;to=".urlencode(unesc($row_user["username"]))."\">".image_or_link("$STYLEPATH/images/pm.png","",$language["USERS_PM"])."</a>";
if ($CURUSER["edit_users"]=="yes" && $CURUSER["uid"]!=$row_user["id"])
  $users[$i]["edit"] = "<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=users&amp;action=edit&amp;uid=".$row_user["id"]."\">".image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>";
if ($CURUSER["delete_users"]=="yes" && $CURUSER["uid"]!=$row_user["id"])

  $users[$i]["delete"] = "<a onclick=\"return confirm('".AddSlashes($language["DELETE_CONFIRM"])."')\" href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=users&amp;action=delete&amp;uid=".$row_user["id"]."&amp;smf_fid=".$row_user["smf_fid"]."&amp;returnto=".urlencode("index.php?page=users")."\">".image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"])."</a>";

    $i++;
  }   // end while

$userstpl->set("users", $users);

}     // end 'view_users'

?>