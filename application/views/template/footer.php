                            <!-- end panel -->
                            </div>
                        <!-- end kanan -->
                        </div>
                    <!-- end uk-grid -->
                    </div>
                <!-- end uk-container -->
                </div>
            <!-- end tara-middle -->
            </div>
            <!--
            <div class="tara-footer">
                <div class="uk-container uk-container-center uk-text-center">
                    <div class="uk-panel">
                        <p><a href="http://www.bintangpelajar.net"><?php echo APP_FOOTER; ?></a></p>
                    </div>
                </div>
            </div>
            -->
        </div>
        <!-- end tara-wrapper -->
        
        <!-- offcanvas -->
        <div class="uk-offcanvas" id="tm-offcanvas">
            <div class="uk-offcanvas-bar">
                <ul data-uk-nav="{multiple:true}" class="uk-nav uk-nav-offcanvas uk-nav-parent-icon">
                    <li><a href="<?php echo base_url("jadwal_masuk"); ?>">Jadwal Masuk</a></li>
                    <li><a href="<?php echo base_url("rekap_presensi"); ?>">Rekap Presensi</a></li>
                </ul>
            </div>
        </div>
        <!-- end offcanvas -->
        
        <script type="text/javascript">
        $(document).ready(function() {
            <?php
            if(isset($li_id))
            {
                ?>
                $("#<?php echo $li_id; ?>").addClass("uk-active");
                <?php
            }
            ?>
            
            <?php
            if($this->session->userdata("isPresensi") === TRUE)
            {
                ?>
                setInterval(function(){
                    $("#jam").load('<?php echo base_url("home/jam"); ?>');
                }, 1000);
                <?php
            }
            ?>
        });
        
        <!--
        var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth();
        var thisDay = date.getDay(),
            thisDay = myDays[thisDay];
        var yy = date.getYear();
        var year = (yy < 1000) ? yy + 1900 : yy;
        document.getElementById('tgl').innerHTML = thisDay + ', ' + day + ' ' + months[month] + ' ' + year;
        //-->
        <?php
        if($this->session->userdata("isPresensi")===TRUE)
        {
            ?>
            setInterval("my_function();",100000);
            
            function my_function(){
                window.location = location.href;
            }
            <?php
        }
        ?>
        </script>
    </body>
</html>