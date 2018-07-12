<?php

namespace Firststep\Common\Setup;

class Setup {

	public $appNameForPageTitle;
	public $privateTemplateFileName;
	public $publicTemplateFileName;
	public $basePath;
    public $pathtoapp;
	public $yamlpath;

	public function __construct() {
		$this->appNameForPageTitle = '';
		$this->privateTemplateFileName = '';
		$this->publicTemplateFileName = '';
		$this->basePath = '';
        $this->pathtoapp = '';
		$this->yamlpath = '';
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
    public function getYamlPath(): string {
        return $this->yamlpath;
    }

    /**
     * @param string $yamlpath
     */
    public function setYamlPath(string $yamlpath) {
        $this->yamlpath = $yamlpath;
    }

}
