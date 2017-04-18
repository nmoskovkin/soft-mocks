# SoftMocks v1 Change Log

## v1.1.0

There are next changes:

- patches for phpunit in composer.json was added;
- exact version of nikic/php-parser in composer.json was provided;
- parameter $strict for method `\QA\SoftMocks::redefineMethod()` was removed, now only strict mode available;
- redefine for built-in mocks was allowed (is activated by `\QA\SoftMocks::setRewriteInternal(true)`) [https://github.com/badoo/soft-mocks/pull/15](https://github.com/badoo/soft-mocks/pull/15), thanks [Mougrim](https://github.com/mougrim);
- null for redefined constants was allowed [https://github.com/badoo/soft-mocks/pull/11](https://github.com/badoo/soft-mocks/pull/11), thanks [Alexey Manukhin](https://github.com/axxapy);
- error "Fatal error: Couldn't find constant QA\SoftMocks::CLASS in /src/QA/SoftMocks.php on line 388" was fixed for old versions hhvm [https://github.com/badoo/soft-mocks/pull/16](https://github.com/badoo/soft-mocks/pull/16), thanks [Mougrim](https://github.com/mougrim);
- warning "PHP Warning:  array_key_exists() expects exactly 2 parameters" was fixed [https://github.com/badoo/soft-mocks/pull/14](https://github.com/badoo/soft-mocks/pull/14), thanks [Mougrim](https://github.com/mougrim);
- unit tests was added.
