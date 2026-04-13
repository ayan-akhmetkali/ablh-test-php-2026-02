#!/usr/bin/env sh
set -eu

ROOT_DIR="$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)"
SRC="$ROOT_DIR/public/assets/scss/styles.scss"
DST="$ROOT_DIR/public/assets/css/styles.css"

if command -v sass >/dev/null 2>&1; then
  sass --no-source-map --style=expanded "$SRC":"$DST"
  echo "SCSS compiled: $SRC -> $DST"
  exit 0
fi

if command -v npx >/dev/null 2>&1; then
  npx --yes sass --no-source-map --style=expanded "$SRC":"$DST"
  echo "SCSS compiled via npx: $SRC -> $DST"
  exit 0
fi

echo "sass compiler is not installed."
echo "Install Dart Sass: https://sass-lang.com/install"
echo "Then run: sass --no-source-map --style=expanded $SRC:$DST"
exit 1
