<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
#[\AllowDynamicProperties]
class MY_Form_validation extends CI_Form_validation
{

    public function in_column($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return isset($this->CI->db)
        ? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 1)
        : false;
    }

    public function disponible_cobro($poliza_id)
    {
        $this->CI->db->where('poliza_id', $poliza_id);
        $this->CI->db->where('estado', 5);
        $this->CI->db->where('deletedAt', '0000-00-00 00:00:00');
        $rows = $this->CI->db->get('polizas')->num_rows();
        if ($rows == 1) {
            return true;
        } else {
            $this->CI->form_validation->set_message('disponible_cobro', 'La póliza indicada no es válida para esta acción');
            return false;
        }
    }

    public function one_required($str, $field)
    {
        if ($str != '' && $this->CI->input->post($field) != '') {
            $this->CI->form_validation->set_message('one_required', 'Solo uno de los campos ({field} o {param}) es válido');
            return false;
        } else {
            return true;
        }

    }

    public function valid_iban($iban)
    {
        $iban      = strtolower(str_replace(' ', '', $iban));
        $Countries = array('al' => 28, 'ad' => 24, 'at' => 20, 'az' => 28, 'bh' => 22, 'be' => 16, 'ba' => 20, 'br' => 29, 'bg' => 22, 'cr' => 21, 'hr' => 21, 'cy' => 28, 'cz' => 24, 'dk' => 18, 'do' => 28, 'ee' => 20, 'fo' => 18, 'fi' => 18, 'fr' => 27, 'ge' => 22, 'de' => 22, 'gi' => 23, 'gr' => 27, 'gl' => 18, 'gt' => 28, 'hu' => 28, 'is' => 26, 'ie' => 22, 'il' => 23, 'it' => 27, 'jo' => 30, 'kz' => 20, 'kw' => 30, 'lv' => 21, 'lb' => 28, 'li' => 21, 'lt' => 20, 'lu' => 20, 'mk' => 19, 'mt' => 31, 'mr' => 27, 'mu' => 30, 'mc' => 27, 'md' => 24, 'me' => 22, 'nl' => 18, 'no' => 15, 'pk' => 24, 'ps' => 29, 'pl' => 28, 'pt' => 25, 'qa' => 29, 'ro' => 24, 'sm' => 27, 'sa' => 24, 'rs' => 22, 'sk' => 24, 'si' => 19, 'es' => 24, 'se' => 24, 'ch' => 21, 'tn' => 24, 'tr' => 26, 'ae' => 23, 'gb' => 22, 'vg' => 24);
        $Chars     = array('a' => 10, 'b' => 11, 'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16, 'h' => 17, 'i' => 18, 'j' => 19, 'k' => 20, 'l' => 21, 'm' => 22, 'n' => 23, 'o' => 24, 'p' => 25, 'q' => 26, 'r' => 27, 's' => 28, 't' => 29, 'u' => 30, 'v' => 31, 'w' => 32, 'x' => 33, 'y' => 34, 'z' => 35);

        $pais = substr($iban, 0, 2);
        if (array_key_exists($pais, $Countries) && strlen($iban) == $Countries[substr($iban, 0, 2)]) {

            $MovedChar      = substr($iban, 4) . substr($iban, 0, 4);
            $MovedCharArray = str_split($MovedChar);
            $NewString      = "";

            foreach ($MovedCharArray as $key => $value) {
                if (!is_numeric($MovedCharArray[$key])) {
                    $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
                }
                $NewString .= $MovedCharArray[$key];
            }

            if (bcmod($NewString, '97') == 1) {
                return true;
            }
        }
        $this->CI->form_validation->set_message('valid_iban', 'El código IBAN no es válido');
        return false;
    }

    public function is_time($str)
    {
        if (empty($str)) {
            $this->CI->form_validation->set_message('is_time', 'No se ha indicad la hora');
            return false;
        }

        $times = explode(':', $str);
        if (count($times) < 2) {
            $this->CI->form_validation->set_message('is_time', 'Formato de hora no válida');
            return false;
        }

        if (count($times) == 2) {
            $times[2] = '00';
        }

        if ((int) $times[0] > 23 || (int) $times[1] > 59 || (int) $times[2] > 59) {
            $this->CI->form_validation->set_message('is_time', 'Hora no válida');
            return false;
        } else if (mktime((int) $times[0], (int) $times[1], (int) $times[2]) === false) {
            $this->CI->form_validation->set_message('is_time', 'Invalid time');
            return false;
        }

        return true;
    }

    public function validDni($dni)
    {
        $cif = strtoupper($dni);
        for ($i = 0; $i < 9; $i++) {
            $num[$i] = substr($cif, $i, 1);
        }
        // Si no tiene un formato valido devuelve error
        if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)) {
            $this->CI->form_validation->set_message('validDni', 'DNI / NIF / NIE no válido');
            return false;
        }
        // Comprobacion de NIFs estandar
        if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif)) {
            if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, 8) % 23, 1)) {
                return true;
            } else {
                $this->CI->form_validation->set_message('validDni', 'DNI / NIF / NIE no válido');
                return false;
            }
        }
        // Algoritmo para comprobacion de codigos tipo CIF
        $suma = $num[2] + $num[4] + $num[6];
        for ($i = 1; $i < 8; $i += 2) {
            $suma += (int) substr((2 * $num[$i]), 0, 1) + (int) substr((2 * $num[$i]), 1, 1);
        }
        $n = 10 - substr($suma, strlen($suma) - 1, 1);
        // Comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
        if (preg_match('/^[KLM]{1}/', $cif)) {
            if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 1, 8) % 23, 1)) {
                return true;
            } else {
                $this->CI->form_validation->set_message('validDni', 'DNI / NIF / NIE no válido');
                return false;
            }
        }
        // Comprobacion de NIEs
        // T
        if (preg_match('/^[T]{1}/', $cif)) {
            if ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $cif)) {
                return true;
            } else {
                $this->CI->form_validation->set_message('validDni', 'DNI / NIF / NIE no válido');
                return false;
            }
        }
        // XYZ
        if (preg_match('/^[XYZ]{1}/', $cif)) {
            if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X', 'Y', 'Z'), array('0', '1', '2'), $cif), 0, 8) % 23, 1)) {
                return true;
            } else {
                $this->CI->form_validation->set_message('validDni', 'DNI / NIF / NIE no válido');
                return false;
            }
        }

        // Excluye CIF
        if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)) {
            if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1)) {
                $this->CI->form_validation->set_message('validDni', 'DNI / NIF / NIE no válido');
                return false;
            } else {
                return false;
            }
        }
        // Si todavía no se ha verificado devuelve error
        $this->CI->form_validation->set_message('validDni', 'DNI / NIF / NIE no válido');
        return false;
    }

    public function validCif($dni)
    {
        $cif = strtoupper($dni);
        for ($i = 0; $i < 9; $i++) {
            $num[$i] = substr($cif, $i, 1);
        }
        // Si no tiene un formato valido devuelve error
        if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)) {
            $this->CI->form_validation->set_message('validCif', 'Formato CIF no válido');
            return false;
        }

        // Algoritmo para comprobacion de codigos tipo CIF
        $suma = $num[2] + $num[4] + $num[6];
        for ($i = 1; $i < 8; $i += 2) {
            $suma += (int) substr((2 * $num[$i]), 0, 1) + (int) substr((2 * $num[$i]), 1, 1);
        }
        $n = 10 - substr($suma, strlen($suma) - 1, 1);

        // Comprobacion de CIFs
        if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)) {
            if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1)) {
                return true;
            } else {
                $this->CI->form_validation->set_message('validCif', 'CIF no verificado');
                return false;
            }
        } else {
            $this->CI->form_validation->set_message('validCif', 'CIF no válido');
            return false;
        }

        // Si todavía no se ha verificado devuelve error
        $this->CI->form_validation->set_message('validCif', 'CIF no verificado');
        return false;
    }

    public function validDniCifNie($dni)
    {
        $this->CI->form_validation->set_message('validDniCifNie', 'Documento de identidad no válido');
        $cif = str_pad(strtoupper($dni), 9, "0", STR_PAD_LEFT);
        for ($i = 0; $i < 9; $i++) {
            $num[$i] = substr($cif, $i, 1);
        }
        // Si no tiene un formato valido devuelve error
        if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)) {
            return false;
        }
        // Comprobacion de NIFs estandar
        if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif)) {
            if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, 8) % 23, 1)) {
                return true;
            } else {
                return false;
            }
        }
        // Algoritmo para comprobacion de codigos tipo CIF
        $suma = $num[2] + $num[4] + $num[6];
        for ($i = 1; $i < 8; $i += 2) {
            $suma += (int) substr((2 * $num[$i]), 0, 1) + (int) substr((2 * $num[$i]), 1, 1);
        }
        $n = 10 - substr($suma, strlen($suma) - 1, 1);
        // Comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
        if (preg_match('/^[KLM]{1}/', $cif)) {
            if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 1, 8) % 23, 1)) {
                return true;
            } else {
                return false;
            }
        }
        // Comprobacion de CIFs
        if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)) {
            if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1)) {
                return true;
            } else {
                return false;
            }
        }
        // Comprobacion de NIEs
        // T
        if (preg_match('/^[T]{1}/', $cif)) {
            if ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $cif)) {
                return true;
            } else {
                return false;
            }
        }
        // XYZ
        if (preg_match('/^[XYZ]{1}/', $cif)) {
            if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X', 'Y', 'Z'), array('0', '1', '2'), $cif), 0, 8) % 23, 1)) {
                return true;
            } else {
                return false;
            }
        }
        // Si todavía no se ha verificado devuelve error
        return false;
    }

    public function currentPassword($str)
    {
        if (empty($str)) {
            $this->CI->form_validation->set_message('currentPassword', 'No se ha recibido el password');
            return false;
        }

        if ($this->CI->ion_auth_model->verify_password($str, $this->CI->user->password) == false) {
            $this->CI->form_validation->set_message('currentPassword', 'Contraseña de usuario no válida');
            return false;
        } else {
            return true;
        }
    }
    
    public function dateFuturo($str)
    {
        if (strtotime($str) <= strtotime(date('Y-m-d'))) {
            $this->CI->form_validation->set_message('dateFuturo', 'La fecha indicada no es posterior a hoy');
            return false;
        }
        return true;
    }

	public function timeFuturo($str)
    {
        if (strtotime($str) <= time()) {
            $this->CI->form_validation->set_message('timeFuturo', 'La fecha indicada es anterior a este momento');
            return false;
        }
        return true;
    }

    public function valid_captcha($str)
    {
        if ($str != $this->CI->session->userdata('captcha')) {
            $this->CI->form_validation->set_message('valid_captcha', 'Captcha no válido');
            return false;
        }
        return true;
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = date($format, strtotime($date));
        $return = $d == $date;

        if($return == FALSE){
            $this->CI->form_validation->set_message('validateDate', 'Formato no válido. Formato requerido: '.$format.' llega '.$date);
            return false;
        }
        return TRUE;
    }
    public function validDate($date, $format)
    {
     
        $d = DateTime::createFromFormat($format, $date);
        if($d && $d->format($format) == $date){
            $this->CI->form_validation->set_message('validDate', 'Formato no válido. Formato requerido: '.$format);
            return false;
        }
        return true;
    }
	
	public function notHtmlTags($str)
    {
        if(preg_match("/<[^<]+>/",$str,$m)){
            $this->CI->form_validation->set_message('notHtmlTags', 'Formato de contenido no válido.');
            return false;
        }
        return TRUE;
    }
/*
    public function is_number_decimal($str)
    {
        $int = intval($str);
        $float = 
        if (!is_int($str) && !is_float($str)) {
            $this->CI->form_validation->set_message('is_number_decimal', 'El campo {field} ha de ser un número entero o decimal.');
            return FALSE;
        } else {
            return TRUE;
        }
    }*/
}
