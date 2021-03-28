<?php

namespace App\Controller;

use App\Entity\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/list", name="list")
     */
    public function index(): Response
    {
        $email = new Email();
        $email->setFromEmail('from');
        $email->setFromName('setFromName');
        $email->setHtmlPart('setHtmlPart');
        $email->setTextPart('setTextPart');
        $email->setRecipients('setRecipients');
        $email->setStatus('setStatus');
        $email->setSubject('setSubject');

        $this->entityManager->persist($email);
        $this->entityManager->flush();


        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ListController.php',
        ]);
    }
}
