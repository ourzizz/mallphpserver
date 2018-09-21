<h2><?php echo "文件内容"; ?></h2>

<?php foreach ($files as $files_item): ?>

    <div class="main">
    <h3><?php echo $files_item['pubtime']; ?></h3>
       <a href="<?php echo site_url('demo/show_article/'.$files_item['fileid']); ?>"> <?php echo $files_item['filetitle']; ?></a>
    </div>

<?php endforeach; ?> 
