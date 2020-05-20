<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Form\NewgameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(NewgameType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $permittedCharacter = '0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPSDFGHJKLMWXCVBN';
            $urlid = substr(str_shuffle($permittedCharacter), 0, 10);
            dump($urlid);

            $gameUrl = $this->generateUrl('game', ['id' => $urlid]);

            $game = new Game();
            $game->setUrl($urlid);
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();


            return $this->redirect($gameUrl);


        }
        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route( "/{id}", name="game", requirements={ "id" = "[a-zA-Z0-9]{10}" })
     */
    public function tictactoe(Request $request)
    {
        $url = substr($request->getRequestUri(), 1);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Game::class);
        $game = $repo->getByUrl($url);
        $array=[];

        $form = $this->createForm(GameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $player = '';
            if ($game->getStatus()) {
                $player = 'X';
            } else {
                $player = 'O';
            }
            $array = $game->getFields();

            $clickedButton = intval($form->getClickedButton()->getConfig()->getName());
            $array[$clickedButton] = $player;
            $game->setStatus(!$game->getStatus());
            $game->setFields($array);
            $em->flush();

            if ($this->checkVictory($array)) {
                dump("win");
            }
        }

        return $this->render('game/game.html.twig', [
            'form' => $form->createView(),
            'player' => $game->getStatus(),
            'array' => $array,
        ]);
    }


    private function checkVictory($a)
    {
        return
            (($a[0] === $a[1]) && ($a[0] === $a[2])) ? true:                                                   //row1
                (($a[3] === $a[4]) && ($a[3] === $a[5])) ? true:                                               //row2
                    (($a[6] === $a[7]) && ($a[6] === $a[8])) ? true:                                           //row3

                        (($a[0] === $a[3]) && ($a[0] === $a[6])) ? true:                                       //col1
                            (($a[1] === $a[4]) && ($a[1] === $a[7])) ? true:                                   //row2
                                (($a[2] === $a[5]) && ($a[2] === $a[8])) ? true:                               //row3

                                    (($a[0] === $a[4]) && ($a[0] === $a[8])) ? true:                           //diag1
                                        (($a[2] === $a[4]) && ($a[2] === $a[6])) ? true: false;                //diag2
    }
}
