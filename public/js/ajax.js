$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Ajax request for adding new comments without refreshing the page
$('.new-comment-form').submit(function(event) {
    var form_id = $(this).attr('id');
    event.preventDefault();
    var formData = $(this).serializeArray();

    $.ajax({
        url: get_url('add_comment'),
        type: 'post',
        data: formData,
        success: function(response) {
            $('#comment_error').remove();

            var content = response.content;
            content = content.replace(/\n/g, "<br />");

            var profile_link = response.profile_link;
            var image = response.image;
            var name = response.name;
            var time = response.time;
            var id = response.id;
            var edit_link = response.edit_link;


            var comment = ` <div class='comment-buttons' id='comment-buttons` + id + `'>
                                <div class="comment">
                                    <a href="` + profile_link + `"><img src="` + image + `" alt="Comment author's pic"></a>
                                    <span class='comment-name'><strong>` + name + `</strong></span>
                                    <span class='comment-date'>` + time + `</span>
                                    <span></span>
                                    <span class='comment-content'>` + content + `</span>
                                </div>
                                <form id='delete-comment-form` + id + `'  name='delete-comment-form' class='delete-comment-form' method='POST' action='` + get_url('delete_comment') + `'>
                                    <input type="hidden" name='comment_id' value='` + id + `'>
                                    <div>
                                        <img src="` + get_img('delete_icon.png') + `" alt="Delete">
                                        <input class='delete-comment' type='image' name='submit' src="` + get_img('delete_icon_active.png') + `" alt="Delete" >
                                    </div>
                                </form>
        
                                <div class='edit-comment-link'>
                                    <img src="` + get_img('edit_icon.png') + `" alt="Edit" class='edit-comment-inactive'>
                                    <a href='` + edit_link + `'><img class='edit-comment-active' type='image' name='submit' src="` + get_img('edit_icon_active.png') + `" alt="Edit"></a>
                                </div>
                            </div>`;
            
            $('#post' + response.post_id + ' hr').after(comment);
            $('textarea').val('');
            comment_delete_ajax($('#delete-comment-form' + id)); // add an ajax event listener to the new comment
            add_delete_confirmation($('#delete-comment-form' + id + ' .delete-comment')); // add delete confirmation for the new comment
        },

        error: function(response) {
            $('#comment_error').remove(); // don't display more than 1 error message at once

            var form = $('#' + form_id);
            if (locale == 'en') var error = `<h2 id='comment_error'>Comment text is required!</h2>`;
            else if (locale == 'lv') var error = `<h2 id='comment_error'>Komentāra teksts ir nepieciešams!</h2>`;
            $(form).append(error);
        }
    });
});

// Ajax request for deleting a post
$('.delete-post-form').submit(function(event) {
    var div_id = $(this).closest('.post-buttons').attr('id');
    event.preventDefault();
    var formData = $(this).serializeArray();

    $.ajax({
        url: get_url('delete_post'),
        type: 'post',
        data: formData,
        success: function(response) {
            $('#' + div_id).remove();

            $('<h1>', {
                class: 'success-msg',
                text: response.message
            }).prependTo('main');

            const success_msg = document.querySelector('.success-msg');
            setTimeout(() => {success_msg.style.display="none"}, 3000);
        },

        error: function(response) {
            alert('error');
        }
    });
});

// Ajax request for deleting a comment
function comment_delete_ajax(comment) {

    $(comment).on('submit', function(event) {
        event.preventDefault();
        var div_id = $(this).closest('.comment-buttons').attr('id');
        var formData = $(this).serializeArray();

        $.ajax({
            url: get_url('delete_comment'),
            type: 'delete',
            data: formData,
            success: function(response) {
                $('#' + div_id).remove();

                $('<h1>', {
                    class: 'success-msg',
                    text: response.message
                }).prependTo('main');

                const success_msg = document.querySelector('.success-msg');
                setTimeout(() => {success_msg.style.display="none"}, 3000);
            },

            error: function(response) {
                alert('error');
            }
        });
    });
}

// Ajax for liking or unliking a post
$('.like_form').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serializeArray();

    $.ajax({
        url: get_url('like_post'),
        type: 'post',
        data: formData,
        success: function(response) {
            $('#like_form' + response.post).load(' #like_form' + response.post + ' > *');
        },

        error: function(response) {
            alert('error');
        }
    });
});

$('.delete-comment-form').each(function(index, object) {
    comment_delete_ajax(object);
})