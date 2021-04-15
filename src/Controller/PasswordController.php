<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Model\ChangePassword;

class PasswordController extends AbstractController
{
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
