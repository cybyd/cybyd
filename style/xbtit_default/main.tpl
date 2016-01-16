<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html<tag:main_rtl /> xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><tag:main_title /></title>
  <meta http-equiv="content-type" content="text/html; charset=<tag:main_charset />" />
  <meta name="author" content="cybernet2u" />
  <meta name="revised" content="cybyd, 1.1" />
  <link rel="stylesheet" href="<tag:main_css />" type="text/css" />
  <tag:more_css />
  <tag:main_jscript />

<if:IS_DISPLAYED_1>
<!--[if lte IE 7]>
<style type="text/css">
#menu ul {display:inline;}
</style>
<![endif]-->
</if:IS_DISPLAYED_1>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-XXXXXXXX-1', 'cybyd.io');
  ga('require', 'displayfeatures');
  ga('send', 'pageview');
</script>
</head>
<body>
<div id="main">
<if:IS_DISPLAYED_2>
  <div id="logo">
    <table width="792" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="tracker_logo" align="center" valign="top"></td>
      </tr>
    </table></div>

    <TABLE align="center" width="700" cellpadding="0" cellspacing="0" border="0">
      <TR>
        <TD valign="top">  
    <div id="dropdown">
      <tag:main_dropdown />
   </div></TD>
       </TR>
    </TABLE>
    <TABLE align="center" width="100%" height="10" cellpadding="0" cellspacing="0" border="0">
      <TR>
        <TD valign="top" background="style/xbtit_default/images/spacer.gif"></TD>
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
  <div id="bodyarea" style="padding:4ex 0 0 0;">  
    <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td valign="top" width="5" rowspan="2"></td>
        <if:HAS_LEFT_COL>
        <td valign="top" id="col" width="150"><tag:main_left /></td>
        <td valign="top" width="5" rowspan="2"></td>
        </if:HAS_LEFT_COL>
        <td id="mcol" valign="top"><tag:main_content /></td>
        <if:HAS_RIGHT_COL>
        <td valign="top" width="5" rowspan="2"></td>
        <td valign="top" id="col" width="150"><tag:main_right /></td>
        </if:HAS_RIGHT_COL>
        <td valign="top" width="5" rowspan="2"></td>
      </tr>
    </table>
    <br />      
    <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td valign="top" width="5" rowspan="2"></td>
        <td id="mcol" valign="top"><tag:main_footer /></td>
        <td valign="top" width="5" rowspan="2"></td>
      </tr>
    </table>
        <br />
  </div>
    </TD>
      </TR>
    </TABLE>
  <div id="footer">
       <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
         <tr>
  <td align="center" valign="bottom"><br /><br /><br /><br /><br /><tag:style_copyright />&nbsp;<tag:xbtit_version /><br />
         <tag:xbtit_debug /></td>
        </tr><tr>
                <td class="footer" align="center" valign="bottom"><br /><br /><br /><tag:to_top /></td>
         </table>
      </div>
<else:IS_DISPLAYED_2>
    <div id="bodyarea" style="padding: 1ex 0ex 0ex 0ex;">  
<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td valign="top"><tag:main_content /></td>
  </tr>
    </table>
      </div>
</if:IS_DISPLAYED_2>
</div>
</body>
</html>
