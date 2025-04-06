<?php


return [
	'title' => __( 'Blocksy - Team Members', 'blocksy' ),
	'categories' => ['about', 'blocksy'],
	
	'content' => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"7rem","bottom":"7rem"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background" style="padding-top:7rem;padding-bottom:7rem"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"bottom":"20px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black"} -->
<p class="has-text-align-center has-black-color has-text-color has-link-color" style="margin-bottom:20px;font-style:normal;font-weight:500">Our awesome team</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"800","lineHeight":"1.2"},"spacing":{"margin":{"top":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontSize":"xx-large"} -->
<h1 class="wp-block-heading has-text-align-center has-black-color has-text-color has-link-color has-xx-large-font-size" style="margin-top:0;font-style:normal;font-weight:800;line-height:1.2">The Perfect Theme For <br>Stunning Websites!</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|80"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black"} -->
<p class="has-text-align-center has-black-color has-text-color has-link-color" style="margin-bottom:var(--wp--preset--spacing--80)">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"30px","left":"30px"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"style":{"border":{"color":"#0000001f","style":"solid","width":"2px","radius":"20px"},"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"}}}} -->
<div class="wp-block-column has-border-color" style="border-color:#0000001f;border-style:solid;border-width:2px;border-radius:20px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"50px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-bottom:50px"><!-- wp:image {"id":7842,"width":"150px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"100%"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="' . trailingslashit( get_template_directory_uri() ) . 'inc/components/patterns/assets/pattern-avatar-1.webp" alt="" class="wp-image-7842" style="border-radius:100%;width:150px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"top":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontSize":"medium"} -->
<h2 class="wp-block-heading has-text-align-center has-black-color has-text-color has-link-color has-medium-font-size" style="margin-top:0px">Alicia Peterson</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"30px"}},"elements":{"link":{"color":{"text":"#000000a8"}}},"color":{"text":"#000000a8"}}} -->
<p class="has-text-align-center has-text-color has-link-color" style="color:#000000a8;margin-bottom:30px">Elementum tempus egestas sed risus pretium quam risus feugiat in ante metus dictumat.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"size":"has-normal-icon-size","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"bottom":"60px"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links has-normal-icon-size" style="margin-bottom:60px"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /-->

<!-- wp:social-link {"url":"#","service":"wordpress"} /--></ul>
<!-- /wp:social-links -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":{"top":"20px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"black","textColor":"white","width":100,"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"12px","bottom":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-white-color has-black-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:8px;padding-top:12px;padding-bottom:12px">View portfolio →</a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"black","width":100,"className":"is-style-outline","style":{"border":{"radius":"8px","color":"#0000001f","style":"solid","width":"2px"},"spacing":{"padding":{"top":"12px","bottom":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}},"color":{"background":"#ffffff00"}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-black-color has-text-color has-background has-link-color has-border-color wp-element-button" style="border-color:#0000001f;border-style:solid;border-width:2px;border-radius:8px;background-color:#ffffff00;padding-top:12px;padding-bottom:12px">More info</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"color":"#0000001f","style":"solid","width":"2px","radius":"20px"},"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"}}}} -->
<div class="wp-block-column has-border-color" style="border-color:#0000001f;border-style:solid;border-width:2px;border-radius:20px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"50px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-bottom:50px"><!-- wp:image {"id":7833,"width":"150px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"100%"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="' . trailingslashit( get_template_directory_uri() ) . 'inc/components/patterns/assets/pattern-avatar-2.webp" alt="" class="wp-image-7833" style="border-radius:100%;width:150px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"top":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontSize":"medium"} -->
<h2 class="wp-block-heading has-text-align-center has-black-color has-text-color has-link-color has-medium-font-size" style="margin-top:0px">Michael Anderson</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"30px"}},"elements":{"link":{"color":{"text":"#000000a8"}}},"color":{"text":"#000000a8"}}} -->
<p class="has-text-align-center has-text-color has-link-color" style="color:#000000a8;margin-bottom:30px">Elementum tempus egestas sed risus pretium quam risus feugiat in ante metus dictumat.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"size":"has-normal-icon-size","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"bottom":"60px"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links has-normal-icon-size" style="margin-bottom:60px"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /-->

