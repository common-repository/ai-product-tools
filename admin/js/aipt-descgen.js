(function( $ ) {
    'use strict';

    const metabox = document.getElementById('aipt_product_metabox');

      if (!metabox) {
        return; // Metabox yoksa kodun geri kalanını çalıştırma
    }

    const header = metabox.querySelector('.hndle');
    const toggleFixedButton = document.createElement('button');
    toggleFixedButton.className = 'aipt-toggle-fixed';
    header.appendChild(toggleFixedButton);

    // Kullanıcının tercihini localStorage'dan al
    let isFixed = localStorage.getItem('aipt_product_metabox_fixed');

    // İlk kez çalıştırıldığında, metabox fixed olmalı
    if (isFixed === null) {
        isFixed = 'true';
        localStorage.setItem('aipt_product_metabox_fixed', 'true');
    }

    if (isFixed === 'true') {
        metabox.classList.add('fixed');
        toggleFixedButton.innerHTML = '<i class="fas fa-times"></i>';
    } else {
        metabox.classList.remove('fixed');
        toggleFixedButton.innerHTML = '<i class="fas fa-expand-alt"></i>';
    }

    toggleFixedButton.addEventListener('click', function(event) {
        event.preventDefault(); // Varsayılan davranışı durdur
        event.stopPropagation(); // Olay yayılmasını durdur
        metabox.classList.toggle('fixed');
        if (metabox.classList.contains('fixed')) {
            toggleFixedButton.innerHTML = '<i class="fas fa-times"></i>';
            localStorage.setItem('aipt_product_metabox_fixed', 'true');
        } else {
            toggleFixedButton.innerHTML = '<i class="fas fa-expand-alt"></i>';
            localStorage.setItem('aipt_product_metabox_fixed', 'false');
        }
    });

     function scrollToAndCenterElement(element) {
        $('html, body').animate({
            scrollTop: element.offset().top - ($(window).height() - element.outerHeight()) / 2
        }, 700);
    }
    
    $('.aipt-generate-desc').on('click', function() {
        var title = $('#title').val(); // Ürün başlığını alır

        if(title === '') {
            alert('Title is required to generate description.');
            return;
        }

        // Yükleme göstergesini ve blur efektini ekle
        scrollToAndCenterElement($('#postdivrich'));
        $('#content_ifr').addClass('blurred');
        $('#mceu_30').append('<div class="aipt-loader">Generating<span></span></div>');

        var data = {
            'action': 'aipt_generate_long_description',
            'title': title,
            'nonce': aiptDescGenAjax.nonce,
            'aipt_openai_model': aiptDescGenAjax.aipt_openai_model,
            'temperature': aiptDescGenAjax.aipt_temperature,
            'frequency_penalty': aiptDescGenAjax.aipt_frequency_penalty,
            'presence_penalty': aiptDescGenAjax.aipt_presence_penalty,
            'top_p': aiptDescGenAjax.aipt_top_p,
            'best_of': aiptDescGenAjax.aipt_best_of,
            'aipt_writing_style': aiptDescGenAjax.aipt_writing_style,
            'aipt_descgen_language': aiptDescGenAjax.aipt_descgen_language
        };

        $('.aipt-generate-desc').prop('disabled', true).html('<i class="animation"></i><i class="fas fa-spinner fa-spin"></i> Generating...<i class="animation"></i>');

        $.ajax({
            url: aiptDescGenAjax.ajax_url,
            type: 'POST',
            data: data,
            success: function(response) {
                if(response.success) {
                    // Açıklama alanını güncelle
                    if (typeof tinyMCE !== 'undefined' && tinyMCE.get('content') && !tinyMCE.get('content').isHidden()) {
                        tinyMCE.get('content').setContent(response.data.description);
                    } else {
                        $('textarea#content').val(response.data.description);
                    }
                } else {
                    alert(response.data.message);
                }
            },
            complete: function () {
                $('#content_ifr').removeClass('blurred');
                $('.aipt-loader').remove();
                $('.aipt-generate-desc').prop('disabled', false).html('<i class="animation"></i><i class="fas fa-magic"></i>&nbsp; Generate Product Description<i class="animation"></i>');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#content_ifr').removeClass('blurred');
                $('.aipt-loader').remove();
                $('.aipt-generate-desc').prop('disabled', false).html('<i class="animation"></i><i class="fas fa-magic"></i>&nbsp; Generate Product Description<i class="animation"></i>');
                alert('AJAX error: ' + textStatus);
            }
        });
    });

    $('.aipt-generate-short-desc').on('click', function() {

        var title = $('#title').val(); // Ürün başlığını alır

        if(title === '') {
            alert('Title is required to generate description.');
            return;
        }
        
        scrollToAndCenterElement($('#postexcerpt'));
         $('#excerpt_ifr').addClass('blurred');
        $('#mceu_90').append('<div class="aipt-loader">Generating<span></span></div>');

        var data = {
            'action': 'aipt_generate_short_description',
            'title': title,
            'nonce': aiptDescGenAjax.nonce,
            'aipt_openai_model': aiptDescGenAjax.aipt_openai_model,
            'temperature': aiptDescGenAjax.aipt_temperature,
            'frequency_penalty': aiptDescGenAjax.aipt_frequency_penalty,
            'presence_penalty': aiptDescGenAjax.aipt_presence_penalty,
            'top_p': aiptDescGenAjax.aipt_top_p,
            'best_of': aiptDescGenAjax.aipt_best_of,
            'aipt_writing_style': aiptDescGenAjax.aipt_writing_style,
            'aipt_descgen_language': aiptDescGenAjax.aipt_descgen_language
        };

        $('.aipt-generate-short-desc').prop('disabled', true).html('<i class="animation"></i><i class="fas fa-spinner fa-spin"></i> Generating...<i class="animation"></i>');

        $.ajax({
            url: aiptDescGenAjax.ajax_url,
            type: 'POST',
            data: data,
            success: function(response) {
                if(response.success) {
                    // Açıklama alanını güncelle
                    if (typeof tinyMCE !== 'undefined' && tinyMCE.get('excerpt') && !tinyMCE.get('excerpt').isHidden()) {
                        tinyMCE.get('excerpt').setContent(response.data.description);
                    } else {
                        $('textarea#excerpt').val(response.data.description);
                    }
                } else {
                    alert(response.data.message);
                }
            },
            complete: function () {
                $('#excerpt_ifr').removeClass('blurred');
                $('.aipt-loader').remove();
                $('.aipt-generate-short-desc').prop('disabled', false).html('<i class="animation"></i><i class="fas fa-magic"></i>&nbsp; Generate Short Description<i class="animation"></i>');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#excerpt_ifr').removeClass('blurred');
                $('.aipt-loader').remove();
                $('.aipt-generate-short-desc').prop('disabled', false).html('<i class="animation"></i><i class="fas fa-magic"></i>&nbsp; Generate Short Description<i class="animation"></i>');
                alert('AJAX error: ' + textStatus);
            }
        });
    });

})( jQuery );
