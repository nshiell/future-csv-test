# Future Labs CSV data parser

This project will process a batch of data from a third party service which is
not in a format Future can easily import.

As the third party service is in a different time zone, it will be
quicker to parse the badly formatted files ourselves.

So this project can be executed from the command line.
The script will be able to parse any number of files within a number
of directories. So, the script will process all directories within a
given directory, and all files in each of those directories.
The script will be able to reformat the files into CSV files.

## To instal and run
To run the script firstly run `composer install` (if not already installed).
Then to run the parser, run from the project root `./data-parser.php`.

I was not sure from the pdf explanation whether the script should create one or
multiple output files.

The line "...The script should be able to reformat the files into CSV files ..."
implied multiple files, so the script does that.

**(A subsequent code addition made the project also write CSV to only one file).**

## Structure
My strategy for building the project and demonstrate good OOP skills
was to divide, input, process and output into different modules (classes):

**CsvReader**
- knows how to read a file line by line and return the line into a keyed array
**CsvWriterFuture**
- Knows how to create the output file and direcory structure
- Knows how to write a given formatted as per FuturePlc's spec into a file
**Parser**
- Knows how to map (convert) an inputed line into an outputable line
- Uses the following classes:
- **LogFileLoader**
    - Knows how to find the full paths of log files
    ** It also implements `IteratorAggregate` so it can be loopeded over
- **AppCodesReaderIniKeysByTitle**
    - Knows how to read the supplied ini file of App Codes and
        return a key from a description
        It also implements `ArrayAccess` so it can be used like an array
    - Extends **AppCodesReaderIni** which has the `parse_ini_file()` function
- **TagGroupConsolidater**
    - Given an array of tags, it will return an array of groups with the tag
        in the correct group

## To instal and run
PHPUnit was needed to ensure that **TagGroupConsolidater** worked well
To run the test, run from the project root `vendor/bin/phpunit tests/`

## Handling of bad tags
**TagGroupConsolidater** Has an extra group called `bad_tags` tags will be put
in that group if:
- The tag is not recognised
- The destination group is not empty

## Development
The time taken to built the project was around 3 hours
AI was NOT used to build the project
I spent an extra 40 minutes or so:
- Writing and using **CsvWriterFutureOneFile**
- Writing this README.md file

If I had more time:
- Added more comments around the code
- I would have refactorred the writer classes
- Made the parser handle multiple writers at once (using an array)
- I would have written unit tests around the other classes (maybe using vfsStream)
- created another writer to print to STDOUT the processing line by line
- Had the script print out a records processed count
- implemented completed/sealed functionality
    *(so the writers cannot NOT overwrite existing output files)*
- Write a class to handle the auto increment responsibility
    *(the writers should not generate IDs)*