<?php
/**
 * Mocks core that rewrites code
 * @author Yuriy Nasretdinov <y.nasretdinov@corp.badoo.com>
 */

namespace QA;

@trigger_error(
    'The ' . __NAMESPACE__ . ' classes are deprecated since version 1.1.3 and will be removed in 2.0. Use the \Badoo\* classes instead.',
    E_USER_DEPRECATED
);

/**
 * Class SoftMocksFunctionCreator
 *
 * @deprecated \QA\SoftMocksFunctionCreator class is deprecated since version 1.1.3 and will be removed in 2.0. Use the \Badoo\SoftMocksFunctionCreator class instead.
 */
class SoftMocksFunctionCreator extends \Badoo\SoftMocksFunctionCreator {}

/**
 * Class SoftMocksPrinter
 *
 * @deprecated \QA\SoftMocksPrinter class is deprecated since version 1.1.3 and will be removed in 2.0. Use the \Badoo\SoftMocksPrinter class instead.
 */
class SoftMocksPrinter extends \Badoo\SoftMocksPrinter {}

/**
 * Class SoftMocks
 *
 * @deprecated \QA\SoftMocks class is deprecated since version 1.1.3 and will be removed in 2.0. Use the \Badoo\SoftMocks class instead.
 */
class SoftMocks extends \Badoo\SoftMocks {}

/**
 * Class SoftMocks
 *
 * @deprecated \QA\SoftMocksTraverser class is deprecated since version 1.1.3 and will be removed in 2.0. Use the \Badoo\SoftMocksTraverser class instead.
 */
class SoftMocksTraverser extends \Badoo\SoftMocksTraverser {}
