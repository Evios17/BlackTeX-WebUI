#!/bin/bash

git clone https://github.com/Sparkyu222/r208-BlackTeX.git &&

cd r208-BlackTeX &&

./build.sh &&

cp build/blacktex ../app/exe/. &&

cd .. &&

rm -rf r208-BlackTeX