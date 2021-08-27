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
