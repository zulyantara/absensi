<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=absensi_rekap.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo HEAD_TITLE; ?></title>
    </head>
    <body>
        
        <table>
            <tr>
                <td><b>Nama</b></td>
                <td colspan="4"><b>: <?php echo $res_biodata->karyawan_nama; ?></b></td>
            </tr>
            <tr>
                <td><b>Jabatan</b></td>
                <td colspan="4"><b>: <?php echo $res_biodata->jabatan_ket; ?></b></td>
            <tr>
                <td><b>Cabang</b></td>
                <td colspan="4"><b>: <?php echo $res_biodata->cabang_ket; ?></b></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" align="center"><b>Presensi Tanggal <?php echo $txt_tgl_1; ?> s/d <?php echo $txt_tgl_2; ?></b></td>
            </tr>
                <td></td>
            </tr>
        </table>
        
        <table border="1">
            <tr>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Keterangan</th>
            </tr>
            
            <?php
            foreach ($res_personal as $row)
            {
                $timestamp = strtotime($row->absensi_tgl);
                $day = date('D',$timestamp);
                $dayArr = array('Mon'=>'Senin','Tue'=>'Selasa','Wed'=>'Rabu','Thu'=>'Kamis','Fri'=>'Jum\'at','Sat'=>'Sabtu');
                
                $tanggal = date('d-m-Y', strtotime($row->absensi_tgl));
                ?>
                <tr>
                    <td><?php echo $tanggal; ?></td>
                    <td><?php echo $dayArr[$day]; ?></td>
                    <td><?php echo $row->absensi_jamMasuk; ?></td>
                    <td><?php echo $row->absensi_jamKeluar; ?></td>
                    <td><!-- Keterangan --></td>
                </tr>
            <?php } ?>
        </table>
    </body>
</html>