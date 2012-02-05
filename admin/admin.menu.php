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

if ($moderate_user)
  {
    $admin_menu=array(
    0=>array(
            "title"=>$language["ACP_USERS_TOOLS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=masspm&amp;action=write" ,
                    "description"=>$language["ACP_MASSPM"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=pruneu" ,
                    "description"=>$language["ACP_PRUNE_USERS"]),
                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=searchdiff" ,
                    "description"=>$language["ACP_SEARCH_DIFF"])
                    )
            ),
    );

}
else
  {
    $admin_menu=array(
    0=>array(
            "title"=>$language["ACP_TRACKER_SETTINGS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=config&amp;action=read" ,
                    "description"=>$language["ACP_TRACKER_SETTINGS"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=banip&amp;action=read" ,
                    "description"=>$language["ACP_BAN_IP"]),

                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=language&amp;action=read" ,
                    "description"=>$language["ACP_LANGUAGES"]),
                          3=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=style&amp;action=read" ,
                    "description"=>$language["ACP_STYLES"]),
                          4=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=security_suite" ,
                    "description"=>$language["ACP_SECSUI_SET"]),
					      5=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=php_log" ,
                    "description"=>$language["LOGS_PHP"])
                                 )),
    1=>array(
            "title"=>$language["ACP_FRONTEND"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=category&amp;action=read" ,
                    "description"=>$language["ACP_CATEGORIES"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=poller&amp;action=read" ,
                    "description"=>$language["ACP_POLLS"]),
                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=badwords&amp;action=read" ,
                    "description"=>$language["ACP_CENSORED"]),
                          3=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=read" ,
                    "description"=>$language["ACP_BLOCKS"])
                    )
            ),
    2=>array(
            "title"=>$language["ACP_USERS_TOOLS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=groups&amp;action=read" ,
                    "description"=>$language["ACP_USER_GROUP"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=masspm&amp;action=write" ,
                    "description"=>$language["ACP_MASSPM"]),
                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=pruneu" ,
                    "description"=>$language["ACP_PRUNE_USERS"]),
                          3=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=searchdiff" ,
                    "description"=>$language["ACP_SEARCH_DIFF"])
                    )
            ),

    3=>array(
            "title"=>$language["ACP_TORRENTS_TOOLS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=prunet" ,
                    "description"=>$language["ACP_PRUNE_TORRENTS"]))
            ),

    4=>array(
            "title"=>$language["ACP_FORUM"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=forum&amp;action=read" ,
                    "description"=>$language["ACP_FORUM"])
                    )
            ),

    5=>array(
            "title"=>$language["ACP_OTHER_TOOLS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=dbutil" ,
                    "description"=>$language["ACP_DBUTILS"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=mysql_stats" ,
                    "description"=>$language["ACP_MYSQL_STATS"]),
                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=logview" ,
                    "description"=>$language["ACP_SITE_LOG"])
                    )
            ),
            
    6=>array(
            "title"=>$language["ACP_MODULES"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=module_config&amp;action=manage" ,
                    "description"=>$language["ACP_MODULES_CONFIG"])
                    )
            ),

    7=>array(
            "title"=>$language["ACP_HACKS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=read" ,
                    "description"=>$language["ACP_HACKS_CONFIG"])
                    )
            ),

    );
}
?>