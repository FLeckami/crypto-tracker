<?php

namespace App\Controller;

use App\Entity\Crypto;
use App\Service\CryptoConversionManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CryptoTrackerController extends AbstractController
{

    #[Route('/', name: 'app_crypto_tracker')]
    public function index(ManagerRegistry $doctrine, CryptoConversionManager $conv): Response
    {
        $crypto_rep = $doctrine->getRepository(Crypto::class);
        $crypto_list = $crypto_rep->findAll();
        $crypto_list = array_reverse($crypto_list);

        $sum_eur = 0;
        if (count($crypto_list) != 0) {
            $sum_btc = $crypto_rep->sumOfCrypto('Bitcoin');
            $sum_eth = $crypto_rep->sumOfCrypto('Ethereum');
            $sum_xrp = $crypto_rep->sumOfCrypto('Ripple');

            $btc_eur = 0;
            $eth_eur = 0;
            $xrp_eur = 0;
            if (!is_null($sum_btc[1]))
                $btc_eur = $conv->cryptoToEuro($sum_btc[1], 'BTC');
            if (!is_null($sum_eth[1]))
                $eth_eur = $conv->cryptoToEuro($sum_eth[1], 'ETH');
            if (!is_null($sum_xrp[1]))
                $xrp_eur = $conv->cryptoToEuro($sum_xrp[1], 'XRP');

            $sum_eur = $btc_eur + $eth_eur + $xrp_eur;
        }
        
        return $this->render('crypto_tracker/index.html.twig', [
            'total_amount' => $sum_eur,
            'crypto_list' => $crypto_list
        ]);
    }
    
    #[Route('/graph')]
    public function graph(): Response
    {
        return $this->render('crypto_tracker/graph.html.twig', [
            'controller_name' => 'CryptoTrackerController',
        ]);
    }
    
    #[Route('/add')]
    public function add(ManagerRegistry $getDoctrine,Request $request)
    {
        $form = $this->createFormBuilder()
        ->add('crypto', ChoiceType::class, [
            'label' => "Sélectionnez la cryptomonnie: ",
            'choices' => [
                'BTC' => "Bitcoin",
                'ETC' => "Ethereum",
                'XRP' => "Ripple"
            ]
        ])
        ->add('qty', NumberType::class, ['label' => 'Quantité: '])
        ->add('buying_price', NumberType::class, ['label' => 'Prix d\'achat: '])
        ->add('submit', SubmitType::class, ['label' => 'Ajouter'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $crypto = new Crypto();

            $crypto->setCryptoName($data['crypto']);
            $crypto->setCryptoQty($data['qty']);
            $crypto->setBuyingPrice($data['buying_price']);

            $entityManager = $getDoctrine->getManager();

            $entityManager->persist($crypto);
            $entityManager->flush();

            return $this->redirectToRoute('app_crypto_tracker');
        }

        return $this->render('crypto_tracker/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/edit')]
    public function edit(): Response
    {
        $form = $this->createFormBuilder()
        ->add('crypto', ChoiceType::class, [
            'label' => "Sélectionnez la cryptomonnaie",
            'choices' => [
                'BTC' => "Bitcoin",
                'ETC' => "Ethereum",
                'XRP' => "Ripple"
            ]
        ])
        ->add('montant', NumberType::class, ['label' => "Montant"])
        ->add('submit', SubmitType::class, ['label' => 'Valider'])
        ->getForm();

        return $this->render('crypto_tracker/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
