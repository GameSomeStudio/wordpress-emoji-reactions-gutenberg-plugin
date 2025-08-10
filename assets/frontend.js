
jQuery(document).ready(function($) {
    
    // Cookie Control
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    $('.wpr-reactions-wrapper').on('click', '.wpr-reaction-item', function(e) {
        e.preventDefault();

        const $this = $(this);
        const $wrapper = $this.closest('.wpr-reactions-wrapper');
        const postId = $wrapper.data('post-id');
        const emoji = $this.data('emoji');
        
        if (getCookie('wpr_voted_' + postId)) {
            alert(wpr_ajax_object.voted_message);
            return;
        }

        if ($wrapper.hasClass('processing')) {
            return;
        }
        $wrapper.addClass('processing');

        $.ajax({
            url: wpr_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'wpr_save_reaction',
                post_id: postId,
                emoji: emoji,
                nonce: wpr_ajax_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    const newCount = response.data.new_count;
                    $this.find('.wpr-reaction-count').text(newCount);

                    $wrapper.find('.wpr-reaction-item').addClass('voted');
                    setCookie('wpr_voted_' + postId, '1', 365); 
                } else {
                    alert(response.data.message || 'Bir hata oluştu.');
                    if (response.data.message.includes('You have already voted for this article.')) {
                       $wrapper.find('.wpr-reaction-item').addClass('voted');
                       setCookie('wpr_voted_' + postId, '1', 365); 
                    }
                }
            },
            error: function() {
                alert('Server error');
            },
            complete: function() {
                $wrapper.removeClass('processing');
            }
        });
    });
});