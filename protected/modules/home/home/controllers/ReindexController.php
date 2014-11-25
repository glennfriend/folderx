<?php

class ReindexController extends HomeBaseController
{
    /**
     *  程式上線之後，一但修改該參數，則必須要將資料表清除，重新索引
     */
    const BASE_URL = '/home/public';

    /**
     *  
     */
    public function actionIndex()
    {
        $this->actionDo();
    }

    /**
     *  reindex
     */
    public function actionDo()
    {
        $root = UrlManager::baseIndexPath();

        // clearstatcache();
        $this->accessPath( $root );
        $this->cleanItems();

        $this->render('index');
    }

    /**
     *  檢查在資料表所有的資料, 不存在的就刪除
     *
     */
    private function cleanItems()
    {
        $items = new Items();
        $allItems = $items->findItems(array(
            '_page' => -1,
        ));
        foreach( $allItems as $item )
        {
            if( !file_exists( $item->getReal() ) ) {
                $items->deleteItem( $item->getKey() );
            }
        }
    }

    /**
     *  掃描 並且 索引 實際存在的 目錄 與 檔案
     *
     *  @param $pathRule, access path , EX. '/home/vivian' => '/home/vivian/*'
     */
    private function accessPath( $pathRule )
    {
        $pathRule = $pathRule . '/*';

        // validate base path
        if ( substr($pathRule,0,strlen(UrlManager::baseIndexPath())) !== UrlManager::baseIndexPath() ) {
            die('error path rule :'. trim(strip_tags($pathRule)) );
        }

        //
        $items = new Items();
        $pathNames = glob($pathRule);
        foreach ( $pathNames as $pathName ) {

            $file = $this->makeFileByPathName( $pathName );
            if ( !$file['real'] ) {
                echo 'error : ';
                print_r( $file );
                exit;
                continue;
            }

            $item = $items->getItem( $file['key'] );
            if ( !$item ) {
                // add
                $item = $this->makeNewItem($file);
                $items->addItem( $item );
            }
            elseif( $file['mtime'] > $item->getMtime() ) {
                // update
                $item = $this->makeUpdateItem( $item, $file );
                $items->updateItem( $item );
            }
            else {
                // 不變動
            }

            //pr($item);
            if ( Item::TYPE_DIRECTORY === $item->getType() ) {
                //echo '<ul><li>';
                $this->accessPath( $item->getReal() );
                //echo '</li></ul>';
            }

        }

    }

    /**
     *  array to new item object
     */
    private function makeNewItem( $file )
    {
        $item = new Item();
        $item->setKey       ( $file['key']          );
        $item->setReal      ( $file['real']         );
        $item->setUri       ( $file['uri']          );
        $item->setName      ( $file['name']         );
        $item->setExtension ( $file['extension']    );
        $item->setType      ( $file['type']         );
        $item->setSize      ( $file['size']         );
        $item->setMtime     ( $file['mtime']        );
        $item->resetSearchKeys( UrlManager::baseIndexPath() );
        return $item;
    }

    /**
     *  array update to origin item object
     */
    private function makeUpdateItem( $item, $file )
    {
        $item->setType      ( $file['type']         );
        $item->setSize      ( $file['size']         );
        $item->setMtime     ( $file['mtime']        );
        $item->resetSearchKeys( UrlManager::baseIndexPath() );
        return $item;
    }

    /**
     *  整理出所需要的 file structure
     *
     *  註1
     *      所謂的 extension 指的是最後一個 . 符號後面的名稱
     *      檔案本身在該程式中未經過驗證
     *      所以請自行檢查驗證 檔案格式
     *
     *  註2
     *      如果它是 目錄名稱, 將不會有 extension name
     *
     */
    private function makeFileByPathName( $pathName )
    {
        $type = '';
        if ( true === is_file($pathName) ) {
            $type = Item::TYPE_FILE;
        }
        elseif ( true === is_dir($pathName) ) {
            $type = Item::TYPE_DIRECTORY;
        }

        $attrib = stat($pathName);
        $pathInfo = StringHelper::pathinfo($pathName);
        $uri = substr( $pathInfo['dirname'], strlen(UrlManager::baseIndexPath()) );
        $file = array(
            'key'       => md5($pathName),
            'real'      => $pathName,
            'uri'       => $uri,
            'name'      => $pathInfo['filename'],
            'extension' => isset($pathInfo['extension']) ? $pathInfo['extension'] : '' ,
            'type'      => $type,
            'size'      => (Item::TYPE_FILE===$type) ? $attrib['size'] : 0 ,
          //'atime'     => $attrib['atime'],
          //'ctime'     => $attrib['ctime'],
            'mtime'     => $attrib['mtime'],
        );

        // 特別處理 "目錄名稱" 狀況
        if ( $type === Item::TYPE_DIRECTORY && null !== $file['extension'] ) {
            $file['name'] .=  '.' . $file['extension'];
            $file['extension'] = '';
        }

        return $file;
    }

    private function getNormalizedSearch( $file, $fileName )
    {
        $path = substr( $file, strlen(UrlManager::baseIndexPath()) );
        $path = dirname( $path );

        $result = explode('/', $path);
        $result[] = $fileName;
        
        $result = join(',', array_unique($result) );
        $result = ltrim($result, ', ');
        if ( $result ) {
            $result .= ',';
        }

        return $result;
    }

}
