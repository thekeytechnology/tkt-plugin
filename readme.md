# the key technology Wordpress Utility Plugin

This plugin combines a lot of common functionality for usage with Wordpress, from templating to cookie management.



## Conversion Tracking

Enables conversion tracking via Google Analytics and Facebook.

You can either activate the by adding

    if (function_exists("tkInstallConversionTracking")) {
        tkInstallConversionTracking(CF7, Call, Mail, Mailchimp, SignUps);
    }

to activate everything with their default values, or use the functions listed below to activate each separately.

The parameters are optional. Set one to false in order to not install it.

You can also install them individually. To do so follow the instructions below:


### Contact Form 7

#Setup:

Add

    if (function_exists("tkInstallCF7ConversionTracking")) {
        tkInstallCF7ConversionTracking();
    }

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

### MailChimp

Add

    if (function_exists("tkInstallMailchimpConversionTracking")) {
        tkInstallMailchimpConversionTracking();
    }

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


### Calls (tel: links)
Add

    if (function_exists("tkInstallCallConversionTracking")) {
        tkInstallCallConversionTracking(CATEGORY, ACTION, LABEL, VALUE);
    }

to functions.php

Parameters are optional. CATEGORY defaults to "Conversion". ACTION defaults to "Anruf". VALUE must be integer > 0 to be included.


## E-Mails (mailto: links)
Add

    if (function_exists("tkInstallMailConversionTracking")) {
       tkInstallMailConversionTracking(CATEGORY, ACTION, LABEL, VALUE);
    }

to functions.php

Parameters are optional. CATEGORY defaults to "Conversion". ACTION defaults to "E-Mail". VALUE must be integer > 0 to be included.


### SignUps
Add
    if (function_exists("tkInstallSignUpConversionTracking")) {
       tkInstallSignUpConversionTracking(CATEGORY, ACTION, LABEL, VALUE);
    }

to functions.php

Parameters are optional. CATEGORY defaults to "Conversion". ACTION defaults to "SignUp". VALUE must be integer > 0 to be included.



## Attachment Image Titles

TODO: If Image Fixes works correctly, obsolete this

**Description**

