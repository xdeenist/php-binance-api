<?php

use Binance\API;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php-binance-api.php'; // Adjust path if needed



class BinanceLiveTests extends TestCase
{
    private Binance\API $binance;

    public function setUp(): void {
        $this->binance = new API('api_key', 'api_secret');
        $this->binance->useTestnet = true;
    }
    public function testPricesSpot()
    {
        $res = $this->binance->prices();
        $this->assertIsArray($res);
        $this->assertArrayHasKey('BTCUSDT', $res);
        $this->assertIsString($res['BTCUSDT']);
    }

    public function testPricesFutures()
    {
        $res = $this->binance->futuresPrices();
        $this->assertIsArray($res);
        $this->assertArrayHasKey('BTCUSDT', $res);
        $this->assertIsString($res['BTCUSDT']);
    }
}
