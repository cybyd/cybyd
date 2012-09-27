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


$BASEDIR=str_replace("\\", "/", dirname(__FILE__));

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
  <head>
  <title>SMF Import</title>
  <meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\" />
  <link rel=\"stylesheet\" href=\"$BASEDIR/style/xbtit_default/main.css\" type=\"text/css\" />
  </head>
  <body>
";

require_once($BASEDIR."/include/settings.php");
require_once($BASEDIR."/include/common.php");
require_once($BASEDIR."/language/english/lang_smf_import.php");

// Lets open a connection to the database
mysql_select_db($database, mysql_connect($dbhost,$dbuser,$dbpass));

$cookie=test_my_cookie();

if($cookie["is_valid"]===true)
{
    $res=mysql_query("SELECT `ul`.`admin_access` FROM `{$TABLE_PREFIX}users` `u` LEFT JOIN `{$TABLE_PREFIX}users_level` `ul` ON `u`.`id_level`=`ul`.`id` WHERE `u`.`id`=".$cookie["id"]);
    if(@mysql_num_rows($res)==1)
        $row=mysql_fetch_assoc($res);
}
if(!isset($row["admin_access"]))
    $row["admin_access"]="no";

if($cookie["is_valid"]===false || $row["admin_access"]=="no")
    die($lang[38]);

$lock=mysql_fetch_assoc(mysql_query("SELECT random FROM {$TABLE_PREFIX}users WHERE id=1"));
if($lock["random"]==54345)
    die($lang[26] . $lang[27] . $lang[35]);

(!file_exists($BASEDIR."/smf/Settings.php") ? $files_present=$lang[1] : $files_present=$lang[0]);

if($files_present==$lang[0])
{
    $filename=$BASEDIR."/smf/Settings.php";
    $fd=fopen($filename,"r");
    $data=fread($fd,filesize($filename));
    $start=strpos($data, "\$db_prefix");
    $end=strpos(substr($data,$start),";")+1;
    $data=substr($data,$start,$end);
    fclose($fd);
    
    $filename=dirname(__FILE__)."/include/settings.php";
    if (file_exists($filename))
    {
        if (is_writable($filename))
        {
            $filesize=filesize($filename);
            $fd = fopen($filename, "w");
            $contents ="<?php\n\n";
            $contents.="\$dbhost = \"$dbhost\";\n";
            $contents.="\$dbuser = \"$dbuser\";\n";
            $contents.="\$dbpass = \"$dbpass\";\n";
            $contents.="\$database = \"$database\";\n";
            $contents.= "\$TABLE_PREFIX = \"$TABLE_PREFIX\";\n";
            $contents.= $data."\n";
            $contents.= "\n?>";
            fwrite($fd,$contents);
            fclose($fd);
        }
        else
            die($lang[36] . $filename . $lang[37]);
    } 
}
(isset($_GET["act"]) ? $act=$_GET["act"] : $act="");
(isset($_GET["confirm"]) ? $confirm=$_GET["confirm"] : $confirm="");
(isset($_GET["start"]) ? $start=intval($_GET["start"]) : $start=2);
(isset($_GET["counter"]) ? $counter=intval($_GET["counter"]) : $counter=0);
(isset($_GET["lastacc"]) ? $lastacc=intval($_GET["lastacc"]) : $lastacc=0);

