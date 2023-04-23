# Web GUI for BlackTeX

### About this project
BlackTeX is a PGN to LaTeX converter, you just need to input a PGN file and it will convert the file into a beautiful TEX file.

This repository is one component of a two-part college project aimed at converting PGN files to LaTeX format. This software is written in C++ and it uses the library [pgnp](https://gitlab.com/manzerbredes/pgnp), a PGN parser made by [manzerbredes](https://gitlab.com/manzerbredes). 

[The other part of the project](https://github.com/Evios17/r208-web) is a PHP-based front-end web page that relies on this software in order to function.

This project was made with ❤️ by [Evios17](https://github.com/Evios17), [Sparkyu222](https://github.com/Sparkyu222) and [ch2792](https://github.com/ch2792)

### Usage
Basic usage :
```bash
$ blacktex <input_file>
```
> This command will convert the PGN input file to a TEX file with the same name as the input

You can also use options :
```bash
$ blacktex -i <input_file> -o <output_file> -n <counts>
```
> `-i`: specify the input file
> 
> `-o` : specify the output file
> 
> `-n` : specify the number of counts before showing the chessboard in the output

### Building
To build the converter, you just need to execute the `build.sh` script :
```bash
$ chmod u+x build.sh
$ ./build.sh
```
