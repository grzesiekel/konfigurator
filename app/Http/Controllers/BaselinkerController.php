<?php

namespace App\Http\Controllers;

use App\Services\BaselinkerService;
use Illuminate\Http\Request;

class BaselinkerController extends Controller
{
    protected $baselinker;

    public function __construct(BaselinkerService $baselinker)
    {
        $this->baselinker = $baselinker;
    }

    public function getOrdersByStatus($statusId)
    {
        $blOrders = $this->baselinker->getOrdersByStatus($statusId);
        
        return view('orders.print', [
            'blOrders' => $blOrders['orders'] ?? [],
            'statusId' => $statusId
        ]);
    }
}
