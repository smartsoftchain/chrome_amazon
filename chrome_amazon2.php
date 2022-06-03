<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: text/html;charset=utf-8"); 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set( 'display_errors', 1 );

//アマゾンアソシエイトタグ//
$amazon_ast = "research-amazon-22";
//アマゾンアソシエイトタグ//

//海外アマゾンアソシエイトタグ//
$amazon_ast_kaigai = "research-amazon-22";
//海外アマゾンアソシエイトタグ//

?>
<html>
	<head>
<style type="text/css">
<!--
table {
	border: #d0d0d0 1px solid;
	border-collapse: collapse;
	width:720px;
}
table th {
	padding:5px;
	background-color:#d9d9d9;
}
table td{
	border: #d0d0d0 1px solid;
	padding:5px;
}
//-->
</style>
<script type="text/javascript">
function iframeResize(){
	var PageHight = document.body.scrollHeight + 0;
	window.parent.document.getElementById('disps').style.height = PageHight + 'px';
}
window.onload = iframeResize;
</script>
	</head>
<body>

<?php

$srror_str = "";
$error = 0;


for($j=21; $j<100; $j++){
	$amazon_app = file_get_contents("common/amazon_access_key{$j}.txt");//アマゾンアクセスキー
	$amazon_app_sec = file_get_contents("common/amazon_secret{$j}.txt");//アマゾンシークレット
	$amazon_app_id_list[$j] = $amazon_app;
	$amazon_app_sec_list[$j]= $amazon_app_sec;
}
$keyArray = array_rand($amazon_app_id_list,1);
$awsk = $amazon_app_id_list[$keyArray];
$awss = $amazon_app_sec_list[$keyArray];

