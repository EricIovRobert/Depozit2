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
                'label' => 'Date',
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
            // Note: stoc_intrare is removed from here since it will be calculated automatically.
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intrare::class,
        ]);
    }
}
