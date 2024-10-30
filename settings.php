<?php
use Elementor\Controls_Manager;


/**
 * Load CSS and Javascript
 */
function movsw_load_assets() {
    wp_enqueue_style( 'movsw-swiper', plugins_url( '/assets/css/swiper.min.css', __FILE__ ), [], '5.4.3' );
    wp_enqueue_style( 'movsw-styles', plugins_url( '/assets/css/styles.css', __FILE__ ), [], '1.0' );
    wp_enqueue_script( 'movsw-swiper', plugins_url( '/assets/js/swiper.min.js', __FILE__ ), [ 'jquery' ], '5.4.3', true );
    wp_enqueue_script( 'movsw-init', plugins_url( '/assets/js/init.min.js', __FILE__ ), [], '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'movsw_load_assets' );

/**
 * The following functions add settings to the Elelentor columns.
 * Users can adjust settings to make widgets inside a column scrollable
 *
 */

/**
 * Registers the general controls for the Elementor column
 */
function movsw_controls_general( $element ) {

    // Heading at the start of the control section
    $element->add_control(
        'mov_col_heading',
        [
            'label' => __( 'Widget Scroll Settings', 'movsw' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before'
        ]
    );

    // Allows to enable scrolling for widgets inside columns
    $element->add_control(
        'mov_col_layout',
        [
            'label'        => __( 'Make Widgets Scrollable', 'movsw' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'scroll',
            'prefix_class' => 'mov-col-'
        ]
    );

    // Respnsive setting to define the visible number of slides
    $element->add_responsive_control(
        'mov_slides_view',
        [
            'label'           => __( 'Widget Slides per View', 'movsw' ),
            'type'            => Controls_Manager::NUMBER,
            'condition'       => [ 'mov_col_layout' => 'scroll' ],
            'min'             => 1,
            'max'             => 10,
            'desktop_default' => 4,
            'tablet_default'  => 3,
            'mobile_default'  => 2,
            'prefix_class'    => 'mov-sl-view-%s-'
        ]
    );

    // Respnsive setting to define the scrollable number of slides
    $element->add_responsive_control(
        'mov_slides_scroll',
        [
            'label'           => __( 'Widget Slides per Scroll', 'movsw' ),
            'type'            => Controls_Manager::NUMBER,
            'condition'       => [ 'mov_col_layout' => 'scroll' ],
            'min'             => 1,
            'max'             => 10,
            'desktop_default' => 4,
            'tablet_default'  => 3,
            'mobile_default'  => 2,
            'prefix_class'    => 'mov-sl-scroll-%s-'
        ]
    );
}
add_action( 'elementor/element/column/layout/before_section_end', 'movsw_controls_general', 0 );


/**
 * Opens the tabs section for arrow and progress bar controls
 */
function movsw_controls_tabs_start( $element ) {
    $element->start_controls_tabs(
        'mov_col_arrow_tabs'
    );
}
add_action( 'elementor/element/column/layout/before_section_end', 'movsw_controls_tabs_start', 1 );


/**
 * Registers the controls the Elementor column to adjust arrow settings
 */
function movsw_controls_arrows( $element ) {

    // Opens the tab for the arrow controls
    $element->start_controls_tab(
        'mov_arrows_tab',
        [
            'label'     => __( 'Arrows', 'movsw' ),
            'condition' => [ 'mov_col_layout' => 'scroll' ]
        ]
    );

    // Enable/disable arrows
    $element->add_control(
        'mov_arrows',
        [
            'label'        => __( 'Show/Hide Arrows', 'movsw' ),
            'type'         => Controls_Manager::CHOOSE,
            'options'      => [
                'hide' => [ 'title' => __( 'Hide', 'movsw' ), 'icon' => 'far fa-eye-slash' ],
                'show' => [ 'title' => __( 'Show', 'movsw' ), 'icon' => 'far fa-eye' ]
            ],
            'default' => 'hide',
            'toggle'       => false,
            'condition'    => [ 'mov_col_layout' => 'scroll' ],
            'prefix_class' => 'mov-arrows-'
        ]
    );

    // Define the arrows color
    $element->add_control(
        'mov_arrows_color',
        [
            'label'     => __( 'Arrows Color', 'movsw' ),
            'type'      => Controls_Manager::COLOR,
            'condition' => [ 'mov_col_layout' => 'scroll', 'mov_arrows' => 'show' ],
            'default'   => '#007aff',
            'selectors' => [
                '{{WRAPPER}} .swiper-arrow::before' => 'color: {{VALUE}}'
            ]
        ]
    );

    // Closes the tab for the arrow controls
    $element->end_controls_tab();
}
add_action( 'elementor/element/column/layout/before_section_end', 'movsw_controls_arrows', 2 );


/**
 * Registers the controls the Elementor column to adjust progress bar settings
 */
function movsw_controls_pagination( $element ) {

    // Opens the tab for the progress bar controls
    $element->start_controls_tab(
        'mov_pagination_tab',
        [
            'label'     => __( 'Progress Bar', 'movsw' ),
            'condition' => [ 'mov_col_layout' => 'scroll' ]
        ]
    );

    // Enable/disable the progress bar
    $element->add_control(
        'mov_pagination',
        [
            'label'        => __( 'Show/Hide Progress Bar', 'movsw' ),
            'type'         => Controls_Manager::CHOOSE,
            'options'      => [
                'hide' => [ 'title' => __( 'Hide', 'movsw' ), 'icon' => 'far fa-eye-slash' ],
                'show' => [ 'title' => __( 'Show', 'movsw' ), 'icon' => 'far fa-eye' ]
            ],
            'default' => 'hide',
            'toggle'       => false,
            'condition'    => [ 'mov_col_layout' => 'scroll' ],
            'prefix_class' => 'mov-pagination-'
        ]
    );

    // Define the progress bar background color
    $element->add_control(
        'mov_progress_bg',
        [
            'label'     => __( 'Background Color', 'movsw' ),
            'type'      => Controls_Manager::COLOR,
            'condition' => [ 'mov_col_layout' => 'scroll', 'mov_pagination' => 'show' ],
            'default'   => '#cccccc',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}}',
            ]
        ]
    );

    // Define the progress bar color
    $element->add_control(
        'mov_progress_color',
        [
            'label'     => __( 'Color', 'movsw' ),
            'type'      => Controls_Manager::COLOR,
            'condition' => [ 'mov_col_layout' => 'scroll', 'mov_pagination' => 'show' ],
            'default'   => '#007aff',
            'selectors' => [
                '{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
            ]
        ]
    );

    // Closes the tab for the progress bar controls
    $element->end_controls_tab();
}
add_action( 'elementor/element/column/layout/before_section_end', 'movsw_controls_pagination', 3 );


/**
 * Closes the tabs section for arrow and progress bar controls
 */
function movsw_controls_tabs_end( $element ) {
    $element->end_controls_tabs();
}
add_action( 'elementor/element/column/layout/before_section_end', 'movsw_controls_tabs_end', 4 );


/**
 * Adds info about the Pro version and adds the Buy button
 */
function movsw_controls_pro( $element ) {
    $element->add_control(
        'mov_pro_info',
        [
            'label'      =>   __( 'Pro Information', 'movsw' ),
            'show_label' => false,
            'type'       => Controls_Manager::RAW_HTML,
            'separator'  => 'before',
            'raw'        => sprintf(
                '<h2 style="font-weight: bold;">%1$s</h2>
                <ul style="margin: 10px 0 10px 20px; line-height: 1.2;">
                    <li style="list-style: disc;">%2$s</li>
                    <li style="list-style: disc;">%3$s</li>
                    <li style="list-style: disc;">%4$s</li>
                </ul>
                <button type="button" class="elementor-button elementor-button-success" style="width: 200px; margin: auto; display: block;">
                    <a href="https://movelize.com#movsw-upgrade" target="_blank" style="display:block; padding: 5px 10px; color: #fff;">%5$s</a>
                </button>',
                __( 'Movelize Sccrolling Widgets Pro Features', 'movsw' ),
                __( 'All settings of the free version can be adjusted responsively', 'movsw' ),
                __( 'Define arrow positioning, size, background color, padding and margin', 'movsw' ),
                __( 'Define progress bar positioning and height', 'movsw' ),
                __( 'Buy now', 'movsw' )
            )
        ]
    );
}
add_action( 'elementor/element/column/layout/before_section_end', 'movsw_controls_pro', 5 );
?>