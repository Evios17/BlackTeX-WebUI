# BlackTeX-WebUI

### About this project
BlackTeX-webui is a front-end for [BlackTeX](https://github.com/Sparkyu222/r208-BlackTeX), a PGN to LaTeX converter. You just need to input a PGN file, and it will give you a beautiful TeX or PDF file. This front end was created in order to make the usage of BlackTex easier.

This repository is one component of a two-part college project. In order for this front end to work, you will need to [download the other part of the project](https://github.com/Sparkyu222/r208-BlackTeX). A bash script placed at the root of this project will be available in order to setup BlackTeX easily.

This project was made with ❤️ by [Evios17](https://github.com/Evios17), [Sparkyu222](https://github.com/Sparkyu222) and [ch2792](https://github.com/ch2792).

### Requirements
The TeX to PDF converter uses two different type of LaTeX compiler :

If you choose to show NAGs, BlackTeX-webui will compile your TeX file with XeLaTeX, which is a compiler that support UTF-8 characters.

Otherwise it will compile with pdflatex.

You'll need to have at least one of the two compiler in order to compile your TeX file to PDF.

### Setup
Clone this repository into the folder where you usually put your web pages:
```bash
$ git clone https://github.com/Evios17/r208-web/ blacktex
```

Then you need to either download and compile BlackTex by yourself or use the built-in bash script to easily setup it:
```bash
$ chmod u+x setup-blacktex.sh
$ ./setup-blacktex.sh
```
