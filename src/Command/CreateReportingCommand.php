<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Activity;
use App\MessageHandler\ActivityHandler;

#[AsCommand(
    name: 'app:projects:report',
    description: "Create a tickets'reporting day for each project.",
    hidden: false,
    aliases: ['app:add-report']
)]

class CreateReportingCommand extends Command 
{
    private EntityManagerInterface $em;
    private ActivityHandler $activityHandler;

    public function __construct(ActivityHandler $activityHandler, EntityManagerInterface $em)
    {   
        $this->em = $em;
        $this->activityHandler =$activityHandler;
        parent::__construct();
    }

    protected function configure() :void
    {
        $this
            ->addArgument('gap', InputArgument::OPTIONAL, 'defined the day gap.', 1)
            ->setHelp ("This command allows to created a activities'report for each project according to a gap")
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) :int
    {
        $gap = $input->getArgument('gap');
        $date = new \dateTime('now');
   
        $activities = $this->em->getRepository(Activity::class)->findActivitiesByPeriod($gap, $date);
        
        $this->activityHandler->sendEmail($activities);

        return Command::SUCCESS; 
      
    }
}