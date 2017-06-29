SoftMocks
=
Основная идея «мягких моков» по сравнению с «жесткими», которые работают на уровне интерпретатора PHP (runkit и uopz) — переписывать «на лету» код классов так, чтобы можно было вставлять моки в любое место.
Идея состоит в следующем: вместо использования расширений типа runkit или uopz, переписывать код на лету во время include.

Установка
=

SoftMocks можно установить через [Composer](https://getcomposer.org/):
```bash
php composer.phar require --dev badoo/soft-mocks
mkdir /tmp/mocks/ # создаем папку для кеша SoftMocks
```

Использование
=
Ключевой особенностью (и в то же время ограничением) SoftMocks является то, что они должны быть инициализированы на самом раннем этапе запуска приложения. Это необходимо из-за того, что в PHP нет возможности переопределять уже загруженные в память классы и функции. Пример заготовки bootstrap-файла можно увидеть в _[src/bootstrap.php](src/bootstrap.php)_. Для PHPUnit нужно использовать патчи из _[composer.json](composer.json)_, т.к. нужно подключать загрузчик файлов composer через SoftMocks.

SoftMocks не переписывают следующие части системы:
* собственный код;
* код PHPUnit;
* код PHP-Parser;
* уже переписанный код;
* код, который был подключен до инициализации SoftMocks.

Для того, чтобы подключить какие-либо внешние зависимости (например, vendor/autoload.php) в файле, который был подключен до инициализации SoftMocks, их нужно подключить через обертку:
```
require_once (\QA\SoftMocks::rewrite('vendor/autoload.php'));
require_once (\QA\SoftMocks::rewrite('path/to/external/lib.php'));
```

После того, как вы подключите файл через SoftMocks::rewrite(), все вложенные вызовы `include` уже будут «обернуты» самой системой.

Более подробный пример можно увидеть выполнив следующую команду:
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

API (краткое описание)
=
```
\QA\SoftMocks::init();
```
Инициализирует софт-моки. По умолчанию, кэш файлов создаётся в /tmp/mocks. Если вам такой путь не подходит, то его можно переопределить следующим образом:

```
\QA\SoftMocks::setMocksCachePath($cache_path);
```


Переопределение констант
==

Дать новое значение константе $constantName, или создать, если такой ещё не было объявлено. Создание делается не с помощью вызова define(), поэтому операцию можно отменить.

Поддерживаются как «обычные константы», так и константы классов, с синтаксисом "className::CONST_NAME".

```
\QA\SoftMocks::redefineConstant($constantName, $value)
```

Переопределение функций
==

Soft Mocks позволяет переопределить как пользовательские, так и встроенные функции, кроме функций, которые зависят от текущего контекста (см. свойство \QA\SoftMocksTraverser::$ignore_functions), а также тех, для которых есть встроенные моки (debug_backtrace, call_user_func* и некоторые другие, но встроенные моки можно разрешить переотпределять через `\QA\SoftMocks::setRewriteInternal(true)`).

Сигнатура:
```
\QA\SoftMocks::redefineFunction($func, $functionArgs, $fakeCode)
```

Пример использования (переопределить функцию strlen и вызвать оригинальную для trim'нутой строки):
```
\QA\SoftMocks::redefineFunction(
    'strlen',
    '$a',
    'return \\QA\\SoftMocks::callOriginal("strlen", [trim($a)]));'
);

var_dump(strlen("  a  ")); // int(1)
```

Переопределение методов
==

В данный момент поддерживается только переопределение пользовательских методов, для встроенных классов эта функциональность не поддерживается.

Сигнатура:
```
\QA\SoftMocks::redefineMethod($class, $method, $functionArgs, $fakeCode)
```

Отличие от redefineFunction состоит в наличии класса ($class).

В качестве аргумента $class может выступать как имя класса, так и имя trait'а.

Переопределение функций, являющихся генераторами
==
Метод позволяет заменить вызов функции-генератора на \Callable-объект также являющийся генератором. Генераторы отличаются от обычных функций тем, что в них невозможно вернуть значение через return и обязательно нужно пользоваться yield.

```
\QA\SoftMocks::redefineGenerator($class, $method, Callable $replacement)
```

Восстановление значений
==

Следующие функции отменяют замену, осуществлённую одним из приведенных раннее redefine-методов.

```
\QA\SoftMocks::restoreAll()

// Так же можно отменить конкретные моки:
\QA\SoftMocks::restoreConstant($constantName)
\QA\SoftMocks::restoreAllConstants()
\QA\SoftMocks::restoreFunction($func)
\QA\SoftMocks::restoreMethod($class, $method)
\QA\SoftMocks::restoreGenerator($class, $method)
\QA\SoftMocks::restoreNew()
\QA\SoftMocks::restoreAllNew()
\QA\SoftMocks::restoreExit()
```

Использование совместно с PHPUnit
==

При использовании SoftMocks совместно с PHPUnit есть следующие нюансы:
- если phpunit установлен через composer, то обязательно нужно применить патч на `phpunit` _[patches/phpunit_phpunit.patch](patches/phpunit_phpunit.patch)_, чтобы классы, загружаемые через composer переписывались через SoftMocks;
- если phpunit установлен отдельно, то нужно подключить _[src/bootstrap.php](src/bootstrap.php)_, что бы классы, загружаемые через composer переписывались через SoftMocks;
- что бы трэйсы выглядели красиво, нужно применить патч на `phpunit` _[patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_1.patch](patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_1.patch)_;
- что бы правильно считался coverage, нужно применить патч на `phpunit` _[patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_2.patch](patches/phpunit5.x/phpunit_add_ability_to_set_custom_filename_rewrite_callbacks_2.patch)_ и патч на `php-code-coverage` _[patches/phpunit5.x/php-code-coverage_add_ability_to_set_custom_filename_rewrite_callbacks.patch](patches/phpunit5.x/php-code-coverage_add_ability_to_set_custom_filename_rewrite_callbacks.patch)_.

Если в проекте используется `phpunit4.x`, то вместо папки `phpunit5.x` нужно использовать `phpunit4.x`.

Что бы все нужны патчи применялись автоматически нужно прописать следующее в composer.json:
```json
{
  "require-dev": {
    "vaimo/composer-patches": "^3.3.1",
    "phpunit/phpunit": "^5.7.20" // или "^4.8.35"
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/vaimo/composer-patches.git"
    }
  ],
  "extra": {
    "enable-patching": true
  }
}
```

Для принудительного переприменения патчей можно использовать переменную окржения `COMPOSER_FORCE_PATCH_REAPPLY`, например:
```bash
COMPOSER_FORCE_PATCH_REAPPLY=1 php composer.phar update
```

FAQ
=
**Q**: Как запретить переопределять ту или иную функцию/класс/константу?

**A**: Используйте методы \QA\SoftMocks::ignore(Class|Function|Constant).

**Q**: Я не могу переопределить вызовы некоторых функций: call_user_func(_array)?, defined, etc.

**A**: Есть ряд функций, для которых существуют встроенные моки и по умолчанию их нельзя перехватить. Вот их список:
* call_user_func_array
* call_user_func
* is_callable
* function_exists
* constant
* defined
* debug_backtrace

Что бы включить перехватывание для них, нужно вызвать `\QA\SoftMocks::setRewriteInternal(true)` после подключения bootstrap-а, но будьте внимателены.
Например, если strlen и call_user_func(_array) переопределены, то можно получить разные результаты для strlen: 
```php
\QA\SoftMocks::redefineFunction('call_user_func_array', '', function () {return 20;});
\QA\SoftMocks::redefineFunction('strlen', '', function () {return 5;});
...
strlen('test'); // вернет 5
call_user_func_array('strlen', ['test']); // вернет 20
call_user_func('strlen', 'test'); // вернет 5
```

**Q**: Почему я получаю Parse error или Fatal error с попыткой вызова несуществующих методов PhpParser?

**A**: Для проекта Soft Mocks создавался специализированный класс для pretty print PHP-файлов, чтобы сохранялись номера строк. По всей видимости, API изменился с тех пор, как мы взяли к себе библиотеку PHP Parser, поэтому наш pretty-printer иногда не работает с версией из мастера или 2.0. Для решения этой проблемы в данный момент предлагается использовать версию, прописанную в composer.json, пока мы нашли способ обойти проблему.
