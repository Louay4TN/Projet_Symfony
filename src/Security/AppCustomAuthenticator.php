<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppCustomAuthenticator extends AbstractLoginFormAuthenticator
{
    // use TargetPathTrait;

    // public const LOGIN_ROUTE = 'app_login';

    // public function __construct(private UrlGeneratorInterface $urlGenerator, private Security $security)
    // {
    // }

    // public function authenticate(Request $request): Passport
    // {
    //     $username = $request->request->get('username', '');

    //     $request->getSession()->set(Security::LAST_USERNAME, $username);

    //     return new Passport(
    //         new UserBadge($username),
    //         new PasswordCredentials($request->request->get('password', '')),
    //         [
    //             new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
    //             new RememberMeBadge(),
    //         ]
    //     );
    // }

    // public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    // {
    //     if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
    //         return new RedirectResponse($targetPath);
    //     }
    //     if($this->security->isGranted("ROLE_ADMIN")){
    //         return new RedirectResponse($this->urlGenerator->generate('app_voiture_index'));
    //     }
    //     else if("ROLE_USER"){
    //         return new RedirectResponse($this->urlGenerator->generate('app_default'));
    //     }
    //     // For example:
        
    //     // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    // }

    // protected function getLoginUrl(Request $request): string
    // {
    //     return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    // }
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private Security $security;

    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // // Vérifie si une URL cible existe (utilisée dans certaines redirections après login)
        // if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        //     return new RedirectResponse($targetPath);
        // }

        // // Redirection basée sur les rôles
        // if ($this->security->isGranted("ROLE_ADMIN")) {
        //     // Si l'utilisateur a le rôle ADMIN, redirige vers la gestion des voitures
        //     return new RedirectResponse($this->urlGenerator->generate('app_voiture_index'));
        // }

        // if ($this->security->isGranted("ROLE_USER")) {
        //     // Si l'utilisateur a le rôle USER, redirige vers la page principale
        //     return new RedirectResponse($this->urlGenerator->generate('app_default'));
        // }

        // // Redirection par défaut si aucun rôle spécifique n'est détecté
        // return new RedirectResponse($this->urlGenerator->generate('app_default'));
        if ($this->security->isGranted("ROLE_ADMIN")) {
            return new RedirectResponse($this->urlGenerator->generate('app_Voiture_index')); // Remplacez par la route correcte
        }
    
        if ($this->security->isGranted("ROLE_USER")) {
            return new RedirectResponse($this->urlGenerator->generate('app_default')); // Remplacez par la route correcte
        }
    
        return new RedirectResponse($this->urlGenerator->generate('app_default'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
