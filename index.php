<?php

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


if (file_exists("install.unlock") && file_exists("install.php"))
   {
   if (dirname($_SERVER["PHP_SELF"])=="/" || dirname($_SERVER["PHP_SELF"])=="\\")
      header("Location: http://".$_SERVER["HTTP_HOST"]."/install.php");
   else
      header("Location: http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"])."/install.php");
   exit;
}

define("IN_BTIT",true);


$THIS_BASEPATH = dirname(__FILE__);

include("$THIS_BASEPATH/btemplate/bTemplate.php");

require("$THIS_BASEPATH/include/functions.php");

session_name("xbtit");
session_start();
dbconn(true);

// If they've updated to SMF 2.0 and their tracker settings still thinks they're using SMF 1.x.x force an update
if($FORUMLINK=="smf")
{
    $check_ver=get_result("SELECT `value` FROM `{$db_prefix}settings` WHERE `variable`='smfVersion'", true, 60);
    if(((int)substr($check_ver[0]["value"],0,1))==2)
        do_sqlquery("UPDATE `{$TABLE_PREFIX}settings` SET `value`='smf2' WHERE `key`='forum'", true);
    foreach (glob($THIS_BASEPATH."/cache/*.txt") as $filename)
        unlink($filename);
}



$sp = $_SERVER['SERVER_PORT']; $ss = $_SERVER['HTTPS']; if ( $sp =='443' || $ss == 'on' || $ss == '1') $p = 's';
$domain = 'http'.$p.'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$domain = str_replace('/index.php', '', $domain);

if ($BASEURL != $domain) {
 $currentFile = $_SERVER['REQUEST_URI']; preg_match("/[^\/]+$/",$currentFile,$matches);
 $filename = "/" . $matches[0];
 header ("Location: " . $BASEURL . $filename . "");          
}


$time_start = get_microtime();

//require_once ("$THIS_BASEPATH/include/config.php");

clearstatcache();

$style_css=load_css("main.css");

$idlang=intval($_GET["language"]);

$pageID=(isset($_GET["page"])?$_GET["page"]:"");

$no_columns=(isset($_GET["nocolumns"]) && intval($_GET["nocolumns"])==1?true:false);

//which module by cooly
if($pageID=="modules")
{
$MID=(isset($_GET["module"])?htmlentities($_GET["module"]):$MID="");
check_online(session_id(), ($MID==""?"index":$MID));
}else{
check_online(session_id(), ($pageID==""?"index":$pageID));
}

require(load_language("lang_main.php"));


$tpl=new bTemplate();
$tpl->set("main_title",$btit_settings["name"]." .::. "."Index");

// is language right to left?
if (!empty($language["rtl"]))
   $tpl->set("main_rtl"," dir=\"".$language["rtl"]."\"");
else
   $tpl->set("main_rtl","");
if (!empty($language["charset"]))
  {
   $GLOBALS["charset"]=$language["charset"];
   $btit_settings["default_charset"]=$language["charset"];
}
$tpl->set("main_charset",$GLOBALS["charset"]);
$tpl->set("main_css","$style_css");


require_once("$THIS_BASEPATH/include/blocks.php");


$logo.="<div></div>";
$dropdown=dropdown_menu();
$extra=extra_menu();
$slideIt="<span style=\"text-align:left;\"><a href=\"javascript:collapse2.slideit()\"><img src=\"$STYLEURL/images/slide.png\" border=\"0\" alt=\"click\" /></a></span>";
$header.="<div>".main_menu()."</div>";



$left_col=side_menu();
$right_col=right_menu();

if ($left_col=="" && $right_col=="")
   $no_columns=1;

include 'include/jscss.php';


$tpl->set("main_jscript",$morescript);
if (!$no_columns && $pageID!='admin' && $pageID!='forum' && $pageID!='torrents' && $pageID!='usercp') {
  $tpl->set("main_left",$left_col);
  $tpl->set("main_right",$right_col);
}

$tpl->set("main_logo",$logo);

$tpl->set("main_dropdown",$dropdown);

$tpl->set("main_extra",$extra);

$tpl->set("main_slideIt",$slideIt);

$tpl->set("main_header",$header.$err_msg_install);

