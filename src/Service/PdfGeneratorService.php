<?php


namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

class PdfGeneratorService {

    private Environment $twig;
    private KernelInterface $kernel;

    public function __construct(Environment $twig, KernelInterface $kernelInterface) {
        $this->twig = $twig;
        $this->kernel = $kernelInterface;
    }

    public function generatePdf($data, $fileName, $template, $destinationPath) {

        // 1 - mettre en place les options du pdf
        $pdfOptions = new Options();
        $pdfOptions->set(['defaultFont' => 'Arial', 'enable_remote' => true]);

        // 2 - je créer le pdf
        $domPdf = new Dompdf($pdfOptions);

        // 3 - préparer le template
        $html = $this->twig->render($template, $data);

        $domPdf->loadHtml($html);
        $domPdf->setPaper('A4', 'portrait');

        $domPdf->render();
        $invoicePDF = $domPdf->output();

        $uploadDirectory = $this->kernel->getProjectDir() . "/public/" . $destinationPath;
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        file_put_contents($fileName, $invoicePDF);

        return $domPdf->output();

    }

}


?>