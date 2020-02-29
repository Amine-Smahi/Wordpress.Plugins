<style type="text/css">
    .Brute-Force-Protector .status-yes {
        color:#27ae60;
    }
    .Brute-Force-Protector .status-no {
        color:#cd3d2e;
    }
    .Brute-Force-Protector .postbox-footer {
        padding:10px;
        clear:both;
        border-top:1px solid #ddd;
        background:#f5f5f5;
    }
    .Brute-Force-Protector input[type="number"] {
        width:60px;
    }
    .Brute-Force-Protector tr.even {
        background-color:#f5f5f5;
    }
</style>

<script type="text/javascript">
    function ResetOptions() {
        if (confirm("<?php _e('Are you sure you want to reset all options?', 'Brute-Force-Protector'); ?>")) {
            document.forms["reset_form"].submit();
        }
    }

    function WhitelistCurrentIP() {
        document.forms["whitelist_current_ip_form"].submit();
    }
</script>

<div class="wrap Brute-Force-Protector">
    <h2><?php _e('Brute Force Login Protection Settings', 'Brute-Force-Protector'); ?></h2>

    <div class="metabox-holder">
        <div class="postbox">
            <?php $status = $this->__htaccess->checkRequirements(); ?>
            <h3>
                <?php _e('Status', 'Brute-Force-Protector'); ?>
                <?php if (in_array(false, $status)): ?>
                    <span class="dashicons dashicons-no status-no"></span><small class="status-no"><?php _e('You are not protected!', 'Brute-Force-Protector'); ?></small>
                <?php else: ?>
                    <span class="dashicons dashicons-yes status-yes"></span><small class="status-yes"><?php _e('You are protected!', 'Brute-Force-Protector'); ?></small>
                <?php endif; ?>
            </h3>
            <div class="inside">
                <?php if ($status['found']): ?>
                    <span class="dashicons dashicons-yes status-yes"></span> <strong><?php _e('.htaccess file found', 'Brute-Force-Protector'); ?></strong>
                <?php else: ?>
                    <span class="dashicons dashicons-no status-no"></span> <strong><?php _e('.htaccess file not found', 'Brute-Force-Protector'); ?></strong>
                <?php endif; ?>
                <br />
                <?php if ($status['readable']): ?>
                    <span class="dashicons dashicons-yes status-yes"></span> <strong><?php _e('.htaccess file readable', 'Brute-Force-Protector'); ?></strong>
                <?php else: ?>
                    <span class="dashicons dashicons-no status-no"></span> <strong><?php _e('.htaccess file not readable', 'Brute-Force-Protector'); ?></strong>
                <?php endif; ?>
                <br />
                <?php if ($status['writeable']): ?>
                    <span class="dashicons dashicons-yes status-yes"></span> <strong><?php _e('.htaccess file writeable', 'Brute-Force-Protector'); ?></strong>
                <?php else: ?>
                    <span class="dashicons dashicons-no status-no"></span> <strong><?php _e('.htaccess file not writeable', 'Brute-Force-Protector'); ?></strong>
                <?php endif; ?>
            </div>
        </div>

        <div class="postbox">
            <h3><?php _e('Options', 'Brute-Force-Protector'); ?></h3>
            <form method="post" action="options.php"> 
                <?php settings_fields('Brute-Force-Protector'); ?>
                <div class="inside">
                    <p><strong><?php _e('Allowed login attempts before blocking IP', 'Brute-Force-Protector'); ?></strong></p>
                    <p><input type="number" min="1" max="100" name="bflp_allowed_attempts" value="<?php echo $this->__options['allowed_attempts']; ?>" /></p>

                    <p><strong><?php _e('Minutes before resetting login attempts count', 'Brute-Force-Protector'); ?></strong></p>
                    <p><input type="number" min="1" name="bflp_reset_time" value="<?php echo $this->__options['reset_time']; ?>" /></p>

                    <p><strong><?php _e('Delay in seconds when a login attempt has failed (to slow down brute force attack)', 'Brute-Force-Protector'); ?></strong></p>
                    <p><input type="number" min="1" max="10" name="bflp_login_failed_delay" value="<?php echo $this->__options['login_failed_delay']; ?>" /></p>

                    <p><strong><?php _e('Inform user about remaining login attempts on login page', 'Brute-Force-Protector'); ?></strong></p>
                    <p><input type="checkbox" name="bflp_inform_user" value="true" <?php echo ($this->__options['inform_user']) ? 'checked' : ''; ?> /></p>

                    <p><strong><?php _e('Send email to administrator when an IP has been blocked', 'Brute-Force-Protector'); ?></strong></p>
                    <p><input type="checkbox" name="bflp_send_email" value="true" <?php echo ($this->__options['send_email']) ? 'checked' : ''; ?> /></p>

                    <p><strong><?php _e('Message to show to blocked users (leave empty for default message)', 'Brute-Force-Protector'); ?></strong></p>
                    <p><input type="text" size="70" name="bflp_403_message" value="<?php echo $this->__options['403_message']; ?>" /></p>

                    <p><strong><?php _e('.htaccess file location', 'Brute-Force-Protector'); ?></strong></p>
                    <p><input type="text" size="70" name="bflp_htaccess_dir" value="<?php echo $this->__options['htaccess_dir']; ?>" /></p>
                </div>
                <div class="postbox-footer">
                    <?php submit_button(__('Save', 'Brute-Force-Protector'), 'primary', 'submit', false); ?>&nbsp;
                    <a href="javascript:ResetOptions()" class="button"><?php _e('Reset', 'Brute-Force-Protector'); ?></a>
                </div>
            </form>
        </div>
    </div>

    <h3><?php _e('Blocked IPs', 'Brute-Force-Protector'); ?></h3>
    <table class="wp-list-table widefat fixed">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="30%"><?php _e('Address', 'Brute-Force-Protector'); ?></th>
                <th width="65%"><?php _e('Actions', 'Brute-Force-Protector'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($this->__htaccess->getDeniedIPs() as $deniedIP):
                ?>
                <tr <?php echo ($i % 2 == 0) ? 'class="even"' : ''; ?>>
                    <td><?php echo $i; ?></td>
                    <td><strong><?php echo $deniedIP ?></strong></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="IP" value="<?php echo $deniedIP ?>" />
                            <input type="submit" name="unblock" value="<?php echo __('Unblock', 'Brute-Force-Protector'); ?>" class="button" />
                        </form>
                    </td>
                </tr>
                <?php
                $i++;
            endforeach;
            ?>
            <tr <?php echo ($i % 2 == 0) ? 'class="even"' : ''; ?>>
                <td><?php echo $i; ?></td>
        <form method="post" action="">
            <td>
                <input type="text" name="IP" placeholder="<?php _e('IP to block', 'Brute-Force-Protector'); ?>" required />
            </td>
            <td>
                <input type="submit" name="block" value="<?php _e('Manually block IP', 'Brute-Force-Protector'); ?>" class="button button-primary" />
            </td>
        </form>
        </tr>
        </tbody>
    </table>

    <h3><?php _e('Whitelisted IPs', 'Brute-Force-Protector'); ?></h3>
    <table class="wp-list-table widefat fixed">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="30%"><?php _e('Address', 'Brute-Force-Protector'); ?></th>
                <th width="65%"><?php _e('Actions', 'Brute-Force-Protector'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $currentIP = $this->__getClientIP();

            $i = 1;
            $whitelist = $this->__getWhitelist();
            foreach ($whitelist as $whitelistedIP):
                ?>
                <tr <?php echo ($i % 2 == 0) ? 'class="even"' : ''; ?>>
                    <td><?php echo $i; ?></td>
                    <td><strong><?php echo $whitelistedIP ?></strong></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="IP" value="<?php echo $whitelistedIP ?>" />
                            <input type="submit" name="unwhitelist" value="<?php echo __('Remove from whitelist', 'Brute-Force-Protector'); ?>" class="button" />
                        </form>
                    </td>
                </tr>
                <?php
                $i++;
            endforeach;
            ?>
            <tr <?php echo ($i % 2 == 0) ? 'class="even"' : ''; ?>>
                <td><?php echo $i; ?></td>
        <form method="post" action="">
            <td>
                <input type="text" name="IP" placeholder="<?php _e('IP to whitelist', 'Brute-Force-Protector'); ?>" required />
            </td>
            <td>
                <input type="submit" name="whitelist" value="<?php _e('Add to whitelist', 'Brute-Force-Protector'); ?>" class="button button-primary" />
                <?php if (!in_array($currentIP, $whitelist)): ?>
                    &nbsp;<a href="javascript:WhitelistCurrentIP()" class="button"><?php printf(__('Whitelist my current IP (%s)', 'Brute-Force-Protector'), $currentIP); ?></a>
                <?php endif; ?>
            </td>
        </form>
        </tr>
        </tbody>
    </table>

    <form id="reset_form" method="post" action="">
        <input type="hidden" name="reset" value="true" />
    </form>

    <form id="whitelist_current_ip_form" method="post" action="">
        <input type="hidden" name="whitelist" value="true" />
        <input type="hidden" name="IP" value="<?php echo $currentIP; ?>" />
    </form>
</div>