<?php
namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{ 
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
{
    $this->encoder = $encoder;

}    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setMailUtilisateur('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->encoder->encodePassword($user,'0000'));  
       // $user->setEmail('no-reply@overseas.media');
        $manager->persist($user);
        $manager->flush();
        $user1 = new User();
        $user1->setMailUtilisateur('fATI');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->encoder->encodePassword($user1,'0000'));  
       // $user->setEmail('no-reply@overseas.media');
        $manager->persist($user1);
        $manager->flush();
        $user2 = new User();
        $user2->setMailUtilisateur('fATDI');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->encoder->encodePassword($user2,'0000'));  
       // $user->setEmail('no-reply@overseas.media');
        $manager->persist($user2);
        $manager->flush();
        $user3 = new User();
        $user3->setMailUtilisateur('f44ATI');
        $user3->setRoles(['ROLE_USER']);
        $user3->setPassword($this->encoder->encodePassword($user3,'0000'));  
       // $user->setEmail('no-reply@overseas.media');
        $manager->persist($user3);
        $manager->flush();
        $user4 = new User();
        $user4->setMailUtilisateur('fATRRI');
        $user4->setRoles(['ROLE_USER']);
        $user4->setPassword($this->encoder->encodePassword($user4,'0000'));  
       // $user->setEmail('no-reply@overseas.media');
        $manager->persist($user4);
        $manager->flush();
    }
}