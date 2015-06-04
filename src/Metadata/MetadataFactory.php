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

use FivePercent\Component\Reflection\Reflection;
use phpDocumentor\Reflection\DocBlock;

/**
 * Base metadata factory
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class MetadataFactory implements MetadataFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadMetadata($class)
    {
        $properties = Reflection::getClassProperties($class, true);

        $metadataProperties = [];

        foreach ($properties as $property) {
            $docBlock = new DocBlock($property);

            $docVarTags = $docBlock->getTagsByName('var');

            if (count($docVarTags)) {
                /** @var \phpDocumentor\Reflection\DocBlock\Tag\VarTag $docVarTag */
                $docVarTag = array_pop($docVarTags);

                $types = $docVarTag->getTypes();

                $types = array_map(function ($type) {
                    return ltrim($type, '\\');
                }, $types);

                if (count($types)) {
                    $propertyMetadata = new PropertyMetadata($types);
                    $metadataProperties[$property->getName()] = $propertyMetadata;
                }
            }
        }

        $classMetadata = new ClassMetadata($metadataProperties);

        return $classMetadata;
    }
}
