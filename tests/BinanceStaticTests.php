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

    // Default values for the tests
    private $symbol = 'ETHUSDT';
    private $quantity = '1.234';
    private $price = '1000';
    private $type = 'LIMIT';
    private $orderid = '000';
    private $limit = 2;
    private $fromOrderId = 1;
    private $fromTradeId = 2;
    private $startTime = 0;
    private $endTime = 6;
    private $symbols = ['ETHUSDT','BTCUSDT'];
    private $asset = 'USDT';
    private $assets = ['IOST','AAVE','CHZ'];
    private $address = '0x1234567890abcdef1234567890abcdef12345678';
    private $amount = 10.1;
    private $addressTag = '123456';
    private $addressName = 'My Address';
    private $transactionFeeFlag =  true;
    private $network = 'TEST Network';
    private $fromSymbol = 'USDT';
    private $toSymbol = 'BNB';
    private $recvWindow = 1000;
    private $current = 2;
    private $tradeId = 3;
    private $side = 'BUY';
    private $test = false;
    private $interval = '15m';
    private $nbrDays = 6;
    private $baseAsset = 'ETH';
    private $quoteAsset = 'USDT';
    private $quoteQty = 10.3;
    private $fromId = 1;
    private $contractType = 'CURRENT_QUARTER';
    private $period =  '15m';
    private $origClientOrderId = 'test client order id';
    private $orderIdList = ['123456', '654321'];
    private $origClientOrderIdList = ['test client order id 1', 'test client order id 2'];
    private $countdownTime = 100;
    private $autoCloseType = 'LIQUIDATION';
    private $marginType = 'CROSSED';
    private $dualSidePosition = true;
    private $leverage = 10;
    private $multiAssetsMarginMode = true;
    private $positionSide = 'SHORT';
    private $incomeType = 'COMMISSION_REBATE';
    private $page = 3;
    private $downloadId = 'test download id';
    private $fromAsset = 'USDT';
    private $toAsset = 'BNB';
    private $fromAmount = '100';
    private $validTime = '10s';
    private $quoteId = 'test quote id';

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

    public function testSpotBuy()
    {
        try  {
            $this->binance->buy($this->symbol, $this->quantity, $this->price, $this->type);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->type, $params['type']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotBuyTest()
    {
        try  {
            $this->binance->buyTest($this->symbol, $this->quantity, $this->price, $this->type);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order/test", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->type, $params['type']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotSell()
    {
        try  {
            $this->binance->sell($this->symbol, $this->quantity, $this->price, $this->type);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->type, $params['type']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotSellTest()
    {
        try  {
            $this->binance->sellTest($this->symbol, $this->quantity, $this->price, $this->type);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order/test", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->type, $params['type']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotMarketQuoteBuy()
    {
        try  {
            $this->binance->marketQuoteBuy($this->symbol, $this->quantity);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quoteOrderQty']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotMarketQuoteBuyTest()
    {
        try  {
            $this->binance->marketQuoteBuyTest($this->symbol, $this->quantity);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order/test", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quoteOrderQty']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotMarketBuy()
    {
        try  {
            $this->binance->marketBuy($this->symbol, $this->quantity);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotMarketBuyTest()
    {
        try  {
            $this->binance->marketBuyTest($this->symbol, $this->quantity);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order/test", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotMarketQuoteSell()
    {
        try  {
            $this->binance->marketQuoteSell($this->symbol, 1);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals(1, $params['quoteOrderQty']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotMarketQuoteSellTest()
    {
        try  {
            $this->binance->marketQuoteSellTest($this->symbol, 1);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order/test", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals(1, $params['quoteOrderQty']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotMarketSell()
    {
        try  {
            $this->binance->marketSell($this->symbol, 1);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals(1, $params['quantity']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotMarketSellTest()
    {
        try  {
            $this->binance->marketSellTest($this->symbol, $this->quantity);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order/test", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX));
    }

    public function testSpotCancel()
    {
        try  {
            $this->binance->cancel($this->symbol, $this->orderid);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'orderId' => $this->orderid,
        ]);
        $endpoint = "https://api.binance.com/api/v3/order?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotOrderStatus()
    {
        try  {
            $this->binance->orderStatus($this->symbol, $this->orderid);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'orderId' => $this->orderid,
        ]);
        $endpoint = "https://api.binance.com/api/v3/order?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotOpenOrders()
    {
        try  {
            $this->binance->openOrders($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://api.binance.com/api/v3/openOrders?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotCancelOpenOrders()
    {
        try  {
            $this->binance->cancelOpenOrders($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://api.binance.com/api/v3/openOrders?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotOrders()
    {
        try  {
            $this->binance->orders($this->symbol, $this->limit, $this->fromOrderId);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
            'orderId' => $this->fromOrderId,
        ]);
        $endpoint = "https://api.binance.com/api/v3/allOrders?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotHistory()
    {
        try  {
            $this->binance->history($this->symbol, $this->limit, $this->fromTradeId, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
            'fromId' => $this->fromTradeId,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://api.binance.com/api/v3/myTrades?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotMyTrades()
    {
        try  {
            $this->binance->myTrades($this->symbol, $this->limit, $this->fromTradeId, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
            'fromId' => $this->fromTradeId,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://api.binance.com/api/v3/myTrades?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testUseServerTime()
    {
        try  {
            $this->binance->useServerTime();

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/time", self::$capturedUrl);
    }

    public function testTime()
    {
        try  {
            $this->binance->time();

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/time", self::$capturedUrl);
    }

    public function testFuturesOrder()
    {
        try  {
            $this->binance->futuresOrder('BUY', 'BTCUSDT', 1, 1000);
        } catch(\Throwable $e) {

        }
        $this->assertEquals("https://fapi.binance.com/fapi/v1/order", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals("BTCUSDT", $params['symbol']);
        $this->assertEquals("BUY", $params['side']);
        $this->assertEquals("LIMIT", $params['type']);
        $this->assertEquals(1, $params['quantity']);
        $this->assertEquals(1000, $params['price']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->CONTRACT_ORDER_PREFIX));
    }
}
