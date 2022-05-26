<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    #[Route('/webhook/activity/new', name: 'webhook_activity_new', methods: 'POST')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $webhookParameters = json_decode($request->getContent(), true);

        if (!isset($webhookParameters['issue'])) {
            throw new BadRequestException();
        }

        $project = $entityManager->getRepository(Project::class)->findOneBy(['jiraKey' => $webhookParameters['issue']['fields']['project']['key']]);

        if (!$project) {
            throw new NotFoundHttpException();
        }

        $activity = new Activity();

        $activity->setJiraKey($webhookParameters['issue']['key']);
        $activity->setSummary($webhookParameters['issue']['fields']['summary']);
        $activity->setProject($project);

        $entityManager->persist($activity);
        $entityManager->flush();


        return new JsonResponse(['message' => 'Activity will be managed soon']);
    }
}
