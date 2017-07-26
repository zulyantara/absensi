<?php
if(isset($alert))
{
    ?>
    <div class="uk-alert uk-alert-<?php echo $class_alert; ?>">
        <?php echo $alert; ?>
    </div>
    <?php
}
?>
<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("sakit"); ?>">
    <div class="uk-form-row">
        <label for="txt_npp" class="uk-form-label">NPP</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="NPP" name="txt_npp" id="txt_npp">
            <span class="uk-form-help-inline"><?php echo form_error('txt_npp'); ?></span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_tgl_sakit" class="uk-form-label">Hari / Tanggal Sakit</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="Tanggal Izin" name="txt_tgl_sakit_1" id="txt_tgl_sakit_1" data-uk-datepicker="{format:'YYYY-MM-DD'}">
            s/d
            <input type="text" placeholder="Tanggal Izin" name="txt_tgl_sakit_2" id="txt_tgl_sakit_2" data-uk-datepicker="{format:'YYYY-MM-DD'}">
                <span class="uk-form-help-inline"><?php echo form_error('txt_tgl_sakit_1'); ?></span>
            <span class="uk-form-help-inline"><?php echo form_error('txt_tgl_sakit_2'); ?></span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_keterangan" class="uk-form-label">Keterangan</label>
        <div class="uk-form-controls">
            <textarea name="txt_keterangan" id="txt_keterangan" cols="30"></textarea>
            <span class="uk-form-help-inline"><?php echo form_error('txt_keterangan'); ?></span>
        </div>
    </div>
    <?php
    if(isset($npp))
    {
        ?>
        <div class="uk-form-row">
            <label for="opt_kategori" class="uk-form-label">Kategori Izin</label>
            <div class="uk-form-controls">
                <select name="opt_kategori" id="opt_kategori">
                    <?php
                    foreach($qry_kategori as $row_kategori)
                    {
                        ?>
                        <option value="<?php echo $row_kategori->kategori_id; ?>"><?php echo $row_kategori->kategori_ket; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="uk-form-help-inline"><?php echo form_error('txt_keterangan'); ?></span>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="uk-form-row">
        <div class="uk-form-controls">
            <button class="uk-button uk-button-primary" name="btn_simpan" id="btn_simpan" value="simpan">Simpan</button>
        </div>
    </div>
</form>
<hr>
<table id="example" class="compact uk-table uk-table-hover uk-table-striped">
    <thead>
        <tr>
            <th style="width: 200px;">Karyawan</th>
            <th style="width: 135px;">Tanggal Sakit</th>
            <th style="width: 170px;">Alasan Sakit</th>
            <th style="width: 100px;">Status</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Karyawan</th>
            <th>Tanggal Izin</th>
            <th>Alasan Izin</th>
            <th>Status</th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        if($qry_sakit != FALSE)
        {
            foreach($qry_sakit as $row_sakit)
            {
                $npp = $row_sakit->karyawan_npp;
                $divisi = $row_sakit->jabatan_divisi;
                $tgl_1 = date("d-m-Y", strtotime($row_sakit->ki_date_izin_1));
                $tgl_2 = date("d-m-Y", strtotime($row_sakit->ki_date_izin_2));
                if($row_sakit->ki_flag_approve === "1")
                {
                    $status = "Diizinkan";
                    $disable = "disabled=\"disabled\"";
                }
                elseif($row_sakit->ki_flag_approve === "0")
                {
                    $status = "Tidak diizinkan";
                    $disable = "disabled=\"disabled\"";
                }
                elseif($row_sakit->ki_flag_approve === "2")
                {
                    $status = "Belum Diproses";
                    $disable = "";
                }
                ?>
                
                <tr>
                    <td><?php echo "[".$npp."] ".$row_sakit->karyawan_nama; ?></td>
                    <td><?php echo $tgl_1." - ".$tgl_2; ?></td>
                    <td><?php echo ucwords($row_sakit->ki_alasan); ?></td>
                    <td><?php echo $status; ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
function proses_izin(npp, divisi, tgl_1, tgl_2){
    window.location.href='<?php echo base_url("izin/form_proses"); ?>/'+npp+"/"+divisi+"/"+tgl_1+"/"+tgl_2;
}
$(document).ready(function(){
    $('#example').DataTable({
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            {"targets": 2, "orderable": false },
            {"targets": 3, "orderable": true }
        ]
    });
    
    $("#txt_npp").blur(function(event){
        var npp = $("#txt_npp").val();
        $("#txt_nama").load('<?php echo base_url("izin/check_nama"); ?>',{"txt_npp":npp});
    });
});
</script>