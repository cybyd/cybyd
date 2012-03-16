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

if (!defined('IN_ACP'))
    die('non direct access!');

include(load_language('lang_usercp.php'));
# get uid
$uid=isset($_GET['uid'])?(int)$_GET['uid']:0;
# test uid
if ($uid==$CURUSER['uid'] || $uid==1) {
    if ($action=='delete') # cannot delete guest/myself
        stderr($language['ERROR'],$language['USER_NOT_DELETE']);
    # cannot edit guest/myself
    stderr($language['ERROR'],$language['USER_NOT_EDIT']);
}

# get uid info
if ($XBTT_USE)
    $curu=get_result('SELECT u.username, u.cip, ul.level, ul.id_level as base_level, u.email, u.avatar, u.joined, u.lastconnect, u.id_level, u.custom_title, u.language, u.style, u.flag, u.time_offset, u.topicsperpage, u.postsperpage, u.torrentsperpage, (u.downloaded+x.downloaded) as downloaded, (u.uploaded+x.uploaded) as uploaded, u.smf_fid, u.ipb_fid FROM '.$TABLE_PREFIX.'users u INNER JOIN '.$TABLE_PREFIX.'users_level ul ON ul.id=u.id_level LEFT JOIN xbt_users x ON x.uid=u.id WHERE u.id='.$uid.' LIMIT 1', true);
else
    $curu=get_result('SELECT u.username, u.cip, ul.level, ul.id_level as base_level, u.email, u.avatar, u.joined, u.lastconnect, u.id_level, u.custom_title, u.language, u.style, u.flag, u.time_offset, u.topicsperpage, u.postsperpage, u.torrentsperpage, u.downloaded, u.uploaded, u.smf_fid, u.ipb_fid FROM '.$TABLE_PREFIX.'users u INNER JOIN '.$TABLE_PREFIX.'users_level ul ON ul.id=u.id_level WHERE u.id='.$uid.' LIMIT 1', true);

# test for bad id
if (!isset($curu[0]))
    stderr($language['ERROR'],$language['BAD_ID']);
# save memory address sums
$curu=$curu[0];
# test levels
if ($CURUSER['id_level'] < $curu['base_level']){
    if ($action=='delete') # cannot delete guest/myself
        stderr($language['ERROR'],$language['USER_NOT_DELETE_HIGHER']);
    # cannot edit guest/myself
    stderr($language['ERROR'],$language['USER_NOT_EDIT_HIGHER']);
}
$note='';
# find smf_id
$smf_fid=false;
$ipb_fid=false;
if (substr($FORUMLINK,0,3)=='smf')
{
    if (!isset($curu['smf_fid']) || $curu['smf_fid']==0)
    {
        # go full mysql search on it's ass
        $smf_user=get_result("SELECT ".(($FORUMLINK=="smf")?"`ID_MEMBER`":"`id_member`")." FROM `{$db_prefix}members` WHERE `member".(($FORUMLINK=="smf")?"N":"_n")."ame`=".sqlesc($curu['username'])." LIMIT 1");
        if (isset($smf_user[0]))
        {
            $smf_fid=$smf_user[0]['ID_MEMBER'];
            quickQuery('UPDATE `'.$TABLE_PREFIX.'users` SET `smf_fid`='.$smf_fid.' WHERE `id`='.$uid.' LIMIT 1;');
        }
        else
        {
            $smf_fid=false;
            $note=' User not found in SMF.';
        }
    }
    else
        $smf_fid=$curu['smf_fid'];
}
elseif ($FORUMLINK=='ipb') 
{
    if (!isset($curu['ipb_fid']) || $curu['ipb_fid']==0)
    {
        # go full mysql search on it's ass
        $ipb_user=get_result('SELECT `member_id` FROM `'.$ipb_prefix.'members` WHERE `name`='.sqlesc($curu['username']).' LIMIT 1;');
        if (isset($ipb_user[0]))
        {
            $ipb_fid=$ipb_user[0]['member_id'];
            quickQuery('UPDATE `'.$TABLE_PREFIX.'users` SET `ipb_fid`='.$ipb_fid.' WHERE `id`='.$uid.' LIMIT 1;');
        }
        else
        {
            $ipb_fid=false;
            $note=' User not found in IPB.';
        }
    }
    else
        $ipb_fid=$curu['ipb_fid'];
}

