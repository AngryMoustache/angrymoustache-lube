<?php

namespace Lube\Views;

use Lube\LubeObject;

/**
 * View object that controls rendering of pages
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/03/2019
 */
class View extends LubeObject
{
    /** @var string */
    protected $path;

    /** @var array */
    protected $variables;

    /** @var string */
    protected $base;

    /** @var boolean */
    protected $folder;

    /**
     * Construct the view
     *
     * @param string $path      The path to the view file
     * @param array  $variables Variables to pass to the view
     *
     * @return void
     */
    public function __construct(
        $path,
        $variables = [],
        $base = 'assets.views.bases.default',
        $folder = true
    ) {
        $this->path = $path;
        $this->variables = $variables;
        $this->base = $base;
        $this->folder = $folder;
    }

    /**
     * Render the base view
     *
     * @return void
     */
    public function render()
    {
        $this->include($this->base);
    }

    /**
     * Render the content inside the view
     *
     * @param string $path      Path to the file to load
     * @param array  $variables Variables to be available in the included file
     *
     * @return void
     */
    public function content()
    {
        $path = $this->path($this->path, $this->folder);
        $this->include($path, $this->variables);
    }

    /**
     * Render an element inside the view
     *
     * @return void
     */
    public function element($path, $variables = [])
    {
        $path = $this->path($path);
        $this->include($path, $variables);
    }
}
