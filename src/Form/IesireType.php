<?php

namespace App\Form;

use App\Entity\Iesire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IesireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('data', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])
            ->add('nr_doc_iesire', TextType::class, [
                'label' => 'Document Number',
            ])
            ->add('iesiri', IntegerType::class, [
                'label' => 'Exits',
            ]);
            // The 'stoc_iesire' field has been removed because it's calculated automatically
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Iesire::class,
        ]);
    }
}
