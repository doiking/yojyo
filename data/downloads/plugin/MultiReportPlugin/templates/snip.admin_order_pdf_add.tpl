<script type="text/javascript">
$(function(){
    // 初期表示
    getTypeValue(1);
    
    $('select[name=type]').change(function() {
        // AJAX通信部分
    　  id = $(this).attr("value");　// valueの取得
        id =parseInt(id) +1;
        getTypeValue(id);
    });
});

// typeを取得
function getTypeValue(id) {
	$.ajaxSetup({ cache: false });	
	$.getJSON(
        "../MultiReportApi.php",
        { id: id },
        function(data){
            $('input[name=title]').val(data[0].report_title);
            $('input[name=msg1]').val(data[0].message1);
            $('input[name=msg2]').val(data[0].message2);
            $('input[name=msg3]').val(data[0].message3);
            $('input[name=etc1]').val(data[0].notes1);
            $('input[name=etc2]').val(data[0].notes2);
            $('input[name=etc3]').val(data[0].notes3);
        }
    );
}
</script>
