<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    private $logger;
    private $isDebug;

    public function __construct(LoggerInterface $logger, bool $isDebug)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }


    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(QuestionRepository $repository)
    {
        $questions = $repository->findAllAskedOrderedByNewest();
//        dd($questions);
        return $this->render('question/homepage.html.twig',
        [
            'questions' => $questions
        ]
        );
    }

    /**
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entityManager)
    {
        $qestion = new Question();
        $qestion->setName('Missing pants')
        ->setSlug('missing-pants-'.rand(0,1000))
        ->setQuestion(<<<EOF
            Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. 
            Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. 
            Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat 
            massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. 
            In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis 
            eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. 
            Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, 
            enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut 
            metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. 
            Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, 
            tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque 
            sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec
             odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis 
             ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit 
             amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc?
             
        EOF
        );
        if (rand(1,10) > 2) {
            $qestion->setAskAt(
                new \DateTime(sprintf('-%d days', rand(1,100)))
            );
        }
        $qestion->setVotes(rand(-20, 50));
        $entityManager->persist($qestion);
        $entityManager->flush();

        return new Response(sprintf('Well hallo! The shiny new question is id #%d, slug %s',
            $qestion->getId(),
            $qestion->getSlug()
        ));
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show(
        Question $question
    ) {

        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }

        $answers = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }

    /**
     * @Route("/question/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(
        Question $question,
        Request $request,
        EntityManagerInterface $entityManager
    ) {
        $direction = $request->request->get('direction');
        if ($direction === 'up') {
            $question->upVotes();
        } elseif ($direction === 'down') {
            $question->downVotes();
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_question_show', [
            'slug' => $question->getSlug()
        ]);
    }
}