$awsk = $amazon_app_id_list[$keyArray];
$awss = $amazon_app_sec_list[$keyArray];



	$mode = $_REQUEST["mode"];
	$asin = $_REQUEST["asin"];
	
	$countries = $_REQUEST["countries"];
	$countries_used = $_REQUEST["countries_used"];

	
	$str = "";
	if($mode == "amazon"){
		
		$amazon = SearchAmazon($asin);
		
		//var_dump($amazon);
		if($amazon["ItemAttributes"]["EAN"]){
			//各国の情報
			
			//if($_REQUEST["us"] == "on" or $_REQUEST["us_used"] == "on" ){
				$us = SearchAmazon_c($amazon["ItemAttributes"]["EAN"],"us");
			//}
			//if($_REQUEST["ca"] == "on" or $_REQUEST["ca_used"] == "on" ){
				$ca = SearchAmazon_c($amazon["ItemAttributes"]["EAN"],"ca");
			//}
			//if($_REQUEST["uk"] == "on" or $_REQUEST["uk_used"] == "on" ){
				$uk = SearchAmazon_c($amazon["ItemAttributes"]["EAN"],"uk");
			//}
			//if($_REQUEST["fr"] == "on" or $_REQUEST["fr_used"] == "on" ){
				$fr = SearchAmazon_c($amazon["ItemAttributes"]["EAN"],"fr");
			//}
			//if($_REQUEST["de"] == "on" or $_REQUEST["de_used"] == "on" ){
				$dp = SearchAmazon_c($amazon["ItemAttributes"]["EAN"],"dp");
			//}
			

			
			//$amazon2 = Get_Ranking($amazon["ItemAttributes"]["EAN"],$amazon["OfferSummary"]["LowestNewPrice"]["Amount"]);
			$str = "<table>";
			//if($amazon2){
				
				$amazon_str .= "<tr><td colspan=\"3\">JAN[ ".$amazon["ItemAttributes"]["EAN"]." ]</td><td colspan=\"3\">ASIN[ ".$asin." ]</td></tr>";
				$amazon_str .= "<tr><td colspan=\"2\"><a href=\"http://www.amazon.co.jp/gp/offer-listing/".$asin."/ref=olp_f_new?ie=UTF8&f_new=true\" target=\"_blank\">Amazon新品最安値</a></td><td align=\"left\">".number_format($amazon["OfferSummary"]["LowestNewPrice"]["Amount"])."</td>";
				$amazon_str .= "<td colspan=\"2\"><a href=\"http://www.amazon.co.jp/gp/offer-listing/".$asin."/ref=olp_f_used?ie=UTF8&f_used=true&f_usedAcceptable=true&f_usedGood=true&f_usedLikeNew=true&f_usedVeryGood=true\" target=\"_blank\">Amazon中古最安値</a></td><td align=\"left\">".number_format($amazon["OfferSummary"]["LowestUsedPrice"]["Amount"])."</td></tr>";
				$str .= $amazon_str;
			//}else{
				//$error = 1;
				//$srror_str .= "jp-amazon-rank:error<br />";
			//}
			$str .= "</table>";
		}else{
			$error = 1;
			$srror_str .= "jp-amazon-get:error<br />";
			
		}
		
		//アマゾンの詳細ページからデータをスクレイピング
			$detail_url = "http://www.amazon.co.jp/dp/";
			$contents = file_get_contents($detail_url.$asin);
			$contents = mb_convert_encoding($contents,'UTF-8','auto');
			$contents = str_replace(array("\r\n","\r","\n"), '', $contents);
			
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
			
			//それでも取れない場合→APIからのものを使用する
			if(!$amazon_price){
				$amazon_price = $amazon["Offers"]["Offer"][0]["OfferListing"]["Price"]["Amount"];
			}
			
			//カテゴリ
			$category = "";
			$match1 = array();
			preg_match_all('/<ul class=\"a-horizontal a-size-small\">(.*?)<\/span><\/li>[\s　]*<\/ul>/', $contents, $match1);
			if($match1[1][0]){
				$category = strip_tags($match1[1][0]);
			}
			//ランキング
			$rank = "";
			$match1 = array();
			preg_match_all('/ベストセラー商品ランキング<\/td><td class=\"value\">(.*?)[\s　]*<tr class=\"shipping-weight\">/', $contents, $match1);
			if($match1[1][0]){
				$rank = trim($match1[1][0]);
			
			}else{
				$match1 = array();
				preg_match_all('/<b>Amazon ベストセラー商品ランキング\:<\/b>[\s　]* (.*?)位/', $contents, $match1);
				if($match1[1][0]){
					$rank = trim($match1[1][0])."位";
				}else{
					//$rank = number_format($response["Item"][0]["SalesRank"]);
				}
			}
				
			if(strlen($rank) == 0){
				$match1 = array();
				preg_match_all('/Amazon 売れ筋ランキング\:.*?[\s　]*(.*?)[\s　]*style/is', $contents, $match1);
				if($match1[1][0]){
					$rank = trim($match1[1][0]);
				
				}
			}
			//echo "<!--".$rank."-->";
			if($rank){
				$match1 = array();
				preg_match_all('/<ul class=\"zg_hrsr\">[\s　]*(.*?)[\s　]*<\/li>[\s　]*<\/ul>/', $contents, $match1);
				if($match1[1][0]){
					$rank .= "<br />".str_replace(array("<li>","</li>",'<li class="zg_hrsr_item">'),"",trim($match1[1][0]));
				}
				$rank = strip_tags($rank);
			}
			$rank = strstr($rank, '位', true);
			if($rank){
				$rank = $rank."位";
			}
			
			$str2 = "";
			$str2 .= "<table>";
			//$str2 .= "<tr><th>カテゴリ</th></tr>";
			//$str2 .= "<tr><td>".$category."</td></tr>";
			$str2 .= "<tr><th>ランキング</th></tr>";
			$str2 .= "<tr><td>".$rank."</td></tr>";
			$str2 .= "<tr><th>Amazonカート価格</th></tr>";
			$str2 .= "<tr><td>".number_format($amazon_price)."</td></tr>";
			$str2 .= "</table>";
			

			if($_REQUEST["it"] == "on" or $_REQUEST["it_used"] == "on" ){
				$amazon_it_new = "-";$amazon_it_used = "-";
				//イタリアamazonスクレイピング
				$detail_url = "http://www.amazon.it/dp/".$asin."/";
				$contents = file_get_contents($detail_url);
				$contents = mb_convert_encoding($contents,'UTF-8','auto');
				$contents = str_replace(array("\r\n","\r","\n"), '', $contents);
				if($contents){
					//新品
					$match1 = array();
					preg_match('/<span class=\"olp-padding-right\"><a href=\"\/gp\/offer-listing\/.*?\">Nuovi: \d+<\/a>.*?<span class=\'a-color-price\'>EUR (.*?)<\/span><\/span>/', $contents, $match1);
					if($match1[1]){
						$amazon_it_new = $match1[1];
					}
					//中古
					$match1 = array();
					preg_match('/<span class=\"olp-padding-right\"><a href=\"\/gp\/offer-listing\/.*?\">Usati: \d+<\/a>.*?<span class=\'a-color-price\'>EUR (.*?)<\/span><\/span>/', $contents, $match1);
					if($match1[1]){
						$amazon_it_used = $match1[1];
					}
					
				}
			}
			if($_REQUEST["es"] == "on" or $_REQUEST["es_used"] == "on" ){
				$amazon_es_new = "-";$amazon_es_used = "-";
				//スペインamazonスクレイピング
				$detail_url = "http://www.amazon.es/dp/B006WLSIRI/";
				$contents = file_get_contents($detail_url);
				$contents = mb_convert_encoding($contents,'UTF-8','auto');
				$contents = str_replace(array("\r\n","\r","\n"), '', $contents);
				if($contents){
					//新品
					$match1 = array();
					preg_match('/<span class=\"olp-padding-right\"><a href=\"\/gp\/offer-listing\/.*?\">Nuevos: \d+<\/a> desde <span class=\'a-color-price\'>EUR (.*?)<\/span><\/span>/', $contents, $match1);
					if($match1[1]){
						$amazon_es_new = $match1[1];
					}
					//中古
					$match1 = array();
					preg_match('/mano\: 3<\/a> desde <span class=\'a-color-price\'>EUR (.*?)<\/span><\/span>/', $contents, $match1);
					if($match1[1]){
						$amazon_es_used = $match1[1];
					}
					
				}
			}
			
			
			
			//国別価格表示
			if(!$us[0]){$us[2]="--";}
			if(!$us[1]){$us[3]="--";}
			if(!$ca[0]){$ca[2]="--";}
			if(!$ca[1]){$ca[3]="--";}
			if(!$uk[0]){$uk[2]="--";}
			if(!$uk[1]){$uk[3]="--";}
			if(!$fr[0]){$fr[2]="--";}
			if(!$fr[1]){$fr[3]="--";}
			if(!$dp[0]){$dp[2]="--";}
			if(!$dp[1]){$dp[3]="--";}
			
			$str3 = "";
		
					$str3 .= "<table>";
					$str3 .= "<tr><th colspan=\"4\">海外アマゾン</th></tr>";
					$str3 .= "<tr><td>アメリカ</td><td>";
					if($_REQUEST["us"] == "on"){$str3 .= "新品：".$us[3]." (".Get_kawase("USD","JPY",$us[1])."円)<br />";}
					if($_REQUEST["us_used"] == "on"){$str3 .= "中古：".$us[2]." (".Get_kawase("USD","JPY",$us[0])."円)<br />";}
					$str3 .="ランキング：".$us[4]."</td>";
					$str3 .= "<td>カナダ</td><td>";
					if($_REQUEST["ca"] == "on"){$str3 .= "新品：".$ca[3]." (".Get_kawase("CAD","JPY",$ca[1])."円)<br />";}
					if($_REQUEST["ca_used"] == "on"){$str3 .= "中古：".$ca[2]." (".Get_kawase("CAD","JPY",$ca[0])."円)<br />";}
					$str3 .= "ランキング：".$ca[4]."</td></tr>";
					$str3 .= "<tr><td>イギリス</td><td>";
					if($_REQUEST["uk"] == "on"){$str3 .= "新品：".$uk[3]." (".Get_kawase("EUR","JPY",$uk[1])."円)<br />";}
					if($_REQUEST["uk_used"] == "on"){$str3 .= "中古：".$uk[2]." (".Get_kawase("EUR","JPY",$uk[0])."円)<br />";}
					$str3 .= "ランキング：".$uk[4]."</td>";
					$str3 .= "<td>フランス</td><td>";
					if($_REQUEST["fr"] == "on"){$str3 .= "新品：".$fr[3]." (".Get_kawase("EUR","JPY",$fr[1])."円)<br />";}
					if($_REQUEST["fr_used"] == "on"){$str3 .= "中古：".$fr[2]." (".Get_kawase("EUR","JPY",$fr[0])."円)<br />";}
					$str3 .= "ランキング：".$fr[4]."</td></tr>";
					$str3 .= "<tr><td>ドイツ</td><td>";
					if($_REQUEST["de"] == "on"){$str3 .= "新品：".$dp[3]." (".Get_kawase("EUR","JPY",$dp[1])."円)<br />";}
					if($_REQUEST["de_used"] == "on"){$str3 .= "中古：".$dp[2]." (".Get_kawase("EUR","JPY",$dp[0])."円)<br />";}
					$str3 .= "ランキング：".$dp[4]."</td>";
					$str3 .= "<td>&nbsp</td><td>&nbsp;</td></tr>";
					$str3 .= "<tr><td>スペイン</td><td>";
					if($_REQUEST["es"] == "on"){$str3 .= "新品：".$amazon_es_new." (".Get_kawase("EUR","JPY",$amazon_es_new)."円)<br />";}
					if($_REQUEST["es_used"] == "on"){$str3 .= "中古：".$amazon_es_used." (".Get_kawase("EUR","JPY",$amazon_es_used)."円)<br />";}
					$str3 .= "</td><td>イタリア</td><td>";
					if($_REQUEST["it"] == "on"){$str3 .= "新品：".$amazon_it_new." (".Get_kawase("EUR","JPY",$amazon_it_new)."円)<br />";}
					if($_REQUEST["it_used"] == "on"){$str3 .= "中古：".$amazon_it_used." (".Get_kawase("EUR","JPY",$amazon_it_used)."円)<br />";}
					$str3 .= "</td></tr>";
					$str3 .= "</table>";
			/*
					$str3 .= "<table>";
					$str3 .= "<tr><th colspan=\"4\">海外アマゾン</th></tr>";
					$str3 .= "<tr><td>アメリカ</td><td>新品：".$us[3]." (".Get_kawase("USD","JPY",$us[1])."円)<br />ランキング：".$us[4]."</td>";
					$str3 .= "<td>カナダ</td><td>新品：".$ca[3]." (".Get_kawase("CAD","JPY",$ca[1])."円)<br />ランキング：".$ca[4]."</td></tr>";
					$str3 .= "<tr><td>イギリス</td><td>新品：".$uk[3]." (".Get_kawase("EUR","JPY",$uk[1])."円)<br />ランキング：".$uk[4]."</td>";
					$str3 .= "<td>フランス</td><td>新品：".$fr[3]." (".Get_kawase("EUR","JPY",$fr[1])."円)<br />ランキング：".$fr[4]."</td></tr>";
					$str3 .= "<tr><td>ドイツ</td><td>新品：".$dp[3]." (".Get_kawase("EUR","JPY",$dp[1])."円)<br />ランキング：".$dp[4]."</td>";
					$str3 .= "<td>&nbsp</td><td>&nbsp;</td></tr>";
					//$str3 .= "<tr><td>スペイン</td><td>--</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
					$str3 .= "</table>";
				*/
	}
	//エラーがなければ表示
	if($error == 0){
		echo $str.$str2.$str3;
	}else{
		//エラーがあれば再表示リンク
		echo "<div style=\"width:300px;height:200px;\">";
		echo "<p>データの読込みに失敗いたしました。<br />下記リンクから再読込みしてください。</p>";
		echo "<a href=\"chrome_amazon2.php?mode=".$mode."&asin=".$asin."\"><input type=\"button\" value=\"再読込み\"></a>";
		echo "<br />".$srror_str."<br />";
		echo "</div>";
	}
