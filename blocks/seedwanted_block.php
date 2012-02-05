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

global $CURUSER, $BASEURL, $STYLEURL, $XBTT_USE,$btit_settings;

if (!$CURUSER || $CURUSER["view_torrents"]=="no")
   {
    // do nothing
   }
else
    {
   $limit=10;
  if ($XBTT_USE)
     $sql = "SELECT f.info_hash as hash, f.seeds+ifnull(x.seeders,0) as seeds , f.leechers + ifnull(x.leechers,0) as leechers, dlbytes AS dwned, format(f.finished+ifnull(x.completed,0),0) as finished, filename, url, info, UNIX_TIMESTAMP(data) AS added, c.image, c.name AS cname, category AS catid, size, external, uploader FROM {$TABLE_PREFIX}files as f LEFT JOIN xbt_files x ON f.bin_hash=x.info_hash LEFT JOIN {$TABLE_PREFIX}categories as c ON c.id = f.category WHERE f.leechers + ifnull(x.leechers,0) > 0 AND f.seeds+ifnull(x.seeders,0) = 0 AND f.external='no' ORDER BY f.leechers + ifnull(x.leechers,0) DESC LIMIT $limit";
  else
     $sql = "SELECT info_hash as hash, seeds, leechers, dlbytes AS dwned, finished, filename, url, info, UNIX_TIMESTAMP(data) AS added, c.image, c.name AS cname, category AS catid, size, external, uploader FROM {$TABLE_PREFIX}files as f LEFT JOIN {$TABLE_PREFIX}categories as c ON c.id = f.category WHERE leechers >0 AND seeds = 0 AND external='no' ORDER BY leechers DESC LIMIT $limit";

   $row = get_result($sql,true,$btit_settings['cache_duration']);

   if (count($row)>0)
     {
       block_begin("Seeder Wanted");

       ?>
       <table cellpadding="4" cellspacing="1" width="100%">
       <tr>
         <td colspan="2" align="center" class="header">&nbsp;<?php echo $language["TORRENT_FILE"]; ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["CATEGORY"] ?>&nbsp;</td>
         <?php
         if (max(0,$CURUSER["WT"])>0)
         print("<TD align=\"center\" class=\"header\">".$language["WT"]."</TD>");
         ?>
         <td align="center" class="header">&nbsp;<?php echo $language["ADDED"] ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["SIZE"] ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["SHORT_S"] ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["SHORT_L"] ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["SHORT_C"] ?>&nbsp;</td>
       </tr>
       <?php

       if ($row)
       {
           foreach ($row as $id=>$data)
           {
           echo "<tr>\n";

               if ( strlen($data["hash"]) > 0 )
               {
                  echo "\t<td NOWRAP align=\"center\" class=\"lista\">";


           echo "<a class=seedwant href=download.php?id=".$data["hash"]."&amp;f=" . rawurlencode($data["filename"]) . ".torrent><img src='images/torrent.gif' border='0' alt='".$language["DOWNLOAD_TORRENT"]."' title='".$language["DOWNLOAD_TORRENT"]."' /></a>";


         //waitingtime
             if (max(0,$CURUSER["WT"])>0){
             if (max(0,$CURUSER['downloaded'])>0) $ratio=number_format($CURUSER['uploaded']/$CURUSER['downloaded'],2);
             else $ratio=0.0;
             $vz = $data['added']; // sql_timestamp_to_unix_timestamp($added["data"]);
             $timer = floor((time() - $vz) / 3600);
             if($ratio<1.0 && $CURUSER['uid']!=$data["uploader"]){
                 $wait=$CURUSER["WT"];
             }
             $wait -=$timer;
             if ($wait<=0)$wait=0;
             }
         //end waitingtime

                echo "</td>";
                if ($GLOBALS["usepopup"])
                     echo "\t<td width=60% class=\"lista\" style=\"padding-left:10px;\"><a class=\"seedwant\" href=\"javascript:popdetails('index.php?page=torrent-details&amp;id=" . $data['hash'] . "');\" title=\"" . $language["VIEW_DETAILS"] . ": " . $data["filename"] . "\">" . $data["filename"] . "</a></td>";
                else
                     echo "\t<TD align=\"left\" class=\"lista\" style=\"padding-left:10px;\"><A class=\"seedwant\" HREF=\"index.php?page=torrent-details&amp;id=".$data["hash"]."\" title=\"".$language["VIEW_DETAILS"].": ".$data["filename"]."\">".$data["filename"]."</A></td>";
                echo "\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\"><a class=\"seedwant\" href=\"index.php?page=torrents&category=$data[catid]\">" . image_or_link( ($data["image"] == "" ? "" : "$STYLEPATH/images/categories/" . $data["image"]), "", $data["cname"]) . "</td>";
                if (max(0,$CURUSER["WT"])>0)
                echo "\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\">".$wait." h</td>";
                include("include/offset.php");
                echo "\t<td nowrap=\"nowrap\" class=\"lista\" align=\"center\" style=\"text-align: center;\">" . date("d/m/Y", $data["added"]-$offset) . "</td>";
                echo "\t<td nowrap=\"nowrap\" align=\"center\" class=\"lista\" style=\"text-align: center;\">" . makesize($data["size"]) . "</td>";

                if ($data["external"]=="no")
                {
                    if ($GLOBALS["usepopup"])
                    {
                        echo "\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\" style=\"text-align: center;\"><a class=\"seedwant\" href=\"javascript:poppeer('index.php?page=peers&amp;id=".$data["hash"]."');\" title=\"".$language["PEERS_DETAILS"]."\">" . $data["seeds"] . "</a></td>\n";
                        echo "\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\" style=\"text-align: center;\"><a class=\"seedwant\" href=\"javascript:poppeer('index.php?page=peers&amp;id=".$data["hash"]."');\" title=\"".$language["PEERS_DETAILS"]."\">" .$data["leechers"] . "</a></td>\n";
                        if ($data["finished"]>0)
                            echo "\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\"><a class=\"seedwant\" href=\"javascript:poppeer('index.php?page=torrent_history&amp;id=".$data["hash"]."');\" title=\"History - ".$data["filename"]."\">" . $data["finished"] . "</a></td>";
                        else
                            echo "\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\">---</td>";
                    }
                    else
                    {
                        echo "\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\" style=\"text-align: center;\"><a class=\"seedwant\" href=\"index.php?page=peers&amp;id=".$data["hash"]."\" title=\"".$language["PEERS_DETAILS"]."\">" . $data["seeds"] . "</a></td>\n";
                        echo "\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\" style=\"text-align: center;\"><a class=\"seedwant\" href=\"index.php?page=peers&amp;id=".$data["hash"]."\" title=\"".$language["PEERS_DETAILS"]."\">" .$data["leechers"] . "</a></td>\n";
                        if ($data["finished"]>0)
                            echo "\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\"><a class=\"seedwant\" href=\"index.php?page=torrent_history&amp;id=".$data["hash"]."\" title=\"History - ".$data["filename"]."\">" . $data["finished"] . "</a></td>";
                        else
                            echo "\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\">---</td>";
                    }
                }
                else
                {
                    // linkcolor
                    echo "\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\" style=\"text-align: center;\">" . $data["seeds"] . "</td>";
                    echo "\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\" style=\"text-align: center;\">" .$data["leechers"] . "</td>";
                    if ($data["finished"]>0)
                        echo "\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\">" . $data["finished"] . "</td>";
                    else
                    echo "\t<td align=\"center\" class=\"lista\" style=\"text-align: center;\">---</td>";
                }
                echo "</tr>\n";
                }
           }
       }
       else
       {
         echo "<tr><td class=\"lista\" colspan=\"9\" align=\"center\" style=\"text-align: center;\">" . $language["NO_TORRENTS"]  . "</td></tr>";
       }

       print("</table>");

       block_end();
    }
    else
      echo "<table class=\"lista\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\"><tr><td><div align=\"center\" style=\"text-align: center;\">".$language["NO_TORRENTS"]."</div></td></tr></table>";
} // end if user can view
?>