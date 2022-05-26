<?php

namespace App\MessageHandler;


use App\Entity\Activity;
use App\Entity\Admin;
use App\Entity\Project;
use App\Message\ActivityMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

#[AsMessageHandler]
class ActivityHandler implements MessageHandlerInterface
{
    private $entityManager;

    private $client;
 
    private $nicokaPassword;

    private $nicokaUser;

    private $adminEmail;

public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $client, $nicokaUser, $nicokaPassword, MailerInterface $mailer, string $adminEmail)
    {
        $this->entityManager = $entityManager;
        $this->client = $client;
        $this->nicokaUser = $nicokaUser;
        $this->nicokaPassword = $nicokaPassword;
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
    }

    public function sendEmail(Array $activities)
    {
        $admins = $this->entityManager->getRepository(Admin::class)->findAll();
       
        $projects =  $this->entityManager->getRepository(Project::class)->findAll();
 
        $date = (new \DateTime('now'))->format('d/m/Y');

        foreach ($admins as $admin) {
            
            $activitiesByAdmin = array_filter($activities, function(Activity $activity) use($admin){
                return $activity->getProject()->getAdmins()->contains($admin);
            });
            
            if(!empty($activitiesByAdmin)){  
                    $this->mailer->send((new TemplatedEmail())
                    ->subject("Your activities'daily report")
                    ->htmlTemplate('emails/activities_notification.html.twig')
                    ->from($this->adminEmail)
                    ->to($admin->getEmail())
                    ->context([  
                        'date' => $date,
                        'admin' => $admin,
                        'activities'=>$activitiesByAdmin,
                        'projects' => $projects,
                        ])
                    );
            }
        }
    }

    public function __invoke(ActivityMessage $message)
    {
        /**nicoka_id
         * @Activity
         */
        $activity = $this->entityManager->getRepository(Activity::class)->find($message->getActivityId());

        if (!$activity) {
            return;
        }

        try {
            $tokenRequest = $this->client->request(
                'POST',
                'https://acelys.nicoka.com/api/login',
                [
                    'query' =>
                        [
                            'login' => $this->nicokaUser,
                            'password' => $this->nicokaPassword,
                        ]
                ]
            );

            $tokenResponse = json_decode($tokenRequest->getContent(), true);
        } catch (\Exception $e) {
            throw new BadRequestException('Unable to connect to Nicoka');
        }

        try {
           $this->client->request(
                'POST',
                'https://acelys.nicoka.com/api/projectItems',
                [
                    'auth_bearer' => $tokenResponse['token'],
                    'body' =>
                        [
                            'projectid' => $activity->getProject()->getNicokaId(),
                            'type' => 1,
                            'label' => $activity->getSummary(),
                            'status' => 1,
                            'price' => $activity->getProject()->getTjm(),
                            'unit' => 1,
                            'ts_enable' => 1,
                            'invoiceable' => 1,
                            'currency' => 'EUR',
                        ]
                ]
            );

        } catch (\Exception $e) {
            throw new BadRequestException('Unable to created activity');
        }

        $activity->setIsManaged(true);

        $this->entityManager->persist($activity);
        $this->entityManager->flush();
    }
}

