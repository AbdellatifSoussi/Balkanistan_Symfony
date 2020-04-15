<?php

namespace App\Controller;

use App\Entity\Mairie;
use App\Form\Type\MairieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class MairieController extends AbstractController{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function list(){
        $mairies = $this->getDoctrine()->getRepository(Mairie::class)->findAll();
        return $this->render('mairie/list.html.twig',[
            'mairies' => $mairies
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function ajouter(Request $request){
        $mairie = new Mairie();
        $form = $this->createForm(MairieType::class, $mairie,
            ['action' => $this->generateUrl('mairie_ajouter')]);

        $form->add('submit', SubmitType::class,
            array('label' => 'Valider'));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($mairie);
            $em->flush();
            $this->addFlash('success',"Mairie ajoutée avec success !");
            return $this->redirectToRoute('mairie_list');
        }
        return $this->render('mairie/ajouter.html.twig',[
            'monFormulaire' =>$form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Request $request, Mairie $mairie){

        if($this->isCsrfTokenValid('delete'.$mairie->getId(), $request->request->get('_token'))){
            $this->entityManager->remove($mairie);
            $this->entityManager->flush();
            $this->addFlash('success',"Mairie supprimée !");
            return $this->redirectToRoute('mairie_list');
        }
        else{
            $this->addFlash('erreur',"Mairie non supprimée car ils existent des politiciens appartenant à cette mairie !");
            return $this->redirectToRoute('mairie_list');
        }

    }

}
