<?php

date_default_timezone_set("Asia/Vientiane");
$todaytime = date("Y-m-d H:i:s");  

$hookString = file_get_contents('php://input');

$log_string = $todaytime. " Received data...".$hookString.PHP_EOL;
file_put_contents("log.txt", $log_string.PHP_EOL, FILE_APPEND);

// prototype - ticker@time@identifier@order_action@prev_position@curr_position@price@comment
// prototype - {"ticker": "ticker", "time": "time", "identifier": "identifier", "order_action":"order_action", "prev_position":"prev_position", "curr_position":"curr_position", "price":"price", "comment":"comment"}
$hookData = json_decode($hookString, true);

var_dump($hookData);

if ($hookData == NULL) {

	$hookData = explode('@', $hookString);
	var_dump($hookData);

}

$count = count($hookData);	
$log_string = $todaytime. " Count of Datas...".$count.PHP_EOL;
var_dump($log_string);
file_put_contents("log.txt", $log_string.PHP_EOL, FILE_APPEND);

if ($hookData != FALSE and $count >= 8) { 
	
	$hookData = array_values($hookData);
	$ticker = trim($hookData[0]);
	$time = trim($hookData[1]);
	$identifier = trim($hookData[2]);
	$order_action = trim($hookData[3]);
	$prev_position = trim($hookData[4]);
	$curr_position = trim($hookData[5]);
	$price = trim($hookData[6]);
	$comment = trim($hookData[7]);

//	$postRequest = array(
//	    'ticker' => $ticker,
//	    'time' => $time,
//	    'identifier' => $identifier,
//	    'order_action' => $order_action,
//	    'prev_position' => $prev_position,
//	    'curr_position' => $curr_position,
//	    'price' => $price,
//	    'comment' => $comment
//	);

	$post_url = "https://xxx.io/trade_signal/trading_Test";

	$log_string = $todaytime. " Post URL...".$post_url.PHP_EOL;
	var_dump($log_string);
	file_put_contents("log.txt", $log_string.PHP_EOL, FILE_APPEND);


	$sending_message = "Nothing";

	$ch = curl_init();
	
	if ($identifier == "1") { // Indicator

		$sending_message = "Hello World!";

	} elseif ($identifier == "2") { // Strategy

		$sending_message = "Hello World!";

		if ($order_action == "buy") {

			if ($prev_position == "short") {

				if ($curr_position == "short") {

				} elseif ($curr_position == "flat") {
					
					$sending_message = "[{\"message_type\": \"bot\",  \"bot_id\": 1000001,  \"email_token\": \"c8e10f5a-1111-2222-3333-0a2132a9bc92\",  \"delay_seconds\": 0,  \"action\": \"close_at_market_price_all\"}, {  \"message_type\": \"bot\",  \"bot_id\": 1000002,  \"email_token\": \"c8e10f5a-1111-2222-3333-0a2132a9bc92\",  \"delay_seconds\": 6}]";

				} elseif ($curr_position == "long") {

				}

			} elseif ($prev_position == "flat") {

			} elseif ($prev_position == "long") {

			}

		} elseif ($order_action == "sell") {

			if ($prev_position == "short") {

			} elseif ($prev_position == "flat") {

			} elseif ($prev_position == "long") {

			}

		}
	}

	$log_string = $todaytime. " Sending message...".$sending_message.PHP_EOL;
	var_dump($log_string);
	file_put_contents("log.txt", $log_string.PHP_EOL, FILE_APPEND);

	$result = FALSE;
	if ($sending_message != "Nothing") {

		curl_setopt($ch, CURLOPT_URL, $post_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sending_message ); 

		//if need to send text format
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain; charset=utf-8')); 

		//if need to send json format
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 


		$result=curl_exec($ch);
	}

	if ($result == FALSE) {
		$result = "FAIL";
	} else {
		$result = "SUCCESS";
	}

	$log_string = $todaytime. " Sending result...".$result.PHP_EOL;
	var_dump($log_string);
	file_put_contents("log.txt", $log_string.PHP_EOL, FILE_APPEND);

} else {

	$log_string = $todaytime. " Parsing error...".PHP_EOL;
	var_dump($log_string);
	file_put_contents("log.txt", $log_string.PHP_EOL, FILE_APPEND);
	file_put_contents("error_log.txt", $log_string.PHP_EOL, FILE_APPEND);

}

?>