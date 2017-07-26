<?php
$nama_karyawan = $res_biodata->karyawan_nama;

$arr_bulan = array("01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember");

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$nama_karyawan."_".$arr_bulan[$bln_pilih]."_".$thn_pilih.".xls");
header("Pragma: no-cache");
header("Expires: 0");


if($bln_pilih === "02")
{
    $tgl_last = 29;
    
    $check_tgl = checkdate($bln_pilih, $tgl_last, $thn_pilih);
    if($check_tgl === FALSE)
    {
        $tgl_last = 28;
    }
    else
    {
        $tgl_last = 29;
    }    
}
else
{
    $tgl_last = 31;
    
    $check_tgl = checkdate($bln_pilih, $tgl_last, $thn_pilih);
    if($check_tgl === FALSE)
    {
        $tgl_last = 30;
    }
    else
    {
        $tgl_last = 31;
    }    
}
?>

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
        <td colspan="5" align="center"><b>Presensi Bulan <?php echo $arr_bulan[$bln_pilih]; ?> Tahun <?php echo $thn_pilih; ?></b></td>
    </tr>
        <td></td>
    </tr>
</table>

<table border="1">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Cabang</th>
            <th>IP</th>
            <th>Jumlah Jam Kerja</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for($i=1; $i <= $tgl_last; $i++)
        {
            $tgl = (strlen($i)==1) ? "0".$i : $i;
            $tanggal_sekarang = $thn_pilih."-".$bln_pilih."-".$tgl;
            
            $timestamp = strtotime($tanggal_sekarang);
            $day = date('D',$timestamp);
            $dayArr = array('Mon'=>'Senin','Tue'=>'Selasa','Wed'=>'Rabu','Thu'=>'Kamis','Fri'=>'Jum\'at','Sat'=>'Sabtu', 'Sun'=>'Minggu');
            
            $absen_pegawai = $this->lpm->get_selected_absen($txt_npp, $tanggal_sekarang);
            
            $cabang_ket = $absen_pegawai === FALSE ? "" : $absen_pegawai->cabang_ket;
            $absensi_ip = $absen_pegawai === FALSE ? "" : $absen_pegawai->absensi_ip;
            $jam_masuk = ($absen_pegawai === FALSE) ? "00:00" : date("H:i", strtotime($absen_pegawai->JamMasuk));
            $jam_keluar = ($absen_pegawai === FALSE) ? "00:00" : date("H:i", strtotime($absen_pegawai->JamKeluar));
            
            ($jam_masuk === "00:00") ? list($jamm, $menitm)=array("00","00") : list($jamm, $menitm)=explode(":",$jam_masuk);
            ($jam_keluar === "00:00") ? list($jamk, $menitk)=array("00","00") : list($jamk, $menitk)=explode(":",$jam_keluar);
            
            if($menitk >= $menitm )
            {
                $selisih_jam = $jamk - $jamm;
                $selisih_menit = $menitk-$menitm;
            }
            else
            {
                $selisih_jam = ($jamk-1) - $jamm;
                $selisih_menit = ($menitk+60)-$menitm;
            }
            ?>
            <tr>
                <td><?php echo date("d-F-Y", strtotime($tanggal_sekarang)); ?></td>
                <td><?php echo $dayArr[$day]; ?></td>
                <td><?php echo $jam_masuk; ?></td>
                <td><?php echo $jam_keluar; ?></td>
                <td><?php echo $cabang_ket; ?></td>
                <td><?php echo $absensi_ip; ?></td>
                <td><?php echo $selisih_jam." Jam, ".$selisih_menit." Menit"; ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>