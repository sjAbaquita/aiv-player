<?php

get_header(); ?>
<style>
    .aivp-container{
        max-width: 960px;
        background-color: white;
        display: block;
        margin: auto;
        padding: 30px;
    }
    /** -----------------Do not copy */
    .video-container{
        max-width: 50%;
        width: 50%;
        text-align: right;
        transition: 0.2s;
        -webkit-box-flex: 0;
    }
    .video-container.active{
        width: 100%;
        max-width: 100%;
    }
    .content-container{
        max-width: 50%;
        width: 50%;
        transition: 0.2s;
        -webkit-box-flex: 0;
    }
    .content-container .aivp-content{
        margin: 0px 20px;
    }
    .content-container.active{
        width: 100%;
        max-width: 100%;
    }
    .content-container p{
        margin: 0;
    }
    .video-wrapper{
        display: flex;
        flex-wrap: wrap;
        width: 100%;
    }
    #btn-player{
        margin-top: 20px;
        padding: 8px 14px;
        border: 0px;
        font-size: 0.7rem;
        background-color: rgb(225, 225, 225);
        cursor: pointer;
    }
    .aivp-social-icons{
        display: flex;
        margin-top: 20px;
    }
    .aivp-social-icons img{
        width: 30px;
        margin-right: 10px;
        transform: scale(1);
        transition: 0.2s;
        image-rendering: optimizeQuality;
    }
    .aivp-social-icons img:hover{
        transform: scale(1.1);
    }

    .what-to-watch{
        padding-top: 20px;
    }
    .what-to-watch .aivp-row{
        display: flex;
        flex-wrap: wrap;
    }
    .what-to-watch .aivp-column{
        -webkit-box-flex: 0;
        -ms-flex: 0 0 25%;
        flex: 0 0 25%;
        max-width: 25%;
    }
    .what-to-watch .aivp-card{
        margin: 10px;
        display: block;
        text-decoration: none;
        color: #000;
        transition: 0.2s;
    }
    .what-to-watch .aivp-card:hover{
        color: royalblue;
    }
    .what-to-watch .aivp-card img{
        width: 100%;
        display: block;
        height: 138px;
        border: #444 solid 1px;
    }

    @media(max-width: 900px){
        #btn-player{
            visibility: hidden;;
        }
        .content-container{
            max-width: 100%;
            width: 100%;
        }
        .content-container .aivp-content{
            margin: 0px;
        }
        .video-container iframe,
        .video-container{
            max-width: 100%;
            width: 100%;
        }
        .what-to-watch .aivp-column{
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>


    <div class="video-wrapper">
        <div class="video-container">
            <!-- <iframe src="https://player.vimeo.com/video/466083703" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe> -->
            <?php if( get_post_meta(get_the_ID(), "platform", true) == 'wistia' ) : ?>

                <script src="https://fast.wistia.com/embed/medias/<?php echo (get_aivp_option( 'wistia_access_token' ) ? get_post_meta(get_the_ID(), "hashed-id", true) : get_post_meta(get_the_ID(), "video-id", true)) ; ?>.jsonp" async></script>
                <script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
                <div class="wistia_responsive_padding" style="padding:55.31% 0 0 0;position:relative;">
                    <div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;">
                        <div class="wistia_embed wistia_async_<?php echo (get_aivp_option( 'wistia_access_token' ) ? get_post_meta(get_the_ID(), "hashed-id", true) : get_post_meta(get_the_ID(), "video-id", true)) ; ?> videoFoam=true" style="height:100%;position:relative;width:100%">
                            &nbsp;
                        </div>
                    </div>
                </div>

            <?php endif;
            if( get_post_meta(get_the_ID(), "platform", true) == 'vimeo' ) : ?>

                <iframe src="https://player.vimeo.com/video/<?php echo get_post_meta(get_the_ID(), "video-id", true); ?>?autoplay=1" width="640" height="354" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>

            <?php endif; ?>

            <input type="button" id="btn-player" value="Enlarge Player">
        </div>
        <div class="content-container">
            <div class="aivp-content">
                <h1 class="content-title"><?php echo the_title(); ?></h1>
                <p><b><?php echo date("F jS, Y", strtotime(get_post_meta(get_the_ID(), "created-date", true))); ?> </b></p>
                <div style="margin:10px 0px;"><?php echo the_content(); ?></div>
                <div class="aivp-social-icons">
                    <a href="http://www.facebook.com/sharer.php?u=<?php echo get_permalink( get_the_ID() ); ?>"><img src="https://cdn.iconscout.com/icon/free/png-512/facebook-262-721949.png" alt=""></a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo the_title(); ?>&url=<?php echo get_permalink( get_the_ID() ); ?>"><img src="https://cdn.iconscout.com/icon/free/png-512/twitter-241-721979.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>
    

    <?php
    if( get_aivp_option( 'platform' ) == 'vimeo' ) {
        $args = array(
            'post_type' => 'aivp',
            'numberposts' => -1,
            'post_status' => 'publish',
            'exclude' => array( get_the_ID() ),
            'meta_key'  => 'created-date',
            'orderby'   => 'meta_value_num',
            'order'     => 'DESC',
        );
    } else {
        $args = array(
            'post_type' => 'aivp',
            'numberposts' => -1,
            'post_status' => 'publish',
            'exclude' => array( get_the_ID() ),
            'orderby'   => 'date',
            'order'     => 'DESC',
        );
    }

        $clips = get_posts( $args );
        // echo json_encode($clips);
        if( $clips ) :
    ?>

        <div class="what-to-watch">
            <h2>What to Watch</h2>
            <div class="aivp-row">
                
            <?php foreach( $clips as $clip ) { ?>
                <div class="aivp-column">
                    <a class="aivp-card" href="<?php echo $clip->guid; ?>">
                        <img src="<?php echo get_post_meta($clip->ID, 'thumbnail-url', true); ?>" alt="<?php echo $clip->post_title; ?>">
                        <h3><b><?php echo $clip->post_title; ?></b></h3>
                    </a>
                </div>
            <?php } ?>
            </div>
        </div>
        <?php endif; ?>
 
       
<script>
    let isActive = false;

    const triggerActive = () =>{
        
        isActive = !isActive;

        if(isActive){
            document.querySelector('#btn-player').value = 'Shrink Player';
            document.querySelector('.video-container').classList.add('active');
            document.querySelector('.content-container').classList.add('active');
        }else{
            document.querySelector('#btn-player').value = 'Enlarge Player';
            document.querySelector('.video-container').classList.remove('active');
            document.querySelector('.content-container').classList.remove('active');
        }
        
    }

    document.querySelector('#btn-player').addEventListener('click',triggerActive);
</script>

<?php get_footer(); ?>