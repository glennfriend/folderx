<?php

    if ( file_exists( !$item->getReal() ) ) {
        echo 'file is not exist';
        exit;
    }

    $mtime = date("Y-m-d H:i:s", $item->getMtime() );
    $imageAttrib = FileTypeImageHelper::getAttribByItem( $item );
    $imageLink = FileTypeImageHelper::getLinkByAttrib( $imageAttrib );
    $timeAfter = TimeBeforeHelper::get( $item->getMtime() );

    $fileSize = '';
    if ( 'folder' !== $imageAttrib ) {
        $fileSize = $item->getFileSize();
    }

    $smb = '\\\\training.simplybridal.com\\public';
    $smb .= str_replace('/', '\\', $item->getUri() );
    $smb .= '\\' . $item->getFullName();

    echo <<<EOD
        <div class="col-md-12">
            <div class="thumbnail">
                <h4>
                    {$imageLink}
                    <span>{$item->getFullName()}</span>
                </h4>
                <p>{$timeAfter}</p>
                <p>{$smb}</p>
                <p>{$fileSize}</p>
            </div>
        </div>
EOD;

    contentDisplaySwitch( $item );
    return;

    /**
     *  用該方式取代煩長的 if else
     */
    function contentDisplaySwitch( $item )
    {
        $type = FileTypeDisplayHelper::getAttribByItem( $item );
        $fn = $type .'Option';
        if ( function_exists($fn) ) {
            $fn( $item );
        }
    }

    /**
     *  display by folder
     */
    function folderOption( $item )
    {
        $path = $item->getReal();
        $escapePath = str_replace(" ", "\\ ", $path );
        $escapePath = str_replace("(", "\\(", $escapePath );
        $escapePath = str_replace(")", "\\)", $escapePath );

        $cmd = 'ls -lha --group-directories-first '. $escapePath . '/';
        if(true) {
            ob_start();
                system($cmd);
                $output = ob_get_contents();
            ob_end_clean();
        }
        echo <<<EOD
            <div class="col-md-12">
                <pre>{$output}</pre>
            </div>
EOD;

        // $dirs  = glob( $path . "/*", GLOB_ONLYDIR );
        // $files = array_filter(glob( $path . "/*"), 'is_file');
    }

    /**
     *  image by folder
     */
    function imageOption( $item )
    {
        $size = $item->getSize();
        $output = '';
        $max = 10485760;    // 10*1024*1024 Mb
        if ( $size > $max ) {
            $output = '';
            echo '<div class="col-md-12">圖片過大不顯示</div>';
        }
        else {
            $mime = mime_content_type( $item->getReal() );
            $output = base64_encode(file_get_contents( $item->getReal() ));
            echo <<<EOD
                <div class="col-md-12">
                    <img src="data:{$mime};base64,{$output}" style="border:2px dashed #000000;" />
                </div>
EOD;
        }


    }

    /**
     *  
     */
    function textOption( $item )
    {
        $size = $item->getSize();
        $output = '';
        $max = 1048576; // 1 Mb
        if ( $size > $max ) {
            $output = file_get_contents_size( $item->getReal() );
            $output = convert_utf8( $output );
            $output = trim($output) . "\n============================== (只取得部份內容) ==============================";
        }
        else {
            $output = file_get_contents( $item->getReal() );
            $output = convert_utf8( $output );
        }
        
        $output = htmlspecialchars( $output );
        echo <<<EOD
            <div class="col-md-12">
                <pre>{$output}</pre>
            </div>
EOD;
    }

    /**
     *  text
     */
    function scriptOption( $item )
    {
        textOption( $item );
    }

    /**
     *  將文字內容轉換為 utf-8 編碼
     */
    function convert_utf8( $text )
    {
        mb_detect_order(array(
            'UTF-8',
            'BIG-5',
            'ASCII',
        ));

        $textEncoding = mb_detect_encoding($text);
        if ( 'UTF-8' != $textEncoding ) {
            $text = mb_convert_encoding( $text, 'UTF-8', $textEncoding );
        }
        return $text;
    }

    /**
     *  取得檔案的部份內容, 只適用於文字檔
     */
    function file_get_contents_size( $file, $size=0 )
    {
        $size = (int) $size;
        if ( !$size ) {
            $size = 10240;  // 10kb
        }
        if ( $handle = fopen( $file, 'r') ) {
            $buffer = fread($handle, $size);
            fclose($handle);
            return $buffer;
        }
    }
