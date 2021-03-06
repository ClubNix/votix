<?php
/**
 * Votix. The advanced and secure online voting platform.
 *
 * @author Club*Nix <club.nix@edu.esiee.fr>
 *
 * @license MIT
 */
namespace App\Controller;

use App\Form\KeyCheckType;
use App\Form\VoteCountingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Voter;
use App\Entity\Candidate;

/**
 * Class DemoController
 */
class DemoController extends AbstractController
{
    /**
     * @Route("/{_locale}/demo", name="demo")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('default/demo.html.twig');
    }

    /**
     * @Route("/{_locale}/demo/vote-bad-timing", name="demo-vote-bad-timing")
     *
     * @return Response
     */
    public function voteBadTiming(): Response
    {
        return $this->render('default/bad-timing.html.twig');
    }

    /**
     * @Route("/{_locale}/demo/already-voted", name="demo-already-voted")
     *
     * @param string $votixSupport
     *
     * @return Response
     */
    public function alreadyVoted(string $votixSupport): Response
    {
        $voter = new Voter();
        $voter->setFirstname('Anonymous');
        return $this->render('default/already-voted.html.twig', [
            'voter' => $voter,
            'votix_support_email' => $votixSupport,
        ]);
    }

    /**
     * @Route("/{_locale}/demo/thank-you", name="demo-thank-you")
     *
     * @return Response
     */
    public function thankYou(): Response
    {
        $voter = new Voter();
        return $this->render('default/thank-you.html.twig', ['voter' => $voter]);
    }

    /**
     * @Route("/{_locale}/demo/vote", name="demo-vote")
     *
     * @return Response
     */
    public function vote(): Response
    {
        $voter = new Voter();
        $voter->setFirstname('Anonyme');
        $candidates = $this->getFakeCandidates();

        return $this->render('default/vote.html.twig', [
            'voter'      => $voter,
            'candidates' => $candidates,
            'token'      => '', //TODO
        ]);
    }

    /**
     * @Route("/{_locale}/demo/vote-counting", name="demo-vote-counting")
     *
     * @return Response
     */
    public function voteCounting(): Response
    {
        $form = $this->createForm(VoteCountingType::class, [
            'action' => $this->generateUrl('demo'),
        ]);
        return $this->render('default/no-stress.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{_locale}/demo/results", name="demo-results")
     *
     * @return Response
     */
    public function voteResults(): Response
    {
        $voter = new Voter();
        $voter->setFirstname('Anonyme');
        [$fakeCandidate1, $fakeCandidate2, $fakeCandidate3] = $this->getFakeCandidates();

        $results = [
            $fakeCandidate1->getId() => ['candidate' => $fakeCandidate1, 'count' => 0],
            $fakeCandidate2->getId() => ['candidate' => $fakeCandidate2, 'count' => 0],
            $fakeCandidate3->getId() => ['candidate' => $fakeCandidate3, 'count' => 0],
        ];

        $stats = [
            'nb_votants' => 42,
            'nb_invites' => 12,
            'ratio_float' => 1,
        ];

        $hash = 'AAAA';

        return $this->render('default/results.html.twig', [
            'message' => '',
            'results' => $results,
            'stats'   => $stats,
            'hash'    => $hash,
        ]);
    }

    /**
     * @Route("/{_locale}/demo/arm", name="demo-arm")
     *
     * @return Response
     */
    public function arm(): Response
    {
        return $this->render('default/arm.html.twig', ['armed' => false]);
    }

    /**
     * @Route("/{_locale}/demo/check", name="demo-check")
     *
     * @return Response
     */
    public function check(): Response
    {
        $form = $this->createForm(KeyCheckType::class, [
            'action' => $this->generateUrl('demo'),
        ]);
        return $this->render('default/check.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{_locale}/demo/voters-list", name="demo-voters-list")
     *
     * @return Response
     */
    public function votersList(): Response
    {
        $voters = [];

        return $this->render('default/admin-voters-list.html.twig', [
            'voters' => $voters,
        ]);
    }

    /**
     * @Route("/{_locale}/demo/verify-results-hash", name="demo-verify-results-hash")
     *
     * @return Response
     */
    public function verifyResultsHash(): Response
    {
        $candidates = $this->getFakeCandidates();

        return $this->render('default/admin-verify-results-hash.html.twig', [
            'candidates' => $candidates,
            'isChecksumValid' => null,
        ]);
    }

    private function getFakeCandidates(): array
    {
        $fakeCandidate1 = new Candidate();
        $fakeCandidate1->setId(1);
        $fakeCandidate1->setName('Candidate A');
        $fakeCandidate2 = new Candidate();
        $fakeCandidate2->setId(2);
        $fakeCandidate2->setName('Candidate B');
        $fakeCandidate3 = new Candidate();
        $fakeCandidate2->setId(3);
        $fakeCandidate3->setName('Candidate C');

        return [$fakeCandidate1, $fakeCandidate2, $fakeCandidate3];
    }
}