<!-- wp:social-link {"url":"#","service":"wordpress"} /--></ul>
<!-- /wp:social-links -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":{"top":"20px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"black","textColor":"white","width":100,"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"12px","bottom":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-white-color has-black-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:8px;padding-top:12px;padding-bottom:12px">View portfolio →</a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"black","width":100,"className":"is-style-outline","style":{"border":{"radius":"8px","color":"#0000001f","style":"solid","width":"2px"},"spacing":{"padding":{"top":"12px","bottom":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}},"color":{"background":"#ffffff00"}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-black-color has-text-color has-background has-link-color has-border-color wp-element-button" style="border-color:#0000001f;border-style:solid;border-width:2px;border-radius:8px;background-color:#ffffff00;padding-top:12px;padding-bottom:12px">More info</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"color":"#0000001f","style":"solid","width":"2px","radius":"20px"},"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"}}}} -->
<div class="wp-block-column has-border-color" style="border-color:#0000001f;border-style:solid;border-width:2px;border-radius:20px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"50px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-bottom:50px"><!-- wp:image {"id":7834,"width":"150px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"100%"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="' . trailingslashit( get_template_directory_uri() ) . 'inc/components/patterns/assets/pattern-avatar-3.webp" alt="" class="wp-image-7834" style="border-radius:100%;width:150px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"top":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontSize":"medium"} -->
<h2 class="wp-block-heading has-text-align-center has-black-color has-text-color has-link-color has-medium-font-size" style="margin-top:0px">Olivia Ortega</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"30px"}},"elements":{"link":{"color":{"text":"#000000a8"}}},"color":{"text":"#000000a8"}}} -->
<p class="has-text-align-center has-text-color has-link-color" style="color:#000000a8;margin-bottom:30px">Elementum tempus egestas sed risus pretium quam risus feugiat in ante metus dictumat.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"size":"has-normal-icon-size","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"bottom":"60px"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links has-normal-icon-size" style="margin-bottom:60px"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /-->

<!-- wp:social-link {"url":"#","service":"wordpress"} /--></ul>
<!-- /wp:social-links -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":{"top":"20px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"black","textColor":"white","width":100,"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"12px","bottom":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-white-color has-black-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:8px;padding-top:12px;padding-bottom:12px">View portfolio →</a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"black","width":100,"className":"is-style-outline","style":{"border":{"radius":"8px","color":"#0000001f","style":"solid","width":"2px"},"spacing":{"padding":{"top":"12px","bottom":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}},"color":{"background":"#ffffff00"}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-black-color has-text-color has-background has-link-color has-border-color wp-element-button" style="border-color:#0000001f;border-style:solid;border-width:2px;border-radius:8px;background-color:#ffffff00;padding-top:12px;padding-bottom:12px">More info</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"color":"#0000001f","style":"solid","width":"2px","radius":"20px"},"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"}}}} -->
<div class="wp-block-column has-border-color" style="border-color:#0000001f;border-style:solid;border-width:2px;border-radius:20px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"50px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-bottom:50px"><!-- wp:image {"id":7860,"width":"150px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"100%"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="' . trailingslashit( get_template_directory_uri() ) . 'inc/components/patterns/assets/pattern-avatar-4.webp" alt="" class="wp-image-7860" style="border-radius:100%;width:150px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"top":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"textColor":"black","fontSize":"medium"} -->
<h2 class="wp-block-heading has-text-align-center has-black-color has-text-color has-link-color has-medium-font-size" style="margin-top:0px">Thomas Lewroy</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"30px"}},"elements":{"link":{"color":{"text":"#000000a8"}}},"color":{"text":"#000000a8"}}} -->
<p class="has-text-align-center has-text-color has-link-color" style="color:#000000a8;margin-bottom:30px">Elementum tempus egestas sed risus pretium quam risus feugiat in ante metus dictumat.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"size":"has-normal-icon-size","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"bottom":"60px"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links has-normal-icon-size" style="margin-bottom:60px"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /-->

<!-- wp:social-link {"url":"#","service":"wordpress"} /--></ul>
<!-- /wp:social-links -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":{"top":"20px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"black","textColor":"white","width":100,"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"12px","bottom":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-white-color has-black-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:8px;padding-top:12px;padding-bottom:12px">View portfolio →</a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"black","width":100,"className":"is-style-outline","style":{"border":{"radius":"8px","color":"#0000001f","style":"solid","width":"2px"},"spacing":{"padding":{"top":"12px","bottom":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}},"color":{"background":"#ffffff00"}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-black-color has-text-color has-background has-link-color has-border-color wp-element-button" style="border-color:#0000001f;border-style:solid;border-width:2px;border-radius:8px;background-color:#ffffff00;padding-top:12px;padding-bottom:12px">More info</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->'
];