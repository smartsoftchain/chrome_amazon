<?php
//yahooアプリケーションID
//アプリケーションIDの登録URLは、こちらです↓
// http://e.developer.yahoo.co.jp/webservices/register_application
//$appid = 'dj0zaiZpPWJMM3d1d2R5Y01TdiZzPWNvbnN1bWVyc2VjcmV0Jng9OWE-'; 
function escapestring($str) {
    return htmlspecialchars($str, ENT_QUOTES);
}
function Insert_tag($str){
	global $yahoo_apikey_list;
	shuffle($yahoo_apikey_list);
	$appid = $yahoo_apikey_list[0];
	if(strlen($str) > 0){
		$ma_response = "surface";
		$ma_filter = join("|", array("1","3","4","9"));
		$sentence = mb_convert_encoding($str, 'utf-8', 'auto');
	   $url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=".$appid."&results=ma";
	   $url .= "&ma_response=".$ma_response;
	   $url .= "&ma_filter="  .urlencode($ma_filter);
	    $url .= "&sentence=".urlencode($sentence);
	    $xml  = simplexml_load_file($url);
	    $w = array();
	    foreach ($xml->ma_result->word_list->word as $cur){
	    	if(preg_match("/^[^0-9]+$/",$cur->surface)){
	    		$w[] = str_replace("?","",escapestring($cur->surface));
	    	}
	    }
	    return implode(",",$w);
	}
}
function Insert_tag2($str){
	global $yahoo_apikey_list;
	shuffle($yahoo_apikey_list);
	$appid = $yahoo_apikey_list[0];

	if(strlen($str) > 0){
		$sentence = mb_convert_encoding($str, 'utf-8', 'auto');
	   $url = "http://jlp.yahooapis.jp/KeyphraseService/V1/extract?appid=".$appid."&results=ma";
	   $url .= "&output=xml";
	    $url .= "&sentence=".urlencode($sentence);
	    $xml  = simplexml_load_file($url);
	    //var_dump($xml);
	    $w = array();
	    foreach ($xml->Result as $cur){
	    	if($cur->Score > 60){
	    		$w[] = str_replace("?","",escapestring($cur->Keyphrase));
	    	}
	    }
	    return implode(",",$w);
	}
}
$yahoo_apikey_list = array(
"dj0zaiZpPWtuc2I4b29uaXg4USZzPWNvbnN1bWVyc2VjcmV0Jng9NDg-",
"dj0zaiZpPU1ZYnZSSktKc1dTNiZzPWNvbnN1bWVyc2VjcmV0Jng9YmU-",
"dj0zaiZpPTZjYXNSS25tOHFSQyZzPWNvbnN1bWVyc2VjcmV0Jng9Yjc-",
"dj0zaiZpPU1ubUUzdzQyM2pVRiZzPWNvbnN1bWVyc2VjcmV0Jng9Yjg-",
"dj0zaiZpPVJkN1ZhOXFKQjNoQiZzPWNvbnN1bWVyc2VjcmV0Jng9Y2U-",
"dj0zaiZpPW9MWWFsUzdzM3k4SiZzPWNvbnN1bWVyc2VjcmV0Jng9MjM-",
"dj0zaiZpPTRzWm9QOWFHTHRzbiZzPWNvbnN1bWVyc2VjcmV0Jng9M2M-",
"dj0zaiZpPVJYWGRCdklwMU1uViZzPWNvbnN1bWVyc2VjcmV0Jng9YWI-",
"dj0zaiZpPTJOTEk2c2pvcWlXNiZzPWNvbnN1bWVyc2VjcmV0Jng9NDg-",
"dj0zaiZpPU9SakhyY2ptY09iNiZzPWNvbnN1bWVyc2VjcmV0Jng9YTE-",
"dj0zaiZpPVpUSWNwT2phVlF1YyZzPWNvbnN1bWVyc2VjcmV0Jng9ZjM-",
"dj0zaiZpPXZhSk5xWTVYQUg4RiZzPWNvbnN1bWVyc2VjcmV0Jng9Zjg-",
"dj0zaiZpPUFnemh2eVhiOGtOQyZzPWNvbnN1bWVyc2VjcmV0Jng9Yjc-",
"dj0zaiZpPU1MVktWSlhrV3dxbiZzPWNvbnN1bWVyc2VjcmV0Jng9NjU-",
"dj0zaiZpPXdWZ1NTamV5TW1BRCZzPWNvbnN1bWVyc2VjcmV0Jng9ODk-",
"dj0zaiZpPW9zNHlkeGh1WldIUiZzPWNvbnN1bWVyc2VjcmV0Jng9MDc-",
"dj0zaiZpPXJ5UzdpNk1LN2J2UiZzPWNvbnN1bWVyc2VjcmV0Jng9ZTU-",
"dj0zaiZpPUFqWkM2bUhNZHZ0YiZzPWNvbnN1bWVyc2VjcmV0Jng9NGU-",
"dj0zaiZpPVBHbDFRd0UzUk9BTSZzPWNvbnN1bWVyc2VjcmV0Jng9ODc-",
"dj0zaiZpPW5NbGJReEhXSkFVYSZzPWNvbnN1bWVyc2VjcmV0Jng9MGU-",
"dj0zaiZpPXNNYXl6bEMxRkdpMyZzPWNvbnN1bWVyc2VjcmV0Jng9YzM-",
"dj0zaiZpPWR2UWE2d2UzRGFUYyZzPWNvbnN1bWVyc2VjcmV0Jng9Y2M-",
"dj0zaiZpPXJlQUV6bmNpY2NBayZzPWNvbnN1bWVyc2VjcmV0Jng9ODE-",
"dj0zaiZpPW1TaVRDd2FxYUp4bSZzPWNvbnN1bWVyc2VjcmV0Jng9MjU-",
"dj0zaiZpPXlMbEZROGFrRTlFcyZzPWNvbnN1bWVyc2VjcmV0Jng9OWQ-",
"dj0zaiZpPVNpajVibERlcFFmWiZzPWNvbnN1bWVyc2VjcmV0Jng9M2M-",
"dj0zaiZpPVNqZUR1MzFvdUlkQSZzPWNvbnN1bWVyc2VjcmV0Jng9MTI-",
"dj0zaiZpPVFxR21UTVFyMTAxSCZzPWNvbnN1bWVyc2VjcmV0Jng9MmY-",
"dj0zaiZpPUNJaEV2OE5VaE16dyZzPWNvbnN1bWVyc2VjcmV0Jng9ZTU-",
"dj0zaiZpPTNGb0xhZWR4ZEd3QSZzPWNvbnN1bWVyc2VjcmV0Jng9Mjg-",
"dj0zaiZpPXhBTmhTT25WZjhBYyZzPWNvbnN1bWVyc2VjcmV0Jng9NTQ-",
"dj0zaiZpPTk4QmNTZG5haXZvbCZzPWNvbnN1bWVyc2VjcmV0Jng9OTE-",
"dj0zaiZpPW5jUXBNU0xCRnplZyZzPWNvbnN1bWVyc2VjcmV0Jng9YWQ-",
"dj0zaiZpPWxNZ0dxT09TMXMyMSZzPWNvbnN1bWVyc2VjcmV0Jng9NzE-",
"dj0zaiZpPVpHN0ZuVHZHZHlyeiZzPWNvbnN1bWVyc2VjcmV0Jng9Y2I-",
"dj0zaiZpPThaWnNHb2dzQlE5TiZzPWNvbnN1bWVyc2VjcmV0Jng9OGQ-",
"dj0zaiZpPUpSTnk3NTh1UzlpNSZzPWNvbnN1bWVyc2VjcmV0Jng9Y2M-",
"dj0zaiZpPVNycmZKaXhYUWNSQSZzPWNvbnN1bWVyc2VjcmV0Jng9MGM-",
"dj0zaiZpPURrVmQ3UmVuU1NjMyZzPWNvbnN1bWVyc2VjcmV0Jng9YzI-",
"dj0zaiZpPUlybVJHbTIzZGFkNiZzPWNvbnN1bWVyc2VjcmV0Jng9MjY-",
"dj0zaiZpPWd6N0VPMnJUbktuWCZzPWNvbnN1bWVyc2VjcmV0Jng9Yzc-",
"dj0zaiZpPUVpd3NXTnZPdllsQiZzPWNvbnN1bWVyc2VjcmV0Jng9MmU-",
"dj0zaiZpPVNhZ3NBUDFWVXlzNyZzPWNvbnN1bWVyc2VjcmV0Jng9MGM-",
"dj0zaiZpPUxYU2U5ek1pWUlBTiZzPWNvbnN1bWVyc2VjcmV0Jng9YmQ-",
"dj0zaiZpPXE3amJtNTFNaEdVRSZzPWNvbnN1bWVyc2VjcmV0Jng9ZWM-",
"dj0zaiZpPURwd3JsMmZFSDlwbiZzPWNvbnN1bWVyc2VjcmV0Jng9MDE-",
"dj0zaiZpPVJ5NFFqc3o5WUpDNyZzPWNvbnN1bWVyc2VjcmV0Jng9ZTQ-",
"dj0zaiZpPTlVTmlKS0RodU9PWCZzPWNvbnN1bWVyc2VjcmV0Jng9MDg-",
"dj0zaiZpPXZGaUdRRnBOSUhjUiZzPWNvbnN1bWVyc2VjcmV0Jng9Yjg-",
"dj0zaiZpPXV1TTJJV1Nvak1jQiZzPWNvbnN1bWVyc2VjcmV0Jng9MDg-",
"dj0zaiZpPUM0YVJwRGNPUUpVWSZzPWNvbnN1bWVyc2VjcmV0Jng9ODI-",
"dj0zaiZpPVJpZmI1MmNibXRONiZzPWNvbnN1bWVyc2VjcmV0Jng9YjY-",
"dj0zaiZpPThrYVlrenVHSDJqUSZzPWNvbnN1bWVyc2VjcmV0Jng9MTc-",
"dj0zaiZpPXloaUJrcUFQUGdBYyZzPWNvbnN1bWVyc2VjcmV0Jng9ZDE-",
"dj0zaiZpPXBPYXZUNnFtWWszZSZzPWNvbnN1bWVyc2VjcmV0Jng9ZTM-",
"dj0zaiZpPVY5cWR5eGRoZGpPaSZzPWNvbnN1bWVyc2VjcmV0Jng9MWI-",
"dj0zaiZpPVo3OUJWdE1Tb2s0cSZzPWNvbnN1bWVyc2VjcmV0Jng9OGY-",
"dj0zaiZpPW1TTUZhaXpUVks1USZzPWNvbnN1bWVyc2VjcmV0Jng9MmE-",
"dj0zaiZpPUphY2ZtRTc5YkJFOSZzPWNvbnN1bWVyc2VjcmV0Jng9MTU-",
"dj0zaiZpPUZvOTVxRFhkZ0JNayZzPWNvbnN1bWVyc2VjcmV0Jng9OGY-",
"dj0zaiZpPXRUVHNVeGpscWxkZyZzPWNvbnN1bWVyc2VjcmV0Jng9NWQ-",
"dj0zaiZpPWZrSWdLcXg3YVhqYSZzPWNvbnN1bWVyc2VjcmV0Jng9ODc-",
"dj0zaiZpPWFNY01UUFVpdDMzRCZzPWNvbnN1bWVyc2VjcmV0Jng9YzA-",
"dj0zaiZpPXZDbHVHSE1CVXRxNyZzPWNvbnN1bWVyc2VjcmV0Jng9NWE-",
"dj0zaiZpPWtTbzZiTTdka3NQeSZzPWNvbnN1bWVyc2VjcmV0Jng9NzA-",
"dj0zaiZpPTk3TVlETTFsVndPYyZzPWNvbnN1bWVyc2VjcmV0Jng9NTk-",
"dj0zaiZpPW9JMFhVUFhEQVhlYyZzPWNvbnN1bWVyc2VjcmV0Jng9MjU-",
"dj0zaiZpPU1UZkowR2w0NERWcyZzPWNvbnN1bWVyc2VjcmV0Jng9ZjE-",
"dj0zaiZpPVBSa25Fa3VNdTJhaCZzPWNvbnN1bWVyc2VjcmV0Jng9MWY-",
"dj0zaiZpPUhlakUxQ09lZFIyZiZzPWNvbnN1bWVyc2VjcmV0Jng9MWI-",
"dj0zaiZpPUxsZ0c1MlpVS1BTRyZzPWNvbnN1bWVyc2VjcmV0Jng9OTk-",
"dj0zaiZpPWhVMXlsU2JwdGZNbCZzPWNvbnN1bWVyc2VjcmV0Jng9MzE-",
"dj0zaiZpPTlSWUJpVVJLcHNYNiZzPWNvbnN1bWVyc2VjcmV0Jng9ODI-",
"dj0zaiZpPUpFa1diRXFVRWhabCZzPWNvbnN1bWVyc2VjcmV0Jng9ODg-",
"dj0zaiZpPXpFVlhvR2pWeXROZyZzPWNvbnN1bWVyc2VjcmV0Jng9OGM-",
"dj0zaiZpPUd5elJ2OFR1Q2Q2QiZzPWNvbnN1bWVyc2VjcmV0Jng9NDY-",
"dj0zaiZpPXdlZ2xwNGRlTlFmYSZzPWNvbnN1bWVyc2VjcmV0Jng9Yjk-",
"dj0zaiZpPUNVNGhmZ3ZhbHRwNSZzPWNvbnN1bWVyc2VjcmV0Jng9Mjg-",
"dj0zaiZpPWI2TzljYVZ1WDFkTSZzPWNvbnN1bWVyc2VjcmV0Jng9NDM-",
"dj0zaiZpPWgzWUNmME93QUhFbCZzPWNvbnN1bWVyc2VjcmV0Jng9Yzk-",
"dj0zaiZpPVhZVTI5YjVZYVBNUyZzPWNvbnN1bWVyc2VjcmV0Jng9NTY-",
"dj0zaiZpPXZuN2s1d1lXdlQ1ZSZzPWNvbnN1bWVyc2VjcmV0Jng9YTc-",
"dj0zaiZpPXM0UUhZaHMwNEJnMCZzPWNvbnN1bWVyc2VjcmV0Jng9MTk-",
"dj0zaiZpPVU3aFdBQkw5NTh6diZzPWNvbnN1bWVyc2VjcmV0Jng9YTk-",
"dj0zaiZpPUdraWxZS29GZUswVSZzPWNvbnN1bWVyc2VjcmV0Jng9NWY-",
"dj0zaiZpPTRNOUk4eThvYTU2QSZzPWNvbnN1bWVyc2VjcmV0Jng9M2E-",
"dj0zaiZpPUpoZ1I4T3NYUTZGSSZzPWNvbnN1bWVyc2VjcmV0Jng9ZDM-",
"dj0zaiZpPTg0RWVjQ0M4eUthSyZzPWNvbnN1bWVyc2VjcmV0Jng9ZWQ-",
"dj0zaiZpPXFKQUUwVUdkR290SyZzPWNvbnN1bWVyc2VjcmV0Jng9NmY-",
"dj0zaiZpPVRDeEl3OXFNT3c3diZzPWNvbnN1bWVyc2VjcmV0Jng9MWY-",
"dj0zaiZpPWlNTThsS2RwMkZ1dyZzPWNvbnN1bWVyc2VjcmV0Jng9ZDQ-",
"dj0zaiZpPTVncE5YblhwV1BHTSZzPWNvbnN1bWVyc2VjcmV0Jng9Yzc-",
"dj0zaiZpPWxrczdvR251alEwNyZzPWNvbnN1bWVyc2VjcmV0Jng9MTU-",
"dj0zaiZpPWJsVnV1VlVsMnY4TSZzPWNvbnN1bWVyc2VjcmV0Jng9ODQ-",
"dj0zaiZpPUFXVXhuelFzTTh0aiZzPWNvbnN1bWVyc2VjcmV0Jng9MWU-",
"dj0zaiZpPXVOeW5lNlpJU2k4cCZzPWNvbnN1bWVyc2VjcmV0Jng9NDc-",
"dj0zaiZpPTBXTXR3TEh4RHN2TSZzPWNvbnN1bWVyc2VjcmV0Jng9Njk-",
"dj0zaiZpPURlQ2I1SzlsUk5aYSZzPWNvbnN1bWVyc2VjcmV0Jng9ODc-",
"dj0zaiZpPXBOY202TXFhZWlBYiZzPWNvbnN1bWVyc2VjcmV0Jng9NGY-",
"dj0zaiZpPVpMYlRleWNEYUVhcCZzPWNvbnN1bWVyc2VjcmV0Jng9NTY-",
"dj0zaiZpPU1LSEExYTVST1J2bSZzPWNvbnN1bWVyc2VjcmV0Jng9NjM-",
"dj0zaiZpPUIyOGVMT1JJZEFJTiZzPWNvbnN1bWVyc2VjcmV0Jng9ZjM-",
"dj0zaiZpPUpIVmtPdExrVjdrQiZzPWNvbnN1bWVyc2VjcmV0Jng9MWQ-",
"dj0zaiZpPWFEdXVYaXE4TVlnTSZzPWNvbnN1bWVyc2VjcmV0Jng9ZjM-",
"dj0zaiZpPWJNRUR1aXFZNkx4bSZzPWNvbnN1bWVyc2VjcmV0Jng9MDI-",
"dj0zaiZpPVRtYTBIc05JUTQ3TiZzPWNvbnN1bWVyc2VjcmV0Jng9YzE-",
"dj0zaiZpPU9DZTFjVXp6d0NCMCZzPWNvbnN1bWVyc2VjcmV0Jng9YmQ-",
"dj0zaiZpPW5pUlpINzVWOHQzVSZzPWNvbnN1bWVyc2VjcmV0Jng9YmQ-",
"dj0zaiZpPWpveVNKazczR1Q4NCZzPWNvbnN1bWVyc2VjcmV0Jng9YjE-",
"dj0zaiZpPXU0a3hPeTFuTWRaRCZzPWNvbnN1bWVyc2VjcmV0Jng9NmM-",
"dj0zaiZpPVpZblNzUjJVOWdKbCZzPWNvbnN1bWVyc2VjcmV0Jng9MGU-",
"dj0zaiZpPWpOcm1ja3pRV2NsZyZzPWNvbnN1bWVyc2VjcmV0Jng9ZGQ-",
"dj0zaiZpPUE5eFFyWVNVb1VhMyZzPWNvbnN1bWVyc2VjcmV0Jng9NzU-",
"dj0zaiZpPW5IbEsyQTV2MEJ1NSZzPWNvbnN1bWVyc2VjcmV0Jng9MDU-",
"dj0zaiZpPVFhdUpodGFlSGVONSZzPWNvbnN1bWVyc2VjcmV0Jng9ZDI-",
"dj0zaiZpPVc1dlNqZ015QnJ0bCZzPWNvbnN1bWVyc2VjcmV0Jng9YjE-",
"dj0zaiZpPVlkNE5OSXBsVG1QYSZzPWNvbnN1bWVyc2VjcmV0Jng9YzQ-",
"dj0zaiZpPUdsTlhudXpMMVNFeiZzPWNvbnN1bWVyc2VjcmV0Jng9YTI-",
"dj0zaiZpPWdiMU1nZHAxNm1aVSZzPWNvbnN1bWVyc2VjcmV0Jng9ZTQ-",
"dj0zaiZpPXY5QlJnY1NGeDBITyZzPWNvbnN1bWVyc2VjcmV0Jng9Y2U-",
"dj0zaiZpPVV2cnA2NUJXandjOCZzPWNvbnN1bWVyc2VjcmV0Jng9ZDQ-",
"dj0zaiZpPW1OZ2ZEMkhJeGFBUiZzPWNvbnN1bWVyc2VjcmV0Jng9MDY-",
"dj0zaiZpPVloT2ZDM3F0Y1dheSZzPWNvbnN1bWVyc2VjcmV0Jng9MjM-",
"dj0zaiZpPUpJV0NFT2pFTnF4RCZzPWNvbnN1bWVyc2VjcmV0Jng9Njc-",
"dj0zaiZpPTZtajJrR2ZsajV5QyZzPWNvbnN1bWVyc2VjcmV0Jng9Njk-",
"dj0zaiZpPWE3MFRGSzVxV2RGNiZzPWNvbnN1bWVyc2VjcmV0Jng9NWI-",
"dj0zaiZpPUYybUI5UXVNMjBwTCZzPWNvbnN1bWVyc2VjcmV0Jng9YmM-",
"dj0zaiZpPUtnU0pYMkpUQzdwdCZzPWNvbnN1bWVyc2VjcmV0Jng9NWQ-",
"dj0zaiZpPXRxdUFSdWtVNGlETiZzPWNvbnN1bWVyc2VjcmV0Jng9OTQ-",
"dj0zaiZpPVpNOFRhUm8ybnc3RiZzPWNvbnN1bWVyc2VjcmV0Jng9YTY-",
"dj0zaiZpPUs1T1JWTWswZlFreCZzPWNvbnN1bWVyc2VjcmV0Jng9ZjI-",
"dj0zaiZpPUFNMTBhM3VMazBEbyZzPWNvbnN1bWVyc2VjcmV0Jng9YjE-",
"dj0zaiZpPXBYcWFiWnRXa080dSZzPWNvbnN1bWVyc2VjcmV0Jng9YzY-",
"dj0zaiZpPVo5YVNSN0dESjI1NiZzPWNvbnN1bWVyc2VjcmV0Jng9MmI-",
"dj0zaiZpPUNacnJiamI3ZjJrYyZzPWNvbnN1bWVyc2VjcmV0Jng9NDg-",
"dj0zaiZpPWN1QUY3YnNTU0JhbiZzPWNvbnN1bWVyc2VjcmV0Jng9YmU-",
"dj0zaiZpPWhybzltd0JwS1FYVyZzPWNvbnN1bWVyc2VjcmV0Jng9MzM-",
"dj0zaiZpPWY2Q1pCVGNHSHVGNCZzPWNvbnN1bWVyc2VjcmV0Jng9MGI-",
"dj0zaiZpPUFvc212eHBaNlNhZyZzPWNvbnN1bWVyc2VjcmV0Jng9YjM-",
"dj0zaiZpPUNranBhNVpkVFJaUiZzPWNvbnN1bWVyc2VjcmV0Jng9YmM-",
"dj0zaiZpPTdNQWxJZ0tlM1JvZyZzPWNvbnN1bWVyc2VjcmV0Jng9N2Q-",
"dj0zaiZpPUFKWTJiaXVKcEtnWSZzPWNvbnN1bWVyc2VjcmV0Jng9ODA-",
"dj0zaiZpPTNKMWphQlJJMGFUTSZzPWNvbnN1bWVyc2VjcmV0Jng9ZWY-",
"dj0zaiZpPXBUU3VvUWczNkZSZSZzPWNvbnN1bWVyc2VjcmV0Jng9ZDI-",
"dj0zaiZpPTlQV1NvRHJkOHQ2USZzPWNvbnN1bWVyc2VjcmV0Jng9NWQ-",
"dj0zaiZpPVJDc1hyc015V1hzMCZzPWNvbnN1bWVyc2VjcmV0Jng9NmU-",
"dj0zaiZpPXZFT3I3VkRWWTRZNyZzPWNvbnN1bWVyc2VjcmV0Jng9ZmE-",
"dj0zaiZpPXRiRTRqMnpmbGhsbiZzPWNvbnN1bWVyc2VjcmV0Jng9NzA-",
"dj0zaiZpPWNyY1dQQUZWN2xVVCZzPWNvbnN1bWVyc2VjcmV0Jng9OWM-",
"dj0zaiZpPUNnQndSbXJBdGlhUCZzPWNvbnN1bWVyc2VjcmV0Jng9YjM-",
"dj0zaiZpPU5vUXJHaEh2WlZpcCZzPWNvbnN1bWVyc2VjcmV0Jng9MzU-",
"dj0zaiZpPVBJTVV2VXFEeUxvcyZzPWNvbnN1bWVyc2VjcmV0Jng9Yzk-",
"dj0zaiZpPWI3Yk1BYmQ5V29MWiZzPWNvbnN1bWVyc2VjcmV0Jng9NzU-",
"dj0zaiZpPUtKVGtoQml4S2xsMyZzPWNvbnN1bWVyc2VjcmV0Jng9NTE-",
"dj0zaiZpPXF1RWt4VnEwMDBOeCZzPWNvbnN1bWVyc2VjcmV0Jng9ZGQ-",
"dj0zaiZpPXo2OHZhSnFibjFZayZzPWNvbnN1bWVyc2VjcmV0Jng9MmY-",
"dj0zaiZpPVowNUpjZVZ4MUw3RiZzPWNvbnN1bWVyc2VjcmV0Jng9N2I-",
"dj0zaiZpPUxmZ2I3WUFYQnFvYyZzPWNvbnN1bWVyc2VjcmV0Jng9ZTk-",
"dj0zaiZpPUNaTU5hZnl5QWxxeSZzPWNvbnN1bWVyc2VjcmV0Jng9ZjI-",
"dj0zaiZpPUhVZEJ6cjRVaG5NSyZzPWNvbnN1bWVyc2VjcmV0Jng9YTQ-",
"dj0zaiZpPW5HYkhmYzFhS0dLNCZzPWNvbnN1bWVyc2VjcmV0Jng9Njg-",
"dj0zaiZpPUs1U0w0bDhuNUJxZyZzPWNvbnN1bWVyc2VjcmV0Jng9MmE-",
"dj0zaiZpPXYxcGU1TUFkY2k4diZzPWNvbnN1bWVyc2VjcmV0Jng9NmQ-",
"dj0zaiZpPW8zQnZqYWtJeVVPaCZzPWNvbnN1bWVyc2VjcmV0Jng9MzA-",
"dj0zaiZpPXByaWg4VWYzWERmOCZzPWNvbnN1bWVyc2VjcmV0Jng9N2Q-",
"dj0zaiZpPTR5cThSNnpTNmtQeCZzPWNvbnN1bWVyc2VjcmV0Jng9ZGE-",
"dj0zaiZpPVhETVJxY29jMXc0diZzPWNvbnN1bWVyc2VjcmV0Jng9Mzg-",
"dj0zaiZpPXVET3pTZGNMTTVSTiZzPWNvbnN1bWVyc2VjcmV0Jng9NTE-",
"dj0zaiZpPXlOT2o5aFNhcmRObSZzPWNvbnN1bWVyc2VjcmV0Jng9Njg-",
"dj0zaiZpPTZFNDNMYm1mY3FaTiZzPWNvbnN1bWVyc2VjcmV0Jng9ODc-",
"dj0zaiZpPXNjWGlTTWZmTm9GZSZzPWNvbnN1bWVyc2VjcmV0Jng9MjE-",
"dj0zaiZpPUg2QVJsWEZQbUJDQSZzPWNvbnN1bWVyc2VjcmV0Jng9Y2Q-",
"dj0zaiZpPUtHS2c5YUhTbEdhRiZzPWNvbnN1bWVyc2VjcmV0Jng9ODk-",
"dj0zaiZpPW4xR1F1MjhOZ3ZPTyZzPWNvbnN1bWVyc2VjcmV0Jng9N2U-",
"dj0zaiZpPVh0TWF0NnBYdXJKSiZzPWNvbnN1bWVyc2VjcmV0Jng9MjQ-",
"dj0zaiZpPWZGTXRBQm81REdkNCZzPWNvbnN1bWVyc2VjcmV0Jng9NWI-",
"dj0zaiZpPUlVcXZDd0FRTmJvYSZzPWNvbnN1bWVyc2VjcmV0Jng9MWY-",
"dj0zaiZpPTY1VjYweVc0WjZmNCZzPWNvbnN1bWVyc2VjcmV0Jng9YTE-",
"dj0zaiZpPUs5TmpRVXlOQlFwTCZzPWNvbnN1bWVyc2VjcmV0Jng9NDM-",
"dj0zaiZpPUZDNVdha3NoMlZkQSZzPWNvbnN1bWVyc2VjcmV0Jng9MzI-",
"dj0zaiZpPUhJbDhWbjJOTFlmZSZzPWNvbnN1bWVyc2VjcmV0Jng9MzU-",
"dj0zaiZpPUhJTklPUVV2QVczcyZzPWNvbnN1bWVyc2VjcmV0Jng9ZDA-",
"dj0zaiZpPThxc0VpWFhvNnJwViZzPWNvbnN1bWVyc2VjcmV0Jng9MTI-",
"dj0zaiZpPTR6QkRyTW5PRzFUTCZzPWNvbnN1bWVyc2VjcmV0Jng9YjA-",
"dj0zaiZpPTFLUmg0anJHNk16ViZzPWNvbnN1bWVyc2VjcmV0Jng9YjM-",
"dj0zaiZpPThlTEoweWFmZlNqUSZzPWNvbnN1bWVyc2VjcmV0Jng9NTY-",
"dj0zaiZpPTVEdDBNTmh0VHN1ZiZzPWNvbnN1bWVyc2VjcmV0Jng9ZmU-",
"dj0zaiZpPThtTGtlbDBIZnpDNSZzPWNvbnN1bWVyc2VjcmV0Jng9ZjA-",
"dj0zaiZpPTRUaU5oMVp2d3pTQyZzPWNvbnN1bWVyc2VjcmV0Jng9YzQ-",
"dj0zaiZpPUUwNU5CN3g0N01hdCZzPWNvbnN1bWVyc2VjcmV0Jng9YmQ-",
"dj0zaiZpPWhNZGZ1OU5CNVYzTSZzPWNvbnN1bWVyc2VjcmV0Jng9NDM-",
"dj0zaiZpPUMzcFFYeFpsWjdBcSZzPWNvbnN1bWVyc2VjcmV0Jng9NTM-",
"dj0zaiZpPWRyYVl4YVlNckl0UyZzPWNvbnN1bWVyc2VjcmV0Jng9MjE-",
"dj0zaiZpPXM1SDhsNUROYTBlYiZzPWNvbnN1bWVyc2VjcmV0Jng9NzM-",
"dj0zaiZpPTBHTW12Zk1YRndPdyZzPWNvbnN1bWVyc2VjcmV0Jng9NWQ-",
"dj0zaiZpPXdDMWN0eWZEaE5SSyZzPWNvbnN1bWVyc2VjcmV0Jng9Mzk-",
"dj0zaiZpPUtpaUNGODF1YXMyNiZzPWNvbnN1bWVyc2VjcmV0Jng9YTM-",
"dj0zaiZpPVNEZ1g3VE0yTUpNQiZzPWNvbnN1bWVyc2VjcmV0Jng9MGM-",
"dj0zaiZpPWwzcE1VQm1xTndFMiZzPWNvbnN1bWVyc2VjcmV0Jng9MTU-",
"dj0zaiZpPWE2dXo3dTA3U2NyQyZzPWNvbnN1bWVyc2VjcmV0Jng9YzE-",
"dj0zaiZpPTZ6WFdsdG9hM3dXUyZzPWNvbnN1bWVyc2VjcmV0Jng9Njc-",
"dj0zaiZpPWhDMURaNVBnWlBwbCZzPWNvbnN1bWVyc2VjcmV0Jng9MTc-",
"dj0zaiZpPURNc1JGM2x1TWdYeCZzPWNvbnN1bWVyc2VjcmV0Jng9YjQ-",
"dj0zaiZpPU93Rzh0bVdBVFZzZyZzPWNvbnN1bWVyc2VjcmV0Jng9MmU-",
"dj0zaiZpPUk0dG93OGhlcmtSZCZzPWNvbnN1bWVyc2VjcmV0Jng9Nzg-",
"dj0zaiZpPXVUa0V4Tm1YbVV4ZyZzPWNvbnN1bWVyc2VjcmV0Jng9YmU-",
"dj0zaiZpPU9IaFZuR2ZVaGJqeSZzPWNvbnN1bWVyc2VjcmV0Jng9OWU-",
"dj0zaiZpPTVCQ2xaQjZPSlVDZCZzPWNvbnN1bWVyc2VjcmV0Jng9MzY-",
"dj0zaiZpPW5TWktMU21sSWVyQiZzPWNvbnN1bWVyc2VjcmV0Jng9MDM-",
"dj0zaiZpPVE2WjExblNnS2FaTiZzPWNvbnN1bWVyc2VjcmV0Jng9OWQ-",
"dj0zaiZpPWRPcnVwTGExbFhhaiZzPWNvbnN1bWVyc2VjcmV0Jng9MzY-",
"dj0zaiZpPWtLSU1DOThzUFVWYSZzPWNvbnN1bWVyc2VjcmV0Jng9ZDY-",
"dj0zaiZpPXBBOUU4b1huYmZkaSZzPWNvbnN1bWVyc2VjcmV0Jng9MDA-",
"dj0zaiZpPUZtbVFlR1VSYW5mcyZzPWNvbnN1bWVyc2VjcmV0Jng9YWM-",
"dj0zaiZpPUtkZVR1SGZuYlZndyZzPWNvbnN1bWVyc2VjcmV0Jng9ZGM-",
"dj0zaiZpPThNTmFZWjJ4Q1V3SiZzPWNvbnN1bWVyc2VjcmV0Jng9ZDM-",
"dj0zaiZpPTRMZWthRklDclNGOSZzPWNvbnN1bWVyc2VjcmV0Jng9NTE-",
"dj0zaiZpPXpQVHBnUlN6RTRIWSZzPWNvbnN1bWVyc2VjcmV0Jng9NGE-",
"dj0zaiZpPTVDQkU1bTJYN2VheSZzPWNvbnN1bWVyc2VjcmV0Jng9MmU-",
"dj0zaiZpPU0xaDBFUU9KckdwbSZzPWNvbnN1bWVyc2VjcmV0Jng9Mjk-",
"dj0zaiZpPWhmbGZENlcxNHowSSZzPWNvbnN1bWVyc2VjcmV0Jng9OGY-",
"dj0zaiZpPVVaZ0Z3eEpXUVBjNyZzPWNvbnN1bWVyc2VjcmV0Jng9YmI-",
"dj0zaiZpPWFxcXJJVmFWTG9CVCZzPWNvbnN1bWVyc2VjcmV0Jng9MzM-",
"dj0zaiZpPU1sRUI3ZWxlQ0lnaiZzPWNvbnN1bWVyc2VjcmV0Jng9YTc-",
"dj0zaiZpPUpmZjJGcXpHSHpQMiZzPWNvbnN1bWVyc2VjcmV0Jng9NWU-",
"dj0zaiZpPVIxNVFYQVJxa1VNbiZzPWNvbnN1bWVyc2VjcmV0Jng9NTI-",
"dj0zaiZpPWNuam96bUg5cnBrOSZzPWNvbnN1bWVyc2VjcmV0Jng9ZGE-",
"dj0zaiZpPVZrMjdzVDdiZ29NeCZzPWNvbnN1bWVyc2VjcmV0Jng9OGM-",
"dj0zaiZpPUk5VVZKaEVIemtXVSZzPWNvbnN1bWVyc2VjcmV0Jng9MzA-",
"dj0zaiZpPTJEWG1qMEVUS3N0MCZzPWNvbnN1bWVyc2VjcmV0Jng9MWY-",
"dj0zaiZpPTRCQkJYTVVoTkl2bSZzPWNvbnN1bWVyc2VjcmV0Jng9Yjg-",
"dj0zaiZpPTA3cDV0YVZENDIxbyZzPWNvbnN1bWVyc2VjcmV0Jng9MWQ-",
"dj0zaiZpPTJjVDZlVDRHclVFeSZzPWNvbnN1bWVyc2VjcmV0Jng9Y2E-",
"dj0zaiZpPW5VSmVlNnR6aU5VMCZzPWNvbnN1bWVyc2VjcmV0Jng9OWI-",
"dj0zaiZpPVZmdUlvdFBpTVZ1YSZzPWNvbnN1bWVyc2VjcmV0Jng9YWQ-",
"dj0zaiZpPUhVTW11dzdLanF4NyZzPWNvbnN1bWVyc2VjcmV0Jng9NGI-",
"dj0zaiZpPXJjRDdid2pUalU1RCZzPWNvbnN1bWVyc2VjcmV0Jng9ZTE-",
"dj0zaiZpPXlDaGN1NkVNTG84MSZzPWNvbnN1bWVyc2VjcmV0Jng9YTk-",
"dj0zaiZpPXpIQ0hValZRbG95MiZzPWNvbnN1bWVyc2VjcmV0Jng9OTU-",
"dj0zaiZpPTl4aUw4ZnlrU1FpYyZzPWNvbnN1bWVyc2VjcmV0Jng9MWI-",
"dj0zaiZpPXQyUkRaUGZtd0owZSZzPWNvbnN1bWVyc2VjcmV0Jng9ZGQ-",
"dj0zaiZpPXptOU14MHNYNTQ1WSZzPWNvbnN1bWVyc2VjcmV0Jng9MGU-",
"dj0zaiZpPUdCbXJwTVAxY2xMUiZzPWNvbnN1bWVyc2VjcmV0Jng9MzI-",
"dj0zaiZpPUdYYWE1cEd0T0NVTSZzPWNvbnN1bWVyc2VjcmV0Jng9OGQ-",
"dj0zaiZpPUhLVmFLMDBxa2V6SSZzPWNvbnN1bWVyc2VjcmV0Jng9ZjQ-",
"dj0zaiZpPUd6VEZWa05ta0FhYSZzPWNvbnN1bWVyc2VjcmV0Jng9MzM-",
"dj0zaiZpPW1NYUJ4WjlSRzdyZSZzPWNvbnN1bWVyc2VjcmV0Jng9ODg-",
"dj0zaiZpPXh2RDdsTkFNTTVnOSZzPWNvbnN1bWVyc2VjcmV0Jng9ZjU-",
"dj0zaiZpPVZlMzk3Q3dpdjVZSSZzPWNvbnN1bWVyc2VjcmV0Jng9NzY-",
"dj0zaiZpPUltelFKNTZMeU4wWCZzPWNvbnN1bWVyc2VjcmV0Jng9MDM-",
"dj0zaiZpPUZnUnJ0bkdJaFBmNCZzPWNvbnN1bWVyc2VjcmV0Jng9NjE-",
"dj0zaiZpPWtJZFkyZXRKR1ZmUiZzPWNvbnN1bWVyc2VjcmV0Jng9YjQ-",
"dj0zaiZpPTVVYzZ1MEVqYVFSZyZzPWNvbnN1bWVyc2VjcmV0Jng9Yjg-",
"dj0zaiZpPVRudWU5eE0xSVJhSCZzPWNvbnN1bWVyc2VjcmV0Jng9OTE-",
"dj0zaiZpPVlRb29LMVkzOEIySiZzPWNvbnN1bWVyc2VjcmV0Jng9MWY-",
"dj0zaiZpPW1CbFdaakhFMFp3WSZzPWNvbnN1bWVyc2VjcmV0Jng9MDQ-",
"dj0zaiZpPU84Nnp5WE1ZSE1rbyZzPWNvbnN1bWVyc2VjcmV0Jng9Y2U-",
"dj0zaiZpPVRYMUZ1eWRTd1pmWiZzPWNvbnN1bWVyc2VjcmV0Jng9ZjI-",
"dj0zaiZpPU1wRkZhWkZhc2paVyZzPWNvbnN1bWVyc2VjcmV0Jng9MGE-",
"dj0zaiZpPVh4aWRKVlVRVkxyYSZzPWNvbnN1bWVyc2VjcmV0Jng9MzY-",
"dj0zaiZpPTE3OGtjUDBicHQxciZzPWNvbnN1bWVyc2VjcmV0Jng9YWQ-",
"dj0zaiZpPUljWmdvdklqNFZnMCZzPWNvbnN1bWVyc2VjcmV0Jng9MzI-",
"dj0zaiZpPVhVUjBXbjdndHdYaiZzPWNvbnN1bWVyc2VjcmV0Jng9NWM-",
"dj0zaiZpPWFLbWE1SjNxdmFBYSZzPWNvbnN1bWVyc2VjcmV0Jng9MTk-",
"dj0zaiZpPWpBY1BiUjFETTQ0YSZzPWNvbnN1bWVyc2VjcmV0Jng9ZTk-",
"dj0zaiZpPUtHdkswWWtTNHpNdyZzPWNvbnN1bWVyc2VjcmV0Jng9MDk-",
"dj0zaiZpPTlYT25uMnl0ZVJsRCZzPWNvbnN1bWVyc2VjcmV0Jng9ZTE-",
"dj0zaiZpPWFjZkVEQ0RkZ2RmWCZzPWNvbnN1bWVyc2VjcmV0Jng9YzU-",
"dj0zaiZpPUpqRGRXbGpMZk1QNCZzPWNvbnN1bWVyc2VjcmV0Jng9NjE-",
"dj0zaiZpPUlwOXN0aDJ0TktWdyZzPWNvbnN1bWVyc2VjcmV0Jng9ZDE-",
"dj0zaiZpPXZ0aHZIR2Q2SzhkZCZzPWNvbnN1bWVyc2VjcmV0Jng9Yjc-",
"dj0zaiZpPWVsSXQ5M2sydzNmRCZzPWNvbnN1bWVyc2VjcmV0Jng9MTk-",
"dj0zaiZpPW9hS2NrQ1FZb0lyQSZzPWNvbnN1bWVyc2VjcmV0Jng9NzI-",
"dj0zaiZpPURzWUo4N3R3N0pGVSZzPWNvbnN1bWVyc2VjcmV0Jng9YzE-",
"dj0zaiZpPWdxR25OUUZTZEl3dCZzPWNvbnN1bWVyc2VjcmV0Jng9ZWM-",
"dj0zaiZpPWFZTWgxWkpHalJBTyZzPWNvbnN1bWVyc2VjcmV0Jng9MjU-",
"dj0zaiZpPWNkUk0yS0ptRGswbSZzPWNvbnN1bWVyc2VjcmV0Jng9NTc-",
"dj0zaiZpPUE1eWVEVzhhZGQ2SCZzPWNvbnN1bWVyc2VjcmV0Jng9YmQ-",
"dj0zaiZpPWRyN3ZpVGVEUk8wbyZzPWNvbnN1bWVyc2VjcmV0Jng9MzM-",
"dj0zaiZpPTRLREV4TnAwa1RxZiZzPWNvbnN1bWVyc2VjcmV0Jng9NzU-",
"dj0zaiZpPTJaSFVLaEtnZ2JLMiZzPWNvbnN1bWVyc2VjcmV0Jng9M2U-",
"dj0zaiZpPUZneDgxUW1FRERVdiZzPWNvbnN1bWVyc2VjcmV0Jng9NjY-",
"dj0zaiZpPVhHS0lMOE1ja01iSiZzPWNvbnN1bWVyc2VjcmV0Jng9Njg-",
"dj0zaiZpPWQzR29oMkNNODFsYSZzPWNvbnN1bWVyc2VjcmV0Jng9NDU-",
"dj0zaiZpPUEzd2swWU5xamw0SCZzPWNvbnN1bWVyc2VjcmV0Jng9ZjI-",
"dj0zaiZpPXNUU1Z5RU5FMG85ayZzPWNvbnN1bWVyc2VjcmV0Jng9Y2I-",
"dj0zaiZpPTNWeUJScXIwaUhPQSZzPWNvbnN1bWVyc2VjcmV0Jng9YWU-",
"dj0zaiZpPUlZRG8wZWphelREMyZzPWNvbnN1bWVyc2VjcmV0Jng9ZjE-",
"dj0zaiZpPTEyMm5IUXlEUU5YSyZzPWNvbnN1bWVyc2VjcmV0Jng9MGQ-",
"dj0zaiZpPXJXTjVGWkQxWDNvViZzPWNvbnN1bWVyc2VjcmV0Jng9ODk-",
"dj0zaiZpPTREQWEwR01oeTMxeSZzPWNvbnN1bWVyc2VjcmV0Jng9ODM-",
"dj0zaiZpPUZxZEZsb3ZEYXlnTSZzPWNvbnN1bWVyc2VjcmV0Jng9ZWI-",
"dj0zaiZpPWQ2eHJrc0x1YW1TNiZzPWNvbnN1bWVyc2VjcmV0Jng9OTI-",
"dj0zaiZpPWM4QWJ1c1c2V3R3WCZzPWNvbnN1bWVyc2VjcmV0Jng9ZTg-",
"dj0zaiZpPVdKS0VXYmF1UzdweSZzPWNvbnN1bWVyc2VjcmV0Jng9NjY-",
"dj0zaiZpPUk1a2Z0U2tMWnlPYSZzPWNvbnN1bWVyc2VjcmV0Jng9ODA-",
"dj0zaiZpPTlZWm5WQTVPQ0ZDZSZzPWNvbnN1bWVyc2VjcmV0Jng9YTA-",
"dj0zaiZpPTVuVDhNR1d1ejlVdyZzPWNvbnN1bWVyc2VjcmV0Jng9Yzg-",
"dj0zaiZpPTZvUHZSNHdjdVNIWCZzPWNvbnN1bWVyc2VjcmV0Jng9OGI-",
"dj0zaiZpPUV2QUxROW5iSGlDVSZzPWNvbnN1bWVyc2VjcmV0Jng9ZGY-",
"dj0zaiZpPVRUZG9NeWI5VGJIWiZzPWNvbnN1bWVyc2VjcmV0Jng9OGY-",
"dj0zaiZpPU9QdXY5MWhhR1V0YyZzPWNvbnN1bWVyc2VjcmV0Jng9M2I-",
"dj0zaiZpPWs5VG1rd2RCclY3YSZzPWNvbnN1bWVyc2VjcmV0Jng9OTE-",
"dj0zaiZpPXM2a2RIZ3RYSkxKViZzPWNvbnN1bWVyc2VjcmV0Jng9ZGE-",
"dj0zaiZpPUJ4Q3p2cENRN1RNOSZzPWNvbnN1bWVyc2VjcmV0Jng9ZDM-",
"dj0zaiZpPXRscEU3azB2ZlRFOSZzPWNvbnN1bWVyc2VjcmV0Jng9OWU-",
"dj0zaiZpPUU5VVFMZ2JhTGNldCZzPWNvbnN1bWVyc2VjcmV0Jng9YmY-",
"dj0zaiZpPWxUTTZWYVkwakx4NSZzPWNvbnN1bWVyc2VjcmV0Jng9NTg-",
"dj0zaiZpPW45amI1Sno2VHp6QiZzPWNvbnN1bWVyc2VjcmV0Jng9MjE-",
"dj0zaiZpPXgxNXpPV05adjBLbCZzPWNvbnN1bWVyc2VjcmV0Jng9MjA",
"dj0zaiZpPVpJSWpKaGZQUVNzMSZzPWNvbnN1bWVyc2VjcmV0Jng9YTk-",
"dj0zaiZpPUZhYnlZdVBqMnBGZyZzPWNvbnN1bWVyc2VjcmV0Jng9OGI-",
"dj0zaiZpPXF5azR4NG5lNm5lUSZzPWNvbnN1bWVyc2VjcmV0Jng9Yjg-",
"dj0zaiZpPTZEN2h3RWQwUVJrdiZzPWNvbnN1bWVyc2VjcmV0Jng9MGQ-",
"dj0zaiZpPXduQ1ZKSWRuQ1VraSZzPWNvbnN1bWVyc2VjcmV0Jng9ZDM-",
"dj0zaiZpPTZ6WGVFalh4dXpYZiZzPWNvbnN1bWVyc2VjcmV0Jng9MGI-",
"dj0zaiZpPWxmZ1FVSnZrTFZEaiZzPWNvbnN1bWVyc2VjcmV0Jng9YzQ-",
"dj0zaiZpPW1Dam9ER2tGejJ4UCZzPWNvbnN1bWVyc2VjcmV0Jng9MWU-",
"dj0zaiZpPXByTlhxQWtlRkJkUyZzPWNvbnN1bWVyc2VjcmV0Jng9NzU-",
"dj0zaiZpPVBINDlZQ2F2OE1ZeiZzPWNvbnN1bWVyc2VjcmV0Jng9YzA-",
"dj0zaiZpPXN6OVJ1Y0JVS2hSdiZzPWNvbnN1bWVyc2VjcmV0Jng9YTU-",
"dj0zaiZpPW9zSFJhS1o4N2toYyZzPWNvbnN1bWVyc2VjcmV0Jng9ZjA-",
"dj0zaiZpPXlvOHpzRVhrY202SSZzPWNvbnN1bWVyc2VjcmV0Jng9NmQ-",
"dj0zaiZpPXJhVUR3WmhjZ1g3eCZzPWNvbnN1bWVyc2VjcmV0Jng9NDM-",
"dj0zaiZpPTBpczhjTVV5eloydiZzPWNvbnN1bWVyc2VjcmV0Jng9OWE-",
"dj0zaiZpPTlBNlozVlQwSUJiYSZzPWNvbnN1bWVyc2VjcmV0Jng9MGU-",
"dj0zaiZpPVR2TWtSZTNvM0xnbSZzPWNvbnN1bWVyc2VjcmV0Jng9MzU-",
"dj0zaiZpPXBJVmV4dURIZUJNZyZzPWNvbnN1bWVyc2VjcmV0Jng9OGY-",
"dj0zaiZpPVVZZjBGZTE2Z3BPQSZzPWNvbnN1bWVyc2VjcmV0Jng9NmY-",
"dj0zaiZpPU1HVmI3UklycUJNVyZzPWNvbnN1bWVyc2VjcmV0Jng9Yjk-",
"dj0zaiZpPWVmcGlCdXJLMGc3ZCZzPWNvbnN1bWVyc2VjcmV0Jng9YjY-",
"dj0zaiZpPTd2YW9OcWx3TE4wbyZzPWNvbnN1bWVyc2VjcmV0Jng9OGY-",
"dj0zaiZpPUtLcGJCOTVKQ1BSeSZzPWNvbnN1bWVyc2VjcmV0Jng9NTU-",
"dj0zaiZpPW5kRFVqWktoTWZPYSZzPWNvbnN1bWVyc2VjcmV0Jng9OGI-",
"dj0zaiZpPWpqb0NnbWR6bUtTOCZzPWNvbnN1bWVyc2VjcmV0Jng9M2I-",
"dj0zaiZpPXRhZlpZYVVmSk9VZCZzPWNvbnN1bWVyc2VjcmV0Jng9MjA-",
"dj0zaiZpPVRCSkZGc1p5d3MxViZzPWNvbnN1bWVyc2VjcmV0Jng9OTk-",
"dj0zaiZpPWVmcGlCdXJLMGc3ZCZzPWNvbnN1bWVyc2VjcmV0Jng9YjY-",
"dj0zaiZpPTRwcjBST3hlT1p0SSZzPWNvbnN1bWVyc2VjcmV0Jng9MWM-",
"dj0zaiZpPXAwZ3Mycm5hQmtHSCZzPWNvbnN1bWVyc2VjcmV0Jng9NWU-",
"dj0zaiZpPXUyODdIYjVYWnJLeCZzPWNvbnN1bWVyc2VjcmV0Jng9Yjg-",
"dj0zaiZpPW1FcWFucGlmSjZxSCZzPWNvbnN1bWVyc2VjcmV0Jng9ZjE-",
"dj0zaiZpPTJWSXFTSHl1V200SyZzPWNvbnN1bWVyc2VjcmV0Jng9NmQ-",
"dj0zaiZpPW1ibkZVMGFKZURoNyZzPWNvbnN1bWVyc2VjcmV0Jng9NTE-",
"dj0zaiZpPTh4dnBVODM2S2NyYSZzPWNvbnN1bWVyc2VjcmV0Jng9MGI-",
"dj0zaiZpPWc1U0RUbkJRTjNtaCZzPWNvbnN1bWVyc2VjcmV0Jng9YmU-",
"dj0zaiZpPW9wVk1FcjVRM3pRTCZzPWNvbnN1bWVyc2VjcmV0Jng9Y2I-",
"dj0zaiZpPUpFUWJtbkdSQlM4WiZzPWNvbnN1bWVyc2VjcmV0Jng9YjU-",
"dj0zaiZpPXpvS3lrS1AyYXhVSyZzPWNvbnN1bWVyc2VjcmV0Jng9YzQ-",
"dj0zaiZpPVVWOTM3aHViTXBHaiZzPWNvbnN1bWVyc2VjcmV0Jng9NGM-",
"dj0zaiZpPVV5cVVxSk1xSGhCQSZzPWNvbnN1bWVyc2VjcmV0Jng9NmE-",
"dj0zaiZpPUZ2MGZNT3VKT0NxbyZzPWNvbnN1bWVyc2VjcmV0Jng9ODk-",
"dj0zaiZpPXE5b0JmazBsaG5NcCZzPWNvbnN1bWVyc2VjcmV0Jng9NTc-",
"dj0zaiZpPXA1bzdRY0R4RFVDWiZzPWNvbnN1bWVyc2VjcmV0Jng9ZDg-",
"dj0zaiZpPUJSdmdoZ2lFd3NmYSZzPWNvbnN1bWVyc2VjcmV0Jng9MzA-",
"dj0zaiZpPVg0R1RHekNuM2FxbiZzPWNvbnN1bWVyc2VjcmV0Jng9OTY-",
"dj0zaiZpPW9NU2F2YnI1MTRwUSZzPWNvbnN1bWVyc2VjcmV0Jng9MmQ-",
"dj0zaiZpPU1uTHB0OW8wcmwyQiZzPWNvbnN1bWVyc2VjcmV0Jng9YWU-",
"dj0zaiZpPVI2YWtkdUU3S2NwZSZzPWNvbnN1bWVyc2VjcmV0Jng9ZTc-",
"dj0zaiZpPWl3VTgxdW9rQlJDSSZzPWNvbnN1bWVyc2VjcmV0Jng9NDc-",
"dj0zaiZpPXZqSXlndXpDQkpCYyZzPWNvbnN1bWVyc2VjcmV0Jng9NDU-",
"dj0zaiZpPXR1SEV0RjZNYUtVUCZzPWNvbnN1bWVyc2VjcmV0Jng9MmI-",
"dj0zaiZpPWJjdDR5ZGtRYU0wMSZzPWNvbnN1bWVyc2VjcmV0Jng9MWM-",
"dj0zaiZpPXRKTGFjMHJyOGhwSiZzPWNvbnN1bWVyc2VjcmV0Jng9YTI-",
"dj0zaiZpPWZ6SGpkWmlTZFM5cCZzPWNvbnN1bWVyc2VjcmV0Jng9OWQ-",
"dj0zaiZpPXpVQjR0dWxZcFJnZiZzPWNvbnN1bWVyc2VjcmV0Jng9OWM-",
"dj0zaiZpPW9pMFVXR2Fhc0VMUSZzPWNvbnN1bWVyc2VjcmV0Jng9NWI-",
"dj0zaiZpPTBXa3BYN0hhOUNNbiZzPWNvbnN1bWVyc2VjcmV0Jng9NTY-",
"dj0zaiZpPU5mc2RabnJSSVVhNyZzPWNvbnN1bWVyc2VjcmV0Jng9ZDM-",
"dj0zaiZpPWFmdzBhTjRXbXkzZCZzPWNvbnN1bWVyc2VjcmV0Jng9NzQ-",
"dj0zaiZpPWdxeFFyZE5aNDlpVSZzPWNvbnN1bWVyc2VjcmV0Jng9Yjc-",
"dj0zaiZpPXpIdFBpeEl0MlM4byZzPWNvbnN1bWVyc2VjcmV0Jng9ZTU-",
"dj0zaiZpPWlycHBqSFhDUU1TMSZzPWNvbnN1bWVyc2VjcmV0Jng9Njg-",
"dj0zaiZpPVJCRGJNeEo3OXpNTyZzPWNvbnN1bWVyc2VjcmV0Jng9MWI-",
"dj0zaiZpPXVXRld0YUM0TlN0cyZzPWNvbnN1bWVyc2VjcmV0Jng9MjA-",
"dj0zaiZpPUd2NGxMNE0zVkhoSyZzPWNvbnN1bWVyc2VjcmV0Jng9MjM-",
"dj0zaiZpPWEzTFVzT2VUa2RSaCZzPWNvbnN1bWVyc2VjcmV0Jng9ZTg-",
"dj0zaiZpPU1aeXdmWHJtbnMwWCZzPWNvbnN1bWVyc2VjcmV0Jng9NjM-",
"dj0zaiZpPTI4SVlCQUhNNUdZaSZzPWNvbnN1bWVyc2VjcmV0Jng9MzM-",
"dj0zaiZpPVR4ekNLdndCQ1hVQSZzPWNvbnN1bWVyc2VjcmV0Jng9Y2I-",
"dj0zaiZpPVE4Q203dXpqcnRKcSZzPWNvbnN1bWVyc2VjcmV0Jng9NGY-",
"dj0zaiZpPXI1QlZ0MXhzSFJzWiZzPWNvbnN1bWVyc2VjcmV0Jng9NTU-",
"dj0zaiZpPTRzTXNPMWFnbk9lQyZzPWNvbnN1bWVyc2VjcmV0Jng9ZWE-",
"dj0zaiZpPUhwY0djd3lvUmhERCZzPWNvbnN1bWVyc2VjcmV0Jng9MDk-",
"dj0zaiZpPTMydmlRNEJGVkFtVSZzPWNvbnN1bWVyc2VjcmV0Jng9YTM-",
"dj0zaiZpPWF4d2toNlNWcHFaeSZzPWNvbnN1bWVyc2VjcmV0Jng9Zjk-",
"dj0zaiZpPUNOQ2ZKWXk3V0FsRiZzPWNvbnN1bWVyc2VjcmV0Jng9NTQ-",
"dj0zaiZpPXByYTNZT3JvQnZCWSZzPWNvbnN1bWVyc2VjcmV0Jng9YWM-",
"dj0zaiZpPXNBTnp0RHUxV05IZSZzPWNvbnN1bWVyc2VjcmV0Jng9Y2E-",
"dj0zaiZpPXpxOE9zY3d3UGMwWiZzPWNvbnN1bWVyc2VjcmV0Jng9Y2Q-",
"dj0zaiZpPTkwWEFKS1pEbGhkWSZzPWNvbnN1bWVyc2VjcmV0Jng9YzU-",
"dj0zaiZpPUVOOFJqOVU3Y0tJaiZzPWNvbnN1bWVyc2VjcmV0Jng9NDE-",
"dj0zaiZpPWQyNlMwTW52UUtYZyZzPWNvbnN1bWVyc2VjcmV0Jng9MzY-",
"dj0zaiZpPWFMOVBBWDJPVjVhNSZzPWNvbnN1bWVyc2VjcmV0Jng9MGE-",
"dj0zaiZpPUZPSDZMRmJqUUo4MiZzPWNvbnN1bWVyc2VjcmV0Jng9OWU-",
"dj0zaiZpPTBsS1ZyM0pSMTliZyZzPWNvbnN1bWVyc2VjcmV0Jng9MTA-",
"dj0zaiZpPW5Gc0wwNk5ydzJETyZzPWNvbnN1bWVyc2VjcmV0Jng9OWE-",
"dj0zaiZpPW1PaXlmWjVnQjdNdiZzPWNvbnN1bWVyc2VjcmV0Jng9MmM-",
"dj0zaiZpPVdaSWV2djJzRGtyUCZzPWNvbnN1bWVyc2VjcmV0Jng9ZjQ-",
"dj0zaiZpPWtGT1F6eHFyOEhHYSZzPWNvbnN1bWVyc2VjcmV0Jng9MmQ-",
);
?>