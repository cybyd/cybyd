<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <title><tag:main_title /></title>
  <meta http-equiv="content-type" content="text/html; charset=<tag:main_charset />" />
  <link rel="stylesheet" href="<tag:main_css />" type="text/css" />
  <tag:more_css />
  <tag:main_jscript />
</head>
<body>
<div id="main">
<if:IS_DISPLAYED_1>
  <div id="top" style="padding: 10px auto;">
    <table class="windowbg6" align="center" width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td align="middle"><center><img src="style/holiday-spirit/images/northstar.gif" style="margin: 0px;" alt="" /></center></td>
      </tr>
			<tr>
        <td align="middle" valign="bottom"><center><img src="style/holiday-spirit/images/img110.gif" style="margin: 0px;" alt="" /></center></td>
			</tr>
      <tr>
			  <td width="100%" height="8" style="background: url(images/spacer.gif); background-repeat: repeat-x; background-color:#FFF;"></td>
      </tr>
    </table>
  </div>

	<TABLE align="center" cellpadding="0" cellspacing="0" border="0">
      <TR>
        <TD valign="top">  
	<div id="dropdown">
      <tag:main_dropdown />
   </div></TD>
       </TR>
    </TABLE>
	<TABLE align="center" width="982" cellpadding="0" cellspacing="0" border="0">
      <TR>
        <TD valign="top">
  <div id="slideIt">
    <tag:main_slideIt />
    <div id="header">
      <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td valign="top" width="5" rowspan="2"></td>
          <td valign="top"><tag:main_header /></td>
          <td valign="top" width="5" rowspan="2"></td>
        </tr>
      </table>
    </div>
  </div>
  <script type="text/javascript">
    var collapse2=new animatedcollapse("header", 800, false, "block")
  </script>
  <div id="bodyarea" style="padding-top:2em;">  
    <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td valign="top" width="5" rowspan="2"></td>
        <if:HAS_LEFT_COL>
        <td valign="top" width="150"><tag:main_left /></td>
        <td valign="top" width="5" rowspan="2"></td>
        </if:HAS_LEFT_COL>
        <td valign="top"><tag:main_content /></td>
        <if:HAS_RIGHT_COL>
        <td valign="top" width="5" rowspan="2"></td>
        <td valign="top" width="150"><tag:main_right /></td>
        </if:HAS_RIGHT_COL>
        <td valign="top" width="5" rowspan="2"></td>
      </tr>
    </table>
    <br />      
    <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td valign="top" width="5" rowspan="2"></td>
        <td valign="top"><tag:main_footer /></td>
        <td valign="top" width="5" rowspan="2"></td>
      </tr>
    </table>
		<br />
  </div>
  <table width="100%" cellspacing="0" cellpadding="0" colspan="3" align="center">
    <tr>
		  <td class="header" align="center" valign="top"></td>
    </tr>
    <tr>
      <td class="lights" align="center" valign="top" bgcolor="#E1E5D5" height="24"></td>
		</tr>
		<tr>
      <td align="center" style="background:#E1E5D5;text-align:center;"><span valign="bottom" class="nav"><tag:style_copyright />&nbsp;<tag:xbtit_version /><br /><tag:xbtit_debug /><br />Holiday Spirit &copy; 2006-2010<br /><tag:to_top /></span></td>
    </tr>
		<tr>
      <td class="end" align="center" valign="top"></td>
    </tr>
  </table>
	</TD>
      </TR>
    </TABLE>
<else:IS_DISPLAYED_1>
  <div id="bodyarea" style="padding: 1ex 0ex 0ex 0ex;">  
    <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td valign="top" width="5" rowspan="2"></td>
        <td valign="top"><tag:main_content /></td>
        <td valign="top" width="5" rowspan="2"></td>	
      </tr>
    </table>
  </div>
</if:IS_DISPLAYED_1>
</div>
</body>
</html>