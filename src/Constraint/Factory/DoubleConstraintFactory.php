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
 * Double constraint factory
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class DoubleConstraintFactory implements ConstraintFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getVarTagConstraint()
    {
        $constraints = [];

        $constraints[] = new Assert\Type([
            'type' => 'numeric',
            'message' => 'This value should be of type float.',
            'groups' => 'FirstStep'
        ]);

        $constraints[] = new Assert\Regex([
            'pattern' => '/^[^\.]+(\.\d+)?$/',
            'message' => 'This value should be of type float.',
            'groups' => 'SecondStep'
        ]);

        $groupSequence = new Assert\GroupSequence(['FirstStep', 'SecondStep']);

        return new VarTagConstraint($constraints, $groupSequence);
    }
}
