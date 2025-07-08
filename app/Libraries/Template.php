<?php

namespace App\Libraries;

class Template 
{
    protected $template_data = [];
    protected $template_name = 'main';
    
    public function __construct()
    {
        // Initialize template data
        $this->template_data = [];
    }
    
    /**
     * Set template data
     */
    public function set($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->template_data[$key] = $val;
            }
        } else {
            $this->template_data[$name] = $value;
        }
        return $this;
    }
    
    /**
     * Set template name
     */
    public function template($template_name)
    {
        $this->template_name = $template_name;
        return $this;
    }
    
    /**
     * Load and display template
     */
    public function member($view = null, $data = [], $return_output = false)
    {
        // Merge template data with view data
        $template_data = array_merge($this->template_data, $data);
        
        // Set the main content
        if ($view) {
            $template_data['content'] = view($view, $template_data);
        }
        
        // Set default values
        $template_data['title'] = $template_data['title'] ?? 'Project Management System';
        $template_data['page_title'] = $template_data['page_title'] ?? '';
        $template_data['breadcrumbs'] = $template_data['breadcrumbs'] ?? [];
        $template_data['user'] = $template_data['user'] ?? session()->get();
        $template_data['current_route'] = $template_data['current_route'] ?? service('router')->getMatchedRoute();
        
        // Load and return template
        $output = view('templates/' . $this->template_name, $template_data);
        
        // Always return the output - let CodeIgniter handle the response
        return $output;
    }
    
    /**
     * Render partial view
     */
    public function partial($view, $data = [])
    {
        return view($view, array_merge($this->template_data, $data));
    }
    
    /**
     * Add CSS file
     */
    public function add_css($css_file)
    {
        if (!isset($this->template_data['css_files'])) {
            $this->template_data['css_files'] = [];
        }
        $this->template_data['css_files'][] = $css_file;
        return $this;
    }
    
    /**
     * Add JS file
     */
    public function add_js($js_file)
    {
        if (!isset($this->template_data['js_files'])) {
            $this->template_data['js_files'] = [];
        }
        $this->template_data['js_files'][] = $js_file;
        return $this;
    }
    
    /**
     * Add inline CSS
     */
    public function add_inline_css($css)
    {
        if (!isset($this->template_data['inline_css'])) {
            $this->template_data['inline_css'] = '';
        }
        $this->template_data['inline_css'] .= $css;
        return $this;
    }
    
    /**
     * Add inline JS
     */
    public function add_inline_js($js)
    {
        if (!isset($this->template_data['inline_js'])) {
            $this->template_data['inline_js'] = '';
        }
        $this->template_data['inline_js'] .= $js;
        return $this;
    }
    
    /**
     * Convenience method for auth template
     */
    public function auth($view = null, $data = [], $return_output = false)
    {
        $this->template_name = 'auth';
        return $this->member($view, $data, $return_output);
    }
}
