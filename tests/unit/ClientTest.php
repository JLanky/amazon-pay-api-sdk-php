<?php
    namespace Amazon\Pay\API;
    
    include 'vendor/autoload.php';
    require_once 'Amazon/Pay/API/Client.php';

    use phpseclib3\Crypt\RSA;
    use PHPUnit\Framework\TestCase;

    class ClientTest extends TestCase
    {
        private $configParams = array(
            'public_key_id' => 'ABC123DEF456XYZ789IJK000',
            'private_key'   => 'tests/unit/unit_test_key_private.txt',
            'sandbox'       => true,
            'region'        => 'us',
        );

        private $configParamsWithProxy = array(
            'public_key_id' => 'ABC123DEF456XYZ789IJK000',
            'private_key'   => 'tests/unit/unit_test_key_private.txt',
            'sandbox'       => true,
            'region'        => 'us',
            'proxy' => [
                'host' => 'proxy_host',
                'port' => 'proxy_port',
                'username' => 'proxy_username',
                'password' => 'proxy_password',
            ]
        );

        private $requestParameters = array(
            'chargePermissionId'    => 'P03-0772540-6944847',
            'chargeReferenceId'     => 'chargeReferenceId-1',
            'chargeTotal'           => '100.50',
            'currencyCode'          => 'JPY',
            'amount'                => '100.50',
            'softDescriptor'        => 'chargeTest-1',
            'metadata'              => array('shoe sale', 'Information about order'),
            'merchantNote'          => '',
            'customInformation'     => 'Information about order',
            'communicationContext'  => array(),
            'merchantStoreName'     => 'Name of Store',
            'merchantOrderId'       => 'Order 123'
        );

        private $requestHeaders = array(
            'Accept'            => 'application/json',
            'Content-Type'      => 'application/json',
            'X-Amz-Pay-Host'    => 'pay-api.amazon.jp',
            'User-Agent'        => ''
        );

        private $uri = "http://pay-api.amazon.jp/sandbox/in-store/v999/charge?extradata";
        private $expectedUnitedStatesURL = 'https://pay-api.amazon.com/';
        private $expectedEuropeURL = 'https://pay-api.amazon.eu/';
        private $expectedJapanURL = 'https://pay-api.amazon.jp/';

        public function testConfigArray()
        {
            $client = new Client($this->configParams);

            $this->assertEquals($this->configParams['public_key_id'], $client->__get('public_key_id'));
            $this->assertEquals($this->configParams['private_key'], $client->__get('private_key'));
            $this->assertEquals($this->configParams['sandbox'], $client->__get('sandbox'));
            $this->assertEquals($this->configParams['region'], $client->__get('region'));
        }

        public function testConfigArrayWithProxy() {
            $client = new Client($this->configParamsWithProxy);
            
            $this->assertEquals($this->configParamsWithProxy['public_key_id'], $client->__get('public_key_id'));
            $this->assertEquals($this->configParamsWithProxy['private_key'], $client->__get('private_key'));
            $this->assertEquals($this->configParamsWithProxy['sandbox'], $client->__get('sandbox'));
            $this->assertEquals($this->configParamsWithProxy['region'], $client->__get('region'));
            $this->assertEquals($this->configParamsWithProxy['proxy']['host'], $client->__get('proxy')['host']);
            $this->assertEquals($this->configParamsWithProxy['proxy']['port'], $client->__get('proxy')['port']);
            $this->assertEquals($this->configParamsWithProxy['proxy']['username'], $client->__get('proxy')['username']);
            $this->assertEquals($this->configParamsWithProxy['proxy']['password'], $client->__get('proxy')['password']);
        }

        public function testGetCanonicalURI()
        {
            $client = new Client($this->configParams);
            $class = new \ReflectionClass($client);
            $method = $class->getMethod('getCanonicalURI');
            $method->setAccessible(true);

            $uriTrue = "/sandbox/in-store/v999/charge";

            $this->assertEquals($uriTrue, $method->invoke($client, $this->uri));
        }

        public function testSortCanonicalArray()
        {
            $client = new Client($this->configParams);
            $class = new \ReflectionClass($client);
            $method = $class->getMethod('sortCanonicalArray');
            $method->setAccessible(true);

            $canonicalArrayTrue = array(
                'amount'                => '100.50',
                'chargePermissionId'    => 'P03-0772540-6944847',
                'chargeReferenceId'     => 'chargeReferenceId-1',
                'chargeTotal'           => '100.50',
                'currencyCode'          => 'JPY',
                'customInformation'     => 'Information about order',
                'merchantOrderId'       => 'Order 123',
                'merchantStoreName'     => 'Name of Store',
                'metadata.1'            => 'shoe sale',
                'metadata.2'            => 'Information about order',
                'softDescriptor'        => 'chargeTest-1'
            );
            
            $this->assertEquals($canonicalArrayTrue, $method->invoke($client, $this->requestParameters));
        }

        public function testCreateCanonicalQuery()
        {
            $client = new Client($this->configParams);
            $class = new \ReflectionClass($client);
            $method = $class->getMethod('createCanonicalQuery');
            $method->setAccessible(true);

            $canonicalQueryTrue = ("amount=100.50" .
                "&chargePermissionId=P03-0772540-6944847" .
                "&chargeReferenceId=chargeReferenceId-1" .
                "&chargeTotal=100.50" .
                "&currencyCode=JPY" .
                "&customInformation=Information%20about%20order" .
                "&merchantOrderId=Order%20123" .
                "&merchantStoreName=Name%20of%20Store" .
                "&metadata.1=shoe%20sale" .
                "&metadata.2=Information%20about%20order" .
                "&softDescriptor=chargeTest-1");
            
            $this->assertEquals($canonicalQueryTrue, $method->invoke($client, $this->requestParameters));
        }

        public function testGetCanonicalHeaders()
        {
            $client = new Client($this->configParams);
            $class = new \ReflectionClass($client);
            $method = $class->getMethod('getCanonicalHeaders');
            $method->setAccessible(true);

            $canonicalHeadersTrue = array(
                'accept'            => 'application/json',
                'content-type'      => 'application/json',
                'x-amz-pay-host'    => 'pay-api.amazon.jp'
            );

            $this->assertEquals($canonicalHeadersTrue, $method->invoke($client, $this->requestHeaders));
        }

        public function testGetCanonicalHeadersNames()
        {
            $client = new Client($this->configParams);
            $class = new \ReflectionClass($client);
            $method = $class->getMethod('getCanonicalHeadersNames');
            $method->setAccessible(true);

            $canonicalHeadersNamesTrue = 'accept;content-type;x-amz-pay-host';
            
            $this->assertEquals($canonicalHeadersNamesTrue, $method->invoke($client, $this->requestHeaders));
        }

        public function testGetHost()
        {
            $client = new Client($this->configParams);
            $class = new \ReflectionClass($client);
            $method = $class->getMethod('gethost');
            $method->setAccessible(true);

            $hostTrue = 'pay-api.amazon.jp';
            $this->assertEquals($hostTrue, $method->invoke($client, $this->uri));

            $emptyHost = '/';
            $this->assertEquals($emptyHost, $method->invoke($client, ''));
        }

        public function testGetHeaderString()
        {
            $client = new Client($this->configParams);
            
            $headerStringTrue = (
                "accept:application/json\n" .
                "content-type:application/json\n" .
                "x-amz-pay-host:pay-api.amazon.jp\n"
            );

            $this->assertEquals($headerStringTrue, $client->getHeaderString($this->requestHeaders, $this->uri));
        }

        public function testGetPostSignedHeaders() {
            $method = 'POST';
            $url = 'https://pay-api.amazon.com/sandbox/in-store/v999/merchantScan';
            $requestParameters = array();

            $request = array(
                'chargeId' => 'S03-2622124-8818929-C062250',
                'refundReferenceId' => 'refundRef-1',
                'refundTotal' => array(
                    'currencyCode' => 'JPY',
                    'amount' => 2
                ),
                'softDescriptor' => 'TESTSTORE refund',
            );
            $payload = json_encode($request);
            $client = new Client($this->configParams);

            $postSignedHeaders = $client->getPostSignedHeaders($method, $url, $requestParameters, $payload);
            $signature = substr($postSignedHeaders[1], strpos($postSignedHeaders[1], "Signature=") + 10);
            $this-> assertNotNull($signature);
            //TODO: verify signature, see http://phpseclib.sourceforge.net/rsa/2.0/examples.html

        }

        private function verifySignature($plaintext, $signature) {
            $rsa = RSA::loadPrivateKey(file_get_contents('tests/unit/unit_test_key_private.txt'))->withSaltLength(Client::SALT_LENGTH);

            return $rsa->getPublicKey()->verify($plaintext, base64_decode($signature));
        }

        public function testGenerateButtonSignature() {
            $payload = '{"storeId":"amzn1.application-oa2-client.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx","webCheckoutDetails":{"checkoutReviewReturnUrl":"https://localhost/test/CheckoutReview.php","checkoutResultReturnUrl":"https://localhost/test/CheckoutResult.php"}}';

            $client = new Client($this->configParams);
            $signature = $client->generateButtonSignature($payload);

            $plaintext = "AMZN-PAY-RSASSA-PSS\n8dec52d799607be40f82d5c8e7ecb6c171e6591c41b1111a576b16076c89381c";
            $this->assertEquals($this->verifySignature($plaintext, $signature), true);

            // confirm "same" sigature is generated if an array is passed in instead of a string
            $payloadArray = array(
                "storeId" => "amzn1.application-oa2-client.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
                "webCheckoutDetails" => array(
                    "checkoutReviewReturnUrl" => "https://localhost/test/CheckoutReview.php",
                    "checkoutResultReturnUrl" => "https://localhost/test/CheckoutResult.php"
                ),
            );

            $signature = $client->generateButtonSignature($payloadArray);
            $this->assertEquals($this->verifySignature($plaintext, $signature), true);

            // confirm "same" signature is generated when quotes and slashes are esacped
            $payloadEscaped = '{"storeId\":\"amzn1.application-oa2-client.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\",\"webCheckoutDetails\":{\"checkoutReviewReturnUrl\":\"https:\/\/localhost\/test\/CheckoutReview.php\",\"checkoutResultReturnUrl\":\"https:\/\/localhost\/test\/CheckoutResult.php\"}}';
            $signature = $client->generateButtonSignature($payloadEscaped);
            $this->assertEquals($this->verifySignature($plaintext, $signature), true);

        }

        // Method used to test the Environment Specific Endpoint URL
        public function testCreateServiceURL() {
            // Constants
            $live = 'live/';
            $sandbox = 'sandbox/';

            // Testing Live specific endpoint for region United States
            $this->verifyEnvironmentSpecificEndpoint('us', false, $this->expectedUnitedStatesURL . $live);

            // Testing Sandbox specific endpoint for region United States
            $this->verifyEnvironmentSpecificEndpoint('us', true, $this->expectedUnitedStatesURL . $sandbox);

            // Testing Live specific endpoint for region Europe
            $this->verifyEnvironmentSpecificEndpoint('eu', false, $this->expectedEuropeURL . $live);

            // Testing Sandbox specific endpoint for region Europe
            $this->verifyEnvironmentSpecificEndpoint('eu', true, $this->expectedEuropeURL . $sandbox);

            // Testing Live specific endpoint for region Japan
            $this->verifyEnvironmentSpecificEndpoint('jp', false, $this->expectedJapanURL . $live);

            // Testing Sandbox specific endpoint for region Japan
            $this->verifyEnvironmentSpecificEndpoint('jp', true, $this->expectedJapanURL . $sandbox);
        }

        // Generic method used to verify Environment Specific Endpoint
        private function verifyEnvironmentSpecificEndpoint($region, $sandboxFlag, $expectedURL) {
            // Configuration
            $payConfig = array(
                'public_key_id' => $this->configParams['public_key_id'],
                'private_key'   => $this->configParams['private_key'],
                'sandbox'       => $sandboxFlag,
                'region'        => $region
            );
            $reflectionMethod = self::getMethod('createServiceUrl');
            $client = new Client($payConfig);

            // Building URL
            $actualURL = $reflectionMethod->invoke($client);

            // Assertion 
            $this->assertEquals($actualURL, $expectedURL);
        }

        // Method used to apply reflection on method which is having abstraction
        private static function getMethod($methodName) {
            $reflectionClass = new \ReflectionClass('Amazon\Pay\API\Client');
            $reflectionMethod = $reflectionClass->getMethod($methodName);
            $reflectionMethod->setAccessible(true);
            return $reflectionMethod;
        }

        // Method used to test the Unified Endpoint URL
        public function testCreateServiceURLForUnifiedEndpoint() {
            // Constants
            $livePublicKeyId = 'LIVE-XXXXXXXXXXXXXXXXXXXXXXXX';
            $sandboxPublicKeyId = 'SANDBOX-XXXXXXXXXXXXXXXXXXXXXXXX';

            // Testing Unified endpoint URL by passing Live specific PublicKeyId for UnitedStates
            $this->verifyUnifiedEndpoint('us', $livePublicKeyId, $this->expectedUnitedStatesURL);

            // Testing Unified endpoint URL by passing Sandbox specific PublicKeyId for UnitedStates
            $this->verifyUnifiedEndpoint('us', $sandboxPublicKeyId, $this->expectedUnitedStatesURL);

            // Testing Unified endpoint URL by passing Live specific PublicKeyId for Europe
            $this->verifyUnifiedEndpoint('eu', $livePublicKeyId, $this->expectedEuropeURL);

            // Testing Unified endpoint URL by passing Sandbox specific PublicKeyId for Europe
            $this->verifyUnifiedEndpoint('eu', $sandboxPublicKeyId, $this->expectedEuropeURL);

            // Testing Unified endpoint URL by passing Live specific PublicKeyId for Japan
            $this->verifyUnifiedEndpoint('jp', $livePublicKeyId, $this->expectedJapanURL);

            // Testing Unified endpoint URL by passing Sandbox specific PublicKeyId for Japan
            $this->verifyUnifiedEndpoint('jp', $sandboxPublicKeyId, $this->expectedJapanURL);
        }

        // Generic method used to verify Unified Endpoint
        private function verifyUnifiedEndpoint($region, $publicKeyd, $expectedURL) {
            // Configuration
            $payConfig = array(
                'public_key_id' => $publicKeyd,
                'private_key'   => $this->configParams['private_key'],
                'region'        => $region
            );
            $reflectionMethod = self::getMethod('createServiceUrl');
            $client = new Client($payConfig);
            
            // Building URL
            $actualURL = $reflectionMethod->invoke($client);

            // Assertion 
            $this->assertEquals($actualURL, $expectedURL);
        }

    }
?>
