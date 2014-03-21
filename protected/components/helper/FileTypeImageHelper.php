<?php

/**
 *  
 */
class FileTypeImageHelper
{

    /**
     *  依照 image 資訊, 判斷該檔案是何種屬性
     */
    public static function getAttribByItem( $item )
    {
        if ( Item::TYPE_DIRECTORY === $item->getType() ) {
            return 'folder';
        }

        // 基本判斷
        $attrib = 'unknown';
        $mapping = array(
            // text
            //
            'htm'       => 'html',
            'html'      => 'html',
            //
            'js'        => 'script',
            'css'       => 'script',
            'sass'      => 'script',
            'less'      => 'script',
            'pl'        => 'script',
            'php'       => 'script',
            //
            'htaccess'  => 'script',
            //
            'sql'       => 'data',
            'csv'       => 'data',
            //
            'txt'       => 'document',
            'log'       => 'document',
            // binary
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
            'rar'       => 'compress',
            'zip'       => 'compress',
            'tar'       => 'compress',
            'gz'        => 'compress',
            'gz2'       => 'compress',
        );

        $extension = $item->getExtension();
        if ( isset($mapping[$extension]) ) {
            $attrib = $mapping[$extension];
        }

        // photo
        if ( in_array($extension, array('jpg','jpeg') ) ) {
            // TODO: 有 exif 是 photo
            // 過小不是 photo
            return 'photo';
        }

        return $attrib;
    }

    /**
     *  由檔案名稱取得圖片路徑, 如果是空值, 則傳回空字串
     *  @param image name
     *  @return string
     */
    public static function getLinkByAttrib( $attrib )
    {
        if ( !$attrib ) {
            return '';
        }

        return '<img src="'. UrlManager::imageUri( '/type/' . $attrib . '.png' ) .'" />';
    }


}
