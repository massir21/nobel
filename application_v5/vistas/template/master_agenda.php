<!DOCTYPE html>
<html lang="es">

<head>
    <base href="" />
    <title>
        <?= SITETITLE ?>
    </title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="es_ES" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <meta property="og:url" content="<?= base_url() ?>" />
    <meta property="og:site_name" content="<?= SITETITLE ?>" />
    <link rel="canonical" href="<?= base_url() ?>" />
    <link rel="shortcut icon" href="<?= base_url() ?>favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="<?= base_url() ?>assets_v5/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet"
        type="text/css" />
    <link href="<?= base_url() ?>assets_v5/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
        type="text/css" />
    <link href="<?= base_url() ?>assets_v5/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets_v5/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <script>
        var hostUrl = "<?= base_url() ?>assets_v5/";
    </script>
    <script src="<?= base_url() ?>assets_v5/plugins/global/plugins.bundle.js"></script>
    <script src="<?= base_url() ?>assets_v5/js/scripts.bundle.js"></script>
    <script src="<?= base_url() ?>assets_v5/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
    <script src="<?= base_url() ?>assets_v5/plugins/custom/fullcalendar/fullcalendar.scheduler.main.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
    <script src="<?= base_url() ?>assets_v5/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="<?= base_url() ?>assets_v5/js/widgets.bundle.js"></script>
    <script src="<?= base_url() ?>assets_v5/js/custom/widgets.js"></script>
    <script src="<?= base_url() ?>assets_v5/js/custom/apps/chat/chat.js"></script>
    <script src="<?= base_url() ?>assets_v5/js/custom/utilities/modals/upgrade-plan.js"></script>
    <script src="<?= base_url() ?>assets_v5/js/custom/utilities/modals/create-app.js"></script>
    <script src="<?= base_url() ?>assets_v5/js/custom/utilities/modals/new-target.js"></script>
    <script src="<?= base_url() ?>assets_v5/js/custom/utilities/modals/users-search.js"></script>
    <style>
        @media (max-width: 582px) {

            .app-default,
            body {
                background-color: white;
            }

            .card>div {
                padding-left: 0px !important;
                padding-right: 0px !important;
            }
        }
    </style>
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <?php $this->load->view($this->config->item('template_dir') . '/header'); ?>
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <?php $this->load->view($this->config->item('template_dir') . '/sidebar'); ?>
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <?php // $this->load->view($this->config->item('template_dir') . '/pagetitle'); ?>
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-fluid pt-5">
                                <?php echo $content_view ?>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view($this->config->item('template_dir') . '/footer'); ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        <?php if ($this->uri->segment(2) != '') { ?>
            console.log('<?= $this->uri->segment(2) ?>')
            var currenturl = '<?= base_url() . $this->uri->segment(1) . '/' . $this->uri->segment(2) ?>';
        <?php } else { ?>
            var currenturl = '<?= base_url() . $this->uri->segment(1) ?>';
        <?php } ?>
        var menuitem = $('.menu-link[href="' + currenturl + '"]');
        if (menuitem.length > 0) {
            menuitem.addClass('active');
            menuitem.closest('.menu-sub-accordion').addClass('show');
            menuitem.closest('.menu-accordion').addClass('hover show');
        } else {
            var currenturl = '<?= base_url() . $this->uri->segment(1) ?>/gestion';
            var encontrado = 0;
            $(".menu-link[href]").each(function () {
                if ($(this).attr("href").indexOf(currenturl) >= 0 && $(this).hasClass("menu-link") && encontrado == 0) {
                    var menuitem = $(this);
                    menuitem.addClass('active');
                    menuitem.closest('.menu-sub-accordion').addClass('show');
                    menuitem.closest('.menu-accordion').addClass('hover show');
                    encontrado = 1;
                }
            });
            if (encontrado == 0) {
                var currenturl = '<?= base_url() . $this->uri->segment(1) ?>/<?= $this->uri->segment(2) ?>';
                $(".menu-link[href]").each(function () {
                    if ($(this).attr("href").indexOf(currenturl) >= 0 && $(this).hasClass("menu-link") && encontrado == 0) {
                        var menuitem = $(this);
                        menuitem.addClass('active');
                        menuitem.closest('.menu-sub-accordion').addClass('show');
                        menuitem.closest('.menu-accordion').addClass('hover show');
                        encontrado = 1;
                    }
                });
                if (encontrado == 0) {
                    var currenturl = '<?= base_url() . $this->uri->segment(1) ?>';
                    $(".menu-link[href]").each(function () {
                        if ($(this).attr("href").indexOf(currenturl) >= 0 && $(this).hasClass("menu-link") && encontrado == 0) {
                            var menuitem = $(this);
                            menuitem.addClass('active');
                            menuitem.closest('.menu-sub-accordion').addClass('show');
                            menuitem.closest('.menu-accordion').addClass('hover show');
                            encontrado = 1;
                        }
                    });
                }
            }
        }
    </script>
</body>

</html>