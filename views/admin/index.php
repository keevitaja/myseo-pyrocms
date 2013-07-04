<section class="title">
    <h4><?php echo $module_details['name'] ?></h4>
</section>

<section class="item">
    <div class="content">
        <fieldset id="filters">
            <legend>Filters & Settings</legend>
                <?php echo form_open($this->uri->uri_string() . '/update_settings', 'class="meta_information" id="myseo_settings"') ?>
                <table class="table-list" cellspacing="0" border="0">
                    <tr>
                        <th style="width: 200px;">Name</th>
                        <th>Value</th>
                    </tr>
                    <tr>
                        <td>Hide drafts</td>
                        <td><?php echo form_checkbox(array('name' => 'hide_drafts', 'value' => 1, 'checked' => $settings['hide_drafts'])); ?></td>
                    </tr>
                    <tr>
                        <td>Filter by top page</td>
                        <td><?php echo form_dropdown('top_page', $top_pages, $settings['top_page']); ?></td>
                    </tr>
                    <tr>
                        <td>Filter by title</td>
                        <td><?php echo form_input(array('name' => 'filter_by_title', 'value' => $settings['filter_by_title'])); ?></td>
                    </tr>
                    <tr>
                        <td><?php $msg = $this->session->flashdata('settings_update_status'); if($msg) { echo $msg; } else { echo '&nbsp;'; } ?></td>
                        <td><button name="settings_update" type="submit" class="btn blue">Save</button></td>
                    </tr>
                </table>
            <?php echo form_close(); ?>
        </fieldset>

        <br />

        <?php foreach ($pages as $page): ?>
            <?php echo form_open($this->uri->uri_string() . '/update_page', 'class="meta_information"') ?>
            <?php echo form_hidden('id', $page['id']); ?>
            <?php echo form_hidden('url', $this->uri->uri_string() . '/update_page'); ?>

            <table class="table-list" cellspacing="0" border="0">
                <tr>
                    <th colspan="2">ID#<?php echo $page['id']; ?> - <?php echo $page['title']; ?> ( <span style="font-weight: normal; color: #777;"><?php echo $page['uri']; ?></span> )</th>
                </tr>
                <tr>
                    <td style="width: 200px;">Meta title ( <span class="count_title_<?php echo $page['id']; ?>" style="font-weight: bold;"><?php echo strlen($page['meta_title']); ?></span> )</td>
                    <td><?php echo form_input(array('name' => 'title', 'class' => 'input_title_' . $page['id'], 'value' => $page['meta_title'] , 'style' => 'width: 97%')); ?></td>
                </tr>
                <tr>
                    <td>Meta keywords</td>
                    <td><?php echo form_input(array('name' => 'keywords', 'value' => $page['meta_keywords'] , 'style' => 'width: 97%')); ?></td>
                </tr>
                <tr>
                    <td>Meta description ( <span class="count_description_<?php echo $page['id']; ?>" style="font-weight: bold;"><?php echo strlen($page['meta_description']); ?></span> )</td>
                    <td><?php echo form_textarea(array('name' => 'description', 'class' => 'input_description_' . $page['id'], 'value' => $page['meta_description'] , 'style' => 'width: 97%;', 'rows' => '2')); ?></td>
                </tr>
                <tr>
                    <td>Robots: Don't index</td>
                    <td><?php echo form_checkbox(array('name' => 'robots_no_index', 'value' => 1, 'checked' => $page['meta_robots_no_index'])); ?></td>
                </tr>
                <tr>
                    <td>Robots: Don't follow</td>
                    <td><?php echo form_checkbox(array('name' => 'robots_no_follow', 'value' => 1, 'checked' => $page['meta_robots_no_follow'])); ?></td>
                </tr>
                <tr>
                    <td class="status"><?php $msg = $this->session->flashdata('meta_update_status'); if($msg) { echo $msg; } else { echo '&nbsp;'; } ?></td>
                    <td><button class="btn blue" name="meta_update">Save</button></td>
                </tr>
            </table>
            <?php echo form_close(); ?>

            <script type="text/javascript">
                $(document).ready(function() {
                    $('input.input_title_<?php echo $page['id']; ?>').simplyCountable({
                        counter:            'span.count_title_<?php echo $page['id']; ?>',
                        maxCount:           69
                    });

                    $('textarea.input_description_<?php echo $page['id']; ?>').simplyCountable({
                        counter:            'span.count_description_<?php echo $page['id']; ?>',
                        maxCount:           156
                    });
                });
            </script>

            <br />
        <?php endforeach; ?>

    </div>
</section>