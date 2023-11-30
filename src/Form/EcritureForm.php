<?php

namespace App\Form;

use App\Entity\Ecriture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class EcritureForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                "empty_data" => "",
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        "min" => 1,
                        "max" => 255
                    ]),
                ]
            ]);
        if (!$options["edit"]) {
            $builder->add('type', ChoiceType::class, [
                "expanded" => false,
                "multiple" => false,
                "choices" => array_keys(Ecriture::TYPE),
                "choice_label" => function ($choice, $key, $value) {
                    return Ecriture::TYPE[$choice];
                }
            ]);
        }


        $builder->add('amount', NumberType::class, [
            "scale" => 2,
            "constraints" => [
                new Positive()
            ]
        ])

            ->add('date', DateType::class, [
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                "input" => "string",
                "years" => [
                    (int)date("Y"),
                    (int)date("Y") - 1,
                    (int)date("Y") - 2
                ],
                "constraints" => [
                    new LessThan('today')
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ecriture::class,
            "edit" => false
        ]);
    }
}
