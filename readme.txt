
--- Conversion Tracking ---

#Description:

Enables conversion tracking via Google Analytics and Facebook.


-- Contact Form 7:

#Setup:

Add

tkInstallCF7ConversionTracking();

to functions.php and add

<input type="hidden" name="tk-conversion-action" value="name_of_conversion_action"/>

to the form.

Google Analytics events can be customized further by adding the following to the form:

Category (defaults to "Conversion" if omitted)
<input type="hidden" name="tk-conversion-category" value="name_of_conversion_category"/>

Label (optional)
<input type="hidden" name="tk-conversion-label" value="name_of_conversion_label"/>

Value (optional; must be integer > 0 to be included)
<input type="hidden" name="tk-conversion-value" value="name_of_conversion_value"/>


-- Calls (tel: links):

#Setup:

Add

tkInstallCallConversionTracking(CATEGORY, ACTION, LABEL, VALUE);

to functions.php

Parameters are optional. CATEGORY defaults to "Conversion". ACTION defaults to "Anruf". VALUE must be integer > 0 to be included.


-- E-Mails (mailto: links):

#Setup:

Add

tkInstallMailConversionTracking(CATEGORY, ACTION, LABEL, VALUE);

to functions.php

Parameters are optional. CATEGORY defaults to "Conversion". ACTION defaults to "E-Mail". VALUE must be integer > 0 to be included.



--- Attachment Image Titles ---

TODO: If Image Fixes works correctly, obsolete this

#Description:

Make WP also add the title to images (doesn't affect tags in visual editor).

For img tags in the visual editor, use the Restore Image Title plugin.

#Setup:

Add

tkInstallImageTitle();

to functions.php



--- (BETA) Image Fixes ---

TODO: Test whether this functions correctly with CDN

#Description:

Makes images uploaded via the media library responsive.
Adds title and alt attributes to images uploaded via the media library if the attributes are missing/empty and the values are set in the media library.
(Without this, images in mfn builder elements are not responsive and need to have their title/alt set manually in the builder element/visual editor.)

#Setup:

Add

tkInstallImageFixes();

to functions.php



--- Empty Element Removal ---

#Description:

Removes empty <p> tags from the_content.

#Setup:

Add

tkInstallRemoveEmptyElements();

to functions.php



--- BeTemplate ---

#Description:

Insert mfn builder content anywhere via [tk-betemplate] shortcode. This utilizes BeTheme's "Template" custom post type.
Caution: The Content WP builder element outputs the current page's the_content.

Attributes:

id (required): id of the Template to be inserted

stripdown (optional): one of:
 wrap
 element
 prettytext
 plaintext
By default, nothing is stripped. "Wrap" removes the section parts, "Element" removes the section and wrap parts. "Prettytext" assumes text in a visual column; it removes everything but the text and applies wpautop. "Plaintext" assumes text in a regular column; it removes everything but the text.
Caution: Stripdown assumes that only one section and one wrap are used. If that is not the case, the output will be incorrect.

enable_nesting (optional, defaults to false): Enables nesting of this shortcode. Disabled by default because it breaks the page if nesting happens when the shortcode is used inside the_content.

setup_global_post_var (optional, defaults to false): Sets up (and resets) the global post variable. Use this when the shortcode is called in a situation where that isn't the case (e.g. ajax, 404 template), because mfn_builder_print uses WP's template tags.

#Setup:

In BeTheme's Theme Options under Global -> Advanced -> Theme Functions -> Post Type | Disable: Make sure "Templates" is NOT disabled.

Add

tkInstallBeTemplate();

to functions.php

Hint: For easy inclusion of BeTemplate content in a mfn builder section (regular column), use /theme-utils/assets/less/betemplate.less to remove the default styling of the surrounding section/wrap/column.



--- PrettyPhoto ---

#Description:

Enables http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/

#Setup:

Add

tkInstallPrettyPhoto();

to functions.php

Include the following (with the options of your choice) in the theme:

jQuery(document).ready(function($){
    $("a[rel^='prettyPhoto']").prettyPhoto();
});



--- Content Subnavigation ---

#Description:

Page template with sidebar that links to sections headed by h2 in the content.

#Setup:

Add

tkEnableSubnavigation();

to functions.php

Add the page template and sidebar style to the theme.

In BeTheme's theme options, add a sidebar area named "Inhalt". In that sidebar area, add a html widget containing the shortcode [tkContentSubnav]



--- Body Class ---

#Description:

Enables adding classes to the body of a specific single page/post without the need for a custom template.

#Setup:

Create a custom field named "tk-bodyclass" on the page/post (using WP's built-in functionality or Pods' page/post type extension) and input the class/classes to be added as its value. (No dots, space as seperator.)



--- Remove Slug ---

#Description:

Removes the custom post type slug from the URL (e.g. ~/lager/lagerhalle-münchen/ becomes ~/lagerhalle-münchen/).

#Setup:

Add

tkInstallRemoveSlug(CUSTOM_POST_TYPE_OR_TAXONOMY_NAME, SLUG, IS_TAXONOMY);

to functions.php

(e.g. tkInstallRemoveSlug("tk-storage", "lager"); ) (IS_TAXONOMY defaults to false)



--- Mail HTML ---

#Description:

Sets the content-type of sent mails to text/html.

#Setup:

Add

tkInstallMailHTML();

to functions.php