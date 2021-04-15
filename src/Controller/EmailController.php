<?php

namespace App\Controller;

use App\Form\ResetEmailType;
use App\Model\ChangePassword;
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
        $changePassword = new ChangePassword();
        $formChangePassword = $this->createForm('App\Form\ResetPasswordType', $changePassword);
        $form = $this->createForm('App\Form\ResetEmailType');
        $form->handleRequest($request);

        if($request){
            dump($request->request);
        };
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $newEmail = $form->get('email')['first']->getData();
//            $emailConfirmation = $form->get('email')['second']->getData();
//
//            if ($newEmail === $emailConfirmation) {
//                $user->setEmail($newEmail);
//                $em->persist($user);
//                $em->flush();
//                $this->addFlash('notice', 'Votre adresse email à bien été changé !');
//
//                //TODO changer la route
//                return $this->redirectToRoute('task_index');
//
//            } else {
//
//                $form->addError(new FormError('Vos adresses mail doivent correspondre'));
//
//            }
//
//        }

        return $this->render('settings/email-update.html.twig', array(
            'form' => $form->createView(),
            'changePassword' => $formChangePassword->createView(),
            'user' => $user,
        ));
    }

    /**
     * @Route("/password", name="reset-password")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $changePassword = new changePassword();
        $form = $this->createForm('App\Form\ResetPasswordType', $changePassword);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $newpwd = $form->get('Password')['first']->getData();

            $newEncodedPassword = $passwordEncoder->encodePassword($user, $newpwd);
            $user->setPassword($newEncodedPassword);

            $em->flush();
            $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

            return $this->redirectToRoute('task_index');
        }

        //TODO changer la route
        return $this->render('settings/password-update.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }
}
