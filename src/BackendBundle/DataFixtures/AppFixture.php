<?php

namespace App\BackendBundle\DataFixtures;

use App\BackendBundle\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /** Generowanie listy produktów */
        for($i=1;$i<=20;$i++)
        {
            $item=new Item();
            $item
                ->setName("Produkt ".$i)
                ->setAmount(rand(0,20));
            $manager->persist($item);
        }

        $manager->flush();
    }
}
