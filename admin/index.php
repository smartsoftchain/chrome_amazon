<?php
header("Content-Type: text/html;charset=utf-8"); 
ini_set("memory_limit", "1024M");
ini_set('max_execution_time', '360000');
ini_set( 'display_errors', 1 );
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set( "log_errors", "On" );
//ini_set( "error_log", "./log/".date("Y-m-d")."_php.log" );

$act = (isset($_REQUEST['act'])) ? $_REQUEST['act'] : "";

session_start();
$data = array();



$path = "./inc";
	require_once($path."/conf.php");
	require_once($path."/my_db.inc");
	require_once($path."/htmltemplate.inc");
	require_once($path."/errlog.inc");
	

$DB_URI = array("host" => $DB_SV, "db" => $DB_NAME, "user" => $DB_USER, "pass" => $DB_PASS);
define("SITE_INFO", "総合管理画面");


$data["admin"]["title"] = SITE_INFO;


define("SCRIPT_ENCODING", "UTF-8");
// データベースの漢字コード
define("DB_ENCODING", "UTF-8");
// メールの漢字コード(UTF-8かJIS)
define("MAIL_ENCODING", "JIS");


//サイドメニュー
$menu = array(
	array("title" => "サイト管理"),
	array("act" => "top", "title" => "管理画面TOP"),
	array("act" => "key_list", "title" => "登録キー管理"),
	array("act" => "key_regist", "title" => "キー登録"),
	array("act" => "setup", "title" => "管理者情報変更"),

	array("act" => "logout", "title" => "ログアウト"),
);
$data["menu"] = $menu;


//キータイプ
$keytype_list = array(
	"0"=>"楽天",
	"1"=>"Yahoo",
	"2"=>"Amazon"
);


// --------------------------------
// 各ページの処理

$html = &htmltemplate::getInstance();

/*--------------------------------*/
if($act == "logout"){
	$_SESSION = array();
	session_destroy();
	$act = "login";
}

/*----------------------------

セッションが切れていたらログインページへ

--------------------------------*/

if(!isset($_SESSION["ADMIN_LOGIN"])){
	$act = "login";
}
/*----------------------------

act = login　ログイン

--------------------------------*/

if($act == "login"){
	if ($_REQUEST["id"] && $_REQUEST["passwd"]) {
		$id = htmlspecialchars($_REQUEST["id"]);
		$passwd = htmlspecialchars($_REQUEST["passwd"]);
		
		$inst = DBConnection::getConnection($DB_URI);
		//ログイン情報取得
		$sql = "select * from `admin` where `login_id`='".$_REQUEST["id"]."' and `login_pw`='".$_REQUEST["passwd"]."'";

		$ret = $inst->search_sql($sql);
		if($ret["count"] > 0){
			
				$_SESSION["ADMIN_LOGIN"] = $ret["data"][0];
				$login_id = $ret["data"][0]["login_id"];
				$login_pw = $ret["data"][0]["login_pw"];

				$act="top";
			
		}else{
			$data["message"] = "ログインできません。IDとパスワードを確認してください。";
		}
	}
	if($act == "login"){
		$html->t_include("login.html", $data);
		exit;
	}
}
/*----------------------------

act = 管理者情報更新

--------------------------------*/

if($act == "setup"){
	$inst = DBConnection::getConnection($DB_URI);
	if($_REQUEST["mode"] == "update"){
		$login_id=$_REQUEST["login_id"];
		$login_pw = $_REQUEST["login_pw"];
		$sql = "update `admin` set `login_id`='".$login_id."',`login_pw`='".$login_pw."' where `id`=1";
		$ret = $inst->db_exec($sql);
		$data["error"] = "更新しました。";
	}
	
	$sql = "select * from `admin` where id=1";
	$ret = $inst->search_sql($sql);
	if($ret["count"] > 0){
		$data["form"] = $ret["data"][0];
	}
	$html->t_include("setup.html", $data);
	exit;
}
/*----------------------------

act = ユーザー削除

--------------------------------*/

if($act == "key_del"){
	$id = $_REQUEST["id"];
	$inst = DBConnection::getConnection($DB_URI);

	$sql = "delete from `api` where `id`=".$id."";
	$inst->db_exec($sql);
	//exec("rm -rf ../logs/".$id."");
	
	exit;
}


/*----------------------------

act = キー編集

--------------------------------*/

if($act == "key_update"){
	$id = $_REQUEST["id"];
	$data["id"] = $id;
	$inst = DBConnection::getConnection($DB_URI);
	if($_REQUEST["mode"]=="update"){
		$type = $_REQUEST["type"];
		$key = $_REQUEST["key"];
		$secret = $_REQUEST["secret"];
		$sql = "select * from `api` where `key`='".$key."'";
		$ret = $inst->search_sql($sql);
		if($ret["count"] > 0){
			$data["message"] = "このキーは登録済みです。";
		}else{
			$sql = "update `api` set `type`='".$type."',`key`='".$key."',`secret`='".$secret."' where `id`=".$id."";
			$inst->db_exec($sql);
			$data["message"] = "更新しました。";
		}
	}
	$sql = "select * from `api` where `id`=".$id."";
	$ret = $inst->search_sql($sql);
	if($ret["count"] > 0){
		$form = $ret["data"][0];
		$data["type".$form["type"]] = "selected";
		$data["form"] = $form;
		if($form["type"] == 0){
			$data["raku"] = "on";
		}elseif($form["type"] == 1){
			$data["yahoo"] = "on";
		}elseif($form["type"] == 2){
			$data["amazon"] = "on";
		}
	}
	
	$html->t_include("key_update.html", $data);
	exit;
}




