<?php 
    $api_key = get_option('gs.api_key');
    $api_secret = get_option('gs_api_secret');
    
    $user_name = get_option('gs.api_user_name');
    $user_nick = get_option('gs.api_user_nick');
    $canonical_name = get_option('gs.api_canonical_name');
    $avatar_url = get_option('gs.api_avatar_url');
?>

<?php if (array_key_exists('updated', $_GET)) : ?>
<div id="message" class="updated fade">
	<p><?php _e('Get Satisfaction API Access Authorized'); ?>.</p>
</div>
<?php endif; ?>

<div class="wrap">
	<a href="http://getsatisfaction.com"><img src="<?php echo get_bloginfo('wpurl') .'/wp-content/plugins/get-satisfaction-for-wordpress/images/getsatisfaction.gif';?>" alt="Get Satisfaction"></a>
	<h2>
	    <?php _e('API Keys Settings'); ?>
	</h2>

	
	<form method="post">
		<table class="form-table">
			<tr valign="top">
				<td>
                    <label for="api_key" class="selectit"><?php _e('API Key') ?></label>
				</td>
				<td>
                    <input type='text'  size="30" name="api_key" id="api_key" value="<?php echo $api_key;?>"></input>
				</td>
			</tr>
			<tr valign="top">
				<td>
                    <label for="api_secret" class="selectit"><?php _e('API Secret') ?></label>
				</td>
				<td>
                    <input type='text'  size="30" name="api_secret" id="api_secret" value="<?php echo $api_secret;?>"></input>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="submit" class="button-primary" value="<?php _e('Authorize API Access'); ?>" />
		</p>
		<a href="#api-info">How do I get an API Key?</a>
	</form>
</div>

<div class="wrap">
	<h2>API User Information</h2>
	<table class="form-table">
		<tr valign="top">
			<td>
                <label class="selectit"><?php _e('Username') ?></label>
			</td>
			<td>
				<?php if (!empty($user_name)) :?>
                <label class="selectit"><?php echo $user_name;?></label>
		        <?php endif; ?>
			</td>
		</tr>
		<tr valign="top">
			<td>
                <label class="selectit"><?php _e('Nickname') ?></label>
			</td>
			<td>
				<?php if (!empty($user_nick)) :?>
                <label class="selectit"><?php echo $user_nick;?></label>
		        <?php endif; ?>
			</td>
		</tr>
		<tr valign="top">
			<td>
                <label class="selectit"><?php _e('Canonical Name') ?></label>
			</td>
			<td>
				<?php if (!empty($canonical_name)) :?>
                <label class="selectit"><?php echo $canonical_name;?></label>
                <?php endif; ?>
			</td>
		</tr>
		<tr valign="top">
			<td>
                <label class="selectit"><?php _e('Avatar Image') ?></label>
			</td>
			<td>
				<?php if (!empty($avatar_url)) :?>
                <img src="<?php echo $avatar_url;?>"></img>
		        <?php endif; ?>
			</td>
		</tr>
	</table>
</div>

<div class="wrap" id="api-info">
	<h2>
	    <?php _e('GetSatisfaction API Keys'); ?>
	</h2>
	<p>
		Get Satisfaction has an open API which let you access all our public data, and it also lets you and your users add data to Get Satisfaction.
	</p>

	<p>
		The API is simple, clean, RESTful, and it should let you replicate almost all the functionality of Get Satisfaction.
	</p>

	<p>
		<strong>How do I get one?</strong>
	</p>
	
	<p>
		Only customers of the Integrate Classic and Customize plans have access to an API key. The key can be found in the company's Admin area.
	</p>
	
	<p>
		When you <a href="http://getsatisfaction.com/login">sign up for a GetSatisfaction account</a> after the confirmation process you can go to your user account settings and setup the API Services.
	</p>

	<p>
		<a href="http://getsatisfaction.com"><img src="<?php echo get_bloginfo('wpurl') .'/wp-content/plugins/get-satisfaction-for-wordpress/images/gs-api-screen.jpg';?>" alt="API Services Screen"></a>
	</p>

	<p>		
		If you already have a GetSatisfaction account, your API key is listed on your Account Settings page under API Services, 
	</p>

	<p>		
		which you can get to by clicking the “Account Settings” link in user dashboard when you’re logged in.
	</p>

	<p>
		<strong>Can I share my API key?</strong>
	</p>
	<p><strong>No</strong>, it’s like a password to your account.</p>
</div>	