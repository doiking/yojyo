
<!--{if $order|regex_replace:'/^a[0-9]+/i':'add' eq 'add'}-->
    <tr class="add_item">
      <th>
        <!--{assign var=key value="plg_customtradelaw_name_$order"}-->
        <input type="hidden" name="plg_customtradelaw_order[]" value="<!--{$order}-->" />
        <!--{if $arrErr[$key]}-->
        <span class="attention">※項目名が入力されていません。</span><br />
        <!--{/if}-->
        <input type="checkbox" name="plg_customtradelaw_disp[]" value="<!--{$order}-->"<!--{if in_array($order, $arrDisp)}--> checked<!--{/if}--> />
        <input type="text" name="<!--{$key}-->" maxlength="<!--{$arrForm[$key].length}-->" size="30" value="<!--{$arrForm[$key].value|h}-->" /><br />
        <button type="button" class="delete_item" value="<!--{$order}-->">削除</button>
      </th>
      <td>
        <!--{assign var=key value="plg_customtradelaw_value_$order"}-->
        <textarea name="<!--{$key}-->" maxlength="<!--{$arrForm[$key].length}-->" cols="60" rows="8" class="area60"><!--{$arrForm[$key].value|h}--></textarea><span class="attention"> (上限<!--{$arrForm[$key].length}-->文字)</span>
      </td>
    </tr>
<!--{/if}-->
<!--{/foreach}-->
