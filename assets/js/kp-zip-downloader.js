( function( $ ) {
    'use strict';

    $( document ).ready( function() {
        // Add "Download ZIP" link to each theme action area
        $( 'div.theme-actions' ).each( function() {
            var themeName = $( this ).closest( 'div.theme' ).attr( 'data-slug' );
            if ( themeName === undefined ) {
                themeName = kp_zip_downloader.theme_name;
            }
            var url = new URL( kp_zip_downloader.themes_url );
            var params = new URLSearchParams( url.search );

            params.set( 'kp_download', 'theme' );
            params.set( 'theme', themeName );
            params.set( kp_zip_downloader.nonce.param, kp_zip_downloader.nonce.value );

            url.search = params.toString();

            $( this ).append(
                '<a class="button kp-download-theme" href="' +
                url.toString() +
                '">' +
                kp_zip_downloader.download_link_text +
                '</a>'
            );
        });
    });

} )( jQuery );