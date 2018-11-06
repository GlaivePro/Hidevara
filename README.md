# Hidevara

Laravel millipackage that hides your variables from getting dumped in the Whoops page when your app crashes.

`Hidevara` is japonese for `hide the damn vars`.

## Usage

Just install it:

``` bash
$ composer require glaivepro/hidevara
```

By default it will:

- leave your GET and FILES intact;
- hide value of any POST field that has a name containg `password`;
- hide values of SESSION and COOKIE;
- remove almost all SERVER variables (except REDIRECT_STATUS, REQUEST_METHOD, QUERY_STRING, REQUEST_URI);
- remove all ENV variables.

"Hide" means that the value will be replaced with a string. By default it's empty string for null/emptystring values and `[hidden]` for everything else.

## Customization

Publish the config:

``` bash
$ php artisan vendor:publish --provider="GlaivePro\Hidevara\Provider"
```

Now you've got your very own `config/hidevara.php` file to edit. 

You'll see a set of rules (`'action' => $test`) associated with each of the variables. The test can be an array of exact field names, string with a regex or `true` to take this action for anything.

Here's an example:

```php
	'_GET' => [                   //this is the ruleset for fields in GET
		'expose' => true,         // show all fields
	],

	'_ENV' => [
		'remove' => ['APP_KEY'],  // remove key field entirely
		'hide' => '/password/i',  // hide anything that matches regex contains password
		'trash' => '/PUSHER/'     // anything that's not 'expose' or 'hide' will remove matched fields
		'expose' => true,         // expose all that remains
	],
	
	'_SERVER' => [
		'expose' => ['REQUEST_METHOD'],  // show the REQUEST_METHOD
		                                 // everything that hasn't matched a rule will be removed
	],
```

There are also `replaceHiddenValueWith` and `replaceHiddenEmptyValueWith` where you can supply whatever strings you love (like 🍑).

## Changes to error handling

The package extends `App\Exceptions\Handler` and thus will not work if your exception handler is not `App\Exceptions\Handler`. Take care if you decide to change the app name. Or pop a new issue in the tracker if you've got an idea how to solve this problem.

To hide the global variables from Whoops, they are hijacked/ruined just before calling your `App\Exceptions\Handler::render()`. If you need access to the original global at that method, you can get them in `$GLOBALS['hidevara']`. For example, `$GLOBALS['hidevara']['_SERVER']` is what `$_SERVER` was.

## Collaboration

Pls help! Here are the open problems and questions:

- What should the default config be?
- Should config allow repeating the same type of rule? It's possible but would make config syntax more complicated.
- Are there better ways to do this in Laravel? 
- Can we at least avoid tying it all to the app name in Laravel?
- Can we intercept directly in the Whoopsies `PrettyPageHandler` and make this not Laravel specific?

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-packagist]: https://packagist.org/packages/GlaivePro/Ajaxable
[link-author]: https://github.com/tontonsb
[link-contributors]: ../../contributors
