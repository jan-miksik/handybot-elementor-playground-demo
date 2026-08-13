<?php
/**
 * Plugin Name: HandyBot Demo Setup
 * Description: Creates the editable Elementor demo page and safe demo defaults.
 * Version: 0.5.4
 * Author: Codex demo
 * Requires PHP: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const HANDBYBOT_DEMO_VERSION = '0.5.4';

/**
 * Generate a stable-looking Elementor element ID.
 */
function handybot_demo_id( string $seed ): string {
	return substr( md5( 'handybot-' . $seed ), 0, 7 );
}

/**
 * Create an Elementor container.
 *
 * @param string $seed     Stable ID seed.
 * @param array  $settings Elementor settings.
 * @param array  $elements Child elements.
 */
function handybot_demo_container( string $seed, array $settings, array $elements = array() ): array {
	// Elementor 3 classic containers use underscore-prefixed advanced fields,
	// while Elementor 4 also reads the new unprefixed atomic field names.
	if ( isset( $settings['_css_classes'] ) ) {
		$settings['css_classes'] = $settings['_css_classes'];
	}
	if ( isset( $settings['css_id'] ) ) {
		$settings['_element_id'] = $settings['css_id'];
	}

	return array(
		'id'       => handybot_demo_id( $seed ),
		'elType'   => 'container',
		'settings' => $settings,
		'elements' => $elements,
		'isInner'  => false,
	);
}

/**
 * Create an Elementor widget.
 *
 * @param string $seed     Stable ID seed.
 * @param string $type     Elementor widget type.
 * @param array  $settings Elementor settings.
 */
function handybot_demo_widget( string $seed, string $type, array $settings ): array {
	return array(
		'id'         => handybot_demo_id( $seed ),
		'elType'     => 'widget',
		'settings'   => $settings,
		'elements'   => array(),
		'widgetType' => $type,
	);
}

/**
 * Shared CSS class setting.
 */
function handybot_demo_classes( string $classes ): array {
	return array(
		'_css_classes' => $classes,
		'css_classes'  => $classes,
	);
}

/**
 * Build editable Elementor content.
 */
