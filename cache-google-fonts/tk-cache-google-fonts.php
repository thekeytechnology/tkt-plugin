<?php

function tkCacheGoogleFonts() {
    global $wp_styles;
    foreach ($wp_styles->registered as $style ) {
        if (strpos( $style->src , 'fonts.googleapis.com')) {
            $newFileName = parse_url($style->src)['query'] . '.css';
            $permfile = wp_upload_dir()['basedir'] . '/tk-fonts/' . $newFileName ;

            if (!file_exists($permfile)) {
                tkSaveFontFile($style->src, $permfile);
                $cssContents = file_get_contents($permfile);
                preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $cssContents, $urls);
                foreach ($urls[0] as $url) {
                    tkSaveFontFile($url , wp_upload_dir()['basedir'] . '/tk-fonts/' . basename($url) );
                    $cssContents = str_replace ( $url, '/wp-content/uploads/tk-fonts/' . basename($url) , $cssContents );
                }
                file_put_contents($permfile, $cssContents);
            }

            $style->src = '/wp-content/uploads/tk-fonts/' . $newFileName;

        }
    }

}


function tkSaveFontFile($src, $target) {
    $wpFileIncludePath = ABSPATH . 'wp-admin/includes/file.php';
    require_once ($wpFileIncludePath);
    $tmpfile = download_url( $src, $timeout = 300 );

    if (!file_exists(wp_upload_dir()['basedir'] . '/tk-fonts/')) {
        mkdir(wp_upload_dir()['basedir'] . '/tk-fonts/', 0777, true);
    }

    copy($tmpfile, $target);

    unlink( $tmpfile ); // must unlink afterwards
}


function tkInstallCacheGoogleFonts() {
    add_action('wp_print_styles', 'tkCacheGoogleFonts', 999999999);
}