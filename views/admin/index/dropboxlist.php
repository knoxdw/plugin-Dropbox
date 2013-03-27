<?php
    $filePath = PLUGIN_DIR . DIRECTORY_SEPARATOR . 'Dropbox' . DIRECTORY_SEPARATOR . 'files';
    $fileNames = dropbox_dir_list($filePath);

    function isImage($file)
    {
        if(@getimagesize($file) == false)
            return false;
        else
            return true;
    }
    
?>

<?php if (!$fileNames): ?>
    <p>No files have been uploaded to the dropbox.</p>
<?php else: ?>
    <script type="text/javascript">
        function dropboxSelectAllCheckboxes(checked) {
            jQuery('#dropbox-file-checkboxes tr:visible input').each(function() {
                this.checked = checked;
            });
        }

        function dropboxFilterFiles() {
            var filter = jQuery.trim(jQuery('#dropbox-file-filter').val().toLowerCase());
            var someHidden = false;
            jQuery('#dropbox-file-checkboxes input').each(function() {
                var v = jQuery(this);
                if (filter != '') {
                    if (v.val().toLowerCase().match(filter)) {
                        v.parent().parent().show();
                    } else {
                        v.parent().parent().hide();
                        someHidden = true;
                    }
                } else {
                    v.parent().parent().show();
                }
            });

            jQuery('#dropbox-show-all').toggle(someHidden);
        }

        function dropboxNoEnter(e) {
            var e  = (e) ? e : ((event) ? event : null);
            var node = (e.target) ? e.target : ((e.srcElement) ? e.srcElement : null);
            if ((e.keyCode == 13) && (node.type=="text")) {return false;}
        }

        jQuery(document).ready(function () {
            jQuery('#dropbox-select-all').click(function () {
                dropboxSelectAllCheckboxes(this.checked);
            });

            jQuery('#dropbox-show-all').click(function () {
                jQuery('#dropbox-file-filter').val('');
                dropboxFilterFiles();
                return false;
            });

            jQuery('#dropbox-file-filter').keyup(function () {
                dropboxFilterFiles();
                return false;
            }).keypress(dropboxNoEnter);

            jQuery('.dropbox-js').show();
            jQuery('#dropbox-show-all').hide();
            
            jQuery(".filename").hover(function(){
                    jQuery('#imgprev').empty();
                    jQuery('#imgprev').append('<img src="' + jQuery(this).attr('href')+ '"/>');
                    jQuery('#imgprev img').animate({'opacity':0.7},400);
                },
                function(){
                    jQuery('#imgprev img').animate({'opacity': 0.1},300,
                        function(){ jQuery(this).remove();}
                    );
                    
            });
        });
    </script>
    <style>
        #imgprev {max-width: 400px; margin-left: 300px; top: 100px; position: fixed;}
        #imgprev img {max-height: 700px; max-width: 400px; opacity: 0.1; border: 3px solid #888; border-radius: 5px;}
    </style>
    <p class="dropbox-js" style="display:none; vertical-align:baseline; margin-bottom:0">
        Filter files by name:
        <input id="dropbox-file-filter" name="dropbox-file-filter" class="textinput" style="font-size:1em">
        <a href="#" id="dropbox-show-all" style="vertical-align:baseline">Show All</a>
    </p>
    <div id="imgprev"></div>
    <table>
        <colgroup>
            <col style="width: 2em">
        </colgroup>
        <thead>
            <tr>
                <th><input type="checkbox" id="dropbox-select-all" class="dropbox-js" style="display:none"></th>
                <th>File Name</th>
            </tr>
        </thead>
        <tbody id="dropbox-file-checkboxes">
        <?php foreach ($fileNames as $fileName): ?>
            <tr><td><input type="checkbox" name="dropbox-files[]" value="<?php echo html_escape($fileName); ?>"/></td><td>
                
                <a 
                <?php if (isImage($filePath ."/". $fileName)) { echo 'class="filename"';} ?> 
                
                href="<?php echo WEB_PLUGIN . "/Dropbox/files/" . html_escape($fileName); ?>"><?php echo html_escape($fileName); ?></a>
                
                </td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php endif;
