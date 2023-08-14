<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class EasyAdminSubscriber implements EventSubscriberInterface {
    
    private $appKernel;
    public function __construct(KernelInterface $appKernel) {
        $this -> appKernel = $appKernel;
    }




    public static function getSubscribedEvents(): array {
        return [
            BeforeEntityPersistedEvent::class => ['setIllustration'],
            BeforeEntityUpdatedEvent::class => ['updateIllustration']
        ];
    }

    public function uploadIllustration($event) {
        $entity    = $event  -> getEntityInstance();
        $tmp_name  = $_FILES['Product']['name']['illustration']['file'];

        $extention = pathinfo($_FILES['Product']['name']['illustration']['file'], PATHINFO_EXTENSION);
        $filename  = uniqid().'.'.$extention;
        //dd($tmp_name);
        $project_dir = $this -> appKernel -> getprojectDir();
        rename($project_dir.'/public/uploads/'.$tmp_name, $project_dir.'/public/uploads/'.$filename);
        $entity->setIllustration($filename);
    }

    public function updateIllustration(BeforeEntityUpdatedEvent $event) {
        if(!($event -> getEntityInstance() instanceof Product)) {
            return;
        }
        if($_FILES['Product']['name']['illustration']['file'] != ''){
            $this -> uploadIllustration($event);
        }
    }

    public function setIllustration(BeforeEntityPersistedEvent $event) {
        if(!($event -> getEntityInstance() instanceof Product)) {
            return;
        }
        $this -> uploadIllustration($event);

        // dd($_FILES);
        // $extention = pathinfo($_FILES['Product']['name']['illustration']['file'], PATHINFO_EXTENSION);
        // move_uploaded_file($tmp_name, $project_dir.'/public/uploads/'.$filename.'.'.$extention);
        // $entity -> setIllustration($filename.'.'.$extention);
    }

}
