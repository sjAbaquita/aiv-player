<?php

include_once( AIVP_PATH . 'includes/classes/salesforce.php' );

class AIVP_Youtube {
    
    function get_videos( $playlist_id, $api_key ) {

        $endpoint = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId='.$playlist_id.'&key='.$api_key;

        //https://www.googleapis.com/youtube/v3/videos?part=snippet&id=tQ10MSBjQmE%2C10L2IyS6T4A&key=AIzaSyA3TY_-ch6ujtlmoIxcrEHFr9yHeRXeX2A
        
        $response = file_get_contents($endpoint);
        $response = json_decode($response);

        $videoIds = '';

        foreach ($response->items as $key => $video) {
            $videoIds .= $video->snippet->resourceId->videoId .',';
        }

        $endpoint = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.urlencode($videoIds).'&key='.$api_key;
        $response = file_get_contents($endpoint);
        $response = json_decode($response);

        // return json_encode($response->items[1]->snippet->tags);

        $videos = array();
        foreach ($response->items as $key => $video) {

            $salesforce_id = "";
            $video_id = (isset($video->id) ? $video->id : "");
            $hashed_id = (isset($video->snippet->resourceId->videoId) ? $video->snippet->resourceId->videoId : "");
            $title = (isset($video->snippet->title) ? $video->snippet->title : "");
            $description = (isset($video->snippet->description) ? $video->snippet->description : "");
            $tags = (isset($video->snippet->tags) ? $video->snippet->tags : "" );
            $platform = get_aivp_option( 'platform' );
            $thumbnail_url = (isset($video->snippet->thumbnails->standard->url) ? $video->snippet->thumbnails->standard->url : "");
            $created_date = (isset($video->snippet->publishedAt) ? $video->snippet->publishedAt  : "");
            $video_data = $video->snippet;
        
            $set_video = array(
                "SALESFORCE_ID" => $salesforce_id,
                "VIDEO_ID" => $video_id,
                "HASHED_ID" => $hashed_id,
                "TITLE" => $title,
                "DESCRIPTION" => $description,
                "TAGS" => $tags,
                "PLATFORM" => $platform,
                "THUMBNAIL_URL" => $thumbnail_url,
                "CREATED_DATE" => $created_date,
                "VIDEO_DATA" => $video_data,
            );
            array_push($videos, $set_video);
        }
        return $videos;
    }


    function upload_videos( $videos ) {

        $sf = new AIVPSalesforce();
        
        foreach( $videos as $key => $video ) {
            $post = array(
                "post_title" => wp_strip_all_tags( $video['TITLE'] ),
                "post_content" => $video["DESCRIPTION"],
                "post_type" => "aivp",
                "post_status" => "publish"
            );
            // if( get_aivp_option( 'wistia_access_token' ) ) :
                $old_video = get_posts(array(
                    "post_type" => "aivp",
                    "meta_key" => "video-id",
                    "meta_value" => $video["VIDEO_ID"]
                ));
            // else :
            //     $old_video = get_posts(array(
            //         "post_type" => "aivp",
            //         "meta_key" => "salesforce-id",
            //         "meta_value" => $video["SALESFORCE_ID"]
            //     ));
            // endif;
            
            if (empty($old_video)){
                $id_video = wp_insert_post($post);
                //add to salesforce if new
                // $sf_id = $sf->upload_video( $video );
            } else {
                $id_video = $old_video[0]->ID;
                // Update video
                $update_video = array(
                    'ID'           => $id_video,
                    'post_title'   => wp_strip_all_tags( $video['TITLE'] ),
                    'post_content' => $video["DESCRIPTION"],
                );

                // Update the video into the database
                wp_update_post( $update_video );
            }

            update_post_meta($id_video, "salesforce-id", ($sf_id ? $sf_id : $video["SALESFORCE_ID"]));
            update_post_meta($id_video, "video-id", $video["VIDEO_ID"]);
            update_post_meta($id_video, "hashed-id", $video["HASHED_ID"]);
            update_post_meta($id_video, "platform", $video["PLATFORM"]);
            update_post_meta($id_video, "video-tags", json_encode($video["TAGS"]));
            update_post_meta($id_video, "thumbnail-url", $video["THUMBNAIL_URL"]);
            update_post_meta($id_video, "created-date", $video["CREATED_DATE"]);
            update_post_meta($id_video, "video-data", json_encode($video["VIDEO_DATA"]));
        }
        return true;
    }

}