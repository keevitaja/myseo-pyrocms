/**
 * Myseo - jquery
 *
 * Copyright (c) 2013
 * http://github.com/keevitaja/sidenav-pyrocms
 *
 * @package     myseo
 * @author      Tanel Tammik <keevitaja@gmail.com>
 * @copyright   Copyright (c) 2013
 * @version     master
 * @link        http://github.com/keevitaja/myseo-pyrocms
 *
 */

$(document).ready(function()
{
    // clear request messages
    $('button[name=myseo_filters_update], button[name=myseo_options_update]').click(function(e) {
        $('td.myseo-update-status').html('&nbsp;');
    });

    // update page meta information
    $('button[name=myseo_page_update]').click(function(e)
    {
        e.preventDefault();

        $('td.myseo-status', form).html('');

        var form = $(this).parents('form:first');

        // collect data
        var form_data = {
            id                  : $('input[name=id]', form).val(),
            title               : $('input[name=title]', form).val(),
            keywords            : $('input[name=keywords]', form).val(),
            robots_no_follow    : $('input[name=robots_no_follow]:checked', form).val(),
            robots_no_index     : $('input[name=robots_no_index]:checked', form).val(),
            description         : $('textarea[name=description]', form).val()
        }

        // ajax request
        var request = $.ajax({
            url         : $('input[name=url]', form).val(),
            type        : 'POST',
            data        : form_data,
            dataType    : 'text'
        });

        // on success
        request.done(function(status){
            $('td.myseo-status', form).html(status);
        });

        // this really should not happen, but just in case!
        request.fail(function(jqXHR, textStatus) {
            alert( "Request failed: " + textStatus );
        });
    });

    // collapse&expand page fields
    $('tr.myseo-header-row').click(function() {
        var table = $(this).parents('table:first');
        var row = $('tr.myseo-data-row', table);

        if (row.hasClass('myseo-hidden'))
        {
            row.removeClass('myseo-hidden');
        }
        else
        {
            row.addClass('myseo-hidden');
        }
    });
});