function handybot_demo_data( array $asset_ids = array() ): array {
	$hero_image_id  = (int) ( $asset_ids['hero'] ?? 0 );
	$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : '';
	if ( ! $hero_image_url ) {
		$hero_image_url = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/handybot-software-robot-hero.png';
	}

	$hero_copy = handybot_demo_container(
		'hero-copy',
		array_merge(
			handybot_demo_classes( 'hb-hero-copy' ),
			array( 'flex_direction' => 'column', 'justify_content' => 'center', 'width' => array( 'unit' => '%', 'size' => 54 ) )
		),
		array(
			handybot_demo_widget( 'hero-kicker', 'heading', array_merge( handybot_demo_classes( 'hb-eyebrow' ), array( 'title' => '✦ SOFTWAROVÁ AUTOMATIZACE PRO FIRMY', 'header_size' => 'div' ) ) ),
			handybot_demo_widget( 'hero-title', 'heading', array_merge( handybot_demo_classes( 'hb-hero-title' ), array( 'title' => 'Méně rutiny. Více práce, která má smysl.', 'header_size' => 'h1' ) ) ),
			handybot_demo_widget( 'hero-text', 'text-editor', array_merge( handybot_demo_classes( 'hb-lead hb-hero-explainer' ), array( 'editor' => '<p>Automatizujeme účetnictví, administrativu a firemní procesy pomocí softwarových robotů.</p>' ) ) )
		)
	);

	$hero_visual = handybot_demo_container(
		'hero-visual',
		array_merge( handybot_demo_classes( 'hb-hero-visual' ), array( 'width' => array( 'unit' => '%', 'size' => 46 ), 'justify_content' => 'center' ) ),
		array(
			handybot_demo_widget(
				'hero-image',
				'image',
				array_merge(
					handybot_demo_classes( 'hb-hero-image' ),
					array(
						'image'          => array( 'url' => $hero_image_url, 'id' => $hero_image_id ),
						'image_size'     => 'full',
						'caption_source' => 'none',
						'link_to'        => 'none',
					)
				)
			)
		)
	);

	$client_asset_url = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/clients/';
	$client_logos     = array(
		array( 'accace', 'accace.webp', 'Accace' ),
		array( 'microsoft', 'microsoft.webp', 'Microsoft' ),
		array( 'grant_thornton', 'grant-thornton.webp', 'Grant Thornton' ),
		array( 'drmax', 'drmax.webp', 'Dr. Max' ),
		array( 'sazka', 'sazka.webp', 'Sazka' ),
	);
	$client_logo_widgets = array();
	foreach ( $client_logos as $client_logo ) {
		$client_image_id  = (int) ( $asset_ids[ $client_logo[0] ] ?? 0 );
		$client_image_url = $client_image_id ? wp_get_attachment_image_url( $client_image_id, 'full' ) : '';
		if ( in_array( $client_logo[0], array( 'drmax', 'sazka' ), true ) ) {
			$client_image_id  = 0;
			$client_image_url = '';
		}
		if ( ! $client_image_url ) {
			$client_image_id  = 0;
			$client_image_url = $client_asset_url . $client_logo[1];
		}
		$client_logo_widgets[] = handybot_demo_container(
			'client-' . $client_logo[0],
			handybot_demo_classes( 'hb-client-logo' ),
			array(
				handybot_demo_widget(
					'client-image-' . $client_logo[0],
					'image',
					array_merge(
						handybot_demo_classes( 'hb-client-logo-image' ),
						array(
							'image'          => array( 'url' => $client_image_url, 'id' => $client_image_id ),
							'image_size'     => 'full',
							'caption_source' => 'none',
							'link_to'        => 'custom',
							'link'           => array( 'url' => 'https://handybot.cz/reference/', 'is_external' => 'on', 'nofollow' => 'on' ),
						)
					)
				)
			)
		);
	}

	$metric_cards = array();
	$metrics      = array(
		array( '30', 'automatizovaných procesů u Accace' ),
		array( '40 h', 'měsíčně zpět pro účetní tým Grant Thornton' ),
		array( '30 %', 'nižší zátěž obchodního týmu u Accace' ),
	);
	foreach ( $metrics as $index => $metric ) {
		$metric_cards[] = handybot_demo_container(
			'metric-' . $index,
			array_merge( handybot_demo_classes( 'hb-metric' ), array( 'width' => array( 'unit' => '%', 'size' => 33.333 ) ) ),
			array(
				handybot_demo_widget( 'metric-number-' . $index, 'heading', array_merge( handybot_demo_classes( 'hb-metric-number' ), array( 'title' => $metric[0], 'header_size' => 'div' ) ) ),
				handybot_demo_widget( 'metric-text-' . $index, 'text-editor', array( 'editor' => '<p>' . esc_html( $metric[1] ) . '</p>' ) )
			)
		);
	}

	$roi_html = '<div class="hb-roi-calculator" data-hb-roi>'
		. '<div class="hb-roi-fields">'
		. '<label><span>Počet lidí</span><input type="number" min="1" max="500" step="1" value="3" data-roi-input="people"></label>'
		. '<label><span>Hodin rutiny na osobu / měsíc</span><input type="number" min="0" max="300" step="1" value="20" data-roi-input="hours"></label>'
		. '<label><span>Průměrná hodinová sazba</span><span class="hb-input-suffix"><input type="number" min="0" max="10000" step="50" value="450" data-roi-input="rate"><b>Kč</b></span></label>'
		. '</div>'
		. '<p class="hb-roi-assumption">Orientačně počítáme s automatizací 70 % rutinní práce.</p>'
		. '<div class="hb-roi-results">'
		. '<div><small>ÚSPORA ZA MĚSÍC</small><strong data-roi-output="monthly">18 900 Kč</strong><span data-roi-output="hours">42 hodin práce</span></div>'
		. '<div><small>ÚSPORA ZA ROK</small><strong data-roi-output="yearly">226 800 Kč</strong><span>orientační odhad</span></div>'
		. '</div>'
		. '<a class="hb-roi-cta" href="#kontakt">Domluvit konzultaci <span>→</span></a>'
		. '</div>';

	$video_notes = array();
	$video_note_data = array(
		array( '01', 'Jak poznat proces, který se vyplatí automatizovat.' ),
		array( '02', 'Co softwarový robot skutečně dělá v účetnictví.' ),
		array( '03', 'Jak probíhá spolupráce a vznik prvního demorobota.' ),
	);
	foreach ( $video_note_data as $index => $video_note ) {
		$video_notes[] = handybot_demo_container(
			'video-note-' . $index,
			array_merge( handybot_demo_classes( 'hb-video-note' ), array( 'width' => array( 'unit' => '%', 'size' => 33.333 ), 'flex_direction' => 'row' ) ),
			array(
				handybot_demo_widget( 'video-note-number-' . $index, 'heading', array_merge( handybot_demo_classes( 'hb-video-note-number' ), array( 'title' => $video_note[0], 'header_size' => 'div' ) ) ),
				handybot_demo_widget( 'video-note-text-' . $index, 'text-editor', array_merge( handybot_demo_classes( 'hb-video-note-text' ), array( 'editor' => '<p>' . esc_html( $video_note[1] ) . '</p>' ) ) ),
			)
		);
	}

	$service_cards = array();
	$services      = array(
		array( 'Účetnictví bez rutiny', 'Doklady, výpisy a přiznání zpracuje robot přesně a včas.', 'fas fa-file-invoice' ),
		array( 'Data pod kontrolou', 'Propojíme vaše systémy a odstraníme ruční přepisování dat.', 'fas fa-chart-pie' ),
		array( 'Procesy, které běží', 'Administrativa pokračuje, i když se váš tým věnuje klientům.', 'fas fa-project-diagram' ),
	);
	foreach ( $services as $index => $service ) {
		$service_cards[] = handybot_demo_container(
			'service-card-' . $index,
			array_merge( handybot_demo_classes( 'hb-card' ), array( 'width' => array( 'unit' => '%', 'size' => 33.333 ) ) ),
			array(
				handybot_demo_widget(
					'service-box-' . $index,
					'icon-box',
					array(
						'title_text'      => $service[0],
						'description_text'=> $service[1],
						'icon'            => array( 'value' => $service[2], 'library' => 'fa-solid' ),
						'position'        => 'top',
					)
				),
				handybot_demo_widget(
					'service-link-' . $index,
					'button',
					array_merge(
						handybot_demo_classes( 'hb-card-link' ),
						array( 'text' => 'Zjistit více →', 'link' => array( 'url' => '#kontakt' ), 'align' => 'left' )
					)
				)
			)
		);
	}

	$faq_items = array();
	$faq_data  = array(
		array( 'Je automatizace vhodná i pro malou firmu?', 'Ano. Rozhodující není velikost firmy, ale množství opakované práce. Smysl může dávat už činnost, která zabere několik desítek minut týdně.' ),
		array( 'Potřebuji technické znalosti?', 'Ne. Proces nám ukážete tak, jak ho děláte dnes. Návrh, realizaci i následný dohled zajistíme za vás.' ),
		array( 'Za jak dlouho uvidím první výsledek?', 'První demorobot může vzniknout během několika dnů. U kompletního řešení záleží na složitosti a dostupnosti potřebných systémů.' ),
	);
	foreach ( $faq_data as $index => $faq ) {
		$faq_items[] = array(
			'_id'         => handybot_demo_id( 'faq-item-' . $index ),
			'tab_title'   => $faq[0],
			'tab_content' => '<p>' . esc_html( $faq[1] ) . '</p>',
		);
	}

	$steps = array();
	$step_data = array(
		array( '01', 'Najdeme rutinu', 'Na krátké konzultaci společně vybereme proces s největším potenciálem.' ),
		array( '02', 'Postavíme demorobota', 'Na reálném příkladu ověříme, kolik času a práce dokáže ušetřit.' ),
		array( '03', 'Spustíme a hlídáme', 'Robota nasadíme, zaškolíme váš tým a průběžně dohlížíme na provoz.' ),
	);
	foreach ( $step_data as $index => $step ) {
		$steps[] = handybot_demo_widget(
			'step-' . $index,
			'icon-box',
			array_merge(
				handybot_demo_classes( 'hb-step' ),
				array(
					'title_text'       => $step[1],
					'description_text' => $step[2],
					'icon'             => array( 'value' => $step[0], 'library' => '' ),
					'position'         => 'left',
				)
			)
		);
	}

	return array(
		handybot_demo_container( 'hero', array_merge( handybot_demo_classes( 'hb-hero' ), array( 'content_width' => 'full', 'flex_direction' => 'row', 'gap' => array( 'unit' => 'px', 'size' => 0 ) ) ), array( $hero_copy, $hero_visual ) ),
		handybot_demo_container(
			'clients',
			array_merge( handybot_demo_classes( 'hb-clients hb-section' ), array( 'content_width' => 'full', 'flex_direction' => 'column', 'html_tag' => 'section', 'css_id' => 'vysledky' ) ),
			array(
				handybot_demo_widget( 'clients-kicker', 'heading', array_merge( handybot_demo_classes( 'hb-eyebrow' ), array( 'title' => '✦ ROBOTIZUJEME PRO', 'header_size' => 'div' ) ) ),
				handybot_demo_widget( 'clients-title', 'heading', array_merge( handybot_demo_classes( 'hb-display-title hb-clients-title' ), array( 'title' => 'Naši klienti', 'header_size' => 'h2' ) ) ),
				handybot_demo_widget( 'clients-text', 'text-editor', array_merge( handybot_demo_classes( 'hb-lead hb-clients-lead' ), array( 'editor' => '<p>Softwaroví roboti už pomáhají týmům ve financích, účetnictví i administrativě. Tady jsou výsledky z vybraných realizací.</p>' ) ) ),
				handybot_demo_container( 'clients-logos', array_merge( handybot_demo_classes( 'hb-client-logos' ), array( 'flex_direction' => 'row', 'flex_wrap' => 'wrap', 'gap' => array( 'unit' => 'px', 'size' => 18 ) ) ), $client_logo_widgets ),
				handybot_demo_container( 'metric-grid', array_merge( handybot_demo_classes( 'hb-metric-grid' ), array( 'flex_direction' => 'row', 'gap' => array( 'unit' => 'px', 'size' => 18 ) ) ), $metric_cards ),
				handybot_demo_widget( 'clients-button', 'button', array_merge( handybot_demo_classes( 'hb-button hb-button-outline' ), array( 'text' => 'Prohlédnout více referencí →', 'link' => array( 'url' => 'https://handybot.cz/reference/', 'is_external' => 'on' ), 'align' => 'left' ) ) )
			)
		),
		handybot_demo_container(
			'services',
			array_merge( handybot_demo_classes( 'hb-section' ), array( 'content_width' => 'full', 'flex_direction' => 'column', 'html_tag' => 'section', 'css_id' => 'sluzby' ) ),
			array(
				handybot_demo_widget( 'services-kicker', 'heading', array_merge( handybot_demo_classes( 'hb-eyebrow' ), array( 'title' => '✦ S ČÍM POMÁHÁME', 'header_size' => 'div' ) ) ),
				handybot_demo_widget( 'services-title', 'heading', array_merge( handybot_demo_classes( 'hb-display-title' ), array( 'title' => 'Rutina patří robotům.<br>Lidé mají na víc.', 'header_size' => 'h2' ) ) ),
				handybot_demo_container( 'service-grid', array_merge( handybot_demo_classes( 'hb-card-grid' ), array( 'flex_direction' => 'row', 'gap' => array( 'unit' => 'px', 'size' => 22 ) ) ), $service_cards )
			)
		),
		handybot_demo_container(
			'roi',
			array_merge( handybot_demo_classes( 'hb-roi hb-section' ), array( 'content_width' => 'full', 'flex_direction' => 'row', 'gap' => array( 'unit' => 'px', 'size' => 70 ), 'align_items' => 'center', 'html_tag' => 'section', 'css_id' => 'kalkulacka' ) ),
			array(
				handybot_demo_container( 'roi-copy', array_merge( handybot_demo_classes( 'hb-roi-copy' ), array( 'width' => array( 'unit' => '%', 'size' => 40 ), 'flex_direction' => 'column' ) ), array(
					handybot_demo_widget( 'roi-kicker', 'heading', array_merge( handybot_demo_classes( 'hb-eyebrow' ), array( 'title' => '✦ ORIENTAČNÍ KALKULAČKA', 'header_size' => 'div' ) ) ),
					handybot_demo_widget( 'roi-title', 'heading', array_merge( handybot_demo_classes( 'hb-display-title' ), array( 'title' => 'Kolik času stojí vaše rutina?', 'header_size' => 'h2' ) ) ),
					handybot_demo_widget( 'roi-text', 'text-editor', array_merge( handybot_demo_classes( 'hb-lead' ), array( 'editor' => '<p>Upravte tři jednoduché údaje. Kalkulačka hned ukáže potenciál úspory, který potom ověříme na vašem konkrétním procesu.</p>' ) ) ),
				) ),
				handybot_demo_container( 'roi-panel', array_merge( handybot_demo_classes( 'hb-roi-panel' ), array( 'width' => array( 'unit' => '%', 'size' => 60 ) ) ), array(
					handybot_demo_widget( 'roi-calculator', 'html', array( 'html' => $roi_html ) )
				) )
			)
		),
		handybot_demo_container(
			'how',
			array_merge( handybot_demo_classes( 'hb-how' ), array( 'content_width' => 'full', 'flex_direction' => 'row', 'gap' => array( 'unit' => 'px', 'size' => 80 ), 'html_tag' => 'section', 'css_id' => 'jak' ) ),
			array(
				handybot_demo_container( 'how-copy', array_merge( handybot_demo_classes( 'hb-how-copy' ), array( 'width' => array( 'unit' => '%', 'size' => 45 ), 'flex_direction' => 'column' ) ), array(
					handybot_demo_widget( 'how-kicker', 'heading', array_merge( handybot_demo_classes( 'hb-eyebrow' ), array( 'title' => '✦ JEDNODUŠE, KROK ZA KROKEM', 'header_size' => 'div' ) ) ),
					handybot_demo_widget( 'how-title', 'heading', array_merge( handybot_demo_classes( 'hb-display-title' ), array( 'title' => 'Od první rutiny k robotovi v provozu.', 'header_size' => 'h2' ) ) ),
					handybot_demo_widget( 'how-text', 'text-editor', array_merge( handybot_demo_classes( 'hb-lead' ), array( 'editor' => '<p>Žádné složité IT zadání. Stačí nám ukázat, co dnes děláte ručně.</p>' ) ) ),
				) ),
				handybot_demo_container( 'how-steps', array_merge( handybot_demo_classes( 'hb-how-steps' ), array( 'width' => array( 'unit' => '%', 'size' => 55 ), 'flex_direction' => 'column' ) ), $steps )
			)
		),
		handybot_demo_container(
			'video',
			array_merge( handybot_demo_classes( 'hb-video hb-section' ), array( 'content_width' => 'full', 'flex_direction' => 'column', 'html_tag' => 'section', 'css_id' => 'rozhovor' ) ),
			array(
				handybot_demo_widget( 'video-kicker', 'heading', array_merge( handybot_demo_classes( 'hb-eyebrow' ), array( 'title' => '✦ ROZHOVOR O AUTOMATIZACI', 'header_size' => 'div' ) ) ),
				handybot_demo_widget( 'video-title', 'heading', array_merge( handybot_demo_classes( 'hb-display-title hb-video-title' ), array( 'title' => 'Co může softwarový robot změnit ve firmě?', 'header_size' => 'h2' ) ) ),
				handybot_demo_widget( 'video-text', 'text-editor', array_merge( handybot_demo_classes( 'hb-lead hb-video-lead' ), array( 'editor' => '<p>Rozhovor s Michalem Křížem jsme zvětšili na celou šířku obsahového sloupce. Pokud nemáte čas na celé video, začněte třemi tématy pod ním.</p>' ) ) ),
				handybot_demo_widget( 'video-player', 'video', array_merge( handybot_demo_classes( 'hb-video-player' ), array( 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=PMNmRrzVq_w&t=1s', 'aspect_ratio' => '169', 'lazy_load' => 'yes' ) ) ),
				handybot_demo_container( 'video-notes', array_merge( handybot_demo_classes( 'hb-video-notes' ), array( 'flex_direction' => 'row' ) ), $video_notes )
			)
		),
		handybot_demo_container(
			'quote',
			array_merge( handybot_demo_classes( 'hb-quote' ), array( 'content_width' => 'full', 'html_tag' => 'section', 'css_id' => 'pribeh' ) ),
			array(
				handybot_demo_widget( 'testimonial', 'testimonial', array( 'testimonial_content' => 'Technologie nemá lidi nahrazovat. Má jim vracet čas na práci, ve které jsou opravdu nenahraditelní.', 'testimonial_name' => 'Michal Kříž', 'testimonial_job' => 'Zakladatel HandyBot', 'testimonial_alignment' => 'center', 'testimonial_image' => array( 'url' => '', 'id' => '' ) ) )
			)
		),
		handybot_demo_container(
			'faq',
			array_merge( handybot_demo_classes( 'hb-faq hb-section' ), array( 'content_width' => 'full', 'flex_direction' => 'row', 'gap' => array( 'unit' => 'px', 'size' => 80 ), 'html_tag' => 'section', 'css_id' => 'otazky' ) ),
			array(
				handybot_demo_container( 'faq-copy', array_merge( handybot_demo_classes( 'hb-faq-copy' ), array( 'width' => array( 'unit' => '%', 'size' => 40 ), 'flex_direction' => 'column' ) ), array(
					handybot_demo_widget( 'faq-kicker', 'heading', array_merge( handybot_demo_classes( 'hb-eyebrow' ), array( 'title' => '✦ ČASTO SE PTÁTE', 'header_size' => 'div' ) ) ),
					handybot_demo_widget( 'faq-title', 'heading', array_merge( handybot_demo_classes( 'hb-display-title' ), array( 'title' => 'Možná vás napadlo…', 'header_size' => 'h2' ) ) ),
				) ),
				handybot_demo_container( 'faq-panel', array_merge( handybot_demo_classes( 'hb-faq-list' ), array( 'width' => array( 'unit' => '%', 'size' => 60 ), 'flex_direction' => 'column' ) ), array(
					handybot_demo_widget(
						'faq-accordion',
						'accordion',
						array(
							'tabs'           => $faq_items,
							'title_html_tag' => 'h3',
							'faq_schema'     => 'yes',
							// An empty icon keeps the accordion free of a Font Awesome
							// dependency; the child theme draws the +/- marks instead.
							'selected_icon'        => array( 'value' => '', 'library' => '' ),
							'selected_active_icon' => array( 'value' => '', 'library' => '' ),
						)
					)
				) )
			)
		),
		handybot_demo_container(
			'contact',
			array_merge( handybot_demo_classes( 'hb-contact' ), array( 'content_width' => 'full', 'flex_direction' => 'row', 'justify_content' => 'space-between', 'align_items' => 'end', 'html_tag' => 'section', 'css_id' => 'kontakt' ) ),
			array(
				handybot_demo_container( 'contact-copy', array( 'width' => array( 'unit' => '%', 'size' => 70 ) ), array(
					handybot_demo_widget( 'contact-kicker', 'heading', array_merge( handybot_demo_classes( 'hb-eyebrow' ), array( 'title' => '✦ ZAČNĚME MALÝM KROKEM', 'header_size' => 'div' ) ) ),
					handybot_demo_widget( 'contact-title', 'heading', array_merge( handybot_demo_classes( 'hb-display-title' ), array( 'title' => 'Co by mohl robot dělat právě za vás?', 'header_size' => 'h2' ) ) ),
				) ),
				handybot_demo_widget( 'contact-button', 'button', array_merge( handybot_demo_classes( 'hb-button' ), array( 'text' => 'Napište nám ↗', 'link' => array( 'url' => 'mailto:info@handybot.cz' ) ) ) )
			)
		),
	);
}

/**
 * Register a bundled demo asset in the Media Library so an editor can replace it.
 *
 * @param string $relative_path Path relative to the child-theme assets folder.
 * @param string $title         Media title.
 * @param string $alt           Alternative text.
 */
function handybot_demo_asset_attachment( string $relative_path, string $title, string $alt ): int {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_handybot_demo_asset',
			'meta_value'     => $relative_path,
		)
	);
	$source = trailingslashit( get_stylesheet_directory() ) . 'assets/' . ltrim( $relative_path, '/' );
	if ( ! is_readable( $source ) ) {
		return ! empty( $existing ) ? (int) $existing[0] : 0;
	}

	// An existing attachment is reused, but its file is refreshed whenever the
	// bundled source changed. Without this, replacing an image in the theme
	// assets folder would leave the site serving the previously imported copy.
	if ( ! empty( $existing ) ) {
		$existing_id   = (int) $existing[0];
		$existing_file = get_attached_file( $existing_id );

		if ( $existing_file && ( ! file_exists( $existing_file ) || hash_file( 'sha1', $source ) !== hash_file( 'sha1', $existing_file ) ) ) {
			$contents = file_get_contents( $source );
			if ( false !== $contents && false !== file_put_contents( $existing_file, $contents ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				$metadata = wp_generate_attachment_metadata( $existing_id, $existing_file );
				if ( ! is_wp_error( $metadata ) ) {
					wp_update_attachment_metadata( $existing_id, $metadata );
				}
			}
		}

		return $existing_id;
	}

	$uploads = wp_upload_dir();
	if ( ! empty( $uploads['error'] ) ) {
		return 0;
	}

	$directory = trailingslashit( $uploads['basedir'] ) . 'handybot-demo';
	if ( ! wp_mkdir_p( $directory ) ) {
		return 0;
	}

	$filename    = sanitize_file_name( wp_basename( $relative_path ) );
	$destination = trailingslashit( $directory ) . $filename;
	$contents = file_get_contents( $source );
	if ( false === $contents || false === file_put_contents( $destination, $contents ) ) {
		return 0;
	}

	$filetype      = wp_check_filetype( $filename, null );
	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'] ?? 'image/png',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$destination
	);
	if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return 0;
	}

	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $attachment_id, '_handybot_demo_asset', $relative_path );

	require_once ABSPATH . 'wp-admin/includes/image.php';
	$metadata = wp_generate_attachment_metadata( $attachment_id, $destination );
	if ( ! is_wp_error( $metadata ) ) {
		wp_update_attachment_metadata( $attachment_id, $metadata );
	}

	return (int) $attachment_id;
}

