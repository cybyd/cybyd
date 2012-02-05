<if:NOHISTORY>
<table width=100% class="lista" border="0"><tr><td align="center" colspan="9" class="lista"><tag:language.NO_HISTORY /></td></tr></table>
<else:NOHISTORY>
<script language=javascript>
function windowunder(link)
{
  window.opener.document.location=link;
  window.close();
}
</script>
<table width=100% class="lista" border="0">
<tr>
<td align=center class="header" colspan=2><tag:language.USER_NAME /></td>
<td align=center class="header"><tag:language.PEER_COUNTRY /></td>
<td align=center class="header"><tag:language.ACTIVE /></td>
<if:XBTT>
<td align=center class="header"><tag:language.PEER_CLIENT /></td>
</if:XBTT>
<td align=center class="header"><tag:language.DOWNLOADED /></td>
<td align=center class="header"><tag:language.UPLOADED /></td>
<td align=center class="header"><tag:language.RATIO /></td>
<td align=center class="header"><tag:language.FINISHED /></td>
</tr>
<!-- peers' listing -->
<loop:history>
<tr>
<td class="lista" style="text-align:left;"><tag:history[].USERNAME /></td>
<td class="lista" style="text-align:center;"><tag:history[].PM /></td>
<td class="lista" style="text-align:center;"><tag:history[].FLAG /></td>
<td class="lista" style="text-align:center;"><tag:history[].ACTIVE /></td>
<if:XBTT2>
<td class="lista"><tag:history[].CLIENT /></td>
</if:XBTT2>
<td class="lista" style="text-align:right;"><tag:history[].DOWNLOADED /></td>
<td class="lista" style="text-align:right;"><tag:history[].UPLOADED /></td>
<td class="lista" style="text-align:center;"><tag:history[].RATIO /></td>
<td class="lista" style="text-align:center;"><tag:history[].FINISHED /></td>
</tr>
</loop:history>
</table>
<tag:BACK2 />
</if:NOHISTORY>