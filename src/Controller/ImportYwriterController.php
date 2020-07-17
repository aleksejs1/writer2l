<?php

namespace App\Controller;

use App\Service\ImportYwriteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImportYwriterController extends AbstractController
{
    /**
     * @Route("project/import/ywriter", name="import_ywriter")
     * @param Request $request
     * @param ImportYwriteService $importYwriteService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, ImportYwriteService $importYwriteService)
    {
        $error = '';
        $data = [];
        $form = $this
            ->createFormBuilder($data)
            ->add('file', FileType::class, [
                'attr' => ['accept' => '.zip'],
                'constraints' => [new \Symfony\Component\Validator\Constraints\File(['maxSize' => '1024k'])]
            ])
            ->add('submit', SubmitType::class)
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var File $file */
            $file = $form['file']->getData();
            if ('zip' !== $file->guessExtension()) {
                $error = 'Wrong file type.';
            } else {
                $project = null;
                try {
                    $project = $importYwriteService->readZip($file, $this->getUser());
                } catch (\Exception $e) {
                    $error = 'Sorry, sth went wrong... send your file to developers to check this case';
                }

                if ($project) {
                    return  $this->redirectToRoute('project_show', ['project' => $project->getId()]);
                }
            }
        }

        return $this->render('import_ywriter/index.html.twig', [
            'error' => $error,
            'form' => $form->createView(),
            'import' => true,
        ]);
    }


}
