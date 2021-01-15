<?php

include_once( AIVP_PATH . 'includes/classes/sync-aivp.php' );
include_once( AIVP_PATH . 'includes/classes/AIVP-Vimeo.php' );
include_once( AIVP_PATH . 'includes/classes/AIVP-Youtube.php' );
include_once( AIVP_PATH . 'vendor/vimeo/vimeo-api/autoload.php' );

function sync_wistia( $data ) {
    
    $aivp = new Sync_AIVP();
    $aivp_yt = new AIVP_Youtube();

    if( get_aivp_option( 'platform' ) == 'wistia' ) :
        if( get_aivp_option( 'wistia_access_token' ) ) :
            if( get_aivp_option( 'wistia_project_id' ) ) :
                $videos = $aivp->get_project_videos( get_aivp_option( 'wistia_access_token' ), get_aivp_option( 'wistia_project_id' ) );
            else :
                $videos = $aivp->get_videos( get_aivp_option( 'wistia_access_token' ), 'api' );
            endif;
        elseif( get_aivp_option( 'wistia_salesforce_endpoint' ) ) :
            $videos = $aivp->get_videos( get_aivp_option( 'wistia_salesforce_endpoint' ) );
        else :
            return 'Failed! The endpoint or the access token must not be empty.';
        endif;
    elseif( get_aivp_option( 'platform' ) == 'vimeo' ) :
        if(empty(get_aivp_option( 'vimeo_salesforce_endpoint' ))) :
            $client = array(
                "client_id" => get_aivp_option( 'vimeo_client_id' ),
                "client_secret" => get_aivp_option( 'vimeo_secret_key' )
            );
            // $vimeo = new AIVP_Vimeo();
            $vimeo = new \Vimeo\Vimeo(get_aivp_option( 'vimeo_client_id' ), get_aivp_option( 'vimeo_secret_key' ));
            $vimeo->setToken(get_aivp_option( 'vimeo_token' ));

            $response = $vimeo->request('/me/videos', [], 'GET')['body'];
            $videos = $aivp->set_data($response['data']);
        else :
            $videos = $aivp->get_videos( get_aivp_option( 'vimeo_salesforce_endpoint' ) );
        endif;
    elseif( get_aivp_option( 'platform' ) == 'youtube' ) :
        if( !empty(get_aivp_option( 'yt_api_key' )) && !empty(get_aivp_option( 'yt_playlist_id')) ) :
            $videos = $aivp_yt->get_videos( get_aivp_option( 'yt_playlist_id' ), get_aivp_option( 'yt_api_key' ) );
            // return $videos;
            if( $aivp_yt->upload_videos( $videos ) ) {
                return 'Successfuly updated!';
            } else {
                return 'Unable to update! Retry later.';
            }
        else :
            return 'API Key and playlist id must not be empty!';
        endif;
    endif;

    //if Youtube is checked
    // if( get_aivp_option( 'youtube_api' ) ) {
    //     if( !empty(get_aivp_option( 'yt_api_key' )) && !empty(get_aivp_option( 'yt_playlist_id')) ) :
    //         $videos = $aivp_yt->get_videos( get_aivp_option( 'yt_playlist_id' ), get_aivp_option( 'yt_api_key' ) );

    //         if( $aivp_yt->upload_videos( $videos ) ) {
    //             return 'Successfuly updated!';
    //         } else {
    //             return 'Unable to update! Retry later.';
    //         }
    //     else :
    //         return 'API Key and playlist id must not be empty!';
    //     endif;
    // }
    
    if( $aivp->upload_videos( $videos ) ) {
        return 'Successfuly updated!';
    } else {
        return 'Unable to update! Retry later.';
    }

}

add_action( 'rest_api_init', function () {
    register_rest_route( 'aivp/v1', '/sync-video', array(
        'methods' => 'GET',
        'callback' => 'sync_wistia',
    ) );
} );


function sync_youtube( $data ) {
    
    $aivp = new AIVP_Youtube();

    if( !empty(get_aivp_option( 'yt_api_key' )) && !empty(get_aivp_option( 'yt_playlist_id')) ) :
        $videos = $aivp->get_videos( get_aivp_option( 'yt_playlist_id' ), get_aivp_option( 'yt_api_key' ) );
    else :
        return 'API Key and playlist id must not be empty!';
    endif;
    
    if( $aivp->upload_videos( $videos ) ) {
        return 'Successfuly updated!';
    } else {
        return 'Unable to update! Retry later.';
    }

}

add_action( 'rest_api_init', function () {
    register_rest_route( 'aivp/v1', '/sync-yt', array(
        'methods' => 'GET',
        'callback' => 'sync_youtube',
    ) );
} );


add_filter( "page_template", "load_clips_page" );
function load_clips_page( $template ) {
    if( is_page( get_aivp_option( 'page_slug' ) ) )  {
        $template = AIVP_PATH . 'includes/templates/page-template.php';
    }
    return $template;
}

add_filter( "single_template", "load_clip_single" );
function load_clip_single( $template ) {
    global $post;
    if( $post->post_type == 'aivp' )  {
        if( file_exists( AIVP_PATH . 'includes/templates/single-template.php' ) ) {
            $template = AIVP_PATH . 'includes/templates/single-template.php';
        }
    }
    return $template;
}

// function aivp_enqueue_scripts() {
//     global $post;
//     if( is_page( get_aivp_option( 'page_slug' ) ) || $post->post_type == 'aivp' )  {
//         wp_enqueue_style( 'bootstrap-css', plugins_url('/templates/assets/css/bootstrap.min.css', __FILE__) );
//         wp_enqueue_script( 'fontawesome', plugins_url('/templates/assets/js/kit-fontawesome.js', __FILE__), array ( 'jquery' ), 1.0, true );
//     }
// }
// add_action( 'wp_enqueue_scripts', 'aivp_enqueue_scripts' );



