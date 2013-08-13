$(document).ready(function() {
    $('button[name=myseo_page_update]').click(function(e) {
        e.preventDefault();

        $('td.myseo-update-status').html('');

        // find current form
        var form = $(this).parents('form:first');

        // collect data
        var form_data = {
            meta_title               : $('input[name=meta_title]', form).val(),
            meta_keywords            : $('input[name=meta_keywords]', form).val(),
            meta_robots_no_follow    : $('input[name=meta_robots_no_follow]:checked', form).val(),
            meta_robots_no_index     : $('input[name=meta_robots_no_index]:checked', form).val(),
            meta_description         : $('textarea[name=meta_description]', form).val()
        }

        // make ajax request
        var request = $.ajax({
            url         : $('input[name=url]', form).val(),
            type        : 'POST',
            data        : form_data,
            dataType    : 'text'
        });

        // on success
        request.done(function(status){
            $('td.myseo-update-status', form).html(status);
        });

        // this really should not happen, but just in case!
        request.fail(function(jqXHR, textStatus) {
            alert( "Request failed: " + textStatus );
        });
    });

    // collapse&expand page fields
    $('tr.myseo-header-row').click(function() {
        var table = $(this).parents('table:first');

        if (table.hasClass('myseo-item-hidden'))
        {
            table.removeClass('myseo-item-hidden');
        }
        else
        {
            table.addClass('myseo-item-hidden');
        }
    });
});