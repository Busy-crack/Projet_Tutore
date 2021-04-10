<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/settings", name="resetPassword", methods={"GET","POST"})
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        dump($user);
        $passwordForm = $this->createForm(ResetPasswordType::class, $user);
        $passwordForm->handleRequest($request);


        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $oldPassword = $user->getPassword();
            // Si l'ancien mot de passe est bon
            if (true === true) {
                $newEncodedPassword = $passwordEncoder->encodePassword($request->get("password"));
                $user->setPassword($newEncodedPassword);
                dump($newEncodedPassword);
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');
                //TODO route à actualiser
                return $this->redirectToRoute('profile');
            } else {
                $passwordForm->addError(new FormError('Ancien mot de passe incorrect'));
            }
//            dump($request->request);
        }


        return $this->render('settings/password-update.html.twig', array(

            'form' => $passwordForm->createView(),

        ));
    }
}
