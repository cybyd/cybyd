<table class="lista" width="100%">

  <tr>

    <td width="20%" class="header"><tag:language.USERNAME /></td>

    <td width="80%" class="lista"><tag:userdetail_username /><tag:userdetail_send_pm /><tag:userdetail_edit /><tag:userdetail_delete /></td>

    <if:userdetail_has_avatar>

      <td class="lista" align="center" valign="middle" rowspan="4"><tag:userdetail_avatar /></td>

    <else:userdetail_has_avatar>

    </if:userdetail_has_avatar>

  </tr>

  <if:userdetail_edit_admin>

  <tr>

    <td class="header"><tag:language.EMAIL /></td>

    <td class="lista"><tag:userdetail_email /></td>

  </tr>

  <tr>

    <td class="header"><tag:language.LAST_IP /></td>

    <td class="lista"><tag:userdetail_last_ip /></td>

  </tr>

  <tr>

    <td class="header"><tag:language.USER_LEVEL /></td>

    <td class="lista"><tag:userdetail_level_admin /></td>

  </tr>

  <else:userdetail_edit_admin>

  <tr>

    <td class="header"><tag:language.USER_LEVEL /></td>

    <td class="lista"><tag:userdetail_level /></td>

  </tr>

  </if:userdetail_edit_admin>

  <tr>

    <td class="header"><tag:language.USER_JOINED /></td>

    <td class="lista" colspan="<tag:userdetail_colspan />"><tag:userdetail_joined /></td>

  </tr>

  <tr>

    <td class="header"><tag:language.USER_LASTACCESS /></td>

    <td class="lista" colspan="<tag:userdetail_colspan />"><tag:userdetail_lastaccess /></td>

  </tr>

  <tr>

    <td class="header"><tag:language.PEER_COUNTRY /></td>

    <td class="lista" colspan="2"><tag:userdetail_country /></td>

  </tr>

  <tr>

    <td class="header"><tag:language.USER_LOCAL_TIME /></td>

    <td class="lista" colspan="2"><tag:userdetail_local_time /></td>

  </tr>

  <tr>

    <td class="header"><tag:language.DOWNLOADED /></td>

    <td class="lista" colspan="2"><tag:userdetail_downloaded /></td>

  </tr>

  <tr>

    <td class="header"><tag:language.UPLOADED /></td>

    <td class="lista" colspan="2"><tag:userdetail_uploaded /></td>

  </tr>

  <tr>

    <td class="header"><tag:language.RATIO /></td>

    <td class="lista" colspan="2"><tag:userdetail_ratio /></td>

  </tr>

  <if:userdetail_forum_internal>

  <tr>

    <td class="header"><b><tag:language.FORUM /></b>&nbsp;<b><tag:language.POSTS /></b></td>

    <td class="lista" colspan="2"><tag:userdetail_forum_posts /></td>

  </tr>

  <else:userdetail_forum_internal>

  </if:userdetail_forum_internal>

</table>

<br />
<tag:pagertop />
<table width="100%" class="lista">

  <tr>

    <td class="block" align="center" colspan="6"><b><tag:language.UPLOADED /> <tag:language.TORRENTS /></b></td>

  </tr>

  <tr>

    <td align="center" class="header"><tag:language.FILE /></td>

    <td align="center" class="header"><tag:language.ADDED /></td>

    <td align="center" class="header"><tag:language.SIZE /></td>

    <td align="center" class="header"><tag:language.SHORT_S /></td>

    <td align="center" class="header"><tag:language.SHORT_L /></td>

    <td align="center" class="header"><tag:language.SHORT_C /></td>

  </tr>

  <if:RESULTS>

  <loop:uptor>

  <tr>

    <td class="lista" align="center" style="padding-left:10px;"><tag:uptor[].filename /></td>

    <td class="lista" align="center" style="text-align: center;"><tag:uptor[].added /></td>

    <td class="lista" align="center" style="text-align: center;"><tag:uptor[].size /></td>

    <td class="<tag:uptor[].seedcolor />" align="center" style="text-align: center;"><tag:uptor[].seeds /></td>

    <td class="<tag:uptor[].leechcolor />" align="center" style="text-align: center;"><tag:uptor[].leechs /></td>

    <td class="lista" align="center" style="text-align: center;"><tag:uptor[].completed /></td>

  </tr>

  </loop:uptor>

  <else:RESULTS>

  <tr>

    <td class="lista" align="center" colspan="6"><tag:language.NO_TORR_UP_USER /></td>

  </tr>

  </if:RESULTS>

