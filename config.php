<?php

//DB接続の為の定数設定
define("DB_NAME","saiyasu");  //ここにＭyＳＱＬデータベース名を入力ください。
define("DB_HOST","localhost");  //ここにデータベースホスト名を入力してください。
define("DB_USER","root");  //ここにデータベースユーザー名を入力してください。
define("DB_PASSWORD","r1YOnKH9");  //ここにデータベースユーザーパスワードを入力してください。


date_default_timezone_set('Asia/Tokyo');


//■■■■■関数定義■■■■■
//データベース接続関数
function connectdb(){
	mysql_connect(DB_HOST,DB_USER,DB_PASSWORD)or die("aaaDBに接続できません：".mysql_error());
	mysql_select_db(DB_NAME)or die("DBを選択できません：".mysql_error());
mysql_query('SET NAMES utf8');
	}


//出力前のエスケープ
function h($s){
	return htmlspecialchars($s);
	}

//DBのクエリ前のエスケープ
function r($r){
	return mysql_real_escape_string($r);
	}


// $str にURLが含まれていたらリンクをつける
function autoLinker($str){
        $pat_sub = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/'); // 正規表現向けのエスケープ処理
        $pat  = '/((http|https):\/\/[0-9a-z' . $pat_sub . ']+)/i'; // 正規表現パターン
        $rep  = '<a href="\\1">\\1</a>'; // \\1が正規表現にマッチした文字列に置き換わります
 
        $str = preg_replace ($pat, $rep, $str); // 実処理
        return $str;
    }

//暗号化の為の独自関数
function angou($string){
		$salt="banana";
		$pass_hash = sha1($salt . $string);	
		return $pass_hash;
	}




//入力されているかチェック
function isEmpty2($vali){
	
	if(strlen($vali)>1){
		
		if($vali=="商品名・型番などを入力してください"){
			return false;	
		}else{
			return true;
		}
	
	}else{
		return false;
	}//if(strlen($vali)>1)
	
}

//var_dumpの独自
function debug_echo($a,$b=1){
	
	echo "<pre>";
	var_dump($a);
	echo "</pre>";

	if($b==1){die();}

}

//die()だと文字化けが出るのでこっちを使う。
function abort2($status=0) {
	if (is_string($status) && !headers_sent()) {
		header('Content-Type: text/html; charset=UTF-8');
	}
	die($status);
}

//関数定義ここまで





/*ここから変数処理（サイト情報アンドAPIキー系）*/

//すべての情報を変数に入れる。
$site_url = file_get_contents("common/site_url.txt");//サイトURL
$site_root = $site_url;
$site_name = file_get_contents("common/site_name.txt");//サイト名
$site_description = file_get_contents("common/site_description.txt");//サイトの詳細
$site_keyword = file_get_contents("common/site_keyword.txt");//サイトキーワード


//amazonAPIキー
for($j=21; $j<100; $j++){

	$amazon_app = file_get_contents("common/amazon_access_key{$j}.txt");//アマゾンアクセスキー
	$amazon_app_sec = file_get_contents("common/amazon_secret{$j}.txt");//アマゾンシークレット
	
	if(isEmpty2($amazon_app)==TRUE && isEmpty2($amazon_app_sec)==TRUE){
		$amazon_app_id_list[$j] = $amazon_app;
		$amazon_app_sec_list[$j]= $amazon_app_sec;
	}

}
$keyArray = array_rand($amazon_app_id_list,1);
$amazon_access_key = $amazon_app_id_list[$keyArray];
$amazon_secret = $amazon_app_sec_list[$keyArray];



//ヤフーAPIキー
for($j=1; $j<10; $j++){

	$yahoo_app = file_get_contents("common/yahoo_app_id{$j}.txt");//yahooアプリＩＤ
	
	if(isEmpty2($yahoo_app)==TRUE){
		$yahoo_app_id_list[] = $yahoo_app;
	}

}
$keyArray = array_rand($yahoo_app_id_list,1);
$yahoo_app_id = $yahoo_app_id_list[$keyArray];


//楽天APIキー
for($j=1; $j<10; $j++){

	$raku_app = file_get_contents("common/raku_app_id{$j}.txt");//楽天アプリＩＤ
	
	if(isEmpty2($raku_app)==TRUE){
		$raku_app_id_list[] = $raku_app;
	}

}
$keyArray = array_rand($raku_app_id_list,1);
$raku_app_id = $raku_app_id_list[$keyArray];



//VC　api キー
$vc_api_token = file_get_contents("common/vc_api_token.txt");



/*ここから変数処理（アフィリID系）*/

//アマゾン
$amazon_vc_sid = file_get_contents("common/amazon_vc_sid.txt");//バリューコマースsid
$amazon_vc_pid = file_get_contents("common/amazon_vc_pid.txt");//バリューコマースpid