# init vars
if (isset($_GET['returnto'])) {
    $ret_decode=urldecode($_GET['returnto']);
    $ret_url=htmlspecialchars($_GET['returnto']);
} else {
    $ret_decode='index.php';
    $ret_url='index.php';
}
$edit=true;
$profile=array();
$newname='';

switch ($action) {
    case 'delete':
        if (isset($_GET['sure']) && $_GET['sure']==1) {
            quickQuery('DELETE FROM '.$TABLE_PREFIX.'users WHERE id='.$uid.' LIMIT 1;',true);
            if (substr($FORUMLINK,0,3)=='smf')
                quickQuery("DELETE FROM `{$db_prefix}members` WHERE ".(($FORUMLINK=="smf")?"`ID_MEMBER`":"`id_member`")."=".$smf_fid." LIMIT 1");
            elseif ($FORUMLINK=='ipb')
                quickQuery("DELETE FROM `{$ipb_prefix}members` WHERE `member_id`=".$ipb_fid." LIMIT 1");
            if ($XBTT_USE)
                quickQuery('DELETE FROM xbt_users WHERE uid='.$uid.' LIMIT 1;');

            write_log('Deleted '.unesc($curu['level']).' '.$profile['username'],'modified');
            redirect($ret_decode);
        } else {
            $edit=false;
            $block_title=$language['ACCOUNT_EDIT'];
            $profile['username']=unesc($curu['username']);
            $profile['last_ip']=unesc($curu['cip']);
            $profile['level']=unesc($curu['level']);
            $profile['joined']=unesc($curu['joined']);
            $profile['lastaccess']=unesc($curu['lastconnect']);
            $profile['downloaded']=makesize($curu['downloaded']);
            $profile['uploaded']=makesize($curu['uploaded']);
            $profile['return']='document.location.href=\''.$ret_decode.'\'';
            $profile['confirm_delete']='document.location.href=\'index.php?page=admin&amp;user='.$CURUSER['uid'].'&amp;code='.$CURUSER['random'].'&amp;do=users&amp;action=delete&amp;uid='.$uid.'&amp;sure=1&amp;returnto='.$ret_url.'\'';
        }
        break;

    case 'edit':
        # init vars

        $pass_min_req=explode(",", $btit_settings["secsui_pass_min_req"]);
        $admintpl->set("pass_min_char",$pass_min_req[0]);
        $admintpl->set("pass_min_lct",$pass_min_req[1]);
        $admintpl->set("pass_min_uct",$pass_min_req[2]);
        $admintpl->set("pass_min_num",$pass_min_req[3]);
        $admintpl->set("pass_min_sym",$pass_min_req[4]);
        $admintpl->set("pass_char_plural", (($pass_min_req[0]==1)?false:true),true);
        $admintpl->set("pass_lct_plural", (($pass_min_req[1]==1)?false:true),true);
        $admintpl->set("pass_uct_plural", (($pass_min_req[2]==1)?false:true),true);
        $admintpl->set("pass_num_plural", (($pass_min_req[3]==1)?false:true),true);
        $admintpl->set("pass_sym_plural", (($pass_min_req[4]==1)?false:true),true);
        $admintpl->set("pass_lct_set", (($pass_min_req[1]>0)?true:false),true);
        $admintpl->set("pass_uct_set", (($pass_min_req[2]>0)?true:false),true);
        $admintpl->set("pass_num_set", (($pass_min_req[3]>0)?true:false),true);
        $admintpl->set("pass_sym_set", (($pass_min_req[4]>0)?true:false),true);

        $profile['username']=unesc($curu['username']);
        $profile['email']=unesc($curu['email']);
        $profile['uploaded']=$curu['uploaded'];
        $profile['downloaded']=$curu['downloaded'];
        $profile['down']=makesize($curu['downloaded']);
        $profile['up']=makesize($curu['uploaded']);
        $profile['ratio']=($curu['downloaded']>0?$curu['uploaded']/$curu['downloaded']:'');
        # init options
        $opts['name']='level';
        $opts['complete']=true;
        $opts['id']='id';
        $opts['value']='level';
        $opts['default']=$curu['id_level'];
// Custom title - start
	$profile['custom_title']=unesc($curu['custom_title']);
// Custom title - end
        # rank list
        $ranks=rank_list();
        $admintpl->set('rank_combo',get_combo($ranks, $opts));
        # lang list
        $opts['name']='language';
        $opts['value']='language';
        $opts['default']=$curu['language'];
        $langs=language_list();
        $admintpl->set('language_combo',get_combo($langs, $opts));
        # style list
        $opts['name']='style';
        $opts['value']='style';
        $opts['default']=$curu['style'];
        $styles=style_list();
        $admintpl->set('style_combo',get_combo($styles, $opts));
        # timezone list
        $opts['name']='timezone';
        $opts['id']='difference';
        $opts['value']='timezone';
        $opts['default']=$curu['time_offset'];
        $tzones=timezone_list();
        $admintpl->set('tz_combo',get_combo($tzones, $opts));
        # flag list
        $opts['complete']=false;
        $opts['value']='name';
        $opts['id']='id';
        $opts['default']=$curu['flag'];
        $flags=flag_list();
        $admintpl->set('flag_combo',get_combo($flags, $opts));
        # posts/topics per page
        if ($FORUMLINK=='' || $FORUMLINK=='internal') {
            $admintpl->set('INTERNAL_FORUM',true,true);
            $profile['topicsperpage']=$curu['topicsperpage'];
            $profile['postsperpage']=$curu['postsperpage'];
        } else {
            $admintpl->set('INTERNAL_FORUM',false,true);
            $profile['topicsperpage']='';
            $profile['postsperpage']='';
        }
        # torrents per page
        $profile['torrentsperpage']=$curu['torrentsperpage'];
        # avatar
        $profile['avatar']=($curu['avatar']!='')?$curu['avatar']:$STYLEURL.'/images/default_avatar.gif';
        $profile['avatar_field']=unesc($curu['avatar']);
        $profile['avatar']='<img onload="resize_avatar(this);" src="'.htmlspecialchars($profile['avatar']).'" alt="" />';
        # form stuff
        $profile['frm_action']='index.php?page=admin&amp;user='.$CURUSER['uid'].'&amp;code='.$CURUSER['random'].'&amp;do=users&amp;action=save&amp;uid='.$uid;
        $profile['frm_cancel']='index.php?page=usercp&amp;uid='.$uid;
        # title
        $block_title=$language['ACCOUNT_EDIT'];
        break;
    
    case 'save':
        if ($_POST['confirm']==$language['FRM_CONFIRM']) {

            if($FORUMLINK=="ipb")
            {
                if(!defined('IPS_ENFORCE_ACCESS'))
                    define('IPS_ENFORCE_ACCESS', true);
                if(!defined('IPB_THIS_SCRIPT'))
                    define('IPB_THIS_SCRIPT', 'public');

                require_once($THIS_BASEPATH. '/ipb/initdata.php' );
                require_once( IPS_ROOT_PATH . 'sources/base/ipsRegistry.php' );
                require_once( IPS_ROOT_PATH . 'sources/base/ipsController.php' );
                $registry = ipsRegistry::instance(); 
                $registry->init();
            }

            $idlangue=(int)$_POST['language'];
            $idstyle=(int)$_POST['style'];
            $idflag=(int)$_POST['flag'];
            $level=(int)$_POST['level'];
            $time=(int)$_POST['timezone']; # this is wrong, half hour based time zones won't work
            $topicsperpage=(isset($_POST['topicsperpage']))?(int)$_POST['topicsperpage']:$curu['topicsperpage'];
            $postsperpage=(isset($_POST['postsperpage']))?(int)$_POST['postsperpage']:$curu['postsperpage'];
            $torrentsperpage=(int)$_POST['torrentsperpage'];
            $uploaded=(float)$_POST['uploaded'];
            $downloaded=(float)$_POST['downloaded'];
            $email=AddSlashes($_POST['email']);
            $avatar=unesc($_POST['avatar']);
            $username=unesc($_POST['username']);
            $pass=$_POST['pass'];
            $chpass=(isset($_POST['chpass']) && $pass!='');
            # new level of the user
// Custom title - start
	    $custom_title=unesc($_POST["custom_title"]);
// Custom title - end
            $rlev=do_sqlquery("SELECT `id_level` `base_level`, `level` `name`".((substr($FORUMLINK,0,3)=='smf')?", `smf_group_mirror`":(($FORUMLINK=='ipb')?", `ipb_group_mirror`":""))." FROM {$TABLE_PREFIX}users_level WHERE id=".$level." LIMIT 1");
            $reslev=mysql_fetch_assoc($rlev);
            if ( ($CURUSER['id_level'] < $reslev['base_level']))
                $level=0;
            # check avatar image extension if someone have better idea ;)
            if ($avatar && $avatar!='' && !in_array(substr($avatar,strlen($avatar)-4),array('.gif','.jpg','.bmp','.png')))
                stderr($language['ERROR'], $language['ERR_AVATAR_EXT']);
            if ($idlangue>0 && $idlangue != $curu['language'])
                $set[]='language='.$idlangue;
            if ($idstyle>0 && $idstyle != $curu['style'])
                $set[]='style='.$idstyle;
            if ($idflag>0 && $idflag != $curu['flag'])
                $set[]='flag='.$idflag;
            if ($level>0 && $level != $curu['id_level']) {
                if (substr($FORUMLINK,0,3)=='smf') {
                    # find the coresponding level in smf
                    if($reslev["smf_group_mirror"]==0)
                        $smf_group=get_result("SELECT ".(($FORUMLINK=="smf")?"`ID_GROUP`":"`id_group`")." FROM `{$db_prefix}membergroups` WHERE `group".(($FORUMLINK=="smf")?"N":"_n")."ame`='".$reslev["name"]."' LIMIT 1", true, $CACHE_DURATION);
                    # if there is one update it
                    if (isset($smf_group[0]) || $reslev["smf_group_mirror"]>0)
                    {
                        if($reslev["smf_group_mirror"]>0)
                        {
                            if($FORUMLINK=="smf")
                                $smf_group[0]['ID_GROUP']=$reslev["smf_group_mirror"];
                            else
                                $smf_group[0]['id_group']=$reslev["smf_group_mirror"];
                        }
                        $smfset[]=(($FORUMLINK=="smf")?'ID_GROUP='.$smf_group[0]['ID_GROUP']:'id_group='.$smf_group[0]['id_group']);
                    }
                    else $note.=' Group not found in SMF.';
                }
                elseif($FORUMLINK=="ipb")
                {
                    # find the coresponding level in ipb
                    if($reslev["ipb_group_mirror"]==0)
                    $ipb_group=get_result("SELECT `perm_id` FROM `{$ipb_prefix}forum_perms` WHERE `perm_name`='".$reslev["name"]."' LIMIT 1;", true, $CACHE_DURATION);
                    # if there is one update it
                    if (isset($ipb_group[0]) || $reslev["ipb_group_mirror"]>0)
                    {
                        if($reslev["ipb_group_mirror"]>0)
                            $ipb_group[0]["perm_id"]=$reslev["ipb_group_mirror"];
                        $ipblevel=$ipb_group[0]["perm_id"];
                        IPSMember::save($ipb_fid, array("members" => array("member_group_id" => "$ipblevel")));
                    }
                    else $note.=' Group not found in IPB.';
                }
                $set[]='id_level='.$level;
            }
            if ($time != $curu['time_offset'])
                $set[]='time_offset='.$time;
// Custom title - start
	if ($custom_title != $curu['custom_title'])
                $set[]='custom_title='.sqlesc(htmlspecialchars($custom_title));
// Custom title - end
            if ($email != $curu['email'])
            {
                $set[]='email='.sqlesc($email);
                if(substr($FORUMLINK,0,3)=="smf")
                {
                    $smfset[]="email".(($FORUMLINK=="smf")?"A":"_a")."ddress=".sqlesc($email);
                }
                elseif($FORUMLINK=="ipb")
                {
                    IPSMember::save($ipb_fid, array("members" => array("email" => "$email")));
                }
            }
            if ($avatar != $curu['avatar'])
                $set[]='avatar='.sqlesc(htmlspecialchars($avatar));
            if ($username != $curu['username']) {
                $new_username=$username;
                $sql_name=sqlesc($curu['username']);
                $username=sqlesc($username);
                $dupe=get_result("SELECT `id` FROM `{$TABLE_PREFIX}users` WHERE `username`=".$username." LIMIT 1", true, $CACHE_DURATION);
                if (!isset($dupe[0])) {
                    $set[]='username='.$username;
                    $newname=' ( now: '.$username;
                    if (substr($FORUMLINK,0,3)=='smf')
                    {
                        $dupe=get_result("SELECT ".(($FORUMLINK=="smf")?"`ID_MEMBER`":"`id_member`")." FROM `{$db_prefix}members` WHERE `member".(($FORUMLINK=="smf")?"N":"_n")."ame`=".$username." LIMIT 1", true, $CACHE_DURATION);
                        if (!isset($dupe[0])) {
                            $smfset[]='member'.(($FORUMLINK=="smf")?"N":"_n").'ame='.$username;
                        } else
                            $newname.=', dupe name in smf memberName';
                        $dupe=get_result("SELECT ".(($FORUMLINK=="smf")?"`ID_MEMBER`":"`id_member`")." FROM {$db_prefix}members WHERE `real".(($FORUMLINK=="smf")?"N":"_n")."ame`=".$username." LIMIT 1", true, $CACHE_DURATION);
                        if (!isset($dupe[0])) {
                            $smfset[]='real'.(($FORUMLINK=="smf")?"N":"_n").'ame='.$username;
                        } else
                            $newname.=', dupe name in smf realName';
                    }
                    elseif($FORUMLINK=='ipb')
                    {
                        $new_username=trim($username,"'");
                        $new_l_username=strtolower($new_username);
                        $new_seoname=IPSText::makeSeoTitle($new_username);
                        IPSMember::save($ipb_fid, array("members" => array("name" => "$new_username", "members_display_name" => "$new_username", "members_l_display_name" => "$new_l_username", "members_l_username" => "$new_l_username", "members_seo_name" => "$new_seoname")));
                    }
                    $newname.=' )';
                } else $note.=' Dupe name in XBTIT.';
            }
            if ($topicsperpage != $curu['topicsperpage']) 
                $set[]='topicsperpage='.$topicsperpage;
            if ($postsperpage != $curu['postsperpage'])
                $set[]='postsperpage='.$postsperpage;
            if ($torrentsperpage != $curu['torrentsperpage'])
                $set[]='torrentsperpage='.$torrentsperpage;
            if ($XBTT_USE){
                if ($downloaded != $curu['downloaded']) {
                    $xbtset[]='downloaded='.$downloaded;
                    $set[]='downloaded=0';
                }
                if ($uploaded != $curu['uploaded']) {
                    $xbtset[]='uploaded='.$uploaded;
                    $set[]='uploaded=0';
                }
            } else {
                if ($uploaded != $curu['uploaded'])
                    $set[]='uploaded='.$uploaded;
                if ($downloaded != $curu['downloaded'])
                    $set[]='downloaded='.$downloaded;
            }
            if ($chpass) {
                $pass_min_req=explode(",", $btit_settings["secsui_pass_min_req"]);
                
                if(strlen($pass)<$pass_min_req[0])
                    stderr($language["ERROR"],$language["ERR_PASS_LENGTH_1"]." <span style=\"color:blue;font-weight:bold;\">".$pass_min_req[0]."</span> ".$language["ERR_PASS_LENGTH_2"]);

                $lct_count=0;
                $uct_count=0;
                $num_count=0;
                $sym_count=0;
                $pass_end=(int)(strlen($pass)-1);
                $pass_position=0;
                $pattern1='#[a-z]#';
                $pattern2='#[A-Z]#';
                $pattern3='#[0-9]#';
                $pattern4='/[¬!"£$%^&*()`{}\[\]:@~;\'#<>?,.\/\\-=_+\|]/';

                for($pass_position=0;$pass_position<=$pass_end;$pass_position++)
                {
                    if(preg_match($pattern1,substr($pass,$pass_position,1),$matches))
                      $lct_count++;
                    elseif(preg_match($pattern2,substr($pass,$pass_position,1),$matches))
                      $uct_count++;
                    elseif(preg_match($pattern3,substr($pass,$pass_position,1),$matches))
                      $num_count++;
                    elseif(preg_match($pattern4,substr($pass,$pass_position,1),$matches))
                      $sym_count++;
                }
                $newpassword=pass_the_salt(30);
                if($lct_count<$pass_min_req[1] || $uct_count<$pass_min_req[2] || $num_count<$pass_min_req[3] || $sym_count<$pass_min_req[4])
                    stderr($language["ERROR"],$language["ERR_PASS_TOO_WEAK_1A"].":<br /><br />".(($pass_min_req[1]>0)?"<li><span style='color:blue;font-weight:bold;'>".$pass_min_req[1]."</span> ".(($pass_min_req[1]==1)?$language["ERR_PASS_TOO_WEAK_2"]:$language["ERR_PASS_TOO_WEAK_2A"])."</li>":"").(($pass_min_req[2]>0)?"<li><span style='color:blue;font-weight:bold;'>".$pass_min_req[2]."</span> ".(($pass_min_req[2]==1)?$language["ERR_PASS_TOO_WEAK_3"]:$language["ERR_PASS_TOO_WEAK_3A"])."</li>":"").(($pass_min_req[3]>0)?"<li><span style='color:blue;font-weight:bold;'>".$pass_min_req[3]."</span> ".(($pass_min_req[3]==1)?$language["ERR_PASS_TOO_WEAK_4"]:$language["ERR_PASS_TOO_WEAK_4A"])."</li>":"").(($pass_min_req[4]>0)?"<li><span style='color:blue;font-weight:bold;'>".$pass_min_req[4]."</span> ".(($pass_min_req[4]==1)?$language["ERR_PASS_TOO_WEAK_5"]:$language["ERR_PASS_TOO_WEAK_5A"])."</li>":"")."<br />".$language["ERR_PASS_TOO_WEAK_6"].":<br /><br /><span style='color:blue;font-weight:bold;'>".$newpassword."</span><br />");

                $un=((!empty($new_username) && $new_username!=$curu["username"])?$new_username:$curu["username"]);
                $multipass=hash_generate(array("salt" => ""), $pass, $un);
                $j=$btit_settings["secsui_pass_type"];
                $set[]="`password`=".sqlesc($multipass[$j]["rehash"]);
                $set[]="`salt`=".sqlesc($multipass[$j]["salt"]);
                $set[]="`pass_type`=".sqlesc($j);
                $set[]="`dupe_hash`=".sqlesc($multipass[$j]["dupehash"]);
                $passhash=smf_passgen($un, $pass);
                $smfset[]='`passwd`='.sqlesc($passhash[0]);
                $smfset[]='`password'.(($FORUMLINK=="smf")?"S":"_s").'alt`='.sqlesc($passhash[1]);
                if($FORUMLINK=="ipb")
                {
                    $ipbhash=ipb_passgen($pass);
                    IPSMember::save($ipb_fid, array("members" => array("member_login_key" => "", "member_login_key_expire" => "0", "members_pass_hash" => "$ipbhash[0]", "members_pass_salt" => "$ipbhash[1]")));
                }
            }

            $updateset=(isset($set))?implode(',',$set):'';
            $updatesetxbt=(isset($xbtset))?implode(',',$xbtset):'';
            $updatesetsmf=(isset($smfset))?implode(',',$smfset):'';
            if ($updateset!='') {
                if ($XBTT_USE && $updatesetxbt!='')
                    quickQuery('UPDATE xbt_users SET '.$updatesetxbt.' WHERE uid='.$uid.' LIMIT 1;');
                if ((substr($FORUMLINK,0,3)=='smf') && ($updatesetsmf!='') && (!is_bool($smf_fid)))
                    quickQuery("UPDATE `{$db_prefix}members` SET ".$updatesetsmf." WHERE ".(($FORUMLINK=="smf")?"`ID_MEMBER`":"`id_member`")."=".$smf_fid." LIMIT 1");
                quickQuery('UPDATE '.$TABLE_PREFIX.'users SET '.$updateset.' WHERE id='.$uid.' LIMIT 1;');

                success_msg($language['SUCCESS'], $language['INF_CHANGED'].$note.'<br /><a href="index.php?page=admin&amp;user='.$CURUSER['uid'].'&amp;code='.$CURUSER['random'].'">'.$language['MNU_ADMINCP'].'</a>');
                write_log('Modified user <a href="'.$btit_settings['url'].'/index.php?page=userdetails&amp;id='.$uid.'">'.$curu['username'].'</a> '.$newname.' ( '.count($set).' changes on uid '.$uid.' )','modified');
                stdfoot(true,false);
                die();
            } else stderr($language['ERROR'],$language['USER_NO_CHANGE']);
        }
        redirect('index.php?page=admin&user='.$CURUSER['uid'].'&code='.$CURUSER['random']);
        break;
}

# set template info
$admintpl->set('profile',$profile);
$admintpl->set('language',$language);
$admintpl->set('edit_user',$edit,true);
?>
