<?php
if($this->session->userdata("isPresensi")===TRUE)
{
    ?>
    <form class="uk-form" method="post" action="<?php echo base_url("home"); ?>">
        <input type="text" name="txt_npp" id="txt_npp" placeholder="NPP" autofocus="autofocus">
    </form>
    <hr>
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($sql_absensi as $row_absen)
            {
                $alert = "";
                $dayArr = array('Mon'=>'senin','Tue'=>'selasa','Wed'=>'rabu','Thu'=>'kamis','Fri'=>'jumat','Sat'=>'sabtu');
                $hari = "wk_".$dayArr[date('D')];
                foreach($sql_jam_masuk as $row_jam_masuk)
                {
                    if($row_jam_masuk->wk_npp === $row_absen->absensi_npp)
                    {
                        if($row_jam_masuk->$hari < $row_absen->JamMasuk)
                        {
                            $alert = "<div class=\"uk-badge uk-badge-danger uk-float-right\">Telat</div>";
                        }
                    }
                }
                ?>
                <tr>
                    <td><b>[<?php echo $row_absen->absensi_npp; ?>]</b> <?php echo $row_absen->karyawan_nama; ?> <?php echo $alert; ?></td>
                    <td><?php echo $row_absen->jabatan_ket; ?></td>
                    <td style="text-align: center;"><?php echo $row_absen->JamMasuk; ?></td>
                    <td style="text-align: center;"><?php echo $row_absen->JamKeluar; ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
else
{
    ?>
    Beranda
    <?php
}
?>