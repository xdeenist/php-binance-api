<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php-binance-api.php'; // Adjust path if needed


class MockBinanceAPI extends Binance\API
{
    public function curl_exec($handler)
    {
        return "";
    }

    protected function curl_set_url($curl, $endpoint) {
        BinanceApiTest::$capturedUrl = $endpoint;
        // curl_setopt($curl, CURLOPT_URL, $endpoint);
    }

    protected function curl_set_body($curl, $option, $query) {
        // curl_setopt($curl, $option, $query);
        BinanceApiTest::$capturedBody = $query;
    }
}

class BinanceApiTest extends TestCase
{
    public static $capturedUrl = null;
    public static $capturedBody = null;
    private MockBinanceAPI $binance;

    public function setUp(): void {
        $this->binance = new MockBinanceAPI('api_key', 'api_secret');
    }
    public function testPricesSpot()
    {
        try  {
            $this->binance->prices();
        } catch(\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/ticker/price", self::$capturedUrl);
    }
}
