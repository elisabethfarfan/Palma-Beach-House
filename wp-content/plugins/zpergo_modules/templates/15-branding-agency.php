<?php
add_action( 'vc_load_default_templates_action','pergo_branding_agency_template_for_vc' ); // Hook in
function pergo_branding_agency_template_for_vc() {
    $data               = array(); // Create new array
    $data['name']       = __( 'Template: 15 - branding-agency', 'pergo' ); // Assign name for your custom template
    $data['weight']     = 0; 
    $data['image_path'] = '';
    $data['custom_class'] = ''; // CSS class name
    $data['content']    = <<<CONTENT
    [vc_row][vc_column][pergo_hero_branding_agency params="%5B%7B%22button_text%22%3A%22Get+Started+Now%22%2C%22button_size%22%3A%22btn-md%22%7D%5D"][pergo_statistic_block params="%5B%7B%22title%22%3A%2210.154%22%2C%22subtitle%22%3A%22Happy%20Clients%22%7D%2C%7B%22title%22%3A%225.069%22%2C%22subtitle%22%3A%22Tickets%20Closed%22%7D%2C%7B%22title%22%3A%2228.189%22%2C%22subtitle%22%3A%22Followers%22%7D%5D"][/pergo_hero_branding_agency][/vc_column][/vc_row][vc_section el_id="about"][vc_row][vc_column][pergo_section_title title=""][/vc_column][/vc_row][vc_row][vc_column width="1/3"][pergo_service_box icon="flaticon-idea" title="Concept &amp; Idea" subtitle="Semper lacus cursus porta, feugiat primis in ultrice ligula risus tempus auctor cubilia congue ipsum ipsum mauris lectus laoreet" css_animation="fadeInUp"][pergo_service_box icon="flaticon-settings-2" subtitle="Semper lacus cursus porta, feugiat primis in ultrice ligula risus tempus auctor cubilia congue ipsum ipsum mauris lectus laoreet" css_animation="fadeInUp"][/vc_column][vc_column width="1/3"][pergo_service_box icon="flaticon-share-2" title="Keyword Research" subtitle="Semper lacus cursus porta, feugiat primis in ultrice ligula risus tempus auctor cubilia congue ipsum ipsum mauris lectus laoreet" css_animation="fadeInUp"][pergo_service_box icon="flaticon-price-tag" title="Brand Identity" subtitle="Semper lacus cursus porta, feugiat primis in ultrice ligula risus tempus auctor cubilia congue ipsum ipsum mauris lectus laoreet" css_animation="fadeInUp"][/vc_column][vc_column width="1/3"][pergo_service_box icon="flaticon-fingerprint" title="User Experience" subtitle="Semper lacus cursus porta, feugiat primis in ultrice ligula risus tempus auctor cubilia congue ipsum ipsum mauris lectus laoreet" css_animation="fadeInUp"][pergo_service_box icon="flaticon-worldwide" title="SEO &amp; SMM Services" subtitle="Semper lacus cursus porta, feugiat primis in ultrice ligula risus tempus auctor cubilia congue ipsum ipsum mauris lectus laoreet" css_animation="fadeInUp"][/vc_column][/vc_row][/vc_section][vc_section full_width="container-wide" el_id="why-pergo"][vc_row full_width="stretch_row_content_no_spaces"][vc_column][pergo_digital_strategy strategy_list="%5B%7B%22title%22%3A%22Fully%20Responsive%20Design%22%7D%2C%7B%22title%22%3A%22Bootstrap%204.0%20Based%22%7D%2C%7B%22title%22%3A%22Google%20Analytics%20Ready%22%7D%2C%7B%22title%22%3A%22Cross%20Browser%20Compatability%22%7D%2C%7B%22title%22%3A%22Developer%20Friendly%20Commented%20Code%22%7D%2C%7B%22title%22%3A%22and%20much%20more%E2%80%A6%22%7D%5D"]An enim tempor sapien gravida donec ipsum blandit porta justo integer odio velna vitae auctor integer congue magna pretium purus pretium ligula rutrum luctus risus ultrice luctus[/pergo_digital_strategy][/vc_column][/vc_row][/vc_section][vc_section full_width="container-wide" padding_class="wide-0" el_id="how-it-works"][vc_row full_width="stretch_row_content_no_spaces"][vc_column][pergo_watch_video image="http://jthemes.org/wp/pergo/files/images/video-1.jpg" title="See the benefits you can get by working with our experts"][/vc_column][/vc_row][/vc_section][vc_section bg_class="bg-lightgrey"][vc_row][vc_column][pergo_digital_solutions subtitle="Digital Strategy" title="Stylish &amp; featured landing pages pack" display="counter" counter_group="%5B%7B%22title%22%3A%22Happy%20Clients%22%2C%22count%22%3A%221154%22%7D%2C%7B%22title%22%3A%22Tickets%20Closed%22%2C%22count%22%3A%22409%22%7D%5D" params="%5B%5D"]An enim nullam tempor sapien gravida donec enim ipsum blandit porta justo integer odio velna vitae auctor integer congue magna at pretium purus pretium ligula rutrum luctus risus ultrice luctus ligula congue vitae auctor eros erat magna morbi pretium neque[/pergo_digital_solutions][/vc_column][/vc_row][/vc_section][vc_section bg_class="bg-lightgrey" el_id="portfolio"][vc_row][vc_column][pergo_section_title title="Creative Ideas That Impress" subtitle="Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero tempus, tempor posuere ligula varius impedit enim tempor sapien" tag="h2:h2-xs"][pergo_portfolios template="portfolio/isotope1.php" posts_per_page="9" tax_term="" order="desc" orderby="date"][/vc_column][/vc_row][/vc_section][vc_section parallax="content-moving" parallax_image="http://jthemes.org/wp/pergo/files/images/reviews.jpg" padding_class="wide-100" bg_class="bg-dark" parallax_image_repeat="" parallax_image_position="50% 0" parallax_image_attachment="inherit" el_id="testimonials"][vc_row][vc_column][pergo_testimonials params="%5B%7B%22name%22%3A%22pebz13%22%2C%22title%22%3A%22Programmer%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Freview-author-1.jpg%22%2C%22desc%22%3A%22%5C%22%20Etiam%20sapien%20sem%20at%20sagittis%20congue%20augue%20massa%20varius%20sodales%20sapien%20undo%20tempus%20dolor%20%20%20%20%20%20%20%20egestas%20magna%20suscipit%20magna%20tempus%20aliquet%20porta%20sodales%20augue%20suscipit%20luctus%20neque%20%5C%22%22%7D%2C%7B%22name%22%3A%22Evelyn%20Martinez%22%2C%22title%22%3A%22Housewife%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Freview-author-2.jpg%22%2C%22desc%22%3A%22%5C%22%20Etiam%20sapien%20sem%20at%20sagittis%20congue%20augue%20massa%20varius%20sodales%20sapien%20undo%20tempus%20dolor%20%20%20%20%20%20%20%20egestas%20magna%20suscipit%20magna%20tempus%20aliquet%20porta%20sodales%20augue%20suscipit%20luctus%20neque%20%5C%22%22%7D%2C%7B%22name%22%3A%22Robert%20Peterson%22%2C%22title%22%3A%22SEO%20Manager%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Freview-author-3.jpg%22%2C%22desc%22%3A%22%5C%22%20Etiam%20sapien%20sem%20at%20sagittis%20congue%20augue%20massa%20varius%20sodales%20sapien%20undo%20tempus%20dolor%20%20%20%20%20%20%20%20egestas%20magna%20suscipit%20magna%20tempus%20aliquet%20porta%20sodales%20augue%20suscipit%20luctus%20neque%20%5C%22%22%7D%5D"][/vc_column][/vc_row][/vc_section][vc_section el_id="team"][vc_row][vc_column][pergo_section_title title="Our Creative Team" subtitle="Our team and staff is presented here, we do what we love. Who we are, what we do and our social networks, connect with us." tag="h2:h2-xs"][/vc_column][/vc_row][vc_row][vc_column][pergo_team_template order="desc" orderby="date"][/vc_column][/vc_row][/vc_section][vc_section padding_class="wide-0" bg_class="bg-lightgrey"][vc_row][vc_column][vc_empty_space height="100px"][pergo_bring_ideas params="%5B%7B%22icon%22%3A%22fa%20fa-angle-double-right%22%2C%22button_text%22%3A%22Get%20Started%20Now%22%2C%22button_url%22%3A%22%23%22%2C%22button_target%22%3A%22_self%22%2C%22button_size%22%3A%22btn-md%22%7D%5D"][vc_empty_space height="10px"][/vc_column][/vc_row][/vc_section][vc_section el_id="blog"][vc_row][vc_column][pergo_section_title title="Our Stories &amp; Latest News" tag="h2:h2-xs"][pergo_posts img_size="pergo-400x400-crop" tax_term=""][/vc_column][/vc_row][/vc_section][vc_section parallax="content-moving" parallax_image="" padding_class="wide-70" bg_class="bg-dark" parallax_image_repeat="" parallax_image_position="50% 0" parallax_image_attachment="inherit" css=".vc_custom_1534236965443{background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}" el_id="clients"][vc_row][vc_column][pergo_section_title title="Trusted by thousands of companies" subtitle="Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero tempus, tempor posuere ligula varius augue luctus donec volutpat sapien"][pergo_our_clients params="%5B%7B%22title%22%3A%22Brand%20image%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Fbrand-11.png%22%7D%2C%7B%22title%22%3A%22Brand%20image%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Fbrand-17.png%22%7D%2C%7B%22title%22%3A%22Brand%20image%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Fbrand-13.png%22%7D%2C%7B%22title%22%3A%22Brand%20image%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Fbrand-18.png%22%7D%2C%7B%22title%22%3A%22Brand%20image%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Fbrand-19.png%22%7D%2C%7B%22title%22%3A%22Brand%20image%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Fbrand-16.png%22%7D%2C%7B%22title%22%3A%22Brand%20image%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Fbrand-14.png%22%7D%2C%7B%22title%22%3A%22Brand%20image%22%2C%22image%22%3A%22http%3A%2F%2Fjthemes.org%2Fwp%2Fpergo%2Fstartup%2Fwp-content%2Fthemes%2Fpergo%2Fimages%2Fbrand-15.png%22%7D%5D"][/vc_column][/vc_row][/vc_section][vc_section parallax="content-moving" parallax_image="" padding_class="wide-100" bg_class="bg-lightgrey" parallax_image_repeat="" parallax_image_position="bottom center" parallax_image_attachment="inherit" el_class="contacts-section" el_id="contacts"][vc_row][vc_column][pergo_section_title title="Let's Make Something Great" subtitle="Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero tempus, tempor posuere ligula varius impedit enim tempor sapien" tag="h2:h2-xs"][/vc_column][/vc_row][vc_row][vc_column width="1/2" el_class="ind-20"][vc_column_text]
<h4 class="h4-xs">Get in Touch</h4>
<p class="p-md">We are always open to discuss software development projects and meet our clients in our cozy offices</p>
[/vc_column_text][pergo_google_map][vc_empty_space height="40px"][vc_row_inner][vc_column_inner width="1/2"][pergo_contact_info css_animation="fadeInUp" animation_delay="300"][/vc_column_inner][vc_column_inner width="1/2"][pergo_contact_info title="Let's Talk" subtitle="Phone : +12 3 3456 7890
Fax : +12 9 8765 4321" css_animation="fadeInUp" animation_delay="400"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/2"][vc_column_text]
<h4 class="h4-xs">Send a Message</h4>
<p class="p-md">Please use the form below to contact us. We will never spam you, or sell your email to third parties. All fields are required</p>
[/vc_column_text][contact-form-7 id="318"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="40px"][/vc_column][/vc_row][/vc_section]
CONTENT;
  
    vc_add_default_templates( $data );   
}
