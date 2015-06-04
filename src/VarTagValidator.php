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

use FivePercent\Component\Reflection\Reflection;
use FivePercent\Component\Exception\UnexpectedTypeException;
use FivePercent\Component\VarTagValidator\Constraint\FactoryRegistry;
use FivePercent\Component\VarTagValidator\Constraint\FactoryRegistryInterface;
use FivePercent\Component\VarTagValidator\Exception\ConstraintFactoryNotFoundException;
use FivePercent\Component\VarTagValidator\Metadata\MetadataFactoryInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Base var tag validator
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class VarTagValidator implements VarTagValidatorInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var FactoryRegistryInterface
     */
    private $constraintFactoryRegistry;

    /**
     * Construct
     *
     * @param ValidatorInterface       $validator
     * @param MetadataFactoryInterface $metadataFactory
     * @param FactoryRegistryInterface $constraintFactoryRegistry
     */
    public function __construct(
        ValidatorInterface $validator,
        MetadataFactoryInterface $metadataFactory,
        FactoryRegistryInterface $constraintFactoryRegistry = null
    ) {
        $this->validator = $validator;
        $this->metadataFactory = $metadataFactory;

        if (!$constraintFactoryRegistry) {
            $constraintFactoryRegistry = FactoryRegistry::createDefault();
        }

        $this->constraintFactoryRegistry = $constraintFactoryRegistry;
    }

    /**
     * {@inheritDoc}
     */
    public function validateObjectByVarTags($object)
    {
        if (!is_object($object)) {
            throw UnexpectedTypeException::create($object, 'object');
        }

        $classMetadata = $this->metadataFactory->loadMetadata(get_class($object));

        $constraintViolationList = new ConstraintViolationList();

        foreach ($classMetadata->getProperties() as $propertyName => $propertyMetadata) {
            $availableVarTypes = $propertyMetadata->getTypes();

            if (!count($availableVarTypes)) {
                continue;
            }

            $violationListForProperty = $this->validatePropertyValueByTypes($object, $propertyName, $availableVarTypes);

            if ($violationListForProperty && count($violationListForProperty)) {
                foreach ($violationListForProperty as $violationForProperty) {
                    // Recreate violation for save property path
                    $violation = new ConstraintViolation(
                        $violationForProperty->getMessage(),
                        $violationForProperty->getMessageTemplate(),
                        $violationForProperty->getMessageParameters(),
                        $violationForProperty->getRoot(),
                        $propertyName,
                        $violationForProperty->getInvalidValue(),
                        $violationForProperty->getMessagePluralization(),
                        $violationForProperty->getCode()
                    );

                    $constraintViolationList->add($violation);
                }
            }
        }

        return $constraintViolationList;
    }

    /**
     * Validate property value by types
     *
     * @param object $object
     * @param string $propertyName
     * @param array  $types
     *
     * @return null|\Symfony\Component\Validator\ConstraintViolationInterface[]
     */
    private function validatePropertyValueByTypes($object, $propertyName, array $types)
    {
        $classReflection = Reflection::loadClassReflection($object);
        $property = $classReflection->getProperty($propertyName);

        if (!$property->isPublic()) {
            $property->setAccessible(true);
        }

        $value = $property->getValue($object);

        if (!$value) {
            return null;
        }

        $firstViolationList = null;

        foreach ($types as $type) {
            try {
                $varTagConstraint = $this->constraintFactoryRegistry->getConstraintFactory($type)
                    ->getVarTagConstraint();
            } catch (ConstraintFactoryNotFoundException $e) {
                continue;
            }

            $constraints = $varTagConstraint->getConstraints();
            $groupSequence = $varTagConstraint->getGroupSequence();

            if (count($constraints)) {
                $violationList = $this->validator->validate($value, $constraints, $groupSequence);

                if (count($violationList)) {
                    if (!$firstViolationList) {
                        $firstViolationList = $violationList;
                    }
                } else {
                    return null;
                }
            }
        }

        return $firstViolationList;
    }
}
