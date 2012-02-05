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

/*

$btit_url_last="";
$btit_url_rss="";

if(get_remote_file("http://www.btiteam.org"))
{
    $btit_url_rss="http://www.btiteam.org/smf/index.php?type=rss;action=.xml;board=83;sa=news";
    $btit_url_last="http://www.btiteam.org/last_version.txt";
}

*/

$admin=array();

$res=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}tasks");
if ($res)
   {
    while ($result=mysql_fetch_array($res))
          {
          if ($result["task"]=="sanity")
             $admin["lastsanity"]=$language["LAST_SANITY"]."<br />\n".get_date_time($result["last_time"])."<br />\n(".$language["NEXT"].": ".get_date_time($result["last_time"]+intval($GLOBALS["clean_interval"])).")<br />\n<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=sanity&amp;action=now\">Do it now!</a><br />";
          elseif ($result["task"]=="update")
             $admin["lastscrape"]="<br />\n".$language["LAST_EXTERNAL"]."<br />\n".get_date_time($result["last_time"])."<br />\n(".$language["NEXT"].": ".get_date_time($result["last_time"]+intval($GLOBALS["update_interval"])).")<br />";
       }
   }

// check if XBTT tables are present in current db
$res=do_sqlquery("SHOW TABLES LIKE 'xbt%'");
$xbt_tables=array('xbt_config','xbt_deny_from_hosts','xbt_files','xbt_files_users','xbt_users');
$xbt_in_db=array();
if ($res)
   {
   while ($result=mysql_fetch_row($res))
         {
             $xbt_in_db[]=$result[0];
         }
 }
 $ad=array_diff($xbt_tables,$xbt_in_db);

 if (count($ad)==0)
    $admin["xbtt_ok"]="<br />\nIT SEEMS THAT ALL XBTT TABLES ARE PRESENT!<br />\n<br />\n";
 else
    $admin["xbtt_ok"]="";

unset($ad);
unset($xbt_tables);
unset($xbt_in_db);
         
unset($result);
mysql_free_result($res);

// check torrents' folder
if (file_exists($TORRENTSDIR))
  {
  if (is_writable($TORRENTSDIR))
        $admin["torrent_ok"]=("<br />\nTorrent's folder $TORRENTSDIR<br />\n<span style=\"color:#BEC635; font-weight: bold;\">is writable</span><br />\n");
  else
        $admin["torrent_ok"]=("<br />\nTorrent's folder $TORRENTSDIR<br />\nis <span style=\"color:#FF0000; font-weight: bold;\">NOT writable</span><br />\n");
  }
else
  $admin["torrent_ok"]=("<br />\nTorrent's folder $TORRENTSDIR<br />\n<span style=\"color:#FF0000; font-weight: bold;\">NOT FOUND!</span><br />\n");

// check cache folder
if (file_exists("$THIS_BASEPATH/cache"))
  {
  if (is_writable("$THIS_BASEPATH/cache"))
        $admin["cache_ok"]=("cache folder<br />\n<span style=\"color:#BEC635; font-weight: bold;\">is writable</span><br />\n");
  else
        $admin["cache_ok"]=("cache folder is<br />\n<span style=\"color:#FF0000; font-weight: bold;\">NOT writable</span><br />\n");
  }
else
  $admin["cache_ok"]=("cache folder<br />\n<span style=\"color:#FF0000; font-weight: bold;\">NOT FOUND!</span><br />\n");


// check censored worlds file
if (file_exists("badwords.txt"))
  {
  if (is_writable("badwords.txt"))
        $admin["badwords_ok"]=("Censored words file (badwords.txt)<br />\n<span style=\"color:#BEC635; font-weight: bold;\">is writable</span><br />\n");
  else
        $admin["badwords_ok"]=("Censored words file (badwords.txt)<br />\nis <span style=\"color:#FF0000; font-weight: bold;\">NOT writable</span> (cannot writing tracker's configuration change)<br />\n");
   }
else
  $admin["badwords_ok"]=("<br />\nCensored words file (badwords.txt)<br />\n<span style=\"color:#FF0000; font-weight: bold;\">NOT FOUND!</span><br />\n");