$amazon_a8mat = file_get_contents("common/amazon_a8mat.txt");//a8mat
$amazon_a8_affi = file_get_contents("common/amazon_a8_affi.txt");//a8affi

$amazon_aid  = file_get_contents("common/amazon_aid.txt");//amazonアソシエイトID

$amazon_moshimo_a = file_get_contents("common/amazon_moshimo_a.txt");//もしもa_id
$amazon_moshimo_p = file_get_contents("common/amazon_moshimo_p.txt");//もしもp_id
$amazon_moshimo_pc = file_get_contents("common/amazon_moshimo_pc.txt");//もしもpc_id
$amazon_moshimo_pl = file_get_contents("common/amazon_moshimo_pl.txt");//もしもpl_id


//楽天
$rakuten_linkshare_id = file_get_contents("common/rakuten_linkshare_id.txt");//リンクシェアid
$rakuten_linkshare_offerid = file_get_contents("common/rakuten_linkshare_offerid.txt");//リンクシェアid

$rakuten_a8mat = file_get_contents("common/rakuten_a8mat.txt");//a8mat

$rakuten_aid = file_get_contents("common/rakuten_aid.txt");//楽天アフィリエイトＩＤ

$rakuten_moshimo_a = file_get_contents("common/rakuten_moshimo_a.txt");//もしもa_id
$rakuten_moshimo_p = file_get_contents("common/rakuten_moshimo_p.txt");//もしもp_id
$rakuten_moshimo_pc = file_get_contents("common/rakuten_moshimo_pc.txt");//もしもpc_id
$rakuten_moshimo_pl = file_get_contents("common/rakuten_moshimo_pl.txt");//もしもpl_id

$rakuten_traffic_gate = file_get_contents("common/rakuten_traffic_gate.txt");//楽天トラフィックゲート用


//ヤフーショッピング
$yahoo_vc_sid = file_get_contents("common/yahoo_vc_sid.txt");//バリューコマースsid
$yahoo_vc_pid = file_get_contents("common/yahoo_vc_pid.txt");//バリューコマースpid

$yahoo_shop_sid = file_get_contents("common/yahoo_shop_sid.txt");//ヤフーアフィリsid
$yahoo_shop_pid = file_get_contents("common/yahoo_shop_pid.txt");//ヤフーアフィリpid
$yahoo_hoge_id = file_get_contents("common/yahoo_hoge_id.txt");//ヤフーアフィリHOGE


//ヤフオク
$yahuoku_vc_sid = file_get_contents("common/yahuoku_vc_sid.txt");//バリューコマースsid
$yahuoku_vc_pid = file_get_contents("common/yahuoku_vc_pid.txt");//バリューコマースpid

$yahuoku_shop_sid = file_get_contents("common/yahuoku_shop_sid.txt");//ヤフーアフィリsid
$yahuoku_shop_pid = file_get_contents("common/yahuoku_shop_pid.txt");//ヤフーアフィリpid
$yahuoku_hoge_id = file_get_contents("common/yahuoku_hoge_id.txt");//ヤフーアフィリHOGE



/*

//バリューコマース
//$vc_sid = file_get_contents("common/vc_sid.txt");//バリューコマースsid
$vc_yahoo_s_sid = file_get_contents("common/vc_yahoo_s_sid.txt");//バリューコマース ヤフーショッピングsid
$vc_yahoo_o_sid = file_get_contents("common/vc_yahoo_o_sid.txt");//バリューコマース ヤフーオークションsid
$vc_amazon_sid = file_get_contents("common/vc_amazon_sid.txt");//バリューコマース アマゾンsid


//$vc_pid = file_get_contents("common/vc_pid.txt");//バリューコマースpid（ヤフー）
//$vc_a_pid = file_get_contents("common/vc_a_pid.txt");//バリューコマースpid(アマゾン)
$vc_yahoo_s_pid = file_get_contents("common/vc_yahoo_s_pid.txt");//バリューコマースpid（ヤフーショッピング）
$vc_yahoo_o_pid = file_get_contents("common/vc_yahoo_o_pid.txt");//バリューコマースpid（ヤフーオークション）
$vc_amazon_pid = file_get_contents("common/vc_amazon_pid.txt");//バリューコマースpid（アマゾン）
*/

