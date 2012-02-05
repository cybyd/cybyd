<script type="text/javascript">
var newwindow;

function popusers(url)
{
  newwindow=window.open(url,'popusers','height=100,width=450');
  if (window.focus) {newwindow.focus()}
}
</script>
<table border="0" width="100%" class="lista">
  <tr>
    <td align="center" class="lista" colspan="3"><br /><tag:language.UCP_NOTE_1 /><br /><tag:language.UCP_NOTE_2 /><br /><br /></td>
  </tr>
  <tr>
    <td width="20%" class="header" align="left"><tag:language.USER_NAME />:</td>
    <td width="80%" class="lista" align="left"><tag:ucp.username /></td>
  <if:AVATAR>
    <td class="lista" align="center" valign="middle" rowspan="4"><tag:ucp.avatar /></td>
  </if:AVATAR>
  </tr>
<if:CAN_EDIT>
  <tr>
    <td class="header" align="left"><tag:language.EMAIL />:</td>
    <td class="lista" align="left"><tag:ucp.email /></td>
  </tr>
  <tr>
    <td class="header" align="left"><tag:language.LAST_IP />:</td>
    <td class="lista" align="left"><tag:ucp.lastip /></td>
  </tr>
</if:CAN_EDIT>
  <tr>
    <td class="header" align="left"><tag:language.USER_LEVEL />:</td>
    <td class="lista" align="left"><tag:ucp.userlevel /></td>
  </tr>

  <tr>
    <td class="header" align="left"><tag:language.USER_JOINED />:</td>
    <td class="lista" colspan="2" align="left"><tag:ucp.userjoin /></td>
  </tr>
  <tr>
    <td class="header" align="left"><tag:language.USER_LASTACCESS />:</td>
    <td class="lista" colspan="2" align="left"><tag:ucp.lastaccess /></td>
  </tr>
  <tr>
    <td class="header" align="left"><tag:language.PEER_COUNTRY />:</td>
    <td class="lista" colspan="2" align="left"><tag:ucp.country /></td>
  </tr>
  <tr>
    <td class="header" align="left"><tag:language.DOWNLOADED />:</td>
    <td class="lista" colspan="2" align="left"><tag:ucp.download /></td>
  </tr>
  <tr>
    <td class="header" align="left"><tag:language.UPLOADED />:</td>
    <td class="lista" colspan="2" align="left"><tag:ucp.upload /></td>
  </tr>
  <tr>
    <td class="header" align="left"><tag:language.RATIO />:</td>
    <td class="lista" colspan="2" align="left"><tag:ucp.ratio /></td>
  </tr>

<if:INTERNAL_FORUM>
  <tr>
    <td class="header" align="left"><tag:language.FORUM /> <tag:language.POSTS />:</td>
    <td class="lista" colspan="2" align="left"><tag:posts /></td>
  </tr>
</if:INTERNAL_FORUM>
</table>
<tag:pagertop />
<table width="100%" class="lista">
  <tr>
    <td class="block" align="center" colspan="8"><b><tag:language.UPLOADED /> <tag:language.MNU_TORRENT /></b></td>
  </tr>
  <tr>
    <td align="center" class="header"><tag:language.FILE /></td>
    <td align="center" class="header"><tag:language.ADDED /></td>
    <td align="center" class="header"><tag:language.SIZE /></td>
    <td align="center" class="header"><tag:language.SHORT_S /></td>
    <td align="center" class="header"><tag:language.SHORT_L /></td>
    <td align="center" class="header"><tag:language.SHORT_C /></td>
    <td align="center" class="header"><tag:language.EDIT /></td>
    <td align="center" class="header"><tag:language.DELETE /></td>
  </tr>
<if:RESULTS>
<loop:uptor>
  <tr>
    <td class="lista" style="padding-left:10px;"><tag:uptor[].filename /></td>
    <td class="lista" align="center" style="text-align: center;"><tag:uptor[].added /></td>
    <td class="lista" align="center" style="text-align: center;"><tag:uptor[].size /></td>
    <td class="<tag:uptor[].seedcolor />" align="center" style="text-align: center;"><tag:uptor[].seeds /></td>
    <td class="<tag:uptor[].leechcolor />" align="center" style="text-align: center;"><tag:uptor[].leechers /></td>
    <td class="lista" align="center" style="text-align: center;"><tag:uptor[].completed /></td>
    <td class="lista" align="center" style="text-align: center;"><a href="<tag:uptor[].editlink />"><tag:uptor[].editimg /></a></td>
    <td class="lista" align="center" style="text-align: center;"><a href="<tag:uptor[].dellink />"><tag:uptor[].delimg /></a></td>
  </tr>
</loop:uptor>
<else:RESULTS>
  <tr>
    <td class="lista" align="center" colspan="8"><tag:language.NO_TORR_UP_USER /></td>
  </tr>
</if:RESULTS>
</table>