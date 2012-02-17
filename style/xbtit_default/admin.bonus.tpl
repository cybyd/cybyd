<p>
<if:firstview>
<table width=33%>
  <tr>
    <form method="post" action="index.php?page=admin&amp;user=<tag:uid />&amp;code=<tag:random />&amp;do=seedbonus">
    <td class="header"><tag:language.BONUS />:</td>
    <td class="lista" colspan="2"><center><input type="text" size="3" name="bonus" maxlength="5" value="<tag:bonus />"/></center></td>
  </tr>
  <tr>
  <td class="header" rowspan="4"><tag:language.PRICE_GB />:</td>
  <loop:traf>
         <tr>
         <td class="lista"><center>GB<input type="text" size="3" name="gb<tag:traf[].name />" maxlength="3" value="<tag:traf[].traffic />"/></center></td>
         <td class="lista"><center><tag:language.POINTS /><input type="text" size="4" name="pts<tag:traf[].name />" maxlength="5" value="<tag:traf[].points />"/></center></td>
         </tr>
  </loop:traf>
  </tr>
  <tr>
    <td class="header"><tag:language.PRICE_VIP />:</td>
    <td class="lista" colspan="2"><center><input type="text" size="4" name="price_vip" maxlength="6" value="<tag:price_vip />"/></center></td>
  </tr>
  <tr>
    <td class="header"><tag:language.PRICE_CT />:</td>
    <td class="lista" colspan="2"><center><input type="text" size="4" name="price_ct" maxlength="6" value="<tag:price_ct />"/></center></td>
  </tr>
  <tr>
    <td class="header"><tag:language.PRICE_NAME />:</td>
    <td class="lista" colspan="2"><center><input type="text" size="4" name="price_name" maxlength="6" value="<tag:price_name />"/></center></td>
  </tr>
  <tr>
    <td class="header" colspan="3" align="center"><input type="submit" value="<tag:language.UPDATE />" name="action"></td>
  </tr>
</table>
<else:firstview>
<tag:language.SEEDBONUS_UPDATED />
</if:firstview>
</p><br />