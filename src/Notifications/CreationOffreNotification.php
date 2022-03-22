<?php 

namespace App\Notifications;

//claase necessaire a l'envoi de mail et à twig
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreationOffreNotification {

/**
 * Proprieté du module envoi-mail
 * @var \Swift_Mailer
 */
    private $mailer;

    /**
 * Proprieté du module envoi-mail
 * @var Environment
 */
private $renderer;


public function __construct(\Swift_Mailer $mailer, Environment $renderer){
    $this->mailer = $mailer;
    $this->renderer = $renderer;
}

/**
 * Methide pour envoyer notre mail
 * @return void
 */

 public function notify()  {

//construire le mail

$mssage = (new Swift_Message('Bonjour,Cher client ceci est pour vous informer que une nouvelle offre est disponible dans notre site '))
//expediteur
->setFrom('celestialservice489@gmail.com')
//destinataire
->setTo('mariembenmassoud123@gmail.com')
//corps msg
->setBody(
$this->renderer->render(
    'contact/creation_offre.html.twig'
),
'text/html'
);

//envyer mail
$this->mailer->send($mssage);
 }   

}

?>