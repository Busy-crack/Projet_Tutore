<?php

namespace App\Controller;

use App\Form\ResetEmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    /**
     * @Route("/email-update", name="email-update")
     */
    public function editEmail(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm('App\Form\ResetEmailType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newEmail = $form->get('email')['first']->getData();
            $emailConfirmation = $form->get('email')['second']->getData();

            if ($newEmail === $emailConfirmation) {
                $user->setEmail($newEmail);
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Votre adresse email à bien été changé !');

                //TODO changer la route
                return $this->redirectToRoute('task_index');

            } else {

                $form->addError(new FormError('Vos adresses mail doivent correspondre'));

            }

        }

        return $this->render('settings/email-update.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }
}
