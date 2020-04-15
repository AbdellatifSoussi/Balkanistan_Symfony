<?php
namespace App\Form\Type;
use App\Entity\Mairie;
use App\Entity\Parti;
use App\Entity\Politicien;
use App\Entity\Affaire;
use App\Repository\MairieRepository;
use App\Repository\PartiRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoliticienAffaireType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('sexe', ChoiceType::class, array('choices' =>['M'=>'M','F'=>'F']))
            ->add('nom', TextType::class, array('disabled' => true ))
            ->add('age', TextType::class, array('label' =>'Age'))
            ->add('mairie', EntityType::class,
                array('class' => Mairie::class,
                    'query_builder' => function (MairieRepository $repo) {
                        return $repo->createQueryBuilder('m')
                            ->orderBy('m.ville', 'ASC'); }
                ))
            ->add('parti', EntityType::class,
                array('class' => Parti::class,
                    'query_builder' => function (PartiRepository $repo) {
                        return $repo->createQueryBuilder('p')
                            ->orderBy('p.nom', 'ASC'); }
                ))
            ->add('affaires_impliques',null, array(
                'by_reference' => false,
                'multiple' => true,
                'expanded' => true,
                ));
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Politicien::class,
        ));
    }
}
