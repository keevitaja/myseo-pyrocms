<section class="title">
    <h4><?php echo lang('myseo:' . $type . ':title'); ?></h4>
</section>

<section class="item">
    <section class="content">
        <div class="tabs">
            <ul class="tab-menu">
                <li><a href="#myseo-list"><?php echo lang('myseo:' . $type . ':tabs:list'); ?></a></li>
                <li><a href="#myseo-filters"><?php echo lang('myseo:' . $type . ':tabs:filters'); ?></a></li>
            </ul>
            <div id="myseo-list">
                <fieldset>
                    <?php foreach ($list as $item): ?>
                        <?php echo form_open(site_url('admin/myseo/' . $type . '/update/' . $item->id)); ?>
                            <?php echo form_hidden('url', site_url('admin/myseo/' . $type . '/update/' . $item->id)); ?>
                            <table class="table-list myseo-item<?php if($options->auto_collapse) { echo ' myseo-item-hidden'; } ?>" border="0" cellspacing="0">
                                <tr class="myseo-header-row">
                                    <th colspan="2">ID#<?php echo $item->id; ?> - <?php echo $item->the_title; ?> ( <span class="myseo-slug"><?php echo $item->the_uri; ?></span> )</th>
                                </tr>
                                <tr class="myseo-row">
                                    <td class="myseo-row-label">
                                        <?php echo lang('myseo:fields:title'); ?> ( <span class="myseo-count myseo-count-title-<?php echo $item->id; ?>"></span> )
                                    </td>
                                    <td>
                                        <?php echo form_input(array(
                                            'name' => 'meta_title',
                                            'value' => $item->$fields['title'],
                                            'class' => 'myseo-input-text myseo-title-' . $item->id
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="myseo-row">
                                    <td class="myseo-row-label">
                                        <?php echo lang('myseo:fields:keywords'); ?> ( <span class="myseo-count myseo-count-keywords-<?php echo $item->id; ?>"></span> )
                                    </td>
                                    <td>
                                        <?php echo form_input(array(
                                            'name' => 'meta_keywords',
                                            'value' => $item->$fields['keywords'],
                                            'class' => 'myseo-input-text myseo-keywords-' . $item->id
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="myseo-row">
                                    <td class="myseo-row-label">
                                        <?php echo lang('myseo:fields:desc'); ?> ( <span class="myseo-count myseo-count-desc-<?php echo $item->id; ?>"></span> )
                                    </td>
                                    <td>
                                        <?php echo form_textarea(array(
                                            'name' => 'meta_description',
                                            'value' => $item->$fields['description'],
                                            'class' => 'myseo-input-textarea myseo-desc-' . $item->id,
                                            'rows' => '2'
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="myseo-row">
                                    <td class="myseo-row-label">
                                        <?php echo lang('myseo:fields:index'); ?>
                                    </td>
                                    <td>
                                        <?php echo form_checkbox(array(
                                            'name' => 'meta_robots_no_index',
                                            'value' => 1,
                                            'checked' => $item->$fields['no_index'],
                                            'class' => 'myseo-input-checkbox'
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="myseo-row">
                                    <td class="myseo-row-label">
                                        <?php echo lang('myseo:fields:follow'); ?>
                                    </td>
                                    <td>
                                        <?php echo form_checkbox(array(
                                            'name' => 'meta_robots_no_follow',
                                            'value' => 1,
                                            'checked' => $item->$fields['no_follow'],
                                            'class' => 'myseo-input-checkbox'
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="myseo-row">
                                    <td class="myseo-update-status"></td>
                                    <td>
                                        <?php echo form_button(array(
                                            'name' => 'myseo_page_update',
                                            'type' => 'submit',
                                            'content' => lang('myseo:save'),
                                            'class' => 'btn blue'
                                        )); ?>
                                    </td>
                                </tr>
                            </table>
                        <?php echo form_close(); ?>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('input.myseo-title-<?php echo $item->id; ?>').simplyCountable({
                                    counter:            'span.myseo-count-title-<?php echo $item->id; ?>',
                                    maxCount:           <?php echo $options->max_title_len; ?>
                                });

                                $('input.myseo-keywords-<?php echo $item->id; ?>').simplyCountable({
                                    counter:            'span.myseo-count-keywords-<?php echo $item->id; ?>',
                                    maxCount:           250, // pyrocms keyword limit
                                    strictMax:          true
                                });

                                $('textarea.myseo-desc-<?php echo $item->id; ?>').simplyCountable({
                                    counter:            'span.myseo-count-desc-<?php echo $item->id; ?>',
                                    maxCount:           <?php echo $options->max_desc_len; ?>
                                });
                            });
                        </script>
                    <?php endforeach; ?>
                    <?php echo $pagination; ?>
                </fieldset>
            </div>
            <div id="myseo-filters">
                <?php echo form_open('admin/myseo/' . $type . '/update_filters'); ?>
                <fieldset>
                    <div class="form_inputs">
                        <ul>
                            <?php foreach ($filters as $filter): ?>
                                <li>
                                    <label for="<?php echo $filter['name']; ?>"><?php echo lang($filter['title']); ?>
                                        <small><?php echo lang($filter['title_long']); ?></small>
                                    </label>
                                    <div class="input type-text"><?php echo $filter['input_field']; ?></div>
                                </li>
                            <?php endforeach; ?>
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
            </div>
        </div>
    </section>
</section>