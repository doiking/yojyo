<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2014 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *}-->

<div id="undercolumn">
    <div id="undercolumn_shopping" class="margin-section">
        <p class="flow_area">
            <ol class="shopping-flow">
                <li class="current">お届け先の指定</li>
                <li class="row2">お支払い方法・<br>お届け時間等の指定</li>
                <li>入力内容ご確認</li>
                <li>ご注文完了</li>
            </ol>
            <!--{* 
            * ここに画像を使うときはコメントアウトを解除
            * <img src="<!--{$TPL_URLPATH}-->img/picture/img_flow_01.jpg" alt="購入手続きの流れ" />
            *}-->
        </p>
        <div class="ui-headline page-title">
            <h2 class="title"><!--{$tpl_title|h}--></h2>
        </div>
        <p class="information">各商品のお届け先を選択してください。<br />（※数量の合計は、カゴの中の数量と合わせてください。）</p>
        <!--{if $tpl_addrmax < $smarty.const.DELIV_ADDR_MAX}-->
            <p>一覧にご希望の住所が無い場合は、「新しいお届け先を追加する」より追加登録してください。</p>
        <!--{/if}-->
        <p class="mini attention">※最大<!--{$smarty.const.DELIV_ADDR_MAX|h}-->件まで登録できます。</p>

        <!--{if $tpl_addrmax < $smarty.const.DELIV_ADDR_MAX}-->
            <p class="addbtn">
                <a href="<!--{$smarty.const.ROOT_URLPATH}-->mypage/delivery_addr.php" onclick="eccube.openWindow('<!--{$smarty.const.ROOT_URLPATH}-->mypage/delivery_addr.php?page=<!--{$smarty.server.SCRIPT_NAME|h}-->','new_deiv','600','640'); return false;" class="btn btn-default btn-styled">新しいお届け先を追加する</a>
                <!--{* 
                * ボタンに画像を使うときはコメントアウトを解除
                * <input type="image" class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_buystep.jpg" alt="購入手続きへ" name="buystep" id="buystep" />
                *}-->
            </p>
        <!--{/if}-->
        <form name="form1" id="form1" method="post" action="?">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />
            <input type="hidden" name="line_of_num" value="<!--{$arrForm.line_of_num.value}-->" />
            <input type="hidden" name="mode" value="confirm" />
            <div class="table-responsive">
                <table class="table table-bordered ui-table" summary="商品情報">
                    <col width="10%" />
                    <col width="35%" />
                    <col width="10%" />
                    <col width="45%" />
                    <tr>
                        <th class="alignC">商品写真</th>
                        <th class="alignC">商品名</th>
                        <th class="alignC">数量</th>
                        <th class="alignC">お届け先</th>
                    </tr>
                    <!--{section name=line loop=$arrForm.line_of_num.value}-->
                        <!--{assign var=index value=$smarty.section.line.index}-->
                        <tr>
                            <td class="alignC">
                                <a
                                    <!--{if $arrForm.main_image[$index]|strlen >= 1}--> href="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.main_image.value[$index]|sfNoImageMainList|h}-->" class="expansion" target="_blank"
                                    <!--{/if}-->
                                >
                                    <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrForm.main_list_image.value[$index]|sfNoImageMainList|h}-->" style="max-width: 65px;max-height: 65px;" alt="<!--{$arrForm.name.value[$index]|h}-->" /></a>
                            </td>
                            <td><!--{* 商品名 *}--><strong><!--{$arrForm.name.value[$index]|h}--></strong><br />
                                <!--{if $arrForm.classcategory_name1.value[$index] != ""}-->
                                    <!--{$arrForm.class_name1.value[$index]|h}-->：<!--{$arrForm.classcategory_name1.value[$index]|h}--><br />
                                <!--{/if}-->
                                <!--{if $arrForm.classcategory_name2.value[$index] != ""}-->
                                    <!--{$arrForm.class_name2.value[$index]|h}-->：<!--{$arrForm.classcategory_name2.value[$index]|h}--><br />
                                <!--{/if}-->
                                <!--{$arrForm.price_inctax.value[$index]|n2s}-->円
                            </td>
                            <td>
                                <!--{assign var=key value="quantity"}-->
                                <!--{if $arrErr[$key][$index] != ''}-->
                                    <span class="attention"><!--{$arrErr[$key][$index]}--></span>
                                <!--{/if}-->
                                <input type="text" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" class="box40" style="<!--{$arrErr[$key][$index]|sfGetErrorColor}-->" maxlength="<!--{$arrForm[$key].length}-->" />
                            </td>
                            <td>
                                <input type="hidden" name="cart_no[<!--{$index}-->]" value="<!--{$index}-->" />
                                <!--{assign var=key value="product_class_id"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="name"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="class_name1"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="class_name2"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="classcategory_name1"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="classcategory_name2"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="main_image"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="main_list_image"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="price"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="price_inctax"}-->
                                <input type="hidden" name="<!--{$key}-->[<!--{$index}-->]" value="<!--{$arrForm[$key].value[$index]|h}-->" />
                                <!--{assign var=key value="shipping"}-->
                                <!--{if strlen($arrErr[$key][$index]) >= 1}-->
                                    <div class="attention"><!--{$arrErr[$key][$index]}--></div>
                                <!--{/if}-->
                                <select name="<!--{$key}-->[<!--{$index}-->]" style="<!--{$arrErr[$key][$index]|sfGetErrorColor}-->">
                                    <!--{html_options options=$addrs selected=$arrForm[$key].value[$index]}-->
                                </select>
                            </td>
                        </tr>
                    <!--{/section}-->
                </table>
            </div>
            <div class="btn_area">
                <ul class="list-inline">
                    <li>
                        <a href="<!--{$smarty.const.CART_URL}-->" class="btn btn-default btn-styled">戻る</a>
                        <!--{* 
                        * ボタンに画像を使うときはコメントアウトを解除
                        * <a href="<!--{$smarty.const.CART_URL}-->">
                            <img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" name="back03" id="back03" />
                        </a>
                        *}-->
                    </li>
                    <li>
                        <input type="submit" class="btn btn-main btn-styled" value="選択したお届け先に送る" name="send_button" id="send_button" />
                        <!--{* 
                        * ボタンに画像を使うときはコメントアウトを解除
                        * <input type="image" class="hover_change_image box190" src="<!--{$TPL_URLPATH}-->img/button/btn_address_select.jpg" alt="選択したお届け先に送る" name="send_button" id="send_button" />
                        *}-->
                    </li>
                </ul>
            </div>
        </form>
    </div>
</div>
