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
If you want to use stub instead of real api changes this lines in `config/services.yaml`:

## Execute
```
./php.sh php run.php test.csv
```
## Tes