/*----------------------------

act = キーリスト

--------------------------------*/

if($act == "key_list"){
	
	$inst = DBConnection::getConnection($DB_URI);
	
	$maxpage = 20;
	$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$start = ($page-1)*$maxpage;
	if($start<0){$start=0;}
	$limit = " limit ".$start.",".$maxpage;
	
	$where = "where 1";
	if($_REQUEST["keyword"]){
		$where .= " and `name` LIKE '%".$keyword."%'";
	}
	
	$list = array();
	$sql = "select * from `api` ".$where." order by `id` desc";
	$ret = $inst->search_sql($sql.$limit);
	if($ret["count"] > 0){
		foreach($ret["data"] as $key => $val){
			if($val["type"] == 0){
				$val["secret"] = $val["aid"];
			}elseif($val["type"] == 1){
				$val["secret"] = $val["sid"]."/".$val["pid"];
			}
			$val["type"] = $keytype_list[$val["type"]];
			$list[] = $val;
		}
	}
	$data["list"] = $list;
	
	$data_count = 0;
	$ret2 = $inst->search_sql($sql);
	//全データ件数取得
	$data_count = $ret2["count"];
	$data["cnt"] = $data_count;

	$page_count = ceil($data_count / $maxpage);

	$data["pagingstring"] = Paging ((int)$page,"user_list",(int)$page_count);
	
	
	
	$html->t_include("key_list.html", $data);
	exit;
}


/*----------------------------

act = キーー登録

--------------------------------*/

if($act == "key_regist"){
	$inst = DBConnection::getConnection($DB_URI);
	if($_REQUEST["mode"]=="new"){
		$type = $_REQUEST["type"];
		$key = $_REQUEST["key"];
		$secret = $_REQUEST["secret"];
		$aid = $_REQUEST["aid"];
		$sid = $_REQUEST["sid"];
		$pid = $_REQUEST["pid"];
		$sql = "select * from `api` where `key`='".$key."'";
		$ret = $inst->search_sql($sql);
		if($ret["count"] > 0){
			$data["message"] = "このキーは登録済みです。";
		}else{
			$sql = "insert into `api`(`type`,`key`,`secret`,`aid`,`sid`,`pid`) values('".$type."','".$key."','".$secret."','".$aid."','".$sid."','".$pid."')";
			$inst->db_exec($sql);
			$data["message"] = "登録しました。";
		}
	}
	
	//楽天aid,Yahoo SID,PIAがあれば取得して表示
	$sql = "select `aid` from `api` where `aid` <> '' limit 1";
	$ret = $inst->search_sql($sql);
	if($ret["count"] > 0){
		$data["aid"] = $ret["data"][0]["aid"];
	}
	$sql = "select `sid`,`pid` from `api` where `sid` <> '' limit 1";
	$ret = $inst->search_sql($sql);
	if($ret["count"] > 0){
		$data["sid"] = $ret["data"][0]["sid"];
		$data["pid"] = $ret["data"][0]["pid"];
	}
	
	
	$html->t_include("key_regist.html", $data);
	exit;
}

/*----------------------------

act =  TOP一覧画面

--------------------------------*/


$html->t_include("top.html", $data);
exit;

function Paging ($page,$act,$page_count,$para=""){

	$pagingstring = "";
	if ($page > 1) {
		$pagingstring .= "<span class=\"pre\"><a rel=\"next\" href=\"./?act=".$act."&page=".strval($page - 1)."".$para."\" title=\"前のページへ\">&laquo;前のページへ</a></span>";
		for ($i = 5; $i >= 1; $i--) {
			if ($page - $i >= 1) {
				$pagingstring .= "<span class=\"num\"><a href=\"./?act=".$act."&page=".strval($page - $i)."".$para."\" >".strval($page - $i)."</a></span>";
			}
		}
	}
	$pagingstring .= "<span class=\"num\">".strval($page)."</span>";
	if ($page < $page_count) {
		for ($i = 1; $i <= 5; $i++) {
			if ($page + $i <= $page_count) {
				$pagingstring .= "<span class=\"num\"><a href=\"./?act=".$act."&page=".strval($page + $i)."".$para."\">".strval($page + $i)."</a></span>";
			}
		}
		$pagingstring .= "<span class=\"next\"><a rel=\"next\" href=\"./?act=".$act."&page=".strval($page + 1)."".$para."\" title=\"次のページへ\">次のページへ&raquo;</a></span>";
	}
	return $pagingstring;
}


function download_csv2($data, $filename,$top){
	header("Content-disposition: attachment; filename=" . $filename);
	header("Content-type: text/x-csv; charset=Shift_JIS");
	echo $fp,mb_convert_encoding(implode(",", $top), "Shift_Jis", "utf-8") . "\r\n";
	foreach ($data as $val) {
		$csv = array();
		foreach ($val as $item) {
			array_push($csv, $item);
		}
		echo mb_convert_encoding(implode(",", $csv), "Shift_Jis", "utf-8") . "\r\n";
	}
	exit;
}
?>