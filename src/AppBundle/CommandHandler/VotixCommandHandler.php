<?php

namespace AppBundle\CommandHandler;

use AppBundle\Command\CheckStatusCommand;
use AppBundle\Command\DropVotersCommand;
use AppBundle\Command\GenerateTokenCommand;
use AppBundle\Command\ResetCandidatesCommand;
use AppBundle\Command\SendMailCommand;
use AppBundle\Entity\Voter;
use AppBundle\Repository\CandidateRepository;
use AppBundle\Repository\VoterRepository;
use AppBundle\Service\MailerService;
use AppBundle\Service\StatsService;
use AppBundle\Service\TokenServiceInterface;
use Aws\Ses\SesClient;
use Broadway\CommandHandling\CommandHandler;
use Psr\Log\LoggerInterface;

/**
 * A command handler that only handles ExampleCommand commands.
 */
class VotixCommandHandler extends CommandHandler
{
    /** @var CandidateRepository */
    private $candidateRepository;

    /** @var VoterRepository */
    private $voterRepository;

    /** @var LoggerInterface */
    private $logger;

    /** @var TokenServiceInterface */
    private $tokenService;

    /** @var MailerService */
    private $mailerService;

    /** @var StatsService */
    private $statsService;

    public function __construct(
        CandidateRepository $candidateRepository,
        VoterRepository $voterRepository,
        TokenServiceInterface $tokenService,
        MailerService $mailerService,
        StatsService $statsService
    ) {
        $this->candidateRepository = $candidateRepository;
        $this->voterRepository = $voterRepository;
        $this->tokenService = $tokenService;
        $this->mailerService = $mailerService;
        $this->statsService = $statsService;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Method handling ResetCandidatesCommand commands.
     *
     * The fact that this method handles the ResetCandidatesCommand is signalled by the
     * convention of the method name: `handle<CommandClassName>`.
     * @param ResetCandidatesCommand $command
     */
    public function handleResetCandidatesCommand(ResetCandidatesCommand $command)
    {
        $this->candidateRepository->import($command->getCandidates());
    }

    public function handleDropVotersCommand(DropVotersCommand $command)
    {
        $this->voterRepository->deleteAll();
    }

    public function handleCheckStatusCommand(CheckStatusCommand $command)
    {
        $this->logger->info('status', ['code' => 42]);
    }

    public function handleGenerateTokenCommand(GenerateTokenCommand $command)
    {
        $voter = $this->voterRepository->findOneBy(['email' => $command->getEmail()]);

        if($voter == NULL) {
            $this->logger->error("L'addresse email n'a pas été trouvé chez les votants !");
            return;
        }

        if($voter->hasVoted()) {
            $this->logger->warning("Ce votant a déjà voté !");
            return;
        }

        $token = $this->tokenService->getTokenForVoter($voter);
        $code  = $this->tokenService->getCodeForVoter($voter);

        $this->logger->info($token);
        $this->logger->info($code);

        $this->logger->info("hello world2");
    }

    public function handleSendMailCommand(SendMailCommand $command)
    {
        /** @var Voter[] $voters */
        $voters = $this->voterRepository->findBy(['ballot' => null]);

        $stats = $this->statsService->getStats();

        $nb_mails_to_send = $stats['nb_invites'] - $stats['nb_votants'];

        $this->logger->info("Number of mails to send : {nb_mails_to_send}", ['nb_mails_to_send' => $nb_mails_to_send]);

        $client = SesClient::factory([
            'key'    => '',
            'secret' => '',
            'region' => 'eu-west-1'
        ]);

        $counter = 1;

        foreach($voters as $voter) {
            $info = '> ' . $counter . '/' . $nb_mails_to_send . ' ' . $voter->getFirstname() . ' '. $voter->getLastname() . ' <' . $voter->getEmail() . '>';

            $this->logger->info($info);

            $email = $this->mailerService->getTemplatedEmail($voter, $command->getTemplate());

            if(!$command->isDryRun()) {
                $emailSentId = $client->sendEmail($email);
                $message = 'Message envoyé à ' . $email['Destination']['ToAddresses'][0];
                $this->logger->info($message, ['mail' => $emailSentId->getAll()]);

                usleep(200000);
            } else {
                $this->logger->notice('Mail not sent because we are running in dry-run');
            }

            $counter++;
        }
    }
}