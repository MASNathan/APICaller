<?php

require_once 'ShortURL.class.php';

//Example of how to use ShortURL class

//You must atleast set the adfly api key and the uid in order to generate adfly urls

$urlShorter = ShortURL::getInstance()
	-> setParam( ShortURL::SHORTENER_TYPE_ADFLY, 'key', 'adfly_api_key' )
	-> setParam( ShortURL::SHORTENER_TYPE_ADFLY, 'uid', 'adfly_user_id' )
	-> setParam( ShortURL::SHORTENER_TYPE_GOOGLE, 'api', 'google_api_key' );
	
$url = 'http://www.phpclasses.org/browse/author/1183559.html';

$anchorTemplate = '<a href="%s" target="_blank">%s</a><br />';

echo sprintf( $anchorTemplate, $url, 'Original URL' );
echo sprintf( $anchorTemplate, $urlShorter -> getUrl( $url, ShortURL::SHORTENER_TYPE_ADFLY ), 'adf.ly URL' );
echo sprintf( $anchorTemplate, $urlShorter -> getUrl( $url, ShortURL::SHORTENER_TYPE_TINYURL ), 'tinyurl.com URL' );
echo sprintf( $anchorTemplate, $urlShorter -> getUrl( $url, ShortURL::SHORTENER_TYPE_ADFLY, ShortURL::SHORTENER_TYPE_TINYURL ), 'adf.ly masked as tinyurl.com URL' );
echo sprintf( $anchorTemplate, $urlShorter -> getUrl( $url, ShortURL::SHORTENER_TYPE_GOOGLE ), 'googleapis.com/urlshortener URL' );
echo sprintf( $anchorTemplate, $urlShorter -> getUrl( 'http://www.youtube.com/watch?v=dQw4w9WgXcQ', ShortURL::SHORTENER_TYPE_YOUTUBE ), 'y2u.be URL' );
