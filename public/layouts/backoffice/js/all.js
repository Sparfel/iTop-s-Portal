var inContextLanguage;

jQuery(function() {
    
	jQuery("body").addClass('hasJs');

    // dropdown sites
	jQuery("ul.main li ul").each(function() {
        var jQuerypicker = jQuery(this);
        jQuerypicker.bind('mouseenter',function() {
            jQuerypicker.show();
        }).bind('mouseleave',function() {
            jQuerypicker.hide();
        }).parent().bind('mouseenter',function() {
            jQuerypicker.show();
        }).bind('mouseleave',function() {
            jQuerypicker.hide();
        });
    });

    //jQuery(".datepicker").CUI("datepicker");
    
	jQuery(".hl-template .hl-item .actions").hide();
	jQuery(".hl-template .hl-item")
        .mouseenter(function() {
            jQuery(this)
                .addClass("hl-item-hover")
                .find('.actions').show();
        })
        .mouseleave(function() {
            jQuery(this)
                .removeClass("hl-item-hover")
                .find('.actions').hide();
        });
        
	jQuery("a.help").each(function() {
        jQueryjQuery = jQuery(this);
        jQuery("<span/>", {
            "class":"bullet",
            text: jQueryjQuery.attr('title'),
            css: {
                width: parseInt(jQueryjQuery.attr('title').length)*6
            }
        }).appendTo(jQueryjQuery);
        jQuery("<span/>", {
            "class":"ui-icon ui-icon-triangle-1-s queue"
        }).appendTo(jQueryjQuery);
    }); 
    
    inContextLanguage = function() {
        cpt=1;
        jQuery('a.help .ui-icon-flag').each(function(){
            jQuery(this).parent().attr('id', 'trigger-translate-'+cpt);
            jQuery(this).parent().parent().find('ul').attr('id', 'picker-translate-'+cpt).appendTo('body').hide();
            jQuery(this).click(function(evt) {
                    var id = jQuery(this).parent().attr('id').split('-')[2]; 
                    jQuery('#picker-translate-'+id).css({'position':'absolute','left':parseInt(evt.pageX-35)+'px','top':parseInt(evt.pageY+10)+'px','z-index':'99'}).show();
                    return false;
                });
            jQuery('#picker-translate-'+cpt).bind({
                mouseenter: function(){ 
                    jQuery(this).show();
                },
                mouseleave: function(){
                    jQuery(this).hide();
                }
            })
            cpt = cpt+1;
        });
    }
    
    inContextLanguage();
    
    jQuery('.box-dashboard-glance .glance div').bind({
        mouseenter: function() {
            jQuery(this).addClass('hover');
        },
        mouseleave: function() {
            jQuery(this).removeClass('hover');
        }
    })
    
    
    // FIX HTML5 FORM
    // function to detect browser support or not by Jeremy Keith
    function checkAttribute(element, attribute) {
        var test = document.createElement(element);
        if (attribute in test) {
            return true;
        } else {
            return false;
        }
    }
    
    if (!checkAttribute('input', 'autofocus')) {
        jQuery("input[autofocus]").focus();
    }

    // minimize-maximize sidebar
    if ( jQuery('body').hasClass('toggle-sidebars')){
        var header = jQuery('body header.sh'),
            buttonToggle = jQuery('<span />',{ 'class' : 'toggle-sidebar-left icon icon-minimize'}),
            nav = jQuery('section nav'),
            section = header.siblings('section'),
            parentSection = header.parent('section'),
            userCookies, valueCookie;
        
        //memorize init value
        header.attr('data-init-margin',header.css('margin-left'));
        section.attr('data-init-margin', section.css('margin-left'));
        parentSection.attr('data-init-background', parentSection.css('background-image'));
        
        if ( !jQuery.support.opacity ){
            nav.attr('data-init-filter', nav.css('filter'));
        }

        function minimizeMaximize(minimize, speed){
            if (minimize){
                nav.hide();
                jQuery.each([header, section], function(){
                    jQuery(this).animate({
                        'margin-left' : 0
                    }, speed);
                    buttonToggle.addClass('icon-maximize').removeClass('icon-minimize');
                });
                parentSection.css('background-image', 'none');
                document.cookie = "sidebarleftHidden=1";
            } else {
                jQuery.each([header, section], function(){
                    jQuery(this).animate({
                        'margin-left' : jQuery(this).attr('data-init-margin')
                    }, speed, function(){
                        parentSection.css('background-image', parentSection.attr('data-init-background'));
                        nav.fadeIn('fast', function(){
                            if ( jQuery(this).attr('data-init-filter') ){
                                jQuery(this).css('filter', jQuery(this).attr('data-init-filter'));
                            }
                            
                        });
                    });
                    buttonToggle.addClass('icon-minimize').removeClass('icon-maximize');
                });
                document.cookie = "sidebarleftHidden=0";
            }
        }

        //manage cookies
        if (document.cookie){
            userCookies = document.cookie.split(';');
            for (var i = 0, len = userCookies.length; i < len; i++){
                if (userCookies[i].indexOf('sidebarleftHidden=') != -1 ){
                    valueCookie = userCookies[i].split('=')[1];
                    minimizeMaximize(parseInt(valueCookie,10) , 0);
                }
            }
        }
        header.append(buttonToggle);
        
        buttonToggle.bind({
            'click' : function(){
                if ( jQuery(this).hasClass('icon-minimize')){
                    minimizeMaximize(true, 250);
                } else {
                    minimizeMaximize(false, 250);
                }
            }
        });
    }

});