<?php

namespace App\Controller;

use App\Entity\Affaire;
use App\Form\Type\AffaireType;
use App\Form\Type\SearchAffaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AffaireController extends AbstractController{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function list(Request $request){
        //$affaires = $this->getDoctrine()->getRepository(Affaire::class)->findAll();
        $aff = new Affaire();

        $form = $this->createForm(SearchAffaireType::class, $aff);
        $form->add('submit', SubmitType::class,
            array('label' => 'Rechercher'));
        $form->handleRequest($request);
        $search = $this->getDoctrine()->getRepository(Affaire::class)->searchAffaires($aff);
        return $this->render('affaire/list.html.twig',[
            'form' => $form->createView(),
            'affaires' => $search
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function ajouter(Request $request){
        $affaire = new Affaire();

        $form = $this->createForm(AffaireType::class, $affaire,
            ['action' => $this->generateUrl('affaire_ajouter')]);
        $form->add('submit', SubmitType::class,
            array('label' => 'Valider'));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($affaire);
            $em->flush();
            return $this->redirectToRoute('affaire_list');
        }
        return $this->render('affaire/ajouter.html.twig',[
            'monFormulaire' =>$form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Affaire $affaire){
        $this->entityManager->remove($affaire);
        try {
            $this->entityManager->flush();
        }catch(\Exception $e){
            return $this->redirectToRoute('affaire_list');
        }
        return $this->redirectToRoute('affaire_list');
    }
    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function modifier($id){
        $affaire = $this->getDoctrine()->getRepository(Affaire::class)->find($id);
        if(!$affaire)
            throw $this->createNotFoundException('Affaire[id='.$id.'] inexistante');
        $form = $this->createForm(AffaireType::class, $affaire,
            ['action' => $this->generateUrl('affaire_modifier_suite',
                array('id' =>$affaire->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render('affaire/modifier.html.twig',[
            'monFormulaire' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function modifierSuite(Request $request, $id){
        $affaire = $this->getDoctrine()->getRepository(Affaire::class)->find($id);
        if(!$affaire)
            throw $this->createNotFoundException('Affaire[id='.$id.'] inexistante');
        $form = $this->createForm(AffaireType::class, $affaire,
            ['action' => $this->generateUrl('affaire_modifier_suite',
                array('id' => $affaire->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($affaire);
            $entityManager->flush();
            return $this->redirectToRoute('affaire_list');
        }
        return $this->render('affaire/modifier.html.twig',
            array('monFormulaire' => $form->createView()));
    }
}
