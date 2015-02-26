<?php
class SC_Controller {
    private $base_path = '';
    private $views_folder = '';

    public function __construct( $base_path = '', $views_folder = '' ) {
        $this->base_path = $base_path;
        $this->views_folder = $views_folder;
    }

    public function render( $view, $params = array() ) {
        extract($params);
        include( $this->get_path( $view ) );
    }

    protected function get_path( $view ) {
        return trailingslashit( $this->base_path ) . 'includes/views/' . $this->views_folder . '/' . $view . '.php';
    }
}