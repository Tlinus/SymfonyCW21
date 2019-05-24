<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Workflow\Registry;

/**
 * @Route("/album")
 */
class AlbumController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ALBUM_LIST")
     * @Route("/", name="album_index", methods={"GET"})
     */
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ALBUM_CREATE")
     * @Route("/new", name="album_new", methods={"GET","POST"})
     */
    public function new(Request $request, Registry $workflows): Response
    {
        $album = new Album();
        $album->setUser($this->getUSer());
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $workflow = $workflows->get($album);
            if($workflow->can($album, "created")){
              $workflow->apply($album, "created");
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('album_index');
        }

        return $this->render('album/new.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ALBUM_READ")
     * @Route("/{id}", name="album_show", methods={"GET"})
     */
    public function show(Album $album): Response
    {
        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }

    /**
     * @IsGranted("ROLE_ALBUM_UPDATE", subject="album")
     * @Route("/{id}/edit", name="album_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Album $album): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('album_index', [
                'id' => $album->getId(),
            ]);
        }

        return $this->render('album/edit.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ALBUM_DELETE", subject="album")
     * @Route("/{id}/delete", name="album_delete", methods={"GET","DELETE"})
     */
    public function delete(Request $request, Album $album): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($album);
            $entityManager->flush();
            return $this->redirectToRoute('album_index');
        }

        return $this->render('album/delete.html.twig', [
            'album' => $album
        ]);
    }

    /**
    * @Route("/{id}/state/{transition}", name="album_state_change", methods={"GET"})
    */
    public function stateChange(Registry $workflows, Album $album , String $transition)
    {
      $workflow = $workflows->get($album);
      if($workflow->can($album, $transition)){
        $workflow->apply($album, $transition);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($album);
        $entityManager->flush();

      }
      return $this->redirectToRoute("album_edit", ["id"=>$album->getId()]);
    }
}
