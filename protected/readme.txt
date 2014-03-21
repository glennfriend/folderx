FolderX

folder index project

    對所有檔案進行 index
    對所有目錄進行 index
    每個 目錄名稱 都會成為該 目標索引檔案 的 tag name
    對 文字的檔案內容 做 重點 or 有內容上限 的 cache
    合併資料夾內多個圖片, 建立該縮圖
    可以忽略的白名單檔案 如 "Thumbs.db"
    忽略穩藏檔案
    對某些格式可以顯示部份內容 如 txt htm html php read.me


取得檔案資訊指令

    http://www.php.net/manual/zh/function.glob.php
    http://www.php.net/manual/zh/function.stat.php


索引做法

    1. 從某個目錄開始做搜尋並且 index or reindex
    2. 對所有資料表中的欄位做 檔案是否存在 做測試, 不存在就刪除, 以避免產生 幽靈檔案/幽靈目錄
    以上兩個動作沒有順序依存


搜尋做法

    搜尋的 keyword 如果不是 檔名 跟 路徑 都有找到, 則不顯示
        否則會出現 搜尋到一個目錄名稱, 而目錄下所有檔案都被列出來

    搜尋 .gif 則列出副檔名為 gif 的檔案



