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
    private $stopprice = '1100';
    private $stoplimitprice = '900';
    private $type = 'LIMIT';
    private $orderid = '000';
    private $orderId = '1234567890';
    private $limit = 2;
    private $fromOrderId = 1;
    private $fromTradeId = 2;
    private $startTime = 1;
    private $endTime = 6;
    private $symbols = ['ETHUSDT','BTCUSDT'];
    private $asset = 'USDT';
    private $assets = ['IOST','AAVE','CHZ'];
    private $address = '0x1234567890abcdef1234567890abcdef12345678';
    private $amount = 10.1;
    private $addressTag = '123456';
    private $addressName = 'MyAddress';
    private $transactionFeeFlag =  true;
    private $network = 'TESTNetwork';
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
    private $downloadId = 'testDownloadId';
    private $fromAsset = 'USDT';
    private $toAsset = 'BNB';
    private $fromAmount = '100';
    private $validTime = '10s';
    private $quoteId = 'testQuoteId';

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

    public function testSpotExchangeInfo()
    {
        try  {
            $this->binance->exchangeInfo($this->symbols);

        } catch (\Throwable $e) {

        }
        $query ='symbols=["' . implode('","', $this->symbols) . '"]';
        $endpoint = "https://api.binance.com/api/v3/exchangeInfo?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testAssetDetail()
    {
        try  {
            $this->binance->assetDetail($this->asset);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'asset' => $this->asset,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/asset/assetDetail?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotDustLog()
    {
        try  {
            $this->binance->dustLog($this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/asset/dribblet?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotTradeFee()
    {
        try  {
            $this->binance->tradeFee($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/asset/tradeFee?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotCommissionFee()
    {
        try  {
            $this->binance->commissionFee($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/asset/tradeFee?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testWithdraw()
    {
        try  {
            $this->binance->withdraw($this->asset, $this->address, $this->amount, $this->addressTag, $this->addressName, $this->transactionFeeFlag, $this->network, $this->orderId);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/sapi/v1/capital/withdraw/apply", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->asset, $params['coin']);
        $this->assertEquals($this->address, $params['address']);
        $this->assertEquals($this->amount, $params['amount']);
        $this->assertEquals($this->addressTag, $params['addressTag']);
        $this->assertEquals($this->addressName, $params['name']);
        $this->assertEquals($this->transactionFeeFlag, $params['transactionFeeFlag']);
        $this->assertEquals($this->network, $params['network']);
        $this->assertEquals($this->orderId, $params['withdrawOrderId']);
    }

    public function testDepositAddress()
    {
        try  {
            $this->binance->depositAddress($this->asset, $this->network);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'coin' => $this->asset,
            'network' => $this->network,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/capital/deposit/address?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testDepositHistory()
    {
        try  {
            $this->binance->depositHistory($this->asset);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'coin' => $this->asset,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/capital/deposit/hisrec?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testWithdrawHistory()
    {
        try  {
            $this->binance->withdrawHistory($this->asset);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'coin' => $this->asset,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/capital/withdraw/history?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testWithdrawFee()
    {
        try  {
            $this->binance->withdrawFee($this->asset);

        } catch (\Throwable $e) {

        }
        $endpoint = "https://api.binance.com/sapi/v1/asset/assetDetail";
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testTransfer()
    {
        try  {
            $this->binance->transfer($this->type, $this->asset, $this->amount, $this->fromSymbol, $this->toSymbol, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v1/asset/transfer", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->type, $params['type']);
        $this->assertEquals($this->asset, $params['asset']);
        $this->assertEquals($this->amount, $params['amount']);
        $this->assertEquals($this->fromSymbol, $params['fromSymbol']);
        $this->assertEquals($this->toSymbol, $params['toSymbol']);
        $this->assertEquals($this->recvWindow, $params['recvWindow']);
    }

    public function testTransfersHistory()
    {
        try  {
            $this->binance->transfersHistory($this->type, $this->startTime, $this->endTime, $this->limit, $this->current, $this->fromSymbol, $this->toSymbol, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'type' => $this->type,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'size' => $this->limit,
            'current' => $this->current,
            'fromSymbol' => $this->fromSymbol,
            'toSymbol' => $this->toSymbol,
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://api.binance.com/api/v1/asset/transfer?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotPrices()
    {
        try  {
            $this->binance->prices();

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/ticker/price", self::$capturedUrl);
    }

    public function testSpotPrice()
    {
        try  {
            $this->binance->price($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://api.binance.com/api/v3/ticker/price?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testSpotBookPrices()
    {
        try  {
            $this->binance->bookPrices();

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/ticker/bookTicker", self::$capturedUrl);
    }

    public function testSpotAccount()
    {
        try  {
            $this->binance->account();

        } catch (\Throwable $e) {

        }
        $this->assertTrue(str_starts_with(self::$capturedUrl, "https://api.binance.com/api/v3/account"));
    }

    public function testSpotPrevDay()
    {
        try  {
            $this->binance->prevDay($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://api.binance.com/api/v1/ticker/24hr?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSotAggTrades()
    {
        try  {
            $this->binance->aggTrades($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://api.binance.com/api/v1/aggTrades?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotHistoricalTrades()
    {
        try  {
            $this->binance->historicalTrades($this->symbol, $this->limit);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
        ]);
        $endpoint = "https://api.binance.com/api/v3/trades?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotHistoricalTradesWithTradeId()
    {
        try  {
            $this->binance->historicalTrades($this->symbol, $this->limit, $this->tradeId);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
            'fromId' => $this->tradeId,
        ]);
        $endpoint = "https://api.binance.com/api/v3/historicalTrades?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotDepth()
    {
        try  {
            $this->binance->depth($this->symbol, $this->limit);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
        ]);
        $endpoint = "https://api.binance.com/api/v1/depth?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotBalances()
    {
        try  {
            $this->binance->balances();

        } catch (\Throwable $e) {

        }
        $endpoint = "https://api.binance.com/api/v3/account";
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));

        parse_str(self::$capturedBody, $params);
    }

    public function testFuturesBalances()
    {
        try  {
            $this->binance->balances('futures', $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v2/balance?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesBalancesV3()
    {
        try  {
            $this->binance->balances('futures', $this->recvWindow, 'v3');

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v3/balance?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testCoins()
    {
        try  {
            $this->binance->coins();

        } catch (\Throwable $e) {

        }
        $this->assertTrue(str_starts_with(self::$capturedUrl, "https://api.binance.com/sapi/v1/capital/config/getall"));
    }

    public function testSpotCandlesticks()
    {
        try  {
            $this->binance->candlesticks($this->symbol, $this->interval, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'interval' => $this->interval,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://api.binance.com/api/v1/klines?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testSpotAccountSnapshot()
    {
        try  {
            $this->binance->accountSnapshot('SPOT', $this->nbrDays, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'type' => 'SPOT',
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'limit' => $this->nbrDays,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/accountSnapshot?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testMarginAccountSnapshot()
    {
        try  {
            $this->binance->accountSnapshot('MARGIN', $this->nbrDays, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'type' => 'MARGIN',
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'limit' => $this->nbrDays,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/accountSnapshot?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesAccountSnapshot()
    {
        try  {
            $this->binance->accountSnapshot('FUTURES', $this->nbrDays, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'type' => 'FUTURES',
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'limit' => $this->nbrDays,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/accountSnapshot?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testAccountStatus()
    {
        try  {
            $this->binance->accountStatus();

        } catch (\Throwable $e) {

        }
        $this->assertTrue(str_starts_with(self::$capturedUrl, "https://api.binance.com/sapi/v1/account/status"));
    }

    public function testApiRestrictions()
    {
        try  {
            $this->binance->apiRestrictions();

        } catch (\Throwable $e) {

        }
        $this->assertTrue(str_starts_with(self::$capturedUrl, "https://api.binance.com/sapi/v1/account/apiRestrictions"));
    }

    public function testApiTradingStatus()
    {
        try  {
            $this->binance->apiTradingStatus();

        } catch (\Throwable $e) {

        }
        $this->assertTrue(str_starts_with(self::$capturedUrl, "https://api.binance.com/sapi/v1/account/apiTradingStatus"));
    }

    public function testOcoOrder()
    {
        try  {
            $this->binance->ocoOrder($this->side, $this->symbol, $this->quantity, $this->price, $this->stopprice, $this->stoplimitprice, $this->stoplimittimeinforce);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://api.binance.com/api/v3/order/oco", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->side, $params['side']);
        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->stopprice, $params['stopPrice']);
        $this->assertEquals($this->stoplimitprice, $params['stopLimitPrice']);
        $this->assertEquals('GTC', $params['stopLimitTimeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->SPOT_ORDER_PREFIX_ORDER_PREFIX));
    }

    public function testSpotAvgPrice()
    {
        try  {
            $this->binance->avgPrice($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://api.binance.com/api/v3/avgPrice?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testBswapQuote()
    {
        try  {
            $this->binance->bswapQuote($this->baseAsset, $this->quoteAsset, $this->quoteQty);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'quoteAsset' => $this->quoteAsset,
            'baseAsset' => $this->baseAsset,
            'quoteQty' => $this->quoteQty,
        ]);
        $endpoint = "https://api.binance.com/sapi/v1/bswap/quote?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesTime()
    {
        try  {
            $this->binance->futuresTime();

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://fapi.binance.com/fapi/v1/time", self::$capturedUrl);
    }

    public function testFuturesExchangeInfo()
    {
        try  {
            $this->binance->futuresExchangeInfo();

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://fapi.binance.com/fapi/v1/exchangeInfo", self::$capturedUrl);
    }

    public function testFuturesDepth()
    {
        try  {
            $this->binance->futuresDepth($this->symbol, $this->limit);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/depth?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesRecentTrades()
    {
        try  {
            $this->binance->futuresRecentTrades($this->symbol, $this->limit);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/trades?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesHistoricalTrades()
    {
        try  {
            $this->binance->futuresHistoricalTrades($this->symbol, $this->limit, $this->tradeId);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
            'fromId' => $this->tradeId,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/historicalTrades?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesAggTrades()
    {
        try  {
            $this->binance->futuresAggTrades($this->symbol, $this->fromId, $this->startTime, $this->endTime, $this->limit);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'fromId' => $this->fromId,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'limit' => $this->limit,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/aggTrades?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesCandlesticks()
    {
        try  {
            $this->binance->futuresCandlesticks($this->symbol, $this->interval, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'interval' => $this->interval,
            'symbol' => $this->symbol,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/klines?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesContinuousCandlesticks()
    {
        try  {
            $this->binance->futuresContinuousCandlesticks($this->symbol, $this->interval, $this->limit, $this->startTime, $this->endTime, $this->contractType);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'interval' => $this->interval,
            'pair' => $this->symbol,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'contractType' => $this->contractType,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/continuousKlines?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesIndexPriceCandlesticks()
    {
        try  {
            $this->binance->futuresIndexPriceCandlesticks($this->symbol, $this->interval, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'interval' => $this->interval,
            'pair' => $this->symbol,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/indexPriceKlines?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesMarkPriceCandlesticks()
    {
        try  {
            $this->binance->futuresMarkPriceCandlesticks($this->symbol, $this->interval, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'interval' => $this->interval,
            'symbol' => $this->symbol,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/markPriceKlines?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesPremiumIndexCandlesticks()
    {
        try  {
            $this->binance->futuresPremiumIndexCandlesticks($this->symbol, $this->interval, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'interval' => $this->interval,
            'symbol' => $this->symbol,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/premiumIndexKlines?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesMarkPrice()
    {
        try  {
            $this->binance->futuresMarkPrice($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/premiumIndex?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesFundingRateHistory()
    {
        try  {
            $this->binance->futuresFundingRateHistory($this->symbol, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/fundingRate?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesFundingInfo()
    {
        try  {
            $this->binance->futuresFundingInfo();

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://fapi.binance.com/fapi/v1/fundingInfo", self::$capturedUrl);
    }

    public function testFuturesPrevDay()
    {
        try  {
            $this->binance->futuresPrevDay($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/ticker/24hr?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesPrice()
    {
        try  {
            $this->binance->futuresPrice($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/ticker/price?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesPrices()
    {
        try  {
            $this->binance->futuresPrices();

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://fapi.binance.com/fapi/v1/ticker/price", self::$capturedUrl);
    }

    public function testFuturesPriceV2()
    {
        try  {
            $this->binance->futuresPriceV2($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v2/ticker/price?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesSymbolOrderBookTicker()
    {
        try  {
            $this->binance->futuresSymbolOrderBookTicker($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/ticker/bookTicker?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesDeliveryPrice()
    {
        try  {
            $this->binance->futuresDeliveryPrice($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'pair' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/futures/data/delivery-price?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesOpenInterest()
    {
        try  {
            $this->binance->futuresOpenInterest($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/openInterest?" . $query;
        $this->assertEquals(self::$capturedUrl, $endpoint);
    }

    public function testFuturesOpenInterestHistory()
    {
        try  {
            $this->binance->futuresOpenInterestHistory($this->symbol, $this->period, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'period' => $this->period,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/futures/data/openInterestHist?" . $query;
        $this->assertEquals($endpoint, self::$capturedUrl);
    }

    public function testFuturesTopLongShortPositionRatio()
    {
        try  {
            $this->binance->futuresTopLongShortPositionRatio($this->symbol, $this->period, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'period' => $this->period,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/futures/data/topLongShortPositionRatio?" . $query;
        $this->assertEquals($endpoint, self::$capturedUrl);
    }

    public function testFuturesTopLongShortAccountRatio()
    {
        try  {
            $this->binance->futuresTopLongShortAccountRatio($this->symbol, $this->period, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'period' => $this->period,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/futures/data/topLongShortAccountRatio?" . $query;
        $this->assertEquals($endpoint, self::$capturedUrl);
    }

    public function testFuturesGlobalLongShortAccountRatio()
    {
        try  {
            $this->binance->futuresGlobalLongShortAccountRatio($this->symbol, $this->period, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'period' => $this->period,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/futures/data/globalLongShortAccountRatio?" . $query;
        $this->assertEquals($endpoint, self::$capturedUrl);
    }

    public function testFuturesTakerLongShortRatio()
    {
        try  {
            $this->binance->futuresTakerLongShortRatio($this->symbol, $this->period, $this->limit, $this->startTime, $this->endTime);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'period' => $this->period,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/futures/data/takerlongshortRatio?" . $query;
        $this->assertEquals($endpoint, self::$capturedUrl);
    }

    public function testFuturesBasis()
    {
        try  {
            $this->binance->futuresBasis($this->symbol, $this->period, $this->limit, $this->startTime, $this->endTime, $this->contractType);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'pair' => $this->symbol,
            'period' => $this->period,
            'contractType' => $this->contractType,
            'limit' => $this->limit,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
        $endpoint = "https://fapi.binance.com/futures/data/basis?" . $query;
        $this->assertEquals($endpoint, self::$capturedUrl);
    }

    public function testFuturesIndexInfo()
    {
        try  {
            $this->binance->futuresIndexInfo($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/indexInfo?" . $query;
        $this->assertEquals($endpoint, self::$capturedUrl);
    }

    public function testFuturesAssetIndex()
    {
        try  {
            $this->binance->futuresAssetIndex($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/assetIndex?" . $query;
        $this->assertEquals($endpoint, self::$capturedUrl);
    }

    public function testFuturesConstituents()
    {
        try  {
            $this->binance->futuresConstituents($this->symbol);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/indexInfo?" . $query;
        $this->assertEquals($endpoint, self::$capturedUrl);
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

    public function testFuturesBuy()
    {
        try  {
            $this->binance->futuresBuy($this->symbol, $this->quantity, $this->price, $this->type);

        } catch (\Throwable $e) {

        }

        $endpoint = "https://fapi.binance.com/fapi/v1/order";
        $this->assertEquals($endpoint, self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->type, $params['type']);
        $this->assertEquals("BUY", $params['side']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->CONTRACT_ORDER_PREFIX));
    }

    public function testFuturesBuyTest()
    {
        try  {
            $this->binance->futuresBuyTest($this->symbol, $this->quantity, $this->price, $this->type);

        } catch (\Throwable $e) {

        }

        $endpoint = "https://fapi.binance.com/fapi/v1/order/test";
        $this->assertEquals($endpoint, self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->type, $params['type']);
        $this->assertEquals("BUY", $params['side']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->CONTRACT_ORDER_PREFIX));
    }

    public function testFuturesSell()
    {
        try  {
            $this->binance->futuresSell($this->symbol, $this->quantity, $this->price, $this->type);

        } catch (\Throwable $e) {

        }

        $endpoint = "https://fapi.binance.com/fapi/v1/order";
        $this->assertEquals($endpoint, self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->type, $params['type']);
        $this->assertEquals("SELL", $params['side']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->CONTRACT_ORDER_PREFIX));
    }

    public function testFuturesSellTest()
    {
        try  {
            $this->binance->futuresSellTest($this->symbol, $this->quantity, $this->price, $this->type);

        } catch (\Throwable $e) {

        }

        $endpoint = "https://fapi.binance.com/fapi/v1/order/test";
        $this->assertEquals($endpoint, self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->type, $params['type']);
        $this->assertEquals("SELL", $params['side']);
        $this->assertEquals("GTC", $params['timeInForce']);
        $this->assertTrue(str_starts_with($params['newClientOrderId'], $this->CONTRACT_ORDER_PREFIX));
    }

    public function testFuturesBatchOrders()
    {
        $order = [
            'symbol' => $this->symbol,
            'side' => $this->side,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'type' => $this->type,
        ];

        try  {
            $this->binance->futuresBatchOrders([ $order ], $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $endpoint = "https://fapi.binance.com/fapi/v1/batchOrders?batchOrders=";
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));

        $ordersString = substr(self::$capturedUrl, strlen($endpoint));

        $orders = json_decode(urldecode($ordersString), true);
        $this->assertEquals($this->symbol, $orders[0]['symbol']);
        $this->assertEquals($this->side, $orders[0]['side']);
        $this->assertEquals($this->quantity, $orders[0]['quantity']);
        $this->assertEquals($this->price, $orders[0]['price']);
        $this->assertEquals($this->type, $orders[0]['type']);
        $this->assertEquals("GTC", $orders[0]['timeInForce']);
        $this->assertTrue(str_starts_with($orders[0]['newClientOrderId'], $this->CONTRACT_ORDER_PREFIX));

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->recvWindow, $params['recvWindow']);
    }

    public function testFuturesEditOrder()
    {
        try  {
            $this->binance->futuresEditOrder($this->symbol, $this->side, $this->quantity, $this->price, $this->orderId);

        } catch (\Throwable $e) {

        }
        $endpoint = "https://fapi.binance.com/fapi/v1/order?";
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));

        $orderString = substr(self::$capturedUrl, strlen($endpoint));
        parse_str($orderString, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->side, $params['side']);
        $this->assertEquals($this->quantity, $params['quantity']);
        $this->assertEquals($this->price, $params['price']);
        $this->assertEquals($this->paramsId, $params['paramsId']);
        $this->assertEquals("GTC", $params['timeInForce']);
    }

    public function testFuturesEditOrders()
    {
        $order = [
            'symbol' => $this->symbol,
            'side' => $this->side,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'orderId' => $this->orderId,
        ];

        try  {
            $this->binance->futuresEditOrders([ $order ], $this->recvWindow);

        } catch (\Throwable $e) {
            print_r($e);
        }
        $endpoint = "https://fapi.binance.com/fapi/v1/batchOrders?";
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));

        $queryString = substr(self::$capturedUrl, strlen($endpoint));
        parse_str($queryString, $params);
        $this->assertEquals($this->recvWindow, $params['recvWindow']);

        $orders = json_decode($params['batchOrders'], true);
        $this->assertEquals($this->symbol, $orders[0]['symbol']);
        $this->assertEquals($this->side, $orders[0]['side']);
        $this->assertEquals($this->quantity, $orders[0]['quantity']);
        $this->assertEquals($this->price, $orders[0]['price']);
        $this->assertEquals("GTC", $orders[0]['timeInForce']);
    }

    public function testFuturesOrderAmendment()
    {
        try  {
            $this->binance->futuresOrderAmendment($this->symbol, $this->orderId, $this->origClientOrderId, $this->startTime, $this->endTime, $this->limit, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'orderId' => $this->orderId,
            'origClientOrderId' => $this->origClientOrderId,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'limit' => $this->limit,
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/orderAmendment?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesCancel()
    {
        try  {
            $this->binance->futuresCancel($this->symbol, $this->orderid);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'orderId' => $this->orderid,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/order?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesCancelBatchOrdersByOrderIds()
    {
        try  {
            $this->binance->futuresCancelBatchOrders($this->symbol, $this->orderIdList, null, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $endpoint = "https://fapi.binance.com/fapi/v1/batchOrders?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));

        $queryString = substr(self::$capturedUrl, strlen($endpoint));
        parse_str($queryString, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals('[123456,654321]', $params['orderIdList']);
        $this->assertEquals($this->recvWindow, $params['recvWindow']);
    }

    public function testFuturesCancelBatchOrdersByClientOrderIds()
    {
        try  {
            $this->binance->futuresCancelBatchOrders($this->symbol, null, $this->origClientOrderIdList, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $endpoint = "https://fapi.binance.com/fapi/v1/batchOrders?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));

        $queryString = substr(self::$capturedUrl, strlen($endpoint));
        parse_str($queryString, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals('["test client order id 1","test client order id 2"]', $params['origClientOrderIdList']);
        $this->assertEquals($this->recvWindow, $params['recvWindow']);
    }

    public function testFuturesCancelOpenOrders()
    {
        try  {
            $this->binance->futuresCancelOpenOrders($this->symbol, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/allOpenOrders?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesCountdownCancelAllOrders()
    {
        try  {
            $this->binance->futuresCountdownCancelAllOrders($this->symbol, $this->countdownTime, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $this->assertEquals("https://fapi.binance.com/fapi/v1/countdownCancelAll", self::$capturedUrl);

        parse_str(self::$capturedBody, $params);

        $this->assertEquals($this->symbol, $params['symbol']);
        $this->assertEquals($this->countdownTime, $params['countdownTime']);
        $this->assertEquals($this->recvWindow, $params['recvWindow']);
    }

    public function testFuturesOrderStatusByOrderId()
    {
        try  {
            $this->binance->futuresOrderStatus($this->symbol, $this->orderId, null, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'orderId' => $this->orderId,
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/order?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesOrderStatusByClientOrderId()
    {
        try  {
            $this->binance->futuresOrderStatus($this->symbol, null, $this->origClientOrderId, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'origClientOrderId' => $this->origClientOrderId,
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/order?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesAllOrders()
    {
        try  {
            $this->binance->futuresAllOrders($this->symbol, $this->startTime, $this->endTime, $this->limit, $this->orderId, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'limit' => $this->limit,
            'orderId' => $this->orderId,
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/allOrders?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesOpenOrders()
    {
        try  {
            $this->binance->futuresOpenOrders($this->symbol, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/openOrders?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesOpenOrderByOrderId()
    {
        try  {
            $this->binance->futuresOpenOrder($this->symbol, $this->orderId, null, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'orderId' => $this->orderId,
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/openOrder?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }

    public function testFuturesOpenOrderByClientOrderId()
    {
        try  {
            $this->binance->futuresOpenOrder($this->symbol, null, $this->origClientOrderId, $this->recvWindow);

        } catch (\Throwable $e) {

        }
        $query = http_build_query([
            'symbol' => $this->symbol,
            'origClientOrderId' => $this->origClientOrderId,
            'recvWindow' => $this->recvWindow,
        ]);
        $endpoint = "https://fapi.binance.com/fapi/v1/openOrder?" . $query;
        $this->assertTrue(str_starts_with(self::$capturedUrl, $endpoint));
    }
}
