<?php get_header(); ?>
<style>
.aivp-container{
	max-width: 960px;
	background-color: white;
	display: block;
	margin: auto;
	padding: 30px;
}
/** -----------------Do not copy */

.video-clips .aivp-row{
	display: flex;
	flex-wrap: wrap;
}
.video-clips .aivp-column{
	-webkit-box-flex: 0;
	-ms-flex: 0 0 33.333333%;
	flex: 0 0 33.333333%;
	max-width: 33.333333%;
	padding-bottom: 20px;
}
.video-clips .aivp-card{
	margin: 10px;
	display: block;
	text-decoration: none;
	color: #000;
	transition: 0.2s;
	box-shadow: 0 0 3px #CACACA;
	height: 100%;
}
.video-clips .aivp-card p{
	padding: 0px 15px;
}
.video-clips .aivp-card:hover{
	color: royalblue;
}
.video-clips .aivp-card img{
	width: 100%;
	display: block;
}

@media(max-width: 900px){

	.video-clips .aivp-column{
		-ms-flex: 0 0 50%;
		flex: 0 0 50%;
		max-width: 50%;
	}
}
</style>
	<div class="video-clips">
		<h2 class="">Video Clips</h2>
		<div class="aivp-row">
			<?php
			   $args = array(
				   'post_type' => 'aivp',
				   'posts_per_page' => -1,
				   'post_status' => 'publish',
				   'orderby' => 'date',
				   'order' => 'desc'
			   );
			   
			   $videos = new WP_Query( $args );
			   
			   if( $videos->have_posts() ) :
			   		while( $videos->have_posts() ) : $videos->the_post();
			?>
						<div class="aivp-column">
							<a class="aivp-card" href="<?php echo the_permalink(); ?>">
								<img src="<?php echo get_post_meta(get_the_ID(), 'thumbnail-url', true); ?>" alt="<?php echo the_title(); ?>">
								<p><b><?php echo the_title(); ?> </b> - <span><?php echo strip_tags(get_the_content(), '<p>'); ?></span></p>
							</a>
						</div>
			   <?php endwhile;
				endif;
				?>

		</div>
		
	</div>
			
<?php get_footer(); ?>