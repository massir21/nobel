<?php $this->load->view($this->config->item('template_dir') . '/head'); ?>

<body class="p-5">
    <h1 class="fs-2x fw-bolder my-0 text-center text-uppercase">ATENCIÓN</h1>
    <div class="card card-flush m-5">
        <div class="card-body p-5 text-center text-uppercase fs-2x">
           <?= $mensaje ?>
        </div>
    </div>
</body>

</html>