// 2014年1月29日　更新

	$vc_ponpare_sid = file_get_contents("common/vc_ponpare_sid.txt");//バリューコマース ポンパレsid
	$vc_yamada_sid = file_get_contents("common/vc_yamada_sid.txt");//バリューコマース ヤマダモールsid
	$vc_dena_sid = file_get_contents("common/vc_dena_sid.txt");//バリューコマース Denaショッピングsid
	$vc_mobaoku_sid = file_get_contents("common/vc_mobaoku_sid.txt");//バリューコマース モバオクsid
	$vc_eduon_sid = file_get_contents("common/vc_eduon_sid.txt");//バリューコマース エデュオンsid
	$vc_keizu_sid = file_get_contents("common/vc_keizu_sid.txt");//バリューコマース ケーズデンキsid
	$vc_best_sid = file_get_contents("common/vc_best_sid.txt");//バリューコマース ベスト電気sid
	$vc_nojima_sid = file_get_contents("common/vc_nojima_sid.txt");//バリューコマース ノジマ電気sid

	$ls_sofmap_id = file_get_contents("common/ls_sofmap_id.txt");//リンクシェア ソフマップ電気sid
	$ls_bicamera_id = file_get_contents("common/ls_bicamera_id.txt");//リンクシェア ビックカメラsid
	$ls_kojima_id = file_get_contents("common/ls_kojima_id.txt");//リンクシェア コジマ電気sid



	$vc_ponpare_pid = file_get_contents("common/vc_ponpare_pid.txt");//バリューコマース ポンパレpid
	$vc_yamada_pid = file_get_contents("common/vc_yamada_pid.txt");//バリューコマース ヤマダモールpid
	$vc_dena_pid = file_get_contents("common/vc_dena_pid.txt");//バリューコマース Denaショッピングpid
	$vc_mobaoku_pid = file_get_contents("common/vc_mobaoku_pid.txt");//バリューコマース モバオクpid
	$vc_eduon_pid = file_get_contents("common/vc_eduon_pid.txt");//バリューコマース エデュオンpid
	$vc_keizu_pid = file_get_contents("common/vc_keizu_pid.txt");//バリューコマース ケーズデンキpid
	$vc_best_pid = file_get_contents("common/vc_best_pid.txt");//バリューコマース ベスト電気pid
	$vc_nojima_pid = file_get_contents("common/vc_nojima_pid.txt");//バリューコマース ノジマ電気pid

	$ls_sofmap_offerid = file_get_contents("common/ls_sofmap_offerid.txt");//リンクシェア ソフマップ電気pid
	$ls_bicamera_offerid = file_get_contents("common/ls_bicamera_offerid.txt");//リンクシェア ビックカメラpid
	$ls_kojima_offerid = file_get_contents("common/ls_kojima_offerid.txt");//リンクシェア コジマ電気pid


$site_urls = file_get_contents("common/site_url.txt");//サイトURL


/*ここから定数を設定する*/

//アマゾンのＡＰＩキー
define('AMAZON_ACCESS_KEY',$amazon_access_key);//アマゾンアクセスキー
define('AMAZON_SECRET',$amazon_secret);//アマゾンシークレット

//ヤフオクのＡＰＩキー
define('YAHOO_APP_ID', $yahoo_app_id);//アプリケーションＩＤ

//楽天のAPIキー
define('RAKUTEN_APP_ID', $raku_app_id);//アプリケーションＩＤ

//楽天のAPIキー
define('VC_API_TOKEN', $vc_api_token);//token

/*★ここからアフィリエイトID定数の設定★*/

//アマゾン
define('AMAZON_VC_SID', $amazon_vc_sid);//VCsid
define('AMAZON_VC_PID', $amazon_vc_pid);//VCpid

define('AMAZON_A8MAT', $amazon_a8mat);//a8mat
define('AMAZON_A8_AFFI', $amazon_a8_affi);//a8mat

define('AMAZON_AID', $amazon_aid);//アマゾンアソシエイト

define('AMAZON_MOSHIMO_A', $amazon_moshimo_a);//もしもa_id
define('AMAZON_MOSHIMO_P', $amazon_moshimo_p);//もしもp_id
define('AMAZON_MOSHIMO_PC', $amazon_moshimo_p);//もしもpc_id
define('AMAZON_MOSHIMO_PL', $amazon_moshimo_pl);//もしもpl_id


//楽天
define('RAKUTEN_A8MAT',$rakuten_a8mat);//a8mat

define('RAKUTEN_AID', $rakuten_aid);//楽天アフィリエイト

define('RAKUTEN_MOSHIMO_A', $rakuten_moshimo_a);//もしもa_id
define('RAKUTEN_MOSHIMO_P', $rakuten_moshimo_p);//もしもp_id
define('RAKUTEN_MOSHIMO_PC', $rakuten_moshimo_p);//もしもpc_id
define('RAKUTEN_MOSHIMO_PL', $rakuten_moshimo_pl);//もしもpl_id

define('RAKUTEN_LINKSHARE_ID', $rakuten_linkshare_id);//リンクシェアid
define('RAKUTEN_LINKSHARE_OFFERID', $rakuten_linkshare_offerid);//リンクシェアオファーid

define('RAKUTEN_TRAFFIC_GATE',$rakuten_traffic_gate);//楽天トラフィックゲート