// check last version on btiteam.org site
/*
if($btit_url_last!="")
{
    $btit_last=get_cached_version($btit_url_last);
    if (!$btit_last)
    {
        $btit_last=get_remote_file($btit_url_last);
        if ($btit_last)
            write_cached_version($btit_url_last,$btit_last);
        else
            $btit_last="Last version n/a";
    }
}
else
    $btit_last="Last version n/a";

$current_version=explode(" ", strtolower($tracker_version)); // array('2.0.0','beta','2')
$last_version=explode("/",strtolower($btit_last));  // array('2.0.0','beta','2')

$your_version="";

// make further control only if differents
if ((implode(" ",$current_version)!=implode(" ",$last_version)))
  {
  $your_version.="<table width=\"100%\"><tr><td align=\"right\">Installed version:</td><td align=\"left\">".implode(" ",$current_version)."</td></tr>\n";
  $your_version.="<tr><td align=\"right\">Current version:</td><td align=\"left\">".implode(" ",$last_version)."</td></tr>\n";
  $your_version.="<tr><td colspan=\"2\" align=\"center\">Get Last Version <a href=\"http://www.btiteam.org\" target=\"_blank\">here</a>!</td></tr>\n</table>";
}
else
  {
  $your_version.="You have the latest xBtit version installed.($tracker_version Rev.$tracker_revision)";
}
if (!empty($your_version))
   $admin["xbtit_version"]=$your_version."<br />\n";
*/

$admin["infos"].=("<br />\n<table border=\"0\">\n");
$admin["infos"].=("<tr><td class=\"header\" align=\"center\">Server's OS</td></tr><tr><td align=\"left\">".php_uname()."</td></tr>");
$admin["infos"].=("<tr><td class=\"header\" align=\"center\">PHP version</td></tr><tr><td align=\"left\">".phpversion()."</td></tr>");

$sqlver=mysql_fetch_row(do_sqlquery("SELECT VERSION()"));
$admin["infos"].=("\n<tr><td class=\"header\" align=\"center\">MYSQL version</td></tr><tr><td align=\"left\">$sqlver[0]</td></tr>");
$sqlver=mysql_stat();
$sqlver=explode('  ',$sqlver);
$admin["infos"].=("\n<tr><td valign=\"top\" class=\"header\" align=\"center\">MYSQL stats</td></tr>\n");
for ($i=0;$i<count($sqlver);$i++)
      $admin["infos"].=("<tr><td align=\"left\">$sqlver[$i]</td></tr>\n");
$admin["infos"].=("\n</table><br />\n");

unset($sqlver);

// check for news on btiteam site (read rss from comunication forum)
/*
if($btit_url_rss!="")
{
    include("$THIS_BASEPATH/include/class.rssreader.php");

    $btit_news=get_cached_version($btit_url_rss);

    if (!$btit_news)
    {
        $frss=get_remote_file($btit_url_rss);

        if (!$frss)
            $btit_news="<div class=\"blocklist\" style=\"padding:5px; align:center;\">Unable to contact Btiteam's site</div>";
        else
        {
            $nrss=new rss_reader();
            $rss_array=$nrss->rss_to_array($frss);

            $btit_news="<div class=\"blocklist\" style=\"padding:5px;\">";
            if (!$rss_array)
                $btit_news="<div class=\"blocklist\" style=\"padding:5px;\">Unable to contact Btiteam's site</div>";
            else
            {
                foreach($rss_array[0]["item"] as $id=>$rss)
                {
                    $btit_news.=date("d M Y",strtotime($rss["pubDate"])).":&nbsp;\n<a href=\"".$rss["guid"]."\">".$rss["title"]."</a><br />\n<br />\n";
                    $btit_news.="\n".$rss["description"]."<br />\n<hr />\n";
                }
            }
            $btit_news.="</div>";
        }
        write_cached_version($btit_url_rss,$btit_news);
    }
}
else
    $btit_news="<div class=\"blocklist\" style=\"padding:5px; align:center;\">Unable to contact Btiteam's site</div>";
*/
//$admintpl->set("btit_news",set_block("BtiTacker Lastest News","left",$btit_news));
$admintpl->set("language",$language);
$admintpl->set("admin",$admin);


?>