<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Events;
use App\Form\CommentType;
use App\Repository\CarroRepository;
use App\Repository\CasaRepository;
use App\Repository\ComentarioRepository;
use App\Repository\ExcursionRepository;
use App\Repository\PaqueteRepository;
use App\Repository\PostRepository;
use App\Repository\ReservaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Controller used to manage blog contents in the public part of the site.
 *
 *
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class BlogController extends AbstractController
{


    /**
     * @Route("/homepage",  name="blog_index")
     *
     */
    public function index(Request $request, CarroRepository $carroRepository, CasaRepository $casaRepository, ExcursionRepository $excursionRepository, PaqueteRepository $paqueteRepository, ComentarioRepository $comentarioRepository, ReservaRepository $reservaRepository)
    {
        if (!$this->get('session')->get('language')) {
            $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $b = true;
            foreach ($langs as $l) {
                $head = substr($l, 0, 2);
                if ($head == 'es') {
                    $this->get('session')->set('language', 'es');
                    $b = false;
                    break;
                } elseif ($head[0] == 'en') {
                    $this->get('session')->set('language', 'en');
                    $b = false;
                    break;
                }
            }
            if ($b) $this->get('session')->set('language', 'en');
        }
        $carros = $carroRepository->findBy(['active' => true], ['valoracion' => 'DESC'], 12);
        $casas = $casaRepository->findBy(['active' => true], ['valoracion' => 'DESC'], 12);
        $excursiones = $excursionRepository->findBy(['active' => true], ['valoracion' => 'DESC'], 12);
        $paquetes = $paqueteRepository->findBy(['active' => true], ['valoracion' => 'DESC'], 12);
        if ($request->get('redirectId')) {
            $this->get('session')->set('redirectedBy', $request->get('redirectId'));
        }
        $comments = $comentarioRepository->findBy(['type' => 'sitio', 'revisado' => true], ['publishedAt' => 'ASC'], 6);
        $message = $request->get('message');
        //--------------Reservas-----------------------------------//
        $cart = false;
        $reservasActivas = false;
        if (!is_null($this->getUser())) {
            $user = $this->getUser();
            $cart = $reservaRepository->findOneBy(['usuario' => $user, 'status' => 'pending']) ? true : false;
            $reservasActivas = $reservaRepository->findOneBy(['usuario' => $user, 'status' => 'payed']) ? true : false;
        }
        $this->get('session')->set('cart', $cart);
        $this->get('session')->set('reserves', $reservasActivas);
        //------------------------------------------------------------------------------
        return $this->render('blog/index.html.twig', [
            'base' => 'true',
            'carros' => $carros,
            'casas' => $casas,
            'excursiones' => $excursiones,
            'paquetes' => $paquetes,
            'comments' => $comments,
            'message' => $message,
            'cart' => $cart,
            'reserves' => $reservasActivas
        ]);
    }


    /**
     * @Route("/blog_languaje", name="change_language")
     * @Method("GET")
     */
    public function changeLanguaje(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $language = ($this->get('session')->get('language') == 'es') ? 'en' : 'es';
        $this->get('session')->set('language', $language);
        if ($this->getUser()) {
            $user = $this->getUser();
            $user->setIdioma($language);
            $em->persist($user);
            $em->flush();
        }
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * @Route("/posts/{slug}", name="blog_post")
     * @Method("GET")
     *
     * NOTE: The $post controller argument is automatically injected by Symfony
     * after performing a database query looking for a Post with the 'slug'
     * value given in the route.
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     */
    public function postShow(Post $post): Response
    {
        // Symfony's 'dump()' function is an improved version of PHP's 'var_dump()' but
        // it's not available in the 'prod' environment to prevent leaking sensitive information.
        // It can be used both in PHP files and Twig templates, but it requires to
        // have enabled the DebugBundle. Uncomment the following line to see it in action:
        //
        // dump($post, $this->getUser(), new \DateTime());

        return $this->render('blog/post_show.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/comment/{postSlug}/new", name="comment_new")
     * @Method("POST")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @ParamConverter("post", options={"mapping": {"postSlug": "slug"}})
     *
     * NOTE: The ParamConverter mapping is required because the route parameter
     * (postSlug) doesn't match any of the Doctrine entity properties (slug).
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrine-converter
     */
    public function commentNew(Request $request, Post $post, EventDispatcherInterface $eventDispatcher): Response
    {
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            // When triggering an event, you can optionally pass some information.
            // For simple applications, use the GenericEvent object provided by Symfony
            // to pass some PHP variables. For more complex applications, define your
            // own event object classes.
            // See https://symfony.com/doc/current/components/event_dispatcher/generic_event.html
            $event = new GenericEvent($comment);

            // When an event is dispatched, Symfony notifies it to all the listeners
            // and subscribers registered to it. Listeners can modify the information
            // passed in the event and they can even modify the execution flow, so
            // there's no guarantee that the rest of this controller will be executed.
            // See https://symfony.com/doc/current/components/event_dispatcher.html
            $eventDispatcher->dispatch(Events::COMMENT_CREATED, $event);

            return $this->redirectToRoute('blog_post', ['slug' => $post->getSlug()]);
        }

        return $this->render('blog/comment_form_error.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * This controller is called directly via the render() function in the
     * blog/post_show.html.twig template. That's why it's not needed to define
     * a route name for it.
     *
     * The "id" of the Post is passed in and then turned into a Post object
     * automatically by the ParamConverter.
     */
    public function commentForm(Post $post): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('blog/_comment_form.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="blog_search")
     * @Method("GET")
     */
    public function search(Request $request, PostRepository $posts): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->render('blog/search.html.twig');
        }

        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 10);
        $foundPosts = $posts->findBySearchQuery($query, $limit);
        $results = [];
        foreach ($foundPosts as $post) {
            $results[] = [
                'title' => htmlspecialchars($post->getTitle(), ENT_COMPAT | ENT_HTML5),
                'date' => $post->getPublishedAt()->format('M d, Y'),
                'author' => htmlspecialchars($post->getAuthor()->getFullName(), ENT_COMPAT | ENT_HTML5),
                'summary' => htmlspecialchars($post->getSummary(), ENT_COMPAT | ENT_HTML5),
                'url' => $this->generateUrl('blog_post', ['slug' => $post->getSlug()]),
            ];
        }

        return $this->json($results);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        return $this->render('blog/index.html');
    }

    /**
     * @Route("/bautcher", name="bautcher")
     */
    public function bautcher()
    {
        return $this->render('blog/bautcher.html.twig');
    }

//    -------------------------------------------------------------------------------------

    /**
     * @Route("/politicas", name="politicas")
     */
    public function politicas()
    {
        return $this->render('blog/politicas.html.twig');
    }

    /**
     * @Route("/reservaCancelacion", name="reservaCancelacion")
     */
    public function reservaCancelacion()
    {
        return $this->render('blog/reservaCancelacion.html.twig');
    }

    /**
     * @Route("/preguntasFrecuentes", name="preguntasFrecuentes")
     */
    public function preguntasFrecuentes()
    {
        return $this->render('blog/preguntasFrecuentes.html.twig');
    }

    /**
     * @Route("/quienesSomos", name="quienesSomos")
     */
    public function quienesSomos()
    {
        return $this->render('blog/quienesSomos.html.twig');
    }

    /**
     * @Route("/galeria", name="galeria")
     */
    public function galeria()
    {
        return $this->render('blog/galeria.html.twig');
    }




//    -------------------------------------------------------------------------------------

}
