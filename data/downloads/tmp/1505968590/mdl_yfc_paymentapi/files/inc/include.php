<?php
/** モジュール基本設定 */
//モジュールコード
define('MDL_YFCAPI_CODE', 'mdl_yfc_paymentapi');
//モジュールバージョン
define('MDL_YFCAPI_VERSION', '2.0.9');

/** モジュール情報 */
//サービス名
define('MDL_YFCAPI_SERVICE_NAME', 'ネット総合決済サービス');
//クロネコ代金後払いサービス名
define('MDL_YFCAPI_DEFERRED_SERVICE_NAME', 'クロネコ代金後払い');
//モジュール名
define('MDL_YFCAPI_MODULE_NAME', 'クロネコｗｅｂコレクト決済モジュール');

/** モジュールデバッグ設定 */
// true の場合、顧客の入力内容がログに残りますので運用時は必ずfalseにして下さい。
define('MDL_YFCAPI_DEBUG', false);

/** モジュールパス設定 */
//モジュールインストール先
define('MDL_YFCAPI_PATH', MODULE_REALDIR. MDL_YFCAPI_CODE.'/');
//クラスパス
define('MDL_YFCAPI_CLASS_PATH', MDL_YFCAPI_PATH.'class/');
//拡張クラスパス
define('MDL_YFCAPI_CLASSEX_PATH', MDL_YFCAPI_PATH.'class_extends/');
//テンプレートパス
define('MDL_YFCAPI_TEMPLATE_PATH', MDL_YFCAPI_PATH.'templates/');
//ヘルパ―クラスパス
define('MDL_YFCAPI_HELPER_PATH', MDL_YFCAPI_PATH.'class/helper/');
//ヘルパ―拡張クラスパス//
define('MDL_YFCAPI_HELPEREX_PATH', MDL_YFCAPI_PATH.'class_extends/helper_extends/');
//ページヘルパ―クラスパス
define('MDL_YFCAPI_PAGE_HELPER_PATH', MDL_YFCAPI_PATH.'class/pages/helper/');
//ページヘルパ―拡張クラスパス//
define('MDL_YFCAPI_PAGE_HELPEREX_PATH', MDL_YFCAPI_PATH.'class_extends/page_extends/helper_extends/');
//Cacheパス
define('MDL_YFCAPI_CACHE_PATH', DATA_REALDIR.'cache/');


/*API情報ファイルパス */
//コードパス
define('MDL_YFCAPI_CODE_PATH', MDL_YFCAPI_PATH .'code/');

/** 決済通知先 */
//決済通知ファイル名
define('MDL_YFCAPI_SETTLEMENT_FILE', '/s/recv.php');
//決済通知URL
define('MDL_YFCAPI_SETTLEMENT_URL', HTTPS_URL . USER_DIR . MDL_YFCAPI_CODE. MDL_YFCAPI_SETTLEMENT_FILE);

/** メディアファイル設置先 */
//メディアファイルURL
define('MDL_YFCAPI_MEDIAFILE_URL', HTTPS_URL . USER_DIR . MDL_YFCAPI_CODE . '/');
//メディアファイルパス
define('MDL_YFCAPI_MEDIAFILE_PATH', USER_REALDIR . MDL_YFCAPI_CODE . '/');

/** 受注データ毎の情報保存カラム設定 */
//支払方法の表示用データ(dtb_order)
define('MDL_YFCAPI_ORDER_COL_PAYVIEW', 'memo02');
//支払方法ID
define('MDL_YFCAPI_ORDER_COL_PAYID', 'memo03');
//決済状況
define('MDL_YFCAPI_ORDER_COL_PAYSTATUS', 'memo04');
//決済データ
define('MDL_YFCAPI_ORDER_COL_PAYDATA', 'memo05');
//審査結果
define('MDL_YFCAPI_ORDER_COL_EXAMRESULT', 'memo06');
//トランザクションID
define('MDL_YFCAPI_ORDER_COL_TRANSID', 'memo08');
//決済ログ
define('MDL_YFCAPI_ORDER_COL_PAYLOG', 'memo09');

/** 支払い方法毎の情報保存カラム設定(dtb_payment) */
//支払方法ID
define('MDL_YFCAPI_PAYMENT_COL_PAYID', 'memo03');
//支払方法ごとの設定値
define('MDL_YFCAPI_PAYMENT_COL_CONFIG', 'memo05');