/*
日本→JPY
US→USD
CA→CAD
UK→EUR
FR→EUR
DE→EUR
イタリア→amazon.it
スペイン→amazon.es
*/

?>
</body>
</html>
<?php

	exit;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function SearchAmazon($asin){
	global $awsk,$awss,$amazon_ast;
	$include_path= "/var/www/html/chrome/PEAR";
	ini_set('include_path', $include_path);
	require_once 'Services/Amazon.php';
	
	
		$amazon = new Services_Amazon($awsk, $awss,$amazon_ast);
		$amazon->setBaseUrl('http://ecs.amazonaws.jp/onca/xml');
		$type = "asin";
		if($type=="asin"){
			$response = $amazon->ItemLookup($asin,array('ResponseGroup' => 'Large,OfferFull,Offers'));
			//var_dump($response);
		}elseif($type=="ean"){
			$options['IdType'] = 'EAN';
			$options['Version'] = '2010-09-01';
			$options['SearchIndex'] = 'All';
			$options['Condition'] = 'All';
			$options['MerchantId'] = 'All';
			$options['ResponseGroup'] = 'ItemAttributes,OfferFull,OfferSummary';
			//$options['ResponseGroup'] = 'Small';
			$response = $amazon->ItemLookup($jan,$options);
		}

			if(@get_class($response)!="PEAR_Error"){
				$item = $response["Item"][0];
				if($item["LargeImage"]["URL"]){
					$img = $item["LargeImage"]["URL"];
				}elseif($item["MediumImage"]["URL"]){
					$img = $item["MediumImage"]["URL"];
				}elseif($item["SmallImage"]["URL"]){
					$img = $item["SmallImage"]["URL"];
				}
				$title = $item["ItemAttributes"]["Title"];
				//$price = $item["OfferSummary"]["LowestNewPrice"]["Amount"];
				//表示価格
				$price = $item["Offers"]["Offer"]["OfferListing"]["Price"]["Amount"];
				$asin = $item["ASIN"];
				$jan = $item["ItemAttributes"]["EAN"];
				//中古最安値
				$amazon_used = $response["Item"][0]["OfferSummary"]["LowestUsedPrice"]["Amount"];
				//新品最安値
				$amazon_new = $response["Item"][0]["OfferSummary"]["LowestNewPrice"]["Amount"];
			}
			
		return $response["Item"][0];
		return array($asin,$title,$price,$img,$jan,$amazon_used,$amazon_new);
}
	
