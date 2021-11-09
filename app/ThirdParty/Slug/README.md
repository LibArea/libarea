# slug
slug is a free function to create beautiful URL for blog or website.

# Installation

  	require_once ('/path/to/slug.php');

# Usage
Load the library

	$slug = new Slug();

Create URL

	$title 	= 'slug is a free function to create beautiful URL.';
	$url 	= $slug->create($title);
	echo $url;

Result

	slug-is-a-free-function-to-create-beautiful-url

# License
slug is licensed under the MIT license. See [License File](LICENSE.md) for more information.
