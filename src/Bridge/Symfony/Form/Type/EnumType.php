<?php

/*
 * This file is part of the "elao/enum" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\Enum\Bridge\Symfony\Form\Type;

use Elao\Enum\EnumInterface;
use Elao\Enum\ReadableEnumInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnumType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choices' => function (Options $options) {
                    $enumClass = $options['enum_class'];

                    if (!$options['as_value']) {
                        return $enumClass::getPossibleInstances();
                    }

                    $possibleValues = $enumClass::getPossibleValues();

                    if (!$this->isReadable($enumClass)) {
                        return $possibleValues;
                    }

                    $choices = [];
                    foreach ($possibleValues as $possibleValue) {
                        $choices[$enumClass::getReadableFor($possibleValue)] = $possibleValue;
                    }

                    return $choices;
                },
                'choice_label' => function (Options $options) {
                    if ($options['as_value']) {
                        return null;
                    }

                    return $this->isReadable($options['enum_class']) ? 'readable' : 'value';
                },
                'choice_value' => function (Options $options) {
                    return $options['as_value'] ? null : 'value';
                },
                // If true, will accept and return the enum value instead of object:
                'as_value' => false,
            ])
            ->setRequired('enum_class')
            ->setAllowedValues('enum_class', function ($value) {
                return is_a($value, EnumInterface::class, true);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    private function isReadable(string $enumClass)
    {
        return is_a($enumClass, ReadableEnumInterface::class, true);
    }
}
