<?php if ($this->session->flashdata('mensaje') != '') { ?>
    <div class="alert alert-dismissible alert-primary d-flex flex-column flex-sm-row p-5 mb-10">
        <div class="d-flex flex-column pe-0 pe-sm-10 justify-content-center text-uppercase"><?php echo $this->session->flashdata('mensaje'); ?></div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa-times fas fs-3 text-primary"></i>
        </button>
    </div>
<?php } ?>
<?php setlocale(LC_MONETARY, 'es_ES'); ?>
<div class="card card-flush mt-6">
    <div class="card-body pt-6">
        <style>
            .card .card-body {
    color: var(--bs-card-color);
}  .phone-card .card-body {
                text-align: center;
                padding: 0 !important; /* Sobreescribir el padding predeterminado */
                color: inherit; /* Sobreescribir el color predeterminado */
            }
            </style>
        <div class="row">
        <?php
        function sendApiRequest($endpoint, $method = 'GET', $data = null) {
            $url = "https://api.clinicadentalnobel.es" . $endpoint;
            $apiKey = '05448b4c-7ab9-44a1-9ce0-8929628f9a52';

            $curl = curl_init();

            $headers = [
                'Content-Type: application/json',
                'x-api-key: ' . $apiKey
            ];

            $options = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_CUSTOMREQUEST => $method
            ];

            if ($method === 'POST' && $data !== null) {
                $options[CURLOPT_POSTFIELDS] = json_encode($data);
            }

            curl_setopt_array($curl, $options);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            return ['response' => $response, 'httpCode' => $httpCode];
        }

        function checkAndGenerateQRCode($sessionId) {
            $statusResult = sendApiRequest("/session/status/$sessionId");
            $statusData = json_decode($statusResult['response'], true);

            if (!$statusData['success'] && $statusData['message'] === 'session_not_found') {
                sendApiRequest("/session/start/$sessionId");
                sleep(20); // Wait for 20 seconds
                return generateQRCode($sessionId);
            } elseif (!$statusData['success'] && $statusData['message'] === 'session_not_connected') {
                return generateQRCode($sessionId);
            } elseif ($statusData['success'] && $statusData['state'] === 'CONNECTED') {
                return 'Conectado';
            } else {
                return $statusData['message'];
            }
        }

        function generateQRCode($sessionId) {
            $qrResult = sendApiRequest("/session/qr/$sessionId/image");
            return "<img src='data:image/png;base64," . base64_encode($qrResult['response']) . "' alt='QR Code' class='qr-code' />";
        }

        $sessions = [
            ['id' => 'B2', 'name' => 'Rivas 1', 'phone' => '692 04 65 36'],
            ['id' => 'A1', 'name' => 'Rivas 2', 'phone' => '690 12 46 36'],
            ['id' => 'C3', 'name' => 'Fuenlabrada', 'phone' => '662 31 01 90']
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionId = $_POST['session_id'];
            $action = $_POST['action'];

            if ($action === 'terminateSession') {
                sendApiRequest("/session/terminate/$sessionId");
            }
        }

        foreach ($sessions as $session) {
            $status = checkAndGenerateQRCode($session['id']);
            $qrCodeImage = '';
            if ($status !== 'Conectado') {
              //  $qrCodeImage = generateQRCode($session['id']);
            }
        ?>
            <div class="col-md-4">
                <div class="card phone-card">
                    <div class="card-header">
                        <h3><?php echo $session['name']; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="status"><?php echo $status; ?></div>
                        <div class="qr-code"><?php echo $qrCodeImage; ?></div>
                        <form method="POST" action="" class="d-flex justify-content-center">
    <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
    <input type="hidden" name="action" value="terminateSession">
    <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
</form>

                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        </div>
    </div>
</div>
</div>
