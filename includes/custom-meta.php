<?php
function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>

            <label for="platform"><b>Platform</b></label> <br />
            <input name="platform" type="text" value="<?php echo get_post_meta($object->ID, "platform", true); ?>" style="width: 100%;">

            <br />
            <br />

            <label for="video-id"><b>Video Id</b></label> <br />
            <input name="video-id" type="text" value="<?php echo get_post_meta($object->ID, "video-id", true); ?>" style="width: 100%;">

            <br />
            <br />

            <label for="hashed-id"><b>Hashed Id (Wistia)</b></label> <br />
            <input name="hashed-id" type="text" value="<?php echo get_post_meta($object->ID, "hashed-id", true); ?>" style="width: 100%;">

            <br />
            <br />

            <label for="salesforce-id"><b>Salesforce Id</b></label> <br />
            <input name="salesforce-id" type="text" value="<?php echo get_post_meta($object->ID, "salesforce-id", true); ?>" style="width: 100%;">

            <br />
            <br />

            <label for="thumbnail-url"><b>Thumbnail URL</b></label> <br />
            <input name="thumbnail-url" type="text" value="<?php echo get_post_meta($object->ID, "thumbnail-url", true); ?>" style="width: 100%;">

            <br />
            <br />

            <label for="created-date"><b>Created Date</b></label> <br />
            <input name="created-date" type="text" value="<?php echo get_post_meta($object->ID, "created-date", true); ?>" style="width: 100%;">

            <br />
            <br />

            <label for="video-data"><b>Video Data</b></label> <br />
            <textarea id="video-data" name="video-data" rows="10" style="width: 100%; height: 20%;">
                <?php echo get_post_meta($object->ID, "video-data", true); ?>
            </textarea>
        </div>
    <?php  
}

function add_custom_meta_box()
{
    add_meta_box("video-meta-box", "Data Field", "custom_meta_box_markup", "aivp", "normal", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");


function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "aivp";
    if($slug != $post->post_type)
        return $post_id;

    $video_data_value = "";
    $platform_value = "";
    $video_id_value = "";
    $video_hashedId_value = "";
    $salesforce_id_value = "";
    $thumbnail_url_value = "";
    $created_date_value = "";

    if(isset($_POST["video-data"]))
    {
        $video_data_value = $_POST["video-data"];
    }   
    update_post_meta($post_id, "video-data", $video_data_value);

    if(isset($_POST["platform"]))
    {
        $platform_value = $_POST["platform"];
    }   
    update_post_meta($post_id, "platform", $platform_value);

    if(isset($_POST["video-id"]))
    {
        $video_id_value = $_POST["video-id"];
    }   
    update_post_meta($post_id, "video-id", $video_id_value);

    if(isset($_POST["hashed-id"]))
    {
        $video_hashedId_value = $_POST["hashed-id"];
    }   
    update_post_meta($post_id, "hashed-id", $video_hashedId_value);

    if(isset($_POST["salesforce-id"]))
    {
        $salesforce_id_value = $_POST["salesforce-id"];
    }   
    update_post_meta($post_id, "salesforce-id", $salesforce_id_value);

    if(isset($_POST["thumbnail-url"]))
    {
        $thumbnail_url_value = $_POST["thumbnail-url"];
    }   
    update_post_meta($post_id, "thumbnail-url", $thumbnail_url_value);

    if(isset($_POST["created-date"]))
    {
        $created_date_value = $_POST["created-date"];
    }   
    update_post_meta($post_id, "created-date", $created_date_value);

}

add_action("save_post", "save_custom_meta_box", 10, 3);


function remove_custom_field_meta_box()
{
    remove_meta_box("postcustom", "aivp", "normal");
}

add_action("do_meta_boxes", "remove_custom_field_meta_box");