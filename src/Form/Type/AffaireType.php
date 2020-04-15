<?php
namespace App\Form\Type;
use App\Entity\Affaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffaireType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('designation', TextType::class, array('label' =>'Designation'))
                ->add('politiciens_impliques',null,array(
                    'by_reference' => false,
                    'multiple' => true
                ));
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Affaire::class,
        ));
    }
}
