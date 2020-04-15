<?php
namespace App\Form\Type;
use App\Entity\Mairie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MairieType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('ville', TextType::class, array('label' =>'Ville'));
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Mairie::class,
        ));
    }
}
