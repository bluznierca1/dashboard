<?php

namespace Dashboard\Request;

use Dashboard\Helpers\RedirectHelper;
use Dashboard\Router\Router;
use Dashboard\Helpers\VariableHelper;

class Request {

    private $requestUri; // original request uri
    private $requestUriParameters = ''; // request parameters (GET)
    private $requestUriFilepath = ''; // filename (with extension)
    private $requestUriFilename = ''; // filename (without extension)
    private $requestUriPreparedForRouter = '';
    private $postRequestData = [];

    public function __construct( string $requestURI = '' ) {

        // replace 'dashboard' that is in URL
        $uriWithReplacedPrefix = str_replace('/' . URL_PREFIX . '/', '', $requestURI);
        $this->requestUri = !empty($uriWithReplacedPrefix) ? $uriWithReplacedPrefix : 'index.php'; // if nothing provided, use index.php

        if( $this->validateUri() ) {

            $this->buildRequestData();

            $this->triggerController();

        } else {
            $this->returnHomePage();
        }

    }

    // Getters are for controller.
    // No setters as in here, use attributes straight away and nothing will be manipulated from Controller
    public function getRequestUri(): string {
        return $this->requestUri;
    }

    public function getRequestUriParameters(): string {
        return $this->requestUriParameters;
    }

    public function getRequestUriFilepath(): string {
        return $this->requestUriFilepath;
    }

    public function getRequestUriFilename(): string {
        return $this->requestUriFilepath;
    }

    public function getRequestUriPreparedForRouter(): string {
        return $this->requestUriFilepath;
    }

    public function getPostRequestData(): array {
        return $this->postRequestData;
    }

    private function returnHomePage(): void {
        RedirectHelper::redirectToHomePage();
    }

    /**
     * Prepare date for controller and trigger it
     */
    private function triggerController(): void {

        $routerData = Router::getRegisteredRoutesWithControllers()[$this->requestUriPreparedForRouter];

        $controllerName = $routerData['controller'];
        $controllerNamespace = "\Dashboard\\Controllers\\" . $controllerName;

        // class needs to exist or it would not reach that point
        new $controllerNamespace($this, $routerData['method']);

    }

    /**
     * Check if URL provided and registered
     * @return bool
     */
    private function validateUri(): bool {

        $base = $this->explodeRequestUri()['base'];

        // check if path is registered
        return !(
            empty($base) ||
            !array_key_exists( VariableHelper::removePhpExtensionFromString(VariableHelper::prepareRequestUriPathForRouter($base) ), Router::getRegisteredRoutesWithControllers())
        );

    }

    /**
     * $params -> string of parameters (until first '?')
     * $base -> array of parts of path
     *
     * @return array
     */
    private function explodeRequestUri(): array {
        $params = '';

        // If '?' provided, get URL params
        if( strpos($this->requestUri, '?') !== false ) {

            // divide URL in 2 parts - base and params
            [$base, $params] = explode('?', $this->requestUri);

            $base = explode('/', $base);

        } else {
            $base = explode('/', $this->requestUri);
        }

        // if last character of url is '/', remove it
        if( empty( end($base) ) ) {
            array_pop($base);
        }

        return [
            'parameters' => $params,
            'base' => $base
        ];

    }

    /**
     * Set class attributes for easier access
     */
    private function buildRequestData(): void {

        $explodedUri = $this->explodeRequestUri();

        $this->requestUriParameters = $explodedUri['parameters'];
        $this->requestUriFilepath = $explodedUri['base'][0];
        $this->requestUriFilename = VariableHelper::removePhpExtensionFromString( $this->requestUriFilepath );
        $this->requestUriPreparedForRouter = VariableHelper::prepareRequestUriPathForRouter($explodedUri['base']);

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) { // set post data. validation will be handled in proper methods
            $this->postRequestData = $_POST;
        }

    }

}