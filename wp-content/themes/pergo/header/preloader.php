<?php 
$preloader_display = ot_get_option( 'preloader_display', 'default' );
if( $preloader_display != 'none' ):
	$custom_preloader = ot_get_option( 'custom_preloader', PERGO_URI . '/images/preloader.png' );
?>
<!-- PRELOADER
============================================= -->		
<div id="loader-wrapper">
	
		<?php if( $preloader_display == 'default' ): ?>
			<div id="loader"><div class="cssload-spin-box"></div></div>
		<?php endif; ?>

		<?php if( $preloader_display == 'custom' ): ?>
			<div class="preloader-img text-center"><img src="<?php echo esc_url($custom_preloader) ?>" alt=""></div>
		<?php endif; ?>
	
</div>
<?php endif; ?>