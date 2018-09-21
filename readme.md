## Laravel 5 Translation Manager

**NOTE:** *This will work only in Laravel 5.2 (and up!)*

This is a package to manage Laravel translation files.
It does not replace the Translation system, only import/export the php files to a database and make them editable through a webinterface.
The workflow would be:

    - Import translations: Read all translation files and save them in the database
    - Find all translations in php/twig sources
    - Optionally: Listen to missing translation with the custom Translator
    - Translate all keys through the webinterface
    - Export: Write all translations back to the translation files.

This way, translations can be saved in git history and no overhead is introduced in production.

## Installation (Laravel >= 5.2 needed)

1)Place the "translation" folder in packages folder.
(if packages folder not exists then create "packages" folder and then place, path like "packages/translation").

2)Add below line in composer.json inside autoload->psr-4

    "Translation\\": "packages/dc/translation/src"

3)Run below command in cmd

    Composer update

4)Add below line in provider in config/app.php file.

    Translation\ManagerServiceProvider::class

5)Run following commands:
    
    php artisan vendor:publish --provider="Translation\ManagerServiceProvider" --tag=migrations
    php artisan migrate
    php artisan vendor:publish --provider="Translation\ManagerServiceProvider" --tag=config
    php artisan vendor:publish --provider="Translation\ManagerServiceProvider" --tag=views

6)This will make the translation manager available at `http://yourdomain.com/translation`

## Usage

### Web interface

When you have imported your translation (via buttons or command), you can view them in the webinterface (on the url you defined with the controller).
You can click on a translation and an edit field will popup. Just click save and it is saved :)
When a translation is not yet created in a different locale, you can also just edit it to create it.

Using the buttons on the webinterface, you can import/export the translations. For publishing translations, make sure your application can write to the language directory.

You can also use the commands below.

### Import command

The import command will search through app/lang and load all strings in the database, so you can easily manage them.

    $ php artisan translations:import

Translation strings from app/lang/locale.json files will be imported to the __json_ group.
    
Note: By default, only new strings are added. Translations already in the DB are kept the same. If you want to replace all values with the ones from the files, 
add the `--replace` (or `-R`) option: `php artisan translations:import --replace`

### Find translations in source

The Find command/button will look search for all php/twig files in the app directory, to see if they contain translation functions, and will try to extract the group/item names.
The found keys will be added to the database, so they can be easily translated.
This can be done through the webinterface, or via an Artisan command.

    $ php artisan translations:find
    
If your project uses translation strings as keys, these will be stored into then __json_ group. 

### Export command

The export command will write the contents of the database back to app/lang php files.
This will overwrite existing translations and remove all comments, so make sure to backup your data before using.
Supply the group name to define which groups you want to publish.

    $ php artisan translations:export <group>

For example, `php artisan translations:export reminders` when you have 2 locales (en/nl), will write to `app/lang/en/reminders.php` and `app/lang/nl/reminders.php`

To export translation strings as keys to JSON files , use the `--json` (or `-J`) option: `php artisan translations:import --json`. This will import every entries from the __json_ group.

### Clean command

The clean command will search for all translation that are NULL and delete them, so your interface is a bit cleaner. Note: empty translations are never exported.

    $ php artisan translations:clean

### Reset command

The reset command simply clears all translation in the database, so you can start fresh (by a new import). Make sure to export your work if needed before doing this.

    $ php artisan translations:reset



### Detect missing translations

Most translations can be found by using the Find command (see above), but in case you have dynamic keys (variables/automatic forms etc), it can be helpful to 'listen' to the missing translations.
To detect missing translations, we can swap the Laravel TranslationServiceProvider with a custom provider.
In your config/app.php, comment out the original TranslationServiceProvider and add the one from this package:

    //'Illuminate\Translation\TranslationServiceProvider',
    'Translation\TranslationServiceProvider',

This will extend the Translator and will create a new database entry, whenever a key is not found, so you have to visit the pages that use them.
This way it shows up in the webinterface and can be edited and later exported.
You shouldn't use this in production, just in development to translate your views, then just switch back.