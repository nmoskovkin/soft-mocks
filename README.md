SoftMocks
=
The idea behind "Soft Mocks" - as opposed to "hardcore" mocks that work on the level of the PHP interpreter (runkit and uopz) - is to rewrite class code on the spot so that it can be inserted in any place. It works by rewriting code on the fly during file inclusion instead of using extensions like runkit or uopz.

Installation
=

You can install SoftMocks via [Composer](https://getcomposer.org/):

```bash
php composer.phar require --dev badoo/soft-mocks
mkdir /tmp/mocks/ # create dir with SoftMocks cache
```

Usage
=
The thing that sets SoftMocks apart (and also limits their usage) is that they need to be initiated at the earliest phase of the app launch. It's necessary to do it this way because you can't redefine the classes and functions that are already loaded into the memory in PHP. For an example bootstrap presets, see _[src/bootstrap.php](src/bootstrap.php)_. For PHPUnit you should use patches form _[composer.json](composer.json)_, because you should require composer autoload through SoftMocks.

SoftMocks don't rewrite the following system parts:
* it's own code;
* PHPUnit code (see `\QA\SoftMocks::addIgnorePath()` for details);
* PHP-Parser code (see `\QA\SoftMocks::addIgnorePath()` for details);
* already rewritten code;
* code which was loaded before SoftMocks initialization.

In order to add external dependencies (for example, vendor/autoload.php) in file, which which was loaded before SoftMocks initialization, you need to use a wrapper:
```
require_once (\QA\SoftMocks::rewrite('vendor/autoload.php'));
require_once (\QA\SoftMocks::rewrite('path/to/external/lib.php'));
```

After you've added the file via `SoftMocks::rewrite()`, all nested include calls will already be "wrapped" by the system itself.

You can see a more detailed example by executing the following command:
```
$ php example/run_me.php
Result before applying SoftMocks = array (
  'TEST_CONSTANT_WITH_VALUE_42' => 42,
  'someFunc(2)' => 84,
  'Example::doSmthStatic()' => 42,
  'Example->doSmthDynamic()' => 84,
  'Example::STATIC_DO_SMTH_RESULT' => 42,
)
Result after applying SoftMocks = array (
  'TEST_CONSTANT_WITH_VALUE_42' => 43,
  'someFunc(2)' => 57,
  'Example::doSmthStatic()' => 'Example::doSmthStatic() redefined',
  'Example->doSmthDynamic()' => 'Example->doSmthDynamic() redefined',
  'Example::STATIC_DO_SMTH_RESULT' => 'Example::STATIC_DO_SMTH_RESULT value changed',
)
Result after reverting SoftMocks = array (
  'TEST_CONSTANT_WITH_VALUE_42' => 42,
  'someFunc(2)' => 84,
  'Example::doSmthStatic()' => 42,
  'Example->doSmthDynamic()' => 84,
  'Example::STATIC_DO_SMTH_RESULT' => 42,
)
```

API (short description)
=
Initialize SoftMocks (set phpunit injections, define internal mocks, get list of internal functions, etc):

```
\QA\SoftMocks::init();
```

Cache files are created in /tmp/mocks by default. If you want to choose a different path, you can redefine it as follows:

```
\QA\SoftMocks::setMocksCachePath($cache_path);
```

Redefine constant
==

You can assign a new value to $constantName or create one if it wasn't already declared. Since it isn't created using the define() call, the operation can be canceled.

Both "regular constants" and class constants like "className::CONST_NAME" are supported.

```
\QA\SoftMocks::redefineConstant($constantName, $value)
```

Redefine functions
==

SoftMocks let you redefine both user-defined and built-in functions except for those that depend on the current context (see \QA\SoftMocksTraverser::$ignore_functions property if you want to see the full list), or for those that have built-in mocks (debug_backtrace, call_user_func* and a few others, but built-in mocks you can enable redefine by call `\QA\SoftMocks::setRewriteInternal(true)`).

Definition:
```
\QA\SoftMocks::redefineFunction($func, $functionArgs, $fakeCode)
```

Usage example (redefine strlen function and call original for the trimmed string):
```
\QA\SoftMocks::redefineFunction(
    'strlen',
    '$a',
    'return \\QA\\SoftMocks::callOriginal("strlen", [trim($a)]));'
);

var_dump(strlen("  a  ")); // int(1)
```

Redefine methods
==

At the moment, only user-defined method redefinition is supported. This functionality is not supported for built-in classes.

Definition:
```
\QA\SoftMocks::redefineMethod($class, $method, $functionArgs, $fakeCode)
```

Arguments are the same as for redefineFunction, but argument $class is introduced.

As an argument $class accepts a class name or a trait name.

Redefining functions that are generators
==
This method that lets you replace a generator function call with another \Generator. Generators differ from regular functions in that you can't return a value using "return"; you have to use "yield".

```
\QA\SoftMocks::redefineGenerator($class, $method, \Generator $replacement)
```

Restore values
==

The following functions undo mocks that were made using one of the redefine methods described above.
```
\QA\SoftMocks::restoreAll()

// You can also undo only chosen mocks:
\QA\SoftMocks::restoreConstant($constantName)
\QA\SoftMocks::restoreAllConstants()
\QA\SoftMocks::restoreFunction($func)
\QA\SoftMocks::restoreMethod($class, $method)
\QA\SoftMocks::restoreGenerator($class, $method)
\QA\SoftMocks::restoreNew()
\QA\SoftMocks::restoreAllNew()
\QA\SoftMocks::restoreExit()
```

Using with PHPUnit
==

If you want to use SoftMocks with PHPUnit then there are next particularities:
- If phpunit is installed by composer then you should apply patch to `phpunit` _[patches/phpunit5.x/phpunit_phpunit.patch](patches/phpunit5.x/phpunit_phpunit.patch)_,so that classes loaded by composer would be rewritten by SoftMocks;
- if phpunit is installed manually then you should require _[src/bootstrap.php](src/bootstrap.php)_, so that classes loaded by composer would be rewritten by SoftMocks;
- so that trace would be readable you should apply patch for `phpunit` _[patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_1.patch](patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_1.patch)_;
- so that coverage would be right the you should apply patch to `phpunit` _[patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_2.patch](patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_2.patch)_ and patch to `php-code-coverage` _[patches/phpunit5.x/php-code-coverage_add_ability_to_set_custom_filename_rewrite_callbacks.patch](patches/phpunit5.x/php-code-coverage_add_ability_to_set_custom_filename_rewrite_callbacks.patch)_.

If you want that patches are applied automatically, you should write next in в composer.json:
```json
{
  "require": {
    "cweagans/composer-patches": "^1.6.1"
  },
  "extra": {
    "patches": {
      "phpunit/phpunit": {
        "phpunit run file": "patches/phpunit5.x/phpunit_phpunit.patch",
        "Add ability to set custom filename rewrite callbacks #1": "patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_1.patch",
        "Add ability to set custom filename rewrite callbacks #2": "patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_2.patch"
      },
      "phpunit/php-code-coverage": {
        "Add ability to set custom filename rewrite callbacks": "patches/phpunit5.x/php-code-coverage_add_ability_to_set_custom_filename_rewrite_callbacks.patch"
      }
    }
  }
}
```
Use `phpunit4.x` directory instead of `phpunit5.x` for `phpunit4.x`.

FAQ
=
**Q**: How can I prevent a specific function/class/constant from being redefined?

**A**: Use the \QA\SoftMocks::ignore(Class|Function|Constant) method.

**Q**: I can't override certain function calls: call_user_func(_array)?, defined, etc.

**A**: There are a bunch of functions that have their own built-in mocks which by default can't be intercepted.
Here is an incomplete list of them:
* call_user_func_array
* call_user_func
* is_callable
* function_exists
* constant
* defined
* debug_backtrace

So you can enable intercepting for them by call `\QA\SoftMocks::setRewriteInternal(true)` after require bootstrap, but be attentive.
For example, if strlen and call_user_func(_array) is redefined, then you can get different result for strlen:
```php
\QA\SoftMocks::redefineFunction('call_user_func_array', '', 'return 20;');
\QA\SoftMocks::redefineFunction('strlen', '', 'return 5;');
...
strlen('test'); // will return 5
call_user_func_array('strlen', ['test']); // will return 20
call_user_func('strlen', 'test'); // will return 5
```

**Q**: Does SoftMocks work with PHP7?

**A**: Yes. The whole idea of SoftMocks is that it will continue to work for all further PHP versions without requiring a full system rewrite as it is for runkit and uopz.

**Q**: Does SoftMocks work with HHVM?

**A**: It seems that SoftMocks indeed works when using HHVM at the moment of writing this Q&A (HipHop VM 3.12.1 (rel)). We do not use HHVM internally so there can be some corner cases that are not covered. We appreciate any issues/pull requests regarding HHVM support.

**Q**: Why do I get parse errors or fatal errors like "PhpParser::pSmth is undefined"?

**A**: SoftMocks uses custom pretty-printer for PHP Parser that does not seem to be compatible with all PHP Parser versions. Please use our vendored version until we found a way to get around that.
