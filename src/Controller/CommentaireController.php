<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Commentaire;
use App\Form\Commentaire1Type;
use App\Repository\CommentaireRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




#[Route('/article/{id}/commentaire')]
class CommentaireController extends AbstractController
{

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Article $article, User $user, CommentaireRepository $commentaireRepository): Response
    {

        $commentaire = new Commentaire();
        $created_ad_string = new DateTime();
        $created_ad_stringValue = $created_ad_string->format('Y-m-d');
        $commentaire->setCreatedAd($created_ad_stringValue);
        $commentaire->setUser($user);
        $commentaire->setArticle($article);
        $form = $this->createForm(Commentaire1Type::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_article_show', [
                'id' => $article->getID()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
}
