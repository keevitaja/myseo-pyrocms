<section class="title">
    <h4><?php echo lang('myseo:title_pages'); ?></h4>
</section>

<section class="item">
    <div class="content">
        <fieldset id="filters">
            <legend><?php echo lang('myseo:title_filters'); ?></legend>

            <?php echo form_open('admin/myseo/filters_update', 'id="myseo_options"') ?>
                <table class="table-list" border="0" cellspacing="0">
                    <tr class="myseo-header-row">
                        <th class="myseo-label"><?php echo lang('myseo:label_name'); ?></th>
                        <th><?php echo lang('myseo:label_value'); ?></th>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_filters'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td><?php echo lang('myseo:label_hide_drafts'); ?></td>
                        <td><?php echo form_checkbox(array('name' => 'hide_drafts', 'value' => 1, 'checked' => $myseo_options['hide_drafts'])); ?></td>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_filters'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td><?php echo lang('myseo:label_filter_by_top_page'); ?></td>
                        <td><?php echo form_dropdown('filter_by_top_page', $top_pages, $myseo_options['filter_by_top_page']); ?></td>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_filters'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td><?php echo lang('myseo:label_filter_by_title'); ?></td>
                        <td><?php echo form_input(array('name' => 'filter_by_title', 'value' => $myseo_options['filter_by_title'])); ?></td>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_filters'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td><?php echo lang('myseo:label_filter_by_uri'); ?></td>
                        <td><?php echo form_input(array('name' => 'filter_by_uri', 'value' => $myseo_options['filter_by_uri'])); ?></td>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_filters'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td class="myseo-update-status"><?php echo $filters_update_status; ?></td>
                        <td><?php echo form_button(array('name' => 'myseo_filters_update', 'type' => 'submit', 'class' => 'btn blue'), lang('myseo:button_filter')); ?></td>
                    </tr>
                </table>
            <?php echo form_close(); ?>

        </fieldset>

        <?php foreach ($pages as $page): ?>
            <?php $id = $page['id']; ?>
            <?php echo form_open('admin/myseo/page_update', 'class="myseo-page"') ?>
                <?php echo form_hidden('id', $id); ?>
                <?php echo form_hidden('url', site_url('admin/myseo/page_update')); ?>

                <a name="ID<?php echo $id; ?>"></a>
                <table class="table-list myseo-data-table" cellspacing="0" border="0">
                    <tr class="myseo-header-row">
                        <th colspan="2">ID#<?php echo $id; ?> - <?php echo $page['title']; ?> ( <span class="myseo-slug"><?php echo $page['uri']; ?></span> )</th>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_rows'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td class="myseo-label"><?php echo lang('myseo:label_meta_title'); ?> ( <span class="myseo-field-count myseo-count-title-<?php echo $page['id']; ?>"><?php echo strlen($page['meta_title']); ?></span> )</td>
                        <td><?php echo form_input(array('name' => 'title', 'class' => 'myseo-title-' . $id, 'value' => $page['meta_title'])); ?></td>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_rows'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td><?php echo lang('myseo:label_meta_keywords'); ?></td>
                        <td><?php echo form_input(array('name' => 'keywords', 'value' => $page['meta_keywords'])); ?></td>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_rows'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td><?php echo lang('myseo:label_meta_desc'); ?> ( <span class="myseo-field-count myseo-count-desc-<?php echo $id; ?>"><?php echo strlen($page['meta_description']); ?></span> )</td>
                        <td><?php echo form_textarea(array('name' => 'description', 'class' => 'myseo-desc-' . $id, 'value' => $page['meta_description'], 'rows' => '2')); ?></td>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_rows'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td><?php echo lang('myseo:label_meta_no_index'); ?></td>
                        <td><?php echo form_checkbox(array('name' => 'robots_no_index', 'value' => 1, 'checked' => $page['meta_robots_no_index'])); ?></td>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_rows'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td><?php echo lang('myseo:label_meta_no_follow'); ?></td>
                        <td><?php echo form_checkbox(array('name' => 'robots_no_follow', 'value' => 1, 'checked' => $page['meta_robots_no_follow'])); ?></td>
                    </tr>
                    <tr class="myseo-data-row<?php if($myseo_options['auto_hide_rows'] == 1) { echo ' myseo-hidden'; } ?>">
                        <td class="myseo-status"><?php if (isset($meta_update_status[$id])) { echo $meta_update_status[$id]; } ?></td>
                        <td><?php echo form_button(array('name' => 'myseo_page_update', 'type' => 'submit', 'class' => 'btn blue'), lang('myseo:button_save')); ?></td>
                    </tr>
                </table>

            <?php echo form_close(); ?>

            <script type="text/javascript">
                $(document).ready(function() {
                    $('input.myseo-title-<?php echo $id; ?>').simplyCountable({
                        counter:            'span.myseo-count-title-<?php echo $page['id']; ?>',
                        maxCount:           <?php echo $myseo_options['max_title_len']; ?>
                    });

                    $('textarea.myseo-desc-<?php echo $id; ?>').simplyCountable({
                        counter:            'span.myseo-count-desc-<?php echo $page['id']; ?>',
                        maxCount:           <?php echo $myseo_options['max_desc_len']; ?>
                    });
                });
            </script>

        <?php endforeach; ?>

        <?php echo $pagination; ?>

    </div>
</section>