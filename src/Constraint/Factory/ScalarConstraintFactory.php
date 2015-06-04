<?php

/**
 * This file is part of the VarTagValidator package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\VarTagValidator\Constraint\Factory;

use FivePercent\Component\VarTagValidator\Constraint\ConstraintFactoryInterface;
use FivePercent\Component\VarTagValidator\Constraint\VarTagConstraint;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Scalar constraint factory
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ScalarConstraintFactory implements ConstraintFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getVarTagConstraint()
    {
        return new VarTagConstraint([
            new Assert\Type('scalar')
        ]);
    }
}
