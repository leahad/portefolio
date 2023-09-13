<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Project;
use App\Form\ContactType;
use App\Form\SkillsFilterType;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
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
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

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
        ChartBuilderInterface $chartBuilder,
    ): Response {
        $SearchBySkills = $this->createForm(SkillsFilterType::class, null, ['method' => 'GET']);
        $SearchBySkills->handleRequest($request);

        $projects = $paginator->paginate(
            $projectRepository->findAll(['id'=>'DESC']), 
            $request->query->getInt('page', 1), 4
        );

        if ($SearchBySkills ->isSubmitted() && $SearchBySkills->isValid()) {
            $search = $SearchBySkills->getData();
            $projects = $paginator->paginate(
                $projectRepository->findSkills($search),
                $request->query->getInt('page', 1), 4
            );
        }

        //Projects
        $chart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $languages = array_keys($github->getLanguages());
        $bytes = array_values($github->getLanguages());

        $chart->setData([
            'labels' => array_splice($languages,0,-2),
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' =>  [
                        '#394188',
                        '#2f05eaa6',
                        '#CDC2FE',
                        '#004B35'
                    ],
                    'borderColor' =>  [
                        '#394188',
                        '#2f05eaa6',
                        '#CDC2FE',
                        '#004B35'
                    ],
                    'data' => array_splice($bytes,0,-2),
                ],
            ],
        ]);

        $chart->setOptions([
            'plugins' => [
                'title' => [
                    'display' => false,
                ]
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ]
            ]
        ]);

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
                ->html($this->renderView('home/contactEmail.html.twig', ['contact' => $contact]));

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a été envoyé avec succès !'
            );

            return $this->redirectToRoute('home', ['contact_form' => $form ], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('home/index.html.twig', [
            'projects' => $projects,
            'skills_form' => $SearchBySkills,
            'contact_form' => $form->createView(),
            'github_contributions' => $github->getTotalContributions(),
            // 'languages' => $github->getLanguages(),
            'fortune_cookie' => $fortuneCookie->getMessage(),
            // 'chart' => $chart,
        ]);
    }
}
