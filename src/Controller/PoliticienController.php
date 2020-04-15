<?php

namespace App\Controller;

use App\Entity\Affaire;
use App\Entity\Mairie;
use App\Entity\Parti;
use App\Entity\Politicien;
use App\Form\Type\PoliticienAffaireType;
use App\Form\Type\PoliticienType;
use App\Form\Type\Politicien2Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class PoliticienController extends AbstractController{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function list(){
        $politiciens = $this->getDoctrine()->getRepository(Politicien::class)->findAll();
        return $this->render('politicien/list.html.twig',[
            'politiciens' => $politiciens
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function ajouter(Request $request){
        $politicien = new Politicien();
        $form = $this->createForm(PoliticienType::class, $politicien,
            ['action' => $this->generateUrl('politicien_ajouter')]);

        $form->add('submit', SubmitType::class,
            array('label' => 'Valider'));
        //$politicien->addAffairesImpliques();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($politicien);
            $em->flush();
            return $this->redirectToRoute('politicien_list');
            //dump($form);
        }
        return $this->render('politicien/ajouter.html.twig',[
            'monFormulaire' =>$form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Politicien $politicien){
        $this->entityManager->remove($politicien);
        $this->entityManager->flush();
        return $this->redirectToRoute('politicien_list');
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function modifier($id){
        $politicien = $this->getDoctrine()->getRepository(Politicien::class)->find($id);
        if(!$politicien)
            throw $this->createNotFoundException('Politicien[id='.$id.'] inexistant');
        $form = $this->createForm(PoliticienAffaireType::class, $politicien,
            ['action' => $this->generateUrl('politicien_modifier_suite',
                array('id' =>$politicien->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render('politicien/modifier.html.twig',[
            'monFormulaire' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function modifierSuite(Request $request, $id){
        $politicien = $this->getDoctrine()->getRepository(Politicien::class)->find($id);
        if(!$politicien)
            throw $this->createNotFoundException('Politicien[id='.$id.'] inexistant');
        $form = $this->createForm(PoliticienAffaireType::class, $politicien,
            ['action' => $this->generateUrl('politicien_modifier_suite',
                array('id' => $politicien->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($politicien);
            $entityManager->flush();
            return $this->redirectToRoute('politicien_list');
        }
        return $this->render('politicien/modifier.html.twig',
            array('monFormulaire' => $form->createView()));
    }


}
