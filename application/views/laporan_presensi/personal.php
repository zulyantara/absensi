<?php
$arr_bulan = array("01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember");

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

<form class="uk-form" method="post" action="<?php echo base_url("laporan_presensi/personal_presensi"); ?>">
    <select name="opt_npp">
        <?php
        foreach($res_karyawan as $row_karyawan)
        {
            ?>
            <option value="<?php echo $row_karyawan->karyawan_npp; ?>"><?php echo $row_karyawan->karyawan_nama; ?></option>
            <?php
        }
        ?>
    </select>
    <select name="opt_bulan">
        <?php
        foreach($arr_bulan as $key=>$row_bulan)
        {
            $selected_bln = (date("m") == $key) ? "selected=\"selected\"" : "";
            ?>
            <option <?php echo $selected_bln; ?> value="<?php echo $key; ?>"><?php echo $row_bulan; ?></option>
            <?php
        }
        ?>
    </select>
    <select name="opt_tahun">
        <?php
        for($thn = date("Y")-5; $thn <= date("Y")+5; $thn++)
        {
            $selected_thn = (date("Y") == $thn) ? "selected=\"selected\"" : "";
            ?>
            <option value="<?php echo $thn; ?>" <?php echo $selected_thn; ?>><?php echo $thn; ?></option>
            <?php
        }
        ?>
    </select>
    <button class="uk-button uk-button-primary">Tampilkan</button>
</form>
<br>
<?php
if($res_biodata != "" AND isset($res_biodata))
{
    ?>
    <article class="uk-comment uk-comment-primary">
        <header class="uk-comment-header">
            <img class="uk-comment-avatar" src="<?php echo base_url("assets/img/placeholder_avatar.svg"); ?>" alt="">
            <h4 class="uk-comment-title"><?php echo $res_biodata->karyawan_nama; ?></h4>
            <div class="uk-comment-meta"><?php echo $res_biodata->jabatan_ket; ?> [<?php echo $res_biodata->divisi_ket; ?>] | <?php echo $res_biodata->cabang_ket; ?></div>
        </header>
    </article>

    <form class="uk-form uk-margin-bottom" method="post" action="<?php echo base_url("laporan_presensi/cetak_personal_excel"); ?>">
        <input type="hidden" id="prd1" name="prd1" value="<?php echo $bln_pilih; ?>">
        <input type="hidden" id="prd2" name="prd2" value="<?php echo $thn_pilih; ?>">
        <input type="hidden" id="Npp" name="Npp" value="<?php echo $opt_npp; ?>">
        <button type="submit" id="cetak_excel" name="cetak_excel" class="uk-button uk-button-success">Cetak Spreadsheet</button>
    </form>

    <div class="uk-overflow-container">
        <table class="uk-table uk-table-hover uk-table-striped uk-table-condensed uk-margin-bottom uk-text-nowrap">
            <caption>Laporan Presensi Bulan <?php echo $arr_bulan[$bln_pilih]; ?> Tahun <?php echo $thn_pilih; ?></caption>
            <thead class="tara-thead">
                <tr>
                    <th style="width: 10px;">Tanggal</th>
                    <th>Hari</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Cabang</th>
                    <th>IP</th>
                    <th>Jumlah Jam Kerja</th>
                    <th>Proses</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                for($i=1; $i <= $tgl_last; $i++)
                {
                    $tgl = (strlen($i)==1) ? "0".$i : $i;
                    $tanggal_sekarang = $thn_pilih."-".$bln_pilih."-".$tgl;
                    $tgl_format_php = date("Y-m-d", strtotime($tanggal_sekarang));

                    $timestamp = strtotime($tanggal_sekarang);
                    $day = date('D',$timestamp);
                    $dayArr = array('Mon'=>'Senin','Tue'=>'Selasa','Wed'=>'Rabu','Thu'=>'Kamis','Fri'=>'Jum\'at','Sat'=>'Sabtu', 'Sun'=>'Minggu');

                    $absen_pegawai = $this->lpm->get_selected_absen($opt_npp, $tanggal_sekarang);
                    $jam_msk_pegawai = $this->lpm->get_selected_absen($opt_npp, $tanggal_sekarang);

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

                    echo $total = $total+$selisih_menit;

                    $jml_jam_kerja = 8;
                    if($opt_npp === "02.102013.0123")
                    {
                        $jml_jam_kerja = 9;
                    }
                    elseif($dayArr[$day] === "Sabtu")
                    {
                        $jml_jam_kerja = 6;
                    }

                    $disabled = ($selisih_jam === 0 && $selisih_menit === 0 OR $selisih_jam < $jml_jam_kerja) ? "disabled=\"disabled\"" : "";
                    ?>
                    <tr>
                        <td><?php echo date("d-F-Y", strtotime($tanggal_sekarang)); ?></td>
                        <td><?php echo $dayArr[$day]; ?></td>
                        <td><?php echo $jam_masuk; ?></td>
                        <td><?php echo $jam_keluar; ?></td>
                        <td><?php echo $cabang_ket; ?></td>
                        <td><?php echo $absensi_ip; ?></td>
                        <td><?php echo $selisih_jam." Jam, ".$selisih_menit." Menit"; ?></td>
                        <td>
                            <button type="button" name="btn_lembur" id="btn_lembur" value="lembur" class="uk-button uk-button-primary" onclick="get_lembur('<?php echo $opt_npp; ?>','<?php echo $tgl_format_php; ?>','<?php echo $selisih_jam.":".$selisih_menit; ?>')" <?php echo $disabled; ?>>Lembur</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="8">
                        <form class="uk-form"method="post" action="<?php echo base_url("laporan_presensi/cetak_personal_excel"); ?>">
                            <input type="hidden" id="prd1" name="prd1" value="<?php echo $bln_pilih; ?>">
                            <input type="hidden" id="prd2" name="prd2" value="<?php echo $thn_pilih; ?>">
                            <input type="hidden" id="Npp" name="Npp" value="<?php echo $opt_npp; ?>">
                            <button type="submit" id="cetak_excel" name="cetak_excel" class="uk-button uk-button-success">Cetak Spreadsheet</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
?>

<script type="text/javascript">
    function get_lembur(npp, tgl, jam)
    {
        var str_bu = '/'+npp+'/'+tgl+'/'+jam
        window.location.href='<?php echo base_url("index.php/lembur/index"); ?>'+str_bu;
    }
</script>
