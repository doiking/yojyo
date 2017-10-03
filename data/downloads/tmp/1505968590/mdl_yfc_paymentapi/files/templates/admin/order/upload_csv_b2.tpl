<!--{*
 * templates/admin/order/upload_csv_b2.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->

<!--★★メインコンテンツ★★-->
<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="csv_upload" />
    <div id="products" class="contents-main">
        <!--{if $tpl_errtitle != ""}-->
        <p>
            <span class="attention"><!--{$tpl_errtitle}--><br />
            <!--{foreach key=key item=item from=$arrCSVErr}-->
            <!--{$item}-->
            <!--{if $key != 'blank'}-->
            [値：<!--{$arrParam[$key]}-->]
            <!--{/if}-->
            <br />
            <!--{/foreach}-->
            </span>
        </p>
        <!--{/if}-->

        <!--▼登録テーブルここから-->
        <table class="form">
            <tr>
                <th>CSVファイル</th>
                <td>
                    <!--{if $arrErr.csv_file}--><span class="attention"><!--{$arrErr.csv_file}--></span><!--{/if}-->
                    <input type="file" name="csv_file" size="60" class="box60" /><span class="attention"><!--{if $arrSetting.header_output == '1'}--> (1行目タイトル行)<!--{/if}-->
                    (最大アップロードサイズ:<!--{$max_upload_csv_size}-->)</span>
                </td>
            </tr>
            <tr>
                <th>登録情報</th>
                <td>
                    <span class="attention">※『送り状番号』が注文データに登録されます。<!--{if $arrSetting.use_b2_format == '1'}-->その他の情報は更新されませんのでご了承くださいませ。<!--{/if}--></span><br />
                    <!--{if $arrSetting.use_b2_shipping_entry == '1'}--><span class="attention">※注文データに登録された『送り状番号』がクロネコwebコレクト、クロネコ代金後払いに対しても登録されます。</span><br /><!--{/if}-->
                    <!--{foreach name=title key=key item=item from=$arrTitle}-->
                    <!--{$smarty.foreach.title.iteration}-->項目：<!--{$item}--><br />
                    <!--{/foreach}-->
                </td>
            </tr>
        </table>
        <!--▲登録テーブルここまで-->
        <div class="btn-area">
            <ul>
                <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'csv_upload', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul>
        </div>
        <!--{if $arrRowErr}-->
        <table class="form">
            <tr>
                <td>
                    <!--{foreach item=err from=$arrRowErr}-->
                    <span class="attention"><!--{$err}--></span>
                    <!--{/foreach}-->
                </td>
            </tr>
        </table>
        <!--{/if}-->
        <!--{if $arrRowResult}-->
        <table class="form">
            <tr>
                <td>
                    <!--{foreach item=result from=$arrRowResult}-->
                    <span><!--{$result}--><br/></span>
                    <!--{/foreach}-->
                </td>
            </tr>
        </table>
        <!--{/if}-->
    </div>
</form>
