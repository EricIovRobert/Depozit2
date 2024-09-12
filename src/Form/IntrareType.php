<?php

namespace App\Form;

use App\Entity\Intrare;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntrareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('data', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'label' => 'Date',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'dd/mm/yyyy',
                    'id' => 'data',
                ],
            ])
            ->add('nr_doc_intrare', TextType::class, [
                'label' => 'Document Number',
            ])
            ->add('intrari', IntegerType::class, [
                'label' => 'Entries',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'intrari',
                ],
            ])
            ->add('nefolosibile', IntegerType::class, [
                'label' => 'Non-usable',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'nefolosibile',
                ],
            ]);

        // Add custom validation listener
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $intrari = $form->get('intrari')->getData();
            $nefolosibile = $form->get('nefolosibile')->getData();

           
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intrare::class,
        ]);
    }
}
