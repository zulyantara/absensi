<?php
$npp = isset($qry_edit) ? $qry_edit->wk_npp : "";
$wk_senin = isset($qry_edit) ? $qry_edit->wk_senin : "";
$wk_selasa = isset($qry_edit) ? $qry_edit->wk_selasa : "";
$wk_rabu = isset($qry_edit) ? $qry_edit->wk_rabu : "";
$wk_kamis = isset($qry_edit) ? $qry_edit->wk_kamis : "";
$wk_jumat = isset($qry_edit) ? $qry_edit->wk_jumat : "";
$wk_sabtu = isset($qry_edit) ? $qry_edit->wk_sabtu : "";
$wk_cabang = isset($qry_edit) ? $qry_edit->wk_cabang : "";

echo validation_errors();
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("jadwal_masuk/frm_tambah"); ?>">
    <div class="uk-form-row">
        <label for="opt_npp" class="uk-form-label">NPP</label>
        <div class="uk-form-controls uk-form-controls-text">
            <select name="opt_npp" id="opt_npp">
                <?php
                foreach($res_karyawan as $row_karyawan)
                {
                    ?>
                    <option value="<?php echo $row_karyawan->karyawan_npp; ?>"><?php echo $row_karyawan->karyawan_nama; ?> [<?php echo $row_karyawan->jabatan_ket; ?>]</option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_senin" class="uk-form-label">Senin</label>
        <div class="uk-form-controls">
            <input type="text" id="txt_senin" name="txt_senin" data-uk-timepicker value="<?php echo $wk_senin; ?>">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_selasa" class="uk-form-label">Selasa</label>
        <div class="uk-form-controls">
            <input type="text" id="txt_selasa" name="txt_selasa" data-uk-timepicker value="<?php echo $wk_selasa; ?>">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_rabu" class="uk-form-label">Rabu</label>
        <div class="uk-form-controls">
            <input type="text" id="txt_rabu" name="txt_rabu" data-uk-timepicker value="<?php echo $wk_rabu; ?>">
        </div>
    </div>

    <div class="uk-form-row">
        <label for="txt_kamis" class="uk-form-label">Kamis</label>
        <div class="uk-form-controls">
            <input type="text" id="txt_kamis" name="txt_kamis" data-uk-timepicker value="<?php echo $wk_kamis; ?>">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_jumat" class="uk-form-label">Jumat</label>
        <div class="uk-form-controls">
            <input type="text" id="txt_jumat" name="txt_jumat" data-uk-timepicker value="<?php echo $wk_jumat; ?>">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_sabtu" class="uk-form-label">Sabtu</label>
        <div class="uk-form-controls">
            <input type="text" id="txt_sabtu" name="txt_sabtu" data-uk-timepicker value="<?php echo $wk_sabtu; ?>">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="opt_cabang" class="uk-form-label">Cabang</label>
        <div class="uk-form-controls">
            <select name="opt_cabang" id="opt_cabang">
                <?php
                foreach($res_cabang as $row_cabang)
                {
                    $selected = $row_cabang->cabang_kode === $wk_cabang ? "selected=\"selected\"" : "";
                    ?>
                    <option value="<?php echo $row_cabang->cabang_kode; ?>" <?php echo $selected; ?>><?php echo $row_cabang->cabang_kode." | ".$row_cabang->cabang_ket; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <input type="submit" name="btn_simpan" id="btn_simpan" value="Simpan" class="uk-button uk-button-primary">
        <a href="<?php echo base_url("jadwal_masuk/index"); ?>" class="uk-button uk-button-primary">List Jadwal Masuk</a>
    </div>
</form>