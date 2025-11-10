jQuery(document).on('click', '.email-to-manufacturer', function(e){
    e.preventDefault();

    let post_id = jQuery('#post_ID').val();

    jQuery(this).text('Sending...').prop('disabled', true);

    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'email_to_manufacturer',
            post_id: post_id
        },
        success: function(res){
            alert('Email Sent Successfully!');
            location.reload();
        },
        error: function(){
            alert('Error Sending Email');
        }
    });
});
