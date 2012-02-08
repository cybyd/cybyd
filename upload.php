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

$scriptname = htmlspecialchars($_SERVER["PHP_SELF"]."?page=upload");
$addparam = "";

require(load_language("lang_upload.php"));

require_once ("include/BDecode.php");
require_once ("include/BEncode.php");
//// Configuration//
function_exists("sha1") or die("<font color=\"red\">".$language["NOT_SHA"]."</font></body></html>");

if (!$CURUSER || $CURUSER["can_upload"]=="no")
   {
    err_msg($language["SORRY"],$language["ERROR"].$language["NOT_AUTHORIZED_UPLOAD"]);
    stdfoot();
    exit();
   }


if (isset($_FILES["torrent"]))
   {
   if ($_FILES["torrent"]["error"] != 4)
   {
      $fd = fopen($_FILES["torrent"]["tmp_name"], "rb") or stderr($language["ERROR"],$language["FILE_UPLOAD_ERROR_1"]);
      is_uploaded_file($_FILES["torrent"]["tmp_name"]) or stderr($language["ERROR"],$language["FILE_UPLOAD_ERROR_2"]);

      if((isset($_FILES["torrent"]["tmp_name"]) && !empty($_FILES["torrent"]["tmp_name"])) && (isset($_FILES["torrent"]["name"]) && !empty($_FILES["torrent"]["name"])))
      {
          $check_torr=check_upload($_FILES["torrent"]["tmp_name"], $_FILES["torrent"]["name"]);         

          switch($check_torr)
          {
              case 1:
              case 2:
                $check_torr_err=$language["ERR_MISSING_DATA"];
                if(file_exists($_FILES["torrent"]["tmp_name"]))
                    @unlink($_FILES["torrent"]["tmp_name"]);
                break;
                        
              case 3:
                $check_torr_err=$language["QUAR_TMP_FILE_MISS"];
                break;

              case 4:
                $check_torr_err=$language["QUAR_OUTPUT"];
                break;

              case 5:
              default:
                $check_torr_err="";
                break;
          }
          if($check_torr_err!="")
              stderr($language["ERROR"], $check_torr_err);
      }
      $length=filesize($_FILES["torrent"]["tmp_name"]);
      if ($length)
        $alltorrent = fread($fd, $length);
      else {
        err_msg($language["ERROR"],$language["FILE_UPLOAD_ERROR_3"]);
        stdfoot();
        exit();

       }
      $array = BDecode($alltorrent);
      if (!isset($array))
         {
          err_msg($language["ERROR"],$language["ERR_PARSER"]);
          stdfoot();
          exit();
         }
      if (!$array)
         {
          err_msg($language["ERROR"],$language["ERR_PARSER"]);
          stdfoot();
          exit();
         }
    if (in_array($array["announce"],$TRACKER_ANNOUNCEURLS) && $DHT_PRIVATE)
      {
      $array["info"]["private"]=1;
      $hash=sha1(BEncode($array["info"]));
      }
    else
      {
      $hash = sha1(BEncode($array["info"]));
      }
      fclose($fd);
      }

if (isset($_POST["filename"]))
   $filename = mysql_real_escape_string(htmlspecialchars($_POST["filename"]));
else
    $filename = mysql_real_escape_string(htmlspecialchars($_FILES["torrent"]["name"]));

if (isset($hash) && $hash) $url = $TORRENTSDIR . "/" . $hash . ".btf";
else $url = 0;
// Gold/Silver Torrent v 1.2 by Losmi / start
$gold = mysql_real_escape_string(0);
// setting gold post var
if (isset($_POST["gold"]) && $_POST["gold"] != '')
{
   $gold = mysql_real_escape_string($_POST["gold"]);
}
// Gold/Silver Torrent v 1.2 by Losmi / end

if (isset($_POST["info"]) && $_POST["info"]!="")
   $comment = mysql_real_escape_string($_POST["info"]);
else { // description is now required (same as for edit.php)
//    $comment = "";
        err_msg($language["ERROR"],$language["EMPTY_DESCRIPTION"]);
        stdfoot();
        exit();
  }

// filename not writen by user, we get info directly from torrent.
if (strlen($filename) == 0 && isset($array["info"]["name"]))
   $filename = mysql_real_escape_string(htmlspecialchars($array["info"]["name"]));

// description not writen by user, we get info directly from torrent.
if (isset($array["comment"]))
   $info = mysql_real_escape_string(htmlspecialchars($array["comment"]));
else
    $info = "";


if (isset($array["info"]) && $array["info"]) $upfile=$array["info"];
    else $upfile = 0;

if (isset($upfile["length"]))
{
  $size = (float)($upfile["length"]);
}
else if (isset($upfile["files"]))
     {
// multifiles torrent
         $size=0;
         foreach ($upfile["files"] as $file)
                 {
                 $size+=(float)($file["length"]);
                 }
     }
else
    $size = "0";

if (!isset($array["announce"]))
     {
     err_msg($language["ERROR"], $language["EMPTY_ANNOUNCE"]);
     stdfoot();
     exit();
}

      $categoria = intval(0+$_POST["category"]);
      $anonyme=sqlesc($_POST["anonymous"]);
      $curuid=intval($CURUSER["uid"]);

      // category check
      $rc=do_sqlquery("SELECT id FROM {$TABLE_PREFIX}categories WHERE id=$categoria",true);
      if (mysql_num_rows($rc)==0)
         {
             err_msg($language["ERROR"],$language["WRITE_CATEGORY"]);
             stdfoot();
             exit();
      }
      @mysql_free_result($rc);

      $announce=trim($array["announce"]);

      if ($categoria==0)
         {
             err_msg($language["ERROR"],$language["WRITE_CATEGORY"]);
             stdfoot();
             exit();
         }

      if ((strlen($hash) != 40) || !verifyHash($hash))
      {
          err_msg($language["ERROR"],$language["ERR_HASH"]);
          stdfoot();
          exit();
      }
//      if ($announce!=$BASEURL."/announce.php" && $EXTERNAL_TORRENTS==false)
      if (!in_array($announce,$TRACKER_ANNOUNCEURLS) && $EXTERNAL_TORRENTS==false)
         {
           err_msg($language["ERROR"],$language["ERR_EXTERNAL_NOT_ALLOWED"]);
           unlink($_FILES["torrent"]["tmp_name"]);
           stdfoot();
           exit();
         }
// Torrent Image Upload by Real_ptr / start
	$userfile = $_FILES["userfile"];
        $screen1 = $_FILES["screen1"];
        $screen2 = $_FILES["screen2"];
        $screen3 = $_FILES["screen3"];
        $image_types = Array ("image/bmp",
                                "image/jpeg",
                                "image/pjpeg",
                                "image/gif",
                                "image/x-png");
        switch($_FILES["userfile"]["type"]) {
            case 'image/bmp':
            $file_name = $hash.".bmp";
            break;
            case 'image/jpeg':
            $file_name = $hash.".jpg";
            break;
            case 'image/pjpeg':
            $file_name = $hash.".jpeg";
            break;
            case 'image/gif':
            $file_name = $hash.".gif";
            break;
            case 'image/x-png':
            $file_name = $hash.".png";
            break;
        }
        switch($_FILES["screen1"]["type"]) {
            case 'image/bmp':
            $file_name_s1 = "s1".$hash.".bmp";
            break;
            case 'image/jpeg':
            $file_name_s1 = "s1".$hash.".jpg";
            break;
            case 'image/pjpeg':
            $file_name_s1 = "s1".$hash.".jpeg";
            break;
            case 'image/gif':
            $file_name_s1 = "s1".$hash.".gif";
            break;
            case 'image/x-png':
            $file_name_s1 = "s1".$hash.".png";
            break;
        }
        switch($_FILES["screen2"]["type"]) {
            case 'image/bmp':
            $file_name_s2 = "s2".$hash.".bmp";
            break;
            case 'image/jpeg':
            $file_name_s2 = "s2".$hash.".jpg";
            break;
            case 'image/pjpeg':
            $file_name_s2 = "s2".$hash.".jpeg";
            break;
            case 'image/gif':
            $file_name_s2 = "s2".$hash.".gif";
            break;
            case 'image/x-png':
            $file_name_s2 = "s2".$hash.".png";
            break;
        }
        switch($_FILES["screen3"]["type"]) {
            case 'image/bmp':
            $file_name_s3 = "s3".$hash.".bmp";
            break;
            case 'image/jpeg':
            $file_name_s3 = "s3".$hash.".jpg";
            break;
            case 'image/pjpeg':
            $file_name_s3 = "s3".$hash.".jpeg";
            break;
            case 'image/gif':
            $file_name_s3 = "s3".$hash.".gif";
            break;
            case 'image/x-png':
            $file_name_s3 = "s3".$hash.".png";
            break;
        }
        $uploadfile = $GLOBALS["uploaddir"] . $file_name;
        $uploadfile1 = $GLOBALS["uploaddir"] . $file_name_s1;
        $uploadfile2 = $GLOBALS["uploaddir"] . $file_name_s2;
        $uploadfile3 = $GLOBALS["uploaddir"] . $file_name_s3;
        $file_size = $_FILES["userfile"]["size"];
        $file_size1 = $_FILES["screen1"]["size"];
        $file_size2 = $_FILES["screen2"]["size"];
        $file_size3 = $_FILES["screen3"]["size"];
        $file_type = $_FILES["userfile"]["type"];
        $file_type1 = $_FILES["screen1"]["type"];
        $file_type2 = $_FILES["screen2"]["type"];
        $file_type3 = $_FILES["screen3"]["type"];
        $file_size = makesize1($file_size);
        $file_size1 = makesize1($file_size1);
        $file_size2 = makesize1($file_size2);
        $file_size3 = makesize1($file_size3);
        if (isset($_FILES["userfile"]))
        {
            if ($_FILES["userfile"]["name"] =='')
            {
            // do nothing...
            }
            else
            {
                if ($file_size > $GLOBALS["file_limit"])
                {
                    err_msg($language["ERROR"],$language["FILE_UPLOAD_TO_BIG"].": ".$file_limit.". ".$language["IMAGE_WAS"].": ".$file_size);
                    stdfoot();
                    exit();
                }
                if (in_array (strtolower ($file_type), $image_types, TRUE))
                {
                    if (@move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
                    {
                    }
                    else
                    {
                        err_msg($language["ERROR"],$language["MOVE_IMAGE_TO"]." ".$GLOBALS["uploaddir"].". ".$language["CHECK_FOLDERS_PERM"]);
                        stdfoot();
                        exit();
                    }
                }
                else
                {
                    err_msg ($language["ERROR"],$language["ILEGAL_UPLOAD"]);
                    stdfoot();
                    exit;
                }
            }
        }
        if (isset($_FILES["screen1"]))
        {
            if ($_FILES["screen1"]["name"] =='')
            {
            // do nothing...
            }
            else
            {
                if ($file_size1 > $GLOBALS["file_limit"])
                {
                    err_msg($language["ERROR"],$language["FILE_UPLOAD_TO_BIG"].": ".$file_limit.". ".$language["IMAGE_WAS"].": ".$file_size1);
                    stdfoot();
                    exit();
                }
                if (in_array (strtolower ($file_type1), $image_types, TRUE))
                {
                    if (@move_uploaded_file($_FILES['screen1']['tmp_name'], $uploadfile1))
                    {
                    }
                    else
                    {
                        err_msg($language["ERROR"],$language["MOVE_IMAGE_TO"]." ".$GLOBALS["uploaddir"].". ".$language["CHECK_FOLDERS_PERM"]);
                        stdfoot();
                        exit();
                    }
                }
                else
                {
                    err_msg ($language["ERROR"],$language["ILEGAL_UPLOAD"]);
                    stdfoot();
                    exit;
                }
            }
        }
        if (isset($_FILES["screen2"]))
        {
            if ($_FILES["screen2"]["name"] =='')
            {
            // do nothing...
            }
            else
            {
                if ($file_size2 > $GLOBALS["file_limit"])
                {
                    err_msg($language["ERROR"],$language["FILE_UPLOAD_TO_BIG"].": ".$file_limit.". ".$language["IMAGE_WAS"].": ".$file_size2);
                    stdfoot();
                    exit();
                }
                if (in_array (strtolower ($file_type2), $image_types, TRUE))
                {
                    if (@move_uploaded_file($_FILES['screen2']['tmp_name'], $uploadfile2))
                    {
                    }
                    else
                    {
                        err_msg($language["ERROR"],$language["MOVE_IMAGE_TO"]." ".$GLOBALS["uploaddir"].". ".$language["CHECK_FOLDERS_PERM"]);
                        stdfoot();
                        exit();
                    }
                }
                else
                {
                    err_msg ($language["ERROR"],$language["ILEGAL_UPLOAD"]);
                    stdfoot();
                    exit;
                }
            }
        }
        if (isset($_FILES["screen3"]))
        {
            if ($_FILES["screen3"]["name"] =='')
            {
            // do nothing...
            }
            else
            {
                if ($file_size3 > $GLOBALS["file_limit"])
                {
                    err_msg($language["ERROR"],$language["FILE_UPLOAD_TO_BIG"].": ".$file_limit.". ".$language["IMAGE_WAS"].": ".$file_size3);
                    stdfoot();
                    exit();
                }
                if (in_array (strtolower ($file_type3), $image_types, TRUE))
                {
                    if (@move_uploaded_file($_FILES['screen3']['tmp_name'], $uploadfile3))
                    {
                    }
                    else
                    {
                        err_msg($language["ERROR"],$language["MOVE_IMAGE_TO"]." ".$GLOBALS["uploaddir"].". ".$language["CHECK_FOLDERS_PERM"]);
                        stdfoot();
                        exit();
                    }
                }
                else
                {
                    err_msg ($language["ERROR"],$language["ILEGAL_UPLOAD"]);
                    stdfoot();
                    exit;
                }
            }
        }
// Torrent Image Upload by Real_ptr / end
//      if ($announce!=$BASEURL."/announce.php")
        
      if (in_array($announce,$TRACKER_ANNOUNCEURLS)){
         $internal=true;
         // inserting into xbtt table
         if ($XBTT_USE)
              do_sqlquery("INSERT INTO xbt_files SET info_hash=0x$hash, ctime=UNIX_TIMESTAMP() ON DUPLICATE KEY UPDATE flags=0",true);
         $query = "INSERT INTO {$TABLE_PREFIX}files (info_hash, filename, url, info, category, data, size, comment, uploader,anonymous, bin_hash) VALUES (\"$hash\", \"$filename\", \"$url\", \"$info\",0 + $categoria,NOW(), \"$size\", \"$comment\",$curuid,$anonyme,0x$hash)";
      }else
          {
          // maybe we find our announce in announce list??
             $internal=false;
             if (isset($array["announce-list"]) && is_array($array["announce-list"]))
                {
                for ($i=0;$i<count($array["announce-list"]);$i++)
                    {
                    if (in_array($array["announce-list"][$i][0],$TRACKER_ANNOUNCEURLS))
                      {
                       $internal = true;
                       continue;
                      }
                    }
                }
              if ($internal)
                {
                // ok, we found our announce, so it's internal and we will set our announce as main
                   $array["announce"]=$TRACKER_ANNOUNCEURLS[0];
                   $query = "INSERT INTO {$TABLE_PREFIX}files (info_hash, filename, url, info, category, data, size, comment, uploader,anonymous, bin_hash) VALUES (\"$hash\", \"$filename\", \"$url\", \"$info\",0 + $categoria,NOW(), \"$size\", \"$comment\",$curuid,$anonyme,0x$hash)";
                   if ($XBTT_USE)
                        do_sqlquery("INSERT INTO xbt_files SET info_hash=0x$hash, ctime=UNIX_TIMESTAMP() ON DUPLICATE KEY UPDATE flags=0",true);
                }
              else
                  $query = "INSERT INTO {$TABLE_PREFIX}files (info_hash, filename, url, info, category, data, size, comment,external,announce_url, uploader,anonymous, bin_hash) VALUES (\"$hash\", \"$filename\", \"$url\", \"$info\",0 + $categoria,NOW(), \"$size\", \"$comment\",\"yes\",\"$announce\",$curuid,$anonyme,0x$hash)";
        }
      //echo $query;
      $status = do_sqlquery($query); //makeTorrent($hash, true);
      if ($status)
         {
         $mf=@move_uploaded_file($_FILES["torrent"]["tmp_name"] , $TORRENTSDIR . "/" . $hash . ".btf");
         if (!$mf)
           {
           // failed to move file
             do_sqlquery("DELETE FROM {$TABLE_PREFIX}files WHERE info_hash=\"$hash\"",true);
             if ($XBTT_USE)
                  do_sqlquery("UPDATE xbt_files SET flags=1 WHERE info_hash=0x$hash",true);
             stderr($language["ERROR"],$language["ERR_MOVING_TORR"]);
         }
// Torrent Image Upload by Real_ptr / start
	do_sqlquery("UPDATE {$TABLE_PREFIX}files set image='$file_name', screen1='$file_name_s1', screen2='$file_name_s2', screen3='$file_name_s3' WHERE info_hash=\"$hash\"");
// Torrent Image Upload by Real_ptr / end
// Gold/Silver Torrent v 1.2 by Losmi / start
	do_sqlquery("UPDATE {$TABLE_PREFIX}files SET gold='$gold' WHERE info_hash=\"$hash\"");
// Gold/Silver Torrent v 1.2 by Losmi / end
         // try to chmod new moved file, on some server chmod without this could result 600, seems to be php bug
         @chmod($TORRENTSDIR . "/" . $hash . ".btf",0766);
//         if ($announce!=$BASEURL."/announce.php")
        if (!in_array($announce,$TRACKER_ANNOUNCEURLS))
            {
                require_once("./include/getscrape.php");
                scrape($announce,$hash);
                $status=2;
                write_log("Uploaded new torrent $filename - EXT ($hash)","add");
            }
         else
             {
              if ($DHT_PRIVATE)
                   {
                   $alltorrent=bencode($array);
                   $fd = fopen($TORRENTSDIR . "/" . $hash . ".btf", "rb+");
                   fwrite($fd,$alltorrent);
                   fclose($fd);
                   }
                // with pid system active or private flag (dht disabled), tell the user to download the new torrent
                write_log("Uploaded new torrent $filename ($hash)","add");
               
            $status=1;
         }
      }
      else
          {
              err_msg($language["ERROR"],$language["ERR_ALREADY_EXIST"]);
              unlink($_FILES["torrent"]["tmp_name"]);
              stdfoot();
              die();
          }

} else {
$status=0;
}

$uploadtpl=new bTemplate();
$uploadtpl->set("language",$language);
$uploadtpl->set("upload_script","index.php");

switch ($status) {
case 0:
      foreach ($TRACKER_ANNOUNCEURLS as $taurl)
            $announcs=$announcs."$taurl<br />";
            
      $category = (!isset($_GET["category"])?0:explode(";",$_GET["category"]));
      // sanitize categories id
      if (is_array($category))
          $category = array_map("intval",$category);
      else
          $category = 0;

      $combo_categories=categories( $category[0] );
// Gold/Silver Torrent v 1.2 by Losmi / start
	$gold_level='';
        $res = get_result("SELECT * FROM {$TABLE_PREFIX}gold  WHERE id='1'", true);
            foreach ($res as $key=>$value)
            {
                $gold_level = $value["level"];
                
            } 
            
            if($gold_level>$CURUSER['id_level'])
            {
                 $uploadtpl->set("upload_gold_level",false,true);
            }
            else 
            {
                 $uploadtpl->set("upload_gold_level",true,true);
            }
      $gold_select_box = createGoldCategories();
      $uploadtpl->set("upload_gold_combo",$gold_select_box);
// Gold/Silver Torrent v 1.2 by Losmi / end
      $bbc = textbbcode("upload","info");
      $uploadtpl->set("upload.announces",$announcs);
      $uploadtpl->set("upload_categories_combo",$combo_categories);
      $uploadtpl->set("textbbcode",  $bbc);
// Torrent Image Upload by Real_ptr / start
$uploadtpl->set("imageon",$GLOBALS["imageon"] == "true", TRUE);
$uploadtpl->set("screenon",$GLOBALS["screenon"] == "true", TRUE);
// Torrent Image Upload by Real_ptr / end
      $tplfile="upload";
    break;
case 1:
    if ($PRIVATE_ANNOUNCE || $DHT_PRIVATE) {       
        $uploadtpl->set("MSG_DOWNLOAD_PID",$language["MSG_DOWNLOAD_PID"]);
        $tplfile="upload_finish";
        $uploadtpl->set("DOWNLOAD","<br /><a href=\"download.php?id=$hash&f=".urlencode($filename).".torrent\">".$language["DOWNLOAD"]."</a><br /><br />");
    }
    $tplfile="upload_finish";
    break;
case 2: 
    $tplfile="upload_finish";
    break;
}

?>
