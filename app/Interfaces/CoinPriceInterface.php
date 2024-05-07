<?php

namespace App\Interfaces;

interface CoinPriceInterface 
{
    public function getAllCoinPrice($request , $limit);
    public function getCoinPriceId($coinPriceId);
    public function deleteCoinPrice($coinPriceId);
    public function statusCoinPrice($coinPriceId);
    public function createCoinPrice(array $request);
}