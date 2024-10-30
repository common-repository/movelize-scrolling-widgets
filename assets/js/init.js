/**
 * Make everything work in frontend and Elelentor editor
 *
 * Load the settings from the Elementor column
 * Initialize Swiper with user-defined settings
 *
 */

function movSwiperSetup(swipeAction) {
    (function($) {
        jQuery('.mov-col-scroll').each(function(i){
            var $this = jQuery(this);
            jQuery('.swiper-container', $this).attr('id', 'mov-col-scroll-' + i);
            jQuery('.swiper-button-prev', $this).addClass('mov-arrow-l-' + i);
            jQuery('.swiper-button-next', $this).addClass('mov-arrow-r-' + i);
            jQuery('.swiper-pagination', $this).addClass('mov-pagination-' + i);
            setTimeout(function () {
                var mobileWidth = elementorFrontendConfig.breakpoints.md;
                var tabletWidth = elementorFrontendConfig.breakpoints.lg;
                var classNames = $this.attr('class').split(' ');
                jQuery.each(classNames, function(i, name){
                    if(name.match('mov-sl-view')) {
                        if(name.match('mov-sl-view--mobile')) {
                            dv = name.split('-');
                            defaultView = Number(dv[dv.length - 1]);
                        }
                        else if(name.match('mov-sl-view--tablet')) {
                            mv = name.split('-');
                            mediumView = Number(mv[mv.length - 1]);
                        }
                        else {
                            lv = name.split('--');
                            largeView = Number(lv[1]);
                        }
                    }
                    if(name.match('mov-sl-scroll')) {
                        if(name.match('mov-sl-scroll--mobile')) {
                            ds = name.split('-');
                            defaultScroll = Number(ds[ds.length - 1]);
                        }
                        else if(name.match('mov-sl-scroll--tablet')) {
                            ms = name.split('-');
                            mediumScroll = Number(ms[ms.length - 1]);
                        }
                        else {
                            ls = name.split('--');
                            largeScroll = Number(ls[1]);
                        }
                    }
                });
                var scrollWidgets = new Swiper('#mov-col-scroll-' + i, {
                    slidesPerView: defaultView,
                    slidesPerGroup: defaultScroll,
                    breakpoints: {
                        [mobileWidth]: {
                            slidesPerView: mediumView,
                            slidesPerGroup: mediumScroll
                        },
                        [tabletWidth]: {
                            slidesPerView: largeView,
                            slidesPerGroup: largeScroll
                        }
                    },
                    pagination: {
                        el: '.mov-pagination-' + i,
                        type: 'progressbar'
                    },
                    navigation: {
                        nextEl: '.mov-arrow-r-' + i,
                        prevEl: '.mov-arrow-l-' + i
                    }
                });
                $this.css('opacity', 1)
                if(swipeAction === 'update') {
                    scrollWidgets.slideTo(0);
                    scrollWidgets.update();
                }
                else if(swipeAction === 'destroy') {
                    scrollWidgets.destroy();
                }
            }, 100);
        });
    })(jQuery);
}

jQuery(window).on('elementor/frontend/init', function() {
    jQuery('.mov-col-scroll').append('<div class="mov-arrow-wrapper"><div class="swiper-arrow swiper-button-prev icon-chevron-left" /><div class="swiper-arrow swiper-button-next icon-chevron-right" /></div>');
    jQuery('.mov-col-scroll > .elementor-column-wrap').addClass('swiper-container').append('<div class="swiper-pagination" />');
    jQuery('.mov-col-scroll > .elementor-column-wrap > .elementor-widget-wrap').addClass('swiper-wrapper');
    jQuery('.mov-col-scroll > .elementor-column-wrap > .elementor-widget-wrap > .elementor-element').addClass('swiper-slide');
    movSwiperSetup();
    if(typeof(elementor) != 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/column', function($element) {
            if($element.hasClass('mov-col-scroll')) {
                $element.append('<div class="mov-arrow-wrapper"><div class="swiper-arrow swiper-button-prev icon-chevron-left" /><div class="swiper-arrow swiper-button-next icon-chevron-right" /></div>');
            }
            jQuery('.mov-col-scroll > .elementor-column-wrap').addClass('swiper-container').append('<div class="swiper-pagination" />');
            jQuery('.mov-col-scroll > .elementor-column-wrap > .elementor-widget-wrap').addClass('swiper-wrapper');
            jQuery('.mov-col-scroll > .elementor-column-wrap > .elementor-widget-wrap > .elementor-element').addClass('swiper-slide');
            movSwiperSetup();
        });
        elementor.hooks.addAction('panel/open_editor/column', function(panel, model, view) {
            model.attributes.settings.on('change:mov_col_layout', function() {
                if(model.attributes.settings.attributes.mov_col_layout === 'scroll') {
                    setTimeout(function () {
                        jQuery(view.$el).append('<div class="mov-arrow-wrapper"><div class="swiper-arrow swiper-button-prev icon-chevron-left" /><div class="swiper-arrow swiper-button-next icon-chevron-right" /></div>');
                        jQuery('.elementor-column-wrap:first', view.$el).addClass('swiper-container').append('<div class="swiper-pagination" />');
                        jQuery('.elementor-widget-wrap:first', view.$el).addClass('swiper-wrapper');
                        jQuery('.elementor-widget-wrap:first > .elementor-element', view.$el).addClass('swiper-slide');
                        movSwiperSetup();
                    }, 10);
                }
                else {
                    movSwiperSetup('destroy');
                    setTimeout(function () {
                        view.$el.removeAttr('id');
                        jQuery('.elementor-column-wrap', view.$el).removeClass('swiper-container');
                        jQuery('.elementor-widget-wrap', view.$el).removeClass('swiper-wrapper');
                        jQuery('.elementor-element', view.$el).removeClass('swiper-slide');
                        jQuery('.mov-arrow-wrapper, .swiper-pagination', view.$el).remove();
                    }, 100)
                }
            });
            model.attributes.settings.on('change:mov_slides_view change:mov_slides_view_tablet change:mov_slides_view_mobile change:mov_slides_scroll change:mov_slides_scroll_tablet change:mov_slides_scroll_mobile', function() {
                movSwiperSetup('update');
            });
        });
    }
});