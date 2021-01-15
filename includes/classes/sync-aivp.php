<?php

include_once( AIVP_PATH . 'includes/classes/salesforce.php' );

class Sync_AIVP {
    
    function get_videos( $endpoint, $api=NULL ) {
        if($api == 'api') {
            $endpoint = 'https://api.wistia.com/v1/medias.json?access_token='.$endpoint;
            $from_end = $this->get_from_api($endpoint);
            return $from_end;
        } else {
            $url = $endpoint;
        }
        $url = $endpoint;
        
        $response = file_get_contents($url);

        $response = json_decode($response);
        $videos = array();

        foreach ($response as $key => $video) {

            $salesforce_id = $video->Id;
            $video_id = (isset($video->VideoId__c) ? $video->VideoId__c : "");
            $title = (isset($video->VideoTitle__c) ? $video->VideoTitle__c : "");
            $description = (isset($video->VideoDescription__c) ? $video->VideoDescription__c : "");
            $platform = get_aivp_option( 'platform' );
            $thumbnail_url = (isset($video->VideoStillImageFile__c) ? $video->VideoStillImageFile__c : "");
            $created_date = (isset($video->VideoCreated__c) ? $video->VideoCreated__c : "");
            $video_data = $video;
        
            $set_video = array(
                "SALESFORCE_ID" => $salesforce_id,
                "VIDEO_ID" => $video_id,
                "TITLE" => $title,
                "DESCRIPTION" => $description,
                "PLATFORM" => $platform,
                "THUMBNAIL_URL" => $thumbnail_url,
                "CREATED_DATE" => $created_date,
                "VIDEO_DATA" => $video_data,
            );
            array_push($videos, $set_video);
        }
        return $videos;
    }

    function get_project_videos( $token, $project_id ) {

        $endpoint = 'https://api.wistia.com/v1/projects/'.$project_id.'.json?access_token='.$token;
        
        $response = file_get_contents($endpoint);

        $response = json_decode($response);
        $videos = array();

        foreach ($response->medias as $key => $v) {

            $video = $this->get_media_by_id( $token, $v->hashed_id );

            $salesforce_id = "";
            $video_id = (isset($video->id) ? $video->id : "");
            $hashed_id = (isset($video->hashed_id) ? $video->hashed_id : "");
            $title = (isset($video->name) ? $video->name : "");
            $description = (isset($video->description) ? $video->description : "");
            $platform = get_aivp_option( 'platform' );
            $thumbnail_url = (isset($video->thumbnail->url) ? $video->thumbnail->url : "");
            $created_date = (isset($video->created) ? $video->created : "");
            $video_data = $video;
        
            $set_video = array(
                "SALESFORCE_ID" => $salesforce_id,
                "VIDEO_ID" => $video_id,
                "HASHED_ID" => $hashed_id,
                "TITLE" => $title,
                "DESCRIPTION" => $description,
                "PLATFORM" => $platform,
                "THUMBNAIL_URL" => $thumbnail_url,
                "CREATED_DATE" => $created_date,
                "VIDEO_DATA" => $video_data,
            );
            array_push($videos, $set_video);
        }
        return $videos;
    }

    function get_media_by_id( $token, $hashed_id ) {
        $endpoint = 'https://api.wistia.com/v1/medias/'.$hashed_id.'.json?access_token='.$token;
        
        $response = file_get_contents($endpoint);

        $response = json_decode($response);
        return $response;
    }

    function get_from_api( $endpoint ) {
        $response = file_get_contents($endpoint);

        $response = json_decode($response);
        $videos = array();

        foreach ($response as $key => $video) {

            $salesforce_id = "";
            $video_id = (isset($video->id) ? $video->id : "");
            $hashed_id = (isset($video->hashed_id) ? $video->hashed_id : "");
            $title = (isset($video->name) ? $video->name : "");
            $description = (isset($video->description) ? $video->description : "");
            $platform = get_aivp_option( 'platform' );
            $thumbnail_url = (isset($video->thumbnail->url) ? $video->thumbnail->url : "");
            $created_date = (isset($video->created) ? $video->created : "");
            $video_data = $video;
        
            $video = array(
                "SALESFORCE_ID" => $salesforce_id,
                "VIDEO_ID" => $video_id,
                "HASHED_ID" => $hashed_id,
                "TITLE" => $title,
                "DESCRIPTION" => $description,
                "PLATFORM" => $platform,
                "THUMBNAIL_URL" => $thumbnail_url,
                "CREATED_DATE" => $created_date,
                "VIDEO_DATA" => $video_data,
            );
            array_push($videos, $video);
        }
        return $videos;
    }

    function set_data( $data ) {

        $videos = array();
        
        foreach ($data as $key => $video) {

            $video_id = (isset($video['uri']) ? substr($video['uri'], strrpos($video['uri'],"/")+1) : "");
            $title = (isset($video['name']) ? $video['name'] : "");
            $description = (isset($video['description']) ? $video['description'] : "");
            $platform = get_aivp_option( 'platform' );
            $thumbnail_url = (isset($video['pictures']['sizes'][3]['link']) ? $video['pictures']['sizes'][3]['link'] : "");
            $created_date = (isset($video['created_time']) ? $video['created_time'] : "");
            $video_data = $video['pictures'];
        
            $video = array(
                "VIDEO_ID" => $video_id,
                "TITLE" => $title,
                "DESCRIPTION" => $description,
                "PLATFORM" => $platform,
                "THUMBNAIL_URL" => $thumbnail_url,
                "CREATED_DATE" => $created_date,
                "VIDEO_DATA" => $video_data,
            );
            array_push($videos, $video);
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
                $sf_id = $sf->upload_video( $video );
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
            update_post_meta($id_video, "thumbnail-url", $video["THUMBNAIL_URL"]);
            update_post_meta($id_video, "created-date", $video["CREATED_DATE"]);
            update_post_meta($id_video, "video-data", json_encode($video["VIDEO_DATA"]));
        }
        return true;
    }
}