/** 決済タイプ (EC-CUBE内の決済種別 識別ID) */
//クレジット支払ID
define('MDL_YFCAPI_PAYID_CREDIT', '10');
//コンビニ支払ID
define('MDL_YFCAPI_PAYID_CVS', '30');
//Edy支払ID
define('MDL_YFCAPI_PAYID_EDY', '42');
//モバイルEdy支払ID
define('MDL_YFCAPI_PAYID_MOBILEEDY', '43');
//SUICA支払ID
define('MDL_YFCAPI_PAYID_SUICA', '44');
//モバイルSUICA支払ID
define('MDL_YFCAPI_PAYID_MOBILESUICA', '45');
//WAON支払ID
define('MDL_YFCAPI_PAYID_WAON', '46');
//モバイルWAON支払ID
define('MDL_YFCAPI_PAYID_MOBILEWAON', '47');
//ネットバンク支払ID
define('MDL_YFCAPI_PAYID_NETBANK', '52');
//クロネコ代金後払い
define('MDL_YFCAPI_PAYID_DEFERRED', '60');
/** モジュール通信設定 */
//決済通信タイムアウト値
define('MDL_YFCAPI_HTTP_TIMEOUT', 20);


/** 入力パラメータ桁数 */
//カード番号桁数
define('MDL_YFCAPI_CREDIT_NO_LEN', 16);
//セキュリティコード最小桁数
define('MDL_YFCAPI_SECURITY_CODE_MIN_LEN', 3);
//セキュリティコード最大桁数
define('MDL_YFCAPI_SECURITY_CODE_MAX_LEN', 4);
//カード名義桁数
define('MDL_YFCAPI_CARD_ORNER_LEN', 20);
//商品名桁数
define('MDL_YFCAPI_ITEM_NAME_LEN', 200);
//SUICAの商品名桁数
define('MDL_YFCAPI_SUICA_ITEM_NAME_LEN', 40);

//クレジット支払上限金額
define('MDL_YFCAPI_CREDIT_RULE_MAX', 300000);
//コンビニ支払上限金額
define('MDL_YFCAPI_CVS_RULE_MAX', 300000);
//Edy支払上限金額
define('MDL_YFCAPI_EDY_RULE_MAX', 50000);
//SUICA支払上限金額
define('MDL_YFCAPI_SUICA_RULE_MAX', 20000);
//WAON支払上限金額
define('MDL_YFCAPI_WAON_RULE_MAX', 50000);
//ネットバンク支払上限金額
define('MDL_YFCAPI_NETBANK_RULE_MAX', 300000);
//クロネコ代金後払い上限金額
define('MDL_YFCAPI_DEFERRED_RULE_MAX', 50000);

//クレジット支払下限金額
define('MDL_YFCAPI_CREDIT_RULE_MIN', 1);
//コンビニ支払下限金額
define('MDL_YFCAPI_CVS_RULE_MIN', 1);
//Edy支払下限金額
define('MDL_YFCAPI_EDY_RULE_MIN', 1);
//SUICA支払下限金額
define('MDL_YFCAPI_SUICA_RULE_MIN', 1);
//WAON支払下限金額
define('MDL_YFCAPI_WAON_RULE_MIN', 1);
//ネットバンク支払下限金額
define('MDL_YFCAPI_NETBANK_RULE_MIN', 32);
//クロネコ代金後払い支払下限金額
define('MDL_YFCAPI_DEFERRED_RULE_MIN', 1);

//カード登録上限数
define('MDL_YFCAPI_CREDIT_SAVE_LIMIT', 3);

