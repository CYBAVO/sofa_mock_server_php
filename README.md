<a name="table-of-contents"></a>
## Table of contents

- [Get Started](#get-started)
- [Deposit Wallet API](#deposit-wallet-api)
- [Withdraw Wallet API](#withdraw-wallet-api)
- [Query API](#query-api)
- Testing
	- [Mock Server](#mock-server)
	- [cURL Testing Commands](#curl-testing-commands)
	- [Other Language Versions](#other-language-versions)

<a name="get-started"></a>
# Get Started

Refer to [here](https://github.com/CYBAVO/SOFA_MOCK_SERVER#get-started) for detailed introduction.

<a name="deposit-wallet-api"></a>
# Deposit Wallet API

Refer to [here](https://github.com/CYBAVO/SOFA_MOCK_SERVER#create-deposit-wallet-addresses) for detailed API documentation.

<a name="withdraw-wallet-api"></a>
# Withdraw Wallet API

Refer to [here](https://github.com/CYBAVO/SOFA_MOCK_SERVER#withdraw) for detailed API documentation.

<a name="query-api"></a>
# Query API

Refer to [here](https://github.com/CYBAVO/SOFA_MOCK_SERVER#query-api-token-status) for detailed API documentation.


<a name="mock-server"></a>
# Mock Server

### Setup configuration
>	Configure CYBAVO API server URL in mockserver.conf.php

```
$api_server_url = 'BACKEND_SERVER_URL';
```

### How to run
> Required version: PHP 7.3.7 or later (with sqlite3 enabled)
> 
> Replace the [SOFA\_MOCK\_SERVER\_PHP\_PATH] in the following commands to source code directory.

- If you have PHP installed then use following command to start the built-in web server.
	- $ cd [SOFA\_MOCK\_SERVER\_PHP\_PATH]
	- $ php -S 0.0.0.0:8889
- Otherwise use docker to setup mock server.
	- $ docker run --name mockserver -d -v [SOFA\_MOCK\_SERVER\_PHP\_PATH]:/var/www/html -p 8889:8889 php:7.3.7-fpm
	- $ docker exec -it mockserver bash
	- $ php -S 0.0.0.0:8889

### Put wallet API code/secret into mock server
-	Get API code/secret on web console
	-	API-CODE, API-SECRET, WALLET-ID
- 	Put API code/secret to mock server's database

```
curl -X POST -d '{"api_code":"API-CODE","api_secret":"API-SECRET"}' \
http://localhost:8889/v1/mock/wallets/{WALLET-ID}/apitoken
```

### Register mock server callback URL
>	Operate on web admin console

Notification Callback URL

```
http://localhost:8889/v1/mock/wallets/callback
```

Withdrawal Authentication Callback URL

```
http://localhost:8889/v1/mock/wallets/withdrawal/callback
```

> The withdrawal authentication callback URL once set, every withrawal request will callback this URL to get authentication to proceed withdrawal request.
> 
> Refer to /v1/mock/wallets/withdrawal/callback handler function in mock server index.php
> 
> NOTE: Because the mock server uses a single-threaded PHP built-in web server as underlying server, it cannot process withdrawal authentication callbacks and withdrawal requests at the same time. While using mock server, please do not set the withdrawal authentication callback URL.

<a name="curl-testing-commands"></a>
# cURL Testing Commands

Refer to [here](https://github.com/CYBAVO/SOFA_MOCK_SERVER#curl-testing-commands) for curl testing commands.

<a name="other-language-versions"></a>
# Other Language Versions
- [Go](https://github.com/CYBAVO/SOFA_MOCK_SERVER)
- [Java](https://github.com/CYBAVO/SOFA_MOCK_SERVER_JAVA)
- [Javascript](https://github.com/CYBAVO/SOFA_MOCK_SERVER_JAVASCRIPT)
