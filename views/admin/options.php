<section class="title">
    <h4><?php echo lang('myseo:title_options'); ?></h4>
</section>

<section class="item">
    <div class="content">

        <?php echo form_open('admin/myseo/options_update', 'id="myseo_options"') ?>
            <table class="table-list" border="0" cellspacing="0">
                <tr>
                    <th class="myseo-label"><?php echo lang('myseo:label_name'); ?></th>
                    <th><?php echo lang('myseo:label_value'); ?></th>
                </tr>
                <tr>
                    <td><?php echo lang('myseo:label_auto_hide_rows'); ?></td>
                    <td><?php echo form_checkbox(array('name' => 'auto_hide_rows', 'value' => 1, 'checked' => $myseo_options['auto_hide_rows'])); ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('myseo:label_auto_hide_filters'); ?></td>
                    <td><?php echo form_checkbox(array('name' => 'auto_hide_filters', 'value' => 1, 'checked' => $myseo_options['auto_hide_filters'])); ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('myseo:label_max_title_len'); ?></td>
                    <td><?php echo form_input(array('name' => 'max_title_len', 'value' => $myseo_options['max_title_len'])); ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('myseo:label_max_desc_len'); ?></td>
                    <td><?php echo form_input(array('name' => 'max_desc_len', 'value' => $myseo_options['max_desc_len'])); ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('myseo:label_pagination_limit'); ?></td>
                    <td><?php echo form_input(array('name' => 'pagination_limit', 'value' => $myseo_options['pagination_limit'])); ?></td>
                </tr>
                <tr>
                    <td class="myseo-update-status"><?php echo $options_update_status; ?></td>
                    <td><?php echo form_button(array('name' => 'myseo_options_update', 'type' => 'submit', 'class' => 'btn blue'), lang('myseo:button_save')); ?></td>
                </tr>
            </table>
        <?php echo form_close(); ?>

    </div>
</section>