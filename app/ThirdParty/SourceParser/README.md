# Source Parser in PHP
[![Build Status](https://travis-ci.com/UrbanMonastics/SourceParser.svg)](https://travis-ci.com/UrbanMonastics/SourceParser)
[![Total Downloads](https://poser.pugx.org/urbanmonastics/sourceparser/d/total.svg)](https://packagist.org/packages/urbanmonastics/sourceparser)
[![Version](https://poser.pugx.org/urbanmonastics/sourceparser/v/stable.svg)](https://packagist.org/packages/urbanmonastics/sourceparser)
[![License](https://poser.pugx.org/urbanmonastics/sourceparser/license.svg)](https://packagist.org/packages/urbanmonastics/sourceparser)

A Fast Markdown Parser extended for Liturgy in PHP. This is a formatting library for the specific styling and formats of the Source Texts available from Urban Monastics.  
  
The source texts use a subset of the Markdown functionality, and include some additional features. To make these texts more approachable and useful to others we wanted to publish a library which makes formatting them simple and direct. This library is based upon [Parsedown](https://github.com/erusev/parsedown) by [Emanuil Rusev](http://erusev.com).  
  
## Features  

*	One File without any Dependencies
*	Fast
*	Extended to support Liturgical needs
*	[GitHub flavored](https://github.github.com/gfm)
*	[Tested](http://parsedown.org/tests/) in 7.0 to 8.0

## Extending Markdown  
To ensure that we can support additional elements. These extensions are broken into inline or block level markings.

### Inline Extensions
These can be placed anywhere within the texts. Since there are not standard ways for these to be implemented in HTML. Instead we need to 

*	\_underline\_		Make the encased text underlined. Note that we disable the ability for bold or italic using underscores in order to support underlining texts. (not supported by Github)  
*	‾over line‾		Make the encased text over-lined. Only supported in HTML formatting. (not supported by Github)  
*	\_‾under and over line‾\_		Make the encased text both underlined and over-lined. Only supported in HTML formatting, in other outputs will show as underlined. (not supported by Github)  
*	[red]red text[/red]		Make the encased text red. Output is `<span class="color-red">red text</span>` only when *LiturgicalElements* is enabled otherwise it is simply stripped.  
*	[+]		This will insert the symbol to prompt the reader to cross themselves. Rendered as ✛ in non HTML [U+271B or `&#10011;`].
*	[*]		This is the for denoting a mid-point in chanted texts.  
*	[t]		This is the dagger/obelisk that indicates the current line continues below. Helpful with chanted texts with more than two lines. Rendered as † in non HTML [U+2020 or `&#8224;` or `&dagger;`].  

### Block Extensions

*	[V]		During the *Responsory* it denotes a **Versicle** line with the leader speaking. Rendered as ℣ in non HTML [U+2123 or `&#8483;`].  
*	[R]		During the *Responsory* it denotes **Response** line with all speaking. Rendered as ℟ in non HTML [U+211F or `&#8479;`].  
*	[II]	During the *Intercessions* this indicates the **Introduction** to the intentions. When prayed in a group it should be read only by the leader.  
*	[IR]	During the *Intercessions* this indicated the **Response**. It should only be placed in the source text on the line after the introduction. It will be placed in other locations when formatted. When prayed in a group it should be read only by the leader.  
*	[I1]	During the *Intercessions* this indicates the **first** part of an **intention**. 
*	[I2]	During the *Intercessions* this indicates the **second** part of an **intention**. 


## Other Options 


## Adding to your Project  
  
Install the composer package:  

```php
composer require UrbanMonastics/SourceParser
```
  
Or download the latest release and include `SourceParser.php`  
  
## Example Usage  
In the most simple approach you can pass text to be parsed.  

```php
$SourceParser = new SourceParser();

echo $SourceParser->text("Hello *Source Parser*!");  # prints: <p>Hello <em>Source Parser</em>!</p>
```

You can also take advantage of the structure of the source texts.

```php
$SourceParser = new SourceParser();

// Load the source data into the parser
$Source = json_decode( file_get_contents('path/to/source.json'), true );
$SourceParser->loadSource( $Source );

$SourceParser->loadText();

echo $SourceParser->text("Hello *Source Parser*!");  # prints: <p>Hello <em>Source Parser</em>!</p>

// Clear the loaded Source and Texts - without altering other options
$SourceParser->clearSource();
```


## Formatting Options
These texts may need to be used in various formats and contexts. There are going to be situations in which you may want to ensure that only certain elements of text are rendered for your use case.

### Methods

*	**setSafeMode( *bool* )** default: false  
	When enabled will ensure that output cannot execute code.
*	**setStrictMode( *bool* )**	default: false  
	When enabled it requires headers not start with a space....
*	**setBreaksEnabled( *bool* )** default: false  
	When enabled it will transform new line markers `\n` into `<br>`. 
*	**setMarkupEscaped( *bool* )** default: false  
	When enabled it will escape any existing HTML syntax within the documents.  
*	**setUrlsLinked( *bool* )**  
	When enabled it will convert inline URL strings into clickable links.
*	**setPreserveIndentations( *bool* )** default: false  
	When enabled this will convert any tabs (set of 4 spaces) into four double spaces wrapped in a span. Enabling this will disable tabbing for code blocks.
*	**setLiturgicalElements( *bool* )** default: false  
	When enabled the standard Markdown will be supplemented with liturgical elements. See Extending Markdown above for additions
*	**setLiturgicalHTML( *bool* )** default: true  
	Do we place liturgical markers within HTML tags, or just place them directly into the document.
*	**setSuppressAlleluia( *bool* )** default: false  
	During the season of Lent the use of the word Alleluia is suppressed. Enabling this option will remove any line where the only text is the word Alleluia.  
*	**setTitlesEnabled( *bool* )** default: false  
	Do we place any titles from the `text.json` document into the output
*	**setFootnotesEnabled( *bool* )** default: false  
	Do we place footnotes from the `text.json` document into the output


```php
$SourceParser->setLiturgicalElements( true );
echo $SourceParser->text("God, [+] come to my assistance,[*]");
// prints: <p>God, <span class="symbol-cross">✛</span> come to my assistance,<span class="symbol-star">*</span></p>

$SourceParser->setLiturgicalHTML( false );	# The default value is True, so you can manually disable wrapping liturgical elements.
echo $SourceParser->text("God, [+] come to my assistance,[*]");
// prints: <p>God, ✛ come to my assistance,*</p>
```

## Development Environment
To make it easier to develop and build out the SourceParser we have setup a local docker container for you to use. There are some simple unix scripts from the project base directory that you can execute to get setup.


	# To build or update the container
	./docker/build.sh
	
	# To start an existing container
	./docker/start.sh
	
	# To stop/shutdown the container
	./docker/stop.sh
	
	# To run the PHPUnit tests in /test/SourceParserTest.php
	./docker/phpunit.sh
	
	# To attach to the running container
	./docker/attach.sh
	
	# To run the PHP composer update on the running container
	./docker/update.sh

In addition we have linked the NGNIX access and error logs to files in the docker directory. This can prove helpful when trouble shooting.

	docker/nginx/access.log
	docker/nginx/error.log