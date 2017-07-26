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
<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("cuti"); ?>">
    <div class="uk-form-row">
        <label for="txt_npp" class="uk-form-label">NPP</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="NPP" name="txt_npp" id="txt_npp">
            <span class="uk-form-help-inline"><?php echo form_error('txt_npp'); ?></span>
        </div>
    </div>
    <?php
    if(isset($npp))
    {
        ?>
        <div class="uk-form-row">
            <label for="txt_sisa_cuti" class="uk-form-label">Sisa Cuti</label>
            <div class="uk-form-controls">
                <input type="text" placeholder="Sisa Cuti" name="txt_sisa_cuti" id="txt_sisa_cuti">
                <span class="uk-form-help-inline"><?php echo form_error('txt_sisa_cuti'); ?></span>
        </div>
        <?php
    }
    ?>
    <div class="uk-form-row">
        <label for="txt_tgl_cuti" class="uk-form-label">Hari / Tanggal Cuti</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="Tanggal Cuti" name="txt_tgl_cuti_1" id="txt_tgl_cuti_1" data-uk-datepicker="{format:'YYYY-MM-DD'}">
            s/d
            <input type="text" placeholder="Tanggal Cuti" name="txt_tgl_cuti_2" id="txt_tgl_cuti_2" data-uk-datepicker="{format:'YYYY-MM-DD'}">
                <span class="uk-form-help-inline"><?php echo form_error('txt_tgl_cuti_1'); ?></span>
            <span class="uk-form-help-inline"><?php echo form_error('txt_tgl_cuti_2'); ?></span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_alasan" class="uk-form-label">Alasan Cuti</label>
        <div class="uk-form-controls">
            <textarea name="txt_alasan" id="txt_alasan" cols="30"></textarea>
            <span class="uk-form-help-inline"><?php echo form_error('txt_alasan'); ?></span>
        </div>
    </div>
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
            <th style="width: 135px;">Tanggal Cuti</th>
            <th style="width: 170px;">Alasan Cuti</th>
            <th style="width: 100px;">Sisa Cuti</th>
            <th style="width: 100px;">Status</th>
            <th style="width: 50px;">Aksi</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Karyawan</th>
            <th>Tanggal Cuti</th>
            <th>Alasan Cuti</th>
            <th>Sisa Cuti</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        if($qry_cuti != FALSE)
        {
            foreach($qry_cuti as $row_cuti)
            {
                $npp = $row_cuti->karyawan_npp;
                $divisi = $row_cuti->jabatan_divisi;
                $tgl_1 = date("d-m-Y", strtotime($row_cuti->ki_date_izin_1));
                $tgl_2 = date("d-m-Y", strtotime($row_cuti->ki_date_izin_2));
                if($row_cuti->ki_flag_approve === "1")
                {
                    $status = "Diizinkan";
                    $disable = "disabled=\"disabled\"";
                }
                elseif($row_cuti->ki_flag_approve === "0")
                {
                    $status = "Tidak diizinkan";
                    $disable = "disabled=\"disabled\"";
                }
                elseif($row_cuti->ki_flag_approve === "2")
                {
                    $status = "Belum Diproses";
                    $disable = "";
                }
                ?>
                
                <tr>
                    <td><?php echo "[".$npp."] ".$row_cuti->karyawan_nama; ?></td>
                    <td><?php echo $tgl_1." - ".$tgl_2; ?></td>
                    <td><?php echo ucwords($row_cuti->ki_alasan); ?></td>
                    <td class="uk-text-center">0</td>
                    <td><?php echo $status; ?></td>
                    <td>
                        <button type="button" name="btn_proses" id="btn_proses" value="btn_proses" class="uk-button uk-button-primary" onclick="proses_cuti('<?php echo $npp; ?>',<?php echo $divisi; ?>, '<?php echo $tgl_1; ?>','<?php echo $tgl_2; ?>')" <?php echo $disable; ?>>Proses</button>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
function proses_cuti(npp, divisi, tgl_1, tgl_2){
    window.location.href='<?php echo base_url("cuti/form_proses"); ?>/'+npp+"/"+divisi+"/"+tgl_1+"/"+tgl_2;
}
$(document).ready(function(){
    $('#example').DataTable({
        "order": [[ 1, "asc" ]],
        "columnDefs": [
            {"targets": 2, "orderable": false },
            {"targets": 3, "orderable": true },
            <?php
            /* khususon manajer */
            if($this->session->userdata("userLevel") === "3" OR $this->session->userdata("userLevel") === "2" OR $this->session->userdata("userLevel") === "1")
            {
                ?>
                {"targets": [ 4 ],"visible": true,"searchable": false,"orderable": false}
                <?php
            }
            else
            {
                ?>
                {"targets": [ 4 ],"visible": false}
                <?php
            }
            ?>
        ]
    });
    
    $("#txt_npp").blur(function(event){
        var npp = $("#txt_npp").val();
        $("#txt_nama").load('<?php echo base_url("izin/check_nama"); ?>',{"txt_npp":npp});
    });
});
</script>