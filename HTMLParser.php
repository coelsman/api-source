<?php
class HTMLParser {
	public $singletonTags = array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source');
	public $html = '';

	public function __construct () {
		// $this->dom = new DOMDocument();
	}

	public function element ($name, $htmlAttributes = array(), $isChild = false) {
		$html = '<'.$name;

		if (!empty($htmlAttributes)) {
			foreach ($htmlAttributes as $key => $value) {
				$html .= ' '.$key.'="'.$value.'"';
			}
		}

		if (in_array($name, $this->singletonTags)) {
			$html .= ' />';
		} else {
			$html .= '>{{content}}</'.$name.'>';
		}

		if ($isChild) {
			$this->html = str_replace('{{content}}', $html, $this->html);
		} else {
			$this->html .= $html;
		}
		return $this;
	}

	public function child ($name, $htmlAttributes = array()) {
		return $this->element($name, $htmlAttributes, true);
	}

	public function done () {
		$this->html = str_replace('{{content}}', '', $this->html);
		print $this->html;
	}
}

$html = new HTMLParser();
$html
->element('div', array('class' => 'col-md-9', 'id' => 'container', 'style' => 'width:100px; height:200px;'))
	->child('div', array('class' => 'form-group'))
		->child('input', array('type' => 'text', 'class' => 'form-control', 'onchange' => 'alert(5);'))
->element('div', array('class' => 'col-md-9', 'id' => 'container', 'style' => 'width:100px; height:200px;'))
->done();