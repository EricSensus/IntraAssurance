(function ($) {

    $.fn.shortcutMenu = function (settings) {
        
        return this.on(settings.trigger,function () {
            
            //open the current one
            $(this).addClass('open');
            
            var rowid = $(this).parent().closest('tr').attr('id');

            //hide all previous shorcuts
            $('#'+settings.tablename+' ul[id!=shortcutMenu-'+rowid+']').hide();

            //set the new menu selector
            settings.menuSelector = settings.menuSelector+rowid;
            $(settings.menuSelector).show('fast',function(){ 
                settings.menuSelector = '#shortcutMenu-';
            });
            
            //make sure menu closes on any click
            $('body > [id!=shortcutMenu-'+rowid+']').click(function (e) {
                
                //hide all shorcuts
                var target = e.target;

                if (!$(target).is('ul.dropdown-menu') && !$(target).parents().is('ul.dropdown-menu')) {
                    $('[id^=shortcutMenu-]').hide();
                }
                else{
                    
                    //workaround for direct or modal links
                    if(!$(target).attr('data-target')){
                        e.stopPropagation();
                    }
                }
                
                //reset menu selector
                settings.menuSelector = '#shortcutMenu-';
            });
        });
    };
})(jQuery, window);
              

