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
        BinanceStaticTests::$capturedUrl = $endpoint;
        // curl_setopt($curl, CURLOPT_URL, $endpoint);
    }

    protected function curl_set_body($curl, $option, $query) {
        // curl_setopt($curl, $option, $query);
        BinanceStaticTests::$capturedBody = $query;
    }
}

class BinanceStaticTests extends TestCase
{
    public static $capturedUrl = null;
    public static $capturedBody = null;
    private MockBinanceAPI $binance;

    private $SPOT_ORDER_PREFIX     = "x-HNA2TXFJ";
	private $CONTRACT_ORDER_PREFIX = "x-Cb7ytekJ";

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


    public function testSpotOrder()
    {
        try  {
            $this->binance->order('BUY', 'BTCUSDT', 1, 1000);
        } catch(\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals("BTCUSDT", $params['symbol']);
        $this->assertEquals("BUY", $params['side']);
        $this->assertEquals("LIMIT", $params['type']);
        $this->assertEquals(1, $params['quantity']);
        $this->assertEquals(1000, $params['price']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testFuturesOrder()
    {
        try  {
            $this->binance->futuresOrder('BUY', 'BTCUSDT', 1, 1000);
        } catch(\Throwable $e) {

        }
        $this->assertEquals("https://fapi.binance.com/fapi/v1/order", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);
        print_r($params);

        $this->assertEquals("BTCUSDT", $params['symbol']);
        $this->assertEquals("BUY", $params['side']);
        $this->assertEquals("LIMIT", $params['type']);
        $this->assertEquals(1, $params['quantity']);
        $this->assertEquals(1000, $params['price']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->CONTRACT_ORDER_PREFIX));
    }
}
