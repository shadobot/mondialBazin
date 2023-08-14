<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
    }
    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index(Cart  $cart, $stripeSessionId): Response
    {
        $order = $this -> entityManager -> getRepository(Order::class) -> findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order -> getUser() != $this -> getUser()) {
            return $this -> redirectToRoute('home');
        }

        if($order -> getState() == 0) {
            // Vider le panier de l'utilisateur
            $cart -> remove();

            $order -> setState(1);
            $this -> entityManager -> flush();

            $email = new Mail();
                $content = "Bonjour ".$order -> getUser() -> getPrenom()."<br> Merci pour votre commande. <br> Merci d'avoir choisi Mondial Bazin pour vos achats de vêtements traditionnels africains.<br><br>À bientôt !<br><br> L'équipe Mondial Bazin";

                $email -> send($order -> getUser() -> getEmail(), $order -> getUser() -> getPrenom(), 'Votre commande Mondial Bazin est bien validé', $content);
        }
        //dd($order);
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
