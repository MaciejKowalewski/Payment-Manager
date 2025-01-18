<?php

namespace App\Form;

use App\Entity\Payments;
use App\Entity\paymentUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AddPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder =  new DynamicFormBuilder($builder);

        $builder
            ->add('paymentname', TextType::class,  [
                'label' => 'Nazwa Płatności',
            ])
            ->add('amount', NumberType::class,  [
                'label' => 'Kwota',
            ])
            ->add('paymentDate', DateType::class, [
                'label' => 'Termin płatności',
            ])
            ->add('link')
            ->add('description', TextType::class,  [
                'required' => false,
                'label' => 'Opis',
            ])
            ->add('email', EntityType::class, [
                'class' => paymentuser::class,
                'choice_label' => 'id',
            ])
            ->add('paymenttype', ChoiceType::class, [
                'label' => 'Rodzaj płatności',
                'choices' => [
                    'Jednorazowa' => 'one-time',
                    'Cykliczna' => 'cyclic',
                    'Plan Płatności' => 'paymentPlan',
                ],
            ])
            ->add('paid', ChoiceType::class, [
                'choices' => [
                    'Opłacona' => true,
                    'Nieopłacona' => false
                ]
            ]);
            

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payments::class,
        ]);
    }
}
