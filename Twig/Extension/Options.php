<?php
namespace iVariable\ExtraBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

class Options extends \Twig_Extension {

	protected $container;

    public function __construct( $container )
    {
		$this->container = $container;
    }

	public function getGlobals(){

		return array(
			'options'		=> $this
		);
	}

	public function getFilters(){
		return array(
            'truncate' => new \Twig_Filter_Method($this, 'modTruncate'),
            'number' => new \Twig_Filter_Method($this, 'modNumber'),
            'split' => new \Twig_Filter_Method($this, 'modSplit'),
            'uri' => new \Twig_Filter_Method($this, 'modUri'),
            'startsWith' => new \Twig_Filter_Method($this, 'modStartsWith'),
            'endsWith' => new \Twig_Filter_Method($this, 'modEndsWith'),
            'contains' => new \Twig_Filter_Method($this, 'modContains'),
            'pad' => new \Twig_Filter_Function('str_pad'),
            'jsonEncode' => new \Twig_Filter_Function('json_encode'),
            'jsonDecode' => new \Twig_Filter_Function('json_decode'),
            'round' => new \Twig_Filter_Function('round'),
            'trim' => new \Twig_Filter_Function('trim'),
            'ltrim' => new \Twig_Filter_Function('ltrim'),
            'rtrim' => new \Twig_Filter_Function('rtrim'),
            'substring' => new \Twig_Filter_Function('substr'),
            'dump' => new \Twig_Filter_Function('var_dump'),
            'shift' => new \Twig_Filter_Function('array_shift'),
            'pop' => new \Twig_Filter_Function('array_pop'),
            'count' => new \Twig_Filter_Function('count'),
        );
	}

	/**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'options';
    }

	public function getServer($key){
		return $_SERVER[$key];
	}

	public function container(){
		return $this->container;
	}

	public function __call( $name, $args ){
		return $this->container->getParameter($name);
	}

	public function modTruncate($str, $len, $suffix = null) {
        if (mb_strlen($str) > $len) {
            $str = mb_substr($str, 0, $len);
            if (!is_null($suffix)) {
                $str .= $suffix;
            }
        }
        return $str;
    }

    public function modNumber($number, $decimals = 2, $decPoint = ',', $thousandsSep = '.', $collapseIntegers = false) {

        if (is_null($decPoint)) {
            $decPoint = ',';
        }

        if (is_null($thousandsSep)) {
            $thousandsSep = '.';
        }

        if ($collapseIntegers && ((int) $number == $number)) {
            $decimals = 0;
        }
        return number_format($number, $decimals, $decPoint, $thousandsSep);
    }

    /**
     * Creates a clean uri. Basic usage:
     * 'foo'|uri() => /foo/
     * 'foo'|uri('pref', {'foo' : 'bar'}, 'top') => /pref/foo/?foo=bar#top
     *
     * Both key and params accept arrays:
     * ['foo', 'bar']|uri() => /foo/bar/
     * 'foo'|uri(['pref', 'foobar']) => /pref/foobar/foo/

     * @param string|array $key
     * @param string|array $prefix
     * @param array        $params
     * @param string       $hash
     */
    public function modUri($key, $prefix = '', $params = array(), $hash = null) {

        $isLink = false;

        // concatenate if array is supplied
        if (is_array($key)) $key = implode('/', $key);
        if (is_array($prefix)) $prefix = implode('/', $prefix);

        if ($isLink || $this->isAbsoluteUrl($key)) {

            $uri = $key;

        } else {

            // use the prefix
            $uri = $prefix . '/' . $key;

            if (substr($uri, -1 != '/') && (strpos($key, '.') === false)) {
                $uri .= '/';
            }

            // make sure uri starts with a slash
            if (substr($uri, 0, 1) != '/') $uri = '/' . $uri;

            // remove 2 or more consecutive slashes
            $uri = preg_replace('/\/{2,}/', '/', $uri);
        }

        $uri = trim($uri);

        if (!empty($params)) {
            $uri .= ((strpos($uri, '?') === false) ? '?' : '&') . http_build_query($params);
        }

        if (!is_null($hash)) {
            $uri .= '#' . $hash;
        }

        return $uri;
    }

    public function isAbsoluteUrl($url) {
        return preg_match('/^[a-zA-Z]+\:.+/', $url);
    }

    public function modSplit($str, $delimiter) {
        return explode($delimiter, $str);
    }

    public function modStartsWith($str, $cmp) {
        return (substr($str, 0, mb_strlen($cmp)) == $cmp);
    }

    public function modEndsWith($str, $cmp) {
        return (substr($str, -mb_strlen($cmp)) == $cmp);
    }

    public function modContains($str, $cmp) {
        return (stristr($str, $cmp) !== false);
    }
}