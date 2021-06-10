<?php
    $client = curl_init();
	curl_setopt($client, CURLOPT_URL, "https://steemsentinels.com/update/phishers");//set url to cronjob.php here, e.g https://example.com/controller/cronjob.php
	curl_setopt($client, CURLOPT_HEADER, 0);
	curl_setopt($client, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($client, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_exec($client);
	curl_close($client);
?>