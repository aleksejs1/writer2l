<?php

namespace App\Controller;

use App\Service\pixelAvatarGenerator\Avatar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/avatar")
 */
class AvatarController extends AbstractController
{
    /**
     * @Route("/{size}/{sex}/{id}", name="avatar", methods={"GET"})
     */
    public function index(int $size, string $sex, string $id, Filesystem $filesystem)
    {
        if (!in_array($sex, ['male', 'female'])) {
            $sex = random_int(0,1) ? 'male' : 'female';
        }

        $dir = $this->getParameter('kernel.cache_dir') . '/avatars/';
        $file = $dir . $size . '_' . $sex . '_' . $id . '.png';

        if (!$filesystem->exists($file)) {
            $avatar = Avatar::generate($size, $sex, $id);
            if (!$filesystem->exists($dir)) {
                $filesystem->mkdir($dir);
            }
            imagepng($avatar,$file,3);
        }

        return new BinaryFileResponse($file);
    }
}
