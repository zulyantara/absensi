<!DOCTYPE html>
<html>
    <head>
        <title><?php echo APP_TITLE; ?></title>
        <link type="image/x-icon" href="<?php echo base_url("favicon.ico"); ?>" rel="shortcut icon">
        <link rel="stylesheet" href="<?php echo base_url("assets/css/uikit.almost-flat.min.css"); ?>" />
        <link rel="stylesheet" href="<?php echo base_url("assets/css/docs.css"); ?>" />
        <link rel="stylesheet" href="<?php echo base_url("assets/css/components/autocomplete.min.css"); ?>" />
        <link rel="stylesheet" href="<?php echo base_url("assets/css/components/datepicker.min.css"); ?>" />
        
        <script src="<?php echo base_url("assets/js/jquery-2.1.1.min.js"); ?>"></script>
        <script src="<?php echo base_url("assets/js/uikit.min.js"); ?>"></script>
        <script src="<?php echo base_url("assets/js/components/autocomplete.min.js"); ?>"></script>
        <script src="<?php echo base_url("assets/js/components/datepicker.min.js"); ?>"></script>
        <script src="<?php echo base_url("assets/js/components/timepicker.min.js"); ?>"></script>
        
        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/jquery.dataTables.min.css"); ?>">
        <!-- DataTables -->
        <script type="text/javascript" charset="utf8" src="<?php echo base_url("assets/js/jquery.dataTables.min.js"); ?>"></script>
    </head>
    <body>
        <div class="tara-wrapper">
            <div class="uk-container uk-container-center uk-margin-top uk-margin-bottom">
                <nav class="tara-navbar uk-navbar uk-navbar-attached">
                    <a href="<?php echo base_url(); ?>" class="uk-navbar-brand uk-hidden-small">
                        <?php echo APP_TITLE; ?>
                    </a>
                    <?php
                    if($this->session->userdata('isLoggedIn') === TRUE)
                    {
                        ?>
                        <ul class="uk-navbar-nav uk-hidden-small">
                            <?php
                            if($this->session->userdata("userLevel") === "3" OR $this->session->userdata("userLevel") === "2" OR $this->session->userdata("userLevel") === "1")
                            {
                                ?>
                                <li id="jadwal_masuk"><a href="<?php echo base_url("jadwal_masuk"); ?>">Jadwal Masuk</a></li>
                                <li id="alpa"><a href="<?php echo base_url("alpa"); ?>">Alpa</a></li>
                                <?php
                            }
                            
                            if($this->session->userdata("userLevel") != "")
                            {
                                ?>
                                <li id="izin"><a href="<?php echo base_url("izin"); ?>">Izin</a></li>
                            <?php
                            if($this->session->userdata("userLevel") === "3" OR $this->session->userdata("userLevel") === "2" OR $this->session->userdata("userLevel") === "1")
                            {
                                ?>
                                <li id="sakit"><a href="<?php echo base_url("sakit"); ?>">Sakit</a></li>
                                <?php
                            }
                            ?>
                                <li id="cuti"><a href="<?php echo base_url("cuti"); ?>">Cuti</a></li>
                                <?php
                            }
                            
                            /* khususon manajer */
                            if($this->session->userdata("userLevel") === "3" OR $this->session->userdata("userLevel") === "2" OR $this->session->userdata("userLevel") === "1")
                            {
                                ?>
                                <li id="laporan_presensi" data-uk-dropdown="" class="uk-parent">
                                    <a href="<?php echo base_url("laporan_presensi"); ?>">Laporan Presensi</a>
                                    <div class="uk-dropdown uk-dropdown-navbar" style="">
                                        <ul class="uk-nav uk-nav-navbar">
                                            <li><a href="<?php echo base_url("laporan_presensi/personal_presensi"); ?>">Personal</a></li>
                                            <?php
                                            /* khususon SDM */
                                            if($this->session->userdata("userLevel") === "2" OR $this->session->userdata("userLevel") === "1")
                                            {
                                                ?>
                                                <li><a href="<?php echo base_url("laporan_presensi/rekap_presensi"); ?>">Rekap</a></li>
                                                <?php
                                                
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <div class="uk-navbar-flip">
                            <ul class="uk-navbar-nav">
                                <li data-uk-dropdown="" class="uk-parent">
                                    <a href="<?php echo base_url(); ?>"><?php echo "[".$this->session->userdata("userDivisi")."] ".ucwords($this->session->userdata("userName")); ?></a>
                                    <div class="uk-dropdown uk-dropdown-navbar" style="">
                                        <ul class="uk-nav uk-nav-navbar">
                                            <?php
                                            if($this->session->userdata("isPresensi") === FALSE)
                                            {
                                                ?>
                                                <li><a href="<?php echo base_url("auth/change_password"); ?>">Change Password</a></li>
                                                <?php
                                            }
                                            ?>
                                            <li><a href="<?php echo base_url("auth/logout"); ?>">Logout</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    
                        <?php
                    }
                    ?>
                    <a data-uk-offcanvas="" class="uk-navbar-toggle uk-visible-small" href="#tm-offcanvas"></a>
                    
                    <div class="uk-navbar-brand uk-navbar-center uk-visible-small">
                        <?php echo APP_TITLE; ?>
                    </div>
                </nav>
            </div>
            
            <div class="tara-middle">
                <div class="uk-container uk-container-center">
                    <div class="uk-grid" data-uk-grid-margin="">
                        <!-- Kiri -->
                        <div class="uk-width-medium-1-4 uk-hidden-small">
                            <img alt="Logo Bintang Pelajar" title="Logo Bintang Pelajar" src="<?php echo base_url("assets/img/logo-bintang-pelajar.png"); ?>" class="uk-thumbnail">
                            <?php
                            if($this->session->userdata("isPresensi") === TRUE)
                            {
                                ?>
                                <div class="uk-panel uk-panel-box" style="margin-top: 10px;">
                                    <div class="uk-text-large uk-text-danger uk-text-center" id="tgl"></div>
                                    <div class="uk-text-large uk-text-danger uk-text-center" id="jam"></div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        
                        <!-- Kanan -->
                        <div class="uk-width-medium-3-4">
                            <div class="uk-panel uk-panel-header">
                                <h3 class="uk-panel-title"><i class="uk-icon-caret-square-o-right"></i> <?php echo $panel_title; ?></h3>