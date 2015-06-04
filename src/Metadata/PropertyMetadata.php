<?php

/**
 * This file is part of the VarTagValidator package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\VarTagValidator\Metadata;

/**
 * Property metadata
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com
 */
class PropertyMetadata
{
    /**
     * @var array
     */
    private $types;

    /**
     * Construct
     *
     * @param array $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * Get types
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
