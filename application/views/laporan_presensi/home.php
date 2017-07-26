<a href="<?php echo base_url("laporan_presensi/personal_presensi"); ?>" class="uk-button uk-button-primary">Laporan Presensi Personal</a>
<?php
/* khususon SDM */
if($this->session->userdata("userLevel") === "2" OR $this->session->userdata("userLevel") === "1")
{
    ?>
    <a href="<?php echo base_url("laporan_presensi/rekap_presensi"); ?>" class="uk-button uk-button-primary">Laporan Rekap Presensi</a>
    <?php
}
?>