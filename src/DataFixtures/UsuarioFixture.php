<?php

namespace App\DataFixtures;

use App\Entity\Main\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsuarioFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    protected function loadData(ObjectManager $manager)
    {
        return;
        $this->createMany(2, 'main_users', function ($i) use ($manager){
            $user = new Usuario();
            $user
                ->setIdentificacion($i + 100)
                ->setEmail(sprintf('usuario%d@sel.com.co', $i))
                ->setPrimerNombre($this->faker->firstName)
                ->setPrimerApellido($this->faker->lastName)
                ->aceptarTerminos()
                ->setIdOld(8);

            $user->setPassword($this->passwordEncoder->encodePassword($user,'coco'));

            return $user;
        });

        $this->createMany(2, 'admin_users', function ($i) {
            $user = new Usuario();
            $user
                ->setIdentificacion($i+200)
                ->setEmail(sprintf('admin%d@sel.com.co', $i))
                ->setPrimerNombre($this->faker->firstName)
                ->setPrimerApellido($this->faker->lastName)
                ->aceptarTerminos();


            $user->setPassword($this->passwordEncoder->encodePassword($user,'coco'));

            $user->setRoles(["ROLE_ADMIN"]);

            return $user;
        });

        $superadmin = new Usuario();
        $superadmin
            ->setIdentificacion(1018410666)
            ->setEmail('guerrerojosedario@gmail.com')
            ->setPrimerNombre("JOSE")
            ->setPrimerApellido("GUERRERO")
            ->setPassword($this->passwordEncoder->encodePassword($superadmin,'coco'))
            ->setRoles(["ROLE_SUPERADMIN"])
            ->aceptarTerminos();

        $manager->persist($superadmin);
        $manager->flush();
    }
}
