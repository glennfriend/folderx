<?php

    $searchKey = '';
    if ( $this->pageLimit ) {
        $searchKey = $this->pageLimit->getParam('searchKey');
    }

?>
    <style type="text/css">
        .ui-tooltip .ui-tooltip-content,
        .ui-tooltip p,
        .ui-tooltip ul,
        .ui-tooltip li,
        .ui-tooltip,
        .qtip {
            max-width: 380px;
            font-size: 32px;
            line-height: 34px;
        }
    </style>

    <script type="text/javascript" charset="utf-8">
        "use strict";

        $(function() {

            // qtip
            var selector = 'span[title]';
            var setting = {
                'content':  {attr: 'title'},
                'position': {'my': 'bottom center', 'at': 'top center'},
                'style':    {classes: 'qtip-shadow qtip-dark'}
            };
            $(selector).qtip(setting);

        });
    </script>

    <div class="row">
        <form name="form-search" id="form-search" class="form-inline" method="get">

            <div class="col-md-4"></div>

            <div class="col-md-4">
                <div class="form-group">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" placeholder="想要搜尋什麼內容" name="searchKey" value="<?php echo $searchKey; ?>" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <a href="<?php echo $this->createUrl('/home/reindex'); ?>">重新索引</a>
            </div>

        </form>
    </div>
    <hr/>

    <div class="row">
    <?php

        $baseResourceUrl = UrlManager::baseResourceUrl();
        foreach ( $items as $item ) {
            //echo '<div class="col-md-3" style="background-color:#abcdef">'; pr($item); echo '</div>';

            $mtime = date("Y-m-d H:i:s", $item->getMtime() );
            $url = url('/home/resource', array('key'=>$item->getKey()) );
            $imageAttrib = FileTypeImageHelper::getAttribByItem( $item );
            $imageLink = FileTypeImageHelper::getLinkByAttrib( $imageAttrib );
            
            $fileSize = '';
            if ( $item->getSize() > 0 ) {
                $fileSize = $item->getFileSize();
            }

            $timeAfter = TimeBeforeHelper::get( $item->getMtime() );

            if ( mb_strlen( $item->getFullName() )<=30 ) {
                $showFull  = '';
                $showShort = $item->getFullName();
            }
            else {
                $showFull  = $item->getFullName();
                $showShort = mb_substr( $showFull, 0, 30 ) . '...';
            }

            echo <<<EOD
                <div class="col-md-3">
                    <div class="thumbnail">
                        <h4>
                            <span title="{$item->getUri()}">{$imageLink}</span>
                            <a href="{$url}"><span title="{$showFull}">{$showShort}</span></a>
                        </h4>
                        <p>{$fileSize} &nbsp; {$timeAfter}</p>
                    </div>
                </div>
EOD;
        }

    ?>
    </div>

    <?php echo cc('displayPageLimit', $this->pageLimit ); ?>
