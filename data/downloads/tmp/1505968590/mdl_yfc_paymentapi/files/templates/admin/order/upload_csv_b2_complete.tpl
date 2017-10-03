<!--{*
 * templates/admin/order/upload_csv_b2_complete.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->

<div id="order" class="contents-main">

    <div class="message">
    <!--{if $arrRowErr|@count > 0}-->
        <span class="attention">CSV登録時にエラーが発生しました。</span>
    <!--{else}-->
        <span>CSV登録を実行しました。</span>
    <!--{/if}-->
    </div>

    <!--{if $arrRowErr}-->
        <table class="form">
            <tr>
                <td>
                    <!--{foreach item=err from=$arrRowErr}-->
                        <span class="attention"><!--{$err}--><br /></span>
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
                    <span><!--{$result|h}--><br/></span>
                    <!--{/foreach}-->
                </td>
            </tr>
        </table>
    <!--{/if}-->

    <!--{if $arrRowShipmentEntryReport}-->
    <p>■出荷情報登録</p>
        <table class="form">
            <tr>
                <td>
                    <!--{foreach item=result from=$arrRowShipmentEntryReport}-->
                    <span><!--{$result}--><br/></span>
                    <!--{/foreach}-->
                </td>
            </tr>
        </table>
    <!--{/if}-->

    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="?"><span class="btn-prev">戻る</span></a></li>
        </ul>
    </div>
</div>
