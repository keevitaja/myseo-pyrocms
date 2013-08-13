<section class="title">
    <h4><?php echo lang('myseo:options:title'); ?></h4>
</section>

<section class="item">
    <section class="content">
        <?php echo form_open(site_url('admin/myseo/options/update')); ?>
            <fieldset>
                <div class="form_inputs">
                    <ul>
                        <li>
                            <label for="max_title_len"><?php echo lang('myseo:options:fields:title'); ?>
                                <small><?php echo lang('myseo:options:fields:long:title'); ?></small>
                            </label>
                            <div class="input type-text">
                                <?php echo form_input(array(
                                    'name' => 'max_title_len',
                                    'id' => 'max_title_len',
                                    'value' => $options->max_title_len
                                )); ?>
                            </div>
                        </li>
                        <li>
                            <label for="max_desc_len"><?php echo lang('myseo:options:fields:desc'); ?>
                                <small><?php echo lang('myseo:options:fields:long:desc'); ?></small>
                            </label>
                            <div class="input type-text">
                                <?php echo form_input(array(
                                    'name' => 'max_desc_len',
                                    'id' => 'max_desc_len',
                                    'value' => $options->max_desc_len
                                )); ?>
                            </div>
                        </li>
                        <li>
                            <label for="pagination_limit"><?php echo lang('myseo:options:fields:pagination'); ?>
                                <small><?php echo lang('myseo:options:fields:long:pagination'); ?></small>
                            </label>
                            <div class="input type-text">
                                <?php echo form_input(array(
                                    'name' => 'pagination_limit',
                                    'id' => 'pagination_limit',
                                    'value' => $options->pagination_limit
                                )); ?>
                            </div>
                        </li>
                        <li>
                            <label for="auto_collapse"><?php echo lang('myseo:options:fields:collapse'); ?>
                                <small><?php echo lang('myseo:options:fields:long:collapse'); ?></small>
                            </label>
                            <div class="input type-checkbox">
                                <label><?php echo form_checkbox(array(
                                        'name' => 'auto_collapse',
                                        'id' => 'auto_collapse',
                                        'value' => 1,
                                        'checked' => $options->auto_collapse
                                    )); ?></label>
                            </div>
                        </li>
                    </ul>
                </div>
            </fieldset>
            <div class="buttons">
                <?php echo form_button(array(
                    'name' => 'submit',
                    'type' => 'submit',
                    'content' => lang('myseo:save'),
                    'class' => 'btn blue'
                )); ?>
            </div>
        <?php echo form_close(); ?>
    </section>
</section>