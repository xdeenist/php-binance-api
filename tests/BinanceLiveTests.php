<?php

use Binance\API;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php-binance-api.php'; // Adjust path if needed



class BinanceLiveTests extends TestCase
{
    private Binance\API $spotBinance;
    private Binance\API $futuresBinance;

    public function setUp(): void {
        $this->spotBinance = new API('X4BHNSimXOK6RKs2FcKqExquJtHjMxz5hWqF0BBeVnfa5bKFMk7X0wtkfEz0cPrJ', 'x8gLihunpNq0d46F2q0TWJmeCDahX5LMXSlv3lSFNbMI3rujSOpTDKdhbcmPSf2i');
        $this->spotBinance->useTestnet = true;

        $this->futuresBinance = new API('227719da8d8499e8d3461587d19f259c0b39c2b462a77c9b748a6119abd74401', 'b14b935f9cfacc5dec829008733c40da0588051f29a44625c34967b45c11d73c');
        $this->futuresBinance->useTestnet = true;
    }
    public function testPricesSpot()
    {
        $res = $this->spotBinance->prices();
        $this->assertIsArray($res);
        $this->assertArrayHasKey('BTCUSDT', $res);
        $this->assertIsString($res['BTCUSDT']);
    }

    public function testPricesFutures()
    {
        $res = $this->futuresBinance->futuresPrices();
        $this->assertIsArray($res);
        $this->assertArrayHasKey('BTCUSDT', $res);
        $this->assertIsString($res['BTCUSDT']);
    }

    public function testBalanceSpot()
    {
        $res = $this->spotBinance->balances();
        $this->assertIsArray($res);
        // $this->assertArrayHasKey('USDT', $res);
        // $this->assertIsString($res['USDT']['free']);
    }

    public function testBalanceFutures()
    {
        $res = $this->futuresBinance->futuresAccount();
        $this->assertIsArray($res);
        $assets = $res['assets'];
        $first = $assets[0];
        $this->assertArrayHasKey('asset', $first);
        $this->assertArrayHasKey('walletBalance', $first);
    }
}