/** 決済ステータス */
//決済依頼済み
define('MDL_YFCAPI_ACTION_STATUS_SEND_REQUEST','0');
//決済申込完了
define('MDL_YFCAPI_ACTION_STATUS_COMP_REQUEST','1');
//入金完了（速報）
define('MDL_YFCAPI_ACTION_STATUS_PROMPT_REPORT','2');
//入金完了（確報）
define('MDL_YFCAPI_ACTION_STATUS_DIFINIT_REPORT','3');
//与信完了
define('MDL_YFCAPI_ACTION_STATUS_COMP_AUTH','4');
//予約受付完了
define('MDL_YFCAPI_ACTION_STATUS_COMP_RESERVE','5');
//購入者都合エラー
define('MDL_YFCAPI_ACTION_STATUS_NG_CUSTOMER','11');
//加盟店都合エラー
define('MDL_YFCAPI_ACTION_STATUS_NG_SHOP','12');
//決済機関都合エラー
define('MDL_YFCAPI_ACTION_STATUS_NG_PAYMENT','13');
//その他システムエラー
define('MDL_YFCAPI_ACTION_STATUS_NG_SYSTEM','14');
//予約販売与信エラー
define('MDL_YFCAPI_ACTION_STATUS_NG_RESERVE','15');
//決済依頼取消エラー
define('MDL_YFCAPI_ACTION_STATUS_NG_REQUEST_CANCEL','16');
//金額変更ＮＧ
define('MDL_YFCAPI_ACTION_STATUS_NG_CHANGE_PAYMENT','17');
//決済中断
define('MDL_YFCAPI_ACTION_STATUS_NG_TRANSACTION','20');
//決済手続き中
define('MDL_YFCAPI_ACTION_STATUS_WAIT','21');
//精算確定待ち
define('MDL_YFCAPI_ACTION_STATUS_WAIT_SETTLEMENT','30');
//精算確定
define('MDL_YFCAPI_ACTION_STATUS_COMMIT_SETTLEMENT','31');
//取消
define('MDL_YFCAPI_ACTION_STATUS_CANCEL','40');
//3Dセキュア認証中
define('MDL_YFCAPI_ACTION_STATUS_3D_WAIT','50');

/** クロネコ代金後払い用決済ステータス **/
//承認済み
define('MDL_YFCAPI_DEFERRED_AUTH_OK','1');
//取消済み
define('MDL_YFCAPI_DEFERRED_AUTH_CANCEL','2');
//送り状番号登録済み
define('MDL_YFCAPI_DEFERRED_REGIST_DELIV_SLIP','3');
//配送要調査
define('MDL_YFCAPI_DEFERRED_RESEARCH_DELIV','5');
//警報メール送信済み
define('MDL_YFCAPI_DEFERRED_SEND_WARNING','6');
//売上確定
define('MDL_YFCAPI_DEFERRED_SALES_OK','10');
//請求書発行済み
define('MDL_YFCAPI_DEFERRED_SEND_BILL','11');
//入金済み
define('MDL_YFCAPI_DEFERRED_PAID','12');

/** クロネコ代金後払い用審査結果 **/
//ご利用可
define('MDL_YFCAPI_DEFERRED_AVAILABLE','0');
//ご利用不可
define('MDL_YFCAPI_DEFERRED_NOT_AVAILABLE','1');
//限度額超過
define('MDL_YFCAPI_DEFERRED_OVER_LIMIT','2');
//審査中
define('MDL_YFCAPI_DEFERRED_UNDER_EXAM','3');

/** クレジットカード決済手段 */
//UC
define('MDL_YFCAPI_CREDIT_METHOD_UC','1');
//ダイナース
define('MDL_YFCAPI_CREDIT_METHOD_DINERS','2');
//JCB
define('MDL_YFCAPI_CREDIT_METHOD_JCB','3');
//DC
define('MDL_YFCAPI_CREDIT_METHOD_DC','4');
//三井住友クレジット
define('MDL_YFCAPI_CREDIT_METHOD_MITSUISUMITOMO','5');
//UFJ
define('MDL_YFCAPI_CREDIT_METHOD_UFJ','6');
//クレディセゾン
define('MDL_YFCAPI_CREDIT_METHOD_SAISON','7');
//NICOS
define('MDL_YFCAPI_CREDIT_METHOD_NICOS','8');
//VISA
define('MDL_YFCAPI_CREDIT_METHOD_VISA','9');
//MASTER
define('MDL_YFCAPI_CREDIT_METHOD_MASTER','10');
//イオンクレジット
define('MDL_YFCAPI_CREDIT_METHOD_AEON','11');
//アメックス
define('MDL_YFCAPI_CREDIT_METHOD_AMEX','12');
//TOP＆カード
define('MDL_YFCAPI_CREDIT_METHOD_TOP','13');
//その他
define('MDL_YFCAPI_CREDIT_METHOD_OTHER','99');

/** コンビニ決済手段 */
//セブンイレブン
define('MDL_YFCAPI_CVS_METHOD_SEVENELEVEN','21');
//ローソン
define('MDL_YFCAPI_CVS_METHOD_LAWSON','22');
//ファミリーマート
define('MDL_YFCAPI_CVS_METHOD_FAMILYMART','23');
//セイコーマート
define('MDL_YFCAPI_CVS_METHOD_SEICOMART','24');
//ミニストップ
define('MDL_YFCAPI_CVS_METHOD_MINISTOP','25');
//サークルＫサンクス
define('MDL_YFCAPI_CVS_METHOD_CIRCLEK','26');

