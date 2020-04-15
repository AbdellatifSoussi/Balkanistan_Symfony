<?php
namespace App\Form\Type;
use App\Entity\Affaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchAffaireType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('designation', TextType::class, array('label' =>'Designation'));
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Affaire::class,
        ));
    }
}
