<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        
        $user = new User();
        $user->setUsername("mehmet");
        $password = $this->encoder->encodePassword($user, 'i_hope_this_position_is_remote');
        $user->setPassword($password);
        $manager->persist($user);
        
        $user = new User();
        $user->setUsername("mehmet_the_bored");
        $password = $this->encoder->encodePassword($user, 'because_i_invested_some_time');
        $user->setPassword($password);
        $manager->persist($user);

        $user = new User();
        $user->setUsername("mehmet_the_sad");
        $password = $this->encoder->encodePassword($user, 'but_i_aint_got_a_real_answer_about_the_remote');
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();
    }
}
