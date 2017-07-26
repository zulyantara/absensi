<?php
$npp = isset($txt_npp) ? $txt_npp : "";
$tgl = isset($txt_tgl) ? $txt_tgl : "";
$jam = isset($txt_jam) ? $txt_jam : "";

if(isset($alert))
{
    ?>
    <div class="uk-alert uk-alert-<?php echo $class_alert; ?>">
        <?php echo $alert; ?>
    </div>
    <?php
}
?>
<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("lembur"); ?>">
    <div class="uk-form-row">
        <label for="txt_npp" class="uk-form-label">NPP</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="NPP" name="txt_npp" id="txt_npp" value="<?php echo $npp; ?>">
            <span class="uk-form-help-inline"><?php echo form_error('txt_npp'); ?></span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_tgl_lembur" class="uk-form-label">Tanggal Lembur</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="Tanggal Lembur" name="txt_tgl_lembur" id="txt_tgl_lembur" value="<?php echo $tgl; ?>" data-uk-datepicker="{format:'YYYY-MM-DD'}">
            <span class="uk-form-help-inline"><?php echo form_error('txt_tgl_lembur'); ?></span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_jam_lembur" class="uk-form-label">Jumlah Jam Kerja</label>
        <div class="uk-form-controls">
            <input type="text" name="txt_jam_kerja" id="txt_jam_kerja" value="<?php echo $jam; ?>" readonly="readonly">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="rdo_hari_lembur" class="uk-form-label">Hari Lembur</label>
        <div class="uk-form-controls uk-form-controls-text">
            <input type="radio" name="rdo_hari_lembur" id="rdo_hari_lembur" value="0" checked="checked"> Hari Kerja
            <input type="radio" name="rdo_hari_lembur" id="rdo_hari_lembur" value="1"> Hari Libur
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_keterangan" class="uk-form-label">Keterangan</label>
        <div class="uk-form-controls">
            <textarea name="txt_keterangan" id="txt_keterangan" cols="30" placeholder="Keterangan" autofocus="autofocus"></textarea>
            <span class="uk-form-help-inline"><?php echo form_error('txt_keterangan'); ?></span>
        </div>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-controls">
            <button type="submit" class="uk-button uk-button-primary" name="btn_simpan" id="btn_simpan" value="simpan">Simpan</button>
            <button type="button" class="uk-button uk-button" name="btn_kembali" id="btn_kembali" value="kembali">Kembali</button>
        </div>
    </div>
</form>

<script type="text/javascript">
$(document).ready(function(){
    $("#txt_npp").blur(function(event){
        var npp = $("#txt_npp").val();
        $("#txt_nama").load('<?php echo base_url("izin/check_nama"); ?>',{"txt_npp":npp});
    });
    $("#btn_kembali").click(function(){
        window.history.back();
    });
});
</script>