<?php
namespace App\Event;

use Swift_Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class UserSubscriber  implements EventSubscriberInterface
{

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Swift_Mailer $mailer,Environment $twig)
    {
        $this->mailer=$mailer;
        $this->twig=$twig;
    }


    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister',
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {

        $body=$this->twig->render('email/registration.html.twig',[
            'user'=>$event->getRegisteredUser()
        ]);
        $message=(new \Swift_Message())
            ->setSubject('Marhaba to Hamhama')
            ->setFrom('Hamhama@Hamahama.com')
            ->setTo($event->getRegisteredUser()->getEmail())
            ->setBody($body,'text/html');
        $this->mailer->send($message);
    }
}