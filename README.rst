.. title:: Var Tag Validator

=================
Var Tag Validator
=================

With this package you can validate objects by **@var** tags.

Installation
------------

Add **FivePercent/VarTagValidator** in your composer.json:

.. code-block:: json

    {
        "require": {
            "fivepercent/var-tag-validator": "~1.0"
        }
    }


Now tell composer to download the library by running the command:


.. code-block:: bash

    $ php composer.phar update fivepercent/var-tag-validator


Basic usage
-----------

Basic create **VarTagValidator** instance:

.. code-block:: php

    use FivePercent\Component\VarTagValidator\VarTagValidator;
    use FivePercent\Component\VarTagValidator\Metadata\MetadataFactory;
    use Symfony\Component\Validator\ValidatorBuilder;

    $validator = (new ValidatorBuilder())
        ->getValidator();

    $metadataFactory = new MetadataFactory();
    $varTagValidator = new VarTagValidator($validator, $metadataFactory);


After create **VarTagValidator** instance you can validate objects by **@var** tags:


.. code-block:: php

    class MyClass
    {
        /**
         * @var int
         */
        public $id;

        /**
         * @var string
         */
        public $firstName;
    }

    $object = new MyClass();
    $object->id = 1;
    $object->firstName = 'Foo Bar';

    $violationList = $varTagValidator->validateObjectByVarTags($object);

**Attention:** **VarTagValidator** system use `Symfony Validator <https://packagist.org/packages/symfony/validator>`_ for validate values.

Available **@var** tag types:

#. **integer**, **int**

#. **string**

#. **scalar**

#. **array**


Custom Var Tag
--------------

If you want add custom **@var** tag type, *Money* as example, you must create a **ConstraintFactory** for this type
 and add to registry.

**Step #1:** Create constraint factory

.. code-block:: php

    use FivePercent\Component\VarTagValidator\Constraint\ConstraintFactoryInterface;
    use Symfony\Component\Validator\Constraints as Assert;
    use FivePercent\Component\VarTagValidator\Constraint\VarTagConstraint;

    class MoneyConstraintFactory implements ConstraintFactoryInterface
    {
        /**
         * {@inheritDoc}
         */
        public function getVarTagConstraint()
        {
            $constraints = array(
                new Assert\Type('numeric')
            );

            return new VarTagConstraint($constraints);
        }
    }

And, if necessary, you can set a `group sequence <http://symfony.com/doc/current/book/validation.html#group-sequence>`_ for Symfony2 Validator.

.. code-block:: php

    use FivePercent\Component\VarTagValidator\Constraint\ConstraintFactoryInterface;
    use Symfony\Component\Validator\Constraints as Assert;
    use FivePercent\Component\VarTagValidator\Constraint\VarTagConstraint;
    use Symfony\Component\Validator\Constraints\GroupSequence;

    class MoneyConstraintFactory implements ConstraintFactoryInterface
    {
        /**
         * {@inheritDoc}
         */
        public function getVarTagConstraint()
        {
            $constraints = [];

            $constraints[] = new Assert\Type([
                'type' => 'numeric',
                'message' => 'This value should be of type money.',
                'groups' => 'FirstStep'
            ]);

            $constraints[] = new Assert\Regex([
                'pattern' => '/^\d+\.\d{2}$/',
                'message' => 'This value should be of type money.',
                'groups' => 'SecondStep'
            ]);

            $groupSequence = new GroupSequence(['FirstStep', 'SecondStep']);

            return new VarTagConstraint($constraints, $groupSequence);
        }
    }

**Step #2:** Add constraint factory to registry

.. code-block:: php

    use FivePercent\Component\VarTagValidator\VarTagValidator;
    use FivePercent\Component\VarTagValidator\Metadata\MetadataFactory;
    use Symfony\Component\Validator\ValidatorBuilder;
    use FivePercent\Component\VarTagValidator\Constraint\FactoryRegistry;

    $registry = FactoryRegistry::createDefault();
    $registry->addConstraintFactory('money', new MoneyConstraintFactory());

    $validator = (new ValidatorBuilder())
        ->getValidator();

    $metadataFactory = new MetadataFactory();
    $varTagValidator = new VarTagValidator($validator, $metadataFactory, $registry);


Tips & Tricks
-------------

#. You can add alias to registry for type.
    As example: *int -> integer*, or *float -> double*
    For more info, please see ``FivePercent\Component\VarTagValidator\Constraint\FactoryRegistryInterface::addConstraintFactoryAlias``

