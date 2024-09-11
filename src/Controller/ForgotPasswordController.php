<?php // src/Controller/ForgotPasswordController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Use the new PasswordHasherInterface
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User; // Import the User class
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Psr\Log\LoggerInterface;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'label' => 'Enter your email address',
                'attr' => ['class' => 'form-control']
            ])
            ->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];

            // Find the user by email
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                // Generate a reset token
                $resetToken = bin2hex(random_bytes(32));

                // Save the token to the user
                $user->setResetToken($resetToken);
                $em->flush();

                // Send the email with the reset link
                $resetUrl = $this->generateUrl('app_reset_password', [
                    'token' => $resetToken
                ], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

                $emailMessage = (new Email())
                    ->from('noreply@example.com')
                    ->to($user->getEmail())
                    ->subject('Password Reset Request')
                    ->html('<p>Please click <a href="' . $resetUrl . '">here</a> to reset your password.</p>');

                try {
                    // Attempt to send the email
                    $mailer->send($emailMessage);

                    // Log the email success
                    $logger->info('Password reset email sent to ' . $user->getEmail());

                    // Add a flash message for the user
                    $this->addFlash('success', 'An email has been sent with instructions to reset your password.');

                } catch (\Exception $e) {
                    // If sending the email fails, log the error message and show a failure message to the user
                    $logger->error('Failed to send password reset email: ' . $e->getMessage());

                    // Add a flash message for the user
                    $this->addFlash('error', 'There was a problem sending the reset email. Please try again later.');
                }

                // Redirect to avoid form resubmission
                return $this->redirectToRoute('app_forgot_password');
            }

            // Log the case where no user was found with the provided email
            $logger->warning('No user found for the email: ' . $email);

            $this->addFlash('error', 'No user found for that email.');
            return $this->redirectToRoute('app_forgot_password');
        }

        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        // Find the user by the reset token
        $user = $em->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Invalid reset token.');
        }

        // Create the password reset form
        $form = $this->createFormBuilder()
            ->add('password', PasswordType::class, [
                'label' => 'New Password',
                'attr' => ['class' => 'form-control']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Set the new password
            $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
            $user->setResetToken(null); // Clear the reset token
            $em->flush();

            $this->addFlash('success', 'Your password has been successfully reset.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/test-email', name: 'app_test_email')]
    public function sendTestEmail(MailerInterface $mailer): Response
    {
        try {
            $email = (new Email())
                ->from('ewa.verify@gmail.com')
                ->to('alexborzza@gmail.com') // Replace with your Mailtrap inbox email
                ->subject('Test Email')
                ->text('This is a test email.');

            $mailer->send($email);

            return new Response('Test email sent successfully!');

        } catch (\Exception $e) {
            return new Response('Error sending email: ' . $e->getMessage());
        }
    }
}
