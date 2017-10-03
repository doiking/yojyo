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
    <div id="undercolumn_order">
        <h2 class="title"><!--{$tpl_title}--></h2>
        <table summary="特定商取引に関する法律に基づく表記">
            <col width="20%" />
            <col width="80%" />
            <tr>
                <th>販売業者</th>
                <td><!--{$arrOrder.law_company}--></td>
            </tr>
            <tr>
                <th>運営責任者</th>
                <td><!--{$arrOrder.law_manager}--></td>
            </tr>
            <tr>
                <th>住所</th>
                <td>〒<!--{$arrOrder.law_zip01}-->-<!--{$arrOrder.law_zip02}--><br /><!--{$arrPref[$arrOrder.law_pref]}--><!--{$arrOrder.law_addr01}--><!--{$arrOrder.law_addr02}--></td>
            </tr>
            <tr>
                <th>電話番号</th>
                <td><!--{$arrOrder.law_tel01}-->-<!--{$arrOrder.law_tel02}-->-<!--{$arrOrder.law_tel03}--></td>
            </tr>
            <tr>
                <th>FAX番号</th>
                <td><!--{$arrOrder.law_fax01}-->-<!--{$arrOrder.law_fax02}-->-<!--{$arrOrder.law_fax03}--></td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td><!?{$arrOrder.law_email|escape:'hexentity'}?></td>
            </tr>
            <tr>
                <th>URL</th>
                <td><a href="<!--{$arrOrder.law_url}-->"><!--{$arrOrder.law_url}--></a></td>
            </tr>
            <tr>
                <th>商品以外の必要代金</th>
                <td><!--{$arrOrder.law_term01|nl2br}--></td>
            </tr>
            <tr>
                <th>注文方法</th>
                <td><!--{$arrOrder.law_term02|nl2br}--></td>
            </tr>
            <tr>
                <th>支払方法</th>
                <td><!--{$arrOrder.law_term03|nl2br}--></td>
            </tr>
            <tr>
                <th>支払期限</th>
                <td><!--{$arrOrder.law_term04|nl2br}--></td>
            </tr>
            <tr>
                <th>引渡し時期</th>
                <td><!--{$arrOrder.law_term05|nl2br}--></td>
            </tr>
            <tr>
                <th>返品・交換について</th>
                <td><!--{$arrOrder.law_term06|nl2br}--></td>
            </tr>
        </table>
    </div>
</div>
