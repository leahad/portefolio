<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ProjectRepository;
use App\Service\FortuneCookie;
use App\Service\FortuneCookies;
use App\Service\GithubData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        Request $request, 
        EntityManagerInterface $manager, 
        MailerInterface $mailer, 
        GithubData $github,
        FortuneCookie $fortuneCookie,
        ProjectRepository $projectRepository,
    ): Response {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('hadida.lea@gmail.com')
                ->subject($contact->getSubject())
                ->html($this->renderView('home/contactEmail.html.twig', ['contact' => $contact]));

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a été envoyé avec succès !'
            );

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView(),
            'github_contributions' => $github->getTotalContributions(),
            'fortune_cookie' => $fortuneCookie->getMessage(),
            'projects' => $projectRepository->findAll()
        ]);
    }
}