</table>

<br />
<tag:pagertopact />
<table width="100%" class="lista">

  <tr>

    <td class="block" align="center" colspan="9"><b><tag:language.ACTIVE_TORRENT /></b></td>

  </tr>

  <tr>

    <td align="center" class="header"><tag:language.FILE /></td>

    <td align="center" class="header"><tag:language.SIZE /></td>

    <td align="center" class="header"><tag:language.PEER_STATUS /></td>

    <td align="center" class="header"><tag:language.DOWNLOADED /></td>

    <td align="center" class="header"><tag:language.UPLOADED /></td>

    <td align="center" class="header"><tag:language.RATIO /></td>

    <td align="center" class="header"><tag:language.SHORT_S /></td>

    <td align="center" class="header"><tag:language.SHORT_L /></td>

    <td align="center" class="header"><tag:language.SHORT_C /></td>

  </tr>

  <if:RESULTS_1>

  <loop:tortpl>

  <tr>

    <td align="center" class="lista" style="padding-left:10px;"><tag:tortpl[].filename /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:tortpl[].size /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:tortpl[].status /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:tortpl[].downloaded /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:tortpl[].uploaded /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:tortpl[].peerratio /></td>

    <td align="center" class="<tag:tortpl[].seedscolor />" style="text-align: center;"><tag:tortpl[].seeds /></td>

    <td align="center" class="<tag:tortpl[].leechcolor />" style="text-align: center;"><tag:tortpl[].leechs /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:tortpl[].completed /></td>

  </tr>

  </loop:tortpl>

  <else:RESULTS_1>

  <tr>

    <td class="lista" align="center" colspan="9"><tag:language.NO_ACTIVE_TORR /></td>

  </tr>


  </if:RESULTS_1>

</table>

<br />
<tag:pagertophist />
<table width="100%" class="lista">

  <tr>

    <td class="block" align="center" colspan="10"><b><tag:language.HISTORY /></b></td>

  </tr>

  <tr>

    <td align="center" class="header"><tag:language.FILE /></td>

    <td align="center" class="header"><tag:language.SIZE /></td>

    <td align="center" class="header"><tag:language.PEER_CLIENT /></td>

    <td align="center" class="header"><tag:language.PEER_STATUS /></td>

    <td align="center" class="header"><tag:language.DOWNLOADED /></td>

    <td align="center" class="header"><tag:language.UPLOADED /></td>

    <td align="center" class="header"><tag:language.RATIO /></td>

    <td align="center" class="header"><tag:language.SHORT_S /></td>

    <td align="center" class="header"><tag:language.SHORT_L /></td>

    <td align="center" class="header"><tag:language.SHORT_C /></td>

  </tr>

  <if:RESULTS_2>

  <loop:torhistory>

  <tr>

    <td align="center" class="lista" style="padding-left:10px;"><tag:torhistory[].filename /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:torhistory[].size /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:torhistory[].agent /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:torhistory[].status /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:torhistory[].downloaded /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:torhistory[].uploaded /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:torhistory[].ratio /></td>

    <td align="center" class="<tag:torhistory[].seedscolor />" style="text-align: center;"><tag:torhistory[].seeds /></td>

    <td align="center" class="<tag:torhistory[].leechcolor />" style="text-align: center;"><tag:torhistory[].leechs /></td>

    <td align="center" class="lista" style="text-align: center;"><tag:torhistory[].completed /></td>

  </tr>

  </loop:torhistory>
  <else:RESULTS_2>

  <tr>

    <td class="lista" align="center" colspan="10"><tag:language.NO_HISTORY /></td>

  </tr>

  </if:RESULTS_2>

</table>

<br />

<br />

<center><tag:userdetail_back /></center>

<br />