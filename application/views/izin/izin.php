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
<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("izin"); ?>">
    <div class="uk-form-row">
        <label for="txt_npp" class="uk-form-label">NPP</label>
        <div class="uk-form-controls">
            <select name="txt_npp" id="txt_npp">
                <?php
                foreach($res_karyawan as $row_karyawan)
                {
                    ?>
                    <option value="<?php echo $row_karyawan->karyawan_npp; ?>"><?php echo $row_karyawan->karyawan_nama; ?></option>
                    <?php
                }
                ?>
            </select>
            <span class="uk-form-help-inline"><?php echo form_error('txt_npp'); ?></span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_tgl_izin" class="uk-form-label">Hari / Tanggal Izin</label>
        <div class="uk-form-controls">
            <input type="text" placeholder="Tanggal Izin" name="txt_tgl_izin_1" id="txt_tgl_izin_1" data-uk-datepicker="{format:'YYYY-MM-DD'}">
            s/d
            <input type="text" placeholder="Tanggal Izin" name="txt_tgl_izin_2" id="txt_tgl_izin_2" data-uk-datepicker="{format:'YYYY-MM-DD'}">
                <span class="uk-form-help-inline"><?php echo form_error('txt_tgl_izin_1'); ?></span>
            <span class="uk-form-help-inline"><?php echo form_error('txt_tgl_izin_2'); ?></span>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_alasan" class="uk-form-label">Alasan Izin</label>
        <div class="uk-form-controls">
            <textarea name="txt_alasan" id="txt_alasan" cols="30"></textarea>
            <span class="uk-form-help-inline"><?php echo form_error('txt_alasan'); ?></span>
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
                <span class="uk-form-help-inline"><?php echo form_error('opt_kategori'); ?></span>
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
<div class="uk-margin-bottom">
    <table id="example" class="compact uk-table uk-table-hover uk-table-striped">
        <thead>
            <tr>
                <th style="width: 200px;">Karyawan</th>
                <th style="width: 135px;">Tanggal Izin</th>
                <th style="width: 170px;">Alasan Izin</th>
                <th style="width: 170px;">Kategori</th>
                <th style="width: 100px;">Status</th>
                <th style="width: 50px;">Aksi</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Karyawan</th>
                <th>Tanggal Izin</th>
                <th>Alasan Izin</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
            if($qry_izin != FALSE)
            {
                foreach($qry_izin as $row_izin)
                {
                    $npp = $row_izin->karyawan_npp;
                    $divisi = $row_izin->jabatan_divisi;
                    $tgl_1 = date("d-m-Y", strtotime($row_izin->ki_date_izin_1));
                    $tgl_2 = date("d-m-Y", strtotime($row_izin->ki_date_izin_2));
                    if($row_izin->ki_flag_approve === "1")
                    {
                        $status = "Diizinkan";
                        $disable = "disabled=\"disabled\"";
                    }
                    elseif($row_izin->ki_flag_approve === "0")
                    {
                        $status = "Tidak diizinkan";
                        $disable = "disabled=\"disabled\"";
                    }
                    elseif($row_izin->ki_flag_approve === "2")
                    {
                        $status = "Belum Diproses";
                        $disable = "";
                    }
                    ?>
                    
                    <tr>
                        <td><?php echo "[".$npp."] ".$row_izin->karyawan_nama; ?></td>
                        <td><?php echo $tgl_1." - ".$tgl_2; ?></td>
                        <td><?php echo ucwords($row_izin->ki_alasan); ?></td>
                        <td><?php echo $row_izin->kategori_ket; ?></td>
                        <td><?php echo $status; ?></td>
                        <td>
                            <button type="button" name="btn_proses" id="btn_proses" value="btn_proses" class="uk-button uk-button-primary" onclick="proses_izin('<?php echo $npp; ?>',<?php echo $divisi; ?>, '<?php echo $tgl_1; ?>','<?php echo $tgl_2; ?>')" <?php echo $disable; ?>>Proses</button>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
function proses_izin(npp, divisi, tgl_1, tgl_2){
    window.location.href='<?php echo base_url("izin/form_proses"); ?>/'+npp+"/"+divisi+"/"+tgl_1+"/"+tgl_2;
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