/** 電子マネー決済手段 */
//楽天Edy
define('MDL_YFCAPI_EMONEY_METHOD_RAKUTENEDY','61');
//楽天モバイルEdy
define('MDL_YFCAPI_EMONEY_METHOD_M_RAKUTENEDY','62');
//Suica
define('MDL_YFCAPI_EMONEY_METHOD_SUICA','63');
//モバイルSuica
define('MDL_YFCAPI_EMONEY_METHOD_M_SUICA','64');
//WAON
define('MDL_YFCAPI_EMONEY_METHOD_WAON','65');
//モバイルWAON
define('MDL_YFCAPI_EMONEY_METHOD_M_WAON','66');

/** ネットバンク決済手段 */
//楽天銀行
define('MDL_YFCAPI_NETBANK_METHOD_RAKUTENBANK','41');

/** 3Dセキュア関連 */
//加盟店ECサイトURL
define('MDL_YFCAPI_TRADER_URL', HTTPS_URL . 'shopping/load_payment_module.php' . '?mode=3dTran');
//3Dセキュア認証対象外エラーコード
define('MDL_YFCAPI_3D_EXCLUDED','A012050002');

/** 予約販売商品関連 */
//商品種別ID(リンクタイプに合わせる)
define('MDL_YFCAPI_PRODUCT_TYPE_ID', '9625');
//予約販売再与信期限(再与信日を含む10日前のため9で設定)
define('MDL_YFCAPI_DEADLINE_RECREDIT', 9);

/** 出荷情報登録関連 */
//１注文に対する配送先上限件数
define('MDL_YFCAPI_DELIV_ADDR_MAX', 99);
//１送り状に対する登録可能受注番号件数（同梱件数）
define('MDL_YFCAPI_SHIPPED_MAX', 3);

/** 出荷完了メール関連 */
define('MDL_YFCAPI_MAIL_COMMON_ID', '9625');

/** 後払い用出荷完了メール関連 */
//追跡URL
define('MDL_YFCAPI_DEFERRED_DELIV_SLIP_URL', 'http://toi.kuronekoyamato.co.jp/cgi-bin/tneko');

/** 後払い不可商品ステータス関連 */
//後払い不可商品ステータスID
define('MDL_YFCAPI_PRODUCT_STATUS_ID', '9625');

/** 注文ステータス関連 */
//クレジットカード出荷登録済み
define('MDL_YFCAPI_ORDER_SHIPPING_REGISTERED', '9625');

/** 後払い通信用 */
//コンバートオプション(全角)
define('MDL_YFCAPI_CONV_OP_DOUBLE', 'KVAS');
//後払いでの1注文に対する配送先上限件数
define('MDL_YFCAPI_DEFERRED_DELIV_ADDR_MAX', 10);

/** 補助機能プラグイン関連 */
//送り状種別コレクト
define('MDL_YFCAPI_DELIV_SLIP_TYPE_CORECT', 2);
//送り状種別：ネコポス
define('MDL_YFCAPI_DELIV_SLIP_TYPE_NEKOPOS', 7);
//EC-CUBEデフォルトの代金引換支払方法ID
define('MDL_YFCAPI_ECCUBE_PAYMENT_DAIBIKI', 4);

/** B2CSV関連 */
//CSV項目数
define('MDL_YFCAPI_B2CSV_BASE_COLUMN_NUMBER', 95);

/** その他変数 */
//許可文字
//「#」「-」「*」「/」「<」「>」「:」「@」
// 禁止半角記号
//ASCII文字のうち印字可能なもの(ASCIIコード順)
//!"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~
//!" $%&'() +, .            ; = ?                           [\]^_`                          {|}~
$arrProhibitedKigo = array('!','"','$','%','&','\'','(',')','+',',','.',';','=','?','[','\\',']','^','_','`','{','|','}','~');

/** 共通インクルードクラス */
//モジュール基本クラス（設定値取得等）
require_once(MDL_YFCAPI_CLASSEX_PATH . 'SC_Mdl_YFCApi_Ex.php');
//モジュールユーティリティクラス
require_once(MDL_YFCAPI_CLASSEX_PATH . 'util_extends/SC_Util_Mdl_YFCApi_Ex.php');
//モジュールユーティリティCacheクラス
require_once(MDL_YFCAPI_CLASSEX_PATH . 'util_extends/SC_Util_Mdl_YFCApi_Cache_Ex.php');
