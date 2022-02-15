<?php 

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Routing\RouterInterface;
use League\OAuth2\Client\Provider\Github;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use  League\OAuth2\Client\Provider\GithubResourceOwner;

class GithubAuthenticator extends SocialAuthenticator
{

    use TargetPathTrait;

    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, UserRepository $userRepo, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->userRepo = $userRepo;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'oauth_check' && $request->get('service') === 'github';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->clientRegistry->getClient('github'));
    }


    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $client = $this->clientRegistry->getClient('github')->fetchUserFromToken($credentials);

        $response = HttpClient::create()->request(
            'GET',
            'https://api.github.com/user/emails',
            [
                'headers'=> [
                    'authorization' => "token {$credentials->getToken()}"
                ]
            ]
        );
        $emails = json_decode($response->getContent(), true);
        
        foreach($emails as $email)
        {
            if($email['primary']===true && $email['verified']===true)
            {
                $data = $client->toArray();
                $data['email'] = $email['email'];
                $client = new GithubResourceOwner($data);
            }
        }

        return $this->userRepo->findOrCreateFromOauth($client);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getTargetPath($request->getSession(),$providerKey);
        return new RedirectResponse($targetPath ?: '/finishReg');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }

}


?>