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
.video-clips .aivp-card .aivp-thumbnail {
	min-height: 188px;
	border-bottom: solid 1px #444;
}
.video-clips .aivp-card h2{
	padding: 6px 8px;
	font-size: 14px;
	color: #162955;
}
.video-clips .aivp-card p{
	padding: 0px 15px;
}
.video-clips .aivp-card:hover{
	color: royalblue;
}
.video-clips .aivp-card img{
	width: 100%;
	height: 188px;
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
			if( get_aivp_option( 'platform' ) == 'vimeo' ) {
			   $args = array(
					'post_type' => 'aivp',
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'meta_key'  => 'created-date',
					'orderby'   => 'meta_value_num',
					'order'     => 'DESC',
			   );
			} else {
				$args = array(
					'post_type' => 'aivp',
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'orderby'   => 'date',
					'order'     => 'DESC',
			   );
			}
			   
			   $videos = new WP_Query( $args );
			   
			   if( $videos->have_posts() ) :
			   		while( $videos->have_posts() ) : $videos->the_post();
			?>
						<div class="aivp-column">
							<a class="aivp-card" href="<?php echo the_permalink(); ?>">
								<div class="aivp-thumbnail">
									<img src="<?php echo get_post_meta(get_the_ID(), 'thumbnail-url', true); ?>" alt="<?php echo the_title(); ?>">
								</div>
								<h2><b><?php echo the_title(); ?></b></h2>
								<?php echo the_excerpt(  ); ?>
							</a>
						</div>
			   <?php endwhile;
				endif;
				?>

		</div>
		
	</div>
			
<?php get_footer(); ?>