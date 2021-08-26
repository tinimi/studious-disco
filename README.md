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

You could use stub instead of real api. This will save api usage. Change following lines in `config/services.yaml`:
```diff
     App\Service\ExchangeRateInterface:
-        alias: App\Service\ExchangeRateApi
+        alias: App\Service\ExchangeRateStub
```
## Execute
```
./php.sh php run.php test.csv
```
## Tests
Run `./php.sh bin/phpunit --coverage-text`.
```
Code Coverage Report:       
  2021-08-26 10:57:37       
                            
 Summary:                   
  Classes: 100.00% (19/19)  
  Methods: 100.00% (46/46)  
  Lines:   100.00% (171/171)
```