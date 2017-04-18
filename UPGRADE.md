# Upgrading Instructions for SoftMocks 1.1.0

!!!IMPORTANT!!!

The following upgrading instructions are cumulative. That is, if you want to upgrade from version A to version C and there is version B between A and C, you need to follow the instructions for both A and B.

## Upgrade from SoftMocks 1.0

- Method `\QA\SoftMocks::setPhpunitPath()` marked as deprecated and will be removed in feature versions. Use `\QA\SoftMocks::addIgnorePath()` instead of it.
- Method `\QA\SoftMocks::setPhpParserPath()` marked as deprecated and will be removed in feature versions. Use `\QA\SoftMocks::addIgnorePath()` instead of it.
- Method `\QA\SoftMocks::getMockCode()` was removed.
- Method `\QA\SoftMocks::generateCode()` was marked as private.
- Parameter $strict for method `\QA\SoftMocks::redefineMethod()` was removed. Now only strict mode available. You should check using this parameter.
