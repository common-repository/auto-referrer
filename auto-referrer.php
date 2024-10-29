<?php
/*
Plugin Name: iWorks_Auto_Referrer
Plugin URI: http://iworks.pl/wordpress-plugins-autoreferrer/
Description: Add a referrer to links.
Author: Marcin Pietrzak
Version: 1.0.0
Author URI: http://iworks.pl/

* $Id: auto-referrer.php 2672727 2022-02-04 07:53:41Z iworks $
* $HeadURL: https://plugins.svn.wordpress.org/auto-referrer/trunk/auto-referrer.php $
 */

class iWorks_Auto_Referrer {

    public function __construct() {
        add_filter( 'the_content', array( &$this, 'iworksiWorks_Auto_Referrer' ), 99 );
    }

    public function iworksiWorks_Auto_Referrer( $content = '' ) {
        preg_match_all( '/href="https?:[^"]+"/', $content, $urls );
        if ( empty( $urls ) ) {
            return $content;
        }
        foreach ( $urls[0] as $url ) {
            # xclude self referring
            $re = '/http:\/\/' . $_SERVER['HTTP_HOST'] . '/';
            if ( ! preg_match( $re, $url ) ) {
                $new = preg_replace( '/"$/', '', $url );
                #                   $new .= ( preg_match ( '/\?/', $new ) )? '&amp;':'?';
                $new    .= '#';
                $new    .= 'referrer=' . $_SERVER['HTTP_HOST'] . '"';
                $url     = preg_replace( '/\//', '\/', $url );
                $url     = preg_replace( '/\?/', '\?', $url );
                $url     = preg_replace( '/\./', '\.', $url );
                $url     = '/' . $url . '/';
                $content = preg_replace( $url, $new, $content );
            }
        }
        return $content;
    }
}
new iWorks_Auto_Referrer;

