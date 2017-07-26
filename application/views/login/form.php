<?php
echo isset($message) ? "<div class=\"uk-alert uk-alert-danger\">".$message."</div>" : "";
?>
<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("auth/update_password"); ?>">
    <div class="uk-form-row">
        <label for="txt_old_password" class="uk-form-label">Password Lama</label>
        <div class="uk-form-controls">
            <input type="password" placeholder="Password Lama" id="txt_old_password" name="txt_old_password" value="<?php echo set_value('txt_old_password'); ?>" >
            <?php echo form_error('txt_old_password'); ?>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_new_password" class="uk-form-label">Password Baru</label>
        <div class="uk-form-controls">
            <input type="password" placeholder="Password Baru" id="txt_new_password" name="txt_new_password" value="<?php echo set_value('txt_new_password'); ?>" >
            <?php echo form_error('txt_new_password'); ?>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_confirm_password" class="uk-form-label">Konfirmasi Password</label>
        <div class="uk-form-controls">
            <input type="password" placeholder="Konfirmasi Password" id="txt_confirm_password" name="txt_confirm_password" value="<?php echo set_value('txt_confirm_password'); ?>" >
            <?php echo form_error('txt_confirm_password'); ?>
        </div>
    </div>
    <hr>
    <div class="uk-form-row">
        <div class="uk-form-controls">
            <button name="btn_simpan" value="btn_simpan" class="uk-button uk-button-primary">Simpan</button>
        </div>
    </div>
</form>