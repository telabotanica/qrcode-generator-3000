<?php

namespace App\Controller;

use Endroid\QrCode\Exception\UnsupportedExtensionException;
use Endroid\QrCode\Factory\QrCodeFactoryInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SmartFloreQrcodeController extends AbstractController {

    /**
     * @Route("/smartflore/{species}/{text}.{extension}", name="smartflore-qrcode", requirements={"text"=".+", "species"="[^\/]*"})
     */
    public function generateSmartFloreQrCode(
        QrCodeFactoryInterface $qrCodeFactory,
        string $species,
        string $text,
        string $extension
    ): Response
    {
        $options = [
            'size' => 500,
            'margin' => 30,
            'label' => $species ? $species.' - Smart’Flore' : 'Smart’Flore',
        ];

        $qrCode = $qrCodeFactory->create($text, $options);

        if ($qrCode instanceof QrCode) {
            try {
                $qrCode->setWriterByExtension($extension);
            } catch (UnsupportedExtensionException $e) {
                throw new NotFoundHttpException("Extension '$extension' is not a supported extension.");
            }
        }

        return new QrCodeResponse($qrCode);
    }
}
