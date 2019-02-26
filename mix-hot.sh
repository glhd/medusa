#!/usr/bin/env bash

BASEDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$BASEDIR"

function cleanup {
  rm resources/js/dist/hot
}
trap cleanup EXIT

./node_modules/.bin/cross-env NODE_ENV=development webpack-dev-server --disable-host-check --inline --hot --config=node_modules/laravel-mix/setup/webpack.config.js
