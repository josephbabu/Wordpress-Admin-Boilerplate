<?php
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	die;
}

?>

<div class="wrap about-wrap">
	<h1><?php _e("Welcome to SideHelper plugin", sidehelper::$plugin_slug); ?></h1>

	<div class="about-text"><?php _e("Plugin will help you to create a grid layout of images easily and simple", sidehelper::$plugin_slug); ?></div>
	<div class="sg-badge"><?php print sidehelper::$version; ?></div>

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab nav-tab-active"
		   href="<?php echo esc_url(admin_url(add_query_arg(array('page' => 'sh-welcome'), 'index.php'))); ?>#welcome">
			<?php _e("SideHelper description", sidehelper::$plugin_slug); ?>
		</a>
		<a class="nav-tab"
		   href="<?php echo esc_url(admin_url(add_query_arg(array('page' => 'sh-welcome'), 'index.php'))); ?>#about">
			<?php _e("SideHelper description", sidehelper::$plugin_slug); ?>
		</a>
	</h2>

	<p class="about-description"><?php _e('Use the tips below to get started using Ninja Forms. You will be up and running in no time!', 'ninja-forms'); ?></p>

	<div class="changelog">

		<div class="feature-section col three-col">
			<div class="col-1">

				<h4>
					<?php _e("Easy to install", sidehelper::$plugin_slug); ?>
				</h4>

				<p><span style="width: 44px; height: 44px;"
						 src="#"
						 class="sg-welcome-feature-one"></span><?php _e('This is where you\'ll build your form by adding fields and dragging them into the order you want them to appear. Each field will have an assortment of options such as label, label position, and placeholder.', 'ninja-forms'); ?>
					<br /><br />
					<img src="https://placekitten.com/g/300/300" />
				</p>

				<h4>
					<?php _e("Easy to manage", sidehelper::$plugin_slug); ?>
				</h4>

				<p><span style="width: 44px; height: 44px;"
						 src="#"
						 class="sg-welcome-feature-two"></span><?php _e('If you would like for your form to notify you via email when a user clicks submit, you can set those up on this tab. You can create an unlimited number of emails, including emails sent to the user who filled out the form.', 'ninja-forms'); ?>
					<br /><br />
					<img src="https://placekitten.com/g/300/300" />
				</p>

				<h4>
					<?php _e("Powerful options", sidehelper::$plugin_slug); ?>
				</h4>

				<p><span style="width: 44px; height: 44px;"
						src="#"
						class="sg-welcome-feature-three"></span><?php _e('This tab hold general form settings, such as title and submission method, as well as display settings like hiding a form when it is successfully completed.', 'ninja-forms'); ?></p>

			</div>

			<div class="col-2 last-feature">
				<h4>
					<?php _e("It's all about simpleness", sidehelper::$plugin_slug); ?>
				</h4>

				<p><?php printf(__('Place %s in any area that accepts shortcodes to display your form anywhere you like. Even in the middle of your page or posts content.', 'ninja-forms'), '[ninja_form id=1]'); ?></p>

				<iframe src="https://player.vimeo.com/video/113394646" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen class="sg-welcome-video"></iframe>
			</div>

		</div>

	</div>

	<hr/>

	<div style="text-align: center;width: 100%;margin-top:10px;">

		<a href="http://nulllogic.net/" class="logo"></a>

	</div>
</div>
