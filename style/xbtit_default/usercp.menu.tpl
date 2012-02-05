<table class="lista" width="100%">
  <loop:usercp_menu>
  <tr>
    <td class="header">
        <tag:usercp_menu[].title />
    </td>
  </tr>
  <loop:usercp_menu[].menu>
  <tr>
    <td class="lista">
        <a href="<tag:usercp_menu[].menu[].url />"><tag:usercp_menu[].menu[].description /></a>
    </td>
  </tr>
  </loop:usercp_menu[].menu>
  </loop:usercp_menu>
</table>