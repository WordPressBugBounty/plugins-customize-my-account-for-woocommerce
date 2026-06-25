<?php
$atts = shortcode_atts( array(
        'slug' => '',
    ), $atts, 'wcmamtx-web-cam' );

    $slug_raw = (string) esc_attr($atts['slug']);
    $slug_raw = trim( $slug_raw );
    $slug = preg_replace( '/[^A-Za-z0-9_-]/', '', $slug_raw );

    $safe_slug_attr = esc_attr( $slug );
    $safe_slug_html = esc_html( $slug );

    $output = '<form method="POST" action="" name="web_cam" id="web_cam">';

    $output = $output.''.wp_nonce_field( 'wcmamtx_webcam_action', 'wcmamtx_webcam_nonce' ).'<div class="row">';

    $output = $output.'<div class="col-md-6">';

    $output = $output.'<div id="my_camera" class="my_camera"></div>';

    $output = $output.'<br/>';

    $output = $output.'<input type=button value="Take Snapshot" class="takeimage" id="takeimage">';

    $output = $output.'<input type="hidden" name="image" class="image-tag">';

    $output = $output.'<input type="hidden" name="slug" value="'.$safe_slug_attr.'">';

    $output = $output.'</div>';

    $output = $output.'<div class="col-md-6">';

    $output = $output.'<div id="results"></div>';

    $output = $output.'</div>';

    $output = $output.'<div class="col-md-12 text-center">';

    $output = $output.'<br/>';

    $output = $output.'<input type="submit" value="UPLOAD" name="web_cam_submit" id="web_cam_submit" style="display:none;" class="btn btn-success">';

    $output = $output.'</div>';

    $output = $output.'</div>';

    $output = $output.'</form>';
            
    echo $output;
?>