<?php

namespace Netzmacht\Contao\FormHelper\Element;

use Netzmacht\Html\Element;
use Netzmacht\Contao\FormHelper\HasTemplate;

abstract class Options extends Element\Node implements HasTemplate
{

    /**
     * @var string
     */
    const CONTAINER_TAG = 'div';

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var string
     */
    protected $template;

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options  = (array) $options;
        $this->children = array();
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        if (!$value) {
            $value = array();
        }
        elseif (!is_array($value)) {
            $value = array($value);
        }

        $this->value = $value;
    }

    /**
     * @param bool $default
     * @return mixed
     */
    public function getValue($default=false)
    {
        if($default && empty($this->value)) {
            $value = array();

            foreach($this->options as $option) {
                if ($option['default']) {
                    $value[] = $option['value'];
                }
            }

            return $value;
        }

        return $this->value;
    }

    /**
     * @return string|void
     */
    public function generate()
    {
        $template             = new \FrontendTemplate($this->template);
        $template->options    = $this->options;
        $template->element    = $this;
        $template->tag        = $this->getTag();

        return $template->parse();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setTemplateName($name)
    {
        $this->template = $name;

        return $this->template;
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->template;
    }

}
