let find_textarea = document.querySelector('textarea');
// console.log(find_textarea);
if (find_textarea) {
    const textarea_styles = window.getComputedStyle(document.querySelector('textarea'));
    const textarea_padding = parseFloat(textarea_styles.getPropertyValue('padding'));

    function adjust(textarea) {
        var current_height = textarea.scrollHeight;
        textarea.style.height = 'auto';

        if (textarea.scrollHeight > textarea.clientHeight) textarea.style.height = (textarea.scrollHeight - 2 * textarea_padding) + 'px';
        if (textarea.scrollHeight < current_height) textarea.style.height = (textarea.scrollHeight - 2 * textarea_padding) + 'px';
    }
}

function add_delete_confirmation(button) {
    $(button).on('click', function (event) {
        event.preventDefault();

        var form = $(button).closest('form'); // Get the related form
        if ($(this).hasClass('delete-comment')) var msg = 'Are you sure you want to delete this comment?';
        if ($(this).hasClass('delete-post')) var msg = 'Are you sure you want to delete this post?';

        if (confirm(msg)) form.submit();

    })
}



