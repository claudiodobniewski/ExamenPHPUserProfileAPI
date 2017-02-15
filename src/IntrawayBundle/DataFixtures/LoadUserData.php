<?php
// src/Intraway\DataFixtures\ORM/LoadUserData.php

namespace Intraway\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use IntrawayBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Jorge Perez');
        $user->setEmail('jorge.perez73@hotmail.com');

        $manager->persist($user);
        
        $user = new User();
        $user->setName('Ana Gutierrez');
        $user->setEmail('anagutierrez@gmail.com');
        
        $manager->persist($user);
        
        $user = new User();
        $user->setName('Martin Larrainzar');
        $user->setEmail('mlarrainzar@cubeform.com.ar');
        
        $manager->persist($user);
        
        $manager->flush();
    }
}