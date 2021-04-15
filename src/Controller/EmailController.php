<?php

namespace App\Controller;

use App\Form\ResetEmailType;
use App\Model\ChangePassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class EmailController extends AbstractController
{
    /**
     * @Route("/email-update", name="email-update")
     */
    public function editEmail(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $changePassword = new ChangePassword();
        $formChangePassword = $this->createForm('App\Form\ResetPasswordType', $changePassword);
        $form = $this->createForm('App\Form\ResetEmailType');

        $form->handleRequest($request);
        $formChangePassword->handleRequest($request);

        if($request->request){
            dump($request->request);
        }

        if($request->request->has("reset_email")){
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
        }

        if($request->request->has("reset_password")){
            if ($formChangePassword->isSubmitted() && $formChangePassword->isValid()) {

                $newpwd = $formChangePassword->get('Password')['first']->getData();

                $newEncodedPassword = $passwordEncoder->encodePassword($user, $newpwd);
                $user->setPassword($newEncodedPassword);

                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('task_index');
            }
        };


        return $this->render('settings/email-update.html.twig', array(
            'form' => $form->createView(),
            'changePassword' => $formChangePassword->createView(),
            'user' => $user,
        ));
    }

}
