<?php
$startTimeStamp = strtotime($qry_izin->ki_date_izin_1);
$endTimeStamp = strtotime($qry_izin->ki_date_izin_2);

$timeDiff = abs($endTimeStamp - $startTimeStamp);
$numberDays = $timeDiff/86400;  // 86400 seconds in one day
// and you might want to convert to integer
$numberDays = intval($numberDays);
?>
<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("izin/form_proses"); ?>">
    <input type="hidden" name="txt_divisi" value="<?php echo $divisi; ?>">
    <div class="uk-form-row">
        <label for="txt_npp" class="uk-form-label">NPP</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="NPP" name="txt_npp" id="txt_npp" value="<?php echo $qry_izin->ki_npp; ?>" readonly="readonly">
            <span class="uk-form-help-inline"><div class="uk-badge uk-badge-success"><?php echo $qry_izin->karyawan_nama; ?></div></span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_tgl_izin_1" class="uk-form-label">Hari / Tanggal Izin</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="Tanggal Izin" name="txt_tgl_izin_1" id="txt_tgl_izin_1" value="<?php echo $qry_izin->ki_date_izin_1; ?>" readonly="readonly">
            <input type="text" placeholder="Tanggal Izin" name="txt_tgl_izin_2" id="txt_tgl_izin_2" value="<?php echo $qry_izin->ki_date_izin_2; ?>" readonly="readonly">
            <span class="uk-form-help-inline uk-text-warning uk-text-bold"><?php echo $numberDays; ?> Hari</span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_tgl_approve" class="uk-form-label">Hari / Tanggal Approve</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="Tanggal Approve" name="txt_tgl_approve_1" id="txt_tgl_approve_1" value="<?php echo $qry_izin->ki_date_izin_1; ?>" data-uk-datepicker="{format:'YYYY-MM-DD'}">
            <input type="text" placeholder="Tanggal Approve" name="txt_tgl_approve_2" id="txt_tgl_approve_2" value="<?php echo $qry_izin->ki_date_izin_2; ?>" data-uk-datepicker="{format:'YYYY-MM-DD'}">
            <span class="uk-form-help-inline uk-text-warning uk-text-bold" id="dayDate"></span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="opt_alasan" class="uk-form-label">Alasan Izin</label>
        <div class="uk-form-controls">
            <textarea name="txt_alasan" id="txt_alasan" cols="30" readonly="readonly"><?php echo $qry_izin->ki_alasan; ?></textarea>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="rdo_kategori" class="uk-form-label">Kategori</label>
        <div class="uk-form-controls uk-form-controls-text">
            <input type="radio" name="rdo_kategori" id="rdo_kategori" value="1"> Izin
            <input type="radio" name="rdo_kategori" id="rdo_kategori" value="2"> Izin Khos
        </div>
    </div>
    <div class="uk-form-row">
        <label for="rdo_alasan" class="uk-form-label">Approve</label>
        <div class="uk-form-controls uk-form-controls-text">
            <input type="radio" name="rdo_approve" id="rdo_approve" value="1"> Diizinkan
            <input type="radio" name="rdo_approve" id="rdo_approve" value="0"> Tidak Diizinkan
        </div>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-controls">
            <button type="submit" class="uk-button uk-button-primary" name="btn_simpan" id="btn_simpan" value="btn_simpan">Simpan</button>
            <button type="button" class="uk-button" name="btn_kembali" id="btn_kembali" value="btn_kembali">Kembali</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $("#txt_tgl_approve_2").change(function(){
            $("#txt_tgl_approve_2").val($("#txt_tgl_approve_2").val());
            var startDate = new Date($("#txt_tgl_approve_1").val());
            var endDate = new Date($("#txt_tgl_approve_2").val());
            var diffDate = new Date(endDate - startDate);
            var dayDate = diffDate/1000/60/60/24;
            $("#dayDate").html(dayDate+" Hari");
            $("#txt_jml_hari").val(dayDate);
        });
        
        $("#btn_kembali").click(function(){
            window.location.href='<?php echo base_url("izin"); ?>';
        });
    });
</script>