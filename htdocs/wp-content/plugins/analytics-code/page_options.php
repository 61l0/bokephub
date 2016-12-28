<?php
	$ga_tc_activated = false;
	$ga_tc_deactivated = false;

    $error = '';

    $ga_tc_code = get_option('ga_tc_code', '');
    $ga_tc_id = get_option( 'ga_tc_id', '' );
    $ga_tc_type = get_option( 'ga_tc_type', 'id' );


    if((mb_strtolower($_SERVER['REQUEST_METHOD']) == 'post')) {
        check_admin_referer( GA_TC_PLUGIN_NAME );
    }


    if (isset($_POST['ga_tc_deactivate'])) {
        update_option( 'ga_tc_id', '' );
        update_option( 'ga_tc_code', '' );
        update_option( 'ga_tc_type', 'id' );
        $ga_tc_deactivated = true;;
    }


	if (isset($_POST['ga_tc_submit_id']) && isset( $_POST['ga_tc_id'] )) {
		$ga_tc_id = trim(filter_input( INPUT_POST, 'ga_tc_id', FILTER_SANITIZE_STRING ));
        $ga_tc_type = 'id';

        if (empty($ga_tc_id) || preg_match( "/^UA\-[0-9]+\-[0-9]+$/i", $ga_tc_id)) {
            update_option('ga_tc_id', $ga_tc_id);
            update_option('ga_tc_type', 'id');
            if (empty($ga_tc_id)) {
                $ga_tc_deactivated = true;
            } else {
                $ga_tc_activated = true;
            }
        } else {
            $error  = 'The Google Analytics Tracking ID is incorrect';
        }
	}

    if (isset($_POST['ga_tc_submit_code']) && isset($_POST['ga_tc_code'])) {
        $ga_tc_code = trim((strval($_POST['ga_tc_code'])));

        $regex = "/^\<script(.*?)?\>(.|\\n)*?\<\/script\>$/i";
        $ga_tc_type = 'code';

        if (empty($ga_tc_code) || preg_match( $regex, $ga_tc_code)) {
            update_option('ga_tc_code', $ga_tc_code);
            update_option('ga_tc_type', 'code');

            if (empty($ga_tc_code)) {
                $ga_tc_deactivated = true;
            } else {
                $ga_tc_activated = true;
            }
        } else {
            $error  = 'The Google Analytics Code is incorrect';
        }
    }

?>

<div class="postbox-container">
	<h1><?php echo GA_TC_TITLE; ?></h1>

	<?php if($ga_tc_activated): ?>
		<div id="message" class="updated notice" style="margin-left: 0px; margin-right: 0px; background-color: #d6ffcb">
			<p>Google analitycs tracking code <strong>activated</strong>.</p>
		</div>
	<?php endif; ?>
	
	<?php if($ga_tc_deactivated): ?>
		<div id="message" class="updated error" style="margin-left: 0px; margin-right: 0px; background-color: #fff8cc">
			<p>Google analitycs tracking code <strong>deactivated</strong>.</p>
		</div>
	<?php endif; ?>
	
	<?php if(!empty($error)): ?>
		<div id="message" class="updated error" style="font-weight: bold; color: red; margin-left: 0px; margin-right: 0px; background-color: #fff8cc">
			<p><?php echo $error;?></p>
		</div>
	<?php endif; ?>

	<div id="ga_tc_id_options" <?php if ($ga_tc_type == 'code') {echo 'style="display: none"';}?>>
		<div class="postbox">
			<div style="padding-left: 10px; border-bottom: 1px solid #e5e5e5">
				<h2>Automatically generate Google Analytics Code</h2>
			</div>
			<div style="padding: 10px;">
			    <form method="post">
                    <?php wp_nonce_field( GA_TC_PLUGIN_NAME ); ?>
				    <table>
					    <tr>
						    <td>
							    <b>Please, past your Google Analytics Tracking ID here:</b>
						    </td>
						    <td>
							    <input type="text" name="ga_tc_id" value="<?php echo stripslashes($ga_tc_id);?>">
                                <input type="submit" name="ga_tc_submit_id" style="width: 100px;" value="Save">
                                <?php if($ga_tc_code || $ga_tc_id): ?>
                                    or <input type="submit" name="ga_tc_deactivate" style="font-size: smaller"  value="Deactivate tracking code">
                                <?php endif;?>

                            </td>
					    </tr>
					    <tr>
						    <td colspan="2">
                                <p>More information about tracking ID you can read on <a href="https://support.google.com/analytics/answer/1032385">Google Analytics support</a> pages.</p>
                                If you have not yet created a google analytics account for this site, <a href='https://analytics.google.com/analytics/web/#management/Settings//%3Fm.page%3DNewAccount/' class='btn btn-xs btn-success'>create it</a>.
						    </td>
					    </tr>
				    </table>
				</form>
			</div>
		</div>
		
		<a href="javascript: void(0)" onclick="ga_tc_showSettings('code')" style="margin-left: 15px;">Manually past Google Analytics Code</a>
	</div>
	
	<div id="ga_tc_code_options"  <?php if ($ga_tc_type == 'id') {echo 'style="display: none"';}?>>
		<div class="postbox">
			<div style="padding-left: 10px; border-bottom: 1px solid #e5e5e5">
				<h2>Manually past Google Analytics Code</h2>
			</div>
			<div style="padding: 10px;">
				<form method="post">
                    <?php wp_nonce_field( GA_TC_PLUGIN_NAME ); ?>
					<table>
						<tr>
							<td colspan="2"><b>Please, past your Google Analytics code here:</b></td>
						</tr>
						<tr>
							<td colspan="2">
							<textarea name="ga_tc_code" style="width: 627px; height: 210px;"><?php echo esc_textarea(stripslashes($ga_tc_code));?></textarea>
							</td>
							
						</tr>
						<tr>
                            <td>
                                <?php if($ga_tc_code || $ga_tc_id): ?>
                                    <input type="submit" name="ga_tc_deactivate" style="font-size: smaller"  value="Deactivate tracking code">
                                <?php endif;?>
                            </td>

							<td style="text-align: right;"><input type="submit" name="ga_tc_submit_code" value="Save" style="width: 100px;">

                            </td>
						</tr>
						<tr>
							<td>
								<p>More information about tracking code you can read on <a href="https://support.google.com/analytics/answer/1008080">Google Analytics support</a> pages.</p>
                                If you have not yet created a google analytics account for this site, <a href='https://analytics.google.com/analytics/web/#management/Settings//%3Fm.page%3DNewAccount/' class='btn btn-xs btn-success'>create it</a>.
							</td>
						</tr>
					</table>	
				</form>
			</div>	
		</div>
	
		<a href="javascript: void(0)" onclick="ga_tc_showSettings('id')"  style="margin-left: 15px;">Automatically generate Google Analytics Code</a>
	</div>
</div>	

<script type="text/javascript">
	function ga_tc_showSettings(type) {
		if (type == 'id') {
			document.getElementById('ga_tc_code_options').style.display = 'none';
			document.getElementById('ga_tc_id_options').style.display = '';
		} else {
			document.getElementById('ga_tc_code_options').style.display = '';
			document.getElementById('ga_tc_id_options').style.display = 'none';
		}
	} 
	
</script>
    



