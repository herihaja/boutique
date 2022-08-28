<?php 

namespace App\Service;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mailer\MailerInterface;

class ProduitService
{
    private EntityManager $entityManager;

    public function __construct(
        private ManagerRegistry $doctrine,
        private MailerInterface $mailer,
    ) {
        $this->entityManager = $doctrine->getManager();
    } 


    public function sendMailToUser($user, $template, $subject, $parameters){
        $mailContents = $this->renderView(
            "emails/".$template.".html.twig", 
            $parameters
        );
        
        $email = (new Email())
            ->from('contact@herihaja.com')
            ->to($user->getEmail())
            ->subject($subject)
            ->html($mailContents);

        $this->mailer->send($email);

    }
}