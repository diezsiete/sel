<?php

namespace App\Command\Migration;

use App\Entity\Usuario;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MigrationUsuarioCommand extends MigrationCommand
{
    protected static $defaultName = 'migration:usuario';
    /**
     * @var ManagerRegistry
     */
    private $doctrine;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;


    public function __construct(ManagerRegistry $doctrine, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($doctrine);
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Migracion de usuarios');
        $this->addOption('rol', null, InputOption::VALUE_OPTIONAL,
            'Importar usuarios solo de un rol especifico');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = "SELECT * FROM `usuario` "
             . "WHERE (ultimo_login IS NOT NULL OR DATE_FORMAT(creacion, \"%Y\") >= 2018) ";

        if($rol = $input->getOption('rol')) {
            $sql .= "AND roles LIKE '%\"$rol\"%' ";
        }

        $sql = $this->addLimitToSql($sql);
        $this->initProgressBar($this->countSql($sql));

        while ($row = $this->fetch($sql)) {
            $roles = $this->migrateRoles($row['roles']);

            $creacion = \DateTime::createFromFormat('Y-m-d H:i:s', $row['creacion']);
            $pss = $row['pss'];

            $usuario = new Usuario();
            $usuario
                ->setIdentificacion($row['ident'])
                ->setPrimerNombre($row['primer_nombre'])
                ->setSegundoNombre($row['segundo_nombre'])
                ->setPrimerApellido($row['primer_apellido'])
                ->setSegundoApellido($row['segundo_apellido'])
                ->setEmail($row['email'])
                ->setCreatedAt($creacion)
                ->setRoles($roles)
                ->aceptarTerminos()
                ->setIdOld($row['id']);

            $type = 1;

            if(!is_numeric($usuario->getIdentificacion())) {
                // usuarios con identificación email truncado restauramos
                $usuario->setIdentificacion($usuario->getEmail());
                if(hash('sha512', $usuario->getIdentificacion()) == $pss) {
                    $type = 2;
                    $pss = $usuario->getIdentificacion();
                }
            } else {
                if(hash('sha512', substr($usuario->getIdentificacion(), -4) ) == $pss) {
                    $type = 2;
                    $pss = substr($usuario->getIdentificacion(), -4);
                }
            }

            if($type === 1) {
                $pss = hash('sha256', $pss);
            }
            $usuario
                ->setPassword($this->passwordEncoder->encodePassword($usuario, $pss))
                ->setType($type);

            $this->selPersist($usuario);
        }
    }

    private function migrateRoles($roles)
    {
        $roles = unserialize($roles);

        $roles = $this->replaceRol($roles, 'ROLE_USUARIO');
        $roles = $this->replaceRol($roles, 'ROLE_VACANTES_ASPIRANTE', 'ROLE_ASPIRANTE');
        $roles = $this->replaceRol($roles, 'ROLE_VACANTES_EDITOR', 'ROLE_VACANTES');
        $roles = $this->replaceRol($roles, 'ROLE_CLIENTES_CLIENTE_REPRESENTANTE', 'ROLE_CLIENTE_REPRESENTANTE');
        $roles = $this->replaceRol($roles, 'ROLE_CLIENTES_EMPRESA_SERVICIO', 'ROLE_EMPRESA_SERVICIO');

        return array_values($roles);
    }

    private function replaceRol($roles, $rolOldName, $rolNewName = null)
    {
        if (($key = array_search($rolOldName, $roles)) !== false) {
            if($rolNewName) {
                $roles[$key] = $rolNewName;
            } else {
                unset($roles[$key]);
            }
        }
        return $roles;
    }

}
