<?php

namespace DigitalRespawn\BreadcrumbBundle\Breadcrumb;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class RequestParamConverter
{
	protected $router;
	protected $paramConverterManager;
	protected $resolver;
	protected $matcher;

	public function __construct(RouterInterface $router, ParamConverterManager $paramConverterManager, ControllerResolverInterface $resolver)
	{
		$this->router = $router;
		$this->paramConverterManager = $paramConverterManager;
		$this->resolver = $resolver;
		$this->matcher = new UrlMatcher($this->router->getRouteCollection(), new RequestContext());
	}

	/**
	 * Create a request with converted parameters
	 *
	 * @param Route $route : Route to create the request from
	 * @param array $params : Array of params to bind
	 *
	 * @return Request : Converted request
	 */
	public function getConvertedRequestFromRoute(Route $route, array $params)
	{
		$request = Request::create($route->getPath());
		$request->attributes->add($this->matcher->match($this->bindPathParams($request->getPathInfo(), $params)));
		$controller = $this->resolver->getController($request);

		return $this->getRequestWithConvertedParams($controller, $request);
	}

	/**
	 * Bind path params with values
	 *
	 * @param string $pathInfo : Path to bind parameters with
	 * @param array $params : Array of parameters to bind
	 *
	 * @return string : Path with keys replaced by values
	 */
	protected function bindPathParams($path, array $params)
	{
		return preg_replace_callback('/\{(.+)\}/', function ($m) use ($params) {
			return $params[$m[1]];
		}, $path);
	}

	/**
	 * Get the request with parameters converted depending on ParamConverters
	 *
	 * @param $controller : Controller called
	 * @param Request $request : Request to process
	 *
	 * @return Request : Request with ParamConverters processed
	 */
	protected function getRequestWithConvertedParams($controller, Request $request)
	{
		$configurations = array();
		if ($configuration = $request->attributes->get('_converters')) {
			foreach (is_array($configuration) ? $configuration : array($configuration) as $configuration) {
				$configurations[$configuration->getName()] = $configuration;
			}
		}
		if (is_array($controller)) {
			$r = new \ReflectionMethod($controller[0], $controller[1]);
		} elseif (is_object($controller) && is_callable($controller, '__invoke')) {
			$r = new \ReflectionMethod($controller, '__invoke');
		} else {
			$r = new \ReflectionFunction($controller);
		}

		$configurations = $this->autoConfigure($r, $request, $configurations);
		$this->paramConverterManager->apply($request, $configurations);

		return $request;
	}

	/**
	 * Get configuration for each request's parameter
	 *
	 * @param \ReflectionFunctionAbstract $r
	 * @param Request $request
	 * @param $configurations
	 *
	 * @return mixed
	 */
	protected function autoConfigure(\ReflectionFunctionAbstract $r, Request $request, $configurations)
	{
		foreach ($r->getParameters() as $param) {
			if (!$param->getClass() || $param->getClass()->isInstance($request)) {
				continue;
			}

			$name = $param->getName();
			if (!isset($configurations[$name])) {
				$configuration = new ParamConverter(array());
				$configuration->setName($name);
				$configuration->setClass($param->getClass()->getName());
				$configurations[$name] = $configuration;
			} elseif (null === $configurations[$name]->getClass()) {
				$configurations[$name]->setClass($param->getClass()->getName());
			}

			$configurations[$name]->setIsOptional($param->isOptional());
		}

		return $configurations;
	}
}