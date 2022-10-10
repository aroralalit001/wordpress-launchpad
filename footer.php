<?php
if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e('Blog Sidebar', 'twentyseventeen');?>">
    <?php dynamic_sidebar('sidebar-11');?>
</aside>

<div class="finaldiv">
	 <a href="<?php echo get_theme_mod('lwp_btn_color') ?>"  target="blank">
	  <div id="instagram"></div>
	 </a>
	 <a href=" <?php echo get_theme_mod('fburl') ?>" target="blank" >
	 <div id="facebook" class="facebook"></div>
	  </a>
	 <a href=" <?php echo get_theme_mod('twitter') ?>" target="blank" > 
	 <div id="twitter" class="twitter"> </div> 
	 </a>
	 <a href="<?php echo get_theme_mod('linkdin') ?>" target="blank" >
	  <div id="linkdin" class="lindin"> </div> 
	 </a>
	 </div>



