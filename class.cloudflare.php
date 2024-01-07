<?php

class Cloudflare {
    public $endpoint = "https://api.cloudflare.com/client/v4";
    public $apiToken = "";
    public $zoneId = "";

    public function __construct($token, $zone) {
        $this->apiToken = $token;
        $this->zoneId = $zone;
    }

    function get($uri) {
        $ch = curl_init();
        if (substr($uri, 0, 1) != "/") { $uri = "/{$uri}"; }
        curl_setopt($ch, CURLOPT_URL, "{$this->endpoint}{$uri}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = array(
            "Authorization: Bearer {$this->apiToken}"
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);

        return json_decode($body, true);
    }


    function delete($uri) {
        $ch = curl_init();
        if (substr($uri, 0, 1) != "/") { $uri = "/{$uri}"; }
        curl_setopt($ch, CURLOPT_URL, "{$this->endpoint}{$uri}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = array(
            "Authorization: Bearer {$this->apiToken}"
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);

        return json_decode($body, true);
    }

    function post($uri, $obj = array()) {
        $ch = curl_init();
        if (substr($uri, 0, 1) != "/") { $uri = "/{$uri}"; }
        curl_setopt($ch, CURLOPT_URL, "{$this->endpoint}{$uri}");

        $headers = array(
            "Authorization: Bearer {$this->apiToken}",
            "Content-Type: application/json"
        );

        $payload = json_encode($obj);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);
        return json_decode($body, true);
    }

    function patch($uri, $obj = array()) {
        $ch = curl_init();
        if (substr($uri, 0, 1) != "/") { $uri = "/{$uri}"; }
        curl_setopt($ch, CURLOPT_URL, "{$this->endpoint}{$uri}");

        $headers = array(
            "Authorization: Bearer {$this->apiToken}",
            "Content-Type: application/json"
        );

        $payload = json_encode($obj);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);
        return json_decode($body, true);
    }

    public function getRecordId($record) {
        return $this->get("/zones/{$this->zoneId}/dns_records?name={$record}");
    }

    public function getRecord($recordId) {
        return $this->get("/zones/{$this->zoneId}/dns_records/{$recordId}");
    }

    public function addRecord($type, $name, $content, $ttl = 300) {
        $payload = array(
            "type" => $type,
            "name" => $name,
            "content" => $content,
            "ttl" => $ttl
        );
        return $this->post("/zones/{$this->zoneId}/dns_records", $payload);
    }

    public function deleteRecord($recordId) {
        return $this->delete("/zones/{$this->zoneId}/dns_records/{$recordId}");
    }

    public function updateRecord($recordId, $type, $name, $content, $ttl = 300) {
        $payload = array(
            "type" => $type,
            "name" => $name,
            "content" => $content,
            "ttl" => $ttl
        );
        return $this->patch("/zones/{$this->zoneId}/dns_records/{$recordId}", $payload);
    }
}
