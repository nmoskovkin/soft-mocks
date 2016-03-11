SoftMocks
=
Основная идея «мягких моков» по сравнению с «жесткими», которые работают на уровне интерпретатора PHP (runkit и uopz) — переписывать «на лету» код классов так, чтобы можно было вставлять моки в любое место.
Идея состоит в следующем: вместо использования расширений типа runkit или uopz, переписывать код на лету во время include.

Использование
=
Для начала важно отметить, что SoftMocks не переписывают следующие части системы:
* код SoftMocks
* код PHPUnit
* код PHP-Parser из vendor/nikic/php-parser
* уже переписанный код

Ключевой особенностью (и в то же время ограничением) SoftMock'ов является то, что они (вместе со всеми своими зависимостями) должны быть инициализированы на самом раннем этапе запуска приложения. Это связано с тем, что иначе не получится переопределять функции/константы, которые могли быть вызваны непосредственно до инициализации софт моков. Пример заготовки bootstrap-файла для PHPUnit можно увидеть в _src/bootstrap.php_.
Важно подчеркнуть, что если вам в bootstrap-файле необходимо подключить какие-либо внешние зависимости (vendor/autoload.php, etc), то подключать их нужно следущим образом:
```
require_once (\QA\SoftMocks::rewrite('vendor/autoload.php'));
require_once (\QA\SoftMocks::rewrite('path/to/external/lib.php'));
```
Более подробный пример можно увидеть выполнив следующую команду:
```
[~/Work/soft-mocks]-> php example/run_me.php
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
\QA\SoftMocks::init($mocks_cache_path = '', $lock_file_path = '', $phpunit_path = '');
```
Инициализирует софт-моки. Аргументы позволяют задать месотположение кэш переписанных файлов и путь до файла с брокировками.
$phpunit_path - содержит путь (или часть пути) до директории с установленным PHPUnit.
По-умолчанию кэш файлов создаётся в /tmp/mocks.

```
\QA\SoftMocks::redefineConstant(...)
```
Данное семейство функций позволяет переопределить значение константы (как объйвленной через define(), так и являющиейся частью класса.

```
\QA\SoftMocks::redifine(Method|Function)(...)
```
Позволяет заменить исполнение кода оригинального метода/функции на переданный извне.

```
\QA\SoftMocks::redefineGenerator(...)
```
Позволяет заменить вызов функции-генератора на \Callable-объект также являющийся генератором.

```
\QA\SoftMocks::restore*
```
Отменяет замену, осуществлённую одним из redefine*-методов.

FAQ
=
**Q**: Как запретить переопределять ту или иную функцию/класс/константу\
**A**: \QA\SoftMocksTraverser::$ignore_(functions|classes|constants), соответственно.

**Q**: Я не могу переопределить вызовы некоторых функций: call_user_func(_array)?, defined, etc.\
**A**: Есть ряд функций, для который существуют встроенные моки и их действительно нельзя перехватить. Вот их список:
* call_user_func_array
* call_user_func
* is_callable
* function_exists
* constant
* defined
* debug_backtrace
