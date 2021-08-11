<?php

namespace App\EventListener;

use App\Entity\LikeNotification;
use App\Entity\Recipe;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\PersistentCollection;

class LikeNotificationSubscriber implements EventSubscriber
{
    //the responsibility of this class is to tell doctrine
    // what event it want's to be subscribe
    public function getSubscribedEvents() //return an array of events
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        //getScheduledCollectionUpdates() is a list of all the persistant collection object

        /** @var PersistentCollection $collectionUpdate */
        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate) {
            if (!$collectionUpdate->getOwner() instanceof Recipe) {
                continue;
            }
            if ('likedBy' !== $collectionUpdate->getMapping()['fieldName']) {
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff();

            if (!count($insertDiff)) {
                return;
            }

            /** @var Recipe $recipe */
            $recipe = $collectionUpdate->getOwner();

            $notification = new LikeNotification();
            $notification->setUser($recipe->getUser());
            $notification->setRecipe($recipe);
            $notification->setLikedBy(reset($insertDiff));

            try {
                $em->persist($notification);
            } catch (ORMException $e) {
            }

            $uow->computeChangeSet(
                $em->getClassMetadata(LikeNotification::class),
                $notification
            );
        }
    }
}