Make WP also add the title to images (doesn't affect tags in visual editor).

For img tags in the visual editor, use the Restore Image Title plugin.

**Setup**

Add

    if (function_exists("tkInstallImageTitle")) {
        tkInstallImageTitle();
    }

to functions.php


## (BETA) Image Fixes

TODO: Test whether this functions correctly with CDN

**Description**

Makes images uploaded via the media library responsive.
Adds title and alt attributes to images uploaded via the media library if the attributes are missing/empty and the values are set in the media library.
(Without this, images in mfn builder elements are not responsive and need to have their title/alt set manually in the builder element/visual editor.)

**Setup**

Add

    if (function_exists("tkInstallImageFixes")) {
        tkInstallImageFixes();
    }

to functions.php

## Empty Element Removal

**Description**

Removes empty <p> tags from the_content.

**Setup**

Add

    if (function_exists("tkInstallRemoveEmptyElements")) {
        tkInstallRemoveEmptyElements();
    }

to functions.php


## BeTemplate

**Description**

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

**Setup**

In BeTheme's Theme Options under Global -> Advanced -> Theme Functions -> Post Type | Disable: Make sure "Templates" is NOT disabled.

Add

    if (function_exists("tkInstallBeTemplate")) {
        tkInstallBeTemplate();
    }

to functions.php

Hint: For easy inclusion of BeTemplate content in a mfn builder section (regular column), use /theme-utils/assets/less/betemplate.less to remove the default styling of the surrounding section/wrap/column.

## PrettyPhoto

**Description**

Enables http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/

**Setup**

Add
    if (function_exists("tkInstallPrettyPhoto")) {
        tkInstallPrettyPhoto();
    }

to functions.php

Include the following (with the options of your choice) in the theme:

    jQuery(document).ready(function($){
        $("a[rel^='prettyPhoto']").prettyPhoto();
    });



## Slick

**Description**

Enables http://kenwheeler.github.io/slick/

**Setup**

Add

    if (function_exists("tkInstallSlick")) {
        tkInstallSlick();
    }

to functions.php

Include the following (with the options of your choice) in the theme:

    jQuery(document).ready(function($){
        $("INSERT_SELECTOR_HERE").slick();
    });

Attention:
If you want to target muffin builder wraps as slides, you need to add 
    $(".slick-slide").removeClass("mcb-wrap");
to your  jQuery(document).ready(function($){ function.

Caution:
Slick does not work well with wp-rocket's lazyloading. Use Slick's own lazyloading and make sure wp-rocket's lazyloading is not applied to Slick slider images. https://docs.wp-rocket.me/article/15-disabling-lazy-load-on-specific-images



## Content Subnavigation

**Description**

Page template with sidebar that links to sections headed by h2 in the content.

**Setup**

Add

    if (function_exists("tkEnableSubnavigation")) {
        tkEnableSubnavigation();
    }

to functions.php

Add the page template and sidebar style to the theme.

In BeTheme's theme options, add a sidebar area named "Inhalt". In that sidebar area, add a html widget containing the shortcode [tkContentSubnav]

If you need a sidebar for custom post types, use tkAddSidebarToPostType($postType, $onArchives = false) to add the sidebars.



## Body Class

**Description**

Enables adding classes to the body of a specific single page/post without the need for a custom template.

**Setup**

Create a custom field named "tk-bodyclass" on the page/post (using WP's built-in functionality or Pods' page/post type extension) and input the class/classes to be added as its value. (No dots, space as seperator.)



## Remove Slug

**Description**

Removes the custom post type slug from the URL (e.g. ~/lager/lagerhalle-dresden/ becomes ~/lagerhalle-dresden/).

**Setup**

Add

    if (function_exists("tkInstallRemoveSlug")) {
       tkInstallRemoveSlug(CUSTOM_POST_TYPE_OR_TAXONOMY_NAME, SLUG, IS_TAXONOMY, REWRITE_OPTIONS);
    }

to functions.php

(e.g. tkInstallRemoveSlug("tk-storage", "lager"); )

    IS_TAXONOMY defaults to false.

    REWRITE_OPTIONS (optional) is a TKSlugRemoverRewriteOptions object.
    The slug removal functionality relies on certain rewrite rules that may not exist if the permalink structure is not set to "post name".
    If REWRITE_OPTIONS is set, the required rewrite rules are added to the rewrite rules list. TKSlugRemoverRewriteOptions has data fields for setting the rule position (as per the add_rewrite_rule parameter; default "bottom"), the init action priority (default 10) and for enabling verbose page rules (default false). The latter is only necessary if it is not already set by WP (depends on permalink settings).
    Caution: If wp_rewrite->pagination_base is changed and REWRITE_OPTIONS are used, tkInstallRemoveSlug must be called after the change, otherwise pagination URLs will be incorrect for post types/taxonomies that use pagination.



## Mail HTML

**Description**

Sets the content-type of sent mails to text/html.

**Setup**

Add

    if (function_exists("tkInstallMailHTML")) {
        tkInstallMailHTML();
    }

to functions.php



## Option Page
**Description**

To generate a wordpress admin backend options page, you can use the

    tkAddOptionsPage(OptionsConfiguration $optionsConfiguration)

function call. The OptionsConfiguration is a struct-like object with several self-explanatory data fields.


## Tracking Opt Out

    [tkTrackingOptOutLink type="PLATFORM"]Text[/tkTrackingOptOutLink]

Outputs a link that lets the user set a cookie to opt out of the respective platform's tracking.
Supported platforms:

    Google Analytics (Plugin "Google Analytics for WordPress by MonsterInsights"): type="GA"
    Facebook (Plugin "Pixel Caffeine"): type="FB"
    Google Tag Manager (Plugin "Duracell Tomi's GTM"): type="GTM"

In order for this to work, page caching must also be disabled for the cookie with the name tk-tracking-opt-out
Contact WPEngine support for the serverside setting, and also add the cookie name to the exclusion list in WP-Rocket.


## Shortcodes

    [tk-attribute field="META_KEY" single="<true | false>"]
    Returns the queried object's post meta value for the specified META_KEY. Single defaults to true.

    [tk-next-weekday weekday="<sonday | tuesday | ... | sunday (default)>"]
    Will return the date of the next days as specified by the weekday parameter. Use this to have a perpetually extending final registation date.

    [tk-read-time]
    Returns the estimated read time of the current post's content. (Uses global $post object, post_content.)

    [tk-variable-parameter tag="TAG" parameter="PARAMETER", tag_attributes="TAG_ATTRIBUTES"]DEFAULT_CONTENT[/tk-variable-parameter]
    Returns the value of the URL parameter PARAMETER base64-decoded. If tag is set, the returned value is surrounded by a TAG HTML tag. If tag_attributes is set, it is inserted into the tag as HTML attributes (tag_attributes is used as-is).