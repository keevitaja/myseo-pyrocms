<section class="title">
    <h4><?php echo $module_details['name'] ?></h4>
</section>

<section class="item">
    <div class="content">
        <fieldset id="filters">
            <legend><?php echo lang('myseo:options_title'); ?></legend>
            <?php echo form_open($this->uri->uri_string() . '/update_settings', 'class="meta_information" id="myseo_settings"') ?>
                <table class="table-list" cellspacing="0" border="0" style="width: 49%; float: left;">
                    <tr>
                        <th colspan="2"><?php echo lang('myseo:options_filters'); ?></th>
                    </tr>
                    <tr>
                        <td style="width: 200px;"><?php echo lang('myseo:label_hide_drafts'); ?></td>
                        <td><?php echo form_checkbox(array('name' => 'hide_drafts', 'value' => 1, 'checked' => $settings['hide_drafts'])); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('myseo:label_top_page'); ?></td>
                        <td><?php echo form_dropdown('top_page', $top_pages, $settings['top_page']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('myseo:label_filter_by_title'); ?></td>
                        <td><?php echo form_input(array('name' => 'filter_by_title', 'value' => $settings['filter_by_title'])); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $settings_update_status; ?></td>
                        <td><?php echo form_button(array('name' => 'settings_update', 'type' => 'submit', 'class' => 'btn blue'), lang('myseo:button_save')); ?></td>
                    </tr>
                </table>
                <table class="table-list" cellspacing="0" border="0" style="width: 49%;float: right;">
                    <tr>
                        <th colspan="2"><?php echo lang('myseo:options_settings'); ?></th>
                    </tr>
                    <tr>
                        <td style="width: 200px;"><?php echo lang('myseo:label_max_title'); ?></td>
                        <td><?php echo form_input(array('name' => 'max_title_len', 'value' => $settings['max_title_len'])); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('myseo:label_max_desc'); ?></td>
                        <td><?php echo form_input(array('name' => 'max_desc_len', 'value' => $settings['max_desc_len'])); ?></td>
                    </tr>
                </table>
            <?php echo form_close(); ?>
        </fieldset>

        <br />

        <?php foreach ($pages as $page): ?>
            <?php $id = $page['id']; ?>
            <?php echo form_open($this->uri->uri_string() . '/update_page', 'class="meta_information"') ?>
            <?php echo form_hidden('id', $id); ?>
            <?php echo form_hidden('url', $this->uri->uri_string() . '/update_page'); ?>

            <a name="ID<?php echo $id; ?>"></a>
            <table class="table-list" cellspacing="0" border="0">
                <tr>
                    <th colspan="2">ID#<?php echo $id; ?> - <?php echo $page['title']; ?> ( <span style="font-weight: normal; color: #777;"><?php echo $page['uri']; ?></span> )</th>
                </tr>
                <tr>
                    <td style="width: 200px;"><?php echo lang('myseo:label_meta_title'); ?> ( <span class="count_title_<?php echo $page['id']; ?>" style="font-weight: bold;"><?php echo strlen($page['meta_title']); ?></span> )</td>
                    <td><?php echo form_input(array('name' => 'title', 'class' => 'input_title_' . $id, 'value' => $page['meta_title'] , 'style' => 'width: 97%')); ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('myseo:label_meta_keywords'); ?></td>
                    <td><?php echo form_input(array('name' => 'keywords', 'value' => $page['meta_keywords'] , 'style' => 'width: 97%')); ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('myseo:label_meta_desc'); ?> ( <span class="count_description_<?php echo $id; ?>" style="font-weight: bold;"><?php echo strlen($page['meta_description']); ?></span> )</td>
                    <td><?php echo form_textarea(array('name' => 'description', 'class' => 'input_description_' . $id, 'value' => $page['meta_description'] , 'style' => 'width: 97%;', 'rows' => '2')); ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('myseo:label_meta_no_index'); ?></td>
                    <td><?php echo form_checkbox(array('name' => 'robots_no_index', 'value' => 1, 'checked' => $page['meta_robots_no_index'])); ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('myseo:label_meta_no_follow'); ?></td>
                    <td><?php echo form_checkbox(array('name' => 'robots_no_follow', 'value' => 1, 'checked' => $page['meta_robots_no_follow'])); ?></td>
                </tr>
                <tr>
                    <td class="status"><?php if (isset($meta_update_status[$id])) { echo $meta_update_status[$id]; } ?></td>
                    <td><?php echo form_button(array('name' => 'meta_update', 'type' => 'submit', 'class' => 'btn blue'), lang('myseo:button_save')); ?></td>
                </tr>
            </table>
            <?php echo form_close(); ?>

            <script type="text/javascript">
                $(document).ready(function() {
                    $('input.input_title_<?php echo $id; ?>').simplyCountable({
                        counter:            'span.count_title_<?php echo $page['id']; ?>',
                        maxCount:           <?php echo $settings['max_title_len']; ?>
                    });

                    $('textarea.input_description_<?php echo $id; ?>').simplyCountable({
                        counter:            'span.count_description_<?php echo $page['id']; ?>',
                        maxCount:           <?php echo $settings['max_desc_len']; ?>
                    });
                });
            </script>

            <br />
        <?php endforeach; ?>

    </div>
</section>