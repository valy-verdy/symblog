<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class ,[
                'required' => true ,
                'label' => 'titre' ,
                'attr' => ['placeholder' => "Titre de l'article"]

            ])
            ->add('content', TextareaType::class,[
                'required' => true
                ])
            ->add('picture',TextType::class ,[
                'required' => true
                ])

            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name',
                'required'=>true
            ])
            // ->add ('submit', SubmitType::class,[
            //     'label' => "Creer L'article"
            // ]
            // )
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
