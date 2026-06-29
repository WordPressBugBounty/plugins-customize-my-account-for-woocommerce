<?php

function wcmamtx_upload_avatar_tab_uninstall() {
    $wcmamtx_upload_avatar_tab = new wcmamtx_upload_avatar_tab;
    $users = get_users();

    foreach ( $users as $user )
        $wcmamtx_upload_avatar_tab->avatar_delete( $user->ID );

    delete_option( 'wcmamtx_upload_avatar_tab_caps' );
}
register_uninstall_hook( __FILE__, 'wcmamtx_upload_avatar_tab_uninstall' );

?>