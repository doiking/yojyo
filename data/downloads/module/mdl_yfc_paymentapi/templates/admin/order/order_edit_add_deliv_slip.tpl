<!--{*
 * templates/admin/order/order_edit_add_deliv_slip.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
        <tr>
            <th>送り状番号</th>
            <td>
                <!--{assign var=key1 value="plg_yfcapi_deliv_slip"}-->
                <span class="attention"><!--{$arrErr[$key1][$shipping_index]}--></span>
                <input type="text" name="<!--{$key1}-->[<!--{$shipping_index}-->]" value="<!--{$arrShipping[$key1]|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1][$shipping_index]|sfGetErrorColor}-->" size="30" class="box30" />
            </td>
        </tr>
