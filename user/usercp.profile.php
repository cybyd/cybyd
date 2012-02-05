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


switch ($action)
{
    case 'post':
           $idlangue=intval(0+$_POST["language"]);
           $idstyle=intval(0+$_POST["style"]);
           $email=AddSlashes($_POST["email"]);
           $avatar=str_replace(array('\t','%25','%00'), array('','',''), htmlspecialchars(AddSlashes($_POST["avatar"])));
           $idflag=intval(0+$_POST["flag"]);
           $timezone=intval($_POST["timezone"]);

           // Password confirmation required to update user record
           (isset($_POST["passconf"])) ? $passcheck=hash_generate(array("salt" => $CURUSER["salt"]), $_POST["passconf"], $CURUSER["username"]) : $passcheck=array();
           if(isset($passcheck[$btit_settings["secsui_pass_type"]]) && is_array($passcheck[$btit_settings["secsui_pass_type"]]))
               $password=$passcheck[$btit_settings["secsui_pass_type"]]["hash"];
           else
               $password="";           

           if($password=="" || $CURUSER["password"]!=$password)
           {
               stderr($language["ERROR"], $language["ERR_PASS_WRONG"]);
               stdfoot();
               exit();
           }
           // Password confirmation required to update user record

           // check avatar is a valid image and one of the supported file types
           if($avatar && $avatar!="")
           {
               $imagearr=@getimagesize($avatar);
               if(!is_array($imagearr) || !in_array($imagearr["mime"], array("image/bmp", "image/jpeg", "image/pjpeg", "image/gif", "image/x-png", "image/png")))
                   stderr($language["ERROR"], $language["ERR_AVATAR_EXT"]);
           }

           if ($email=="")
          {
            err_msg($language["ERROR"],$language["ERR_NO_EMAIL"]);
            stdfoot();
            exit;
          }
           else
               {
               // Reverify Mail Hack by Petr1fied - Start --->
               if ($VALIDATION=="user") {
                   // Send a verification e-mail to the e-mail address they want to change it to
                   if (($email!="")&&($email!=$CURUSER["email"])) {
                       $id=$CURUSER["uid"];
                       // Generate a random number between 10000 and 99999
                       $floor = 100000;
                       $ceiling = 999999;
                       srand((double)microtime()*1000000);
                       $random = rand($floor, $ceiling);

                       // Update the members record with the random number and store the email they want to change to
                       do_sqlquery("UPDATE {$TABLE_PREFIX}users SET random='".$random."', temp_email='".$email."' WHERE id='".$id."'",true);

                       // Send the verification email
                       @ini_set("sendmail_from","");
                       if (mysql_errno()==0)
                          send_mail($email,$language["EMAIL_VERIFY"],$language["EMAIL_VERIFY_MSG"]."\n\n".$BASEURL."/index.php?page=usercp&do=verify&action=changemail&newmail=".$email."&uid=".$id."&random=".$random."","From: ".$SITENAME." <".$SITEEMAIL.">") OR stderr($language["ERROR"],$language["EMAIL_FAILED"]);
                       }
               }
               $set=array();

               if ($VALIDATION!="user") {
                   if ($email!="")
                   {
                       $set[]="email='$email'";
                       if(substr($GLOBALS["FORUMLINK"],0,3)=="smf")
                       {
                           do_sqlquery("UPDATE `{$db_prefix}members` SET `email".(($GLOBALS["FORUMLINK"]=="smf")?"A":"_a")."ddress`='".$email."' WHERE ".(($GLOBALS["FORUMLINK"]=="smf")?"`ID_MEMBER`":"`id_member`")."=".$CURUSER["smf_fid"]);
                       }
                       elseif($GLOBALS["FORUMLINK"]=="ipb")
                       {
                           if(!defined('IPS_ENFORCE_ACCESS'))
                               define('IPS_ENFORCE_ACCESS', true);
                           if(!defined('IPB_THIS_SCRIPT'))
                               define( 'IPB_THIS_SCRIPT', 'public' );
                           require_once($THIS_BASEPATH. '/ipb/initdata.php' );
                           require_once( IPS_ROOT_PATH . 'sources/base/ipsRegistry.php' );
                           require_once( IPS_ROOT_PATH . 'sources/base/ipsController.php' );
                           $registry = ipsRegistry::instance(); 
                           $registry->init();
                           IPSMember::save($CURUSER["ipb_fid"], array("members" => array("email" => "$email")));
                       }
                   }
                }
                // <--- Reverify Mail Hack by Petr1fied - End
               if ($idlangue>0)
                  $set[]="language=$idlangue";
               if ($idstyle>0)
                  $set[]="style=$idstyle";
               if ($idflag>0)
                  $set[]="flag=$idflag";

               $set[]="time_offset='$timezone'";
               $set[]="avatar='$avatar'";
               $set[]="topicsperpage=".intval(0+$_POST["topicsperpage"]);
               $set[]="postsperpage=".intval(0+$_POST["postsperpage"]);
               $set[]="torrentsperpage=".intval(0+$_POST["torrentsperpage"]);

               $updateset=implode(",",$set);

               // Reverify Mail Hack by Petr1fied - Start --->
               // If they've tried to change their e-mail, give them a message telling them as much
               if (($email!="")&&($VALIDATION=="user")&&($email!=$CURUSER["email"]))
                  {
                  success_msg($language["EMAIL_VERIFY_BLOCK"], "".$language["EMAIL_VERIFY_SENT1"]." ".$email." ".$language["EMAIL_VERIFY_SENT2"]."<a href=\"".$BASEURL."\">".$language["MNU_INDEX"]."</a>");
                  stdfoot(true,false);
                  exit;
                  }
               elseif ($updateset!="")
               // <--- Reverify Mail Hack by Petr1fied - End
                  {
                  do_sqlquery("UPDATE {$TABLE_PREFIX}users SET $updateset WHERE id='".$uid."'",true);

                  success_msg($language["SUCCESS"], $language["INF_CHANGED"]."<br /><a href=\"index.php?page=usercp&amp;uid=".$uid."\">".$language["BCK_USERCP"]."</a>");
                  stdfoot(true,false);
                  exit;
                  }
                $_SESSION['user']['style_url']='';
                $_SESSION['user']['language_path']='';
              }
    break;

    case '':
    case 'change':
    default:
      $usercptpl->set("AVATAR",false,true);
      $usercptpl->set("USER_VALIDATION",false,true);
      $usercptpl->set("INTERNAL_FORUM",false,true);
      $profiletpl=array();
      $profiletpl["frm_action"]="index.php?page=usercp&amp;do=user&amp;action=post&amp;uid=".$uid."";
      $profiletpl["username"]=$CURUSER["username"];

      //avatar
      if ($CURUSER["avatar"] && $CURUSER["avatar"]!="")
        {
          $usercptpl->set("AVATAR",true,true);
          $profiletpl["avatar"]="<img border=\"0\" onload=\"resize_avatar(this);\" src=\"".htmlspecialchars(unesc($CURUSER["avatar"]))."\" alt=\"\" />";
        }

      $profiletpl["avatar_field"]=unesc($CURUSER["avatar"]);
      $profiletpl["email"]=unesc($CURUSER["email"]);

      //Reverify Mail Hack by Petr1fied - Start
      if ($VALIDATION=="user")
        {
          //Display a message informing users that they will have
          //to verify their e-mail address if they attempt to change it
          $usercptpl->set("USER_VALIDATION",true,true);
        }
      //Reverify Mail Hack by Petr1fied - End

      //language list
      $lres=language_list();
      $langtpl=array();
        foreach($lres as $langue)
          {
             $langtpl["language_combo"].="\n<option ";
         if ($langue["id"]==$CURUSER["language"])
        $langtpl["language_combo"].="selected=\"selected\" ";
         $langtpl["language_combo"].="value=\"".$langue["id"]."\">".unesc($langue["language"])."</option>";
         $langtpl["language_combo"].=($option);
           }
        unset($lres);
      $usercptpl->set("lang",$langtpl);

      //style list
      $sres=style_list();
      $styletpl=array();
        foreach($sres as $style)
          {
        $styletpl["style_combo"].="\n<option ";
          if ($style["id"]==$CURUSER["style"])
        $styletpl["style_combo"].="selected=\"selected\" ";
        $styletpl["style_combo"].="value=\"".$style["id"]."\">".unesc($style["style"])."</option>";
        $styletpl["style_combo"].=($option);
          }
        unset($sres);
      $usercptpl->set("style",$styletpl);

      //flag list
      $fres=flag_list();
      $flagtpl=array();
        foreach($fres as $flag)
          {
        $flagtpl["flag_combo"].="\n<option ";
          if ($flag["id"]==$CURUSER["flag"])
        $flagtpl["flag_combo"].="selected=\"selected\" ";
        $flagtpl["flag_combo"].="value=\"".$flag["id"]."\">".unesc($flag["name"])."</option>";
        $flagtpl["flag_combo"].=($option);
          }
        unset($fres);
      $usercptpl->set("flag",$flagtpl);

      //timezone list
      $tres=timezone_list();
      $tztpl=array();
        foreach($tres as $timezone)
          {
        $tztpl["tz_combo"].="\n<option ";
          if ($timezone["difference"]==$CURUSER["time_offset"])
        $tztpl["tz_combo"].="selected=\"selected\" ";
        $tztpl["tz_combo"].="value=\"".$timezone["difference"]."\">".unesc($timezone["timezone"])."</option>";
        $tztpl["tz_combo"].=($option);
          }
        unset($tres);
      $usercptpl->set("tz",$tztpl);

      if ($FORUMLINK=="" || $FORUMLINK=="internal")
        {
          $usercptpl->set("INTERNAL_FORUM",true,true);
          $profiletpl["topicsperpage"]=$CURUSER["topicsperpage"];
          $profiletpl["postsperpage"]=$CURUSER["postsperpage"];
        }

      $profiletpl["torrentsperpage"]=$CURUSER["torrentsperpage"];
      $profiletpl["frm_cancel"]="index.php?page=usercp&amp;uid=".$uid."";
      $usercptpl->set("profile",$profiletpl);
    break;
}
?>