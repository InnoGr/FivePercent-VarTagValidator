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
 * All factory registry should implement this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface FactoryRegistryInterface
{
    /**
     * Get constraint factory for type or alias
     *
     * @param string $typeOrAlias
     *
     * @return ConstraintFactoryInterface
     *
     * @throws \FivePercent\Component\VarTagValidator\Exception\ConstraintFactoryNotFoundException
     */
    public function getConstraintFactory($typeOrAlias);

    /**
     * Add constraint factory alias for type
     *
     * @param string $alias
     * @param string $type
     */
    public function addConstraintFactoryAlias($alias, $type);
}
