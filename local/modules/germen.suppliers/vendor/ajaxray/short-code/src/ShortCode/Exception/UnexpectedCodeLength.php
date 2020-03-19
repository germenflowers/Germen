<?php
/*
 * This file is part of the ShortCode project.
 *
 * (c) Anis Uddin Ahmad <anis.programmer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ShortCode\Exception;

/**
 * UnexpectedCodeLength
 *
 * Throws when code length is unusually long or short
 *
 * @author Anis Uddin Ahmad <anis.programmer@gmail.com>
 */
class UnexpectedCodeLength extends \RangeException
{
}