if($act=="")
{
    if(!isset($db_prefix) || empty($db_prefix))
    {
        $db_prefix=substr($data, 14, (strlen($data)-16));
    }

    echo $lang[2];
    echo $lang[3] . (($files_present==$lang[0]) ? "#00FF00" : "#FF0000") . $lang[4] . $files_present .  $lang[5];
    if($files_present==$lang[1])
        die($lang[6] . $lang[8] . $lang[9] . $lang[35]);

    // Make sure SMF is installed by checking the tables are there
    // (There should be 41 as of v1.1.4 but lets be generous and ensure
    // there are at least 35 SMF tables)
    $count=0;
    $tablelist=mysql_list_tables($database); 

    while($list=mysql_fetch_assoc($tablelist))
    {
        if(substr($list["Tables_in_".$database], 0, strlen($db_prefix))==$db_prefix)
        $count++;
    }
    (($count<35) ? $smf_installed=$lang[1] : $smf_installed=$lang[0]);

    // Check the SMF Version
    $res=mysql_query("SELECT `value` FROM `{$db_prefix}settings` WHERE `variable`='smfVersion'");
    if(@mysql_num_rows($res)>0)
    {
        $row=mysql_fetch_assoc($res);
        $smf_type=(((int)(substr($row["value"],0,1))==1)?"smf":"smf2");
    }
    
    echo $lang[10] . (($smf_installed==$lang[0]) ? "#00FF00" : "#FF0000") . $lang[4] . $smf_installed . ((isset($row["value"]) && !empty($row["value"]))?" (".$row["value"].") ":"") . $lang[5];
    if($smf_installed==$lang[1])
        die($lang[7] . $lang[8] . $lang[9] . $lang[35]);

    // Check if the default english language file is present and writable
    (!file_exists($BASEDIR."/smf/Themes/default/languages/Errors.english.php") ? $lang_present=$lang[1] : $lang_present=$lang[0]);
    if($lang_present==$lang[0])
        (is_writable($BASEDIR."/smf/Themes/default/languages/Errors.english.php") ? $lang_writable=$lang[0] : $lang_writable=$lang[1]);
     
    if($lang_present==$lang[1])
        $status=$lang[11];
    elseif($lang_present==$lang[0] && $lang_writable==$lang[1])
        $status=$lang[12];
    else
        $status=$lang[0];
    
    echo $lang[13] . (($status==$lang[0]) ? "#00FF00" : "#FF0000") . $lang[4] . $status . $lang[5];
    
    if($status==$lang[11])
        die($lang[15] . $BASEDIR . "/smf/Themes/default/languages/Errors.english.php" . $lang[16] . $lang[9] . $lang[35]);
    elseif($status==$lang[12])
        die($lang[15] . $BASEDIR . "/smf/Themes/default/languages/Errors.english.php" . $lang[17] . $lang[9] . $lang[35]);

    die($lang[19] . $_SERVER["PHP_SELF"] . "?act=init_setup&smf_type=" . $smf_type . $lang[20] . $lang[35]);
    
}
elseif($act=="init_setup"  && $confirm!="yes")
{
    die($lang[21] . $lang[22] . $lang[23] . $lang[35]);
}
elseif($act=="init_setup"  && $confirm=="yes")
{
    $input_pwd = $_GET["pwd"];

    if ($input_pwd!=="$dbpass")
       {
       die($lang[34] . $lang[35]);
    }

    $smf_type=$_GET["smf_type"];

    // Purge the current forum settings we're about to rebuild
    @mysql_query("TRUNCATE TABLE {$db_prefix}board_permissions");
    @mysql_query("TRUNCATE TABLE {$db_prefix}permissions");
    @mysql_query("TRUNCATE TABLE {$db_prefix}membergroups");

    // Get current tracker ranks
    $query ="SELECT id_level+10 AS id_level, level, edit_forum, admin_access ";
    $query.="FROM {$TABLE_PREFIX}users_level ";
    $query.="WHERE id_level>1 ";
    $query.="ORDER BY id_level ASC";
    
    $getranks=mysql_query($query);
    $ranklist="";
    while($rank=mysql_fetch_assoc($getranks))
    {
        $ranklist.=$rank["id_level"].",";

        // Rank is validating, set up limited access
        if($rank["id_level"]==12)
        {
            $query1 ="INSERT INTO `{$db_prefix}board_permissions` (".(($smf_type=="smf")?"`ID_GROUP`, `ID_BOARD`":"`id_group`, `id_profile`").", `permission`) VALUES ";
            $query1.="(".$rank["id_level"].", 0, 'poll_view'), ";
            $query1.="(".$rank["id_level"].", 0, 'report_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_reply_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_reply_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'modify_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'send_topic'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_new'), ";
            $query1.="(".$rank["id_level"].", 0, 'mark_any_notify'), ";
            $query1.="(".$rank["id_level"].", 0, 'mark_notify')";

            $query2 ="INSERT INTO `{$db_prefix}permissions` (".(($smf_type=="smf")?"`ID_GROUP`":"`id_group`").", `permission`) VALUES ";
            $query2.="(".$rank["id_level"].", 'calendar_view'), ";
            $query2.="(".$rank["id_level"].", 'search_posts'), ";
            $query2.="(".$rank["id_level"].", 'view_stats'), ";
            $query2.="(".$rank["id_level"].", 'who_view'), ";
            $query2.="(".$rank["id_level"].", 'profile_view_own'), ";
            $query2.="(".$rank["id_level"].", 'profile_identity_own')";

            if($smf_type=="smf")
                $query3 ="INSERT INTO `{$db_prefix}membergroups` (`ID_GROUP`, `groupName`, `onlineColor`, `minPosts`, `stars`) VALUES ";
            else
                $query3 ="INSERT INTO `{$db_prefix}membergroups` (`id_group`, `group_name`, `online_color`, `min_posts`, `stars`) VALUES ";
            $query3.="(".$rank["id_level"].", 'Validating', '', -1, '')";
        }
        // Rank has full admin access
        elseif($rank["edit_forum"]=="yes" && $rank["admin_access"]=="yes")
        {
            $query1 ="INSERT INTO `{$db_prefix}board_permissions` (".(($smf_type=="smf")?"`ID_GROUP`, `ID_BOARD`":"`id_group`, `id_profile`").", `permission`) VALUES ";
            $query1.="(".$rank["id_level"].", 0, 'poll_lock_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_edit_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_edit_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_add_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_add_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_post'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_vote'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_view'), ";
            $query1.="(".$rank["id_level"].", 0, 'report_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'modify_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'modify_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'delete_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'delete_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'announce_topic'), ";
            $query1.="(".$rank["id_level"].", 0, 'delete_replies'), ";
            $query1.="(".$rank["id_level"].", 0, 'modify_replies'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_reply_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_reply_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'remove_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'remove_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'lock_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'lock_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'move_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'move_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'make_sticky'), ";
            $query1.="(".$rank["id_level"].", 0, 'send_topic'), ";
            $query1.="(".$rank["id_level"].", 0, 'split_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'merge_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_new'), ";
            $query1.="(".$rank["id_level"].", 0, 'moderate_board'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_lock_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_remove_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_remove_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'mark_any_notify'), ";
            $query1.="(".$rank["id_level"].", 0, 'mark_notify'), ";
            $query1.="(".$rank["id_level"].", 0, 'view_attachments'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_attachment')";

            $query2 ="INSERT INTO `{$db_prefix}permissions` (".(($smf_type=="smf")?"`ID_GROUP`":"`id_group`").", `permission`) VALUES ";
            $query2.="(".$rank["id_level"].", 'profile_remote_avatar'), ";
            $query2.="(".$rank["id_level"].", 'profile_upload_avatar'), ";
            $query2.="(".$rank["id_level"].", 'profile_server_avatar'), ";
            $query2.="(".$rank["id_level"].", 'profile_title_any'), ";
            $query2.="(".$rank["id_level"].", 'profile_title_own'), ";
            $query2.="(".$rank["id_level"].", 'profile_extra_any'), ";
            $query2.="(".$rank["id_level"].", 'profile_extra_own'), ";
            $query2.="(".$rank["id_level"].", 'profile_identity_any'), ";
            $query2.="(".$rank["id_level"].", 'profile_identity_own'), ";
            $query2.="(".$rank["id_level"].", 'profile_view_any'), ";
            $query2.="(".$rank["id_level"].", 'profile_view_own'), ";
            $query2.="(".$rank["id_level"].", 'pm_send'), ";
            $query2.="(".$rank["id_level"].", 'pm_read'), ";
            $query2.="(".$rank["id_level"].", 'send_mail'), ";
            $query2.="(".$rank["id_level"].", 'manage_bans'), ";
            $query2.="(".$rank["id_level"].", 'manage_permissions'), ";
            $query2.="(".$rank["id_level"].", 'manage_membergroups'), ";
            $query2.="(".$rank["id_level"].", 'moderate_forum'), ";
            $query2.="(".$rank["id_level"].", 'edit_news'), ";
            $query2.="(".$rank["id_level"].", 'manage_smileys'), ";
            $query2.="(".$rank["id_level"].", 'manage_attachments'), ";
            $query2.="(".$rank["id_level"].", 'manage_boards'), ";
            $query2.="(".$rank["id_level"].", 'admin_forum'), ";
            $query2.="(".$rank["id_level"].", 'calendar_edit_any'), ";
            $query2.="(".$rank["id_level"].", 'calendar_edit_own'), ";
            $query2.="(".$rank["id_level"].", 'calendar_post'), ";
            $query2.="(".$rank["id_level"].", 'calendar_view'), ";
            $query2.="(".$rank["id_level"].", 'karma_edit'), ";
            $query2.="(".$rank["id_level"].", 'search_posts'), ";
            $query2.="(".$rank["id_level"].", 'who_view'), ";
            $query2.="(".$rank["id_level"].", 'view_mlist'), ";
            $query2.="(".$rank["id_level"].", 'view_stats')";

            if($smf_type=="smf")
                $query3 ="INSERT INTO `{$db_prefix}membergroups` (`ID_GROUP`, `groupName`, `onlineColor`, `minPosts`, `stars`) VALUES ";
            else
                $query3 ="INSERT INTO `{$db_prefix}membergroups` (`id_group`, `group_name`, `online_color`, `min_posts`, `stars`) VALUES ";
            $query3.="(".$rank["id_level"].", '".$rank["level"]."', '#FF0000', -1, '5#staradmin.gif')";
        }
        // Rank has forum edit rights but no admin access (moderator/low level admin)
        elseif($rank["edit_forum"]=="yes" && $rank["admin_access"]=="no")
        {
            $query1 ="INSERT INTO `{$db_prefix}board_permissions` (".(($smf_type=="smf")?"`ID_GROUP`, `ID_BOARD`":"`id_group`, `id_profile`").", `permission`) VALUES ";
            $query1.="(".$rank["id_level"].", 0, 'delete_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'delete_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'remove_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_reply_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_reply_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'lock_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'split_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'send_topic'), ";
            $query1.="(".$rank["id_level"].", 0, 'make_sticky'), ";
            $query1.="(".$rank["id_level"].", 0, 'move_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'lock_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_new'), ";
            $query1.="(".$rank["id_level"].", 0, 'modify_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'report_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'remove_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'merge_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'modify_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_view'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_vote'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_post'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_add_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_add_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_edit_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_edit_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_lock_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_remove_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'mark_any_notify'), ";
            $query1.="(".$rank["id_level"].", 0, 'mark_notify'), ";
            $query1.="(".$rank["id_level"].", 0, 'view_attachments'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_attachment')";

            $query2 ="INSERT INTO `{$db_prefix}permissions` (".(($smf_type=="smf")?"`ID_GROUP`":"`id_group`").", `permission`) VALUES ";
            $query2.="(".$rank["id_level"].", 'calendar_view'), ";
            $query2.="(".$rank["id_level"].", 'karma_edit'), ";
            $query2.="(".$rank["id_level"].", 'view_stats'), ";
            $query2.="(".$rank["id_level"].", 'view_mlist'), ";
            $query2.="(".$rank["id_level"].", 'who_view'), ";
            $query2.="(".$rank["id_level"].", 'search_posts'), ";
            $query2.="(".$rank["id_level"].", 'calendar_post'), ";
            $query2.="(".$rank["id_level"].", 'pm_read'), ";
            $query2.="(".$rank["id_level"].", 'calendar_edit_own'), ";
            $query2.="(".$rank["id_level"].", 'pm_send'), ";
            $query2.="(".$rank["id_level"].", 'profile_view_own'), ";
            $query2.="(".$rank["id_level"].", 'profile_view_any'), ";
            $query2.="(".$rank["id_level"].", 'profile_identity_own'), ";
            $query2.="(".$rank["id_level"].", 'profile_extra_own'), ";
            $query2.="(".$rank["id_level"].", 'profile_server_avatar'), ";
            $query2.="(".$rank["id_level"].", 'profile_upload_avatar'), ";
            $query2.="(".$rank["id_level"].", 'profile_remote_avatar')";

            if($smf_type=="smf")
                $query3 ="INSERT INTO `{$db_prefix}membergroups` (`ID_GROUP`, `groupName`, `onlineColor`, `minPosts`, `stars`) VALUES ";
            else
                $query3 ="INSERT INTO `{$db_prefix}membergroups` (`id_group`, `group_name`, `online_color`, `min_posts`, `stars`) VALUES ";
            $query3.="(".$rank["id_level"].", '".$rank["level"]."', '#00FF00', -1, '5#starmod.gif')";
        }
        else
        {
            // Bog standard settings
            $query1 ="INSERT INTO `{$db_prefix}board_permissions` (".(($smf_type=="smf")?"`ID_GROUP`, `ID_BOARD`":"`id_group`, `id_profile`").", `permission`) VALUES ";
            $query1.="(".$rank["id_level"].", 0, 'view_attachments'), ";
            $query1.="(".$rank["id_level"].", 0, 'send_topic'), ";
            $query1.="(".$rank["id_level"].", 0, 'report_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_reply_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_reply_any'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_new'), ";
            $query1.="(".$rank["id_level"].", 0, 'post_attachment'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_vote'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_view'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_post'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_edit_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'poll_add_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'modify_own'), ";
            $query1.="(".$rank["id_level"].", 0, 'mark_notify'), ";
            $query1.="(".$rank["id_level"].", 0, 'mark_any_notify')";

            $query2 ="INSERT INTO `{$db_prefix}permissions` (".(($smf_type=="smf")?"`ID_GROUP`":"`id_group`").", `permission`) VALUES ";
            $query2.="(".$rank["id_level"].", 'who_view'), ";
            $query2.="(".$rank["id_level"].", 'view_stats'), ";
            $query2.="(".$rank["id_level"].", 'view_mlist'), ";
            $query2.="(".$rank["id_level"].", 'search_posts'), ";
            $query2.="(".$rank["id_level"].", 'profile_view_own'), ";
            $query2.="(".$rank["id_level"].", 'profile_view_any'), ";
            $query2.="(".$rank["id_level"].", 'profile_upload_avatar'), ";
            $query2.="(".$rank["id_level"].", 'profile_server_avatar'), ";
            $query2.="(".$rank["id_level"].", 'profile_remote_avatar'), ";
            $query2.="(".$rank["id_level"].", 'profile_identity_own'), ";
            $query2.="(".$rank["id_level"].", 'profile_extra_own'), ";
            $query2.="(".$rank["id_level"].", 'pm_send'), ";
            $query2.="(".$rank["id_level"].", 'pm_read'), ";
            $query2.="(".$rank["id_level"].", 'karma_edit'), ";
            $query2.="(".$rank["id_level"].", 'calendar_view')";

            if($smf_type=="smf")
                $query3 ="INSERT INTO `{$db_prefix}membergroups` (`ID_GROUP`, `groupName`, `onlineColor`, `minPosts`, `stars`) VALUES ";
            else
                $query3 ="INSERT INTO `{$db_prefix}membergroups` (`id_group`, `group_name`, `online_color`, `min_posts`, `stars`) VALUES ";
            $query3.="(".$rank["id_level"].", '".$rank["level"]."', '', -1, '')";
        }
        // Run the queries
        @mysql_query($query1);
        @mysql_query($query2);
        @mysql_query($query3);
        @mysql_query("UPDATE `{$TABLE_PREFIX}users_level` SET `smf_group_mirror`=".$rank["id_level"]." WHERE `level`='".mysql_real_escape_string($rank["level"])."'");
    }
    // Allow all ranks to see the initial test forum
    @mysql_query("UPDATE `{$db_prefix}boards` SET `member".(($smf_type=="smf")?"G":"_g")."roups`='".substr($ranklist,0,strlen($ranklist)-1).",-1' WHERE ".(($smf_type=="smf")?"`ID_BOARD`":"`id_board`")."=1");
    // Disable forum registration
    @mysql_query("UPDATE `{$db_prefix}settings` SET `value`=3 WHERE `variable`='registration_method'");

    $smf_lang="smf/Themes/default/languages/Errors.english.php";
    require_once($smf_lang);
    
    // finding the host
    $host = empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] . (empty($_SERVER['SERVER_PORT']) || $_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT']) : $_SERVER['HTTP_HOST'];
    // finding the base path.
    $baseurl = 'http://' . $host . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
    
    // Update the registration closed message to something more appropriate
    $txt['registration_disabled'] = 'Sorry, registration via SMF is disabled. Registration for this forum must be done via the Tracker <a target="_self" href="'.$baseurl.'/index.php?page=signup">Here</a>.<br /><br />If you already have a tracker account please <a target="_self" href="index.php?action=login">login here</a> with the same credentials.';

    $fd=fopen($smf_lang, "w");

    $foutput="<?php\n\n";

    foreach($txt as $k => $v)
    {
        $foutput.="\$txt['$k']   =   '".str_replace("'", "\\'", $v)."';\n";
    }
    $foutput.="\n?>";

    fwrite($fd,$foutput);
    fclose($fd);

    // Make sure there is an smf_fid column in the users table, if not add one
    $query=mysql_query("SHOW COLUMNS FROM `{$TABLE_PREFIX}users` WHERE `Field`='smf_fid'");
    $count=mysql_num_rows($query);
    if ($count==0)
        @mysql_query("ALTER TABLE `{$TABLE_PREFIX}users` ADD `smf_fid` INT( 10 ) NOT NULL DEFAULT '0',  ADD INDEX (`smf_fid`)");
    else
    {
        $indexed=mysql_query("SHOW INDEX FROM `{$TABLE_PREFIX}users` WHERE `Key_name`='smf_fid'");
        if(@mysql_num_rows($indexed)==0)
        {
            mysql_query("ALTER TABLE `{$TABLE_PREFIX}users` ADD INDEX (`smf_fid`)");
        }
    }
    die($lang[24] . $lang[25] . $lang[35]);
}
elseif($act=="member_import" && $confirm=="yes")
{
    $smf_type=$_GET["smf_type"];

    if($start==2)
        $end=$start+98;
    else
        $end=$start+99;
    $newstart=$end+1;
    
    if($lastacc==0)
    {
        $last=mysql_query("SELECT `id` FROM `{$TABLE_PREFIX}users` ORDER BY `id` DESC LIMIT 1");
        $acc=mysql_fetch_assoc($res);
        $lastacc=$acc["id"];
    }
    
    // Import Tracker accounts to the forum
    $query="SELECT `u`.`id`, `u`.`username`, `u`.`id_level`+10 `id_level`, `u`.`password`, `u`.`pass_type`, `u`.`email`, UNIX_TIMESTAMP(`u`.`joined`) `joined`, `u`.`lip`, COUNT(`p`.`userid`) `posts` FROM `{$TABLE_PREFIX}users` `u` LEFT JOIN `{$TABLE_PREFIX}posts` `p` ON `u`.`id`=`p`.`userid` WHERE `u`.`id` >=$start AND `u`.`id` <=$end GROUP BY `u`.`id` ORDER BY `u`.`id` ASC";
    $list=mysql_query($query);
    $count=mysql_num_rows($list);
    if($start==2)
        @mysql_query("TRUNCATE TABLE `{$db_prefix}members`");
    if($count>0)
    {
        while ($account=mysql_fetch_assoc($list))
        {
            $counter++;
            @mysql_query("INSERT INTO `{$db_prefix}members` (".(($smf_type=="smf")?"`ID_MEMBER`, `memberName`, `dateRegistered`, `ID_GROUP`, `realName`, `passwd`, `emailAddress`, `memberIP`, `memberIP2`, `is_activated`, `passwordSalt`":"`id_member`, `member_name`, `date_registered`, `id_group`, `real_name`, `passwd`, `email_address`, `member_ip`, `member_ip2`, `is_activated`, `password_salt`").", `posts`) VALUES (".$account["id"].", '".$account["username"]."', ".$account["joined"].", ".$account["id_level"].", '".$account["username"]."', '".(($account["pass_type"]==1)?$account["password"]:"ffffffffffffffffffffffffffffffffffffffff")."', '".$account["email"]."', '".long2ip($account["lip"])."', '".long2ip($account["lip"])."', 1, '',".$account["posts"].")");
            @mysql_query("UPDATE `{$TABLE_PREFIX}users` SET `smf_fid`=".$account["id"]." WHERE `id`=".$account["id"]);
        }
        print("<script LANGUAGE=\"javascript\">window.location.href='".$_SERVER["PHP_SELF"]."?act=member_import&confirm=yes&start=$newstart&counter=$counter&lastacc=$lastacc&smf_type=$smf_type'</script>");
    }
    elseif($lastacc > $end)
    {
        print("<script LANGUAGE=\"javascript\">window.location.href='".$_SERVER["PHP_SELF"]."?act=member_import&confirm=yes&start=$newstart&counter=$counter&lastacc=$lastacc&smf_type=$smf_type'</script>");
    }
    
    $last=mysql_fetch_assoc(mysql_query("SELECT ".(($smf_type=="smf")?"`ID_MEMBER`, `memberName`":"`id_member`, `member_name`")." FROM `{$db_prefix}members` ORDER BY ".(($smf_type=="smf")?"`ID_MEMBER`":"`id_member`")." DESC LIMIT 1"));
    @mysql_query("UPDATE {$db_prefix}settings SET value='".(($smf_type=="smf")?$last["memberName"]:$last["member_name"])."' WHERE variable='latestRealName'");
    @mysql_query("UPDATE {$db_prefix}settings SET value='".(($smf_type=="smf")?$last["ID_MEMBER"]:$last["id_member"])."' WHERE variable='latestMember'");
    print($lang[28] . $counter . $lang[29]);
}
elseif($act=="import_forum" && $confirm!="yes")
    die($lang[30] . $lang[31] . $lang[35]);
