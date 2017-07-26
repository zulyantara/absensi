<form class="uk-form" method="post" action="<?php echo base_url("laporan_presensi/rekap_presensi"); ?>">
    <select name="opt_divisi" id="opt_divisi">
        <option value="">Pilih Divisi</option>
        <option value="0">Semua Divisi</option>
        <?php
        foreach($res_divisi as $row_divisi)
        {
            ?>
            <option value="<?php echo $row_divisi->divisi_id; ?>"><?php echo $row_divisi->divisi_ket; ?></option>
            <?php
        }
        ?>
    </select>
    <input type="text" name="txt_tgl_1" id="txt_tgl_1" data-uk-datepicker="{format:'YYYY-MM-DD'}" placeholder="Tanggal Awal">
    <input type="text" name="txt_tgl_2" id="txt_tgl_2" data-uk-datepicker="{format:'YYYY-MM-DD'}" placeholder="Tanggal Akhir">
    <button class="uk-button uk-button-primary" name="btn_tampilkan" id="btn_tampilkan" value="tampilkan">Tampilkan</button>
    <?php
    if(isset($res_karyawan) AND $res_karyawan == TRUE)
    {
        ?>
        <button class="uk-button uk-button-primary" name="btn_cetak_rekap" id="btn_cetak_rekap" value="cetak_excel_rekap">Cetak Excel</button>
        <?php
    }
    ?>
</form>
<br>

<div class="uk-overflow-container">
    <table class="cell-border uk-table-hover uk-table-striped uk-table-condensed uk-text-nowrap" id="my_table">
        <thead class="tara-thead">
            <tr>
                <th rowspan="2">NPP</th>
                <th rowspan="2" style="width: 350px;">Nama Karyawan</th>
                <th rowspan="2" style="width: 125px;">Jabatan</th>
                <th colspan="2" style="text-align: center;">Terlambat</th>
                <th rowspan="2" style="width: 45px;">Sakit</th>
                <th rowspan="2" style="width: 45px;">Izin</th>
                <th rowspan="2" style="width: 45px;">Izin Khusus</th>
                <th rowspan="2" style="width: 45px;">Cuti</th>
                <th rowspan="2" style="width: 45px;">Alpa</th>
                <th colspan="4" style="width: 45px;">Lembur</th>
            </tr>
            <tr>
                <th style="width: 45px;"><= 10</th>
                <th style="width: 45px;">>= 10</th>
                <th style="width: 45px;"><= 2</th>
                <th style="width: 45px;">>= 2</th>
                <th style="width: 45px;"><= 8</th>
                <th style="width: 45px;">>= 8</th>
            </tr>
        </thead>
        <tbody>
                <?php
                if(isset($res_karyawan) AND $res_karyawan == TRUE)
                {
                    foreach($res_karyawan as $row_karyawan)
                    {
                        $data["txt_npp"] = $row_karyawan->karyawan_npp;
                        $data['txt_tgl_1'] = $txt_tgl_1;
                        $data['txt_tgl_2'] = $txt_tgl_2;
                        
                        $res_rekap = $this->lpm->get_rekap_karyawan($data);
                        //mengambil waktu kerja karyawan
                        $row_wk = $this->lpm->get_wk_karyawan($data['txt_npp']);
                        
                        //menghitung izin
                        $row_izin = $this->lpm->count_izin($data,1);
                        
                        //menghitung izin khusus
                        $row_izin_khusus = $this->lpm->count_izin($data,2);
                        
                        //menghitung sakit
                        $row_sakit = $this->lpm->count_izin($data,4);
                        
                        //menghitung cuti
                        $row_cuti = $this->lpm->count_izin($data,3);
                        
                        //menghitung lembur
                        $lembur_krg_2 = 0;
                        $lembur_lbh_2 = 0;
                        $lembur_krg_8 = 0;
                        $lembur_lbh_8 = 0;
                        $qry_lembur = $this->lpm->count_lembur($data);
                        if($qry_lembur != FALSE)
                        {
                            foreach($qry_lembur as $row_lembur)
                            {
                                $jml_lembur = $row_lembur->kl_jml_lembur;
                            }
                        }
                        
                        //menghitung telat
                        $telat_kr_10 = 0;
                        $telat_lb_10 = 0;
                        if($res_rekap != "")
                        {
                            foreach($res_rekap as $row_rekap)
                            {
                                //echo "<pre>".var_dump($row_wk)."</pre>";
                                //menambahkan 1 menit dan 10 menit dari waktu kerja karyawan untuk parameter keterlambatan
                                $wk_1 = "09:01";
                                $wk_2 = "09:10";
                                if($row_wk === TRUE)
                                {
                                    $wk_lb_1 = substr($row_wk->wk_senin,3,2)+1;
                                    $wk_lb_10 = substr($row_wk->wk_senin,3,2)+10;
                                    $wk_1 = substr($row_wk->wk_senin,0,2).":0".$wk_lb_1;
                                    $wk_2 = substr($row_wk->wk_senin,0,2).":".$wk_lb_10;
                                }
                                //echo $row_rekap->absensi_npp." | ".$row_rekap->jam_masuk." ".$wk_2."<br>";
                                // menghitung jumlah telat =< 10 menit
                                if(substr($row_rekap->jam_masuk,0,5) >= $wk_1 and substr($row_rekap->jam_masuk,0,5) <= $wk_2)
                                {
                                    $telat_kr_10 = count($row_rekap)+$telat_kr_10;
                                }
                                
                                // menghitung jumlah telat > 10 menit
                                if(substr($row_rekap->jam_masuk,0,5) > "09:10")
                                {
                                    $telat_lb_10 = count($row_rekap)+$telat_lb_10;
                                }
                            }
                        }
                        ?>
                        <tr>
                            <td><?php echo $row_karyawan->karyawan_npp; ?></td>
                            <td><?php echo $row_karyawan->karyawan_nama; ?></td>
                            <td><?php echo $row_karyawan->jabatan_ket; ?></td>
                            <td class="uk-text-center"><?php echo $telat_kr_10; ?></td>
                            <td class="uk-text-center"><?php echo $telat_lb_10; ?></td>
                            <td class="uk-text-center"><?php echo $row_sakit->jml_izin; ?></td>
                            <td class="uk-text-center"><?php echo $row_izin->jml_izin; ?></td>
                            <td class="uk-text-center"><?php echo $row_izin_khusus->jml_izin; ?></td>
                            <td class="uk-text-center"><?php echo $row_cuti->jml_izin; ?></td>
                            <td class="uk-text-center">Alpa</td>
                            <td class="uk-text-center"><?php echo $lembur_krg_2; ?></td>
                            <td class="uk-text-center"><?php echo $lembur_lbh_2; ?></td>
                            <td class="uk-text-center"><?php echo $lembur_krg_8; ?></td>
                            <td class="uk-text-center"><?php echo $lembur_lbh_8; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#my_table').DataTable({});
    });
</script>