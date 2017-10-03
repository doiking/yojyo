<!--{*
 *
 * LogoPDFChange
 * Copyright (C) 2013 S-cubism All rights reserved.
 * http://ec-cube.ec-orange.jp/
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *}-->
<input type="hidden" name="image_key" value="" />

    <h2>納品書設定</h2>
    <table>
        <tr>
            <th>納品書ロゴマーク <span class="attention">(※.pngのみ対応)</span<</th>
            <td>
                <span class="attention"> <!--{$pdfLogoErr}--> </span>
                <div>
                    <img src = "<!--{$path_to_PDF_logo}-->" />
                    <a href="javascript:;" name="btn" onclick="fnModeSubmit('reset_logo', 'image_key', '<!--{$logoKey}-->'); return false;">[画像のリセット]</a>
                </div>
                <div>
                    推奨ロゴサイズ 180px × 30px
                </div>
                <div>
                    <input type="file" name="pdf_logo" size="40" style=""/>
                    <a class="btn-normal" href="javascript:;" onclick="fnModeSubmit('upload_logo', 'image_key', '<!--{$key}-->');"><span class="btn-next">アップロード</span></a>
                </div>
            </td>
        </tr>
    </table>
