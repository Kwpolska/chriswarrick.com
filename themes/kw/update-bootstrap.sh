#!/bin/bash
echo "(light) Compiling SASS..."
sass bootstrap-kw.scss > bootstrap.noprefix.css
echo "( dark) Compiling SASS..."
sass bootstrap-kw-dark.scss > bootstrap-dark.noprefix.css
echo "Go to https://autoprefixer.github.io/."

pbcopy < bootstrap.noprefix.css
echo -n "(light) Paste the clipboard contents and copy the output, then press Enter."
read one
pbpaste > assets/css/bootstrap.css

pbcopy < bootstrap-dark.noprefix.css
echo -n "( dark) Paste the clipboard contents and copy the output, then press Enter."
read two
pbpaste > assets/css/bootstrap-dark.css

echo "(light) Minifying..."
curl -X POST -s --data-urlencode 'input@assets/css/bootstrap.css' https://cssminifier.com/raw > assets/css/bootstrap.min.css
echo "( dark) Minifying..."
curl -X POST -s --data-urlencode 'input@assets/css/bootstrap-dark.css' https://cssminifier.com/raw > assets/css/bootstrap-dark.min.css
echo "Attempting to purge CloudFlare cache..."
# script contains API keys and is not public
./purge-cache.sh
echo "Done."
