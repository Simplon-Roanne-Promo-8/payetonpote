<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Campaign;
use App\Form\PaymentType;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    #[Route('/{id}/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Campaign $campaign, EntityManagerInterface $entityManager): Response
    {

        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $payment->getParticipant()->setCampaign($campaign);
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute(
                'app_campaign_show',
                [
                    'id' => $campaign->getId()
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
            'campaign' => $campaign
        ]);
    }
}
