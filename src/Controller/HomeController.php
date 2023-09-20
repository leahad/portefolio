<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Project;
use App\Form\ContactType;
use App\Form\SkillsFilterType;
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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(
        Request $request, 
        EntityManagerInterface $manager, 
        MailerInterface $mailer, 
        GithubData $github,
        FortuneCookie $fortuneCookie,
        ProjectRepository $projectRepository,
        PaginatorInterface $paginator,
    ): Response {

        // dd($github->getLanguagesWithPercentage());
        foreach ($projectRepository->findAll() as $project) {
            if (is_null($project->getGithubLanguages())) {
                $projectId = $project->getId();
                $projectLanguages = $github->getLanguagesWithPercentage()[$projectId] ?? [];
                $project->setGithubLanguages($projectLanguages);
                $manager->persist($project);
            }
        }
        $manager->flush(); 

        // Projects Display
        $searchBySkills = $this->createForm(SkillsFilterType::class, null, ['method' => 'GET']);
        $searchBySkills->handleRequest($request);

        $projects = $paginator->paginate(
            $projectRepository->findAll(['id'=>'DESC']), 
            $page = (int)$request->query->getInt('page', 1), 4
        );

        if ($searchBySkills ->isSubmitted() && $searchBySkills->isValid()) {
            $search = $searchBySkills->getData();
            $projects = $paginator->paginate(
                $projectRepository->findProjectsBySkills($search),
                $request->query->getInt('page', 1), 4
            );
        }

        //Contact Form
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
                ->html($this->renderView('contact/contactEmail.html.twig', ['contact' => $contact]));

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a été envoyé avec succès !'
            );

            return $this->redirectToRoute('home', ['contact_form' => $form ], Response::HTTP_SEE_OTHER);
        }

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('project/_projects.html.twig', ['projects' => $projects]),
                'pagination' => $this->renderView('project/_pagination.html.twig', ['projects' => $projects]),
            ]);
        };
    
        return $this->render('index.html.twig', [
            'projects' => $projects,
            'page'=> $page,
            'skills_form' => $searchBySkills,
            'contact_form' => $form->createView(),
            'github_contributions' => $github->getTotalContributions(),
            'fortune_cookie' => $fortuneCookie->getMessage(),
        ]);
    }
}
