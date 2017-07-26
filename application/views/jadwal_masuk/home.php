<?php echo isset($msg) && $msg != "" ? "<div class=\"uk-alert uk-alert-success\">".$msg."</div>" : ""; ?>
<button id="btnGuru">Export</button>
<a href="<?php echo base_url("jadwal_masuk/frm_tambah"); ?>" class="uk-button uk-button-primary uk-margin-bottom">Input Jadwal</a>
<table class="display cell-border compact hover nowrap order-column stripe" id="table-grid">
    <thead>
        <tr>
            <th>Nama Pegawai</th>
            <th class="tara-60">Senin</th>
            <th class="tara-60">Selasa</th>
            <th class="tara-60">Rabu</th>
            <th class="tara-60">Kamis</th>
            <th class="tara-60">Jumat</th>
            <th class="tara-60">Sabtu</th>
            <th></th>
        </tr>
    </thead>
    
    <tfoot>
        <tr>
            <th>Nama Pegawai</th>
            <th class="tara-60">Senin</th>
            <th class="tara-60">Selasa</th>
            <th class="tara-60">Rabu</th>
            <th class="tara-60">Kamis</th>
            <th class="tara-60">Jumat</th>
            <th class="tara-60">Sabtu</th>
            <th></th>
        </tr>
    </tfoot>
    
    <tbody>
    <?php
    foreach($res_jadwal_masuk as $row_jadwal_masuk)
    {
        ?>
        <tr>
            <td><?php echo "[".$row_jadwal_masuk->karyawan_npp."] ".ucwords(strtolower($row_jadwal_masuk->karyawan_nama)); ?></td>
            <td><?php echo $row_jadwal_masuk->wk_senin; ?></td>
            <td><?php echo $row_jadwal_masuk->wk_selasa; ?></td>
            <td><?php echo $row_jadwal_masuk->wk_rabu; ?></td>
            <td><?php echo $row_jadwal_masuk->wk_kamis; ?></td>
            <td><?php echo $row_jadwal_masuk->wk_jumat; ?></td>
            <td><?php echo $row_jadwal_masuk->wk_sabtu; ?></td>
            <td><button type="button" name="btn_edit" id="btn_edit" value="Edit" class="uk-button uk-button-primary" onclick="edit_jadwal('<?php echo $row_jadwal_masuk->karyawan_npp; ?>')">Edit</button></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>

<script type="text/javascript">
    function edit_jadwal(npp){
        window.location.href="<?php echo base_url("jadwal_masuk/frm_tambah"); ?>"+"/"+npp;
    }
    
    $("#btnGuru").click(function () {
        tableToExcel('myDataTable', 'W3C Example Table');
    });
     

    var tableToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
          , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
          , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
          , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
        return function (table, name) {
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }
            window.location.href = uri + base64(format(template, ctx))
        }
    })()

    $(document).ready(function(){
        $('#table-grid').DataTable({
            "columnDefs": [
                {"targets": [ 1 ],"searchable": false,"orderable": false},
                {"targets": [ 2 ],"searchable": false,"orderable": false},
                {"targets": [ 3 ],"searchable": false,"orderable": false},
                {"targets": [ 4 ],"searchable": false,"orderable": false},
                {"targets": [ 5 ],"searchable": false,"orderable": false},
                {"targets": [ 6 ],"searchable": false,"orderable": false}
            ]
        });
    });
</script>