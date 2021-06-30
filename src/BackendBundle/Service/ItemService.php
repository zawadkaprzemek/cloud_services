<?php


namespace App\BackendBundle\Service;

use App\BackendBundle\Entity\Item;
use App\BackendBundle\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{

    /**
     * @var ItemRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ItemRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }


    public function getProducts(string $amountFilter="all")
    {
        return $this->repository->getProducts($amountFilter);
    }

    public function getItem(int $id): ?Item
    {
        if($id===0)
        {
            $item=new Item();
        }else{
            $item=$this->repository->find($id);
        }
        return $item;
    }

    public function deleteItem(Item $item): array
    {
        $result=['status'=>''];
        try{
            $this->entityManager->remove($item);
            $this->entityManager->flush();
            $result['status']='success';
        }catch (\Exception $e)
        {
            $result['status']='error';
            $result['message']=$e->getMessage();
        }
        return $result;
    }


}