function SearchAmazon_c($jan,$types){
	global $awsk,$awss,$cn,$amazon_ast_kaigai;
	$include_path= "/var/www/html/chrome/PEAR";
	ini_set('include_path', $include_path);
	require_once 'Services/Amazon.php';
	$type = array(
		"us"=>"http://ecs.amazonaws.com/onca/xml",/*アメリカ*/
		"ca"=>"http://ecs.amazonaws.ca/onca/xml",/*カナダ*/
		"uk"=>"http://ecs.amazonaws.uk/onca/xml",/*イギリス*/
		"fr"=>"http://ecs.amazonaws.fr/onca/xml",/*フランス*/
		"dp"=>"http://ecs.amazonaws.de/onca/xml",/*ドイツ*/
	);
		$amazon = new Services_Amazon($awsk, $awss,$amazon_ast_kaigai);
		$amazon->setBaseUrl($type[$types]);
		$options['IdType'] = 'EAN';
		$options['Version'] = '2010-09-01';
		$options['SearchIndex'] = 'All';
		$options['Condition'] = 'All';
		$options['MerchantId'] = 'All';
		$options['ResponseGroup'] = 'ItemAttributes,OfferFull,OfferSummary,SalesRank';
		//$options['ResponseGroup'] = 'Small';
		$response = $amazon->ItemLookup($jan,$options);
//var_dump($response);
			if(@get_class($response)!="PEAR_Error"){
				$amazon_used = number_format(($response["Item"][0]["OfferSummary"]["LowestUsedPrice"]["Amount"]/100),2);
				$amazon_used2 = $response["Item"][0]["OfferSummary"]["LowestUsedPrice"]["FormattedPrice"];
				$amazon_new = number_format(($response["Item"][0]["OfferSummary"]["LowestNewPrice"]["Amount"]/100),2);
				$amazon_new2 = $response["Item"][0]["OfferSummary"]["LowestNewPrice"]["FormattedPrice"];
				//表示価格
				$price = $response["Item"][0]["Offers"]["Offer"][0]["OfferListing"]["Price"]["Amount"];
				//$price = $response["Offers"]["Offer"][0]["OfferListing"]["Price"]["FormattedPrice"];
				//ランキング
				$rank = number_format($response["Item"][0]["SalesRank"])."位(".$response["Item"][0]["ItemAttributes"]["Binding"].")";
			}else{
				//@mysql_query($sql,$cn);
				//var_dump($response);
				return array();
			}
			
		return array($amazon_used,$amazon_new,$amazon_used2,$amazon_new2,$rank);
}
	
	
	
