<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: text/html;charset=utf-8"); 
ini_set( 'display_errors', 0 );
include_once("config.php");
require_once("tag.php");

if($_REQUEST["mode"] == "url"){
	$v = $_REQUEST["v"];
	$item = array();
	$item[0] = "http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".AMAZON_VC_SID."&pid=".AMAZON_VC_PID."&vc_url=##url##";
	$item[1] = "http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHOO_SHOP_SID."&pid=".YAHOO_SHOP_PID."&vc_url=##url##";
	$item[2] = "http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHUOKU_VC_SID."&pid=".YAHUOKU_VC_PID."&vc_url=##url##";
	$item[3] = "http://hb.afl.rakuten.co.jp/hgc/".RAKUTEN_AID."/?pc=##url##?scid=af_link_txt&amp;m=";

	if(strlen($v) > 0){
		echo $item[$v];
	}else{
		echo implode("◇",$item);
	}
}elseif($_REQUEST["mode"] == "jan"){
	$keywd = $_REQUEST["keywd"];
	if($_REQUEST["type"] == "jan"){
		echo "http://saiyasusite.com/product2/".$keywd."/";
	}elseif($_REQUEST["type"] == "asin"){
		$include_path= dirname(__FILE__)."/PEAR/";
		ini_set('include_path', $include_path);
		require_once 'Services/Amazon.php';
		$amazon = new Services_Amazon(AMAZON_ACCESS_KEY, AMAZON_SECRET,"vc-22");
		$amazon->setBaseUrl('http://ecs.amazonaws.jp/onca/xml');
		$options = array();
		$options['IdType'] = 'ASIN';
		$options['Version'] = '2010-09-01';
		$options['Condition'] = 'All';
		$options['MerchantId'] = 'All';
		$options['ResponseGroup'] = 'ItemAttributes';
		$response = $amazon->ItemLookup($keywd,$options);
		if(@get_class($response)!="PEAR_Error"){
			$jan = $response["Item"][0]["ItemAttributes"]["EAN"];
		}
		echo "http://saiyasusite.com/product2/".$jan."/";
	}elseif($_REQUEST["type"] == "keywd"){
		echo "http://saiyasusite.com/k.php?keywords=".urlencode($keywd);
	}
	
	
}elseif($_REQUEST["mode"] == "asinjan"){
	$keywd = $_REQUEST["keywd"];
	echo "http://133.242.181.159/amazon_jan_asin/amazon2.php?type=asin&d=".$keywd;
	
}elseif($_REQUEST["mode"] == "janasin"){
	$keywd = $_REQUEST["keywd"];
	echo "http://133.242.181.159/amazon_jan_asin/amazon2.php?d=".$keywd;
}elseif($_REQUEST["mode"] == "amazon"){
echo '<style type="text/css">
<!--
.sample_01{
width: 100%;
border-collapse: collapse;
font-size:10px;
}
.sample_01 th{
width: 25%;
padding: 6px;
text-align: left;
vertical-align: top;
color: #333;
background-color: #eee;
border: 1px solid #b9b9b9;
}
.sample_01 td{
padding: 6px;
background-color: #fff;
border: 1px solid #b9b9b9;
}
//-->
</style>';
	$asin = $_REQUEST["asin"];
	if($asin){
		$include_path= dirname(__FILE__)."/PEAR/";
		ini_set('include_path', $include_path);
		require_once 'Services/Amazon.php';
		$amazon = new Services_Amazon(AMAZON_ACCESS_KEY, AMAZON_SECRET,"vc-22");
		$amazon->setBaseUrl('http://ecs.amazonaws.jp/onca/xml');
		$options = array();
		$options['IdType'] = 'ASIN';
		$options['Version'] = '2010-09-01';
		$options['Condition'] = 'All';
		$options['MerchantId'] = 'All';
		$options['ResponseGroup'] = 'ItemAttributes,OfferFull,SalesRank';
		$response = $amazon->ItemLookup($asin,$options);
		//var_dump($response["Item"]);
		if(@get_class($response)!="PEAR_Error"){
			$jan = $response["Item"][0]["ItemAttributes"]["EAN"];
			
			
			//アマゾンの詳細ページからデータをスクレイピング
			$detail_url = "http://www.amazon.co.jp/dp/";
			$contents = file_get_contents($detail_url.$asin);
			$contents = mb_convert_encoding($contents,'UTF-8','auto');
			$contents = str_replace(array("\r\n","\r","\n"), '', $contents);
			//価格
			$match1 = array();
			preg_match_all('/<span id=\"actualPriceValue\"><b class=\"priceLarge\">￥ (.*?)<\/b><\/span>/', $contents, $match1);
			if($match1[1][0]){
				$amazon_price = str_replace(",","",$match1[1][0]);
			}
			if(!$amazon_price){
				$match1 = array();
				preg_match_all('/<span id=\"priceblock_ourprice\" class=\"a-size-medium a-color-price\">￥ (.*?)<\/span>/', $contents, $match1);
				if($match1[1][0]){
					$amazon_price = str_replace(",","",$match1[1][0]);
				}
			}
			if(!$amazon_price){
				$match1 = array();
				preg_match_all('/<span class=\"a-size-medium a\-color\-price\">￥ (.*?)<\/span>/', $contents, $match1);
				if($match1[1][0]){
					$amazon_price = str_replace(",","",$match1[1][0]);
				}
			}
			//それでも取れない場合
			if(!$amazon_price){
				$amazon_price = $response["Item"][0]["Offers"]["Offer"]["OfferListing"]["Price"]["Amount"];
			}
			//サイズ
			$size = "";
			$match1 = array();
			preg_match_all('/高さ<\/td><td class=\"value\">(.*?)<\/td><\/tr>/', $contents, $match1);
			if($match1[1][0]){
				$size = $match1[1][0];
			}
			if(!$size){
				$match1 = array();
				preg_match_all('/商品の寸法<\/td><td class=\"value\">(.*?)<\/td><\/tr>/', $contents, $match1);
				if($match1[1][0]){
					$size = $match1[1][0];
				}
			}
			if(!$size){
				$match1 = array();
				preg_match_all('/商品の寸法<\/td><td class=\"value\">(.*?)<\/td><\/tr>/', $contents, $match1);
				if($match1[1][0]){
					$size = $match1[1][0];
				}
			}
			if(!$size){
				$match1 = array();
				preg_match_all('/<li><span class=\"a-list-item\">本体サイズ\:(.*?)<\/span><\/li>/', $contents, $match1);
				if($match1[1][0]){
					$size = $match1[1][0];
				}
			}
			//ランキング
			$rank = "";
			$match1 = array();
			preg_match_all('/ベストセラー商品ランキング<\/td><td class=\"value\">(.*?)[\s　]*<tr class=\"shipping-weight\">/', $contents, $match1);
			if($match1[1][0]){
				$rank = trim($match1[1][0]);
			}else{
				$match1 = array();
				preg_match_all('/<b>Amazon ベストセラー商品ランキング\:<\/b> (.*?)[\s　]*<style type=\"text\/css\">/', $contents, $match1);
				if($match1[1][0]){
					$rank = trim($match1[1][0]);
				}else{
					$rank = number_format($response["Item"][0]["SalesRank"]);
				}
			}
			if($rank){
				$match1 = array();
				preg_match_all('/<ul class=\"zg_hrsr\">[\s　]*(.*?)[\s　]*<\/li>[\s　]*<\/ul>/', $contents, $match1);
				if($match1[1][0]){
					$rank .= "<br />".str_replace(array("<li>","</li>",'<li class="zg_hrsr_item">'),"",trim($match1[1][0]));
				}
				$rank = strip_tags($rank);
			}
			//重さ
			$weight = "";
			$match1 = array();
			preg_match_all('/発送重量<\/td><td class="value">(.*?)<\/td><\/tr>/', $contents, $match1);
			if($match1[1][0]){
				$weight = trim($match1[1][0]);
			}
			if(!$weight){
				$match1 = array();
				preg_match_all('/<li><b>発送重量:<\/b> (.*?)<\/li>/', $contents, $match1);
				if($match1[1][0]){
					$weight = trim($match1[1][0]);
				}
			}
			//カテゴリ
			$category = "";
			$match1 = array();
			preg_match_all('/<ul class=\"a-horizontal a-size-small\">(.*?)<\/span><\/li>[\s　]*<\/ul>/', $contents, $match1);
			if($match1[1][0]){
				$category = strip_tags($match1[1][0]);
			}
			//型番
			$kataban = "";
			$match1 = array();
			preg_match_all('/メーカー型番<\/td><td class=\"value\">(.*?)<\/td><\/tr>/', $contents, $match1);
			if($match1[1][0]){
				$kataban = $match1[1][0];
			}
			
			
			//取り扱い開始日
			$hi = "";
			$hi_title = "";
			$match1 = array();
			preg_match_all('/Amazon.co.jp での(.*?)<\/td><td class=\"value\">(.*?)<\/td><\/tr>/', $contents, $match1);
			if($match1[1][0]){
				$hi = $match1[2][0];
				$hi_title = $match1[1][0];
			}
			if(!$hi){
				$match1 = array();
				preg_match_all('/<li><b> 発売日：<\/b>(.*?)<\/li>/', $contents, $match1);
				if($match1[1][0]){
					$hi = $match1[1][0];
					$hi_title = "発売日";
				}
			}
			if(!$hi){
				$match1 = array();
				preg_match_all('/<li><b> Amazon.co.jp での(.*?)<\/b>(.*?)<\/li>/', $contents, $match1);
				if($match1[1][0]){
					$hi = $match1[2][0];
					$hi_title = "取り扱い開始日";
				}
			}
			
			if(!$hi){
				$match1 = array();
				preg_match_all('/<span class=\"a-color-state\">発売予定日は(.*?)です。<\/span>/', $contents, $match1);
				if($match1[1][0]){
					$hi = $match1[1][0];
					$hi_title = "発売予定日";
				}
			}
			if(!$hi){
				$match1 = array();
				preg_match_all('/<span class=\"a-size-medium a-color-state\">[\s　]*発売予定日は(.*?)です。[\s　]*<\/span>/', $contents, $match1);
				if($match1[1][0]){
					$hi = $match1[1][0];
					$hi_title = "発売予定日";
				}
			}
			//出品者数（新品）
			$fba_new = $response["Item"][0]["OfferSummary"]["TotalNew"];
			//出品者数（中古）
			$fba_used = $response["Item"][0]["OfferSummary"]["TotalUsed"];
			
			$list = "<table  class=\"sample_01\">";
			$list .= "<tr><th colspan=\"2\" align=\"center\">".$response["Item"][0]["ItemAttributes"]["Title"]."</th></tr>";
			if($kataban){$list .= "<tr><td>型番</td><td>".$kataban."</td></tr>";}
			$list .= "<tr><td align=\"left\">JAN</td><td align=\"right\">".$jan."</td></tr>";
			$list .= "<tr><th colspan=\"2\" align=\"center\">比較サイト参照</th></tr>";
			$list .= "<tr><td colspan=\"2\"><a href=\"http://so-bank.jp/detail/?code=".$asin."\" target=\"_blank\">プライスチェックで確認</a></td></tr>";
			$list .= "<tr><td colspan=\"2\"><a href=\"http://mnrate.com/past.php?kwd=".$asin."&i=All\" target=\"_blank\">モノレートで確認</a></td></tr>";
			$list .= "<tr><td colspan=\"2\"><a href=\"https://sellercentral.amazon.co.jp/gp/fba/revenue-calculator/index.html/ref=im_xx_cont_xx?ie=UTF8&lang=ja_JP&searchString=".$asin."\" target=\"_blank\">料金シュミレータで確認</a></td></tr>";
			if($rank){$list .= "<tr><td>アマゾン内ランキング</td><td>".$rank."</td></tr>";}
			if($category){$list .= "<tr><td>ジャンル・カテゴリ</td><td>".$category."</td></tr>";}
			if($fba_new){$list .= "<tr><td>FBA出品者数（新品）</td><td>".$fba_new."</td></tr>";}
			if($fba_used){$list .= "<tr><td>FBA出品者数（中古）</td><td>".$fba_used."</td></tr>";}
			
			$list .= "<tr><th align=\"left\">通販ショップ名</th><th align=\"left\">価格</th></tr>";
			$list .= "<tr><td>Amazon.co.jp</td><td>\\".number_format($amazon_price)." (\\0)</td></tr>";
			$plist = Get_Ranking($jan,$amazon_price);
			if($plist){
				foreach($plist as $key => $val){
					//var_dump($val);
					if(($val["price"]-$amazon_price) >= 0){
						$list .= "<tr><td><a href=\"".$val["vc_link"]."\" target=\"_blank\">".$val["subStoreName"]."</a></td><td>\\".number_format($val["price"])." (\\".number_format(round(($val["price"]-$amazon_price))).")</td></tr>";
					}else{
						$list .= "<tr><td><a href=\"".$val["vc_link"]."\" target=\"_blank\">".$val["subStoreName"]."</a></td><td>\\".number_format($val["price"])." (<span style=\"color:red;\">\\".number_format(round(($val["price"]-$amazon_price)))."</span>)</td></tr>";
						
					}
				}
			}
			//キーフレーズを取得
			$phrase = explode(",",Insert_tag2($response["Item"][0]["ItemAttributes"]["Title"]));
			
			$list .= "<tr><th colspan=\"2\" align=\"center\">ヤフオク検索（出品中）</th></tr>";
			if($jan){$list .= "<tr><td colspan=\"2\"><a href=\"http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHUOKU_VC_SID."&pid=".YAHUOKU_VC_PID."&vc_url=".urlencode("http://auctions.search.yahoo.co.jp/search?auccat=&p=".$jan)."\" target=\"_blank\">".$jan."</a></td></tr>";}
			if($kataban){$list .= "<tr><td colspan=\"2\"><a href=\"http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHUOKU_VC_SID."&pid=".YAHUOKU_VC_PID."&vc_url=".urlencode("http://auctions.search.yahoo.co.jp/search?auccat=&p=".$kataban)."\" target=\"_blank\">".$kataban."</a></td></tr>";}
			if($phrase[0]){$list .= "<tr><td colspan=\"2\"><a href=\"http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHUOKU_VC_SID."&pid=".YAHUOKU_VC_PID."&vc_url=".urlencode("http://auctions.search.yahoo.co.jp/search?auccat=&p=".$phrase[0])."\" target=\"_blank\">".$phrase[0]."</a></tr>";}
			if($phrase[1]){$list .= "<tr><td colspan=\"2\"><a href=\"http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHUOKU_VC_SID."&pid=".YAHUOKU_VC_PID."&vc_url=".urlencode("http://auctions.search.yahoo.co.jp/search?auccat=&p=".$phrase[1])."\" target=\"_blank\">".$phrase[1]."</a></td></tr>";}

			
			
			$list .= "<tr><th colspan=\"2\" align=\"center\">ヤフオク検索（落札済）</th></tr>";
			if($jan){$list .= "<tr><td colspan=\"2\"><a href=\"http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHUOKU_VC_SID."&pid=".YAHUOKU_VC_PID."&vc_url=".urlencode("http://closedsearch.auctions.yahoo.co.jp/jp/closedsearch?p=".$jan)."\" target=\"_blank\">".$jan."</a></td></tr>";}
			if($kataban){$list .= "<tr><td colspan=\"2\"><a href=\"http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHUOKU_VC_SID."&pid=".YAHUOKU_VC_PID."&vc_url=".urlencode("http://closedsearch.auctions.yahoo.co.jp/jp/closedsearch?p=".$kataban)."\" target=\"_blank\">".$kataban."</a></td></tr>";}
			if($phrase[0]){$list .= "<tr><td colspan=\"2\"><a href=\"http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHUOKU_VC_SID."&pid=".YAHUOKU_VC_PID."&vc_url=".urlencode("http://closedsearch.auctions.yahoo.co.jp/jp/closedsearch?p=".$phrase[0])."\" target=\"_blank\">".$phrase[0]."</a></tr>";}
			if($phrase[1]){$list .= "<tr><td colspan=\"2\"><a href=\"http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHUOKU_VC_SID."&pid=".YAHUOKU_VC_PID."&vc_url=".urlencode("http://closedsearch.auctions.yahoo.co.jp/jp/closedsearch?p=".$phrase[1])."\" target=\"_blank\">".$phrase[1]."</a></td></tr>";}
			
			if($weight || $size || $hi){
				$list .= "<tr><th colspan=\"2\" align=\"center\">登録詳細情報</th></tr>";
			}
			if($weight){$list .= "<tr><td>重量</td><td>".$weight."</td></tr>";}
			if($size){$list .= "<tr><td>サイズ</td><td>".$size."</td></tr>";}
			if($hi){$list .= "<tr><td>".$hi_title."</td><td>".$hi."</td></tr>";}
			$list .= "</table>";
			echo $list;
		}else{
			//APIエラー
			echo "商品情報が取得できませんでした。<br />";
			echo "ページを再読み込みして見てください。";
			echo "<p><a href=\"chrome.php?mode=amazon&asin=".$asin."\">更新</a></p>";
		}
		
	}else{
		//ASIN情報が取得できなかった場合
		echo "商品情報が取得できませんでした。<br />";
		echo "ページを再読み込みして見てください。";
		echo "<p><a href=\"chrome.php?mode=amazon&asin=".$asin."\">更新</a></p>";
	}
		
}
exit;
//入力されているかチェック
function isEmpty($vali){
	
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
function Get_Ranking($jan,$amazon_price){
	global $raku_app_id,$yahoo_app_id;
	
	$all_item_list = array();//すべての商品をまずはつっこむ用
	$ranking_list = array();//1位～20位までのランキングリスト用
	//------------------------------------------▼▼▼バリュコマ▼▼▼--------------------------------------------
	$vc_token = VC_API_TOKEN;
	$base_url = "http://webservice.valuecommerce.ne.jp/productdb/search?token={$vc_token}&product_id={$jan}&format=json&results_per_page=50&page=1&sort_by=price&sort_order=asc";

	$vc_api_response = file_get_contents($base_url);
	$vc_api_response = json_decode($vc_api_response);
	//一件でも結果があれば
	if($vc_api_response->items){
		$a = 0;
		foreach ($vc_api_response->items as $key => $value) {
			//var_dump($value);
			$value= (array)$value;//オブジェクトを配列に
				
			if($value['price']!=0){
				
				$all_item_list[$a]['title'] = $value['title'];//商品名
				$all_item_list[$a]['vc_link'] = $value['link'];//VCのアフィリリンクURL
				$all_item_list[$a]['detail_page_url'] = $value['guid'];//詳細ページURL（アフィリリンクなし）
				
				if($value['merchantName']=="Yahoo!ショッピング（ヤフー ショッピング）"){
					$value['merchantName']=str_replace("（ヤフー ショッピング）","",$value['merchantName']);
					//var_dump($value);
					$store_id = str_replace("store-","",$value['subStoreId']);
					$yurl = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch?appid=".$yahoo_app_id."&store_id=".$store_id."";
					//echo $yurl;
				    $yxml = simplexml_load_file($yurl);
				    if ($yxml["totalResultsReturned"] != 0) {//検索件数が0件でない場合,変数$hitsに検索結果を格納します。
				        $hits = $yxml->Result->Hit;
				        $value['subStoreName'] = $hits->Store->Name."（Yahoo!ショッピング）";
				        //var_dump($hits);
				    }
					
					
					
				}
				
				if($value['ecCode']=="038p6"){
					$is_amazon_flag = 1;		
				}
				
				$all_item_list[$a]['merchantName'] = $value['merchantName'];//モール名
				$all_item_list[$a]['ecCode'] = $value['ecCode'];//モールコード
		
				if(isEmpty($value['subStoreName'])){
					$all_item_list[$a]['subStoreName'] = $value['subStoreName'];//ショップ名
				}else{
					$all_item_list[$a]['subStoreName'] = $value['merchantName'];//ショップ名がない場合モール名をショップにいれる。			
				}
				
				//もしアマゾンなら表記を変える
				if($all_item_list[$a]['subStoreName']=="Amazon.co.jp通販サイト(アマゾン)"){
					$all_item_list[$a]['subStoreName']="Amazon";
					$value['price']=$amazon_price;
				}
				
				if($value['ecCode']=="038p6" && isEmpty($value['subStoreName'])==FALSE && $value['merchantName']==FALSE){
					$all_item_list[$a]['subStoreName'] = "Amazon";	
				}
				
				$all_item_list[$a]['image'] = $value['imageLarge']->url;//画像
				$all_item_list[$a]['price'] = $value['price'];//価格
				
				//画像largeの情報があれば（代表）を入れる処理（一回のみ）
				if(isEmpty($value['imageLarge']->url) && $product_flag_img==1){
				
					$product_image = $value['imageLarge'];//画像（オブジェクトでurl,width,heightがある）
					$product_flag_img = 2;//一回入れればあとはOK
				
				}//if(isEmpty($value['imageLarge']->url && $image_large_flag==1))
				
				//画像smallの情報があれば（代表）を入れる処理（一回のみ）
				if(isEmpty($value['imageSmall']->url) && $product_flag_img==1){
				
					$product_image = $value['imageSmall'];//画像（オブジェクトでurl,width,heightがある）
					$product_flag_img = 2;//一回入れればあとはOK
				
				}//if(isEmpty($value['imageLarge']->url && $image_large_flag==1))
				
				//画像の情報があれば（代表）を入れる処理（一回のみ）
				if(isEmpty($value['imageFree']->url) && $product_flag_img==1){
				
					$product_image = $value['imageFree'];//画像（オブジェクトでurl,width,heightがある）
					$product_flag_img = 2;//一回入れればあとはOK
				
				}//if(isEmpty($value['imageLarge']->url && $image_large_flag==1))
				
				
				//タイトルの情報があれば（代表）を入れる処理（一回のみ）
				if(isEmpty($value['title']) && $product_flag_title==1){
					
					$product_title = $value['title'];//商品名
					$product_flag_title = 2;//一回入れればあとはOK
				
				}//if(isEmpty($value['imageLarge']->url && $image_large_flag==1))
				
				
				//アフィリリンクの情報があれば（代表）を入れる処理（一回のみ）
				if(isEmpty($value['link']) && $product_flag_link==1){
					
					$product_vc_link = $value['link'];//VCのアフィリリンク
					$product_flag_link = 2;//一回入れればあとはOK
				
				}//if(isEmpty($value['imageLarge']->url && $image_large_flag==1))
				
				
				//通常詳細ページの情報があれば（代表）を入れる処理（一回のみ）
				if(isEmpty($value['guid']) && $product_flag_guid==1){
					
					$product_url = $value['guid'];//詳細ページURL
					$product_eccode = $value['ecCode'];//モールコード
					$product_merchantname = $value['merchantName'];//モール名
					$product_flag_guid = 2;//一回入れればあとはOK
				
				}//if(isEmpty($value['imageLarge']->url && $image_large_flag==1))
				
				
				$a++;
			
			}//if($value['price']!=0)
				
		}//foreach ($vc_api_response->Items as $key => $value) 
		
	}//if($vc_api_response->Items)

	//------------------------------------------▼▼▼楽天市場▼▼▼--------------------------------------------

	//apiを使うためのアプリＩＤとアフィリエイトＩＤを読み込み
	$raku_affili_id = file_get_contents("common/raku_affili_id.txt");


	//クエリーの為にエンコード

	$rakuten_base_url="https://app.rakuten.co.jp/services/api/IchibaItem/Search/20130805?applicationId={$raku_app_id}&keyword={$jan}&genreId={$node_id}&hits=20&page={$page}&affiliateId={$raku_affili_id}&format=json&availability=0";
//echo $rakuten_base_url;
	$rakuten_xml = file_get_contents($rakuten_base_url);

	//json形式をデコードして配列に
	$rakuten_xml = json_decode($rakuten_xml,true);

	//アイテムの部分を変数につっこむ
	$rakuten_response = $rakuten_xml['Items'];

	foreach($rakuten_response as $rakuten_value){
		
		if(stristr($rakuten_value['Item']['shopName'],"Joshin")===FALSE){
			//var_dump($rakuten_value);
			$all_item_list[$a]['title'] = $rakuten_value['Item']['itemName'];//商品名
			$all_item_list[$a]['vc_link'] = $rakuten_value['Item']['affiliateUrl'];//商品詳細ＵＲＬ（アフィリ）
			$all_item_list[$a]['detail_page_url'] = $rakuten_value['Item']['itemUrl'];//商品詳細ＵＲＬ（通常）
			$all_item_list[$a]['merchantName'] = "楽天市場";//モール名
			$all_item_list[$a]['ecCode'] = "rakuten";//モールコード
			$all_item_list[$a]['subStoreName'] = $rakuten_value['Item']['shopName']."（楽天市場）";//店名
			$all_item_list[$a]['image'] = $rakuten_value['Item']['mediumImageUrls'][0]['imageUrl'];//画像
			$all_item_list[$a]['price'] = $rakuten_value['Item']['itemPrice'];//価格
			
			
			//画像の情報があれば商品の情報（代表）を入れる処理（一回のみ）
			if(isEmpty($rakuten_value['Item']['mediumImageUrls'][0]['imageUrl']) && $product_flag_img == 1){
					
				$product_image->url = $rakuten_value['Item']['mediumImageUrls'][0]['imageUrl'];//画像（中）
				$product_flag_img = 2;//一回入れればあとはOK
				
			}//if(isEmpty($value['imageLarge']->url && $image_large_flag==1))
			
			//商品名の情報があれば商品の情報（代表）を入れる処理（一回のみ）
			if(isEmpty($rakuten_value['Item']['itemName']) && $product_flag_title == 1){
					
				$product_title = $rakuten_value['Item']['itemName'];//商品名
				$product_flag_title = 2;//一回入れればあとはOK
				
			}//if(isEmpty($value['imageLarge']->url && $image_large_flag==1))
			
			//URLの情報があれば商品の情報（代表）を入れる処理（一回のみ）
			if(isEmpty($rakuten_value['Item']['itemUrl']) && $product_flag_guid==1){
					
				$product_vc_link = $rakuten_value['Item']['affiliateUrl'];//詳細ページＵＲＬ(afilli)
				$product_url = $rakuten_value['Item']['itemUrl'];//詳細ページＵＲＬ
				$product_merchantname = "楽天";//モール名
				$product_eccode = "rakuten";//モールコード
				$product_flag_guid = 2;//一回入れればあとはOK
				
			}//if(isEmpty($value['imageLarge']->url && $image_large_flag==1))
			$a++;
			
		}//(stristr($rakuten_value['Item']['shopName'],"Joshin")===FALSE)
		
	}//foreach($rakuten_response as $rakuten_value)
//var_dump($all_item_list);
	//全商品リストを並べ替え
	$foo="";
	foreach($all_item_list as $key => $value){
		$foo[$key] = $value["price"];
	}
	@array_multisort($foo,SORT_ASC,$all_item_list);
	
	for($b=0; $b<20; $b++){
		
		//結果が入っていれば
		if($all_item_list[$b]){
			$ranking_list[$b]=$all_item_list[$b];
		}else{
			//結果がなければループを抜ける
			break;
		}//if($all_item_list[$b])
		
	}//for($b=0; $b<20; $b++)
	
	return $ranking_list;
	
}

?>