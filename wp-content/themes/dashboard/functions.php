<?php

// Featured image functionality.
function mytheme_post_thumbnails()
{
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'mytheme_post_thumbnails');

//Infinite Scroll
function wp_infinitepaginate()
{
    $type = $_POST['type'];
    if ($type == "video") {
        $v_id = $_POST['id'];
        $v_post = get_post($v_id);
        $url = get_field('video_file', $v_post);
        ?>
        <div class="vc-main-c">
            <div class="video-box" style="position:relative;height:400px;width:800px">
                <amp-video autoplay src="<?php echo $url; ?>" layout="fill" controls>
                </amp-video>
            </div>
            <div class="video-about">
                <p class="video-titile"><?php echo $v_post->post_title; ?></p>
                <p class="video-date"><?php echo $v_post->post_date; ?></p>
                <p>
                    <?php echo $v_post->post_content; ?>
                </p>
            </div>
        </div>
    <?php   } else if ($type == "podcast") {
            $pd_id = $_POST['id'];
            $pd_post = get_post($pd_id);
            $url = get_field('audio_file', $pd_post);
            ?>
        <div class="vc-main-c">
            <div class="video-box" style="position:relative;width:400px">
                <amp-audio width="auto" height="50" src="<?php echo $url; ?>">
                    <div fallback>Your browser doesn’t support HTML5 audio</div>
                </amp-audio>
            </div>
            <div class="video-about">
                <p class="video-titile"><?php echo $pd_post->post_title; ?></p>
                <p class="video-date"><?php echo $pd_post->post_date; ?></p>
                <p>
                    <?php echo $pd_post->post_content; ?>
                </p>
            </div>
        </div>

    <?php
        } else if ($type == "gallery") {
            $g_id = $_POST['id'];
            $gallery = get_post($g_id);
            $gallery_array = get_field('photos', $gallery);
            ?>
        <div class="vc-main-c">
            <div class="g-container">
                <div class="g-content">
                    <amp-carousel layout="fill" type="slides">
                        <?php
                                foreach ($gallery_array as $val) {
                                    $url = $val['sizes']['medium'];
                                    ?>
                            <amp-img src="<?php echo $url; ?>" layout="fill" alt=""></amp-img>
                        <?php } ?>
                    </amp-carousel>
                </div>
            </div>

            <div class="video-about">
                <p class="video-titile"><?php echo $gallery->post_title; ?></p>
                <p class="video-date"><?php echo $gallery->post_date; ?></p>
                <p>
                    <?php echo $gallery->post_content; ?>
                </p>
            </div>
        </div>
    <?php
        } else if ($type == "insight") {
            $in_id = $_POST['id'];
            $in_post = get_post($in_id);
            $description = $in_post->post_content;
            ?>
        <div class="chart-ajax" style="height: 400px;width: 700px;min-height: 300px;min-width: 300px;">
            <div class="chart-div" style="height:70%">
                <?php
                        wp_head();
                        echo do_shortcode($description);
                        ?>
            </div>
            <div class="video-about">
                <p class="video-titile"><?php echo $in_post->post_title; ?></p>
                <p class="video-date"><?php echo $in_post->post_date; ?></p>
            </div>
        </div>
        <?php wp_footer(); ?>

<?php
    }
    die();
}
add_action('wp_ajax_infinite_scroll', 'wp_infinitepaginate'); // for logged in user
add_action('wp_ajax_nopriv_infinite_scroll', 'wp_infinitepaginate'); // if user not logged in



function updateRestoreId()
{
    $id = $_POST['id'];
    $restore_id = $_POST['restore_id'];
    echo $restore_id;
    // update user - setting new email and surname
    global $pc_users;
    $data = array(
        'surname'     => $restore_id
    );
    $result = $pc_users->update_user($id, $data);
    if (!$result) {
        // an error occurred - check related properties  
        return http_response_code(401);
    } else {
        // success
        return http_response_code(201);
    }
    die();
}
add_action('wp_ajax_update_restore_id', 'updateRestoreId'); // for logged in user
add_action('wp_ajax_nopriv_update_restore_id', 'updateRestoreId'); // if user not logged in
