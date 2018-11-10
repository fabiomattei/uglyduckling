<?php

namespace Firststep\Common\Setup;

class Setup {

	public $appNameForPageTitle;
	public $privateTemplateFileName;
    public $privateTemplateWithSidebarFileName;
	public $publicTemplateFileName;
	public $emptyTemplateFileName;
	public $basePath;
    public $pathtoapp;
	public $jsonPath;

	public function __construct() {
		$this->appNameForPageTitle = '';
		$this->privateTemplateFileName = '';
		$this->privateTemplateWithSidebarFileName = '';
		$this->publicTemplateFileName = '';
		$this->basePath = '';
        $this->pathtoapp = '';
		$this->jsonPath = '';
    }

    /**
     * @return string
     */


    public function getAppNameForPageTitle(): string {
        return $this->appNameForPageTitle;
    }

    /**
     * @param string $appNameForPageTitle
     */
    public function setAppNameForPageTitle(string $appNameForPageTitle) {
        $this->appNameForPageTitle = $appNameForPageTitle;
    }

    /**
     * @return string
     */
    public function getPrivateTemplateFileName(): string {
        return $this->privateTemplateFileName;
    }

    /**
     * @param string $privateTemplateFileName
     */
    public function setPrivateTemplateFileName(string $privateTemplateFileName) {
        $this->privateTemplateFileName = $privateTemplateFileName;
    }

    /**
     * @return string
     */
    public function getPrivateTemplateWithSidebarFileName(): string {
        return $this->privateTemplateWithSidebarFileName;
    }

    /**
     * @param string $privateTemplateWithSidebarFileName
     */
    public function setPrivateTemplateWithSidebarFileName(string $privateTemplateWithSidebarFileName) {
        $this->privateTemplateWithSidebarFileName = $privateTemplateWithSidebarFileName;
    }

    /**
     * @return string
     */
    public function getPublicTemplateFileName(): string {
        return $this->publicTemplateFileName;
    }

    /**
     * @param string $publicTemplateFileName
     */
    public function setPublicTemplateFileName(string $publicTemplateFileName) {
        $this->publicTemplateFileName = $publicTemplateFileName;
    }

    /**
     * @return string
     */
    public function getEmptyTemplateFileName(): string {
        return $this->emptyTemplateFileName;
    }

    /**
     * @param string $privateTemplateFileName
     */
    public function setEmptyTemplateFileName(string $emptyTemplateFileName) {
        $this->emptyTemplateFileName = $emptyTemplateFileName;
    }

    /**
     * @return string
     */
    public function getBasePath(): string {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath(string $basePath) {
        $this->basePath = $basePath;
    }

    /**
     * @return string
     */
    public function getPathToApp(): string {
        return $this->pathtoapp;
    }

    /**
     * @param string $basePath
     */
    public function setPathToApp(string $pathtoapp) {
        $this->pathtoapp = $pathtoapp;
    }
	
    /**
     * @return string
     */
    public function getJsonPath(): string {
        return $this->jsonPath;
    }

    /**
     * @param string $yamlpath
     */
    public function setJsonPath(string $jsonPath) {
        $this->jsonPath = $jsonPath;
    }

}
