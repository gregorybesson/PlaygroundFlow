/**
 * @namespace reference to Playground object
 */
var PG = Playground,
    init;
    
(function ()
{ 
    'use strict';
    
    function loadQueuAndWait (queu, callback)
    {
        var i = 0; 
        
        if(typeof queu[0] !== 'undefined' && PG.Util.not_null(queu[0])) {
            PG.Util.loadJs(queu[0], function ()
            {
                queu.shift();
                loadQueuAndWait(queu, callback);
            });
        }else {
            if(callback) {
                callback();
            }
        }
    }
    
    var scripts = [],
        browser = PG.Util.saysWho();
    
    if(browser[0] === "MSIE" && browser[1] === "9.0") {
        scripts.push(PG.Config.scripts.xpath);
        scripts.push(PG.Config.scripts.json);
    } 
    
    loadQueuAndWait(scripts, function ()
    {
        var sc = PG.Util.getObjectFromXpath('//script[@src]'),
            settings = {}, i;
            
        for(i in sc) {
            if(sc[i].getAttribute('src').indexOf('pg.min.js') > -1) {
                sc = sc[i];
                break;
            }
        }
        
        // @TODO : disabled api key
        settings.apiKey = sc.getAttribute("data-pg-api-key");
        
        // put the instance of settings into the namespace Playground.Settings
        try {
            addToNamespace('Settings', settings);
        }catch(e) {
           throw new Error( "Cannot extends 'app' to 'Playground.Settings'" );
        }
        
        if(sc.getAttribute("data-ears") && sc.getAttribute("data-ears") === 'true') {
            scripts.push(PG.Config.scripts.ears);
        }
        if(sc.getAttribute("data-mouth") && sc.getAttribute("data-mouth") === 'true') {
            scripts.push(PG.Config.scripts.mouth); 
        }
        
        loadQueuAndWait(scripts, function ()
        {
            // put the instance of config into the namespace Playground.Config
            try {
                addToNamespace('Config', pl_config);
            }catch(e) {
               throw new Error( "Cannot extends 'app' to 'Playground.Config'" );
            }
        });
    });
})();
;