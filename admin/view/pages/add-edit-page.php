<?php use CMS\Models\Pages\Page; ?>

<script type="text/javascript" src="<?= ADMIN . "/vendor/tinymce/tinymce/tinymce.min.js"; ?>"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste",

        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        paste_data_images: true,
        relative_urls: false,
        convert_urls: true
    });
</script>
<script type="text/javascript" src="<?= JS . "preview.js"; ?>"></script>
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <?php
            $page= $data['page'];
                print_r($pages);
            (isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
            if (isset($_POST['submit'])) {
                echo '<div class="medium">';
                echo implode(",", $data['messages']);
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <?php (isset($params[0]) && isset($params[1])) ? $action = ADMIN . 'pages/edit/' . $page->get_id() : $action = ADMIN . 'pages/create'; ?>
            <iframe id="target">hello</iframe>
            <form id="create-form" action="<?= $action; ?>" method="post">
                <input type="checkbox" name="front-end"/>
                <label for="front-end">Front-End Page</label>
                <input type="checkbox" name="back-end"/>
                <label for="back-end">Back-End Page</label>
                <select id="pages" name="sub-page">
                    <option name="none" value="None">None</option>
                    <?php $options = Page::getSelection('none'); ?>
                </select>
                <input type="text" name="title" placeholder="Page Title" value="<?= $page->keep($page->title); ?>"/><br/>
                <input type="text" name="description" placeholder="Page Description (max 160 characters)" value="<?= $page->keep($page->description); ?>"/><br/>
                <textarea type="text" name="content" placeholder="Content"><?= $page->keep($page->content); ?></textarea><br/>
                <input type="text" name="url" placeholder="URL" value=""/><br/>
                <input type="text" name="template" placeholder="Template URL" value=""/><br/>
                <button type="submit" name="submit">Create Page</button>
            </form>
        </div>
    </div>
</div>
