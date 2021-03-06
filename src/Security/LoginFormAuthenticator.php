<?php

namespace App\Security;

use App\Entity\Main\Usuario;
use App\Service\Component\LoadingOverlayComponent;
use App\Service\Napi\EmpleadoService;
use App\Service\Novasoft\Api\EmpleadoService as NapiEmpleadoService;
use App\Service\ServicioEmpleados\Report\ReportCacheHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    protected $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    /**
     * @var NapiEmpleadoService
     */
    private $napiEmpleadoService;
    /**
     * @var EmpleadoService
     */
    private $empleadoService;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator,
                                CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder,
                                EmpleadoService $empleadoService)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->empleadoService = $empleadoService;
    }

    public function supports(Request $request)
    {
        $supports = 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
        return $supports;
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'identificacion' => $request->request->get('identificacion'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['identificacion']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(Usuario::class)->findOneBy(['identificacion' => $credentials['identificacion']]);

        if (!$user) {
            $empleado = $this->empleadoService->updateFromNapi($credentials['identificacion']);
            if ($empleado) {
                $user = $empleado->getUsuario();
            } else {
                // fail authentication with a custom error
                throw new CustomUserMessageAuthenticationException('Identificacion no encontrada.');
            }
        }

        return $user;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface|Usuario $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['password'];
        if($user->getType() === 1) {
            $password = hash('sha256', hash('sha512', $password));
        }
        $isValid = $this->passwordEncoder->isPasswordValid($user, $password);

        if($isValid) {
            if($user->getType() === 1) {
                $this->updatePasswordToNewHash($user, $credentials['password']);
            }
            $this->empleadoService->ifEmpleadoAddRol($user);
        }

        return $isValid;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);

        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('sel_panel'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }

    private function updatePasswordToNewHash(Usuario $usuario, $plainPassword)
    {
        $password = $this->passwordEncoder->encodePassword($usuario, $plainPassword);
        $usuario
            ->setPassword($password)
            ->setType(2);
        $this->entityManager->flush();
    }
}
