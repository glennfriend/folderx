<?php

// 未使用
exit;

class File
{

    const FILE_DEFAULT_DIRECTORY_CREATION_MODE = 0700;

    /**
     *  
     */
    function isDir( $file )
    {
        return is_dir( $file );
    }

    /**
     *  
     */
    function isLink( $file )
    {
        return is_link( $file );
    }

    /**
     * return true if the file is readable. Can be used as static if a
     * filename is provided
     *
     * @param $file 
     * @return true if readable or false otherwise
     */
    function isReadable( $file )
    {
        clearstatcache();
        return is_readable( $file );
    }

    /**
     * removes a file. Can be used as static if a filename is provided.
     * Otherwise it will remove the file that was given in the constructor
     *
     * @param $file
     * @return True if successful or false otherwise
     */
    function delete( $file )
    {
        if( !File::isReadable( $file )) {
            return false;
        }

        if( File::isLink( $file )) {
            $result = @unlink( $file );
        }
        elseif( File::isDir( $file )) {
            $result = @rmdir( $file );
        }
        else {
            $result = @unlink( $file );
        }

        return $result;
    }


    /**
     * returns true if the file exists.
     *
     * Can be used as an static method if a file name is provided as a
     *  parameter
     * @param fileName optinally, name of the file whose existance we'd
     * like to check
     * @return true if successful or false otherwise
     * @static
     */
    static function exists( $file )
    {
        clearstatcache();
        return file_exists( $file );
    }


    /**
     * Creates a new folder. If the folder name is /a/b/c and neither
     * /a or /a/b exist, this method will take care of creating the
     * whole folder structure automatically.
     *
     * @static
     * @param dirName The name of the new folder
     * @param mode Attributes that will be given to the folder
     * @param recursive
     * @return Returns true if no problem or false otherwise.
     */
    function createDir( $dirName,
                        $mode = File::FILE_DEFAULT_DIRECTORY_CREATION_MODE,
                        $recursive = true )
    {
        
        // realpath( $dirName )
        
        if( File::exists( $dirName )) return true;

        if( substr($dirName, strlen($dirName)-1) == "/" ) {
            $dirName = substr($dirName, 0,strlen($dirName)-1);
        }

        if( $recursive ) {
            // for example, we will create dir "/a/b/c"
            // $firstPart = "/a/b"
            $firstPart = substr( $dirName,0,strrpos($dirName, "/") );

            if(file_exists($firstPart)) {
                if( !@mkdir($dirName,$mode) ) {
                   return false;
                }
                @chmod( $dirName, $mode );
            }
            else {
                File::createDir( $firstPart, $mode, $recursive );
                if( !@mkdir($dirName,$mode) ) {
                   return false;
                }
                @chmod( $dirName, $mode );
            }
       }
       else {
           if( @mkdir( $dirName )) {
               @chmod( $dirName, $mode );
               return( true );
           }
           else {
               return( false );
           }
       }

        return true;
    }



}
