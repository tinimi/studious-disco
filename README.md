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
Copy and fill environment config. Specify API key for accessing exchangeratesapi.io and type of account
```
cp config/env.yaml.tpl config/env.yaml
```
You could configure currencies in `config/currencies.yaml`
If your data is already sorted by date you could set `sort` to `false`. This will improve speed and memory usage.

You could use stub instead of real api. This will save api usage. Change following lines in `config/services.yaml`:
```diff
     App\Service\ExchangeRateInterface:
-        alias: App\Service\ExchangeRateApi
+        alias: App\Service\ExchangeRateStub
```
## Build
1. `./build.sh` to build docker image.
2. `./php.sh composer install` to install all dependencies.
## Execute
```
./php.sh php run.php test.csv
```
## Tests
Run `./php.sh composer test`

or `./php.sh bin/phpunit --coverage-text`.

## ENV variables
| Variable               | Default  | Description
|------------------------|----------|-
| **APP_API_KEY**        |          | Api key. **required**
| **APP_API_PAID**       | false    | Api plan. Free plan has limits on ssl and base currency
| **APP_API_BASE**       | EUR      | Base currency for free plan
| **APP_SORT**           | true     | Sort input or not. Disabling improves perfomance
| **APP_LOG_FILENAME**   | logs/log | File for logging
| **APP_LOG_LEVEL_FILE** | info     | Default log level for file logging. See [PSR-3](https://www.php-fig.org/psr/psr-3/).
| **APP_LOG_LEVEL_ECHO** | error    | Default log level for echo logging. See [PSR-3](https://www.php-fig.org/psr/psr-3/).

