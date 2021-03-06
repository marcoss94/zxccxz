<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;


class FacebookController extends Controller
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook", name="connect_facebook_start")
     */
    public function connectAction(Request $request, ClientRegistry $clientRegistry)
    {
        $this->get('session')->set('redirectBack', $request->server->get('HTTP_REFERER'));
        return $clientRegistry
            ->getClient('facebook_main')// key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'public_profile', 'email' // the scopes you want to access
            ]);
    }

    /**
     * @Route("/facebook_forced", name="facebook_forced")
     */
    public function facebook_forced(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('facebook_main')
            ->redirect([
                'public_profile', 'email'
            ]);
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction()
    {
        if(is_null($this->get('session')->get('redirectBack'))){
            return $this->redirectToRoute('show_confirmed_reserves');
        }
        return $this->redirect($this->get('session')->get('redirectBack'));
    }

    /**
     *
     *
     * @Route("/connect/auth_forced", name="auth_forced")
     */
    public function auth_forced(Request $request)
    {
        $this->get('session')->set('redirectBack', $request->server->get('HTTP_REFERER'));
        return $this->redirectToRoute('login');
    }


}
