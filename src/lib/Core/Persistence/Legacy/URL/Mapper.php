<?php

namespace EzSystems\EzPlatformLinkManager\Core\Persistence\Legacy\URL;

use EzSystems\EzPlatformLinkManager\SPI\Persistence\URL\URL;
use EzSystems\EzPlatformLinkManager\SPI\Persistence\URL\URLCreateStruct;
use EzSystems\EzPlatformLinkManager\SPI\Persistence\URL\URLUpdateStruct;

/**
 * URL Mapper.
 */
class Mapper
{
    /**
     * Creates a URL from the given create $struct.
     *
     * @param \EzSystems\EzPlatformLinkManager\SPI\Persistence\URL\URLCreateStruct $struct
     * @return \EzSystems\EzPlatformLinkManager\SPI\Persistence\URL\URL
     */
    public function createURLFromCreateStruct(URLCreateStruct $struct)
    {
        $time = time();

        $url = new URL();
        $url->url = $struct->url;
        $url->originalUrlMd5 = md5($struct->url);
        $url->isValid = $struct->isValid;
        $url->lastChecked = $struct->lastChecked;
        $url->created = $time;
        $url->modified = $time;

        return $url;
    }

    /**
     * Creates a URL from the given update $struct.
     *
     * @param \EzSystems\EzPlatformLinkManager\SPI\Persistence\URL\URLCreateStruct $struct
     * @return \EzSystems\EzPlatformLinkManager\SPI\Persistence\URL\URL
     */
    public function createURLFromUpdateStruct(URLUpdateStruct $struct)
    {
        $url = new URL();
        $url->url = $struct->url;
        $url->originalUrlMd5 = md5($struct->url);
        $url->isValid = $struct->isValid;
        $url->lastChecked = $struct->lastChecked;
        $url->modified = time();

        return $url;
    }

    /**
     * Extracts URL objects from $rows.
     *
     * @param array $rows
     * @return \EzSystems\EzPlatformLinkManager\SPI\Persistence\URL\URL[]
     */
    public function extractURLsFromRows(array $rows)
    {
        $urls = [];

        foreach ($rows as $row) {
            $url = new URL();
            $url->id = (int)$row['id'];
            $url->url = $row['url'];
            $url->originalUrlMd5 = $row['original_url_md5'];
            $url->isValid = (bool)$row['is_valid'];
            $url->lastChecked = (int)$row['last_checked'];
            $url->created = (int)$row['created'];
            $url->modified = (int)$row['modified'];

            $urls[] = $url;
        }

        return $urls;
    }
}
