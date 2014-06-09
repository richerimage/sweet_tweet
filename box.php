<?php
/*
Name: Sweet Tweet Button
Author: Richard Barratt
Description: Adds advanced Twitter sweetness to Thesis 2!
Version: 1.2
Class: dna_sweet_tweet
License: MIT

Copyright 2013 DIYthemes, LLC.
DIYthemes, Thesis, and the Thesis Theme are registered trademarks of DIYthemes, LLC.
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class dna_sweet_tweet extends thesis_box {
	protected $filters = array('canvas_left' => true);

	protected function translate() {
		$this->title = __('Sweet Tweet Button', $this->_class);
	}

	public function construct() {
  global $dna_ah;

		$url        = THESIS_USER_BOXES_URL .'/'. basename(dirname(__FILE__)) . '/assets/images';
    $assets_path = THESIS_USER_BOXES .'/'. basename(dirname(__FILE__)) . '/assets';

		$this->images = array(
			'none' => "$url/twitter-horizontal-no-count.png",
			'horizontal' => "$url/twitter-horizontal-count.png",
			'vertical' => "$url/twitter-vertical.png");

    add_shortcode('tweeting', array($this, 'sweet_tweet_shortcode'));

    if (is_admin()){
      if(!class_exists('dna_asset_handler'))
        include_once($assets_path . '/dna_asset_handler.php');
      if(!isset($dna_ah)) {
        $dna_ah = new dna_asset_handler;
      } 
    }

	}


	protected function class_options() {
		global $thesis;

		return array(
			'layout' => array(
				'type' => 'radio',
				'label' => __('Button Layout', $this->_class),
				'tooltip' => sprintf(__('Check out Twitter for more information <a href="%1$s" target="_blank">about the %2$s</a>.', $this->_class), 'http://twitter.com/about/resources/tweetbutton', $this->title),
				'options' => array(
					'vertical' => __('Vertical with count', $this->_class) . " <img src=\"". esc_url($this->images['vertical']) ."\" />",
					'horizontal' => __('Horizontal with count.', $this->_class) . " <img src=\"". esc_url($this->images['horizontal']) ."\" />",
					'none' => __('No count.', $this->_class) . " <img src=\"". esc_url($this->images['none']) ."\" />"),
				'default' => 'vertical'),
			
      'username' => array(
				'type' => 'text',
				'width' => 'medium',
				'label' => __('Twitter Username', $this->_class),
				'tooltip' => __('Enter your Twitter username here to associate this button with your Twitter account. This field is also required for the Twitter Card.', $this->_class)),
			
      'via' => array(
				'type' => 'checkbox',
				'options' => array(
					'mention' => __('Mention your username in the tweet', $this->_class))),

      'intro' => array(
        'type' => 'text',
        'width' => 'medium',
        'label' => __('Shortcode Tweet Intro', $this->_class),
        'tooltip' => __('Intro text to your shortcode output. e.g "tweet this"', $this->_class)),
			
      'card' => array(
				'type' => 'checkbox',
				'label' => __('Twitter Card', $this->_class),
				'options' => array(
					'show' => __('show Twitter Card metadata in document <code>&lt;head&gt;</code>', $this->_class)),
				'tooltip' => sprintf(__('If you have signed up for the <a href="%1$s">Twitter Card</a> program, Thesis will automatically output the necessary meta tags. <strong>Note:</strong> Currently, only the <code>summary</code> card is supported.', $this->_class), 'https://dev.twitter.com/form/participate-twitter-cards'),
				'dependents' => array('show')),
			
      'image' => array(
				'type' => 'text',
				'width' => 'full',
				'label' => __('Default Image', $this->_class),
				'tooltip' => sprintf(__('Provide a %1$s to the default image. This image will be used if you do not have either a Thesis Post Image or a WP Featured Image. Load the image via the <a href="%2$s" target="_blank">WP Media Gallery</a>.', $this->_class), $thesis->api->base['url'], admin_url('media-new.php')),
				'parent' => array(
					'card' => 'show')),
      );
	}

  protected function html_options (){
  global $thesis;
                       
    $css = $thesis->api->css->options; // shorthand for all options available in the CSS API
    $font_family = $thesis->api->css->properties['font-family'];
    $fsc = $thesis->api->css->font_size_color();
    $o = $thesis->api->css->options;
    $p = $thesis->api->css->properties;
    $font       = array('font'  => $o['font']);
    $background = array('background'  => $o['background']);
    $border     = array('border'      => $o['border']);
    $padding    = array('padding'     => $o['padding']);
    $margin     = array('margin'      => $o['margin']);
    $float      = array('float'       => $o['float']);
    $num_tt     = __('If you enter only a number, Thesis will assume you want your output in pixels. If you want to use any other unit, please supply it here.', $this->_class);
    $twitter_svg = "<svg version=\"1.1\" class=\"twitter-svg\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0\" y=\"0\" viewBox=\"0 0 24 20\" enable-background=\"new 0 0 24 20\" xml:space=\"preserve\" height=\"20\" width=\"24\"><path id=\"_x31_6\" fill=\"#00ACEE\" d=\"M24.2 2.4c-0.9 0.4-1.8 0.6-2.8 0.8 0 0 0 0 0-0.1 1-0.6 1.8-1.6 2.2-2.7 -0.3 0.2-1.8 1-3.2 1.3 -0.9-1-2.3-1.7-3.7-1.7 -3.1 0-5.1 2.6-5.1 5.1 0 0.2 0.1 1 0.1 1C7.5 6 3.8 4 1.3 0.9 -0.4 4 1.4 6.7 2.7 7.7c-0.7 0-1.4-0.2-2-0.4 0.1 2.4 1.9 4.4 4.2 4.9 -0.9 0.3-2 0.1-2.5 0 0.7 2 2.5 3.5 4.8 3.6C3.7 18.2 0.1 18 0 18c2.2 1.3 4.7 2.1 7.4 2 9.9-0.1 14.9-8.8 14.3-15.1C22.8 4.4 23.7 3.5 24.2 2.4z\"/></svg>";



    $border_radius = array(
      'br_tl' => array(
        'type' => 'text',
        'width' => 'tiny',
        'label' => __('Border Radius Top Left', $this->_class),
        'tooltip' => $num_tt),
      'br_tr' => array(
        'type' => 'text',
        'width' => 'tiny',
        'label' => __('Border Radius Top Right', $this->_class),
        'tooltip' => $num_tt),       
      'br_br' => array(
        'type' => 'text',
        'width' => 'tiny',
        'label' => __('Border Radius Bottom Right', $this->_class),
        'tooltip' => $num_tt),     
      'br_bl' => array(
        'type' => 'text',
        'width' => 'tiny',
        'label' => __('Border Radius Bottom Left', $this->_class),
        'tooltip' => $num_tt));

    $intro_styles = array(
      'font-weight' => array(
        'type' => 'select',
        'label' => __('Font Weight', $this->_class),
        'options' => $thesis->api->css->properties['font-weight']),
      'font-style' => array(
        'type' => 'select',
        'label' => __('Font Style', $this->_class),
        'options' => $thesis->api->css->properties['font-style']),
     );

    $logo = array (
      'show_logo' => array(
        'type' => 'checkbox',
        'options' => array(
          'show' => __('Show a twitter svg logo ' . $twitter_svg . '', $this->_class)),
        'dependents' => array('show')),

      'logo_size' => array(
        'type' => 'text',
        'width' => 'tiny',
        'label' => __('Logo Size', $this->_class),
        'tooltip' => $num_tt,
        'parent' => array('show_logo' => 'show')),


      'logo_color' => array(
        'type' => 'color',
        'label' => __('Logo color', $this->_class),
        'parent' => array('show_logo' => 'show')),

    );
    
    $html = $thesis->api->html_options();
    unset($html['id']);
    
    $html['sweet_css'] = array(
    'type' => 'object_set',
    'label' => __('Shortcode Output Styling - <span style="background: #CCCCCC; color: #000000; display: block; font-family: menlo,\'courier new\',monospace; font-size: 11px; font-weight: normal; line-height: 1.7; margin-bottom: 5px; padding: 0 5px;">Please Note: You must save your CSS Design Options before this will take effect!</span>', $this->_class),
    'select' => __('Sweet up your design:', $this->_class),
      'objects' => array(                                                     
        'sweet_styles' => array(
          'type' => 'group',
          'label' => __('Shortcode Wrapper Styling', $this->_class),
          'fields' => array_merge($background, $border, $padding, $margin, $border_radius)), 
        'sweet_btn' => array(
          'type' => 'group',
          'label' => __('Shortcode Button Styling', $this->_class),
          'fields' => array_merge($float, $margin)),
        'sweet_intro' => array(
          'type' => 'group',
          'label' => __('Intro Text Styling', $this->_class),
          'fields' => array_merge($fsc, $intro_styles, $logo)),
      )
    );

  // print_r($html['sweet_css']);

    return $html;
  }

  protected function post_meta() {
    global $thesis;

    return array (
    'title' => __('Sweet Tweet', 'dna_sweet_tweet'),
    'fields' => array (
      
      'tweet_override' => array(
        'type' => 'textarea',
        'rows' => 3,
        'label' => __('Tweet Text', 'dna_sweet_tweet'),
        'description' => __('Enter your Tweet text here - this will override the default text of this post/page\'s title.', 'dna_sweet_tweet'),
    		'tooltip' => __('Remember to account for additional texts such as url, username and your hashtags', 'dna_sweet_tweet'),
    		'counter' => __('Character Countdown helper', 'dna_sweet_tweet'),
        ),

      'hashtag' => array(
        'type' => 'text',
        'width' => 'medium',
        'label' => __('HashTag', 'dna_sweet_tweet'),
        'description' => __('Seperate HashTags with commas <b style="color: black;">(Do not include the \'#\' Character!)</b>', $this->_class),
        'tooltip' => __('Don\'t forget your hashtags will go towards your 140 character limit', $this->_class),
      ),     
      
    ));
    
  }



	public function admin_init() {
		add_action('admin_head', array($this, 'admin_css'));
	}

	public function admin_css() {
		echo
			"<style type=\"text/css\">\n",
      "/* Howdy */",
			"#t_canvas .option_field .radio li { margin-bottom: 12px; }\n",
			"</style>\n";
	}

	public function preload() {
		if (is_singular())
			add_action('wp_head', array($this, 'card'));
	}

	public function card() {
		global $post, $thesis;
		if (empty($this->options['username']) || empty($this->options['card']['show'])) return;
		$username = '@' . trim($this->options['username'], '@');
		$metas = get_metadata($post->post_type, $post->ID);
		$url = !empty($metas['_thesis_canonical_link'][0]) && ($link = unserialize($metas['_thesis_canonical_link'][0])) ? esc_url($link['url']) : get_permalink();
		$title = !empty($metas['_thesis_title_tag'][0]) && ($title = unserialize($metas['_thesis_title_tag'][0])) ? esc_html(wp_strip_all_tags(strip_shortcodes($title['title']), true)) : get_the_title();
		add_filter('excerpt_length', array($this, 'length'));
		add_filter('excerpt_more', array($this, 'nothing'));
		$description = get_the_excerpt();
		remove_filter('excerpt_length', array($this, 'length'));
		remove_filter('excerpt_more', array($this, 'nothing'));
		$description = !empty($description) ? $description : esc_html(wp_strip_all_tags(strip_shortcodes($post->post_content)));		
		$image = !empty($metas['_thesis_post_image'][0]) && ($img = unserialize($metas['_thesis_post_image'][0]) && !empty($img['image']['url'])) ?
			esc_url($img['image']['url']) : (!empty($metas['_thumbnail_id']) ?
			esc_url(array_shift(wp_get_attachment_image_src($metas['_thumbnail_id'][0], 'full'))) : (!empty($this->options['image']) ?
			esc_url($this->options['image']) : false));
		echo
			"<meta name=\"twitter:card\" content=\"summary\">\n",
			"<meta name=\"twitter:site\" content=\"", $thesis->api->esc($username), "\">\n",
			"<meta name=\"twitter:url\" content=\"$url\">\n",
			"<meta name=\"twitter:title\" content=\"$title\">\n",
			"<meta name=\"twitter:description\" content=\"", wp_trim_words($description, 200, '…'), "\">\n",
			(!empty($image) ?
			"<meta name=\"twitter:image\" content=\"$image\">\n" : '');
	}

	public function html($args = false) {
		global $post, $thesis;
		extract($args = is_array($args) ? $args : array());
    $class = !empty($this->options['class']) ? 'sweet-tweet-btn ' . $this->options['class'] . '' : 'sweet-tweet-btn';
		$url = 'url=' . urlencode(((is_front_page() || is_home()) && !in_the_loop() ? home_url() : get_permalink($post->ID)));
		$via = !empty($this->options['via']['mention']) && !empty($this->options['username']) ? '&via='. $thesis->api->esc(strip_tags(trim($this->options['username'], '@'))) : '';
		$count = "&amp;count=" . (!empty($this->options['layout']) ? $thesis->api->esc($this->options['layout']) : 'vertical');
		$width = strpos($count, 'horizontal') ? 107 : 56;
		$height = strpos($count, 'vertical') ? 62 : 20;
		$l = get_bloginfo('language');
		$hashtag = "&amp;hashtags=" . ($this->post_meta['hashtag'] ? str_replace(' ', '', $this->post_meta['hashtag']) : '');

// 	$text = "&amp;text=" . (in_the_loop() || is_singular() ? esc_attr(strip_tags($post->post_title)) : get_option('blogname'));
		
    $default_text = in_the_loop() || is_singular() ? esc_attr(strip_tags($post->post_title)) : get_option('blogname');
    $text = "&amp;text=" . (!empty($this->post_meta['tweet_override']) ? $this->post_meta['tweet_override'] : $default_text);
		$language = ($l == 'en-US' || $l == 'en-GB') ? 'en' : ($l == 'fr_FR' ? 'fr' : ($l == 'de_DE' ? 'de' : ($l == 'it_IT' ? 'it' : ($l == 'ja' ? 'ja' : ($l == 'ko_KR' ? 'ko' : (($l == 'pt_PT' || $l == 'pt_BR') ? 'pt' : ($l == 'ru_RU' ? 'ru' : (($l == 'es_ES' || $l == 'es_PE') ? 'es' : ($l == 'tr' ? 'tr' : 'en')))))))));
		$language = "&amp;lang={$language}";

		echo
			str_repeat("\t", !empty($depth) ? $depth : 0),
			"<iframe class=\"$class\" allowtransparency=\"true\" frameborder=\"0\" scrolling=\"no\" src=\"//platform.twitter.com/widgets/tweet_button.html?{$url}{$via}{$count}{$language}{$text}{$hashtag}\" style=\"height:{$height}px; width:{$width}px;\"></iframe>\n";
	}


    public function sweet_tweet_shortcode($atts, $content = null) {
    global $thesis, $post;

    $btn_class = !empty($this->options['class']) ? 'sweet-tweet-btn ' . $this->options['class'] . '' : 'sweet-tweet-btn';
    $url = urlencode(((is_front_page() || is_home()) && !in_the_loop() ? home_url() : get_permalink($post->ID)));
    $via = !empty($this->options['via']['mention']) && !empty($this->options['username']) ? $thesis->api->esc(strip_tags(trim($this->options['username']))) : '';
    // $count = "&amp;count=" . (!empty($this->options['layout']) ? $thesis->api->esc($this->options['layout']) : 'vertical');
    // $width = strpos($count, 'horizontal') ? 107 : 56;
    // $height = strpos($count, 'vertical') ? 62 : 20;

    $count = "" . (!empty($this->options['layout']) ? $this->options['layout'] : 'vertical');

    // $width = $count == 'horizontal' ? 107 : 56;
    // $height = $count == 'vertical' ? 62 : 20;



    $icon_color = $thesis->api->colors->css (!empty($this->options['sweet_intro']['logo_color']) ? $this->options['sweet_intro']['logo_color'] : "00ACEE");
    $twitter_icon = !empty($this->options['sweet_intro']['show_logo']['show']) ? "<svg version=\"1.1\" class=\"twitter-svg\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0\" y=\"0\" viewBox=\"0 0 24 20\" enable-background=\"new 0 0 24 20\" xml:space=\"preserve\" height=\"20\" width=\"24\"><path id=\"_x31_6\" fill=\"$icon_color\" d=\"M24.2 2.4c-0.9 0.4-1.8 0.6-2.8 0.8 0 0 0 0 0-0.1 1-0.6 1.8-1.6 2.2-2.7 -0.3 0.2-1.8 1-3.2 1.3 -0.9-1-2.3-1.7-3.7-1.7 -3.1 0-5.1 2.6-5.1 5.1 0 0.2 0.1 1 0.1 1C7.5 6 3.8 4 1.3 0.9 -0.4 4 1.4 6.7 2.7 7.7c-0.7 0-1.4-0.2-2-0.4 0.1 2.4 1.9 4.4 4.2 4.9 -0.9 0.3-2 0.1-2.5 0 0.7 2 2.5 3.5 4.8 3.6C3.7 18.2 0.1 18 0 18c2.2 1.3 4.7 2.1 7.4 2 9.9-0.1 14.9-8.8 14.3-15.1C22.8 4.4 23.7 3.5 24.2 2.4z\"/></svg> " : "";
    $intro = !empty($this->options['intro']) ? '<div class="sweet-tweet-intro">' . $twitter_icon . trim($this->options['intro']) . '</div>' : '';

    extract(shortcode_atts(array(
      "id"        => '',
      "class"     => '',
      "url"       => $url,
      "mention"   => $via,
      "count"     => $count,
      "hashtag"   => '',
    ), $atts));


    $width = $count == 'horizontal' ? 107 : 56;
    $height = $count == 'vertical' ? 62 : 20;


    $id     = !empty($id)     ? "id=\"$id\" " : "";
    $class  = !empty($class)  ? "class=\"$class sweet-tweet-sc\"" : "class=\"sweet-tweet-sc\"";

    $text = do_shortcode($content);

    // print_r($text);



    $output = 
 
    "<div {$id}{$class}><iframe class=\"$btn_class\" allowtransparency=\"true\" frameborder=\"0\" scrolling=\"no\" src=\"//platform.twitter.com/widgets/tweet_button.html?".
    "url={$url}";
    if ($mention == 'false') {$output .= "";} elseif ($mention != 'false') {$output .= (!empty($mention)) ? "&via=" . $mention . "" : "";}
    $output .= "&amp;count={$count}".
    "{$language}".
    "&amp;text={$text}";
    if (!empty($hashtag)) {$output .= "&amp;hashtags=" . str_replace(' ', '', $hashtag);} else {$output .= "";}
    $output .= "\" style=\"height:{$height}px; width:{$width}px;\"></iframe>" . $intro . $text . "</div>\n";

    return $output;
  
  }

	public function length() { return 200; }

	public function nothing() { return ''; }




  public function filter_css($css){
  global $thesis;


    $bg_rep = !empty($this->options['sweet_styles']['background-repeat']) ? $this->options['sweet_styles']['background-repeat'] . " " : "repeat ";
    $bg_pos = !empty($this->options['sweet_styles']['background-position']) ? $this->options['sweet_styles']['background-position'] . " " : "0px 0px ";
    $bg_att = !empty($this->options['sweet_styles']['background-attachment']) ? $this->options['sweet_styles']['background-attachment'] . " " : "scroll ";
    $bg_img = !empty($this->options['sweet_styles']['background-image']) ? "url('" . $this->options['sweet_styles']['background-image'] . "') " . $bg_rep . $bg_pos . $bg_att . "" : "";
    $bg_col = $thesis->api->colors->css (!empty($this->options['sweet_styles']['background-color']) ? $this->options['sweet_styles']['background-color'] : 'E6F3FC');
    $bd_col = $thesis->api->colors->css (!empty($this->options['sweet_styles']['border-color']) ? $this->options['sweet_styles']['border-color'] : 'rgba(0, 0, 0, 0.1)');
    $bd_wdt = $thesis->api->css->number (!empty($this->options['sweet_styles']['border-width']) ? $this->options['sweet_styles']['border-width'] : 1);
    $bd_sty = $thesis->api->css->number (!empty($this->options['sweet_styles']['border-style']) ? $this->options['sweet_styles']['border-style'] : 'solid');
    $pad_tp = $thesis->api->css->number (!empty($this->options['sweet_styles']['padding-top']) ? $this->options['sweet_styles']['padding-top'] : 10);
    $pad_rt = $thesis->api->css->number (!empty($this->options['sweet_styles']['padding-right']) ? $this->options['sweet_styles']['padding-right'] : 10);
    $pad_bt = $thesis->api->css->number (!empty($this->options['sweet_styles']['padding-bottom']) ? $this->options['sweet_styles']['padding-bottom'] : 10);
    $pad_lf = $thesis->api->css->number (!empty($this->options['sweet_styles']['padding-left']) ? $this->options['sweet_styles']['padding-left'] : 10);
    $mar_tp = $thesis->api->css->number (!empty($this->options['sweet_styles']['margin-top']) ? $this->options['sweet_styles']['margin-top'] : '0px');
    $mar_rt = $thesis->api->css->number (!empty($this->options['sweet_styles']['margin-right']) ? $this->options['sweet_styles']['margin-right'] : '0px');
    $mar_bt = $thesis->api->css->number (!empty($this->options['sweet_styles']['margin-bottom']) ? $this->options['sweet_styles']['margin-bottom'] : 20);
    $mar_lf = $thesis->api->css->number (!empty($this->options['sweet_styles']['margin-left']) ? $this->options['sweet_styles']['margin-left'] : '0px');
    $br_tl  = $thesis->api->css->number (!empty($this->options['sweet_styles']['br_tl']) ? $this->options['sweet_styles']['br_tl'] : '0px');
    $br_tr  = $thesis->api->css->number (!empty($this->options['sweet_styles']['br_tr']) ? $this->options['sweet_styles']['br_tr'] : '0px');
    $br_br  = $thesis->api->css->number (!empty($this->options['sweet_styles']['br_br']) ? $this->options['sweet_styles']['br_br'] : '0px');
    $br_bl  = $thesis->api->css->number (!empty($this->options['sweet_styles']['br_bl']) ? $this->options['sweet_styles']['br_bl'] : '0px');

    $float      = !empty($this->options['sweet_btn']['float']) ? $this->options['sweet_btn']['float'] : 'right';
    $flmr       = ($float == 'left')  ? '10px' : '0px';
    $flml       = ($float == 'right') ? '10px' : '0px';
    $btn_mar_tp = $thesis->api->css->number (!empty($this->options['sweet_btn']['margin-top']) ? $this->options['sweet_btn']['margin-top'] : '0px');
    $btn_mar_rt = $thesis->api->css->number (!empty($this->options['sweet_btn']['margin-right']) ? $this->options['sweet_btn']['margin-right'] : $flmr);
    $btn_mar_bt = $thesis->api->css->number (!empty($this->options['sweet_btn']['margin-bottom']) ? $this->options['sweet_btn']['margin-bottom'] : '0px');
    $btn_mar_lf = $thesis->api->css->number (!empty($this->options['sweet_btn']['margin-left']) ? $this->options['sweet_btn']['margin-left'] : $flml);



    $size = $thesis->api->css->number(!empty($this->options['sweet_intro']['font-size']) ? "\tfont-size: " . $this->options['sweet_intro']['font-size'] . "px;\n" : "");
    $family = $thesis->api->fonts->family (!empty($this->options['sweet_intro']['font-family']) ? "f\tont-family: " . $this->options['sweet_intro']['font-family'] . ";\n" : "");
    $color = $thesis->api->colors->css (!empty($this->options['sweet_intro']['font-color']) ? "\tcolor: " . $this->options['sweet_intro']['font-color'] . ";\n" : "\tcolor: #0084B4;");
    $weight = !empty($this->options['sweet_intro']['font-weight']) ? "\tfont-weight: " . $this->options['sweet_intro']['font-weight'] . ";\n" : "";
    $style = !empty($this->options['sweet_intro']['font-style']) ? "\tfont-style: " . $this->options['sweet_intro']['font-style'] . ";\n" : "";

    $logo_size = !empty($this->options['sweet_intro']['logo-size']) ? 'height: ' . $this->options['sweet_intro']['logo-size'] . 'px; ' : '';



  $sweets_css = 

  "\n/*-- Sweet Tweet CSS --*/\n".
  ".sweet-tweet-sc {\n".
  "\tbackground: {$bg_img}$bg_col;\n".
  "\tborder: $bd_sty $bd_wdt $bd_col;\n";
  if ($br_tl != '0px' || $br_tr != '0px' || $br_br != '0px' || $br_bl != '0px') {
    $sweets_css .= 
    "\t-moz-border-radius: $br_tl $br_tr $br_br $br_bl;\n".
    "\t-webkit-border-radius: $br_tl $br_tr $br_br $br_bl;\n".
    "\tborder-radius: $br_tl $br_tr $br_br $br_bl;\n";
  } else {}
  $sweets_css .=
  "\tpadding: $pad_tp $pad_rt $pad_bt $pad_lf;\n".
  "\tmargin: $mar_tp $mar_rt $mar_bt $mar_lf;\n".
  "\tmin-height: 80px;}\n";
  if (!empty($this->options['intro'])) {
    $sweets_css .= 
  ".sweet-tweet-intro {\n".
  "" . $size . $style . $weight . $family . $color . "}\n";
  } else {}

    if (!empty($this->options['sweet_intro']['show_logo']['show'])) {
      $sweets_css .=
    ".twitter-svg {" . $logo_size . "width: auto;}\n";
    }
  
  
  $sweets_css .=
  ".sweet-tweet-btn {\n".
  "\tfloat: $float;\n".
  "\tmargin: $btn_mar_tp $btn_mar_rt $btn_mar_bt $btn_mar_lf;}\n";

  return $css . $sweets_css;

  }

}




















