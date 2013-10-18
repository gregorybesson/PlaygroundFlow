/**
 * Copyright (C) 2013 - Playground
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */ 

/** User Object
 * @class
 * @name PG.User
 */
var user = {
    
    env: { },
    id: null,
    uid: null,
    stories: { },
    urls: {
        current: window.location.href,
        prev: null
    },
    
    /**
     * Init the user object
     * @function
     * 
     * @name PG.User.init
     * 
     * @param {null}
     * @return {null}
     * 
     * @this {User}
     * 
     * @ignore
     * 
     * @since version 1.0.0
     */ 
    init: function ()
    {
        'use strict';
        
        PG.Util.log('user.js > init');
        
        PG.Util.GenerateUniqueId();
        PG.User.urls.prev = PG.Util.readCookie('prev-url');
        
        var stories = PG.Util.readCookie('stories'),
            lastSync = PG.Util.readCookie('last-sync'),
            today = new Date().getTime();
        
        // @TODO : UNCOMMENT THIS TO KEEP THE AUTHENT FOR A ENTIRE DAY
        stories = null;
        
        if(!PG.Util.not_null(lastSync) || (parseInt(lastSync, 10) + parseInt(24 * 60 * 60, 10)) < today) {
            stories = null;
        }
        
        if(!PG.Util.not_null(stories)) {
            PG.User.loadStories()
            .then(function ()
            {
                PG.User.stories = JSON.parse(PG.Util.readCookie('stories'));
                // after
                PG.User.checkStories(false, true);
                // normal
                PG.User.checkStories(false, false);
            });
        }else {
            PG.User.stories = JSON.parse(PG.Util.readCookie('stories'));
            // after
            PG.User.checkStories(false, true);
            // normal
            PG.User.checkStories(false, false);
        }
    },
    
    /**
     * Methode to get stories
     * put the return into PG.User.stories
     * @function
     * 
     * @name PG.User.loadStories
     * 
     * @param {null}
     * @return {Object} promise
     *  
     * @this {User}
     * 
     * @ignore
     * 
     * @since version 1.0.0
     */
    loadStories: function ()
    {
        'use strict';
        PG.Util.log('user.js > loadStories');
        
        var p = new PG.Promise();

        PG.App.call( PG.Config.env[PG.Config.mode].connect )
        .then(
            function (data)
            {
                PG.Util.createCookie('last-sync', new Date().getTime());
        
                PG.Util.log('user.js > loadStories > resolve promise', data);
                PG.Util.createCookie('stories', JSON.stringify(data));
                p.resolve();
            }
        );
        
        return p;
    },
    
    /**
     * Loop over all stories,
     * Call PG.User.checkStory
     * 
     * if story true and before = true 
     *  > save story user local
     * 
     * if story true and before and after = false
     *  > send story to playground
     * @function
     * 
     * @name PG.User.checkStories
     * 
     * @param {Boolean} before
     * @param {Boolean} after
     * @return {null}
     * 
     * @this {User}
     * 
     * @ignore
     * 
     * @since version 1.0.1
     */
    checkStories: function (before, after)
    {
        'use strict';
        PG.Util.log('user.js > checkStories', PG.User.stories);
        
        var i, b = {}, a = {}, use, afterCookie, beforeCookie;
        
        if(typeof PG.User.stories !== 'undefined' && typeof PG.User.stories.library.stories !== 'undefined') {
            for(i in PG.User.stories.library.stories) {
                if(typeof PG.User.stories.library.stories[i] === 'object') {
                    use = PG.User.checkStory(PG.User.stories.library.stories[i], before, after);
                    
                    if(use && before) {
                        b[i] = "true";
                        PG.User.createObjectsStory(PG.User.stories.library.stories[i]);
                    }else if(before) {
                        b[i] = "false";
                    }
                    if(use && after) {
                        a[i] = "true";
                    }else if(after) {
                        a[i] = "false";
                    }
                    
                    if(use && !before && !after) {
                        beforeCookie = PG.Util.readCookie('before');
                        if(PG.Util.not_null(beforeCookie)) {
                            beforeCookie = JSON.parse(beforeCookie);
                        }
                        afterCookie = PG.Util.readCookie('after');
                        if(PG.Util.not_null(afterCookie)) {
                            afterCookie = JSON.parse(afterCookie);
                        }
                        if(beforeCookie[i] === 'true' && afterCookie[i] === 'true') {
                            PG.App.send(PG.User.getStory(PG.User.stories.library.stories[i]));
                        }
                    }
                }
            }
            // set cookie to remember after and before
            if(before) {
                PG.Util.createCookie('before', JSON.stringify(b));
            }else if(after) {
                PG.Util.createCookie('after', JSON.stringify(a));
            }else {
                // Else remove before event
                PG.Util.createCookie('before', '');
            }
        }
        
        return;
    },
    
    /**
     * Check single story
     * 
     * if before = true 
     *  > check only before event
     * 
     * if after = true
     *  > check only after event
     * 
     * @function
     * 
     * @name PG.User.checkStory
     * 
     * @param {Object} story
     * @param {Boolean} before
     * @param {Boolean} after
     * @return {Boolean} result true | false
     * 
     * @this {User}
     * 
     * @ignore
     * 
     * @since version 1.0.1
     */
    checkStory: function (story, before, after)
    {
        'use strict';
        PG.Util.log('user.js > checkStory (before:'+before+', after:'+after+') > name "' + story.action + '"');
        
        var use = true;
        
        if(PG.Util.not_null(story)) {
            
            if(before) {
                
                if(story.events.before.length === 0) {
                    PG.Util.log('user.js > checkStory > use story : false');
                    return true;
                }
                
                // CHECK STORY URL ?
                if(PG.Util.not_null(story.events.before.url)) {
                    use = (window.location.href.indexOf(story.events.before.url) !== -1);
                }
                
                // CHECK STORY XPATH ?
                if(PG.Util.not_null(story.events.before.xpath)) {
                    use = PG.Util.checkXpath(story.events.before.xpath);
                }
                
                PG.Util.log('user.js > checkStory > before > use story : ' + before);
            }else if(after) {
                
                if(story.events.after.length === 0) {
                    PG.Util.log('user.js > checkStory > use story : false');
                    return true;
                }
                
                // CHECK STORY URL ?
                if(PG.Util.not_null(story.events.after.url)) {
                    use = (window.location.href.indexOf(story.events.after.url) !== -1);
                }
                
                // CHECK STORY XPATH ?
                if(PG.Util.not_null(story.events.after.xpath)) {
                    use = PG.Util.checkXpath(story.events.after.xpath);
                }
                
                PG.Util.log('user.js > checkStory > after > use story : ' + after);
            }else {
                // CHECK STORY URL ?
                if(PG.Util.not_null(story.conditions.url)) {
                    use = (window.location.href.indexOf(story.conditions.url) !== -1);
                }
                
                // CHECK STORY XPATH ?
                if(PG.Util.not_null(story.conditions.xpath)) {
                    use = PG.Util.checkXpath(story.conditions.xpath);
                }
                
                PG.Util.log('user.js > checkStory > use story : ' + use);
            }
        }
        
        return use;
    },
    
    /**
     * Create valid story ready to send for playground
     * @function
     * 
     * @name PG.User.createObjectsStory
     * 
     * @param {Object} story
     * @return {Object} objects
     * 
     * @this {User}
     * 
     * @ignore
     * 
     * @since version 1.0.0
     */
    createObjectsStory: function (story)
    {
        'use strict';
        PG.Util.log('user.js > createObjectsStory');
        
        var objects, xpathObjects, propValue, properties = [], i, j, k;
        
        if(typeof story.objects.id !== 'undefined') {
            for(j in story.objects.properties) {
                if(typeof story.objects.properties[j] === 'object' && typeof story.objects.properties[j].name !== 'undefined') {
                    xpathObjects = PG.Util.getObjectFromXpath(story.objects.properties[j].xpath);
                    if(typeof xpathObjects !== 'undefined') {
                        for(k in xpathObjects) {
                            propValue =  PG.Util.getValueFromObject(xpathObjects[k]);
                            if(typeof xpathObjects === 'object' && propValue !== '') {
                                properties.push({
                                    'name': story.objects.properties[j].name,
                                    'value': propValue
                                });
                            }
                        }
                    }
                }
            }
            
            objects = {
                id: story.objects.id,
                properties: properties
            };
        }
        
        if(typeof objects !== 'undefined' && typeof objects.id !== 'undefined'
            && typeof objects.properties !== 'undefined' && objects.properties.length > 0 ) {
            PG.Util.createCookie('object.' + story.action, JSON.stringify(objects));
        }else {
            objects = JSON.parse(PG.Util.readCookie('object.' + story.action));
        }
        
        return objects;
    },
    
    /**
     * return json story
     * @function
     * 
     * @name PG.User.getStory
     * 
     * @param {String} url
     * @param {Object} story
     * @return {Boolean} result true | false
     * 
     * @this {User}
     * 
     * @ignore
     * 
     * @since version 1.0.0
     */
    getStory: function (story)
    {
        'use strict';
        
        var objects = PG.User.createObjectsStory(story),
            json = {
            user: {
                anonymous: PG.User.uid
            },
            objects: (objects !== null) ? objects : [],
            action: story.action,
            url: window.location.href,
            apiKey: PG.Settings.apiKey
        };
        
        PG.Util.log("user.js > getStory");
        
        return json;
    },
    
    /**
     * Call this method when user quit the current to check if try to logout/login
     * make cookies 'login-try' and 'logout-try'
     * @function
     * 
     * @name PG.User.quit
     * 
     * @param {null}
     * @return {null}
     * 
     * @this {User}
     * 
     * @ignore
     * 
     * @since version 1.0.0
     */
    quit: function ()
    {
        'use strict';
        
        PG.Util.log('user.js > quit');
        
        PG.User.checkStories(true, false);
        
        PG.Util.createCookie('prev-url', window.location.href);
        
        return;
    }
};

// put the user into Playground.User
try {
    addToNamespace('User', user);
}catch(e) {
   throw new Error( "Cannot extends 'User' to 'Playground.User'" );
}
;