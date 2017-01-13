<?php

namespace TechCorp\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TechCorp\FrontBundle\Entity\Status;
use TechCorp\FrontBundle\Form\StatusType;

class TimelineController extends Controller
{
    public function timelineAction()
    {
        $em = $this->getDoctrine()->getManager();

        $statuses = $em->getRepository('TechCorpFrontBundle:Status')->findAll();

        return $this->render('TechCorpFrontBundle:Timeline:timeline.html.twig', array(
            'statuses' => $statuses,
        ));
    }

    public function userTimelineAction($userId)
    {
        $em = $this->getDoctrine()->getManager();

        // Récupérer l'utilisateur courant
        $user = $em->getRepository('TechCorpFrontBundle:User')->findOneById($userId);

        if (!$user) {
            $this->createNotFoundException("L'utilisateur n'a pas été trouvé");
        }

        // Créer le formulaire
        $authenticatedUser = $this->get('security.context_storage')->getToken()->getUser();
        $status = new Status();
        $status->setDeleted(false);
        $status->setUser($authenticatedUser);

        $form = $this->createForm(new StatusType(), $status);

        $request = $this->getRequest();
        // Récupérer les statuts
        $statuses = $em->getRepository('TechCorpFrontBundle:Status')->getUserTimeline($user)->getResult();

        // Rendre la page
        return $this->render('TechCorpFrontBundle:Timeline:user_timeline.html.twig', array(
            'user' => $user,
            'statuses' => $statuses,
            'form' => $form->createView()
        ));
    }

    public function friendsTimelineAction($userId)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('TechCorpFrontBundle:User')->findOneById($userId);  
        if (!$user) {
            $this->createNotFoundException("L'utilisateur n'a pas été trouvé");
        }

        $statuses = $em->getRepository('TechCorpFrontBundle:Status')->getFriendsTimeline($user)->getResult();

        return $this->render('TechCorpFrontBundle:Timeline:friends_timeline.html.twig',
            array(
               'user' => $user,
                'statuses' => $statuses
            ));
    }
}