#!/bin/bash -v
sass bootstrap-kw.scss | node run_postcss.js bootstrap
sass bootstrap-kw-dark.scss | node run_postcss.js bootstrap-dark
