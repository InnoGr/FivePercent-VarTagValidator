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
 * Class metadata
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ClassMetadata
{
    /**
     * @var array|PropertyMetadata[]
     */
    private $properties = [];

    /**
     * Construct
     *
     * @param array|PropertyMetadata[] $properties
     */
    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * Get properties
     *
     * @return array|PropertyMetadata[]
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
