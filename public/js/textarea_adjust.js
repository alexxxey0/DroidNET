const textarea_min_height = (document.querySelector('textarea').offsetHeight);
const textarea_styles = window.getComputedStyle(document.querySelector('textarea'));
const textarea_padding = parseFloat(textarea_styles.getPropertyValue('padding'));

function adjust(textarea) {
    let current_height = textarea.scrollHeight;
    textarea.style.height = 'auto';

    if (textarea.scrollHeight > textarea.clientHeight) textarea.style.height = (textarea.scrollHeight - 2 * textarea_padding) + 'px';
    if (textarea.scrollHeight < current_height) textarea.style.height = (textarea.scrollHeight - 2 * textarea_padding) + 'px';
}

