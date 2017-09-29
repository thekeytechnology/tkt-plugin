(function() {
    tinymce.create("tinymce.plugins.betemplate_button_plugin", {

        //url argument holds the absolute url of our plugin directory
        init : function(ed, url) {

            //add new button
            ed.addButton("betemplate", {
                title : "BeTemplate Shortcode",
                cmd : "betemplate_command",
                image : "/wp-content/plugins/tkt-plugin/betemplate/tinymce/betemplate.png"
            });

            //button functionality.
            ed.addCommand("betemplate_command", function() {
                // var selected_text = ed.selection.getContent();
                var return_text = '[tk-betemplate id="" stripdown=""]';
                ed.execCommand("mceInsertContent", 0, return_text);
            });

        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : "BeTemplate Shortcode Button",
                author : "tkt",
                version : "1"
            };
        }
    });

    tinymce.PluginManager.add("betemplate_button_plugin", tinymce.plugins.betemplate_button_plugin);
})();