<?php

namespace App\BackendBundle\DataFixtures;

use App\BackendBundle\Entity\ApiKey;
use App\BackendBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        /** Generowanie użytkownika */
        $user = new User();
        $password = $this->encoder->encodePassword($user, 'secret');
        $user->setPassword($password)
            ->setEmail('cloud@cloudservices.pl')
            ->setRoles(["ROLE_USER", "ROLE_ADMIN","ROLE_API"])
        ;

        $manager->persist($user);

        /** Generowanie kluczów do API */

        $valid_key=new ApiKey();
        $valid_key
            ->setCode(md5('cloud_services'))
            ->setValid(true)
            ->setUser($user)
        ;

        $invalid_key=new ApiKey();
        $invalid_key
            ->setCode(md5('nie_cloud_services'))
            ->setValid(false)
            ->setUser($user)
        ;

        $manager->persist($valid_key);
        $manager->persist($invalid_key);

        $manager->flush();
    }
}
