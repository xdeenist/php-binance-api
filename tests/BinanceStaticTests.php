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
            print_r($e);
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
