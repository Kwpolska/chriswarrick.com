#!/bin/sh
curl -X POST -s --data-urlencode 'input@assets/css/bootstrap.css' https://cssminifier.com/raw > assets/css/bootstrap.min.css
curl -X POST -s --data-urlencode 'input@assets/css/bootstrap-dark.css' https://cssminifier.com/raw > assets/css/bootstrap-dark.min.css

