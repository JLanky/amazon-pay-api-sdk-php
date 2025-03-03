#### Version 2.4.0 - August 2022
* Enabled Proxy Support for HttpCurl

#### Version 2.3.2 - February 2022
* Fixed Deprecation error for PHP version 8 - Passing null to parameter ($data) of type string is deprecate

#### Version 2.3.1 - January 2022
* Applied patch to address issues occurred in Version 2.3.0.
* **Please dont use Version 2.3.0**

#### Version 2.3.0 - January 2022
* Migrated signature generating algorithm from AMZN-PAY-RSASSA-PSS to AMZN-PAY-RSASSA-PSS-V2 & increasing salt length from 20 to 32
* Upgraded phpseclib version from "2.0" to "3.0"
* Note : From this SDK version, "algorithm" need to be provided as additional field in "createCheckoutSessionConfig" while rendering Amazon Pay button.

#### Version 2.2.5 - October 2021
* Changing loose comparison operators to strict comparison operators to reduce unexpected behaviors and vulnerabilities

#### Version 2.2.4 - June 2021
* Added API Retry mechanism for error codes 502 & 504

#### Version 2.2.3 - May 2021
* Enabled support for environment specific keys (i.e Public key & Private key). The changes are fully backwards-compatible, where merchants can also use non environment specific keys


#### Version 2.2.2 - March 2021

* Removing deprecated API calls

#### Version 2.2.1 - June 2020

* Underlying endpoint for getBuyer API changed

#### Version 2.2.0 - June 2020

* Added getBuyer() API call
* Fix issue with API call failures when request payload arrays contain character encodings other than UTF-8

#### Version 2.1.0 - April 2020

* Added generateButtonSignature() helper function to generate static signature for amazon.Pay.renderButton used by checkout.js

#### Version 2.0.0 - April 2020

* For /v2/ pay-api.amazon.com|eu|jp endpoints
* New completeCheckoutSession API: POST to v2/checkoutSessions/{checkout_session_id}/complete
* There are subtle changes in the back-end API between v1/ and v2/, please check online integration changelog for complete details

  Any references in code to webCheckoutDetail, paymentDetail, and statusDetail need to be pluarized to
     webCheckoutDetails, paymentDetails, and statusDetails before moving to the new SDK

  Billing address is a top-level node now instead of being in PaymentPreference
* There are also non-subtle workflow changes between v1/ and v2/ as the completeCheckoutSession API call will be required now
  before funds can be captured.  See the [API Release notes](http://amazonpaycheckoutintegrationguide.s3.amazonaws.com/amazon-pay-checkout/release-notes.html) for more details.

#### Version 1.0.0 - April 2020

* Underlying API is going to be versioned; refactoring SDK to realign with API major version numbering

#### legacy-4.3.0 - October 2019

* Added support for passing query parameters in apiCall() function
* Added getAuthorizationToken() function, see API V2 Delegated Authorization Guide for more information

#### legacy-4.2.0 - August 2019

* Initial support for APIv2

#### legacy-4.1.3 - July 2019
* Initial public release

#### legacy-4.1.2 - July 2019
* Fix issue setting x-amz-pay-authtoken header for delegated requests

#### legacy-4.1.1 - July 2019

* Fixed EU and JP endpoints
* Clean up README.md file
* Add Open Source templates (CONTRIBUTING.md, NOTICE.txt, CODE_OF_CONDUCT.md)

#### legacy-4.1.0 - April 2019

* Breaking change: AmazonPay namespace renamed to AmazonPayV2 to prevent clash with the MWS Amazon Pay SDK
* Add convenience functions for API calls: deliveryTrackers, instoreMerchantScan, instoreCharge, instoreRefund
* Introduction of new config attribute: sandbox (boolean) and setSandbox function in Client
* Config object private_key can now be a string representation of RSA key in addition to original filename support

#### legacy-4.0.1 - March 2019

* Initial support for critical data handling signature generation

####legcy-4.0.0 - April 2018

* Initial private release of API v2 PHP Signing SDK
