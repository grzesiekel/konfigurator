<?php

namespace App\Services;

class BaselinkerService
{
    protected $token;

    public function __construct()
    {
        $this->token = config('services.baselinker.token');
    }

    public function getOrdersByStatus($statusId, $unconfirmed = true)
    {
        $params = [
            'token' => $this->token,
            'method' => 'getOrders',
            'parameters' => json_encode([
                'status_id' => $statusId,
                'get_unconfirmed_orders' => $unconfirmed
            ])
        ];

        $query = http_build_query($params);
        
        $contextData = [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                         "Content-Length: " . strlen($query) . "\r\n" .
                         "User-Agent: MyAgent/1.0\r\n",
            'content' => $query
        ];

        $context = stream_context_create(['http' => $contextData]);
        $result = file_get_contents('https://api.baselinker.com/connector.php', false, $context);

        return json_decode($result, true);
    }
}