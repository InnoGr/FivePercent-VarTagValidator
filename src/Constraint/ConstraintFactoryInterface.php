<?php

/**
 * This file is part of the VarTagValidator package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\VarTagValidator\Constraint;

/**
 * All constraint factories should implement this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ConstraintFactoryInterface
{
    /**
     * Get var tag constraint
     *
     * @return \FivePercent\Component\VarTagValidator\Constraint\VarTagConstraint
     */
    public function getVarTagConstraint();
}
