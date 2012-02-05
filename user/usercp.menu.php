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


$usercp_menu=array(
0=>array(
        "title"=>$language["MNU_UCP_HOME"],
        "menu"=>array(0=>array(
                "url"=>"index.php?page=usercp&amp;uid=".$uid."" ,
                "description"=>$language["MNU_UCP_HOME"]))
        ),
1=>array(
        "title"=>$language["MNU_UCP_PM"],
        "menu"=>array(0=>array(
                "url"=>"index.php?page=usercp&amp;uid=".$uid."&amp;do=pm&amp;action=list&amp;what=inbox" ,
                "description"=>$language["MNU_UCP_PM"]),
                      1=>array(
                "url"=>"index.php?page=usercp&amp;uid=".$uid."&amp;do=pm&amp;action=list&amp;what=outbox" ,
                "description"=>$language["MNU_UCP_OUT"]),

                      2=>array(
                "url"=>"index.php?page=usercp&amp;do=pm&amp;action=edit&amp;uid=".$uid."&amp;what=new" ,
                "description"=>$language["MNU_UCP_NEWPM"]), 
                             )),
2=>array(
        "title"=>$language["MNU_UCP_INFO"],
        "menu"=>array(0=>array(
                "url"=>"index.php?page=usercp&amp;do=user&amp;action=change&amp;uid=".$uid."" ,
                "description"=>$language["MNU_UCP_INFO"]),
                      1=>array(
                "url"=>"index.php?page=usercp&amp;do=pwd&amp;action=change&amp;uid=".$uid."" ,
                "description"=>$language["MNU_UCP_CHANGEPWD"]),
                      2=>array(
                "url"=>"index.php?page=usercp&amp;do=pid_c&amp;action=change&amp;uid=".$uid."" ,
                "description"=>$language["CHANGE_PID"]), 
                             )),
);
?>