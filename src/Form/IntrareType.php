<?php

namespace App\Form;

use App\Entity\Intrare;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntrareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('data', DateType::class, [
                'widget' => 'single_text',
                'html5' => false, // disable HTML5 date picker
                'format' => 'dd/MM/yyyy', // Symfony default format for the date type
                'label' => 'Date',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'dd/mm/yyyy',
                    'id' => 'data' // we will use this id in the Twig file
                ],
            ])
            ->add('nr_doc_intrare', TextType::class, [
                'label' => 'Document Number',
            ])
            ->add('intrari', IntegerType::class, [
                'label' => 'Entries',
            ])
            ->add('nefolosibile', IntegerType::class, [
                'label' => 'Non-usable',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intrare::class,
        ]);
    }
}
