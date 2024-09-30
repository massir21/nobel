<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*require_once 'dompdf/lib/html5lib/Parser.php';
require_once 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();*/

require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;
class Pdf
{
    protected $dompdf;
    public function __construct()
    {
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $this->dompdf = new Dompdf($options);
    }

    public function view($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait"){
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();
        $this->dompdf->stream($filename.".pdf", array("Attachment" => 0));
    }

    public function output($html, $paper = 'A4', $orientation = "portrait"){
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    public function stream($html, $name_file, $attachment, $set_paper = null, $set_option = null){
        $this->dompdf->loadHtml($html);
        if(isset($set_paper) && ($set_paper != '') ){
            $this->dompdf->setPaper($set_paper);
        }

        if(isset($set_option) && ($set_option != '') ){
            foreach ($set_option as $key => $value) {
                $this->dompdf->set_option($key, $value);
            }
        }

        $this->dompdf->render();
        return $this->dompdf->stream($name_file,$attachment);

    }
}
