<?php

/**
 * This file is part of the VarTagValidator package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\VarTagValidator;

/**
 * All var tag validators should be implemented of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface VarTagValidatorInterface
{
    /**
     * Validate object properties by "@var" tag
     *
     * @param object $object
     *
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    public function validateObjectByVarTags($object);
}