function Get_Ranking($jan,$amazon_price){
	global $awsk,$awss,$rakukey,$yahookey;
	
	$all_item_list = array();//すべての商品をまずはつっこむ用
	$ranking_list = array();//1位～20位までのランキングリスト用

	//------------------------------------------▼▼▼Yahooショッピング▼▼▼--------------------------------------------
	$yurl = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch?appid=".$yahookey."&availability=1&jan=".$jan;
	//echo $yurl."<br />\n";
	$yxml = simplexml_load_file($yurl);
	
	if ($yxml["totalResultsReturned"] != 0) {//検索件数が0件でない場合,変数$hitsに検索結果を格納します。
		
		//$hits = $yxml->Result->Hit;
		$hits = (array)$yxml->Result;
		$hits = $hits["Hit"];
		if($hits){
			foreach($hits as $hit){
				$hit = (array)$hit;
				if($hit['Price'] and strpos($hit['Name'],"中古") === false){
					$all_item_list[$a]['title'] = $hit['Name'];//商品名
					//$all_item_list[$a]['vc_link'] = "http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHOO_SHOP_SID."&pid=".YAHOO_SHOP_PID."&vc_url=".urlencode($hit['Url']);//商品詳細ＵＲＬ（アフィリ）

					$all_item_list[$a]['urls'] = "http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=".YAHOO_SHOP_SID."&pid=".YAHOO_SHOP_PID."&vc_url=".urlencode($hit['Url']);//商品詳細ＵＲＬ（アフィリ）
					$all_item_list[$a]['detail_page_url'] = $hit['Url'];//商品詳細ＵＲＬ（通常）
					$all_item_list[$a]['merchantName'] = "Yahoo(ショッピング)";//モール名
					//$all_item_list[$a]['ecCode'] = "rakuten";//モールコード
					$all_item_list[$a]['subStoreName'] = $hit['Store']->Name."（Yahooショッピング）";//店名
					$all_item_list[$a]['image'] = $hit['Image']->Medium;//画像
					$all_item_list[$a]['price'] = $hit['Price'];//価格
					$a++;
				}
			}
		}
	}else{
		//echo "Yahoo-error<br />";
		//echo $yurl."<br />\n";
				$sql = "update `api` set `error`=`error`+1 where `key`='".$yahookey."'";
				@mysql_query($sql,$cn);
	}
	
	//------------------------------------------▼▼▼楽天市場▼▼▼--------------------------------------------


	//クエリーの為にエンコード

	$rakuten_base_url="https://app.rakuten.co.jp/services/api/IchibaItem/Search/20130805?applicationId={$rakukey}&affiliateId=".RAKUTEN_ID."&keyword={$jan}&genreId={$node_id}&hits=20&page={$page}&format=json&availability=1";
//echo $rakuten_base_url;
	$rakuten_xml = @file_get_contents($rakuten_base_url);
	//json形式をデコードして配列に
	$rakuten_xml = json_decode($rakuten_xml,true);
	//アイテムの部分を変数につっこむ
	$rakuten_response = $rakuten_xml['Items'];
	if($rakuten_response){
		foreach($rakuten_response as $rakuten_value){
			
			if(stristr($rakuten_value['Item']['shopName'],"Joshin")===FALSE){
				//var_dump($rakuten_value);
				$all_item_list[$a]['title'] = $rakuten_value['Item']['itemName'];//商品名
				//$all_item_list[$a]['vc_link'] = $rakuten_value['Item']['affiliateUrl'];//商品詳細ＵＲＬ（アフィリ）
				$all_item_list[$a]['urls'] = $rakuten_value['Item']['affiliateUrl'];//商品詳細ＵＲＬ（アフィリ）
				$all_item_list[$a]['detail_page_url'] = $rakuten_value['Item']['itemUrl'];//商品詳細ＵＲＬ（通常）
				$all_item_list[$a]['merchantName'] = "楽天市場";//モール名
				$all_item_list[$a]['ecCode'] = "rakuten";//モールコード
				$all_item_list[$a]['subStoreName'] = $rakuten_value['Item']['shopName']."（楽天市場）";//店名
				$all_item_list[$a]['image'] = $rakuten_value['Item']['mediumImageUrls'][0]['imageUrl'];//画像
				$all_item_list[$a]['price'] = $rakuten_value['Item']['itemPrice'];//価格

				$a++;
				
			}
			
		}
	}else{
		//echo "rakuten-error<br />";
		//echo $rakuten_base_url."<br />";
				$sql = "update `api` set `error`=`error`+1 where `key`='".$rakukey."'";
				@mysql_query($sql,$cn);
	}
	//------------------------------------------▼▼▼DMM▼▼▼--------------------------------------------
	
/*
		$siteurl = "DMM.co.jp";
		if(strlen($service) > 0 and $service != "com"){
			$skey = explode("-",$service);
			$service_str = "&service=".$skey[0]."&floor=".$skey[1];
		}else{
			$service_str = "";
		}

		$keyword = urlencode(mb_convert_encoding("妖怪ウォッチ", "EUC-JP", "UTF-8"));
		
		$timestamp = urlencode(date("Y-m-d H:i:s"));
		$requrl = "http://affiliate-api.dmm.com/?api_id=".$dmmkey;
		$requrl.= "&affiliate_id=breakchance-990&operation=ItemList&version=2.00&hits=100&site=DMM.com";
		//$requrl.= "&operation=ItemList&version=2.00&hits=100";
		$requrl.= "&timestamp=".$timestamp."&site=".$siteurl;
		$requrl.= $service_str."&keyword=".$keyword;

		$file = mb_convert_encoding(file_get_contents($requrl), "UTF-8", "EUC-JP");
		$file = str_replace("encoding=\"euc-jp\"", "encoding=\"UTF-8\"", $file);
		$xml = simplexml_load_string($file);
*/
	
	
	
	
	
	


	//全商品リストを並べ替え
	$foo="";
	foreach($all_item_list as $key => $value){
		$foo[$key] = $value["price"];
	}
	@array_multisort($foo,SORT_ASC,$all_item_list);
	$c = 1;
	for($b=0; $b<count($all_item_list); $b++){
		//結果が入っていれば
		if($all_item_list[$b]["price"]){
			if($all_item_list[($b-1)]["subStoreName"]==$all_item_list[$b]["subStoreName"] and $all_item_list[($b-1)]["price"]==$all_item_list[$b]["price"]){
			}else{
				$ranking_list[]=$all_item_list[$b];
				$c++;
				if($c == 21){
					break;
				}
			}
		}else{
			//結果がなければループを抜ける
			break;
		}//if($all_item_list[$b])
		
	}//for($b=0; $b<20; $b++)
	
	return $ranking_list;
	
}
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
/*
日本→JPY
US→USD
CA→CAD
UK→EUR
FR→EUR
DE→EUR
*/
//為替情報を返す
function Get_kawase($from,$to,$price){
	$url ="http://www.reuters.com/finance/currencies/quote?srcAmt=1.0&srcCurr=".$from."&destCurr=".$to;
	$get_contents = file_get_contents($url, false, $context);
	$match1 = array();
	preg_match_all('/<input id=\"destAmt.*\" value=\"([^<]+)\"/', $get_contents, $match1);
	return round(($match1[1][0]*$price),2);
}

?>