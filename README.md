# studious-disco
## Requirements:
* `PHP` 8.0
* `bcmath` extension
* `composer` v2.1
## Docker 
You can use docker for easy local development.
use `./build.sh` script to build container.

Later you could use `./php.sh` to run script. 
## Configuration
Copy and fill environment config. Specify API key for accessing exchangeratesapi.io.
```
cp .env.dist .env
```
You could use stub instead of real api. This will save api usage. Use following env variable:
```
APP_RATE_MODULE=stub
```
## ENV variables
| Variable                                     | Default  | Description
|----------------------------------------------|----------|-
| **APP_API_KEY**                              |          | Api key. **required**
| **APP_API_PAID**                             | false    | Api plan. Free plan has limits on ssl and base currency
| **APP_API_BASE**                             | EUR      | Base currency for free plan
| **APP_SORT**                                 | true     | Sort input or not. Disabling improves perfomance
| **APP_LOG_FILENAME**                         | logs/log | File for logging
| **APP_LOG_LEVEL_FILE**                       | info     | Default log level for file logging. See [PSR-3](https://www.php-fig.org/psr/psr-3/).
| **APP_LOG_LEVEL_ECHO**                       | error    | Default log level for echo logging. See [PSR-3](https://www.php-fig.org/psr/psr-3/).
| **APP_COMMISSION_DEPOSIT**                   | 0.03     | Deposit commission, %
| **APP_COMMISSION_WITHDRAW_BUSSINESS**        | 0.5      | Withdrawal commission for bussiness clients, %
| **APP_COMMISSION_WITHDRAW_PRIVATE**          | 0.3      | Withdrawal commission for private clients, %
| **APP_COMMISSION_WITHDRAW_PRIVATE_AMOUNT**   | 1000     | Withdrawal for private clients discount amount
| **APP_COMMISSION_WITHDRAW_PRIVATE_CURRENCY** | EUR      | Withdrawal for private clients discount amount currency
| **APP_COMMISSION_WITHDRAW_PRIVATE_COUNT**    | 3        | Withdrawal for private clients discount transactions count
| **APP_RATE_MODULE**                          | api      | Used exchange rate module (api\|stub)
| **APP_RATE_EUR_USD**                         | 1.1497   | EUR->USD exchange rate (For stub only)
| **APP_RATE_EUR_JPY**                         | 129.53   | EUR->JPY exchange rate (for stub only)
| **APP_SCALE_USD**                            | 2        | Scale for USD
| **APP_SCALE_EUR**                            | 2        | Scale for EUR
| **APP_SCALE_JPY**                            | 0        | Scale for JPY


## Build
1. `./build.sh` to build docker image.
2. `./php.sh composer install` to install all dependencies.
## Execute
```
./php.sh php run.php data/test.csv
```
## Tests
Run `./php.sh composer test`

or `./php.sh bin/phpunit --coverage-text`.
```  
Code Coverage Report:       
  2021-08-29 12:53:59       
                          
 Summary:                   
  Classes: 100.00% (18/18)  
  Methods: 100.00% (57/57)  
  Lines:   100.00% (260/260)
```