$tpl->set("more_css",$morecss);


// assign main content
switch ($pageID) {

    case 'modules':
        $module_name = htmlspecialchars($_GET["module"]);
        $modules = get_result("SELECT * FROM {$TABLE_PREFIX}modules WHERE name=".sqlesc($module_name)." LIMIT 1", true, $btit_settings["cache_duration"]);
        if (count($modules) < 1) // MODULE NOT SET
           stderr($language["ERROR"],$language["MODULE_NOT_PRESENT"]);

        if ($modules[0]["activated"]=="no") // MODULE SET BUT NOT ACTIVED
           stderr($language["ERROR"],$language["MODULE_UNACTIVE"]);

        $module_out="";
        if (!file_exists("$THIS_BASEPATH/modules/$module_name/index.php")) // MODULE SET, ACTIVED, BUT WRONG FOLDER??
           stderr($language["ERROR"],$language["MODULE_LOAD_ERROR"]."<br />\n$THIS_BASEPATH/modules/$module_name/index.php");

        // ALL OK, LET GO :)
        require("$THIS_BASEPATH/modules/$module_name/index.php");
        $tpl->set("main_content",set_block(ucfirst($module_name),"center",$module_out));
        $tpl->set("main_title",$btit_settings["name"]." .::. ".ucfirst($module_name));
        break;

    case 'admin':
        require("$THIS_BASEPATH/admin/admin.index.php");
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Admin");
        // the main_content for current template is setting within admin/index.php
        break;
                
    case 'forum':
        require("$THIS_BASEPATH/forum/forum.index.php");
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Forum");
        break;

    case 'torrents':
        require("$THIS_BASEPATH/torrents.php");
        $tpl->set("main_content",set_block($language["MNU_TORRENT"],"center",$torrenttpl->fetch(load_template("torrent.list.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Browse Torrents");
        break;
                
// shouthistory
    case 'allshout':
        ob_start();
        require("$THIS_BASEPATH/ajaxchat/getHistoryChatData.php");
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Shout History");
        $out=ob_get_contents();
        ob_end_clean();
        $tpl->set("main_content",set_block($language["SHOUTBOX"]." ".$language["HISTORY"],"left",$out));
        break;

    case 'comment':
        require("$THIS_BASEPATH/comment.php");
        $tpl->set("main_content",set_block($language["COMMENTS"],"center",$tpl_comment->fetch(load_template("comment.tpl")),false));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Comment");
        break;

    case 'delete':
        require("$THIS_BASEPATH/delete.php");
        $tpl->set("main_content",set_block($language["DELETE_TORRENT"],"center",$torrenttpl->fetch(load_template("torrent.delete.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Delete torrent");
        break;

    case 'edit':
        require("$THIS_BASEPATH/edit.php");
        $tpl->set("main_content",set_block($language["EDIT_TORRENT"],"center",$torrenttpl->fetch(load_template("torrent.edit.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Edit torrent");
        break;
// Staff Page - Petr1fied / start / http://www.btiteam.org/smf/index.php?topic=19541.msg109523#msg109523
    case 'staff':
        require("$THIS_BASEPATH/staff.php");
        $tpl->set("main_content",set_block($SITENAME . " " . $language["STAFF"],"center",$stafftpl->fetch(load_template("staff.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Staff");
        break;
// Staff Page - Petr1fied / end
    case 'extra-stats':
        require("$THIS_BASEPATH/extra-stats.php");
        $tpl->set("main_content",set_block($language["MNU_STATS"],"center",$out));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Statistics");
        break;

    case 'history':
    case 'torrent_history':
        require("$THIS_BASEPATH/torrent_history.php");
        $tpl->set("main_content",set_block($language["MNU_TORRENT"],"center",$historytpl->fetch(load_template("torrent_history.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Torrent History");
        break;

    case 'login':
        require("$THIS_BASEPATH/login.php");
        $tpl->set("main_content",set_block($language["LOGIN"],"center",$logintpl->fetch(load_template("login.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Login");
        break;

    case 'moresmiles':
        require("$THIS_BASEPATH/moresmiles.php");
        $tpl->set("main_content",set_block($language["MORE_SMILES"],"center",$moresmiles_tpl->fetch(load_template("moresmiles.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." "."More Smilies");
        break;

   case 'news':
        require("$THIS_BASEPATH/news.php");
        $tpl->set("main_content",set_block($language["MANAGE_NEWS"],"center",$newstpl->fetch(load_template("news.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."News");
        break;

    case 'peers':
        require("$THIS_BASEPATH/peers.php");
        $tpl->set("main_content",set_block($language["MNU_TORRENT"],"center",$peerstpl->fetch(load_template("peers.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Torrent Peers");
        break;

    case 'recover':
        require("$THIS_BASEPATH/recover.php");
        $tpl->set("main_content",set_block($language["RECOVER_PWD"],"center",$recovertpl->fetch(load_template("recover.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Recover");
        break;

    case 'account':
    case 'signup':
        require("$THIS_BASEPATH/account.php");
        $tpl->set("more_css","<link rel=\"stylesheet\" type=\"text/css\" href=\"$BASEURL/jscript/passwdcheck.css\" />");
        $tpl->set("main_content",set_block($language["ACCOUNT_CREATE"],"center",$tpl_account->fetch(load_template("account.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Signup");
        break;

    case 'torrent-details':
    case 'details':
        require("$THIS_BASEPATH/details.php");
        $tpl->set("main_content",set_block($language["TORRENT_DETAIL"],"center",$torrenttpl->fetch(load_template("torrent.details.tpl")),($GLOBALS["usepopup"]?false:true)));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Torrent Details");
        break;

    case 'users':
        require("$THIS_BASEPATH/users.php");
        $tpl->set("main_content",set_block($language["MEMBERS_LIST"],"center",$userstpl->fetch(load_template("users.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Users");
        break;

    case 'usercp':
        require("$THIS_BASEPATH/user/usercp.index.php");
        // the main_content for current template is setting within users/index.php
        $tpl->set("main_title",$btit_settings["name"]." .::. "."My Panel");
        break;

    case 'upload':
        require("$THIS_BASEPATH/upload.php");
        $tpl->set("main_content",set_block($language["MNU_UPLOAD"],"center",$uploadtpl->fetch(load_template("$tplfile.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Upload");
        break;

    case 'userdetails':
        require("$THIS_BASEPATH/userdetails.php");
        $tpl->set("main_content",set_block($language["USER_DETAILS"],"center",$userdetailtpl->fetch(load_template("userdetails.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."Users Details");
        break;

    case 'viewnews':
        require("$THIS_BASEPATH/viewnews.php");
        $tpl->set("main_content",set_block($language["LAST_NEWS"],"center",$viewnewstpl->fetch(load_template("viewnews.tpl"))));
        $tpl->set("main_title",$btit_settings["name"]." .::. "."News");
        break;

    
    case 'index':
    case '':
    default:
        $tpl->set("main_content",center_menu());
        break;
}

// controll if client can handle gzip
if ($GZIP_ENABLED)
    {
     if (stristr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip") && extension_loaded('zlib') && ini_get("zlib.output_compression") == 0)
         {
         if (ini_get('output_handler')!='ob_gzhandler')
             {
             ob_start("ob_gzhandler");
             $gzip='enabled';
             }
         else
             {
             ob_start();
             $gzip='enabled';
             }
     }
     else
         {
         ob_start();
         $gzip='disabled';
         }
}
else
    $gzip='disabled';

// fetch page with right template
switch ($pageID) {

    // for admin page we will display page with header and only left column (for menu)
    case 'admin':
    case 'usercp':
        stdfoot(false,false,true);
        break;
            
        // for torrents and forums pages we will display page with header and no columns (for full view)
        case 'torrents':
        case 'forum':
        stdfoot(false,true,false,true,true);
        break;      

    // if popup enabled then we display the page without header and no columns, else full page
    case 'comment':
    case 'torrent-details':
    case 'torrent_history':
    case 'peers':
        stdfoot(($GLOBALS["usepopup"]?false:true));
        break;

    // we display the page without header and no columns
    case 'allshout':
    case 'moresmiles':
        stdfoot(false);
        break;

    // full page
    default:
        stdfoot();
        break;
}

?>
