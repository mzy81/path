<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProductFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName("Flying Carpet (made by scientists from Sivas)");
        $manager->persist($product);

        $manager->flush();
    }
}
