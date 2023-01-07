<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Form\DeleteTweetType;
use App\Form\TweetType;
use App\Service\UserService;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public FormInterface $newFormTweet;
    private Tweet $tweet;

    public function __construct()
    {
        $this->tweet = new Tweet();
    }

    #[Route('/home', name: 'app_home')]
    public function index(
        Request $request,
        TweetRepository $tweetRepository,
        UserService $userService
    ): Response {
        $this->newFormTweet = $this->createForm(
            TweetType::class,
            $this->tweet
        );
        $this->newFormTweet->handleRequest($request);
        if ($this->newFormTweet->isSubmitted() && $this->newFormTweet->isValid()) {
            $this->new($tweetRepository, $userService);
        }
        // dump($userService->getUserConnected()->getUsername());
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $userService->getUserConnected(),
            'tweets' => $tweetRepository->findOrderByUpdateAt(),
            'newFormTweet' => $this->newFormTweet,
        ]);
    }

    // #[Route('/tweet/new', name: 'app_tweet_new', methods: ['POST'])]
    public function new(
        TweetRepository $tweetRepository,
        UserService $userService
    ): void {
        $date = new \DateTimeImmutable();
        $this->tweet->setUserId($userService->getUserConnected());
        $this->tweet->setCreatedAt($date);
        $this->tweet->setUpdatedAt($date);
        $tweetRepository->save($this->tweet, true);
    }

    #[Route('/tweet/delete/{id}', name: 'app_tweet_delete', methods: ['GET'])]
    public function delete(
        Request $request,
        TweetRepository $tweetRepository,
    ): Response {
        $id = $request->get('id');
        $path = $request->get('path');
        $tweet = $id ? $tweetRepository->findOneBy(['id' => $id]) : false;
        if ($tweet) {
            $tweetRepository->remove($tweet, true);
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route('/tweet/show/{id}', name: 'app_tweet_show', methods: ['GET', 'POST'])]
    public function show(
        Request $request,
        TweetRepository $tweetRepository,
        UserService $userService
    ): Response {
        $id = $request->get('id');
        $tweet = $id ? $tweetRepository->findOneBy(['id' => $id]) : false;
        $date = new \DateTimeImmutable();
        $newTweet = new Tweet();
        $this->newFormTweet = $this->createForm(
            TweetType::class,
            $newTweet
        );
        $this->newFormTweet->handleRequest($request);
        if ($this->newFormTweet->isSubmitted() && $this->newFormTweet->isValid()) {
            $newTweet->setUserId($userService->getUserConnected());
            $newTweet->setParentId($tweet);
            $newTweet->setCreatedAt($date);
            $newTweet->setUpdatedAt($date);
            $tweetRepository->save($newTweet, true);
            $tweet = $tweetRepository->findOneBy(['id' => $id]);
        }
        // dump($tweet);
        if ($tweet) {
            return $this->render('tweet/show.html.twig', [
                'tweet' => $tweet,
                'newFormTweet' => $this->newFormTweet,
                'user' => $userService->getUserConnected(),
            ]);
        }
        return $this->redirectToRoute('app_home');
    }
}
