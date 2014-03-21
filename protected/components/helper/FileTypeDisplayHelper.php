<?php

/**
 *  
 */
class FileTypeDisplayHelper
{

    /**
     *  依照副檔名, 依內容的 顯示/處理 方式做分類
     */
    public static function getAttribByItem( $item )
    {
        if ( Item::TYPE_DIRECTORY === $item->getType() ) {
            return 'folder';
        }

        // 基本判斷
        $attrib = 'unknown';
        $mapping = array(
            //
            'txt'       => 'text',
            'log'       => 'text',
            'htm'       => 'text',
            'html'      => 'text',
            'htaccess'  => 'text',
            'sql'       => 'text',
            'csv'       => 'text',
            //
            'js'        => 'script',
            'css'       => 'script',
            'sass'      => 'script',
            'scss'      => 'script',
            'pl'        => 'script',
            'php'       => 'script',
            //
            'pdf'       => 'pdf',
            //
            'gif'       => 'image',
            'png'       => 'image',
            'ico'       => 'image',
            'jpg'       => 'image',
            'jpeg'      => 'image',
            //
            'avi'       => 'video',
            'mkv'       => 'video',
            'mp4'       => 'video',
            'rm'        => 'video',
            'rmvb'      => 'video',
            //
            'mp3'       => 'audio',
            'wav'       => 'audio',
            'mid'       => 'audio',
            //
            'rar'       => 'download',
            'zip'       => 'download',
            'tar'       => 'download',
            'gz'        => 'download',
            'gz2'       => 'download',
        );

        $extension = $item->getExtension();
        if ( isset($mapping[$extension]) ) {
            $attrib = $mapping[$extension];
        }
        return $attrib;
    }


}
