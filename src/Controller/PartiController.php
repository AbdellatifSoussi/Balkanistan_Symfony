<?php

namespace App\Controller;

use App\Entity\Parti;
use App\Entity\Politicien;
use App\Form\Type\PartiType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class PartiController extends AbstractController{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function list(){
        $partis = $this->getDoctrine()->getRepository(Parti::class)->findAll();
        return $this->render('parti/list.html.twig',[
            'partis' => $partis
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function ajouter(Request $request){
        $parti = new Parti();
        $form = $this->createForm(PartiType::class, $parti,
            ['action' => $this->generateUrl('parti_ajouter')]);

        $form->add('submit', SubmitType::class,
            array('label' => 'Valider'));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($parti);
            $em->flush();
            $this->addFlash('success',"Parti ajouté avec success !");
            return $this->redirectToRoute('parti_list');
        }
        return $this->render('parti/ajouter.html.twig',[
            'monFormulaire' =>$form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Request $request, Parti $parti){

        if($this->isCsrfTokenValid('delete'.$parti->getId(), $request->request->get('_token'))){
            $this->entityManager->remove($parti);
            $this->entityManager->flush();
            $this->addFlash('erreur',"Parti ne peut pas être supprimé car ils existent des politiciens appartenant à cet parti !");
            return $this->redirectToRoute('parti_list');
        }
        else{
            $this->addFlash('erreur',"Parti non supprimé car ils existent des politiciens appartenant à cet Parti! !");
            return $this->redirectToRoute('parti_list');
        }
    }

    public function PartiPoliticiens(Parti $parti){

        $partiPoliticiens = $this->getDoctrine()->getRepository(Parti::class)->findPartiPoliticiens($parti);
        $partis = $this->getDoctrine()->getRepository(Parti::class)->findMoyenneAge($parti);
        $nombreFemme = $this->getDoctrine()->getRepository(Parti::class)->findNombreFemme($parti);
        $nombreHomme = $this->getDoctrine()->getRepository(Parti::class)->findNombreHomme($parti);

        //dump($nombreFemme);
        return $this->render('parti/partiPoliticiens.html.twig',[
            'partiPoliticiens' => $partiPoliticiens,
            'partis' => $partis,
            'nombreFemme' => $nombreFemme,
            'nombreHomme' => $nombreHomme
        ]);
    }
}
