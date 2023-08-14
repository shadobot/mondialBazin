<?php

namespace App\Controller;

use App\Entity\User;
use App\Classe\Mail;
use App\Form\RegistreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


// A ajouter pour faire fonctionner getDoctrine
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{

    private $entityManager;

    function __construct(EntityManagerInterface $entityManager){
        $this -> entityManager = $entityManager; 
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {

        $notification = null;
        $user = new User();
        $form = $this -> createForm(RegistreType::class, $user);

        $form -> handleRequest($request);
        if($form -> isSubmitted() && $form -> isValid()){
            $user = $form -> getData();

            $search_email = $this -> entityManager -> getRepository(User::class) -> findOneByEmail($user -> getEmail());

            if(!$search_email) {

                $password = $hasher -> hashPassword($user, $user -> getPassword());
                $user -> setPassword($password);
    
                $this -> entityManager -> persist($user);
                $this -> entityManager -> flush();
                $email = new Mail();
                $content = "Bonjour ".$user -> getPrenom()."<br> Merci pour votre commande. <br>
                Toute l'équipe de Mondial Bazin est ravie de vous accueillir parmi nos clients privilégiés ! <br>
                Nous sommes heureux de vous compter parmi notre communauté passionnée de vêtements de style africain traditionnel, spécialement les célèbres bazins.<br><br>
                En vous inscrivant sur notre site, vous avez désormais accès à une large sélection de tenues colorées et élégantes qui célèbrent la richesse de la culture africaine. Que vous recherchiez des tenues pour des occasions spéciales ou pour votre usage quotidien, nous avons soigneusement choisi chaque pièce pour vous offrir le meilleur du bazin.
                <br><br> Nous espérons que vous apprécierez naviguer sur notre site et que vous trouverez les tenues qui vous ressemblent le plus. Chez Mondial Bazin, nous célébrons la diversité et l'élégance de la mode africaine.
                <br><br> Si vous avez des questions, n'hésitez pas à nous contacter à l'adresse suivante : support@mondialbazin.com";

                $email -> send($user -> getEmail(), $user -> getPrenom(), 'Bienvenu sur Mondial bazin', $content);
                $notification = "Votre inscription s'est correctement déroulé. Vous pouvez dés à présent vous connecter à votre compte.";
            } else {

                $notification = "L'email que vous avez renseigné existe déjà"; 
            }


        }
        return $this -> render('register/index.html.twig',[
            'form' => $form -> createView(),
            'notification' => $notification
        ]);
    }
}
