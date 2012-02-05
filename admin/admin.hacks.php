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
$admintpl->set("test_ok2",false,true);
$admintpl->set("manual_install",true,true);
$admintpl->set("ftp",false,true);

switch ($action)
 {
    case 'manual':
        $admintpl->set("language",$language);
        $admintpl->set("manual_install",false,true);
        $admintpl->set("test",false,true);
        require_once($THIS_BASEPATH."/include/class.update_hacks.php");    
        $filename=urldecode(base64_decode($_GET["folder"]))."/modification.xml";
        $fd=fopen($filename, "r");
        $xml=fread($fd,filesize($filename));
        fclose($fd);
        $get=new update_hacks();
        $result=$get->hack_to_array($xml);
        $admintpl->set("title",str_replace(array("'", "\""), array("",""), $result[0]["title"]));
        $admintpl->set("version",str_replace(array("'", "\""), array("",""), $result[0]["version"]));
        $admintpl->set("author",str_replace(array("'", "\""), array("",""), $result[0]["author"]));
        $HTMLOUT="\n\n";

        foreach($result[0]["file"] as $k => $v)
        {
            $name=str_replace(array("'","\""), array("", ""),$v["name"]);
            if($name=="database")
            {
                foreach($v["operations"] as $key => $value)
                {
                    $action=str_replace(array("'","\""), array("", ""),$value["action"]);
                    if($action=="sql")
                    {
                        $lines=count(explode("\n",$value["data"]));
                        $HTMLOUT.="<br /><span style='font-family:arial; font-size:12pt; color:#000000;'>".$language["MHI_RUN_QUERY"].":</span><br />\n";
                        $HTMLOUT.="<textarea rows='$lines' cols='98'>".str_replace( array("{\$db_prefix}","<",">"),array($TABLE_PREFIX,"&lt;","&gt;"),$value["data"])."</textarea><br />\n";
                    }
                }
                $HTMLOUT.="<br />";
            }
            else
            {
                $firstpass=0;
                foreach($v["operations"] as $key => $value)
                {
                    $action=str_replace(array("'","\""), array("", ""),$value["action"]);
                    $where=str_replace(array("\$DEFAULT_ROOT","'", "\$DEFAULT_LANGUAGE_PATH", "\$DEFAULT_STYLE_PATH", "\$CURRENT_FOLDER"), (array("","","","")),       str_replace(array("\$DEFAULT_ROOT/","'", "\"", "\$DEFAULT_LANGUAGE_PATH/", "\$DEFAULT_STYLE_PATH/", "\$CURRENT_FOLDER/"), array("","","","language/english/", "style/xbtit_default/", ""), $value["where"]));
                    $name=str_replace(array("\$DEFAULT_ROOT","'", "\$DEFAULT_LANGUAGE_PATH", "\$DEFAULT_STYLE_PATH", "\$CURRENT_FOLDER"), (array("","","","")),       str_replace(array("\$DEFAULT_ROOT/","'", "\"", "\$DEFAULT_LANGUAGE_PATH/", "\$DEFAULT_STYLE_PATH/", "\$CURRENT_FOLDER/"), array("","","","language/english/", "style/xbtit_default/", ""), $v["name"]));
                    $data=$value["data"];
                    $lines=count(explode("\n", $value["search"]));
                    $lines2=count(explode("\n", $value["data"]));
                    if($action=="add" || $action=="replace")
                    {
                        $HTMLOUT.="\n<span style='font-family:arial; font-size:14pt; color:#000000;'>".(($firstpass==0)?$language["MHI_IN"]:$language["MHI_ALSO_IN"])." <span style='color:#0000FF'>".$name . "</span> ".$language["MHI_FIND_THIS"].":</span>";
                        $HTMLOUT.="\n<br /><textarea rows='$lines' cols='98'>".str_replace(array("<",">"),array("&lt;","&gt;"),$value["search"])."</textarea><br /><br />";
                        if($action=="add")
                            $HTMLOUT.="\n<span style='font-family:arial; font-size:14pt; color:#000000;'>".$language["MHI_ADD_THIS"]." " . $where . " ".$language["MHI_IT"].":</span>";
                        elseif($action=="replace")
                            $HTMLOUT.="\n<span style='font-family:arial; font-size:14pt; color:#000000;'>".$language["MHI_REPLACE"].":</span>";
                        $HTMLOUT.="\n<br /><textarea rows='$lines2' cols='98'>".str_replace(array("<",">"),array("&lt;","&gt;"),$data)."</textarea><br /><br />";
                        $firstpass=1;
                    }
                    elseif($action=="copy")
                    {
                        $where=str_replace(array("'","\"","\$DEFAULT_ROOT/", "\$DEFAULT_LANGUAGE_PATH", "\$DEFAULT_STYLE_PATH", "\$CURRENT_FOLDER/"), array("","","","language/english", "style/xbtit_default",""), $value["where"]);
                        $data=str_replace(array("'","\""), array("",""), $value["data"]);
                        $HTMLOUT.="\n<span style='font-family:arial; font-size:14pt; color:#000000;'>".$language["MHI_COPY"]." <span style='font-family:arial; font-size:14pt; color:#0000FF;'>$name</span> ".$language["MHI_AS"]." <span style='font-family:arial; font-size:14pt; color:#0000FF;'> ". $where . (($where!="")?"/":"")."$data</span></span><br />";
                    }
                }
            }
        } 
        $admintpl->set("HTMLOUT",$HTMLOUT);

    break;



    case 'uninstall_ok':

        if (isset($_POST["confirm"]) && $_POST["confirm"]!=$language["HACK_UNINSTALL"])
          {
          redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=hacks&action=read");
          die();
        }


        if (isset($_GET["id"]))
            $hack_id=intval($_GET["id"]);
        else
            $hack_id=0;

        $ui_hack=get_result("SELECT folder FROM {$TABLE_PREFIX}hacks WHERE id=$hack_id",true);

        if (count($ui_hack)>0)
          {

            include("$THIS_BASEPATH/include/class.update_hacks.php");

            $hack_folder=unesc($ui_hack[0]["folder"]);

            // used to define the current path (hack path)
            $CURRENT_FOLDER="$THIS_BASEPATH/hacks/$hack_folder";

            // create object
            $newhack=new update_hacks();

            // we open the work definition file
            $hstring=$newhack->open_hack("$THIS_BASEPATH/hacks/$hack_folder/modification.xml");

            // all structure is now in an array
            $new_hack_array=$newhack->hack_to_array($hstring);

            // we will install the hack or we can just test if installation will run fine.
            if ($newhack->uninstall_hack($new_hack_array,true))
              {
               if ($newhack->uninstall_hack($new_hack_array))
                 {
                  do_sqlquery("DELETE FROM {$TABLE_PREFIX}hacks WHERE id=$hack_id",true);
                  success_msg($language["SUCCESS"],$language["HACK_UNINSTALLED_OK"]);
                  stdfoot(true,false);
                  die;
               }
            }
            else
              {
                 stderr($language["ERROR"],join("<br />\n",$newhack->errors));
            }
        }
        else
          stderr($language["ERROR"],$language["HACK_BAD_ID"]);

      break;

    case 'uninstall':

        if (isset($_GET["id"]))
            $hack_id=intval($_GET["id"]);
        else
            $hack_id=0;

        $ui_hack=get_result("SELECT folder FROM {$TABLE_PREFIX}hacks WHERE id=$hack_id",true);

        if (count($ui_hack)>0)
          {

            include("$THIS_BASEPATH/include/class.update_hacks.php");

            $hack_folder=unesc($ui_hack[0]["folder"]);

            // used to define the current path (hack path)
            $CURRENT_FOLDER="$THIS_BASEPATH/hacks/$hack_folder";

            // create object
            $newhack=new update_hacks();

            // we open the work definition file
            $hstring=$newhack->open_hack("$THIS_BASEPATH/hacks/$hack_folder/modification.xml");

            // all structure is now in an array
            $new_hack_array=$newhack->hack_to_array($hstring);

            // we will install the hack or we can just test if installation will run fine.
            if ($newhack->uninstall_hack($new_hack_array,true))
              {
                $admintpl->set("test_result",$newhack->file);
                $admintpl->set("test",true,true);
                $admintpl->set("test_ok",true,true);
            }
            else
              {
                $admintpl->set("test_result",$newhack->errors);
                $admintpl->set("test",true,true);
                $admintpl->set("test_ok",false,true);
            }
            $admintpl->set("language",$language);
            $admintpl->set("hack_folder",$hack_folder);
            $admintpl->set("hack_install",$language["HACK_UNINSTALL"]);
            $admintpl->set("hack_main_link","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=read");
            $admintpl->set("form_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=uninstall_ok&amp;id=$hack_id");
            $admintpl->set("hack_title_action","<b>".$language["HACK_UNINSTALL"].":&nbsp;".$new_hack_array[0]["title"]."</b>");

        }
        else
          stderr($language["ERROR"],$language["HACK_BAD_ID"]);


      break;

    case 'ftp_session':
        if (isset($_POST["add_hack_folder"]))
            $hack_folder=$_POST["add_hack_folder"];

        if (isset($_POST["confirm"]) && $_POST["confirm"]==$language["FRM_CONFIRM"])
          {
           $ftp_data=array();
           $ftp_data["server"]=$_POST["ftp_server"];
           $ftp_data["port"]=$_POST["ftp_port"];
           $ftp_data["username"]=$_POST["ftp_user"];
           $ftp_data["pass"]=$_POST["ftp_pwd"];
           $ftp_data["basedir"]=$_POST["ftp_basedir"];

           $_SESSION["ftp_data"]=$ftp_data;

           unset($ftp_data);
           redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=hacks&action=test&add_hack_folder=".urlencode($hack_folder));
        }
        else
           redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=hacks&action=ftp&hack=".urlencode($hack_folder));

        die();

      break;

    case 'ftp':
        if (isset($_GET["hack"]))
            $hack_folder=urldecode($_GET["hack"]);
         $admintpl->set("language",$language);
         $admintpl->set("hack_folder",$hack_folder);
         $admintpl->set("form_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=ftp_session");
         $admintpl->set("hack_title_action","<b>".$language["HACK_INSTALL"].":&nbsp;FTP Data</b>");
         $admintpl->set("ftp",true,true);
      break;

    case 'install':

        if (isset($_POST["confirm"]) && $_POST["confirm"]!=$language["HACK_INSTALL"])
          {
          redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=hacks&action=read");
          die();
        }

        include("$THIS_BASEPATH/include/class.update_hacks.php");

        if (isset($_POST["add_hack_folder"]))
            $hack_folder=$_POST["add_hack_folder"];
        elseif (isset($_GET["add_hack_folder"]))
            $hack_folder=urldecode($_GET["add_hack_folder"]);


        // used to define the current path (hack path)
        $CURRENT_FOLDER="$THIS_BASEPATH/hacks/$hack_folder";

        // create object
        $newhack=new update_hacks();

        // we open the work definition file
        $hstring=$newhack->open_hack("$THIS_BASEPATH/hacks/$hack_folder/modification.xml");

        // all structure is now in an array
        $new_hack_array=$newhack->hack_to_array($hstring);

        // we will test again, then if ok, we install the hack
        if ($newhack->install_hack($new_hack_array,true))
          {

               if ($newhack->install_hack($new_hack_array))
                 {
                  do_sqlquery("INSERT INTO {$TABLE_PREFIX}hacks SET ".
                    sprintf("title=%s,version=%s,author=%s,added=UNIX_TIMESTAMP(),folder=%s",
                            sqlesc($new_hack_array[0]["title"]),
                            sqlesc($new_hack_array[0]["version"]),
                            sqlesc($new_hack_array[0]["author"]),
                            sqlesc($hack_folder)),true);
                  success_msg($language["SUCCESS"],$language["HACK_INSTALLED_OK"]);
                  stdfoot(true,false);
                  die;

               }
        }
        else
          {
             stderr($language["ERROR"],join("<br />\n",$newhack->errors));
        }

      break;


    case 'test':

        include("$THIS_BASEPATH/include/class.update_hacks.php");

        if (isset($_POST["add_hack_folder"]))
            $hack_folder=$_POST["add_hack_folder"];
        elseif (isset($_GET["add_hack_folder"]))
            $hack_folder=urldecode($_GET["add_hack_folder"]);


        // used to define the current path (hack path)
        $CURRENT_FOLDER="$THIS_BASEPATH/hacks/$hack_folder";

        // create object
        $newhack=new update_hacks();

        // we open the work definition file
        $hstring=$newhack->open_hack("$THIS_BASEPATH/hacks/$hack_folder/modification.xml");

        // all structure is now in an array
        $new_hack_array=$newhack->hack_to_array($hstring);

        // we will install the hack or we can just test if installation will run fine.
        if ($newhack->install_hack($new_hack_array,true))
          {
            $admintpl->set("test_result",$newhack->file);
            $admintpl->set("test",true,true);
            $admintpl->set("test_ok",true,true);
            $admintpl->set("test_ok2",false,true);
        }
        else
          {
            $admintpl->set("test_result",$newhack->errors);
            $admintpl->set("test",true,true);
            $admintpl->set("test_ok",false,true);
            $admintpl->set("test_ok2",true,true);
            $admintpl->set("hack_manual_link", "index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=manual&folder=".urlencode(base64_encode(str_replace("\\", "/", $CURRENT_FOLDER))));
        }
        $admintpl->set("language",$language);
        $admintpl->set("hack_folder",$hack_folder);
        $admintpl->set("hack_install",$language["HACK_INSTALL"]);
        $admintpl->set("hack_main_link","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=read");
        $admintpl->set("form_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=install");
        $admintpl->set("hack_title_action","<b>".$language["HACK_INSTALL"].":&nbsp;".$new_hack_array[0]["title"]."</b>");

      break;

    case 'read':
    default:
        $admintpl->set("language",$language);
        $hacks = get_result("SELECT * FROM {$TABLE_PREFIX}hacks ORDER BY id",true);
        $installed=array();
        $i=0;
        //die(print_r($hacks));
        foreach($hacks as $id=>$hack)
          {
            $installed[]=unesc($hack["folder"]);
            $hacks[$i]["title"]=unesc($hack["title"]);
            $hacks[$i]["author"]=unesc($hack["author"]);
            $hacks[$i]["version"]=unesc($hack["version"]);
            $hacks[$i]["added"]=date("d M Y",$hack["added"]);
            $hacks[$i]["uninstall"]="index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=uninstall&amp;id=".$hacks[$i]["id"]; // link only
            $i++;
        }
        // drop down
        $dir=opendir("$THIS_BASEPATH/hacks");
        $combo="\n<select name=\"add_hack_folder\" size=\"1\" onchange=\"valid_folder(this.options[selectedIndex].value)\">\n<option value=\"\">".$language["SELECT"]."</option>";
        while($file = @readdir($dir))
          {
          if (is_dir("$THIS_BASEPATH/hacks/$file") && $file!="." && $file!=".." && file_exists("$THIS_BASEPATH/hacks/$file/modification.xml"))
             if (!in_array($file,$installed))
               $combo.="\n<option value=\"$file\">$file</option>";
        }
        @closedir($dir);
        unset($installed);
        $combo.="\n</select>";

        $admintpl->set("form_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=test");
        $admintpl->set("hack_combo",$combo);
        $admintpl->set("no_hacks",count($hacks)==0,true);
        $admintpl->set("hacks",$hacks);
        $admintpl->set("test",false,true);
                
      break;
}


?>