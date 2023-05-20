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
        if ($(this).hasClass('remove_friend')) var msg = 'Are you sure you want to remove this friend?';

        if (confirm(msg)) form.submit();

    })
}

function openTab(event, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;
  
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
  
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    event.currentTarget.className += " active";
}



