<?php

// xDNS.ro & xLiST.ro

// xList .::. xDNS
// http://xDNS.ro/
// http://xLiST.ro/
// Modified By cybernet2u

/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2015  Btiteam
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

require_once("include/functions.php");
require_once("include/config.php");

if ($XBTT_USE)
   {
    $tseeds="f.seeds+ifnull(x.seeders,0)";
    $tleechs="f.leechers+ifnull(x.leechers,0)";
    $ttables="{$TABLE_PREFIX}files f INNER JOIN xbt_files x ON x.info_hash=f.bin_hash";
   }
else
    {
    $tseeds="f.seeds";
    $tleechs="f.leechers";
    $ttables="{$TABLE_PREFIX}files f";
    }

dbconn(true);

if ($CURUSER["view_torrents"]!="yes")
   {
   header(ERR_500);
   die;
}

header("Content-type: text/xml");

print("<?xml version=\"1.0\" encoding=\"".$GLOBALS["charset"]."\"?>");
?>

<rss version="2.0">
<channel>
<title><?php print $SITENAME;?></title>
<description>rss feed script designed and coded by beeman (modified by Lupin and VisiGod)</description>
<link><?php print $BASEURL;?></link>
<lastBuildDate><?php print date("D, d M Y H:i:s O");?></lastBuildDate>
<copyright><?php print "(c) ". date("Y",time())." " .$SITENAME;?></copyright>

<?php

  $getItems = "SELECT f.info_hash as id, f.comment as description, f.filename, $tseeds AS seeders, $tleechs as leechers, UNIX_TIMESTAMP( f.data ) as added, c.name as cname, f.size FROM $ttables LEFT JOIN {$TABLE_PREFIX}categories c ON c.id = f.category ORDER BY data DESC LIMIT 20";
  $doGet=get_result($getItems, true, $btit_settings['cache_duration']);

  foreach($doGet as $id=>$item)
   {
    $id=$item['id'];
    $filename=($item['filename']);
    $added=strip_tags(date("D, d M Y H:i:s O",$item['added']));
    $cat=strip_tags($item['cname']);
    $seeders=strip_tags($item['seeders']);
    $leechers=strip_tags($item['leechers']);
    $desc=format_comment($item['description']);
    $f=rawurlencode($item['filename']);
    // output to browser

?>

  <item>
  <title><![CDATA[<?php print htmlspecialchars("[$cat] $filename [".SEEDERS." ($seeders)/".LEECHERS." ($leechers)]");?>]]></title>
  <description><![CDATA[<?php print $desc; ?>]]></description>
  <link><?php print "$BASEURL";?>/index.php?page=torrent-details&amp;id=<?php print "$id";?></link>
  <guid><?php print "$BASEURL";?>/index.php?page=torrent-details&amp;id=<?php print "$id";?></guid>
  <enclosure url="<?php print("$BASEURL/download.php?id=$id&amp;f=$f.torrent");?>" length="<?php print $item["size"] ?>" type="application/x-bittorrent" />
  <pubDate><?php print $added;?></pubDate>
  </item>

<?php
}

?>
</channel>
</rss>
