# Simple markdown to html parser

Markdown is a simple syntax used to generate formatted text. It’s used in lots
of places, but the one most developers have probably encountered is README
files in github.

This is a simple demo application intended to transpile markdown content
into HTML. 

## Formatting Specifics

Markdown is a fairly rich specification. For the sake of simplicity and based 
on the goals described, this application will process the following content:

| Markdown                               | HTML                                              |
| -------------------------------------- | ------------------------------------------------- |
| `# Heading 1`                          | `<h1>Heading 1</h1>`                              | 
| `## Heading 2`                         | `<h2>Heading 2</h2>`                              | 
| `...`                                  | `...`                                             | 
| `###### Heading 6`                     | `<h6>Heading 6</h6>`                              | 
| `Unformatted text`                     | `<p>Unformatted text</p>`                         | 
| `[Link text](https://www.example.com)` | `<a href="https://www.example.com">Link text</a>` | 
| `Blank line`                           | `Ignored`                                         | 


## Command line applicatio

In order to get up and running with this project, simply install the composer 
dependencies 

```bash
composer install
```

This will install the required packages to operate the program via CLI,
that will be accisible executing the following command

```bash
php bin/console app:load-markdown
```

The console application written allows you to enter either inline markdown
or specify a file path you would like to process.

### Processing inline content

Simply execute the following in your terminal using the option `--inline` or `-i`

```bash
php bin/console app:load-markdown -i "This is a sample MD
>
> # Process [me](https://example.com)"
```

### Processing files

On the other hand if you would like to open a file directly from the command line,
specify the option `--file` or `-f` with the file's path

```bash
php bin/console app:load-markdown -f /path/to/custom.md
```