/**
 * Create or refresh the demo page once per plugin version.
 */
function handybot_demo_install(): void {
	$page = get_page_by_path( 'handybot-demo', OBJECT, 'page' );
	$id   = $page instanceof WP_Post ? $page->ID : 0;

	$post_data = array(
		'ID'           => $id,
		'post_title'   => 'HandyBot demo',
		'post_name'    => 'handybot-demo',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '',
	);

	$page_id = $id ? wp_update_post( $post_data ) : wp_insert_post( $post_data );
	if ( is_wp_error( $page_id ) || ! $page_id ) {
		return;
	}

	$asset_ids = array(
		'hero'            => handybot_demo_asset_attachment( 'handybot-software-robot-hero.png', 'HandyBot – softwarový robot', 'Softwarový robot HandyBot upravuje digitální rozhraní a zpracovává firemní dokumenty.' ),
		'accace'          => handybot_demo_asset_attachment( 'clients/accace.webp', 'Logo Accace', 'Accace' ),
		'microsoft'       => handybot_demo_asset_attachment( 'clients/microsoft.webp', 'Logo Microsoft', 'Microsoft' ),
		'grant_thornton'  => handybot_demo_asset_attachment( 'clients/grant-thornton.webp', 'Logo Grant Thornton', 'Grant Thornton' ),
		'drmax'           => handybot_demo_asset_attachment( 'clients/drmax.png', 'Logo Dr. Max', 'Dr. Max' ),
		'sazka'           => handybot_demo_asset_attachment( 'clients/sazka.png', 'Logo Sazka', 'Sazka' ),
	);

	update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
	update_post_meta( $page_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
	update_post_meta( $page_id, '_elementor_page_settings', array( 'hide_title' => 'yes' ) );
	update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( handybot_demo_data( $asset_ids ) ) ) );

	// The demo writes Elementor data directly instead of going through the editor,
	// so the caches the editor would normally invalidate have to be dropped here.
	delete_post_meta( $page_id, '_elementor_element_cache' );
	delete_post_meta( $page_id, '_elementor_page_assets' );
	delete_post_meta( $page_id, '_elementor_css' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', (int) $page_id );
	update_option( 'blogname', 'HandyBot Demo' );
	update_option( 'blogdescription', 'Méně rutiny. Více práce, která má smysl.' );
	update_option( 'elementor_css_print_method', 'internal' );
	update_option( 'elementor_onboarded', true );
	delete_transient( 'elementor_activation_redirect' );
	update_option( 'handybot_demo_page_id', (int) $page_id );
	update_option( 'handybot_demo_version', HANDBYBOT_DEMO_VERSION );

	if ( did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}

register_activation_hook( __FILE__, 'handybot_demo_install' );

add_action(
	'admin_init',
	static function (): void {
		$page_id = (int) get_option( 'handybot_demo_page_id' );
		$missing = ! $page_id || 'page' !== get_post_type( $page_id );

		// Bumping the plugin version is what republishes the demo content, so a
		// change to handybot_demo_data() reaches the page on the next admin load.
		if ( $missing || get_option( 'handybot_demo_version' ) !== HANDBYBOT_DEMO_VERSION ) {
			handybot_demo_install();
		}
	}
);

add_action(
	'admin_notices',
	static function (): void {
		$page_id = (int) get_option( 'handybot_demo_page_id' );
		if ( ! $page_id || ! current_user_can( 'edit_post', $page_id ) ) {
			return;
		}
		?>
		<div class="notice notice-success">
			<p>
				<strong>HandyBot demo je připravené.</strong>
				<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $page_id . '&action=elementor' ) ); ?>">Otevřít v Elementoru</a>
				· <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Zobrazit web</a>
			</p>
		</div>
		<?php
	}
);
