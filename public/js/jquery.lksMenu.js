/* @version 1.1 lksMenu
 * @author Lucas Forchino
 * @webSite: http://www.tutorialjquery.com
 * lksMenu.
 * jQuery Plugin to create a css menu
 */
(function($){
    $.fn.lksMenu=function(){
        return this.each(function(){
            var menu= $(this);
            menu.find('ul li ul.active').slideDown('medium');
            menu.find('ul li > a').bind('click',function(event){
                var ahref = $(event.currentTarget).attr('href');
                if(ahref!='#'){ // si conttiene un href distinto de # (submenu)
                    window.location.href = ahref; // linkea 
                }else{//titulos
                    var currentlink=$(event.currentTarget);
                    
                    
                    if (currentlink.parent().find('ul.active').size()==1) // si esta abierto
                    {
                        
                        currentlink.parent().find('ul.active').slideUp('medium',function(){
                            currentlink.parent().find('ul.active').removeClass('active');
                        });
                    }
                    else if (menu.find('ul li ul.active').size()==0) //cerrar
                    {
                        
                        show(currentlink);
                    }
                    else
                    {
                       
                        menu.find('ul li ul.active').slideUp('medium',function(){ //cerrar uno y abrir otro
                            menu.find('ul li ul').removeClass('active'); //problema
                            show(currentlink);
                        });
                    }
                }
            });
            function show(currentlink){ // problema
                currentlink.parent().find('ul').addClass('active');
                currentlink.parent().find('ul').slideDown('medium');
            }
        });
    }
})(jQuery);