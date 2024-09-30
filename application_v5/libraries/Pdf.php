<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . "/third_party/dompdf/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\Helpers;
use Dompdf\Exception;
use Dompdf\FontMetrics;
use Dompdf\Frame\FrameTree;

#[\AllowDynamicProperties]
class Pdf
{
    public function __construct()
    {
        $this->options = new Options();
        $this->options->set('defaultFont', 'Courier');
        $this->dompdf = new DOMPDF($this->options);
    }

    public function view($html, $filename = '', $stream = TRUE, $paper = 'A4', $orientation = "portrait")
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();
        $this->dompdf->stream($filename . ".pdf", array("Attachment" => 0));
    }

    public function output($html, $paper = 'A4', $orientation = "portrait")
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    public function stream($html, $name_file, $attachment, $set_paper = null, $set_option = null)
    {
        $this->dompdf->loadHtml($html);
        if (isset($set_paper) && ($set_paper != '')) {
            $this->dompdf->setPaper($set_paper);
        }

        if (isset($set_option) && ($set_option != '')) {
            foreach ($set_option as $key => $value) {
                $this->options->set($key, $value);
            }
        }

        $this->dompdf->render();
        return $this->dompdf->stream($name_file, $attachment);
    }



    public function generate($html, $filename, $stream = true, $attachment = 0, $paper = 'A4', $orientation = "portrait")
    {
        $options = new Options();
        $options->set('defaultFont', 'Roboto');

        $dompdf = new DOMPDF();
        /*$dompdf->set_option('isRemoteEnabled', TRUE);
        $dompdf->set_option('enable_remote', true);*/
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        if ($stream) {
            // Attachment 1 para descargar el pdf
            $dompdf->stream($filename . ".pdf", array("Attachment" => $attachment));
        } else {
            return $dompdf->output();
        }
    }
}
