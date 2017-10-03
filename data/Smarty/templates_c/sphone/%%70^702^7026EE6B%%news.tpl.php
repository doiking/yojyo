<?php /* Smarty version 2.6.27, created on 2017-09-26 11:01:36
         compiled from /home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/news.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/news.tpl', 27, false),array('modifier', 'h', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/news.tpl', 30, false),array('modifier', 'date_format', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/news.tpl', 31, false),)), $this); ?>

<!-- ▼新着情報 -->
<section id="news_area">
    <h2 class="title_block">新着情報</h2>
    <ul class="newslist">
        <?php unset($this->_sections['data']);
$this->_sections['data']['name'] = 'data';
$this->_sections['data']['loop'] = is_array($_loop=((is_array($_tmp=$this->_tpl_vars['arrNews'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['data']['max'] = (int)3;
$this->_sections['data']['show'] = true;
if ($this->_sections['data']['max'] < 0)
    $this->_sections['data']['max'] = $this->_sections['data']['loop'];
$this->_sections['data']['step'] = 1;
$this->_sections['data']['start'] = $this->_sections['data']['step'] > 0 ? 0 : $this->_sections['data']['loop']-1;
if ($this->_sections['data']['show']) {
    $this->_sections['data']['total'] = min(ceil(($this->_sections['data']['step'] > 0 ? $this->_sections['data']['loop'] - $this->_sections['data']['start'] : $this->_sections['data']['start']+1)/abs($this->_sections['data']['step'])), $this->_sections['data']['max']);
    if ($this->_sections['data']['total'] == 0)
        $this->_sections['data']['show'] = false;
} else
    $this->_sections['data']['total'] = 0;
if ($this->_sections['data']['show']):

            for ($this->_sections['data']['index'] = $this->_sections['data']['start'], $this->_sections['data']['iteration'] = 1;
                 $this->_sections['data']['iteration'] <= $this->_sections['data']['total'];
                 $this->_sections['data']['index'] += $this->_sections['data']['step'], $this->_sections['data']['iteration']++):
$this->_sections['data']['rownum'] = $this->_sections['data']['iteration'];
$this->_sections['data']['index_prev'] = $this->_sections['data']['index'] - $this->_sections['data']['step'];
$this->_sections['data']['index_next'] = $this->_sections['data']['index'] + $this->_sections['data']['step'];
$this->_sections['data']['first']      = ($this->_sections['data']['iteration'] == 1);
$this->_sections['data']['last']       = ($this->_sections['data']['iteration'] == $this->_sections['data']['total']);
?>
            <li>
                <a id="windowcolumn<?php echo ((is_array($_tmp=$this->_sections['data']['index'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" href="javascript:getNewsDetail(<?php echo ((is_array($_tmp=$this->_tpl_vars['arrNews'][$this->_sections['data']['index']]['news_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
);">
                <span class="news_title"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrNews'][$this->_sections['data']['index']]['news_title'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</span></a><br />
                <span class="news_date"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrNews'][$this->_sections['data']['index']]['cast_news_date'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y年 %m月 %d日") : smarty_modifier_date_format($_tmp, "%Y年 %m月 %d日")); ?>
</span>
            </li>
        <?php endfor; endif; ?>
    </ul>

    <?php if (((is_array($_tmp=$this->_tpl_vars['newsCount'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) > 3): ?>
        <div class="btn_area">
            <p><a href="javascript:;" class="btn_more" id="btn_more_news" onclick="getNews(3); return false;">もっとみる(＋3件)</a></p>
        </div>
    <?php endif; ?>
</section>
<!-- ▲新着情報 -->


<script>
    var newsPageNo = 2;

    function getNews(limit) {
        eccube.showLoading();
        var i = limit;

        $.ajax({
            url: "<?php echo ((is_array($_tmp=@ROOT_URLPATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
frontparts/bloc/news.php",
            type: "POST",
            data: "mode=getList&pageno="+newsPageNo+"&disp_number="+i,
            cache: false,
            dataType: "json",
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(textStatus);
                eccube.hideLoading();
            },
            success: function(result){
                if (result.error) {
                    alert(result.error);
                } else {
                    for (var j = 0; j < i; j++) {
                        if (result[j] != null) {
                            var news = result[j];
                            var maxCnt = $("#news_area ul.newslist li").length - 1;
                            var newsEl = $("#news_area ul.newslist li").get(maxCnt);
                            newsEl = $(newsEl).clone(true).insertAfter(newsEl);
                            maxCnt++;

                            //件名をセット
                            $($("#news_area ul.newslist li a span.news_title").get(maxCnt)).text(news.news_title);

                            //リンクをセット
                            $($("#news_area ul.newslist li a").get(maxCnt)).attr("href", "javascript:getNewsDetail(" + news.news_id + ");");

                            //年月をセット
                            var newsDateDispArray = news.cast_news_date.split("-"); //ハイフンで年月日を分解
                            var newsDateDisp = newsDateDispArray[0] + "年 " + newsDateDispArray[1] + "月 " + newsDateDispArray[2] + "日";
                            $($("#news_area ul.newslist li span.news_date").get(maxCnt)).text(newsDateDisp);
                        }
                    }

                    //全ての新着情報を表示したか判定
                    var newsPageCount = result.news_page_count;
                    if (parseInt(newsPageCount) <= newsPageNo) {
                        $("#btn_more_news").hide();
                    }

                    newsPageNo++;
                }
                eccube.hideLoading();
            }
        });
    }

    var loadingState = 0;
    function getNewsDetail(newsId) {
        if (loadingState == 0) {
            loadingState = 1;
            eccube.showLoading();
            $.ajax({
                url: "<?php echo ((is_array($_tmp=@ROOT_URLPATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
frontparts/bloc/news.php",
                type: "GET",
                data: "mode=getDetail&news_id="+newsId,
                cache: false,
                async: false,
                dataType: "json",
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    alert(textStatus);
                    eccube.hideLoading();
                    loadingState = 0;
                },
                success: function(result){
                    if (result.error) {
                        alert(result.error);
                        eccube.hideLoading();
                        loadingState = 0;
                    }
                    else if (result != null) {
                        var dialog = $("#news-dialog");

                        //件名をセット
                        $("#news-dialog-title").remove();
                        if (result.news_url != null) {
                            dialog.find(".dialog-content").append(
                                $('<h3 id="news-dialog-title">').append(
                                    $('<a>')
                                        .attr('href', result.news_url)
                                        .attr('rel', "external")
                                        .attr('target', "_blank")
                                        .text(result.news_title)
                                )
                            );
                        } else {
                            dialog.find(".dialog-content").append(
                                $('<h3 id="news-dialog-title">').text(result.news_title)
                            );
                        }

                        //本文をセット
                        $("#news-dialog-body").remove();
                        if (result.news_comment != null) {
                            dialog.find(".dialog-content").append(
                                $('<div id="news-dialog-body">').html(result.news_comment.replace(/\n/g,"<br />"))
                            );
                        }

                        //ダイアログをモーダルウィンドウで表示
                        $.colorbox({inline: true, href: dialog, onOpen: function(){
                            dialog.show().css('width', String($('body').width() * 0.9) + 'px');
                        }, onComplete: function(){
                            eccube.hideLoading();
                            loadingState = 0;
                        }, onClosed: function(){
                            dialog.hide();
                        }});
                    }
                    else {
                        eccube.hideLoading();
                        loadingState = 0;
                        alert('取得できませんでした。');
                    }
                }
            });
        }
    }
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@SMARTPHONE_TEMPLATE_REALDIR)."frontparts/dialog_modal.tpl", 'smarty_include_vars' => array('dialog_id' => "news-dialog",'dialog_title' => "新着情報")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>