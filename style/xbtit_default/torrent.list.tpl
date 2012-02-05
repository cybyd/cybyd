<div align="center">
<form action="<tag:torrent_script />" method="get" name="torrent_search">
  <input type="hidden" name="page" value="torrents" />
  <table border="0" class="lista" align="center">
    <tr>
      <td class="block"><tag:language.TORRENT_SEARCH /></td>
      <td class="block"><tag:language.CATEGORY_FULL /></td>
      <td class="block"><tag:language.TORRENT_STATUS /></td>
      <td class="block">&nbsp;</td>
    </tr>
    <tr>
      <td><input type="text" name="search" size="25" maxlength="50" value="<tag:torrent_search />" /></td>
      <td>
        <tag:torrent_categories_combo />
      </td>
      <td>
        <select name="active" size="1">
        <option value="0" <tag:torrent_selected_all />><tag:language.ALL /></option>
        <option value="1" <tag:torrent_selected_active />><tag:language.ACTIVE_ONLY /></option>
        <option value="2" <tag:torrent_selected_dead />><tag:language.DEAD_ONLY /></option>
        </select>
      </td>
      <td><input type="submit" class="btn" value="<tag:language.SEARCH />" /></td>
     </tr>
  </table>
</form>
</div>

<table width="100%">
  <tr>
    <td colspan="2" align="center"> <tag:torrent_pagertop /></td>
  </tr>
  <tr>
    <td>
      <table width="100%" class="lista">      
        <tr>
          <td align="center" width="45" class="header"><tag:torrent_header_category /></td>
          <td align="center" class="header"><tag:torrent_header_filename /></td>
          <if:WT>
          <td align="center" width="20" class="header"><tag:torrent_header_waiting /></td>
          <else:WT>
          </if:WT>
          <td align="center" width="20" class="header"><tag:torrent_header_download /></td>
          <td align="center" width="85" class="header"><tag:torrent_header_added /></td>
          <td align="center" width="30" class="header"><tag:torrent_header_seeds /></td>
          <td align="center" width="30" class="header"><tag:torrent_header_leechers /></td>
          <td align="center" width="30" class="header"><tag:torrent_header_complete /></td>
          <if:XBTT>
          <else:XBTT>
          <td align="center" width="45" class="header"><tag:torrent_header_speed /></td>
          </if:XBTT>
          <td align="center" width="45" class="header"><tag:torrent_header_average /></td>
        </tr>      
        <loop:torrents>
        <tr>
          <td align="center" width="45" class="lista" style="text-align: center;"><tag:torrents[].category /></td>
          <td class="lista" valign="middle" onMouseOver="this.className='post'" onMouseOut="this.className='lista'" style="padding-left:10px;overflow:auto;"><tag:torrents[].filename /></td>
          <if:WT1>
          <td align="center" width="20" class="lista" style="text-align: center;"><tag:torrents[].waiting /></td>
          <else:WT1>
          </if:WT1>
          <td align="center" width="20" class="lista" style="text-align: center;"><tag:torrents[].download /></td>
          <td align="center" width="85" class="lista" style="white-space:wrap; text-align:center;"><tag:torrents[].added /></td>
          <td align="center" width="30" class="<tag:torrents[].classe_seeds />" style="text-align: center;"><tag:torrents[].seeds /></td>
          <td align="center" width="30" class="<tag:torrents[].classe_leechers />" style="text-align: center;"><tag:torrents[].leechers /></td>
          <td align="center" width="30" class="lista" style="text-align: center;"><tag:torrents[].complete /></td>
         <if:XBTT1>
          <else:XBTT1>
          <td align="center" width="45" class="lista" style="text-align: center;"><tag:torrents[].speed /></td>
          </if:XBTT1>
          <td align="center" width="45" class="lista" style="text-align: center;"><tag:torrents[].average /></td>
        </tr>
        </loop:torrents>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"> <tag:torrent_pagerbottom /></td>
  </tr>
</table>