<?php

require_once 'ShortURL.class.php';

//Example of how to use ShortURL class

$urlShorter = ShortURL::getInstance()
	-> setParam( ShortURL::SHORTENER_TYPE_ADFLY, 'key', '9c82057bc7e3deb5094d52eb9ffba184' )
	-> setParam( ShortURL::SHORTENER_TYPE_ADFLY, 'uid', '1312770' );

$url = 'http://www.phpclasses.org/browse/author/1183559.html';

$anchorTemplate = '<a href="%s" target="_blank">%s</a><br />';

echo sprintf( $anchorTemplate, $url, 'Original URL' );
echo sprintf( $anchorTemplate, $urlShorter -> getUrl( $url, ShortURL::SHORTENER_TYPE_ADFLY ), 'adf.ly URL' );
echo sprintf( $anchorTemplate, $urlShorter -> getUrl( $url, ShortURL::SHORTENER_TYPE_TINYURL ), 'tinyurl.com URL' );
echo sprintf( $anchorTemplate, $urlShorter -> getUrl( $url, ShortURL::SHORTENER_TYPE_ADFLY, ShortURL::SHORTENER_TYPE_TINYURL ), 'adf.ly masked as tinyurl.com URL' );
echo sprintf( $anchorTemplate, $urlShorter -> getUrl( 'http://www.youtube.com/watch?v=dQw4w9WgXcQ', ShortURL::SHORTENER_TYPE_YOUTUBE ), 'y2u.be URL' );
	
