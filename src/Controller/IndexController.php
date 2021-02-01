<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request): Response
    {
        return new Response('<html><body>huipizdadzigurda</body></html>');
    }

    /**
     * @Route("/template", name="template")
     * @throws \Exception
     */
    public function templateAction(Request $request): Response
    {
        $number = random_int(0, 100);

        return $this->render('test/test.html.twig', [
            'number' => $number,
            'host'   => $request->getClientIp()
        ]);
    }
}