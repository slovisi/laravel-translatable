# A trait to make Eloquent models translatable

This package is a Fork of spatie/laravel-translatable. I needed to be able to use it with PHP5.6.

This package contains a trait to make Eloquent models translatable. Translations are stored as json. There is no extra table needed to hold them.

Once the trait is installed on the model you can do these things:

```php
$newsItem = new NewsItem; // This is an Eloquent model
$newsItem
   ->setTranslation('name', 'en', 'Name in English');
   ->setTranslation('name', 'nl', 'Naam in het Nederlands');
   ->save();

$newsItem->name; // Returns 'Name in English' given that the current app locale is 'en'
$newsItem->getTranslation('name', 'nl'); // returns 'Naam in het Nederlands'

app()->setLocale('nl');

$newsItem->name; // Returns 'Naam in het Nederlands'
```

## Installation

You can install the package :

In composer.json add before require :

``` json
"repositories": [
        {
            "type": "vcs",
            "url":  "https://github.com/slovisi/laravel-translatable"
        }
],
```

Modify also your minimum-stability setting in composer.json,
see [for more details](https://getcomposer.org/doc/04-schema.md#minimum-stability).


 then in the console:

``` bash
composer update
```

The rest should be nearly identical to Spatie/laravel-translatable original package.

Next up, the service provider must be registered:

```php
// config/app.php
'providers' => [
    ...
    Spatie\Translatable\TranslatableServiceProvider::class,

];
```

If you want to change add fallback_locale, you must publish the config file:
```
php artisan vendor:publish --provider="Spatie\Translatable\TranslatableServiceProvider"
```

This is the contents of the published file:
```php
return [
  'fallback_locale' => 'en',
];
```


## Making a model translatable

The required steps to make a model translatable are:

- First you need to add the `Spatie\Translatable\HasTranslations`-trait.
- Next you should create a public property `$translatable` which holds an array with all the names of attributes you wish to make translatable.
- Finally you should make sure that all translatable attributes are set to the `text`-datatype in your database. If your database supports `json`-columns, use that.

Here's an example of a prepared model:

``` php
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NewsItem extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
}
```

### Available methods

#### Getting a translation

The easiest way to get a translation for the current locale is to just get the property for the translated attribute.
For example (given that `name` is a translatable attribute):

```php
$newsItem->name;
```

You can also use this method:

```php
public function getTranslation(string $attributeName, string $locale) : string
```

This function has an alias named `translate`.

#### Setting a translation

``` php
public function setTranslation(string $attributeName, string $locale, string $value)
```

#### Forgetting a translation

``` php
public function forgetTranslation(string $attributeName, string $locale)
```

#### Getting all translations in one go

``` php
public function getTranslations(string $attributeName) : array
```

#### Setting translations in one go

``` php
public function setTranslations(string $attributeName, array $translations)
```

Here's an example:

``` php
$translations = [
   'en' => 'Name in English',
   'nl' => 'Naam in het Nederlands'
];

$newsItem->setTranslations('name', $translations);
```

### Events

#### TranslationHasBeenSet
Right after calling `setTranslation` the `Spatie\Translatable\Events\TranslationHasBeenSet`-event will be fired.

It has these properties:
```php
/** @var \Illuminate\Database\Eloquent\Model */
public $model;

/** @var string  */
public $attributeName;

/** @var string  */
public $locale;

public $oldValue;
public $newValue;
```

### Creating models

You can immediately set translations when creating a model. Here's an example:
```php
NewsItem::create([
   'name' => [
      'en' => 'Name in English'
      'nl' => 'Naam in het Nederlands'
   ],
]);
```

### Querying translatable attributes

If you're using MySQL 5.7 or above, it's recommended that you use the json data type for housing translations in the db.
This will allow you to query these columns like this:

```php
NewsItem::whereRaw('name->"$.en" = \'Name in English\'')->get();
```

In laravel 5.2.23 and above you can use the fluent syntax:

```php
NewsItem::where('name->en', 'Name in English')->get();
```

With MySQL < 5.7 you can do this this way (this is less sexy, well this is even a really crapy way in fact but it works) :

```php
NewsItem::where('name','like', '%"en":"Name in English"%')->get();
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

We got the idea to store translations as json in a column from [Mohamed Said](https://github.com/themsaid). Parts of the readme of [his multiligual package](https://github.com/themsaid/laravel-multilingual) were used in this readme.

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