elseif($act=="import_forum" && $confirm=="yes")
{
    $smf_type=$_GET["smf_type"];

    $res=mysql_query("SELECT COUNT(*) `count` FROM `{$TABLE_PREFIX}forums`");
    $row=mysql_fetch_assoc($res);
    if($row["count"]==0)
        print("<script LANGUAGE='javascript'>window.location.href='".$_SERVER["PHP_SELF"]."?act=completed&smf_type=$smf_type'</script>");

    if($smf_type=="smf")
    {
        $sqlquery ="SELECT MAX(`boardOrder`)+1 `nextboard`, `membergroups`, MAX(`catOrder`)+1 `nextcat` ";
        $sqlquery.="FROM `{$db_prefix}boards`, `{$db_prefix}categories` ";
        $sqlquery.="WHERE `ID_BOARD`=1 ";
        $sqlquery.="GROUP BY `membergroups`";
    }
    else
    {
        $sqlquery ="SELECT MAX(`board_order`)+1 `nextboard`, `member_groups` `membergroups`, MAX(`cat_order`)+1 `nextcat` ";
        $sqlquery.="FROM `{$db_prefix}boards`, `{$db_prefix}categories` ";
        $sqlquery.="WHERE `id_board`=1 ";
        $sqlquery.="GROUP BY `membergroups`";
    }

    $res=mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
    $row=mysql_fetch_assoc($res);
    $membergroups=substr($row["membergroups"], 0, strlen($row["membergroups"])-3);
    $nextboard=$row["nextboard"];
    $nextcat=$row["nextcat"];

    $sqlquery ="INSERT INTO `{$db_prefix}categories` ";
    $sqlquery.="SET `cat".(($smf_type=="smf")?"O":"_o")."rder`=$nextcat, `name`='My BTI Import'";

    @mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);

    $ourcat=mysql_insert_id();

    // SQL Query to grab the current Internal Forum Layout
    $sqlquery ="SELECT * ";
    $sqlquery.="FROM `{$TABLE_PREFIX}forums` ";
    $sqlquery.="ORDER BY `id` ASC";

    $res=mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
    $forumlist=array();

    // Lets put all the found results into a single array for later
    $subcat="";
    while($forums=mysql_fetch_assoc($res))
    {
        $i=$forums["id"];
        foreach($forums as $key => $value)
        {
            if($key=="minclassread" && $value!=1) $value=$value+10;
            elseif($key=="minclassread" && $value==1) $value=-1;
            elseif($key=="minclasswrite" && $value!=1) $value=$value+10;
            elseif($key=="minclasswrite" && $value==1) $value=-1;
            $forumlist[$i][$key]=$value;
        }
        if($forumlist[$i]["minclassread"]==-1)
            $forumlist[$i]["permissions"]=$membergroups . ",-1";
        else
            $forumlist[$i]["permissions"]=substr($membergroups, strpos($membergroups, sprintf("%s", $forumlist[$i]["minclassread"])), strlen($membergroups));
    
        $sqlquery ="INSERT INTO `{$db_prefix}boards` ";
        if($smf_type=="smf")
            $sqlquery.="(`ID_CAT`, `boardOrder`, `memberGroups`, `name`, `description`) ";
        else
            $sqlquery.="(`id_cat`, `board_order`, `member_groups`, `name`, `description`) ";
        $sqlquery.="VALUES ($ourcat, $nextboard, '".$forumlist[$i]["permissions"]."', ";
        $sqlquery.=" '".mysql_real_escape_string($forumlist[$i]["name"])."', ";
        $sqlquery.="'".mysql_real_escape_string($forumlist[$i]["description"])."')";

        @mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
        $forumlist[$i]["newid"]=mysql_insert_id();
        if($forumlist[$i]["id_parent"]!=0) $subcat.=$i .",";
        $nextboard++;
    }
    if(isset($subcat) && !empty($subcat))
    {
        $subcat=explode(",", substr($subcat, 0, strlen($subcat)-1));
        foreach($subcat AS $v)
        {
            $main=$forumlist[$v]["id_parent"];
            $forid=$forumlist[$v]["newid"];
            $newparent=$forumlist[$main]["newid"];
            if($smf_type=="smf")
                $sqlquery="UPDATE `{$db_prefix}boards` SET `ID_PARENT`=$newparent WHERE `ID_BOARD`=".$forid;
            else
                $sqlquery="UPDATE `{$db_prefix}boards` SET `id_parent`=$newparent WHERE `id_board`=".$forid;
            @mysql_query($sqlquery); /* or die(mysql_error()."<br />SQL Query:<br />".$sqlquery); */
        }
    }
    $sqlquery="SELECT * FROM `{$TABLE_PREFIX}topics` ORDER BY `id` ASC";
    $res=mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
    while($topiclist=mysql_fetch_assoc($res))
    {
        $i=$topiclist["id"];
        (($topiclist["locked"]=="no") ? $topiclist["locked"]=0 : $topiclist["locked"]=1);
        (($topiclist["sticky"]=="no") ? $topiclist["sticky"]=0 : $topiclist["sticky"]=1);
            
        foreach($topiclist AS $k => $v)
        {
            $topics[$i][$k]=$v;
        }
        if($smf_type=="smf")
            $sqlquery="INSERT INTO `{$db_prefix}topics` (`isSticky`, `ID_BOARD`, `ID_FIRST_MSG`, `ID_LAST_MSG`, `ID_MEMBER_STARTED`, `numViews`, `locked`) ";
        else
            $sqlquery="INSERT INTO `{$db_prefix}topics` (`is_sticky`, `id_board`, `id_first_msg`, `id_last_msg`, `id_member_started`, `num_views`, `locked`) ";
        if(isset($forumlist[$topics[$i]["forumid"]]["newid"]) && !empty($forumlist[$topics[$i]["forumid"]]["newid"]))
        {
            $sqlquery.="VALUES (".$topics[$i]["sticky"].", ".$forumlist[$topics[$i]["forumid"]]["newid"].", ".rand(0,2147483647).", ".rand(0,2147483647).", ".$topics[$i]["userid"].", ".$topics[$i]["views"].", ".$topics[$i]["locked"].")";
            @mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
            $topics[$i]["newtopicid"]=mysql_insert_id();
        }
    }
    $sqlquery="SELECT `p`.* , `u`.`username`, `u`.`email`, `u`.`lip`, `ua`.`username` `edit_username` FROM `{$TABLE_PREFIX}posts` `p` LEFT JOIN `{$TABLE_PREFIX}users` `u` ON `p`.`userid` = `u`.`id` LEFT JOIN `{$TABLE_PREFIX}users` `ua` ON `p`.`editedby` = `ua`.`id` ORDER BY `p`.`id` ASC";
    $res=mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);

    while($postlist=mysql_fetch_assoc($res))
    {
        $i=$postlist["id"];
            
        foreach($postlist AS $k => $v)
        {
            $posts[$i][$k]=$v;
        }
        if($smf_type=="smf")
            $sqlquery="INSERT INTO `{$db_prefix}messages` (`ID_TOPIC`, `ID_BOARD`, `posterTime`, `ID_MEMBER`, `subject`, `posterName`, `posterEmail`, `posterIP`, `smileysEnabled`, `modifiedTime`, `modifiedName`, `body`) ";
        else
            $sqlquery="INSERT INTO {$db_prefix}messages (`id_topic`, `id_board`, `poster_time`, `id_member`, `subject`, `poster_name`, `poster_email`, `poster_ip`, `smileys_enabled`, `modified_time`, `modified_name`, `body`) ";

        if(isset($topics[$posts[$i]["topicid"]]["newtopicid"]) && !empty($topics[$posts[$i]["topicid"]]["newtopicid"]))
        {
            $sqlquery.="VALUES (".$topics[$posts[$i]["topicid"]]["newtopicid"].", ".$forumlist[$topics[$posts[$i]["topicid"]]["forumid"]]["newid"].", ".$posts[$i]["added"].", ".$posts[$i]["userid"].", '".mysql_real_escape_string($topics[$posts[$i]["topicid"]]["subject"])."', '".$posts[$i]["username"]."', '".$posts[$i]["email"]."', '".long2ip($posts[$i]["lip"])."', 1, ".$posts[$i]["editedat"].", '".(($posts[$i]["editedby"]==0) ? "" : $posts[$i]["edit_username"])."', '".mysql_real_escape_string($posts[$i]["body"])."')";
            @mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
            $posts[$i]["newpostid"]=mysql_insert_id();
        }
    }
    $sqlquery="SELECT MAX(".(($smf_type=="smf")?"`ID_MSG`":"`id_msg`").") `max`, ".(($smf_type=="smf")?"`ID_BOARD`":"`id_board`")." `idb` FROM `{$db_prefix}messages` GROUP BY `idb`";
    $res=mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
    while($row=mysql_fetch_assoc($res))
    {
        $sqlquery="UPDATE `{$db_prefix}boards` SET ".(($smf_type=="smf")?"`ID_LAST_MSG`":"`id_last_msg`")."=".$row["max"]." WHERE ".(($smf_type=="smf")?"`ID_BOARD`":"`id_board`")."=".$row["idb"];
        @mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
    }
    $sqlquery="SELECT MIN(".(($smf_type=="smf")?"`ID_MSG`":"`id_msg`").") `min`, MAX(".(($smf_type=="smf")?"`ID_MSG`":"`id_msg`").") `max`, ".(($smf_type=="smf")?"`ID_TOPIC`":"`id_topic`")." `idt` FROM `{$db_prefix}messages` GROUP BY `idt`";
    $res=mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
    while($row=mysql_fetch_assoc($res))
    {
        $sqlquery="UPDATE `{$db_prefix}topics` SET ".(($smf_type=="smf")?"`ID_FIRST_MSG`":"`id_first_msg`")."=".$row["min"].", ".(($smf_type=="smf")?"`ID_LAST_MSG`":"`id_last_msg`")."=".$row["max"]." WHERE ".(($smf_type=="smf")?"`ID_TOPIC`":"`id_topic`")."=".$row["idt"];
        @mysql_query($sqlquery) or die(mysql_error()."<br />SQL Query:<br />".$sqlquery);
    }
print("<script LANGUAGE=\"javascript\">window.location.href='".$_SERVER["PHP_SELF"]."?act=completed&smf_type=$smf_type'</script>");
}
elseif($act=="completed")
{
    $smf_type=$_GET["smf_type"];
    // Lock import file from future use and change to smf mode
    @mysql_query("UPDATE `{$TABLE_PREFIX}settings` SET `value` ='smf".(($smf_type=="smf")?"":"2")."' WHERE `key`='forum'");
    @mysql_query("UPDATE `{$TABLE_PREFIX}users` SET `random`=54345 WHERE `id`=1");
    echo $lang[32] . $lang[33] . $lang[35];
}

?>