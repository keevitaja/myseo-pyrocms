/**
 * MySeo jquery
 * 2013
 *
 * https://github.com/keevitaja/myseo-pyrocms
 *
 * @package     myseo/core
 * @author      Tanel Tammik <keevitaja@gmail.com>
 * @version     master
 *
 */

$(document).ready(function()
{
    // update page meta information
    $('button[name=meta_update]').click(function(e)
    {
        e.preventDefault();

        $('td.status', form).html('');

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
            $('td.status', form).html(status);
        });

        // this really should not happen, but just in case!
        request.fail(function(jqXHR, textStatus) {
            alert( "Request failed: " + textStatus );
        });
    });
});