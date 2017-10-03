CREATE TABLE plg_dtb_report (
    id smallint,
    name text,
    report_title text,
    message1 text,
    message2 text,
    message3 text,
    notes1 text,
    notes2 text,
    notes3 text,
    PRIMARY KEY (id)
);
INSERT INTO plg_dtb_report (id, name, report_title, message1, message2, message3, notes1, notes2, notes3) VALUES (1, '納品書', 'お買上げ明細書(納品書)','このたびはお買上げいただきありがとうございます。', '下記の内容にて納品させていただきます。', 'ご確認くださいますよう、お願いいたします。', '', '', '');
INSERT INTO plg_dtb_report (id, name, report_title, message1, message2, message3, notes1, notes2, notes3) VALUES (2, 'ピッキングリスト', 'お買上げ明細書(ピッキングリスト)','いつもお世話になっております。', '以下商品をピッキング頂きますよう', 'よろしくお願いいたします。', '株式会社＊＊＊＊様', '', '');