//ヤフーショッピング
define('YAHOO_VC_SID', $yahoo_vc_sid);//VC　sid
define('YAHOO_VC_PID', $yahoo_vc_pid);//VC　pid

define('YAHOO_SHOP_SID', $yahoo_shop_sid);//ヤフーアフィリエイト　sid
define('YAHOO_SHOP_PID', $yahoo_shop_pid);//ヤフーアフィリエイト　pid
define('YAHOO_HOGE_ID', $yahoo_hoge_id);//ヤフーアフィリエイト　hoge


//ヤフオク
define('YAHUOKU_VC_SID', $yahuoku_vc_sid);//VC　sid
define('YAHUOKU_VC_PID', $yahuoku_vc_pid);//VC　pid

define('YAHUOKU_SHOP_SID', $yahuoku_shop_sid);//ヤフーアフィリエイト　sid
define('YAHUOKU_SHOP_PID', $yahuoku_shop_pid);//ヤフーアフィリエイト　pid
define('YAHUOKU_HOGE_ID', $yahuoku_hoge_id);//ヤフーアフィリエイト　hoge




/*一応残すここから
define('RAKUTEN_A8',$rakuten_a8);//楽天
define('YAHOO_SHOP_AID', 'f2LQGVLxbLAtLmubnlKWx5E-');//ヤフーショッピング
define('YAHUOKU_AID', 'auct/p/f2LQGVLxbLAtLmubnlKWx5E-');//ヤフオクアフィリエイトＩＤ
//define('YAHOO_SID', $vc_sid);//ヤフーsid
define('YAHOO_S_SID', $vc_yahoo_s_sid);//ヤフーショッピングsid
define('YAHOO_O_SID', $vc_yahoo_o_sid);//ヤフーオークションsid
define('AMAZON_A_SID', $vc_amazon_sid);//アマゾンsid
//define('YAHOO_PID', $vc_pid);//ヤフーpid
//define('AMAZON_PID', $vc_a_pid);//アマゾンpid
define('YAHOO_S_PID', $vc_yahoo_s_pid);//ヤフーショッピングpid
define('YAHOO_O_PID', $vc_yahoo_o_pid);//ヤフーオークションpid
/*ここまで*/


//2014年1月29日　追加

define('PONPARE_SID', $vc_ponpare_sid);//バリューコマース ポンパレsid
define('YAMADA_SID', $vc_yamada_sid);//バリューコマース ヤマダモールsid
define('DENA_SID', $vc_dena_sid);//バリューコマース Denaショッピングsid
define('MOBAOKU_SID', $vc_mobaoku_sid);//バリューコマース モバオクsid
define('EDUON_SID', $vc_eduon_sid);//バリューコマース エデュオンsid
define('KEIZU_SID', $vc_keizu_sid);//バリューコマース ケーズデンキsid
define('BEST_SID', $vc_best_sid);//バリューコマース ベスト電気sid
define('NOJIMA_SID', $vc_nojima_sid);//バリューコマース ノジマ電気sid

define('SOFMAP_ID', $ls_sofmap_id);//リンクシェア ソフマップ電気sid
define('BICAMERA_ID', $ls_bicamera_id);//リンクシェア ビックカメラsid
define('KOJIMA_ID', $ls_kojima_id);//リンクシェア コジマ電気sid



define('PONPARE_PID', $vc_ponpare_pid);//バリューコマース ポンパレpid
define('YAMADA_PID', $vc_yamada_pid);//バリューコマース ヤマダモールpid
define('DENA_PID', $vc_dena_pid);//バリューコマース Denaショッピングpid
define('MOBAOKU_PID', $vc_mobaoku_pid);//バリューコマース モバオクpid
define('EDUON_PID', $vc_eduon_pid);//バリューコマース エデュオンpid
define('KEIZU_PID', $vc_keizu_pid);//バリューコマース ケーズデンキpid
define('BEST_PID', $vc_best_pid);//バリューコマース ベスト電気pid
define('NOJIMA_PID', $vc_nojima_pid);//バリューコマース ノジマ電気pid

define('SOFMAP_OFFERID', $ls_sofmap_offerid);//リンクシェア ソフマップ電気pid
define('BICAMERA_OFFERID', $ls_bicamera_offerid);//リンクシェア ビックカメラpid
define('KOJIMA_OFFERID', $ls_kojima_offerid);//リンクシェア コジマ電気pid


//その他設定
define("SITE_URL",$site_url);  //サイトのドメインルート（URL）
define("SITE_NAME",$site_name);  //サイトの名前

//エラーレポートの設定（この場合NOTICEは表示しない）
error_reporting(E_ALL&~E_NOTICE & ~E_DEPRECATED);

mb_language("Japanese");
mb_internal_encoding("UTF-8");

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(str_replace('\\', '/', dirname(__FILE__)).'/PEAR/'));

?>