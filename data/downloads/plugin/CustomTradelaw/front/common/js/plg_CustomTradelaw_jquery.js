$(document).ready(function(){
    function DeleteItem(){
        $(this).attr('disabled', 'disabled');
        $(this).unbind();
        $(this).parents('tr').animate(
            {
                'opacity': '0'
            },
            1000,
            function(){
                $(this).remove();
            }
        );
    }

    $('.delete_item').bind('click', DeleteItem);

    $('button#plg_customtradelaw_add_item').click(function(){
        var newNum = parseInt($('input#plg_customtradelaw_item_no').val());
        $('input#plg_customtradelaw_item_no').val(newNum + 1);
        var Content = '<tr class="add_item"><th><input type="hidden" name="plg_customtradelaw_order[]" value="a' + newNum + '" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="a' + newNum + '" checked />\n<input type="text" name="plg_customtradelaw_name_a' + newNum + '" maxlength="' + plg_customtradelaw_stextlen + '" size="30" value="" /><br /><button type="button" class="delete_item" value="a' + newNum + '">削除</button></th><td><textarea name="plg_customtradelaw_value_a' + newNum + '" maxlength="' + plg_customtradelaw_mtextlen + '" cols="60" rows="8" class="area60"></textarea><span class="attention"> (上限' + plg_customtradelaw_mtextlen + '文字)</span></td></tr>';

        $('.form').append(Content);
        $('button[value=a'+newNum+']').bind('click', DeleteItem);
    });

    $('table.form').attr('id', 'sortable');
    $('#sortable tbody').sortable({
        items: 'tr',
        handle: 'th'
    });
});
