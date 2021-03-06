<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 23/01/2018
 * Time: 12:35
 */

namespace Labs\ApiBundle\EventSubscriber\Kernel;


use Labs\ApiBundle\ApiEvents;
use Labs\ApiBundle\Entity\Notification;
use Labs\ApiBundle\Entity\Product;
use Labs\ApiBundle\Event\StockEvent;
use Labs\ApiBundle\Manager\NotificationManager;
use Labs\ApiBundle\Manager\ProductManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * Class StockSubscriber
 * @package Labs\ApiBundle\EventListener
 */
class StockSubscriber implements EventSubscriberInterface
{


    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var ProductManager
     */
    private $productManager;


    public function __construct(RegistryInterface $registry, NotificationManager $notificationManager, ProductManager $productManager , TokenStorageInterface $tokenStorage)
    {
        $this->registry = $registry;
        $this->notificationManager = $notificationManager;
        $this->tokenStorage = $tokenStorage;
        $this->productManager = $productManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            ApiEvents::API_SEND_NOTIFICATION_STOCK_ALERT => 'sendNotificationStockAlert'
        ];
    }

    /**
     * @param StockEvent $event
     */
    public function sendNotificationStockAlert(StockEvent $event)
    {
        $request  = $event->getRequest();
        $stock    = $event->getStock();
        $user     = $this->tokenStorage->getToken()->getUser();
        $notification = $this->createNotif($request);
        $this->autoPatchStatusProduct($request);
        $this->notificationManager->create($notification, $user);
    }

    /**
     * @param $request
     * @return Notification
     */
    private function createNotif(Request $request)
    {
        $notification = new Notification();
        $product = $request->get('product');
        $subject = sprintf('Alerte de stock sur votre article: %s de SKU: %s', $product->getName(), $product->getSku());
        $content = sprintf("Le Stock de l'article %s de SKU : %s doit être réapprovisionné", $product->getName(), $product->getSku());
         $notification
            ->setType('Notification')
            ->setSubject($subject)
            ->setContent($content)
            ->setOrigin('Stock')
            ->setActor('Systeme');
        return $notification;
    }

    /**
     * @param Request $request
     * @return Product
     */
    private function autoPatchStatusProduct(Request $request)
    {
        $products = $request->get('product');
        $product = $this->registry
            ->getRepository(Product::class)
            ->find($products->getId());
        if ($product !== null){
            return $this->productManager->patchProductStatus($product, 'status', false);
        }
    }
}