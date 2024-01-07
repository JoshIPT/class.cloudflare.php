# class.cloudflare.php
Basic Cloudflare DNS class written for PHP
# Usage
```
require_once("class.cloudflare.php");
$apiKey = "abc123";
$zoneId = "987zxy";
$cf = new Cloudflare($apiKey, $zoneId);

var_dump($cf->getRecordId("my.site.com"));
var_dump($cf->getRecord("id_from_previous_call"));

$cf->addRecord("A", "my.site.com", "123.123.123", 300);
$cf->updateRecord("record_id", "A", "my.site.com", "123.123.1.1");
$cf->deleteRecord("record_id");
```
