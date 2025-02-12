<?php


namespace Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGeneratorService {

    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
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

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath);
        }

        file_put_contents($fileName, $invoicePDF);

        return $domPdf->output();

    }

}


?>